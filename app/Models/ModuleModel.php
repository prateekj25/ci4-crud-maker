<?php

namespace App\Models;

use CodeIgniter\Model;

class ModuleModel extends Model
{
    protected $table = 'modules';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['module_name', 'module_slug', 'table_name', 'controller_name', 'icon', 'is_active'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'module_name' => 'required|max_length[255]',
        'module_slug' => 'required|max_length[255]|is_unique[modules.module_slug,id,{id}]',
        'table_name' => 'required|max_length[255]',
    ];
}
