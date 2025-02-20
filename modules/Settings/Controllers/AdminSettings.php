<?php

namespace Modules\Settings\Controllers;

use App\Controllers\BaseController;
use Modules\Settings\Models\SettingsModel;
use App\Libraries\FireUploader;

class AdminSettings extends BaseController
{
    private SettingsModel $settings;
    public FireUploader $fireUploader;
    public array $rules;
    public ?object $uri;

    public function __construct()
    {
        $this->fireUploader = new FireUploader();
        $this->settings = new SettingsModel();
        $this->uri = service('uri');
    }

    public function index()
    {
        $data['title'] = lang('Settings.settings');
        $data['is_active'] = $this->uri->getSegment(3) == "general_panel" ? "active" : "";
        $inputs = $this->request->getPost();
        // Update settings in the database
        if ($this->request->is('post')) {

            // Update settings in the database
            foreach ($inputs as $key => $value) {
                setting("App.$key", "$value");
            }
            $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
            return redirect()->back();
        }

        return view('index', $data);
    }

    public function change_password_panel()
    {

        $data['title'] = lang("Admin.add_data");
        $data['is_active'] = $this->uri->getSegment(3) == "change_password_panel" ? "active" : "";

        if ($this->request->is('post')) {

            // if the profile photo is updated
            // $this->rules['mobile'] = "required|is_unique[pages.mobile,id,$id]";

            if ($this->validate($this->rules)) {
                $data = [
                    'page_link' => $this->request->getPost('page_link', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
                    'sort' => $this->request->getPost('show_home') ? 1 : 0,
                    'show_home' => $this->request->getPost('show_home') ? 1 : 0,
                    'active' => $this->request->getPost('active') ? 1 : 0,
                ];

                // Retrieve the supported locales
                $locales = $this->request->config->supportedLocales;
                foreach ($locales as $lng) {
                    foreach ($locales as $lng) {
                        $data["title_$lng"] = $this->request->getPost("title_$lng");
                        $data["desc_$lng"] = $this->request->getPost("desc_$lng");
                        $data["content_$lng"] = $this->request->getPost("content_$lng");
                    }
                }

                // add new page data
                $this->settings->save($data);
                // Print the updated query
                /*  $query = $this->setting->getLastQuery()->getQuery();
                echo "Updated Query: " . $query;*/

                if (!empty($id)) { // Check if $id is not empty
                    $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                } else {
                    // Handle error when $id is empty
                    $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
                }
                return redirect()->to(ADMIN_URL . "pages");
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }

        $data['settings'] = $this->settings->findAll(); // Fetch the page data by ID

        //  $data['files'] = json_decode($data['settings']->images, true);
        return View("Modules\Settings\Controllers\AdminSettings::change_password_panel", $data);
    }

}
