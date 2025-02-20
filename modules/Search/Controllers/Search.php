<?php


use App\Controllers\BaseController;
use Modules\Search\Models\SearchModel;

class Search extends BaseController
{
    public SearchModel $searchModel;
    protected $rules;
    public $session;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->searchModel = new SearchModel();
        $this->rules = [
            'name' => 'required',
            'email' => 'trim|required|valid_email',
            'phone' => 'required|is_unique[contact_us.phone,id,{id}]',
            'subject' => 'required',
            'message' => 'required',
        ];
    }

    /**
     * @throws \Exception
     */

    function index()
    {
        $data['title'] = lang('Search.Search');

        if ($this->request->is('post')) {

            if ($this->validate($this->rules)) {
                $data = [
                    "module_name" => "الرحلات",
                    "name" => $this->request->getPost("name"),
                    "email" => $this->request->getPost("email"),
                    "send_to" => setting('App.contact_email'),
                    "subject" => $this->request->getPost("subject"),
                    "message" => $this->request->getPost("message"),
                    "msg_date" => date("Y-m-d H:i:s"),
                ];
                // add new user data
                $this->searchModel->save($data);
                $this->show_msg('success', lang("Admin.edit"), lang("Admin.edit_success"));
                return redirect()->back();
            } else {
                $this->show_msg('danger', lang("Admin.validation_errors"), validation_errors());
            }
        }
        // "Modules\Search\Controllers\Search::add"
        return view('index', $data);
    }


}
