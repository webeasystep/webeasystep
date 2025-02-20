<?php
namespace Modules\Videos\Controllers;
use App\Controllers\BaseController;
use CodeIgniter\Exceptions\PageNotFoundException;
use Modules\Videos\Models\VideosModel;


class Videos extends BaseController
{
    public VideosModel $videos;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->videos = new VideosModel();
    }


    public function index(): string
    {
        // Example: Fetch all active videos from the DB
        $videos = $this->db->table('videos')
            ->where('active', 1)
            ->orderBy('sort', 'ASC')
            ->get()
            ->getResultArray();

        return view('site/index', [
            'title'  => lang('Videos.Videos'),
            'videos' => $videos // Pass as 'videos' to match the view code
        ]);
    }

    /**
     * Builds ordered list of video IDs based on hierarchy
     */
     function show_page($id): string
     {
        // Get the page content from the database
        $product = $this->videos->getById($id);

        $product_images = $this->videos->getproductImages($id);

        // If the product does not exist, return a 404 error
        if (!$product) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => $product['title_'.lang('Site.lang')],
            'product_images' => $product_images,
            'product' => $product,
        ];
         return view('site/show', $data);
    }



}
