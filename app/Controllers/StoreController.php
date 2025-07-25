<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Store;

class StoreController extends Controller
{
    public function index()
    {
        if ($this->currentUser->role === 'super_admin') {
            $stores = Store::all();
        } else {
            $stores = Store::all(['id' => $this->currentUser->store_id]);
        }
        $this->view('stores/index', ['stores' => $stores]);
    }

    public function create()
    {
        $this->authorize(['super_admin']);
        $this->view('stores/create');
    }

    public function store()
    {
        $this->authorize(['super_admin']);
        $store = new Store();
        $store->name = $_POST['name'];
        $store->slug = $_POST['slug'];
        $store->address_line1 = $_POST['address_line1'];
        $store->address_line2 = $_POST['address_line2'] ?: null;
        $store->city = $_POST['city'];
        $store->state_region = $_POST['state_region'];
        $store->country = $_POST['country'];
        $store->phone = $_POST['phone'];
        $store->email = $_POST['email'];
        $store->created_at = date('Y-m-d H:i:s');
        $store->updated_at = date('Y-m-d H:i:s');
        $store->save();
        $this->redirect('/stores');
    }

    public function edit($id)
    {
        $store = Store::findForUser($id, $this->currentUser);
        if (! $store) {
            http_response_code(403); exit;
        }
        $this->view('stores/edit', ['store' => $store]);
    }

    public function update($id)
    {
        $store = Store::findForUser($id, $this->currentUser);
        if (! $store) {
            http_response_code(403); exit;
        }
        $store->name = $_POST['name'];
        $store->slug = $_POST['slug'];
        $store->address_line1 = $_POST['address_line1'];
        $store->address_line2 = $_POST['address_line2'] ?: null;
        $store->city = $_POST['city'];
        $store->state_region = $_POST['state_region'];
        $store->country = $_POST['country'];
        $store->phone = $_POST['phone'];
        $store->email = $_POST['email'];
        $store->updated_at = date('Y-m-d H:i:s');
        $store->save();
        $this->redirect('/stores');
    }

    public function destroy($id)
    {
        $this->authorize(['super_admin']);
        $store = Store::find($id);
        $store->delete($this->currentUser->id);
        $this->redirect('/stores');
    }
}