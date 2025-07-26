<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Store;
use App\Models\Weapon;

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
        $stores = $this->currentUser->role === 'super_admin'
            ? Store::all()
            : [$this->currentUser->store];
        $this->view('users/create', ['stores' => $stores]);
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
        $stores = $this->currentUser->role === 'super_admin'
            ? Store::all()
            : [$this->currentUser->store];
        $this->view('users/edit', ['user' => $user, 'stores' => $stores]);
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

    public function show($id)
    {
        $this->authorize(['super_admin']); // only super can view arbitrary users
        $user = User::find($id);
        if (! $user) {
            http_response_code(404);
            exit;
        }
        // if user is a store_user, list that store’s weapons
        $weapons = [];
        if ($user->role === 'store_user' && $user->store_id) {
            $weapons = Weapon::all(['store_id' => $user->store_id]);
        }
        $this->view('users/show', [
          'user'    => $user,
          'weapons' => $weapons,
        ]);
    }

    public function destroy($id)
    {
        $this->authorize(['super_admin']);
        $user = User::find($id);
        $user->delete($this->currentUser->id); // Soft delete
        $this->redirect('/users');
    }
}