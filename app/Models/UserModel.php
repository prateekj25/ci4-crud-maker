<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected function initialize(): void
    {
        parent::initialize();

        // Merge allowed fields if you need to add custom columns to 'users' table
        $this->allowedFields = array_merge($this->allowedFields, [
            // 'first_name', 'last_name', etc if you added them
        ]);
    }
}
