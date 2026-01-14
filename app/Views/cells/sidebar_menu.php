<?php foreach ($menus as $menu): ?>
    <?php if (empty($menu->children)): ?>
        <li class="nav-item">
            <a href="<?= site_url($menu->route) ?>" class="nav-link">
                <i class="nav-icon <?= esc($menu->icon) ?>"></i>
                <p>
                    <?= esc($menu->title) ?>
                </p>
            </a>
        </li>
    <?php else: ?>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
                <i class="nav-icon <?= esc($menu->icon) ?>"></i>
                <p>
                    <?= esc($menu->title) ?>
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <?php foreach ($menu->children as $child): ?>
                    <li class="nav-item">
                        <a href="<?= site_url($child->route) ?>" class="nav-link">
                            <i class="<?= esc($child->icon) ?> nav-icon"></i>
                            <p>
                                <?= esc($child->title) ?>
                            </p>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
    <?php endif; ?>
<?php endforeach; ?>