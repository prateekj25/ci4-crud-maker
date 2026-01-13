<?= $this->extend('layout/master') ?>

<?= $this->section('title') ?>
Manage Modules
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Generated Modules</h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/modules/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create New Module
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Module Name</th>
                    <th>Table</th>
                    <th>Controller</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($modules)): ?>
                    <tr><td colspan="4" class="text-center">No modules generated yet</td></tr>
                <?php else: ?>
                    <?php foreach($modules as $module): ?>
                    <tr>
                        <td><?= esc($module->module_name) ?></td>
                        <td><?= esc($module->table_name) ?></td>
                        <td><?= esc($module->controller_name) ?></td>
                        <td><span class="badge badge-success">Active</span></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
