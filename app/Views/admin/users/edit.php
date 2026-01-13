<?= $this->extend('layout/master') ?>

<?= $this->section('title') ?>
Edit User:
<?= esc($user->username) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit User</h3>
    </div>

    <form action="<?= site_url('admin/users/' . $user->id) ?>" method="post">
        <input type="hidden" name="_method" value="PUT">
        <?= csrf_field() ?>

        <div class="card-body">

            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach (session('errors') as $error): ?>
                        <?= $error ?><br>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username"
                    value="<?= old('username', $user->username) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email"
                    value="<?= old('email', $user->email) ?>" required>
            </div>

            <div class="form-group">
                <label for="password">New Password (Leave blank to keep current)</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="********">
            </div>

            <div class="form-group">
                <label>Roles</label>
                <?php foreach ($roles as $role): ?>
                    <?php $checked = in_array($role->id, $currentRoles) ? 'checked' : ''; ?>
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="role_<?= $role->id ?>" name="roles[]"
                            value="<?= $role->id ?>" <?= $checked ?>>
                        <label for="role_<?= $role->id ?>" class="custom-control-label">
                            <?= esc($role->title) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= site_url('admin/users') ?>" class="btn btn-default float-right">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>