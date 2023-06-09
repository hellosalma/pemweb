<!-- Sidebar -->
<ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
    <div class="sidebar-brand-icon">
      <i class="fas fa-mail-bulk"></i>
    </div>
    <div class="sidebar-brand-text mx-3">SIPAS <br> MANDIRI<sup> 139</sup></div>
  </a>

  <!-- Heading -->
  <div class="sidebar-heading">
    Admin
  </div>

  <?php if ($user['role_id'] == 1) : ?>

    <!-- Nav Item - Pages Collapse Menu Blog -->
    <li class="nav-item <?= $this->uri->segment(1) == 'pengaturan' ? 'active' : ''; ?>">
      <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePengaturan" aria-expanded="true" aria-controls="collapsePengaturan">
        <i class="fas fa-fw fa-cogs"></i>
        <span>Pengaturan</span>
      </a>
      <div id="collapsePengaturan" class="collapse" aria-labelledby="headingPengaturan" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
          <a class="collapse-item" href="<?= base_url('pengaturan/role') ?>">Role</a>
          <a class="collapse-item" href="<?= base_url('pengaturan/pengguna') ?>">Pengguna</a>
          <a class="collapse-item" href="<?= base_url('pengaturan/klasifikasi') ?>">Klasifikasi</a>
          <a class="collapse-item" href="<?= base_url('pengaturan/sifat') ?>">Sifat Surat</a>
        </div>
      </div>
    </li>
  <?php endif; ?>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">
    Staff
  </div>

  <!-- Nav Item - Dashboard -->
  <li class="nav-item <?= ($this->uri->segment(1) == 'home' ? 'active' : ''); ?>">
    <a class="nav-link pb-0" href="<?= base_url('home/index') ?>">
      <i class="fas fa-fw fa-chart-bar"></i>
      <span>Dashboard</span></a>
  </li>

  <!-- Nav Item - Pages Collapse Menu Blog -->
  <li class="nav-item <?= ($this->uri->segment(1) == 'surat-masuk' || $this->uri->segment(1) == 'surat-keluar' ? 'active' : ''); ?>">
    <a class="nav-link pb-0 collapsed" href="#" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
      <i class="fas fa-fw fa-mail-bulk"></i>
      <span>Transaksi Surat</span>
    </a>
    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="<?= base_url('surat-masuk') ?>">Surat Masuk</a>
        <a class="collapse-item" href="<?= base_url('surat-keluar') ?>">Surat Keluar</a>
      </div>
    </div>
  </li>

  <!-- Nav Item - Pages Collapse Menu Blog -->
  <li class="nav-item <?= ($this->uri->segment(1) == 'laporan' ? 'active' : ''); ?>">
    <a class=" nav-link pb-0 collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
      <i class="fas fa-fw fa-book "></i>
      <span>Laporan</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="<?= base_url('laporan/surat-masuk') ?>">Surat Masuk</a>
        <a class="collapse-item" href="<?= base_url('laporan/surat-keluar') ?>">Surat Keluar</a>
      </div>
    </div>
  </li>

  <li class="nav-item <?= ($this->uri->segment(1) == 'user' ? 'active' : ''); ?>">
    <a class=" nav-link collapsed pb-0" href="<?= base_url('user'); ?>">
      <i class="fas fa-fw fa-user"></i>
      <span>User Profile</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link collapsed" data-toggle="modal" data-target="#logoutModal">
      <i class="fas fa-fw fa-sign-out-alt"></i>
      <span>Logout</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button aria-label="toggler" class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar -->