<?php
defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);
// sd
// Adjust the path to 'initialize.php' correctly
require_once(__DIR__ . DS . 'include' . DS . 'initialize.php');
// if(isset($_SESSION['IDNO'])){
// 	redirect(web_root.'index.php');

// }

$content='home.php';
$view = (isset($_GET['q']) && $_GET['q'] != '') ? $_GET['q'] : '';




switch ($view) {
 

	case 'product' :
        $title="Products";	
		$content='menu.php';		
		break;
 	case 'cart' :
        $title="Cart List";	
		$content='cart.php';		
		break;
 	case 'profile' :
        $title="Profile";	
		$content='customer/profile.php';		
		break;

	case 'trackorder' :
        $title="Track Order";	
		$content='customer/trackorder.php';		
		break;

	case 'orderdetails' :  

         If(!isset($_SESSION['orderdetails'])){
         $_SESSION['orderdetails'] = "Order Details";
		} 
		$content='customer/orderdetails.php';	

		if( isset($_SESSION['orderdetails'])){
		if (is_array($_SESSION['orderdetails']) && count($_SESSION['orderdetails'])>0){
				$title = 'Cart List' . '| <a href="">Order Details</a>';
				}else{
					$title = 'Cart List' ;
				}
		    } 
		break;

	case 'billing' : 	
	 If(!isset($_SESSION['billingdetails'])){
         $_SESSION['billingdetails'] = "Order Details";
		} 
		$content='customer/customerbilling.php';	
		if( isset($_SESSION['billingdetails'])){
      if (@count($_SESSION['billingdetails'])>0){
        	$title = 'Cart List' . '| <a href="">Billing Details</a>';
		      }
		    } 	
		break;

	case 'contact' :
        $title="Contact Us";	
		$content='contact.php';		
		break;
 	case 'single-item' :
        $title="Product";	
		$content='single-item.php';		
		break;

	case 'forgot-password' :
        $title="Password Recovery";	
		$content='forgot-password.php';		
		break;
    case 'forgot-password' :
        $title="Reset Code";	
		$content='reset-code.php';		
		break;
    case 'new-password' :
        $title="Create New Password";	
		$content='new-password.php';		
		break;
    case 'password-changed' :
        $title="Password Changed";	
		$content='password-changed.php';		
		break;
	default :
	    $title="Home";	
		$content ='home.php';		

}

    //   echo $view; 
    
 
require_once("theme/templates.php");
 

?>

