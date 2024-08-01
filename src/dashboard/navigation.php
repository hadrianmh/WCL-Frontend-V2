<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <!-- Logo -->
    <a href="index.php?page=dashboard" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>
        <?php
        if($_SESSION['role'] == '1'){
          $text = "Root";
          echo substr($text, 0, 1);
        } else if($_SESSION['role'] == '2'){
          $text = "Admin";
          echo substr($text, 0, 1);
        } elseif ($_SESSION['role'] == '3') {
          $text = "Sales Order";
          echo substr($text, 0, 1);
        } elseif ($_SESSION['role'] == '4') {
          $text = "Finance";
          echo substr($text, 0, 1);
        } elseif ($_SESSION['role'] == '5') {
          $text = "Guest";
          echo substr($text, 0, 1);
        } elseif ($_SESSION['role'] == '6') {
          $text = "Production";
          echo substr($text, 0, 1);
        }
        ?>
      </b>D</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>
        <?php
        if($_SESSION['role'] == '1'){
          echo "Root";
        } else if($_SESSION['role'] == '2'){
          echo "Admin";
        } elseif ($_SESSION['role'] == '3') {
          echo "Sales Order";
        } elseif ($_SESSION['role'] == '4') {
          echo "Finance";
        } elseif ($_SESSION['role'] == '5') {
          echo "Guest";
        } elseif ($_SESSION['role'] == '6') {
          echo "Finance";
        } 
        ?>
      </b> Dashboard</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Notifications: style can be found in dropdown.less -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning jmlNotif">0</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header headerjmlNotif"></li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu kontenjmlNtotif">
                </ul>
              </li>
            </ul>
          </li>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <?php
              if(empty($_SESSION['picture'])){ ?>
              <img src="../files/img/default-avatar.jpg" class="user-image" alt="User Image">
              <?php } else { ?>
              <img src="../files/uploads/<?php echo $_SESSION['picture']; ?>" class="user-image" alt="User Image">
              <?php } ?>
              <span class="hidden-xs"><?php echo $_SESSION['name']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <?php
                if(empty($_SESSION['picture'])){ ?>
                <img src="../files/img/default-avatar.jpg" class="img-circle" alt="User Image">
                <?php } else { ?>
                <img src="../files/uploads/<?php echo $_SESSION['picture']; ?>" class="img-circle" alt="User Image">
                <?php } ?>
                <p><?php echo $_SESSION['name']; ?><small><?php
                    if($_SESSION['role'] == '1'){
                      echo "Root";
                    } else if($_SESSION['role'] == '2'){
                      echo "Administrator";
                    } elseif ($_SESSION['role'] == '3') {
                      echo "Sales Order";
                    } elseif ($_SESSION['role'] == '4') {
                      echo "Finance";
                    } elseif ($_SESSION['role'] == '5') {
                      echo "Guest";
                    } elseif ($_SESSION['role'] == '6') {
                      echo "Production";
                    }
                    ?></small></p>
              </li>
              <!-- Menu Body -->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="index.php?page=profile" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="../auth/signout.php" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <?php
          if(empty($_SESSION['picture'])){ ?>
          <img src="../files/img/default-avatar.jpg" class="img-circle" alt="User Image">
          <?php } else { ?>
          <img src="../files/uploads/<?php echo $_SESSION['picture']; ?>" class="img-circle" alt="User Image">
          <?php } ?>
        </div>
        <div class="pull-left info">
          <p><?php echo $_SESSION['name']; ?></p>
          <a href="#"><i class="text-success"></i> 
            <?php
            if($_SESSION['role'] == '1'){
              echo "Root";
            } else if($_SESSION['role'] == '2'){
              echo "Administrator";
            } elseif ($_SESSION['role'] == '3') {
              echo "Sales Order";
            } elseif ($_SESSION['role'] == '4') {
              echo "Finance";
            } elseif ($_SESSION['role'] == '5') {
              echo "Guest";
            } elseif ($_SESSION['role'] == '6') {
              echo "Production";
            }
            ?>
          </a>
        </div>
      </div>
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <?php if($_SESSION['account'] == '1'){ ?>
          <?php if($_SESSION['role'] == '1' || $_SESSION['role'] == '2' || $_SESSION['role'] == '3' || $_SESSION['role'] == '4' || $_SESSION['role'] == '5' || $_SESSION['role'] == '6'){ ?>
           <li>
            <a href="index.php?page=dashboard"><i class="fa fa-dashboard"></i> <span>SO Tracking</span></a>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1' || $_SESSION['role'] == '5'){ ?>
          <li>
            <a href="index.php?page=company"><i class="fa fa-user-circle"></i> <span>Company</span></a>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1' || $_SESSION['role'] == '2' || $_SESSION['role'] == '5'){ ?>
          <li>
            <a href="index.php?page=vendor"><i class="fa fa-address-card-o"></i> <span>Vendor</span></a>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1' || $_SESSION['role'] == '2' || $_SESSION['role'] == '3' || $_SESSION['role'] == '4' || $_SESSION['role'] == '5' || $_SESSION['role'] == '6'){ ?>
          <li>
            <a href="index.php?page=customer"><i class="fa fa-address-book"></i> <span>Customer</span></a>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1' || $_SESSION['role'] == '2' || $_SESSION['role'] == '5' || $_SESSION['role'] == '6'){ ?>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-pie-chart"></i>
              <span>Invetory</span>
              <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="treeview-menu">
              <li><a href="index.php?page=label"><i class="fa fa-circle-o"></i> Label</a></li>
              <li><a href="index.php?page=ribbon"><i class="fa fa-circle-o"></i> Ribbon</a></li>
              <li><a href="index.php?page=bahan_baku"><i class="fa fa-circle-o"></i> Bahan Baku</a></li>
            </ul>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1' || $_SESSION['role'] == '2' || $_SESSION['role'] == '5'){ ?>
          <li>
            <a href="index.php?page=purchase"><i class="fa fa-sticky-note-o"></i> <span>Purchase Order</span></a>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1' || $_SESSION['role'] == '2' || $_SESSION['role'] == '3' || $_SESSION['role'] == '5'){ ?>
          <li>
            <a href="index.php?page=preorder"><i class="fa fa-book"></i> <span>Sales Order</span></a>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1' || $_SESSION['role'] == '2' || $_SESSION['role'] == '5'){ ?>
          <li>
            <a href="index.php?page=workorder"><i class="fa fa-send-o"></i> <span>Work Order</span></a>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1' || $_SESSION['role'] == '2' || $_SESSION['role'] == '4' || $_SESSION['role'] == '5'){ ?>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-truck"></i>
              <span>Delivery Orders</span>
              <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="treeview-menu">
              <li><a href="index.php?page=delivery_orders_waiting"><i class="fa fa-circle-o"></i> <span>Waiting</span></a></li>
              <li><a href="index.php?page=delivery_orders_delivery"><i class="fa fa-circle-o"></i> <span>Delivery</span></a></li>
            </ul>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1' || $_SESSION['role'] == '4' || $_SESSION['role'] == '5'){ ?>
          <li class="treeview">
            <a href="#">
              <i class="fa fa-sticky-note-o"></i>
              <span>Invoice</span>
              <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="treeview-menu">
              <li><a href="index.php?page=invoice_waiting"><i class="fa fa-circle-o"></i> Waiting list</a></li>
              <li><a href="index.php?page=invoice_procces"><i class="fa fa-circle-o"></i> Procces</a></li>
              <li><a href="index.php?page=invoice_duedate"><i class="fa fa-circle-o"></i> Due date</a></li>
              <li><a href="index.php?page=invoice_done"><i class="fa fa-circle-o"></i> <span>Done</span></a></li>
            </ul>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1' || $_SESSION['role'] == '4' || $_SESSION['role'] == '5'){ ?>
          <li>
            <a href="index.php?page=aging"><i class="fa fa-archive"></i> <span>Aging</span></a>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1' || $_SESSION['role'] == '4' || $_SESSION['role'] == '5'){ ?>
          <li class="treeview">
            <a href="#">
              <i class="fa fa fa-money"></i>
              <span>Cash Flow</span>
              <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
            </a>
            <ul class="treeview-menu">
              <li><a href="index.php?page=cashflow"><i class="fa fa-circle-o"></i> Cash</a></li>
              <li><a href="index.php?page=tol"><i class="fa fa-circle-o"></i> Tol</a></li>
            </ul>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1'){ ?>
          <li>
            <a href="index.php?page=user"><i class="fa fa-users"></i> <span>User</span></a>
          </li>
          <?php } ?>

          <?php if($_SESSION['role'] == '1'){ ?>
          <li>
            <a href="index.php?page=setting"><i class="fa fa-cogs"></i> <span>Settings</span></a>
          </li>
          <?php } ?>

        <?php } ?>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
