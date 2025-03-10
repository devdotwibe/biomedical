<?php
/*
 * jQuery File Upload Plugin PHP Example 5.14
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
 
ob_start("ob_gzhandler");
session_start();

include_once("../includes/config.php");
error_reporting(E_ALL | E_STRICT);

set_time_limit(0);

$_SESSION['inserted_ids']	= '';

$upload_folder		= $_REQUEST['folder'];  
$uploader    		= new uploader($upload_folder);

?>