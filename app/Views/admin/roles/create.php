<?= $this->extend('layout/master') ?>

<?= $this->section('title') ?>
Create Role
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">New Role</h3>
    </div>

    <form action="<?= site_url('admin/roles') ?>" method="post">
        <?= csrf_field() ?>
        <div class="card-body">

            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach (session('errors') as $error): ?>
                        <?= $error ?>
                        <br>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <div class="form-group">
                <label for="name">Slug (Name)</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="e.g. manager"
                    value="<?= old('name') ?>" required>
                <small class="text-muted">Unique identifier, lowercase.</small>
            </div>

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title" placeholder="e.g. Manager"
                    value="<?= old('title') ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description"
                    rows="3"><?= old('description') ?></textarea>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Submit</button>
            <a href="<?= site_url('admin/roles') ?>" class="btn btn-default float-right">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>