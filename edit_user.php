<?php
include 'session_protect.php';
include 'role_check_admin.php';

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
include __DIR__.'/header.php';
include __DIR__.'/header_loggedin.php';
require __DIR__.'/includes/functions.php';
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
                    <form id="editUserForm" role="form" method="post" action="bll/edit_user.php" onsubmit="return processfrom();">
                        <input type="hidden" name="id" value="<?php echo $userinfo['id'] ?>">


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
                                <label>
                                    <?php
                                    if($userinfo['enabled']== 1)
                                    {?>
                                        <input type='hidden' value='0' name='isenabled'>
                                        <input type="checkbox" name="isenabled"  value="1" checked="checked"  /> Enabled
                                        <?php
                                    }
                                    else
                                    {   ?>
                                        <input type="checkbox" name="isenabled" value="1" /> Enabled
                                        <?php
                                    }
                                echo '</label> &nbsp; <label>';
                                    if($userinfo['admin']== 1)
                                    {
                                        echo  '<input type="hidden" value="0" name="isadmin">';
                                        echo '<br /><input type="checkbox" name="isadmin"  value="1" checked="checked"  /> Admin';
                                    }
                                    else
                                    {
                                        echo '<br /><input type="checkbox" name="isadmin" value="1" /> Admin';
                                    }
                                    ?>
                                </label> <br /> <label>
                                    <?php if($userinfo['alarm']== 1)
                                    {?>
                                        <input type='hidden' value='0' name='oncall'>
                                        <input type="checkbox" name="oncall"  value="1" checked="checked" /> OnCall
                                    <?php
                                    }
                                    else
                                    { ?>
                                        <input type="checkbox" name="oncall" value="1" /> Alarm
                                    <?php } ?>
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
<script type="application/javascript">

    function processfrom() {
        if($("#editUserForm").valid())
            return true;
        return false;
    }

    $("#editUserForm").validate({
        rules: {
            firstname: {required: true, normalizer: function( value ) { return $.trim( value ); }},
            lastname: {required: true, normalizer: function( value ) { return $.trim( value ); }},
            username: {required: true, normalizer: function( value ) { return $.trim( value ); }},
            email: {
                required: true,
                email: true
            }
        },
        messages: {
            firstname: "Please enter your firstname",
            lastname: "Please enter your lastname",
            username: "Please enter a username",
            email: "Please enter a valid email address"
        }
    });

</script>