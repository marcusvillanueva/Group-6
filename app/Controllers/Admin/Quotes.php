<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\QuoteRequestModel;

// Free quote requests sent from the homepage.
class Quotes extends BaseController
{
    public function index()
    {
        return view('admin/quotes', [
            'title'  => 'Quote Requests | Sun Son Solar',
            'active' => 'quotes',
            'quotes' => (new QuoteRequestModel())
                ->orderBy("status = 'new'", 'DESC', false)
                ->orderBy('created_at', 'DESC')
                ->findAll(),
        ]);
    }

    public function markContacted(int $id)
    {
        (new QuoteRequestModel())->update($id, ['status' => 'contacted']);

        return redirect()->to(site_url('admin/quotes'))->with('success', 'Quote request #' . $id . ' marked as contacted.');
    }
}
