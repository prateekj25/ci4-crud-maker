<?= $this->extend('layout/master') ?>

<?= $this->section('title') ?>
Edit Permission:
<?= esc($permission->title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Permission</h3>
    </div>

    <form action="<?= site_url('admin/permissions/' . $permission->id) ?>" method="post">
        <input type="hidden" name="_method" value="PUT">
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
                <input type="text" class="form-control" name="name" id="name"
                    value="<?= old('name', $permission->name) ?>" required>
            </div>

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title"
                    value="<?= old('title', $permission->title) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea class="form-control" name="description" id="description"
                    rows="3"><?= old('description', $permission->description) ?></textarea>
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= site_url('admin/permissions') ?>" class="btn btn-default float-right">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>