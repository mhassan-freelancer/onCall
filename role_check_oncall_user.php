<?php
require 'includes/config.php';
if($_SESSION['on_call_is_super_admin'] == 0 && $_SESSION['on_call_is_admin'] == 0 && $_SESSION['on_call_is_oncall'] == 0)
{
    header("location:".BASE_URL."index.php");
}
