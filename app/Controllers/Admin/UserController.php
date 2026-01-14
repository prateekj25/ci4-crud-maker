<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\RoleModel;
use App\Models\UserRoleModel;
use CodeIgniter\Shield\Entities\User;

class UserController extends BaseController
{
    protected $userModel;
    protected $roleModel;
    protected $userRoleModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->userRoleModel = new UserRoleModel();
    }

    public function index()
    {
        $users = $this->userModel->findAll();

        // Attach roles manually for display
        foreach ($users as $user) {
            $user->roles_list = $this->getUserRoles($user->id);
        }

        return view('admin/users/index', [
            'users' => $users,
            'title' => 'Manage Users'
        ]);
    }

    public function new()
    {
        return view('admin/users/create', [
            'title' => 'Create User',
            'roles' => $this->roleModel->findAll()
        ]);
    }

    public function create()
    {
        $data = $this->request->getPost();

        // 1. Create Shield User
        $user = new User([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
            'active' => 1 // Default active
        ]);

        if (!$this->userModel->save($user)) {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        $userId = $this->userModel->getInsertID();

        // 2. Assign Roles
        if (!empty($data['roles'])) {
            foreach ($data['roles'] as $roleId) {
                $this->userRoleModel->insert([
                    'user_id' => $userId,
                    'role_id' => $roleId
                ]);
            }
        }

        return redirect()->to('admin/users')->with('message', 'User created successfully');
    }

    public function edit($id = null)
    {
        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('admin/users')->with('error', 'User not found');
        }

        // Get current role IDs
        $currentRoles = $this->userRoleModel->where('user_id', $id)->findColumn('role_id') ?? [];

        return view('admin/users/edit', [
            'user' => $user,
            'title' => 'Edit User',
            'roles' => $this->roleModel->findAll(),
            'currentRoles' => $currentRoles
        ]);
    }

    public function update($id = null)
    {
        $data = $this->request->getPost();
        $user = $this->userModel->find($id);

        // Update basic info
        $user->fill([
            'username' => $data['username'],
            'email' => $data['email']
        ]);

        // Update password if provided
        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        if (!$this->userModel->save($user)) {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        // Update Roles (Sync: Delete all, then insert new)
        $this->userRoleModel->where('user_id', $id)->delete();

        if (!empty($data['roles'])) {
            foreach ($data['roles'] as $roleId) {
                $this->userRoleModel->insert([
                    'user_id' => $id,
                    'role_id' => $roleId
                ]);
            }
        }

        return redirect()->to('admin/users')->with('message', 'User updated successfully');
    }

    public function delete($id = null)
    {
        // Shield should handle user deletion, cascades to pivot if FK set correctly
        if (!$this->userModel->delete($id)) {
            return redirect()->to('admin/users')->with('error', 'Failed to delete user');
        }

        // Explicit pivot cleanup if not cascaded by DB (Safety net)
        $this->userRoleModel->where('user_id', $id)->delete();

        return redirect()->to('admin/users')->with('message', 'User deleted successfully');
    }

    private function getUserRoles($userId)
    {
        // Join to get role names
        $query = $this->userRoleModel->select('roles.title')
            ->join('roles', 'roles.id = users_roles.role_id')
            ->where('user_id', $userId)
            ->findAll();

        return array_column($query, 'title');
    }
}
