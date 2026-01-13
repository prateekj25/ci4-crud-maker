<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MenuModel;
use App\Models\ModuleModel;
use CodeIgniter\API\ResponseTrait;

class MenuController extends BaseController
{
    use ResponseTrait;

    protected $menuModel;
    protected $moduleModel;

    public function __construct()
    {
        $this->menuModel = new MenuModel();
        $this->moduleModel = new ModuleModel();
    }

    public function index()
    {
        // Get all menus ordered by order
        $menus = $this->menuModel->orderBy('order', 'ASC')->findAll();

        return view('admin/menus/index', [
            'menus' => $menus,
            'title' => 'Manage Menus'
        ]);
    }

    public function new()
    {
        return view('admin/menus/create', [
            'title' => 'Create Menu',
            'parents' => $this->menuModel->where('parent_id', null)->findAll(),
            'modules' => $this->moduleModel->findAll()
        ]);
    }

    public function create()
    {
        $data = $this->request->getPost();

        // Handle empty values for foreign keys
        if (empty($data['parent_id']))
            $data['parent_id'] = null;
        if (empty($data['module_id']))
            $data['module_id'] = null;

        if (!$this->menuModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->menuModel->errors());
        }

        return redirect()->to('admin/menus')->with('message', 'Menu created successfully');
    }

    public function edit($id = null)
    {
        $menu = $this->menuModel->find($id);
        if (!$menu) {
            return redirect()->to('admin/menus')->with('error', 'Menu not found');
        }

        return view('admin/menus/edit', [
            'menu' => $menu,
            'title' => 'Edit Menu',
            'parents' => $this->menuModel->where('parent_id', null)->where('id !=', $id)->findAll(),
            'modules' => $this->moduleModel->findAll()
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();

        if (empty($data['parent_id']))
            $data['parent_id'] = null;
        if (empty($data['module_id']))
            $data['module_id'] = null;

        if (!$this->menuModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->menuModel->errors());
        }

        return redirect()->to('admin/menus')->with('message', 'Menu updated successfully');
    }

    public function delete($id = null)
    {
        if (!$this->menuModel->delete($id)) {
            return redirect()->to('admin/menus')->with('error', 'Failed to delete menu');
        }

        return redirect()->to('admin/menus')->with('message', 'Menu deleted successfully');
    }
}
