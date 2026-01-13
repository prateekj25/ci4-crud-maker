<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RoleModel;
use App\Models\PermissionModel;
use CodeIgniter\API\ResponseTrait;

class RoleController extends BaseController
{
    use ResponseTrait;

    protected $roleModel;
    protected $permissionModel;

    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->permissionModel = new PermissionModel();
    }

    public function index()
    {
        $data = [
            'roles' => $this->roleModel->findAll(),
            'title' => 'Manage Roles'
        ];

        return view('admin/roles/index', $data);
    }

    public function new()
    {
        return view('admin/roles/create', [
            'title' => 'Create Role',
            'permissions' => $this->permissionModel->findAll()
        ]);
    }

    public function create()
    {
        $data = $this->request->getPost();

        if (!$this->roleModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->roleModel->errors());
        }

        // TODO: Handle permission assignment via pivot table

        return redirect()->to('admin/roles')->with('message', 'Role created successfully');
    }

    public function edit($id = null)
    {
        $role = $this->roleModel->find($id);
        if (!$role) {
            return redirect()->to('admin/roles')->with('error', 'Role not found');
        }

        return view('admin/roles/edit', [
            'role' => $role,
            'title' => 'Edit Role',
            'permissions' => $this->permissionModel->findAll()
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();

        if (!$this->roleModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->roleModel->errors());
        }

        return redirect()->to('admin/roles')->with('message', 'Role updated successfully');
    }

    public function delete($id = null)
    {
        if (!$this->roleModel->delete($id)) {
            return redirect()->to('admin/roles')->with('error', 'Failed to delete role');
        }

        return redirect()->to('admin/roles')->with('message', 'Role deleted successfully');
    }
}
