<?php

require_once("../../include/initialize.php");

//checkAdmin();

  	 if (!isset($_SESSION['USERID'])){

      redirect(web_root."admin/index.php");

     }



$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';

$header=$view;

$title="Dashboard";

switch ($view) {

	case 'top' :

		$content    = 'top.php';		

		break;
        
	default :

		$content    = 'top.php';		

}

require_once ("../theme/templates.php");

?>