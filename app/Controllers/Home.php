<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ServiceModel;

// Was: index.php
class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title'    => 'Sun Son Solar | Power Your Future',
            // Only show what the Admin marked as available in Settings.
            'services' => (new ServiceModel())->where('is_available', 1)->findAll(),
            'products' => (new ProductModel())->where('is_available', 1)->findAll(),
        ];

        return view('home/index', $data);
    }

    // Footer "Privacy policy" and "Terms" links.
    public function legal()
    {
        return view('home/legal', ['title' => 'Privacy & Terms | Sun Son Solar']);
    }
}
