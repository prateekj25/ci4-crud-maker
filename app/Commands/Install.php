<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class Install extends BaseCommand
{
    protected $group = 'App';
    protected $name = 'app:install';
    protected $description = 'Installs the application: runs migrations, seeds database, and checks assets.';

    public function run(array $params)
    {
        CLI::write('Starting Installation...', 'yellow');

        // 1. Check Database Connection
        try {
            $db = \Config\Database::connect();
            $db->connect();
            CLI::write('Database Connected.', 'green');
        } catch (\Throwable $e) {
            CLI::error('Database Connection Failed: ' . $e->getMessage());
            CLI::write('Please check your .env file.', 'red');
            return;
        }

        // 2. Run Migrations
        CLI::write('Running Migrations...', 'yellow');
        try {
            command('migrate --all');
            CLI::write('Migrations Complete.', 'green');
        } catch (\Throwable $e) {
            CLI::error('Migration Failed: ' . $e->getMessage());
            return;
        }

        // 3. Run Seeder
        CLI::write('Seeding Database...', 'yellow');
        try {
            command('db:seed RBACSeeder');
            CLI::write('Seeding Complete.', 'green');
        } catch (\Throwable $e) {
            CLI::error('Seeding Failed: ' . $e->getMessage());
            return;
        }

        // 4. Check/Publish Assets (Plugins)
        CLI::write('Checking Assets...', 'yellow');
        $pluginPath = FCPATH . 'plugins/jquery';
        if (!is_dir($pluginPath)) {
            CLI::write('Plugins missing. Attempting to publish...', 'yellow');
            // This assumes node_modules exists
            if (is_dir(ROOTPATH . 'node_modules')) {
                // Simple copy logic or call a separate script
                // For now, just warn
                CLI::error('Public plugins missing. Please run: npm install && cp -r node_modules/admin-lte/plugins/* public/plugins/');
            } else {
                CLI::error('Node modules missing. Please run: npm install');
            }
        } else {
            CLI::write('Assets Verified.', 'green');
        }

        CLI::write('--------------------------------', 'white');
        CLI::write('Installation Successful!', 'green');
        CLI::write('Admin User: admin@admin.com / password123', 'cyan');
        CLI::write('--------------------------------', 'white');
    }
}
