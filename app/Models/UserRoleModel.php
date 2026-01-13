<?php

namespace App\Models;

use CodeIgniter\Model;

class UserRoleModel extends Model
{
    protected $table = 'users_roles';
    protected $primaryKey = ''; // Pivot table has no single primary key
    protected $useAutoIncrement = false;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['user_id', 'role_id'];

    // Validation
    protected $validationRules = [
        'user_id' => 'required|integer',
        'role_id' => 'required|integer',
    ];
}
