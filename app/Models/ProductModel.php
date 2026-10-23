<?php

namespace App\Models;

use CodeIgniter\Model;

// Products shown on the homepage (Admin > Settings).
class ProductModel extends Model
{
    protected $table         = 'products';
    protected $primaryKey    = 'product_id';
    protected $returnType    = 'array';
    protected $allowedFields = ['name', 'category', 'description', 'price', 'is_available'];
}
