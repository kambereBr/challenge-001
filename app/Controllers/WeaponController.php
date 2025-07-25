<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Weapon;
use App\Models\Store;

class WeaponController extends Controller
{
    public function index()
    {
        $weapons = Weapon::allForUser($this->currentUser);
        $this->view('weapons/index', ['weapons' => $weapons]);
    }

    public function create()
    {
        $this->authorize(['super_admin', 'store_user']);
        $stores = $this->currentUser->role === 'super_admin'
            ? Store::all()
            : [$this->currentUser->store];
        $this->view('weapons/create', ['stores' => $stores]);
    }

    public function store()
    {
        $this->authorize(['super_admin', 'store_user']);
        $weapon = new Weapon();
        $weapon->store_id    = $this->currentUser->role === 'super_admin'
                             ? $_POST['store_id']
                             : $this->currentUser->store_id;
        $weapon->name = $_POST['name'];
        $weapon->type = $_POST['type'];
        $weapon->caliber = $_POST['caliber'] ?: null;
        $weapon->serial_number = $_POST['serial_number'];
        $weapon->price = $_POST['price'] ?: 0.0;
        $weapon->in_stock = $_POST['in_stock'] ?: 0;
        $weapon->status = $_POST['status'] ?: 'available';
        $weapon->created_at = date('Y-m-d H:i:s');
        $weapon->updated_at = date('Y-m-d H:i:s');
        $weapon->save();
        $this->redirect('/weapons');
    }

    public function edit($id)
    {
        $weapon = Weapon::findForUser($id, $this->currentUser);
        if (! $weapon) {
            http_response_code(403);
            exit;
        }
        $stores = $this->currentUser->role === 'super_admin'
            ? Store::all()
            : [$this->currentUser->store];
        $this->view('weapons/edit', ['weapon' => $weapon, 'stores' => $stores]);
    }

    public function update($id)
    {
        $weapon = Weapon::findForUser($id, $this->currentUser);
        if (! $weapon) {
            http_response_code(403);
            exit;
        }
        $weapon->name = $_POST['name'];
        $weapon->type = $_POST['type'];
        $weapon->caliber = $_POST['caliber'] ?: null;
        $weapon->serial_number = $_POST['serial_number'];
        $weapon->price = $_POST['price'] ?: 0.0;
        $weapon->in_stock = $_POST['in_stock'] ?: 0;
        $weapon->status = $_POST['status'] ?: 'available';
        $weapon->updated_at = date('Y-m-d H:i:s');
        $weapon->save();
        $this->redirect('/weapons');
    }

    public function destroy($id)
    {
        $this->authorize(['super_admin', 'store_user']);
        $weapon = Weapon::findForUser($id, $this->currentUser);
        if (! $weapon) {
            http_response_code(403);
            exit;
        }
        $weapon->delete($this->currentUser->id);
        $this->redirect('/weapons');
    }
}
