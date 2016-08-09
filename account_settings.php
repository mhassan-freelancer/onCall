<?php
include 'session_protect.php';
include 'header.php';
include 'header_loggedin.php';
require 'includes/functions.php';

$userid = 0;
if(isset($_SESSION['on_call_u_id']))
{
  $userid = $_SESSION['on_call_u_id'];
}
?>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <?php
  if(isset($_SESSION['error']))
  {
    if(is_array($_SESSION['error']))
    {

      ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
        <?php
        foreach ($_SESSION['error'] as $val)
        {
          echo $val."<br/>";
        }
        $_SESSION['error'] = null;
        ?>
      </div>
      <?php
    }
    else
    {
      ?>
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Alert!</h4>
        <?php
        echo $_SESSION['error'];
        $_SESSION['error'] = null;
        ?>
      </div>
      <?php
    }
  }else if(isset($_SESSION['success']))
  {

    ?>
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <h4><i class="icon fa fa-check"></i> Alert!</h4>
      <?php
      echo $_SESSION['success'];
      $_SESSION['success'] = null;
      ?>
    </div>
    <?php
  }
  $userinfo = getUserInfo($userid);
  ?>

  <section class="content-header">
    <h1>
      User Settings
    </h1>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <!-- left column -->
      <div class="col-md-6">
        <!-- general form elements -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Update Settings</h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form role="form" method="post" action="bll/account_settings.php">
            <div class="box-body">
              <input type="hidden" name="userId" id="userId" value="<?php echo $userinfo['id'] ?>">
              <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" class="form-control" id="firstname"  name="firstname" placeholder="First Name" value="<?php echo $userinfo['first_name'] ?>">
              </div>
              <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo $userinfo['last_name'] ?>">
              </div>
              <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php echo $userinfo['username'] ?>" disabled="disabled">
              </div>

              <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" value="<?php echo $userinfo['email'] ?>" disabled="disabled">
              </div>
              <div class="form-group">
                <label for="cur_password">Current Password</label>
                <input type="password" class="form-control" name="cur_password" id="cur_password" placeholder="Password">
              </div>
              <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="New Password">
              </div>
              <div class="form-group">
                <label for="cPassword">Confirm Password</label>
                <input type="password" class="form-control" name="cPassword" id="cPassword" placeholder="Confirm Password">
              </div>
            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </section>
</div>
