<?= $this->extend('layout/master') ?>

<?= $this->section('title') ?>
Manage Menus
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Menus List</h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/menus/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create New
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Title</th>
                    <th>Icon</th>
                    <th>Route</th>
                    <th>Parent</th>
                    <th style="width: 150px">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($menus)): ?>
                    <tr>
                        <td colspan="6" class="text-center">No menus found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($menus as $menu): ?>
                        <tr>
                            <td>
                                <?= $menu->id ?>
                            </td>
                            <td>
                                <?= esc($menu->title) ?>
                            </td>
                            <td><i class="<?= esc($menu->icon) ?>"></i>
                                <?= esc($menu->icon) ?>
                            </td>
                            <td>
                                <?= esc($menu->route) ?>
                            </td>
                            <td>
                                <?= $menu->parent_id ?? '-' ?>
                            </td>
                            <td>
                                <a href="<?= site_url('admin/menus/' . $menu->id . '/edit') ?>" class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="<?= site_url('admin/menus/' . $menu->id . '/delete') ?>" class="btn btn-danger btn-xs"
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