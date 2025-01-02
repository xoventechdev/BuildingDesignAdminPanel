
<ul class="nav">

<li class="nav-item nav-category">
  <span class="nav-link">Navigation</span>
</li>
<li class="nav-item menu-items">
  <a class="nav-link" href="./index.php">
    <span class="menu-icon">
      <i class="mdi mdi-speedometer"></i>
    </span>
    <span class="menu-title">Dashboard</span>
  </a>
</li>
<li class="nav-item menu-items">
  <a class="nav-link" data-bs-toggle="collapse" href="#uiu-basic" aria-expanded="false" aria-controls="uiu-basic">
    <span class="menu-icon">
      <i class="mdi mdi-laptop"></i>
    </span>
    <span class="menu-title">Design</span>
    <i class="menu-arrow"></i>
  </a>
  <div class="collapse" id="uiu-basic">
    <ul class="nav flex-column sub-menu">
      <li class="nav-item"> <a class="nav-link" href="./design-view.php">View Design</a></li>
      <li class="nav-item"> <a class="nav-link" href="./design-add.php">Add Design</a></li>
    </ul>
  </div>
</li><li class="nav-item menu-items">
  <a class="nav-link" data-bs-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
    <span class="menu-icon">
      <i class="mdi mdi-contacts"></i>
    </span>
    <span class="menu-title">User</span>
    <i class="menu-arrow"></i>
  </a>
  <div class="collapse" id="ui-basic">
    <ul class="nav flex-column sub-menu">
      <li class="nav-item"> <a class="nav-link" href="./user-view.php">View User</a></li>
      <li class="nav-item"> <a class="nav-link" href="./user-add.php">Add User</a></li>
    </ul>
  </div>
</li>
<li class="nav-item menu-items">
  <a class="nav-link" href="./comment-view.php">
    <span class="menu-icon">
      <i class="mdi mdi-table-large"></i>
    </span>
    <span class="menu-title">Comments</span>
    <i class="menu-arrow"></i>
  </a>
</li>
<li class="nav-item menu-items">
  <a class="nav-link" href="./adControl.php">
    <span class="menu-icon">
      <i class="mdi mdi-chart-bar"></i>
    </span>
    <span class="menu-title">Ads Control</span>
    <i class="menu-arrow"></i>
  </a>
</li>
</ul>
</nav>


<!-- partial -->
<div class="container-fluid page-body-wrapper">
<!-- partial:../../partials/_navbar.html -->
<nav class="navbar p-0 fixed-top d-flex flex-row">
<div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
  <a class="navbar-brand brand-logo-mini" href="/"><img src="./assets/images/logo-mini.svg" alt="logo" /></a>
</div>
<div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
  <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
    <span class="mdi mdi-menu"></span>
  </button>

  <ul class="navbar-nav navbar-nav-right">



    <li class="nav-item dropdown">
      <a class="nav-link" id="profileDropdown" href="#" data-bs-toggle="dropdown">
        <div class="navbar-profile">
          <img class="img-xs rounded-circle" src="./assets/images/faces/face15.jpg" alt="">
          <p class="mb-0 d-none d-sm-block navbar-profile-name"><?php echo $_SESSION['admin_name'];?></p>
          <i class="mdi mdi-menu-down d-none d-sm-block"></i>
        </div>
      </a>
      <div class="dropdown-menu dropdown-menu-end navbar-dropdown preview-list" aria-labelledby="profileDropdown">
       
        <a class="dropdown-item preview-item" href="user-edit.php?id=<?php echo $_SESSION['id']; ?>">
          <div class="preview-thumbnail">
            <div class="preview-icon bg-dark rounded-circle">
              <i class="mdi mdi-cog text-success"></i>
            </div>
          </div>
          <div class="preview-item-content">
            <p class="preview-subject mb-1">Profile</p>
          </div>
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item preview-item" href="./logout.php">
          <div class="preview-thumbnail">
            <div class="preview-icon bg-dark rounded-circle">
              <i class="mdi mdi-logout text-danger"></i>
            </div>
          </div>
          <div class="preview-item-content">
            <p class="preview-subject mb-1">Log out</p>
          </div>
        </a>
      </div>
    </li>
  </ul>
  <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
    <span class="mdi mdi-format-line-spacing"></span>
  </button>
</div>
</nav>
<!-- partial -->
