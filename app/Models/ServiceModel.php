<?php

namespace App\Models;

use CodeIgniter\Model;

// Services the company offers (Admin > Settings).
class ServiceModel extends Model
{
    protected $table         = 'services';
    protected $primaryKey    = 'service_id';
    protected $returnType    = 'array';
    protected $allowedFields = ['name', 'category', 'description', 'price', 'is_available'];
}
