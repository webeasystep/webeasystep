<?php

namespace Modules\Videos\Controllers;

use App\Controllers\BaseController;
use App\Libraries\DtTable;
use Modules\Videos\Models\VideosModel;
use App\Libraries\FireUploader;
use CodeIgniter\HTTP\ResponseInterface;

class AdminVideos extends BaseController
{
    protected VideosModel   $videos;
    protected array         $rules;
    protected FireUploader  $fireUploader;

    public function __construct()
    {
        $this->fireUploader = new FireUploader();
        $this->videos       = new VideosModel();

        $this->rules = [
            "title_ar"  => ['label' => lang("Videos.title_ar"), 'rules' => "required"],
            "course_id"  => ['label' => lang("Videos.course_id"), 'rules' => "required"],
            "desc_ar"   => ['label' => lang("Videos.desc_ar"),  'rules' => "required"],
            "video_url" => ['label' => lang("Videos.video_url"), 'rules' => "required"],
        ];
    }

    public function index()
    {
        $data['title'] = lang('Videos.videos_List');

        if ($this->request->isAJAX()) {
            $videosModel = $this->videos
                ->select('id, title_ar, active, sort, show_in_home, created_at')
                ->orderBy('id', 'desc')
                ->builder();

            DtTable::hideColumns(['id']);
            DtTable::setColumnSwitch('active');
            DtTable::searchableColumns(['title_ar']);
            DtTable::orderableColumns(['title_ar','sort']);
            DtTable::setColumnImage('image');
            DtTable::setShowColumns("title_ar,active,sort,show_in_home");
            $output = DtTable::tableRender($videosModel, false);

            return $this->response->setJSON($output);
        }

        return view('index', $data);
    }

    public function add()
    {
        $data['title'] = lang("Admin.add_data");
        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                // Insert record and get the new ID
                $id = $this->data_arr();
                // Download & store YouTube thumbnail
                $this->handleYouTubeCode($id);
                $this->show_msg('success', lang("Admin.add_operation"), lang("Admin.add_success"));
                return redirect()->to(ADMIN_URL . "videos");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $data['videos'] = $this->videos->get_videos();
        return view("form", $data);
    }

    public function edit($id)
    {
        $data['title'] = lang("Admin.edit_data");

        if ($this->request->is('post')) {
            if ($this->validate($this->rules)) {
                // Update record
                $this->data_arr($id);
                // Download & store YouTube thumbnail
                $this->handleYouTubeCode($id);

                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->to(ADMIN_URL . "videos");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $data['videos'] = $this->videos->get_videos();
        $data['video']  = $this->videos->getById($id);
        return view('form', $data);
    }

    /**
     * Insert or update the "videos" table.
     *
     * @param int|null $id If null => insert, else update.
     * @return int The new or updated record's ID.
     */
    public function data_arr($id = null)
    {
        $builder = $this->db->table('videos');

        $data = [
            'video_url'   => $this->request->getPost('video_url'),
            'sort'        => $this->request->getPost('sort', FILTER_SANITIZE_NUMBER_INT),
            'show_in_home'=> $this->request->getPost('show_in_home') ? '1' : '0',
            'active'      => $this->request->getPost('active')      ? '1' : '0',
        ];

        // Localize fields
        $locales = ['ar', 'en'];
        foreach ($locales as $lng) {
            $data["title_$lng"] = $this->request->getPost("title_$lng");
            $data["desc_$lng"]  = $this->request->getPost("desc_$lng");
        }

        // Insert or update
        if ($id) {
            $builder->where('id', $id)->update($data);
        } else {
            $builder->insert($data);
            $id = $this->db->insertID();
        }

        return $id;
    }

    /**
     * Delete multiple records (AJAX).
     */
    public function delete(): ResponseInterface
    {
        if ($this->request->isAJAX()) {
            $table_name = $this->request->getPost('table');
            $ids        = $this->request->getPost('rows');
            $idsArray   = explode(',', $ids);
            $builder    = $this->db->table($table_name);

            if (count($idsArray) > 0) {
                $this->db->transStart();
                $builder->whereIn('id', $idsArray)->delete();
                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    return $this->response->setJSON([
                        'validation' => true,
                        'success'    => false,
                        'message'    => 'An error occurred during deletion'
                    ]);
                }

                return $this->response->setJSON([
                    'validation' => true,
                    'success'    => true,
                    'message'    => 'Deletion successful'
                ]);
            }

            return $this->response->setJSON([
                'validation' => true,
                'success'    => false,
                'message'    => 'No IDs provided for deletion'
            ]);
        }

        return $this->response->setJSON([
            'validation' => true,
            'success'    => false,
            'message'    => 'An error occurred during deletion'
        ]);
    }

    /**
     * Download the YouTube thumbnail & save as a local image.
     */
    private function handleYouTubeCode($id)
    {
        $video_url = $this->request->getPost('video_url');
        $yt_code   = $this->getYouTubeIdFromUrl($video_url);

        if ($yt_code !== '') {
            $update_data['code'] = $yt_code;

            // Build the thumbnail URL
            $image_url = "https://img.youtube.com/vi/{$yt_code}/hqdefault.jpg";

            // Suppress warnings & check for false (404 or no thumbnail)
            $imageContents = @file_get_contents($image_url);

            if ($imageContents !== false) {
                // Folder: public/uploads/videos/
                $uploadPath = FCPATH . 'uploads/videos/';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0777, true);
                }

                // Generate a unique filename
                $uniqueName = bin2hex(random_bytes(16)) . '.jpg';

                // Save file
                file_put_contents($uploadPath . $uniqueName, $imageContents);

                // Store relative path in DB
                $update_data['image'] = 'uploads/videos/' . $uniqueName;
            } else {
                // Optionally log or show a message that the thumbnail wasn’t found
                log_message('error', "YouTube thumbnail not found for video ID: {$yt_code}");
            }

            // Update the database
            $this->db->table('videos')
                ->where('id', $id)
                ->update($update_data);
        }
    }


    /**
     * Extract the YouTube video ID from a full URL.
     * e.g. https://www.youtube.com/watch?v=abc123 => "abc123"
     */
    private function getYouTubeIdFromUrl($url)
    {
        preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
        return $matches[1] ?? '';
    }
}
