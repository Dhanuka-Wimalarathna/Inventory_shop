<?php $user = current_user(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Meta information -->
  <meta charset="UTF-8">

  <!-- Dynamic page title based on user or page title -->
  <title>
    <?php
    
      echo "Phone Shop - Inventory Management System";
    ?>
  </title>

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" />
  <!-- Datepicker CSS -->
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/css/datepicker3.min.css" />
  <!-- Custom CSS -->
  <link rel="stylesheet" href="libs/css/main.css" />
</head>

<body>

  <?php if ($session->isUserLoggedIn(true)): ?>
    <!-- Header section with logo, date, and user profile -->
    <header id="header">
      <div class="logo pull-left"> Inventory System</div>
      <div class="header-content">
        <div class="header-date pull-left">
          <strong><?php echo date("F j, Y, g:i a"); ?></strong>
        </div>
        <div class="pull-right clearfix">
          <ul class="info-menu list-inline list-unstyled">
            <li class="profile">
              <a href="#" data-toggle="dropdown" class="toggle" aria-expanded="false">
                <!-- User's profile image and name -->
                <img src="C:/xampp/htdocs/InventorySystem_PHP/\uploads/users/oh6rnkoo1.jpg<?php echo $user['image']; ?>" alt="user-image" class="img-circle img-inline">
                <span><?php echo remove_junk(ucfirst($user['name'])); ?> <i class="caret"></i></span>
              </a>
              <ul class="dropdown-menu">
                <!-- Profile link -->
                <li>
                  <a href="profile.php?id=<?php echo (int) $user['id']; ?>">
                    <i class="glyphicon glyphicon-user"></i>
                    Profile
                  </a>
                </li>
                <!-- Account settings link -->
                <li>
                  <a href="edit_account.php" title="edit account">
                    <i class="glyphicon glyphicon-cog"></i>
                    Settings
                  </a>
                </li>
                <!-- Logout link -->
                <li class="last">
                  <a href="logout.php">
                    <i class="glyphicon glyphicon-off"></i>
                    Logout
                  </a>
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </header>

    <!-- Sidebar with menu based on user level -->
    <div class="sidebar">
      <?php if ($user['user_level'] === '1'): ?>
        <!-- Admin menu -->
        <?php include_once('admin_menu.php'); ?>

      <?php elseif ($user['user_level'] === '2'): ?>
        <!-- Special user menu -->
        <?php include_once('special_menu.php'); ?>

      <?php elseif ($user['user_level'] === '3'): ?>
        <!-- Standard user menu -->
        <?php include_once('user_menu.php'); ?>

      <?php endif; ?>
    </div>
  <?php endif; ?>

  <!-- Main content area -->
  <div class="page">
    <div class="container-fluid">