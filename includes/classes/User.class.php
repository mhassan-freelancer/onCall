<?php
require_once ("easyCRUD.class.php");
class User  Extends Crud {

    protected $table = 'user';

    # Primary Key of the table
    protected $pk  = 'id';

}