<?= $this->extend('layout/auth') ?>

<?= $this->section('main') ?>
<div class="card">
    <div class="card-body login-card-body">
        <p class="login-box-msg">
            <?= lang('Auth.login') ?>
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

        <?php if (session('message') !== null): ?>
            <div class="alert alert-success" role="alert">
                <?= esc(session('message')) ?>
            </div>
        <?php endif ?>

        <form action="<?= url_to('login') ?>" method="post">
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
                <input type="password" class="form-control" name="password" inputmode="text"
                    autocomplete="current-password" placeholder="<?= lang('Auth.password') ?>" required>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <span class="fas fa-lock"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <?php if (setting('Auth.sessionConfig')['allowRemembering']): ?>
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" <?php if (old('remember')): ?> checked
                            <?php endif ?>>
                            <label for="remember">
                                <?= lang('Auth.rememberMe') ?>
                            </label>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- /.col -->
                <div class="col-4">
                    <button type="submit" class="btn btn-primary btn-block">
                        <?= lang('Auth.login') ?>
                    </button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <?php if (setting('Auth.allowRegistration')): ?>
            <p class="mb-0">
                <a href="<?= url_to('register') ?>" class="text-center">
                    <?= lang('Auth.needAccount') ?>
                </a>
            </p>
        <?php endif ?>

        <?php if (setting('Auth.allowMagicLinkLogins')): ?>
            <p class="mb-1">
                <a href="<?= url_to('magic-link') ?>">
                    <?= lang('Auth.forgotPassword') ?>
                </a>
            </p>
        <?php endif ?>
    </div>
    <!-- /.login-card-body -->
</div>
<?= $this->endSection() ?>