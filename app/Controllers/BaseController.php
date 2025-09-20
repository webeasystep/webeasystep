<?php

namespace App\Controllers;

use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Language\Language;
use CodeIgniter\Session\Session;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Database;
use Config\Services;

class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     * @var RequestInterface
     */

    public $request;
    public Session $session;
    public Language $language;
    public BaseConnection $db;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [ 'url', 'function', 'form', "validation",  'utils'];

    public function initController(RequestInterface $request, ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {

        parent::initController($request, $response, $logger);

        $this->db = Database::connect();
        $this->session = Services::session();
        $this->language = Services::language();
        $currentLang = $this->session->get('lang') ?? config("app")->defaultLocale;
        $this->session->set('lang', $currentLang);
        $this->language->setLocale($currentLang);
    }

    public function langSwitch(): RedirectResponse
    {
        $locale = $this->request->getLocale();
        $this->session->remove('lang');
        $this->session->set('lang', $locale);
        return redirect()->back();
    }

    function show_msg($type, $title, $text, $timeout = 2000)
    {
        $this->session->setFlashdata('msg_type', $type);
        $this->session->setFlashdata('msg_title', $title);
        $this->session->setFlashdata('msg_text', $text);
        $this->session->setFlashdata('msg_timeout', $timeout);
    }

    public function show($id): ResponseInterface
    {
        $table_name = $this->request->getPost('table');
        $columns = $this->request->getPost('columns');
        $all_columns = $columns == '' ? '*' : $columns;
        $module = $this->request->getPost('module');

        $array = $this->db->query("SELECT $all_columns from {$table_name} where id = $id ")->getRowArray();
        $new_array = array();
        // Replace the keys with the lang for this key
        foreach ($array as $key => $value) {
            if ($key !== 'id') {
                $langKey = "{$module}.{$key}";
                $langValue = lang($langKey);
                if ($langValue !== $langKey) {
                    $new_array[$langValue] = $value;
                } else {
                    $new_array[$langKey] = $value;
                }

                unset($new_array[$key]);
            }

        }

        $data = ['data' => $new_array];

        return $this->response->setJSON($data); // Return the user data as JSON
    }

    public function switchToggle(): ResponseInterface
    {
        if ($this->request->isAJAX()) {
            $table_name = $this->request->getPost('table');
            $columnName = $this->request->getPost('columnName');
            $whereName = $this->request->getPost('whereName') ?? 'id';
            $value = $this->request->getPost('value');
            $rowId = $this->request->getPost('rowId');
            $builder = $this->db->table($table_name);
            $builder->set($columnName, $value)
                ->where($whereName, $rowId)
                ->update();
            if ($this->db->affectedRows() > 0) {
                return $this->response->setJSON(['status' => 200, 'html' => 'تم التحديث بنجاح']);
            }
        }
        return $this->response->setJSON(['html' => 'حدث خطأ اثناء التحديث']);
    }

    public function delete(): ResponseInterface
    {
        if ($this->request->isAJAX()) {

            $table_name = $this->request->getPost('table');
            $ids = $this->request->getPost('rows'); // Get the IDs from the post data as a string
            $builder = $this->db->table($table_name);
            // Split the comma-separated string into an array of IDs
            $idsArray = explode(',', $ids);

            if (count($idsArray) > 0) {
                $builder->whereIn('id', $idsArray)->delete();
            } else {
                // Handle the case where no IDs are provided
                return $this->response->setJSON([
                    'validation' => true,
                    'success' => false,
                    'message' => 'لم يتم توفير أي معرف للحذف'
                ]);
            }

            if ($this->db->affectedRows() > 0) {
                return $this->response->setJSON(['validation' => true, 'success' => true, 'message' => 'تم الحذف بنجاح']);
            }

        }
        return $this->response->setJSON(['validation' => true, 'success' => false, 'message' => 'لقد حدث خطأ أثناء الحذف']);
    }


}
