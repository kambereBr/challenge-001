<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize(['super_admin']);
        $users = User::all();
        $this->view('users/index', ['users' => $users]);
    }

    public function create()
    {
        $this->authorize(['super_admin']);
        $this->view('users/create');
    }

    public function store()
    {
        $this->authorize(['super_admin']);
        $user = new User();
        $user->username = $_POST['username'];
        $user->password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user->role = $_POST['role'];
        $user->store_id = $_POST['store_id'] ?: null;
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        $user->save();
        $this->redirect('/users');
    }

    public function edit($id)
    {
        $this->authorize(['super_admin']);
        $user = User::find($id);
        $this->view('users/edit', ['user' => $user]);
    }

    public function update($id)
    {
        $this->authorize(['super_admin']);
        $user = User::find($id);
        $user->username = $_POST['username'];
        if (! empty($_POST['password'])) {
            $user->password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        $user->role = $_POST['role'];
        $user->store_id = $_POST['store_id'] ?: null;
        $user->updated_at = date('Y-m-d H:i:s');
        $user->save();
        $this->redirect('/users');
    }

    public function destroy($id)
    {
        $this->authorize(['super_admin']);
        $user = User::find($id);
        $user->delete($this->currentUser->id); // Soft delete
        $this->redirect('/users');
    }
}