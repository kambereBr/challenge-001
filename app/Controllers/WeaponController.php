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
        $ids = array_unique(array_column($weapons, 'store_id'));
        $storesRaw = Store::whereIn('id', $ids);
        // Convert to associative array for easier access
        $stores = [];
        foreach ($storesRaw as $s) {
            $stores[$s->id] = $s;
        }
        $this->view('weapons/index', ['weapons' => $weapons, 'stores' => $stores]);
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
        $this->verifyCsrf();

        $name = trim($_POST['name'] ?? '');
        $type = trim($_POST['type'] ?? '');
        $caliber = trim($_POST['caliber'] ?? '');
        $serialNumber = trim($_POST['serial_number'] ?? '');
        $price = trim($_POST['price'] ?? 0.0);
        $inStock = trim($_POST['in_stock'] ?? 0);
        $status = trim($_POST['status'] ?? 'available');
        $storeId = $this->currentUser->role === 'super_admin'
            ? $_POST['store_id']
            : $this->currentUser->store_id;

        // Validate required fields
        $errors = $this->validate($_POST, [
            'name' => ['required', 'max:255'],
            'type' => ['required', 'max:100'],
            'caliber' => ['required', 'max:100'],
            'serial_number' => ['required', 'max:100', 'unique:weapons,serial_number'],
            'price' => ['required', 'numeric', 'min:0'],
            'in_stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'in:available,out_of_stock,discontinued'],
            'store_id' => ['required', 'exists:stores,id'],
        ]);
        if ($errors) {
            $this->setError(implode(' ', $errors));
            return $this->view('weapons/create', ['old' => $_POST]);
        }

        try {
            $weapon = new Weapon();
            $weapon->store_id = $storeId;
            $weapon->name = $name;
            $weapon->type = $type;
            $weapon->caliber = $caliber;
            $weapon->serial_number = $serialNumber;
            $weapon->price = $price;
            $weapon->in_stock = $inStock;
            $weapon->status = $status;
            $weapon->created_at = date('Y-m-d H:i:s');
            $weapon->updated_at = date('Y-m-d H:i:s');
            $weapon->save();
        } catch (\Exception $e) {
            $this->setError('Failed to create weapon: ' . $e->getMessage());
            return $this->view('weapons/create', ['old' => $_POST]);
        }

        $this->setSuccess('Weapon "' . htmlspecialchars($name) . '" created successfully.');
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
        $this->verifyCsrf();
        $errors = $this->validate($_POST, [
            'name' => ['required', 'max:255'],
            'type' => ['required', 'max:100'],
            'caliber' => ['max:100'],
            'serial_number' => ['required', 'max:100'],
            'price' => ['numeric', 'min:0'],
            'in_stock' => ['integer', 'min:0'],
            'status' => ['in:available,out_of_stock,discontinued'],
            'store_id' => ['required', 'exists:stores,id'],
        ]);
        if ($errors) {
            $stores = $this->currentUser->role === 'super_admin'
                ? Store::all()
                : [$this->currentUser->store];
            $this->setError(implode(' ', $errors));
            return $this->view('weapons/edit', ['weapon' => $weapon, 'old' => $_POST, 'stores' => $stores]);
        }

        try {
            $weapon->store_id = $_POST['store_id'];
            $weapon->name = $_POST['name'];
            $weapon->type = $_POST['type'];
            $weapon->caliber = $_POST['caliber'] ?: null;
            $weapon->serial_number = $_POST['serial_number'];
            $weapon->price = $_POST['price'] ?: 0.0;
            $weapon->in_stock = $_POST['in_stock'] ?: 0;
            $weapon->status = $_POST['status'] ?: 'available';
            $weapon->updated_at = date('Y-m-d H:i:s');
            $weapon->save();
        } catch (\Exception $e) {
            // Handle database errors
            $this->setError('Failed to update weapon: ' . $e->getMessage());
            return $this->view('weapons/edit', ['weapon' => $weapon, 'old' => $_POST]);
        }
        
        $this->setSuccess('Weapon "' . htmlspecialchars($weapon->name) . '" updated successfully.');
        $this->redirect('/weapons');
    }

    public function show($id)
    {
        $weapon = Weapon::findForUser($id, $this->currentUser);
        if (! $weapon) {
            http_response_code(403);
            exit;
        }
        // also get its store
        $store = $weapon->store();
        $this->view('weapons/show', [
          'weapon' => $weapon,
          'store'  => $store,
        ]);
    }

    public function destroy($id)
    {
        $this->authorize(['super_admin', 'store_user']);
        $this->verifyCsrf();

        try {
            $weapon = Weapon::findForUser($id, $this->currentUser);
            if (! $weapon) {
                http_response_code(403);
                exit;
            }
            $weapon->delete($this->currentUser->id);
        } catch (\Exception $e) {
            $this->setError('Failed to delete weapon: ' . $e->getMessage());
            return $this->redirect('/weapons');
        }

        $this->setSuccess('Weapon "' . htmlspecialchars($weapon->name) . '" deleted successfully.');
        $this->redirect('/weapons');
    }
}
