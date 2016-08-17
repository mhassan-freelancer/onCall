<?php 
include 'session_protect.php';
include 'role_check_administrator.php';
include 'header.php';
include 'header_scripts.php';
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
            Add Event
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
                        <h3 class="box-title">Enter Event Info</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form id="addEventForm" role="form" method="post" action="bll/events/event_add.php" onsubmit="return processfrom();">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="event">Event</label>
                                <textarea class="form-control" id="event"  name="event" placeholder="Event Text"></textarea>
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
        if($("#addEventForm").valid())
            return true;
        return false;
    }

    $("#addEventForm").validate({
        rules: {
            event: { required: true, normalizer: function( value ) { return $.trim( value ); } }
        },
        messages: {
            event: "Please enter event text."
        }
    });

</script>
