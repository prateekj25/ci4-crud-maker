<?php

namespace CodeIgniter\CLI {
    if (!class_exists('CodeIgniter\CLI\CLI')) {
        class CLI
        {
            public static function write($msg)
            {
                echo $msg . PHP_EOL;
            }
        }
    }
}

namespace CodeIgniter {
    if (!class_exists('CodeIgniter\Model')) {
        class Model
        {
        }
    }
}

namespace CodeIgniter\Database {
    if (!class_exists('CodeIgniter\Database\Migration')) {
        class Migration
        {
            protected $forge;
        }
    }
}

namespace {
    // Global Scope
    define('APPPATH', __DIR__ . '/../app/');
    define('ROOTPATH', __DIR__ . '/../');

    if (!function_exists('url_title')) {
        function url_title($str)
        {
            return strtolower($str);
        }
    }
    if (!function_exists('model')) {
        function model($name)
        {
            return new \stdClass();
        }
    }

    require_once __DIR__ . '/../app/Services/GeneratorService.php';
    use App\Services\GeneratorService;

    echo "Starting Manual Generator Test...\n";

    $generator = new GeneratorService();

    $data = [
        'module_name' => 'TestModule',
        'table_name' => 'test_modules',
        'controller_name' => 'TestModuleController',
        'fields' => [
            ['name' => 'title', 'type' => 'VARCHAR', 'length' => '255'],
            ['name' => 'count', 'type' => 'INT', 'length' => '11', 'nullable' => true],
        ]
    ];

    // 1. Test Generation
    echo "[1] Testing Generate...\n";
    try {
        $generator->generate($data);
        echo " - Service method called.\n";
    } catch (\Exception $e) {
        echo " - Error: " . $e->getMessage() . "\n";
        exit(1);
    }

    // Verify Files
    $files = [
        APPPATH . 'Controllers/Admin/TestModuleController.php',
        APPPATH . 'Models/TestModuleModel.php',
        APPPATH . 'Views/admin/testmodule/index.php',
        APPPATH . 'Views/admin/testmodule/form.php',
    ];

    $allCreated = true;
    foreach ($files as $file) {
        if (file_exists($file)) {
            echo " - OK: " . basename($file) . " created.\n";
        } else {
            echo " - FAIL: " . basename($file) . " NOT found.\n";
            $allCreated = false;
        }
    }

    // Check Migration (glob)
    $migrations = glob(APPPATH . 'Database/Migrations/*_CreateTestModuleTable.php');
    if (!empty($migrations)) {
        echo " - OK: Migration file created.\n";
    } else {
        echo " - FAIL: Migration file NOT found.\n";
        $allCreated = false;
    }

    // Check Route
    $routesContent = file_get_contents(APPPATH . 'Config/Routes.php');
    if (strpos($routesContent, "resource('testmodule'") !== false) {
        echo " - OK: Route entry added.\n";
    } else {
        echo " - FAIL: Route entry NOT found.\n";
        $allCreated = false;
    }

    if (!$allCreated) {
        echo "Generation FAILED.\n";
        exit(1);
    }

    // 2. Test Rollback
    echo "\n[2] Testing Rollback...\n";
    $generator->rollback($data);

    $allDeleted = true;
    foreach ($files as $file) {
        if (!file_exists($file)) {
            echo " - OK: " . basename($file) . " deleted.\n";
        } else {
            echo " - FAIL: " . basename($file) . " STILL EXISTS.\n";
            $allDeleted = false;
        }
    }

    // Check Migration
    $migrations = glob(APPPATH . 'Database/Migrations/*_CreateTestModuleTable.php');
    if (empty($migrations)) {
        echo " - OK: Migration file deleted.\n";
    } else {
        echo " - FAIL: Migration file STILL EXISTS.\n";
        $allDeleted = false;
    }

    // Check Route
    $routesContent = file_get_contents(APPPATH . 'Config/Routes.php');
    if (strpos($routesContent, "resource('testmodule'") === false) {
        echo " - OK: Route entry removed.\n";
    } else {
        echo " - FAIL: Route entry STILL EXISTS.\n";
        $allDeleted = false;
    }

    if ($allDeleted) {
        echo "\nSUCCESS: Generator Engine Verified!\n";
    } else {
        echo "\nFAILURE: Rollback incomplete.\n";
        exit(1);
    }
}
