<?= $this->extend('layout/master') ?>

<?= $this->section('title') ?>
My Profile
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Profile</h3>
            </div>

            <form action="<?= site_url('admin/profile') ?>" method="post">
                <?= csrf_field() ?>
                <div class="card-body">

                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <?php foreach (session('errors') as $error): ?>
                                <?= $error ?><br>
                            <?php endforeach ?>
                        </div>
                    <?php endif ?>

                    <?php if (session()->has('message')): ?>
                        <div class="alert alert-success">
                            <?= session('message') ?>
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

                    <hr>
                    <div class="form-group">
                        <label for="password">Change Password</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="Leave blank to keep current">
                        <small class="text-muted">Min 8 characters</small>
                    </div>

                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>