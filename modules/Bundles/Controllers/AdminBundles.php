<?php

namespace Modules\Bundles\Controllers;

use App\Controllers\BaseController;
use Modules\Bundles\Models\BundlesModel;
use Modules\Courses\Models\CoursesModel;

class AdminBundles extends BaseController
{
    protected BundlesModel $bundlesModel;
    protected CoursesModel $coursesModel;

    public function __construct()
    {
        $this->bundlesModel = new BundlesModel();
        $this->coursesModel = new CoursesModel();
    }

    /**
     * List all bundles
     */
    public function index()
    {
        $data = [
            'title' => 'إدارة الباقات',
            'bundles' => $this->bundlesModel->orderBy('sort_order', 'ASC')->orderBy('id', 'DESC')->findAll(),
        ];
        return AdminView('Modules\Bundles\Views\Admin\index', $data);
    }

    /**
     * Add new bundle
     */
    public function add()
    {
        if ($this->request->is('post')) {
            $rules = [
                'bundle_title' => 'required|min_length[3]|max_length[255]',
                'slug'         => 'required|alpha_dash|is_unique[tb_bundles.slug]',
                'bundle_price' => 'required|numeric',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $imageFile = $this->request->getFile('image');
            $imageName = null;
            if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
                $imageName = $imageFile->getRandomName();
                $imageFile->move(FCPATH . 'uploads/courses', $imageName);
            }

            $bundleId = $this->bundlesModel->insert([
                'bundle_title' => $this->request->getPost('bundle_title'),
                'slug'         => $this->request->getPost('slug'),
                'description'  => $this->request->getPost('description'),
                'image'        => $imageName,
                'bundle_price' => $this->request->getPost('bundle_price'),
                'is_active'    => $this->request->getPost('is_active') ? 1 : 0,
                'sort_order'   => (int) $this->request->getPost('sort_order'),
            ]);

            // Add courses
            $courseIds = $this->request->getPost('courses') ?? [];
            $this->bundlesModel->setCourses($bundleId, $courseIds);

            return redirect()->to('/admin/bundles')->with('success', 'تم إضافة الباقة بنجاح');
        }

        $data = [
            'title'   => 'إضافة باقة جديدة',
            'courses' => $this->coursesModel->where('active', 1)->findAll(),
        ];
        return AdminView('Modules\Bundles\Views\Admin\form', $data);
    }

    /**
     * Edit bundle
     */
    public function edit($id)
    {
        $bundle = $this->bundlesModel->find($id);
        if (!$bundle) {
            return redirect()->to('/admin/bundles')->with('error', 'الباقة غير موجودة');
        }

        if ($this->request->is('post')) {
            $rules = [
                'bundle_title' => 'required|min_length[3]|max_length[255]',
                'slug'         => "required|alpha_dash|is_unique[tb_bundles.slug,id,$id]",
                'bundle_price' => 'required|numeric',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $imageName = $bundle->image;
            $imageFile = $this->request->getFile('image');
            if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
                $imageName = $imageFile->getRandomName();
                $imageFile->move(FCPATH . 'uploads/courses', $imageName);
            }

            $this->bundlesModel->update($id, [
                'bundle_title' => $this->request->getPost('bundle_title'),
                'slug'         => $this->request->getPost('slug'),
                'description'  => $this->request->getPost('description'),
                'image'        => $imageName,
                'bundle_price' => $this->request->getPost('bundle_price'),
                'is_active'    => $this->request->getPost('is_active') ? 1 : 0,
                'sort_order'   => (int) $this->request->getPost('sort_order'),
            ]);

            // Update courses
            $courseIds = $this->request->getPost('courses') ?? [];
            $this->bundlesModel->setCourses($id, $courseIds);

            return redirect()->to('/admin/bundles')->with('success', 'تم تعديل الباقة بنجاح');
        }

        $data = [
            'title'   => 'تعديل باقة: ' . $bundle->bundle_title,
            'bundle'  => $bundle,
            'courses' => $this->coursesModel->where('active', 1)->findAll(),
            'selectedCourses' => $this->bundlesModel->getBundleCourseIds($id),
        ];
        return AdminView('Modules\Bundles\Views\Admin\form', $data);
    }

    /**
     * Delete bundle
     */
    public function delete($id)
    {
        $this->bundlesModel->delete($id);
        return redirect()->to('/admin/bundles')->with('success', 'تم حذف الباقة بنجاح');
    }
}
