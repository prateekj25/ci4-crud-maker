<?= $this->extend('layout/auth') ?>

<?= $this->section('main') ?>
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">
            <?= lang('Auth.register') ?>
        </p>

        <?php if (session('error') !== null): ?>
            <div class="alert alert-danger" role="alert">
                <?= esc(session('error')) ?>
            </div>
        <?php elseif (session('errors') !== null): ?>
            <div class="alert alert-danger" role="alert">
                <?php if (is_array(session('errors'))): ?>
                    <?php foreach (session('errors') as $error): ?>
                        <?= esc($error) ?>
                        <br>
                    <?php endforeach ?>
                <?php else: ?>
                    <?= esc(session('errors')) ?>
                <?php endif ?>
            </div>
        <?php endif ?>

        <form action="<?= url_to('register') ?>" method="post">
            <?= csrf_field() ?>

            <div class="input-group mb-3">
                <input type="email" class="form-control" name="email" inputmode="email" autocomplete="email"
                    placeholder="<?= lang('Auth.email') ?>" value="<?= old('email') ?>" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-envelope"></span>
                    </div>
                </div>
            </div>

            <div class="input-group mb-3">
                <input type="text" class="form-control" name="username" inputmode="text" autocomplete="username"
                    placeholder="<?= lang('Auth.username') ?>" value="<?= old('username') ?>" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-user"></span>
                    </div>
                </div>
            </div>

            <div class="input-group mb-3">
                <input type="password" class="form-control" name="password" inputmode="text" autocomplete="new-password"
                    placeholder="<?= lang('Auth.password') ?>" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>

            <div class="input-group mb-3">
                <input type="password" class="form-control" name="password_confirm" inputmode="text"
                    autocomplete="new-password" placeholder="<?= lang('Auth.passwordConfirm') ?>" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-8">
                    <a href="<?= url_to('login') ?>" class="text-center">
                        <?= lang('Auth.haveAccount') ?>
                    </a>
                </div>
                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">
                        <?= lang('Auth.register') ?>
                    </button>
                </div>
                <!-- /.col -->
            </div>
        </form>

    </div>
    <!-- /.login-card-body -->
</div>
<?= $this->endSection() ?>