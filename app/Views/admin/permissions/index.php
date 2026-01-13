<?= $this->extend('layout/master') ?>

<?= $this->section('title') ?>
Manage Permissions
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Permissions List</h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/permissions/new') ?>" class="btn btn-primary btn-sm">
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
                <?php if (empty($permissions)): ?>
                    <tr>
                        <td colspan="5" class="text-center">No permissions found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($permissions as $permission): ?>
                        <tr>
                            <td>
                                <?= $permission->id ?>
                            </td>
                            <td><code><?= esc($permission->name) ?></code></td>
                            <td>
                                <?= esc($permission->title) ?>
                            </td>
                            <td>
                                <?= esc($permission->description) ?>
                            </td>
                            <td>
                                <a href="<?= site_url('admin/permissions/' . $permission->id . '/edit') ?>"
                                    class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= site_url('admin/permissions/' . $permission->id . '/delete') ?>"
                                    class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')">
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