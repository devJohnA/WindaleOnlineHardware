<?php

require_once("../../include/initialize.php");

//checkAdmin();

	# code...

if(!isset($_SESSION['USERID'])){

	redirect(web_root."admin/index.php");

}



$view = (isset($_GET['view']) && $_GET['view'] != '') ? $_GET['view'] : '';



	$header=$view;

	$title="Products/"; 

	switch ($view) {



	case 'list' :

	 

		$content    = 'list.php';		

		break;



	case 'add' : 

		$content    = 'add.php';		

		break;



	case 'edit' : 

		$content    = 'edit.php';		

		break;



	case 'view' : 

		$content    = 'view.php';

		break;

  



  	default :

	$title="Products";

		$content    = 'list.php';

	}





   

 

require_once ("../theme/templates.php");

?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php
        if(isset($_SESSION['success_message'])) {
            echo "Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '" . $_SESSION['success_message'] . "',
                confirmButtonText: 'OK'
            });";
            unset($_SESSION['success_message']);
        }
        if(isset($_SESSION['error_message'])) {
            echo "Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '" . $_SESSION['error_message'] . "',
                confirmButtonText: 'OK'
            });";
            unset($_SESSION['error_message']);
        }
        ?>
    });
</script>