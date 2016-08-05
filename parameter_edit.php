<?php
session_start();
print_r($_SESSION);

$parameterId= 0;
if(isset($_GET['id']))
{
    $parameterId = $_GET['id'];

}
require  'vendor/autoload.php';
use Respect\Validation\Validator as v;

if(!v::numeric()->validate($parameterId))
{
    $_SESSION['error'] ="Invalid Parameter ID.";
    header('location:/onCall/parameters.php');
}

if(!v::intVal()->min(1,true)->validate($parameterId))
{
    $_SESSION['error'] ="Invalid Parameter ID.";
    header('location:/onCall/parameters.php');
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
    $parameterInfo = getParameterInfo($parameterId);
    if($parameterInfo == null) {
        $_SESSION['error'] ="Invalid Parameter ID.";
        header('location:/onCall/parameters.php');
    }
    ?>

    <section class="content-header">
        <h1>
            Update Parameter
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
                        <h3 class="box-title">Update Parameter Info</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form role="form" method="post" action="bll/parameter_edit.php">
                        <input type="hidden" id="parameterId" name="parameterId" value="<?php echo $parameterId ?>">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="firstname">Parameter</label>
                                <input type="text" class="form-control" id="parameter"  name="parameter" placeholder="Parameter" value="<?php echo $parameterInfo['parameter'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="lastname">Label</label>
                                <input type="text" class="form-control" id="label"  name="label" placeholder="Label" value="<?php echo $parameterInfo['label'] ?>">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Data type</label>
                                <br/>
                                <select name="data_type" id="data_type" class="form-control" onchange="onDataTypeChange(this)">
                                    <option value="">Please Select data type</option>
                                    <?php
                                        if($parameterInfo['data_type'] == 'TEXT')
                                            echo '<option value="TEXT" selected="selected">Text</option>';
                                        else
                                            echo '<option value="TEXT">Text</option>';

                                        if($parameterInfo['data_type'] == 'DROP_DOWN')
                                            echo '<option value="DROP_DOWN" selected="selected">Dropdown</option>';
                                        else
                                            echo '<option value="DROP_DOWN">Dropdown</option>';

                                        if($parameterInfo['data_type'] == 'READ_ONLY')
                                            echo '<option value="READ_ONLY" selected="selected">Readonly</option>';
                                        else
                                            echo '<option value="READ_ONLY">Readonly</option>';
                                    ?>
                                </select>
                            </div>
                            <?php
                                $dataTypeValues = $parameterInfo['data_type_values'];
                                $arr = explode(",", $dataTypeValues);
                            ?>
                            <div class="form-group" id="data_type_value_field" style="display: none">
                                <label for="value">From</label>
                                <input type="number" class="form-control" min="0" value="<?php echo trim($arr[0]) ?>" id="from" name="from" placeholder="From">
                                <label for="value">To</label>
                                <input type="number" class="form-control" min="1" value="<?php echo trim($arr[1]) ?>" id="to" name="to" placeholder="To"> <br />
                                <label for="allow_decimal">Allow Decimal </label> &nbsp;
                                <?php
                                    if($parameterInfo['allow_decimal']== 1)
                                        echo '<input type="checkbox" checked="checked" id="allow_decimal" name="allow_decimal" />';
                                    else
                                        echo '<input type="checkbox" id="allow_decimal" name="allow_decimal"/>';
                                ?>
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
    function onDataTypeChange(scope) {
        var val = scope.value.trim();
        if(val == "DROP_DOWN") {
            document.getElementById("data_type_value_field").style.display = 'block';
        } else {
            document.getElementById("data_type_value_field").style.display = 'none';
        }
    }
</script>
<?php
if($parameterInfo['data_type'] == 'DROP_DOWN')
{
    echo '<script type="application/javascript"> onDataTypeChange(document.getElementById("data_type")); </script>';
}
?>
