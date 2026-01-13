<?= $this->extend('layout/master') ?>

<?= $this->section('title') ?>
Create New Module
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card card-success">
    <div class="card-header">
        <h3 class="card-title">Module Definition</h3>
    </div>

    <form action="<?= site_url('admin/modules') ?>" method="post">
        <?= csrf_field() ?>
        <div class="card-body">

            <?php if (session()->has('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach (session('errors') as $error): ?>
                        <?= $error ?><br>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <?php if (session()->has('error')): ?>
                <div class="alert alert-danger">
                    <?= session('error') ?>
                </div>
            <?php endif ?>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Module Name (Singular)</label>
                        <input type="text" class="form-control" name="module_name" placeholder="Product" required>
                        <small>Used for Class names (e.g. ProductModel)</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Table Name (Plural)</label>
                        <input type="text" class="form-control" name="table_name" placeholder="products" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Controller Name</label>
                        <input type="text" class="form-control" name="controller_name" placeholder="ProductController"
                            required>
                    </div>
                </div>
            </div>

            <hr>
            <h4>Table Fields</h4>
            <table class="table table-bordered" id="fields-table">
                <thead>
                    <tr>
                        <th>Field Name</th>
                        <th>Data Type</th>
                        <th>Length/Values</th>
                        <th>Nullable</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="fields-body">
                    <tr>
                        <td><input type="text" class="form-control" name="fields[name][]" placeholder="id" value="id"
                                readonly></td>
                        <td><input type="text" class="form-control" value="INT" readonly></td>
                        <td><input type="text" class="form-control" value="11" readonly></td>
                        <td>-</td>
                        <td>-</td>
                    </tr>
                    <!-- Dynamic Rows Here -->
                </tbody>
            </table>
            <button type="button" class="btn btn-info btn-sm" id="add-row"><i class="fas fa-plus"></i> Add
                Field</button>

        </div>

        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Generate Module</button>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function () {
        function addRow() {
            var html = `<tr>
            <td><input type="text" class="form-control" name="fields[name][]" required></td>
            <td>
                <select class="form-control" name="fields[type][]">
                    <option value="VARCHAR">VARCHAR</option>
                    <option value="INT">INT</option>
                    <option value="TEXT">TEXT</option>
                    <option value="DECIMAL">DECIMAL</option>
                    <option value="DATE">DATE</option>
                    <option value="DATETIME">DATETIME</option>
                    <option value="BOOLEAN">BOOLEAN</option>
                </select>
            </td>
            <td><input type="text" class="form-control" name="fields[length][]" value="255"></td>
            <td class="text-center">
                <input type="checkbox" name="fields[nullable][]" value="1">
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm remove-row"><i class="fas fa-trash"></i></button>
            </td>
        </tr>`;
            $('#fields-body').append(html);
        }

        // Add initial row
        addRow();

        $('#add-row').click(function () {
            addRow();
        });

        $(document).on('click', '.remove-row', function () {
            $(this).closest('tr').remove();
        });
    });
</script>
<?= $this->endSection() ?>