<?php
include 'session_protect.php';
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
            Add Parameter
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
                        <h3 class="box-title">Enter Parameter Info</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="addParameterForm" role="form" method="post" action="bll/parameter_add.php " onsubmit="return processfrom();">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="parameter">Parameter</label>
                                <input type="text" class="form-control" id="parameter"  name="parameter" placeholder="Paramater">

                            </div>
                            <div class="form-group">
                                <label for="label">Label</label>
                                <input type="text" class="form-control" id="label" name="label" placeholder="Label">
                            </div>
                            <div class="form-group">
                                <label for="data_type">Data type</label>
                                <br/>
                                <select name="data_type" id="data_type" class="form-control" onchange="onDataTypeChange(this)">
                                    <option value="">Please Select data type</option>
                                    <option value="TEXT">Text</option>
                                    <option value="DROP_DOWN">Dropdown</option>
                                    <option value="READ_ONLY">Readonly</option>
                                </select>
                            </div>
                            <div class="form-group" id="data_type_value_field" style="display: none">
                                <label for="from">From</label>
                                <input type="number" class="form-control" min="0"  id="from" name="from" placeholder="From">
                                <label for="to">To</label>
                                <input type="number" class="form-control" min="1" id="to" name="to" placeholder="To"> <br />
                                <label for="allow_decimal">Allow Decimal </label> &nbsp; <input type="checkbox" id="allow_decimal" name="allow_decimal" />
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

    function processfrom() {
        if($("#addParameterForm").valid())
            return true;
        return false;
    }

    $("#addParameterForm").validate({
        rules: {
            parameter: { required: true, normalizer: function( value ) { return $.trim( value ); } },
            label: { required: true, normalizer: function( value ) { return $.trim( value ); } },
            data_type: { required: true, normalizer: function( value ) { return $.trim( value ); } }
        },
        messages: {
            parameter: "Please enter parameter.",
            label: "Please enter label.",
            data_type: "Please select data type."
        }
    });

</script>
