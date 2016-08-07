<?php include 'session_protect.php';
$userid= 0;
if(isset($_GET['id']))
{
    $userid = $_GET['id'];

}
require  'vendor/autoload.php';
use Respect\Validation\Validator as v;

if(!v::numeric()->validate($userid))
{
    $_SESSION['error'] ="Invalid Userid";
    header('location:/system_users.php');
}

if(!v::intVal()->min(1,true)->validate($userid))
{
    $_SESSION['error'] ="Invalid Userid";
    header('location:/system_users.php');
}
if(isset($_SESSION['on_call_u_id']))
{

}
else {
    //header("location:/login.php?error=loginrequired");
}
include 'header.php';
include 'header_loggedin.php';
require 'includes/functions.php';
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
    }
    $userinfo = getUserInfo($userid);

    ?>


    <section class="content-header">
        <h1>
Edit User
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
                        <h3 class="box-title">Update user info</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="bll/edit_user.php">
                        <input type="hidden" name="userid" value="<?php echo $userinfo['id'] ?>">
                        <div class="box-body">
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
                                <input type="text" class="form-control" name="username" id="username" placeholder="Username" value="<?php echo $userinfo['username'] ?>">
                            </div>

                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" value="<?php echo $userinfo['email'] ?>">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Modules</label>
                                <br/>
                                <?php
                                 $modules = getModules();
                                ?>
                                <select name="module">

                                    <option value="0">Please select the module</option>
                                    <?php
                                    foreach ($modules as $module)
                                    {
                                        if($userinfo['alarm'] == $module['id'])
                                        echo '<option value="'.$module['id'].'" selected="selected">'.$module['name'].'</option>';
                                        else{
                                            echo '<option value="'.$module['id'].'">'.$module['name'].'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <?php
                                    if($userinfo['admin']== 1)
                                    {
                                        echo '<input type="checkbox" name="isadmin" checked="checked"> Admin';

                                    }else
                                    {
                                        echo '<input type="checkbox" name="isadmin"> Admin';
                                    }
                                    ?>


                                </label>
                                <label>
                                    <?php
                                    if($userinfo['admin']== 1)
                                    {
                                        echo '<input type="checkbox" name="enabled" checked="checked"> Enabled';

                                    }else
                                    {
                                        echo '<input type="checkbox" name="enabled"> Enabled';
                                    }
                                    ?>


                                </label>
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
