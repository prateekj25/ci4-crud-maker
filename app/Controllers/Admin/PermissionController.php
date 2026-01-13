<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PermissionModel;
use CodeIgniter\API\ResponseTrait;

class PermissionController extends BaseController
{
    use ResponseTrait;

    protected $permissionModel;

    public function __construct()
    {
        $this->permissionModel = new PermissionModel();
    }

    public function index()
    {
        $data = [
            'permissions' => $this->permissionModel->findAll(),
            'title' => 'Manage Permissions'
        ];

        return view('admin/permissions/index', $data);
    }

    public function new()
    {
        return view('admin/permissions/create', [
            'title' => 'Create Permission'
        ]);
    }

    public function create()
    {
        $data = $this->request->getPost();

        if (!$this->permissionModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->permissionModel->errors());
        }

        return redirect()->to('admin/permissions')->with('message', 'Permission created successfully');
    }

    public function edit($id = null)
    {
        $permission = $this->permissionModel->find($id);
        if (!$permission) {
            return redirect()->to('admin/permissions')->with('error', 'Permission not found');
        }

        return view('admin/permissions/edit', [
            'permission' => $permission,
            'title' => 'Edit Permission'
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();

        if (!$this->permissionModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->permissionModel->errors());
        }

        return redirect()->to('admin/permissions')->with('message', 'Permission updated successfully');
    }

    public function delete($id = null)
    {
        if (!$this->permissionModel->delete($id)) {
            return redirect()->to('admin/permissions')->with('error', 'Failed to delete permission');
        }

        return redirect()->to('admin/permissions')->with('message', 'Permission deleted successfully');
    }
}
