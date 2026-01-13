<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;

class ProfileController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        return view('admin/profile/index', [
            'user' => auth()->user(),
            'title' => 'My Profile'
        ]);
    }

    public function update()
    {
        $data = $this->request->getPost();
        $user = auth()->user();

        // Basic Info
        if (!empty($data['username']))
            $user->username = $data['username'];
        if (!empty($data['email']))
            $user->email = $data['email'];

        // Password change
        if (!empty($data['password'])) {
            $user->password = $data['password'];
        }

        if (!$this->userModel->save($user)) {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        return redirect()->to('admin/profile')->with('message', 'Profile updated successfully');
    }
}
