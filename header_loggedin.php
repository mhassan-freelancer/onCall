<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <!-- Logo -->
        <a href="index2.html" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini"><b>A</b>LT</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">A&amp;A Power <b>OnCall</b></span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu pull-left">
                <ul class="nav navbar-nav ">
                    <!-- Messages: style can be found in dropdown.less-->

                    <li >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Details
                        </a>
                    </li>
                    <li >
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> System Detail
                        </a>
                    </li>


                    <li >
                        <a href="#" class="" data-toggle="dropdown">

                        </a>
                    </li>



                    <!-- Control Sidebar Toggle Button -->

                </ul>
            </div>
            <div class="navbar-custom-menu pull-right">
                <ul class="nav navbar-nav ">
                    <!-- Messages: style can be found in dropdown.less-->







                    <li class="dropdown user user-menu">
                        <a href="" class="dropdown-toggle" data-toggle="dropdown">

                            <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                            <span class="hidden-xs"><?php echo $_SESSION['on_call_u_username'] ?></span>
                        </a>
                    </li>


                    <li class="dropdown" >

                        <a href="logout.php">Logout</a>

                    </li>
                    <!-- Control Sidebar Toggle Button -->

                </ul>
            </div>
        </nav>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
            <!-- Sidebar user panel -->

            <!-- search form -->

            <!-- /.search form -->
            <!-- sidebar menu: : style can be found in sidebar.less -->
            <ul class="sidebar-menu">
                <?php include "sidenav.php"?>

            </ul>
        </section>
        <!-- /.sidebar -->
    </aside>