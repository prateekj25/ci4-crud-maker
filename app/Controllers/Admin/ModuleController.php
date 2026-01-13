<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ModuleModel;
use App\Models\MenuModel;
use App\Services\GeneratorService;

class ModuleController extends BaseController
{
    protected $moduleModel;
    protected $menuModel;
    protected $generator;

    public function __construct()
    {
        $this->moduleModel = new ModuleModel();
        $this->menuModel = new MenuModel();
        $this->generator = new GeneratorService();
    }

    public function index()
    {
        return view('admin/modules/index', [
            'modules' => $this->moduleModel->findAll(),
            'title' => 'Manage Modules'
        ]);
    }

    public function new()
    {
        return view('admin/modules/create', [
            'title' => 'Create Module'
        ]);
    }

    public function create()
    {
        $data = $this->request->getPost();

        // 1. Validation for Module Definition
        if (
            !$this->validate([
                'module_name' => 'required|alpha',
                'table_name' => 'required|alpha_dash',
                'controller_name' => 'required|alpha_numeric',
            ])
        ) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // 2. Prepare Data
        // Fields come as arrays: name[], type[], length[], nullable[]
        $fields = [];
        if (isset($data['fields']['name'])) {
            foreach ($data['fields']['name'] as $i => $name) {
                if (!empty($name)) {
                    $fields[] = [
                        'name' => $name,
                        'type' => $data['fields']['type'][$i],
                        'length' => $data['fields']['length'][$i],
                        'nullable' => isset($data['fields']['nullable'][$i]) ? true : false,
                    ];
                }
            }
        }

        $generatorData = [
            'module_name' => $data['module_name'],
            'table_name' => $data['table_name'],
            'controller_name' => $data['controller_name'],
            'fields' => $fields
        ];

        // 3. Run Generator
        try {
            $this->generator->generate($generatorData);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Generation Failed: ' . $e->getMessage());
        }

        // 4. Register in DB
        $moduleId = $this->moduleModel->insert([
            'module_name' => $data['module_name'],
            'module_slug' => strtolower($data['module_name']),
            'table_name' => $data['table_name'],
            'controller_name' => $data['controller_name'],
            'is_active' => 1
        ]);

        // 5. Create Menu Item
        $this->menuModel->insert([
            'title' => $data['module_name'],
            'icon' => 'fas fa-cube',
            'route' => 'admin/' . strtolower($data['module_name']),
            'module_id' => $moduleId,
            'order' => 99
        ]);

        return redirect()->to('admin/modules')->with('message', 'Module generated successfully!');
    }

    public function delete($id = null)
    {
        $module = $this->moduleModel->find($id);

        if (!$module) {
            return redirect()->to('admin/modules')->with('error', 'Module not found');
        }

        // 1. Rollback Files
        try {
            $this->generator->rollback([
                'module_name' => $module->module_name,
                'table_name' => $module->table_name,
                'controller_name' => $module->controller_name
            ]);
        } catch (\Exception $e) {
            // Log error but continue to delete DB record? 
            // Or stop? For now let's stop.
            return redirect()->to('admin/modules')->with('error', 'Rollback failed: ' . $e->getMessage());
        }

        // 2. Delete Menu Item (Cascade should handle this if defined, but being explicit is safer)
        $this->menuModel->where('module_id', $id)->delete();

        // 3. Delete Module Record
        if (!$this->moduleModel->delete($id)) {
            return redirect()->to('admin/modules')->with('error', 'Failed to delete module record');
        }

        return redirect()->to('admin/modules')->with('message', 'Module deleted successfully');
    }
}
