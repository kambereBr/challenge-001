<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\Store;
use Core\PDFService;

class StoreController extends Controller
{
    public function index()
    {
        if ($this->currentUser->role === 'super_admin') {
            $stores = Store::all();
        } else {
            $stores = Store::all(['id' => $this->currentUser->store_id]);
        }
        $totalWeapons = [];
        foreach ($stores as $store) {
            $totalWeapons[$store->id] = count($store->weapons());
        }
        $this->view('stores/index', ['stores' => $stores, 'totalWeapons' => $totalWeapons]);
    }

    public function create()
    {
        $this->authorize(['super_admin']);
        $this->view('stores/create');
    }

    public function store()
    {
        $this->authorize(['super_admin']);
        $this->verifyCsrf();

        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $addressLine1 = trim($_POST['address_line1'] ?? '');
        $addressLine2 = trim($_POST['address_line2'] ?? '');
        $city = trim($_POST['city'] ?? '');
        $stateRegion = trim($_POST['state_region'] ?? '');
        $country = trim($_POST['country'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');

        // Validate required fields
        $errors = $this->validate($_POST, [
            'name' => ['required','max:255','unique:stores,name'],
            'slug' => ['required','alpha_dash','unique:stores,slug'],
            'address_line1' => ['required','max:255'],
            'country' => ['required','max:100'],
            'phone' => ['required','max:20'],
            'email' => ['required','email','max:255'],
        ]);
        if ($errors) {
            $this->setError(implode(' ', $errors));
            return $this->view('stores/create', ['old'=>$_POST]);
        }

        try {
            $store = new Store();
            $store->name = $name;
            $store->slug = $slug;
            $store->address_line1 = $addressLine1;
            $store->address_line2 = $addressLine2;
            $store->city = $city;
            $store->state_region = $stateRegion;
            $store->country = $country;
            $store->phone = $phone;
            $store->email = $email;
            $store->created_at = date('Y-m-d H:i:s');
            $store->updated_at = date('Y-m-d H:i:s');
            $store->save();
        } catch (\PDOException $e) {
            // Handle database errors
            $this->setError('Failed to create store: ' . $e->getMessage());
            return $this->view('stores/create', ['old' => $_POST]);
        }
        

        $this->setSuccess('Store “'.htmlspecialchars($name).'” created successfully.');
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
        $this->verifyCsrf();
        $errors = $this->validate($_POST, [
            'name' => ['required','max:255'],
            'slug' => ['required','alpha_dash'],
            'address_line1' => ['required','max:255'],
            'country' => ['required','max:100'],
            'phone' => ['required','max:20'],
            'email' => ['required','email','max:255'],
        ]);
        if ($errors) {
            $this->setError(implode(' ', $errors));
            return $this->view('stores/edit', ['store' => $store, 'old' => $_POST]);
        }

        try {
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
        } catch (\PDOException $e) {
            // Handle database errors
            $this->setError('Failed to update store: ' . $e->getMessage());
            return $this->view('stores/edit', ['store' => $store, 'old' => $_POST]);
        }

        $this->setSuccess('Store “'.htmlspecialchars($store->name).'” updated successfully.');
        $this->redirect('/stores');
    }

    public function show($id)
    {
        $store = Store::findForUser($id, $this->currentUser);
        if (! $store) {
            http_response_code(403);
            exit;
        }
        // fetch all weapons for this store
        $weapons = $store->weapons();
        $this->view('stores/show', [
          'store'   => $store,
          'weapons' => $weapons,
        ]);
    }

    public function destroy($id)
    {
        $this->authorize(['super_admin']);
        $this->verifyCsrf();
        try {
            $store = Store::find($id);
            if (! $store) {
                http_response_code(404);
                exit;
            }
            $store->delete($this->currentUser->id);
        } catch (\PDOException $e) {
            $this->setError('Failed to delete store: ' . $e->getMessage());
            $this->redirect('/stores');
        }

        $this->setSuccess('Store “'.htmlspecialchars($store->name).'” deleted successfully.');       
        $this->redirect('/stores');
    }

    public function pdf($id)
    {
        $store = Store::findForUser($id, $this->currentUser);
        if (! $store) {
            $this->setError('Store not found for PDF generation.');
            return $this->redirect('/stores');
        }
        $weapons = $store->weapons();
        PDFService::detail(
            "store_{$store->id}.pdf",
            "Store Details Report: {$store->name}",
            [
                'Name' => $store->name,
                'Slug' => $store->slug,
                'Address' => $store->address_line1 . ($store->address_line2 ? ' ' . $store->address_line2 : ''),
                'City' => $store->city,
                'State/Region' => $store->state_region,
                'Country' => $store->country,
                'Phone' => $store->phone,
                'Email' => $store->email,
                'Total Weapons' => count($weapons),
            ],
            $this->currentUser,
            'Weapons in this Store',
            ['ID','Name','Type','Caliber','Serial Number','Price', 'In Stock', 'Status'],
            array_map(function($w) {
                return [
                    $w->id,
                    htmlspecialchars($w->name),
                    htmlspecialchars($w->type),
                    htmlspecialchars($w->caliber),
                    htmlspecialchars($w->serial_number),
                    '$'.number_format($w->price,2),
                    htmlspecialchars($w->in_stock),
                    htmlspecialchars($w->status),
                ];
            }, $store->weapons())
        );
    }

    // Bulk PDF of all stores
    public function pdfAll()
    {
        $stores = Store::allForUser($this->currentUser);
        if (count($stores) === 0) {
            $this->setError('No stores found for PDF generation.');
            return $this->redirect('/stores');
        }
        PDFService::list(
            'stores_report.pdf',
            'All Stores Report',
            ['#','Name','Slug','Address','City','State/Region','Country','Phone','Email', 'Total Weapons'],
            array_map(function($s, $id) {
                return [
                    $id + 1,
                    htmlspecialchars($s->name),
                    htmlspecialchars($s->slug),
                    htmlspecialchars($s->address_line1 . ($s->address_line2 ? ' ' . $s->address_line2 : '')),
                    htmlspecialchars($s->city),
                    htmlspecialchars($s->state_region),
                    htmlspecialchars($s->country),
                    htmlspecialchars($s->phone),
                    htmlspecialchars($s->email),
                    count($s->weapons()),
                ];
            }, $stores, array_keys($stores)),
            $this->currentUser
        );
    }
}