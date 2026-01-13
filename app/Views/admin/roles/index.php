<?= $this->extend('layout/master') ?>

<?= $this->section('title') ?>
Manage Roles
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Roles List</h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/roles/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create New
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th style="width: 150px">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($roles)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No roles found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($roles as $role): ?>
                        <tr>
                            <td>
                                <?= $role->id ?>
                            </td>
                            <td>
                                <?= esc($role->name) ?>
                            </td>
                            <td>
                                <?= esc($role->title) ?>
                            </td>
                            <td>
                                <?= esc($role->description) ?>
                            </td>
                            <td>
                                <a href="<?= site_url('admin/roles/' . $role->id . '/edit') ?>" class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= site_url('admin/roles/' . $role->id . '/delete') ?>" class="btn btn-danger btn-xs"
                                    onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>