<?php include 'session_protect.php';
include 'role_check_administrator.php';
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
            Add New Config
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
                        <h3 class="box-title">Enter Config Info</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="addConfigForm" role="form" method="post" action="bll/config/config_add.php" onsubmit="return processfrom();">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="module">Modules</label>
                                <br/>
                                <?php
                                $modules = getModules();
                                ?>
                                <select name="module" id="module" class="form-control">

                                    <option value="">Please Select Module</option>
                                    <?php
                                    foreach ($modules as $module)
                                    {
                                        echo '<option value="'.$module['id'].'">'.$module['name'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="parameter">Parameters</label>
                                <br/>
                                <?php
                                $parameters = getParameters();
                                ?>
                                <select name="parameter" id="parameter" class="form-control">

                                    <option value="">Please Select parameter</option>
                                    <?php
                                    foreach ($parameters as $parameter)
                                    {
                                        echo '<option value="'.$parameter['parameter_id'].'">'.$parameter['parameter'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="value">Value</label>
                                <input type="text" class="form-control" id="value" name="value" placeholder="Value">
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
        if($("#addConfigForm").valid())
            return true;
        return false;
    }

    $("#addConfigForm").validate({
        rules: {
            module: { depends:function(){ $(this).val($.trim($(this).val())); return true;} },
            parameter: { depends:function(){ $(this).val($.trim($(this).val())); return true;} },
            value: { depends:function(){ $(this).val($.trim($(this).val())); return true;} }
        },
        messages: {
            module: "Please select module.",
            parameter: "Please select parameter.",
            value: "Please enter value."
        }
    });

</script>