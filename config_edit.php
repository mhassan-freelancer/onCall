<?php
session_start();
print_r($_SESSION);

$configId= 0;
if(isset($_GET['id']))
{
    $configId = $_GET['id'];
}
require  'vendor/autoload.php';
use Respect\Validation\Validator as v;

if(!v::numeric()->validate($configId))
{
    $_SESSION['error'] ="Invalid Config ID.";
    header('location:/onCall/config.php');
}

if(!v::intVal()->min(1,true)->validate($configId))
{
    $_SESSION['error'] ="Invalid Config ID.";
    header('location:/onCall/config.php');
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
    $configInfo = getConfigInfo($configId);
    if($configInfo == null) {
        $_SESSION['error'] = "Invalid Config Id.";
        header('location:/onCall/config.php');
    }
    ?>

    <section class="content-header">
        <h1>
            Update Config
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
                        <h3 class="box-title">Update Config Info</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="bll/config/config_edit.php">
                        <input type="hidden" id="configId" name="configId" value="<?php echo $configId ?>">
                        <div class="box-body">

                            <div class="form-group">
                                <label for="exampleInputPassword1">Modules</label>
                                <br/>
                                <?php
                                $modules = getModules();
                                ?>
                                <select name="module" id="module" class="form-control">

                                    <option value="0">Please Select Module</option>
                                    <?php
                                    foreach ($modules as $module)
                                    {
                                        if($configInfo['module_id'] == $module['id'])
                                            echo '<option value="'.$module['id'].'" selected="selected">'.$module['name'].'</option>';
                                        else
                                            echo '<option value="'.$module['id'].'">'.$module['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Parameters</label>
                                <br/>
                                <?php
                                $parameters = getParameters();
                                ?>
                                <select name="parameter" id="parameter" class="form-control">

                                    <option value="0">Please Select parameter</option>
                                    <?php
                                    foreach ($parameters as $parameter)
                                    {
                                        if($configInfo['parameter_id'] == $parameter['parameter_id'])
                                            echo '<option value="'.$parameter['parameter_id'].'" selected="selected">'.$parameter['parameter'].'</option>';
                                        else
                                        echo '<option value="'.$parameter['parameter_id'].'">'.$parameter['parameter'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="value">Value</label>
                                <input type="text" class="form-control" id="value"  name="value" value="<?php echo $configInfo['value'] ?>">
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
