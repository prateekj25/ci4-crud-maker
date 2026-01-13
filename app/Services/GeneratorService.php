<?php

namespace App\Services;

use CodeIgniter\CLI\CLI;

class GeneratorService
{
    private $moduleName;
    private $tableName;
    private $controllerName;
    private $fields;

    public function generate($data)
    {
        $this->moduleName = ucfirst($data['module_name']); // e.g. Products
        $this->tableName = strtolower($data['table_name']); // e.g. products
        $this->controllerName = ucfirst($data['controller_name']); // e.g. ProductController
        $this->fields = $data['fields'];

        $this->createMigration();
        $this->createModel();
        $this->createController();
        $this->createViews();
        $this->updateRoutes();

        return true;
    }

    private function createMigration()
    {
        $migrationName = gmdate('Y-m-d-His') . '_Create' . $this->moduleName . 'Table';
        $path = APPPATH . 'Database/Migrations/' . $migrationName . '.php';

        $fieldStrings = "";
        foreach ($this->fields as $field) {
            $type = strtoupper($field['type']);
            $constraint = $field['length'] ? "'constraint' => {$field['length']}" : "";
            $null = isset($field['nullable']) ? "'null' => true" : "";

            $props = [];
            $props[] = "'type' => '$type'";
            if ($constraint)
                $props[] = $constraint;
            if ($null)
                $props[] = $null;

            $fieldStrings .= "            '{$field['name']}' => [" . implode(', ', $props) . "],\n";
        }

        $template = "<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Create{$this->moduleName}Table extends Migration
{
    public function up()
    {
        \$this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
{$fieldStrings}            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        \$this->forge->addKey('id', true);
        \$this->forge->createTable('{$this->tableName}');
    }

    public function down()
    {
        \$this->forge->dropTable('{$this->tableName}');
    }
}
";
        file_put_contents($path, $template);
    }

    private function createModel()
    {
        $path = APPPATH . 'Models/' . $this->moduleName . 'Model.php';
        $allowedFields = array_column($this->fields, 'name');
        $allowedFieldsString = "'" . implode("', '", $allowedFields) . "'";

        // Basic validation rules based on required
        $validationRules = [];
        foreach ($this->fields as $field) {
            if (!isset($field['nullable'])) {
                $validationRules[$field['name']] = 'required';
            }
        }
        $validationString = "";
        foreach ($validationRules as $key => $rule) {
            $validationString .= "        '$key' => '$rule',\n";
        }

        $template = "<?php

namespace App\Models;

use CodeIgniter\Model;

class {$this->moduleName}Model extends Model
{
    protected \$table            = '{$this->tableName}';
    protected \$primaryKey       = 'id';
    protected \$useAutoIncrement = true;
    protected \$returnType       = 'object';
    protected \$useSoftDeletes   = false;
    protected \$protectFields    = true;
    protected \$allowedFields    = [{$allowedFieldsString}];

    protected bool \$allowEmptyInserts = false;

    // Dates
    protected \$useTimestamps = true;
    protected \$dateFormat    = 'datetime';
    protected \$createdField  = 'created_at';
    protected \$updatedField  = 'updated_at';

    protected \$validationRules      = [
{$validationString}    ];
}
";
        file_put_contents($path, $template);
    }

    private function createController()
    {
        $path = APPPATH . 'Controllers/Admin/' . $this->controllerName . '.php';
        $modelName = $this->moduleName . 'Model';

        $template = "<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\\{$modelName};
use CodeIgniter\API\ResponseTrait;

class {$this->controllerName} extends BaseController
{
    use ResponseTrait;

    protected \$model;

    public function __construct()
    {
        \$this->model = new {$modelName}();
    }

    public function index()
    {
        return view('admin/" . strtolower($this->moduleName) . "/index', [
            'items' => \$this->model->findAll(),
            'title' => 'Manage {$this->moduleName}'
        ]);
    }

    public function new()
    {
        return view('admin/" . strtolower($this->moduleName) . "/form', [
            'title' => 'Create {$this->moduleName}',
            'action' => 'create',
            'item' => null
        ]);
    }

    public function create()
    {
        \$data = \$this->request->getPost();
        
        if (!\$this->model->save(\$data)) {
            return redirect()->back()->withInput()->with('errors', \$this->model->errors());
        }

        return redirect()->to('admin/" . strtolower($this->moduleName) . "')->with('message', 'Item created successfully');
    }

    public function edit(\$id = null)
    {
        \$item = \$this->model->find(\$id);
        if (!\$item) {
            return redirect()->to('admin/" . strtolower($this->moduleName) . "')->with('error', 'Item not found');
        }

        return view('admin/" . strtolower($this->moduleName) . "/form', [
            'item' => \$item,
            'title' => 'Edit {$this->moduleName}',
            'action' => 'edit'
        ]);
    }

    public function update(\$id = null)
    {
        \$data = \$this->request->getPost();
        
        if (!\$this->model->update(\$id, \$data)) {
            return redirect()->back()->withInput()->with('errors', \$this->model->errors());
        }

        return redirect()->to('admin/" . strtolower($this->moduleName) . "')->with('message', 'Item updated successfully');
    }

    public function delete(\$id = null)
    {
        if (!\$this->model->delete(\$id)) {
            return redirect()->to('admin/" . strtolower($this->moduleName) . "')->with('error', 'Failed to delete item');
        }

        return redirect()->to('admin/" . strtolower($this->moduleName) . "')->with('message', 'Item deleted successfully');
    }
}
";
        file_put_contents($path, $template);
    }

    private function createViews()
    {
        $viewPath = APPPATH . 'Views/admin/' . strtolower($this->moduleName);
        if (!is_dir($viewPath)) {
            mkdir($viewPath, 0755, true);
        }

        // Index View
        $th = "                    <th style=\"width: 10px\">#</th>\n";
        $td = "                        <td><?= \$item->id ?></td>\n";
        foreach ($this->fields as $field) {
            $th .= "                    <th>" . ucfirst($field['name']) . "</th>\n";
            $td .= "                        <td><?= esc(\$item->{$field['name']}) ?></td>\n";
        }
        $th .= "                    <th style=\"width: 150px\">Actions</th>";

        $indexTemplate = "<?= \$this->extend('layout/master') ?>

<?= \$this->section('title') ?>
<?= \$title ?>
<?= \$this->endSection() ?>

<?= \$this->section('content') ?>
<div class=\"card\">
    <div class=\"card-header\">
        <h3 class=\"card-title\">List</h3>
        <div class=\"card-tools\">
            <a href=\"<?= site_url('admin/" . strtolower($this->moduleName) . "/new') ?>\" class=\"btn btn-primary btn-sm\">
                <i class=\"fas fa-plus\"></i> Create New
            </a>
        </div>
    </div>
    <div class=\"card-body p-0\">
        <table class=\"table table-striped\">
            <thead>
                <tr>
{$th}
                </tr>
            </thead>
            <tbody>
                <?php if(empty(\$items)): ?>
                    <tr><td colspan=\"" . (count($this->fields) + 2) . "\" class=\"text-center\">No items found</td></tr>
                <?php else: ?>
                    <?php foreach(\$items as \$item): ?>
                    <tr>
{$td}
                        <td>
                            <a href=\"<?= site_url('admin/" . strtolower($this->moduleName) . "/' . \$item->id . '/edit') ?>\" class=\"btn btn-warning btn-xs\">
                                <i class=\"fas fa-edit\"></i>
                            </a>
                            <a href=\"<?= site_url('admin/" . strtolower($this->moduleName) . "/' . \$item->id . '/delete') ?>\" class=\"btn btn-danger btn-xs\" onclick=\"return confirm('Are you sure?')\">
                                <i class=\"fas fa-trash\"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= \$this->endSection() ?>
";
        file_put_contents($viewPath . '/index.php', $indexTemplate);

        // Form View
        $formFields = "";
        foreach ($this->fields as $field) {
            $required = isset($field['nullable']) ? "" : "required";
            $formFields .= "            <div class=\"form-group\">
                <label for=\"{$field['name']}\">" . ucfirst($field['name']) . "</label>
                <input type=\"text\" class=\"form-control\" name=\"{$field['name']}\" id=\"{$field['name']}\" value=\"<?= old('{$field['name']}', \$item->{$field['name']} ?? '') ?>\" {$required}>
            </div>\n";
        }

        $formTemplate = "<?= \$this->extend('layout/master') ?>

<?= \$this->section('title') ?>
<?= \$title ?>
<?= \$this->endSection() ?>

<?= \$this->section('content') ?>
<div class=\"card card-primary\">
    <div class=\"card-header\">
        <h3 class=\"card-title\"><?= \$action === 'edit' ? 'Edit' : 'Create' ?> Item</h3>
    </div>
    
    <?php \$url = \$action === 'edit' ? 'admin/" . strtolower($this->moduleName) . "/' . \$item->id : 'admin/" . strtolower($this->moduleName) . "'; ?>
    <form action=\"<?= site_url(\$url) ?>\" method=\"post\">
        <?= csrf_field() ?>
        <?php if(\$action === 'edit'): ?>
            <input type=\"hidden\" name=\"_method\" value=\"PUT\">
        <?php endif; ?>
        
        <div class=\"card-body\">
            <?php if (session()->has('errors')) : ?>
                <div class=\"alert alert-danger\">
                    <?php foreach (session('errors') as \$error) : ?>
                        <?= \$error ?><br>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

{$formFields}
        </div>

        <div class=\"card-footer\">
            <button type=\"submit\" class=\"btn btn-primary\">Submit</button>
            <a href=\"<?= site_url('admin/" . strtolower($this->moduleName) . "') ?>\" class=\"btn btn-default float-right\">Cancel</a>
        </div>
    </form>
</div>
<?= \$this->endSection() ?>
";
        file_put_contents($viewPath . '/form.php', $formTemplate);
    }

    private function updateRoutes()
    {
        $routesPath = APPPATH . 'Config/Routes.php';
        $routesContent = file_get_contents($routesPath);

        $newRoute = "    \$routes->resource('" . strtolower($this->moduleName) . "', ['controller' => 'Admin\\" . $this->controllerName . "']);";

        // Find the closure for admin group
        $pattern = "/(\\\$routes->group\('admin'.*?function.*?\{)(.*?)(\}\);)/s";

        // Naive append inside the group
        // If customization is needed, we might iterate file lines.
        // For now, looking for the resource lines we added previously.

        if (strpos($routesContent, $newRoute) === false) {
            $lines = file($routesPath);
            $newContent = "";
            $inserted = false;
            foreach ($lines as $line) {
                if (trim($line) === '});' && !$inserted) {
                    $newContent .= $newRoute . "\n";
                    $inserted = true;
                }
                $newContent .= $line;
            }
            file_put_contents($routesPath, $newContent);
        }
    }

    public function rollback($data)
    {
        $this->moduleName = ucfirst($data['module_name']);
        $this->tableName = strtolower($data['table_name']);
        $this->controllerName = ucfirst($data['controller_name']);

        // 1. Delete Controller
        $controllerPath = APPPATH . 'Controllers/Admin/' . $this->controllerName . '.php';
        if (file_exists($controllerPath))
            unlink($controllerPath);

        // 2. Delete Model
        $modelPath = APPPATH . 'Models/' . $this->moduleName . 'Model.php';
        if (file_exists($modelPath))
            unlink($modelPath);

        // 3. Delete Views
        $viewPath = APPPATH . 'Views/admin/' . strtolower($this->moduleName);
        if (is_dir($viewPath)) {
            $files = glob($viewPath . '/*');
            foreach ($files as $file) {
                if (is_file($file))
                    unlink($file);
            }
            rmdir($viewPath);
        }

        // 4. Delete Migration
        $migrationPath = APPPATH . 'Database/Migrations/*_Create' . $this->moduleName . 'Table.php';
        $files = glob($migrationPath);
        if (!empty($files)) {
            foreach ($files as $file) {
                unlink($file);
            }
        }

        // 5. Remove Route
        $this->removeRoute();

        return true;
    }

    private function removeRoute()
    {
        $routesPath = APPPATH . 'Config/Routes.php';
        $content = file_get_contents($routesPath);
        $routeLine = "    \$routes->resource('" . strtolower($this->moduleName) . "', ['controller' => 'Admin\\" . $this->controllerName . "']);";

        $newContent = str_replace($routeLine . "\n", "", $content);
        if ($newContent === $content) {
            $newContent = str_replace($routeLine, "", $content);
        }

        file_put_contents($routesPath, $newContent);
    }
}
