<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?= site_url() ?>" class="brand-link">
        <i class="fas fa-shopping-cart fa-2x text-info"></i>
        <span class="brand-text font-weight-light"><?= setting('App.site_name'); ?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url('uploads/profile/' . esc(1)) ?>" class="img-circle elevation-2 avatar" alt="">
            </div>
            <div class="info">
                <a href="javascript:void(0)" class="d-block"><?= esc("get_user_name"); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p> Dashboard </p>
                    </a>
                </li>

                <?php if (esc(1 == 1)) : ?>
                    <li class="nav-item">
                        <a href="<?= base_url('supplier') ?>" class="nav-link">
                            <i class="nav-icon fas fa-truck"></i>
                            <p> Supplier </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('sale') ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p> Customers </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link">
                            <i class="nav-icon fas fa-cube"></i>
                            <p> Master <i class="right fas fa-angle-left"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= base_url('category') ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>category</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('unit') ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Unit</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('item') ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Item</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif ?>

                <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link">
                        <i class="nav-icon fas fa-cart-plus"></i>
                        <p> Transaction <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= base_url('sale') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sale</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('sale/invoice') ?>" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Invoice</p>
                            </a>
                        </li>
                        <?php if (esc(1 == 1)) : ?>
                            <li class="nav-item">
                                <a href="<?= base_url('stock/enter') ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Incoming Stock</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('stock/out') ?>" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Stock Cancel</p>
                                </a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </li>
                <li class="nav-header">Administrator</li>
                <li class="nav-item">
                    <a href="<?= base_url('user/Sections') ?>" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Sections</p>
                    </a>
                </li>
                <?php if (esc(1 == 1)) : ?>
                    <li class="nav-item">
                        <a href="<?= base_url('user') ?>" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>User</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= base_url('setting') ?>" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>Setting</p>
                        </a>
                    </li>
                <?php endif ?>
                <li class="nav-item">
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modal-logout" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
