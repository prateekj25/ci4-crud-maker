<?= $this->extend('layout/master') ?>

<?= $this->section('title') ?>
Manage Users
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">User Accounts</h3>
        <div class="card-tools">
            <a href="<?= site_url('admin/users/new') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Create New
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Status</th>
                    <th style="width: 150px">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($users)): ?>
                    <tr><td colspan="6" class="text-center">No users found</td></tr>
                <?php else: ?>
                    <?php foreach($users as $user): ?>
                    <tr>
                        <td><?= $user->id ?></td>
                        <td><?= esc($user->username) ?></td>
                        <td><?= esc($user->email) ?></td>
                        <td>
                            <?php if(!empty($user->roles_list)): ?>
                                <?php foreach($user->roles_list as $role): ?>
                                    <span class="badge badge-info"><?= esc($role) ?></span>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <span class="text-muted small">No Role</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($user->active): ?>
                                <span class="badge badge-success">Active</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= site_url('admin/users/' . $user->id . '/edit') ?>" class="btn btn-warning btn-xs">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="<?= site_url('admin/users/' . $user->id . '/delete') ?>" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure? This is irreversible.')">
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
