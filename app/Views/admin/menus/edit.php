<?= $this->extend('layout/master') ?>

<?= $this->section('title') ?>
Edit Menu:
<?= esc($menu->title) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-warning">
    <div class="card-header">
        <h3 class="card-title">Edit Menu</h3>
    </div>

    <form action="<?= site_url('admin/menus/' . $menu->id) ?>" method="post">
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
                <label for="title">Title</label>
                <input type="text" class="form-control" name="title" id="title"
                    value="<?= old('title', $menu->title) ?>" required>
            </div>

            <div class="form-group">
                <label for="icon">Icon (FontAwesome)</label>
                <input type="text" class="form-control" name="icon" id="icon" value="<?= old('icon', $menu->icon) ?>">
            </div>

            <div class="form-group">
                <label for="route">Route / URL</label>
                <input type="text" class="form-control" name="route" id="route"
                    value="<?= old('route', $menu->route) ?>">
            </div>

            <div class="form-group">
                <label for="parent_id">Parent Menu</label>
                <select class="form-control" name="parent_id" id="parent_id">
                    <option value="">-- None (Top Level) --</option>
                    <?php foreach ($parents as $parent): ?>
                        <option value="<?= $parent->id ?>" <?= (old('parent_id', $menu->parent_id) == $parent->id) ? 'selected' : '' ?>>
                            <?= $parent->title ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="order">Order</label>
                <input type="number" class="form-control" name="order" id="order"
                    value="<?= old('order', $menu->order) ?>">
            </div>
        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="<?= site_url('admin/menus') ?>" class="btn btn-default float-right">Cancel</a>
        </div>
    </form>
</div>
<?= $this->endSection() ?>