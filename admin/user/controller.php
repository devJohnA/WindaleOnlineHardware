<?php

require_once '../../vendor/autoload.php';
use Sonata\GoogleAuthenticator\GoogleAuthenticator;
use Sonata\GoogleAuthenticator\GoogleQrUrl;

require_once ("../../include/initialize.php");

	 if (!isset($_SESSION['USERID'])){

      redirect(web_root."admin/index.php");

     }



$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';



switch ($action) {

	case 'generate_qr':
		generateQR();
		break;

	case 'add' :

	doInsert();

	break;

	

	case 'edit' :

	doEdit();

	break;

	

	case 'delete' :

	doDelete();

	break;



	case 'photos' :

	doupdateimage();

	break;



 

	}

   

	function generateQR() {
		try {
			// Validate required fields
			if (empty($_POST['U_USERNAME'])) {
				echo json_encode([
					'success' => false,
					'message' => 'Email is required for QR code generation'
				]);
				exit;
			}
	
			// Initialize Google Authenticator
			$gAuth = new GoogleAuthenticator();
			$secretKey = $gAuth->generateSecret();
			
			// Generate QR Code URL
			$qrCodeUrl = GoogleQrUrl::generate(
				$_POST['U_USERNAME'],
				$secretKey,
				'Windale Hardware'
			);
			
			// Return success response with QR code URL and secret key
			echo json_encode([
				'success' => true,
				'qrCodeUrl' => $qrCodeUrl,
				'secretKey' => $secretKey
			]);
			exit;
		} catch (Exception $e) {
			echo json_encode([
				'success' => false,
				'message' => 'Error generating QR code: ' . $e->getMessage()
			]);
			exit;
		}
	}
	
	
	function doInsert() {
		if (isset($_POST['save'])) {
			// Validate all required fields
			if (empty($_POST['U_NAME']) || 
				empty($_POST['U_USERNAME']) || 
				empty($_POST['U_PASS']) || 
				empty($_POST['secret_key'])) {  
				
				echo json_encode([
					'success' => false,
					'message' => 'All fields are required!'
				]);
				exit;
			}
	
			try {
				$user = New User();
				$user->U_NAME = $_POST['U_NAME'];
				$user->U_USERNAME = $_POST['U_USERNAME'];
				$user->U_CON = $_POST['U_CON'];
				$user->U_PASS = password_hash($_POST['U_PASS'], PASSWORD_DEFAULT);
				$user->U_ROLE = $_POST['U_ROLE'];
				$user->SECRET_KEY = $_POST['secret_key'];
	
				if ($user->create()) {
					// Remove the session message since we're handling it with JSON
					echo json_encode([
						'success' => true,
						'message' => 'New user added successfully! Please configure 2FA.'
					]);
					exit;
				} else {
					echo json_encode([
						'success' => false,
						'message' => 'Error creating user'
					]);
					exit;
				}
			} catch (Exception $e) {
				echo json_encode([
					'success' => false,
					'message' => 'Error creating user: ' . $e->getMessage()
				]);
				exit;
			}
		}
	}





	function doDelete(){

		

		// if (isset($_POST['selector'])==''){

		// message("Select the records first before you delete!","info");

		// redirect('index.php');

		// }else{



		// $id = $_POST['selector'];

		// $key = count($id);



		// for($i=0;$i<$key;$i++){



		 	



		

				$id = 	$_GET['id'];



				$user = New User();

	 		 	$user->delete($id);

			 

			$_SESSION['success'] = "User account deleted successfully!";

			redirect('index.php');

		// }

		// }



		

	}



	function doupdateimage(){

 

			$errofile = $_FILES['photo']['error'];

			$type = $_FILES['photo']['type'];

			$temp = $_FILES['photo']['tmp_name'];

			$myfile =$_FILES['photo']['name'];

		 	$location="photos/".$myfile;





		if ( $errofile > 0) {

				message("No Image Selected!", "error");

				redirect("index.php?view=view&id=". $_GET['id']);

		}else{

	 

				@$file=$_FILES['photo']['tmp_name'];

				@$image= addslashes(file_get_contents($_FILES['photo']['tmp_name']));

				@$image_name= addslashes($_FILES['photo']['name']); 

				@$image_size= getimagesize($_FILES['photo']['tmp_name']);



			if ($image_size==FALSE ) {

				message("Uploaded file is not an image!", "error");

				redirect("index.php?view=view&id=". $_GET['id']);

			}else{

					//uploading the file

					move_uploaded_file($temp,"photos/" . $myfile);

		 	

					 



						$user = New User();

						$user->USERIMAGE 			= $location;

						$user->update($_SESSION['USERID']);

						redirect("index.php");

						 

							

					}

			}

			 

		}

 

?>