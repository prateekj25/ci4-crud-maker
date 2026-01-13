<?= $this->extend('layout/master') ?>

<?= $this->section('title') ?>
Create User
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">New User Account</h3>
    </div>

    <form action="<?= site_url('admin/users') ?>" method="post">
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
                <input type="text" class="form-control" name="username" id="username" value="<?= old('username') ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="<?= old('email') ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>

            <div class="form-group">
                <label>Roles</label>
                <?php foreach ($roles as $role): ?>
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" id="role_<?= $role->id ?>" name="roles[]"
                            value="<?= $role->id ?>">
                        <label for="role_<?= $role->id ?>" class="custom-control-label">
                            <?= esc($role->title) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Create User</button>
            <a href="<?= site_url('admin/users') ?>" class="btn btn-default float-right">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>