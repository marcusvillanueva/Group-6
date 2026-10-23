<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\ServiceModel;

// Settings: configure service categories, pricing and availability,
// so the company can change them without needing a developer.
class Settings extends BaseController
{
    public function index()
    {
        return view('admin/settings', [
            'title'    => 'Settings | Sun Son Solar',
            'active'   => 'settings',
            'services' => (new ServiceModel())->orderBy('category')->orderBy('name')->findAll(),
            'products' => (new ProductModel())->orderBy('category')->orderBy('name')->findAll(),
        ]);
    }

    // POST admin/settings/{services|products}/save - add a new item or update one
    public function save(string $kind)
    {
        if (! in_array($kind, ['services', 'products'], true)) {
            return redirect()->to(site_url('admin/settings'));
        }

        $model      = $kind === 'services' ? new ServiceModel() : new ProductModel();
        $primaryKey = $kind === 'services' ? 'service_id' : 'product_id';
        $id         = (int) $this->request->getPost('id');

        $data = $this->request->getPost(['name', 'category', 'description', 'price']);
        $data = array_map(static fn ($value) => trim((string) $value), $data);

        $rules = [
            'name'        => ['rules' => "required|max_length[100]|is_unique[{$kind}.name,{$primaryKey},{$id}]", 'errors' => ['required' => 'Name is required.', 'is_unique' => 'That name already exists.']],
            'category'    => ['rules' => 'required|max_length[50]', 'errors' => ['required' => 'Category is required.']],
            'description' => ['rules' => 'permit_empty|max_length[255]'],
            'price'       => ['rules' => 'permit_empty|decimal', 'errors' => ['decimal' => 'Price must be a number, or leave it blank for "price on request".']],
        ];

        if (! $this->validateData($data, $rules)) {
            return redirect()->to(site_url('admin/settings') . '#' . $kind)->with('errors', $this->validator->getErrors());
        }

        $data['price']        = $data['price'] === '' ? null : $data['price'];
        $data['is_available'] = $this->request->getPost('is_available') ? 1 : 0;

        if ($id > 0) {
            $model->update($id, $data);
            $message = $data['name'] . ' was updated.';
        } else {
            $model->insert($data);
            $message = $data['name'] . ' was added.';
        }

        return redirect()->to(site_url('admin/settings') . '#' . $kind)->with('success', $message);
    }
}
