<?php
include 'session_protect.php';
include 'role_check_admin.php';
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
    ?>


    <section class="content-header">
        <h1>
Add Users
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
                        <h3 class="box-title">Enter new user info</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="addUserForm" role="form" method="post" action="bll/add_user.php" onsubmit="return processfrom();">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="firstname">First Name</label>
                                <input type="text" class="form-control" id="firstname"  name="firstname" placeholder="First Name" >
                            </div>
                            <div class="form-group">
                                <label for="lastname">Last Name</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" >
                            </div>

                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="Username" >
                            </div>

                            <div class="form-group">
                                <label for="email">Email address</label>
                                <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" >
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="isadmin"> Admin
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
        if($("#addUserForm").valid())
            return true;
        return false;
    }

    $("#addUserForm").validate({
        rules: {
            firstname: { depends:function(){ $(this).val($.trim($(this).val())); return true;} },
            lastname: { depends:function(){ $(this).val($.trim($(this).val())); return true;} },
            username: { depends:function(){ $(this).val($.trim($(this).val())); return true;} },
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