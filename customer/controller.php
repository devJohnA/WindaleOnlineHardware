<?php
	// require '../vendor/autoload.php';
	// use Clarifai\API\ClarifaiClient;
	// use Clarifai\API\Inputs;
require_once ("../include/initialize.php");

$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';

switch ($action) {
	case 'add' :
	doInsert();
	break;
	
	case 'edit' :
	doEdit();
	break;
	
	case 'eydit' :
		doEydit();
		break;

	case 'delete' :
	doDelete();
	break;

 

	case 'processorder' :
	processorder();
	break;

	case 'addwish' :
	addwishlist();
	break;

	case 'wishlist' :
	processwishlist();
	break;

	case 'photos' :
	doupdateimage();
	break;

	case 'changepassword' :
	doChangePassword();
	break;


	}

   
	function doInsert() {
		header('Content-Type: application/json');
		global $mydb;
		
		if(isset($_POST['submit'])) {
			try {
				// Check if the email already exists
				$email = trim($_POST['CUSUNAME']);
				$existingCustomer = new Customer();
				$isEmailExists = $existingCustomer->find_customer_by_email($email);
	
				if ($isEmailExists) {
					echo json_encode(['status' => 'info', 'message' => 'Email already exists! Please use a different email.']);
					exit;
				}
	
				$customer = new Customer();
				$customer->FNAME = $_POST['FNAME'];
				$customer->LNAME = $_POST['LNAME'];
				$customer->CITYADD = $_POST['CITYADD'];
				$customer->LMARK = $_POST['LMARK'];
				// $customer->GENDER = $_POST['GENDER'];
				$customer->PHONE = $_POST['PHONE'];
				$customer->CUSUNAME = $email;
				$customer->CUSPASS = password_hash($_POST['CUSPASS'], PASSWORD_DEFAULT);
				$customer->DATEJOIN = date('Y-m-d H:i:s');
				$customer->TERMS = 1;
				$customer->create();
	
				$h_upass = trim($_POST['CUSPASS']); // Plain password for authentication
				$user = new Customer();
				$res = $user->cusAuthentication($email, $h_upass);
	
				if($_POST['proid'] == '') {
					$response = [
						'status' => 'success', 
						'message' => 'You are now successfully registered. It will redirect to your order details.', 
						'redirect' => web_root . "index.php?q=product"
					];
				} else {
					$proid = $_POST['proid'];
					$id = $mydb->insert_id();
					$query = "INSERT INTO `tblwishlist` (`PROID`, `CUSID`, `WISHDATE`, `WISHSTATS`) VALUES ('{$proid}','{$id}','" . date('Y-m-d') . "',0)";
					$mydb->setQuery($query);
					$mydb->executeQuery();
					$response = [
						'status' => 'success', 
						'message' => 'You are now successfully registered. It will redirect to your profile.', 
						'redirect' => web_root . "index.php?q=profile"
					];
				}
	
				// Log the response for debugging
				error_log("Response: " . json_encode($response));
	
				echo json_encode($response);
			} catch (Exception $e) {
				error_log("Error in doInsert: " . $e->getMessage());
				echo json_encode(['status' => 'error', 'message' => 'An error occurred during registration.']);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
		}
		exit;
	}
	
 
	function doEdit() {
		header('Content-Type: application/json');
	
		if(isset($_POST['save']) || isset($_POST['FNAME'])) {  
			try {
				$customer = New Customer();
				$customer->FNAME = $_POST['FNAME'];
				$customer->LNAME = $_POST['LNAME'];
				$customer->CITYADD = $_POST['CITYADD'];
				$customer->LMARK = $_POST['LMARK'];
				// $customer->GENDER = $_POST['GENDER'];
				$customer->PHONE = $_POST['PHONE'];
				$customer->CUSUNAME = $_POST['CUSUNAME'];
				
				// Check if any changes were made
				$original = $customer->single_customer($_SESSION['CUSID']);
				$changes_made = false;
				
				foreach($customer as $key => $value) {
					if($original->$key != $value) {
						$changes_made = true;
						break;
					}
				}
				
				if($changes_made) {
					$customer->update($_SESSION['CUSID']);
					echo json_encode(['status' => 'success']);
				} else {
					echo json_encode(['status' => 'no_changes']);
				}
			} catch (Exception $e) {
				echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
			}
		} else {
			echo json_encode(['status' => 'error', 'message' => 'Form data not received properly']);
		}
		exit;
	}
	
	function doEydit(){
		global $mydb; 
	
		if ($_GET['actions']=='confirm') {
			$status = 'Confirmed';    
			$remarks = 'Your order has been accepted.';
			$delivered = Date('Y-m-d');
		} elseif ($_GET['actions']=='pending') {
			$status = 'Pending'; 
			$remarks = 'Your order is on process.';
			$delivered = Date('Y-m-d');
		} elseif ($_GET['actions']=='deliver') {
			$status = 'On The Way';    
			$remarks = 'Your order is currently being processed and prepared for delivery.';
			$delivered = Date('Y-m-d');
		} elseif ($_GET['actions']=='approaching') {
			$status = 'Approaching Destination';    
			$remarks = 'The delivery is nearing your destination. Get ready for arrival.';
			$delivered = Date('Y-m-d');
		} elseif ($_GET['actions']=='receive') {
			$status = 'Received';    
			$remarks = 'Order has been already received.';
			$delivered = Date('Y-m-d');
		} elseif ($_GET['actions']=='cancel'){
			// Cancelling the order
			$status = 'Cancelled';
			$remarks = 'You cancelled your order.';
			$delivered = Date('Y-m-d');
	
			// Restore product quantities
			$query = "SELECT * FROM `tblorder` WHERE `ORDEREDNUM`=".$_GET['id'];
			$mydb->setQuery($query);
			$order_items = $mydb->loadResultList(); 
	
			foreach ($order_items as $item) {
				$sql_update_quantity = "UPDATE `tblproduct` SET `PROQTY` = `PROQTY` + {$item->ORDEREDQTY} WHERE `PROID` = {$item->PROID}";
				$mydb->setQuery($sql_update_quantity);
				$mydb->executeQuery();
			}
			$_SESSION['order_cancelled'] = true;
		}
	
		// Update order status
		$order = New Order();
		$order->STATS = $status;
		$order->pupdate($_GET['id']);
	
		// Update summary
		$summary = New Summary();
		$summary->ORDEREDSTATS = $status;
		$summary->ORDEREDREMARKS = $remarks;
		$summary->CLAIMEDADTE = $delivered;
		$summary->HVIEW = 0;
		$summary->update($_GET['id']);
	
		// Insert message
		// $query = "SELECT * FROM `tblsummary` s ,`tblcustomer` c 
		// 	WHERE s.`CUSTOMERID`=c.`CUSTOMERID` and ORDEREDNUM=".$_GET['id'];
		// $mydb->setQuery($query);
		// $cur = $mydb->loadSingleResult();
	
		// $sql = "INSERT INTO `messageout` (`Id`, `MessageTo`, `MessageFrom`, `MessageText`) 
		// 	VALUES (Null, '".$cur->PHONE."','Janno','FROM Bachelor of Science and Entrepreneurs : Your order has been '".$status. "'. The amount is '".$cur->PAYMENT. "')";
		// $mydb->setQuery($sql);
		// $mydb->executeQuery();
	
		// Insert messages for product owners
		// $query = "SELECT * 
		// 	FROM  `tblproduct` p,`tblorder` o,  `tblsummary` s
		// 	WHERE  p.`PROID` = o.`PROID` 
		// 	AND o.`ORDEREDNUM` = s.`ORDEREDNUM`  
		// 	AND o.`ORDEREDNUM`=".$_GET['id'];
		// $mydb->setQuery($query);
		// $cur = $mydb->loadResultList(); 
		// foreach ($cur as $result) {
		// 	$sql = "INSERT INTO `messageout` (`Id`, `MessageTo`, `MessageFrom`, `MessageText`) 
		// 		VALUES (Null, '".$result->OWNERPHONE."','Janno','FROM Bachelor of Science and Entrepreneurs : Your  product has been ordered'. The amount is '".$result->PAYMENT. "')";
		// 	$mydb->setQuery($sql);
		// 	$mydb->executeQuery();
		// }
	
		header('Content-Type: application/json');
    echo json_encode(['status' => 'success', 'message' => "Order has been {$summary->ORDEREDSTATS}!"]);
    exit;
	}
	
	function doDelete(){

		if(isset($_SESSION['U_ROLE'])=='Customer'){

			if (isset($_POST['selector'])==''){
			message("Select the records first before you delete!","error");
			redirect(web_root.'index.php?page=9');
			}else{
		
			$id = $_POST['selector'];
			$key = count($id);

			for($i=0;$i<$key;$i++){ 

			$order = New Order();
			$order->delete($id[$i]);
 
			message("Order has been Deleted!","info");
			redirect(web_root."index.php?q='product'"); 


		} 


		}
	}else{

		if (isset($_POST['selector'])==''){
			message("Select the records first before you delete!","error");
			redirect('index.php');
			}else{

			$id = $_POST['selector'];
			$key = count($id);

			for($i=0;$i<$key;$i++){ 

			$customer = New Customer();
			$customer->delete($id[$i]);

			$user = New User();
			$user->delete($id[$i]);

			message("Customer has been Deleted!","info");
			redirect('index.php');

			}
		}

	}
		
	}

	 
	function processorder() {
		if (empty($_SESSION['gcCart']) || empty($_POST['ORDEREDNUM'])) {
			header('Content-Type: application/json');
			echo json_encode(['success' => false, 'message' => 'Your cart is empty!']);
			exit;
		}
	
		$orderNum = $_POST['ORDEREDNUM'];
		$paymentMethod = $_POST['paymethod'];
	
		// Create the summary (main order) entry first
		$summary = New Summary();
		$summary->ORDEREDDATE    = date("Y-m-d h:i:s");
		$summary->CUSTOMERID     = $_SESSION['CUSID'];
		$summary->ORDEREDNUM     = $orderNum;
		$summary->DELFEE         = $_POST['PLACE'];
		$summary->PAYMENTMETHOD  = $paymentMethod;
		$summary->PAYMENT        = $_POST['alltot'];
		$summary->ORDEREDSTATS   = 'Pending';
		$summary->CLAIMEDDATE    = $_POST['CLAIMEDDATE'];
		$summary->ORDEREDREMARKS = 'Your order is on process.';
		$summary->HVIEW          = 0;
		$summary->create();
	
		// Process each item in the cart
		foreach ($_SESSION['gcCart'] as $item) {
			$order = New Order();
			$order->PROID         = $item['productid'];
			$order->ORDEREDQTY    = $item['qty'];
			$order->ORDEREDPRICE  = $item['price'];
			$order->ORDEREDNUM    = $orderNum;
			$order->create();
	
			// Deduct quantity from product
			$product = New Product();
			$product->qtydeduct($item['productid'], $item['qty']);
		}
	
		// Update the autonumber
		$autonumber = New Autonumber();
		$autonumber->auto_update('ordernumber');
	
		// Clear the cart and order details from session
		unset($_SESSION['gcCart']);
		unset($_SESSION['orderdetails']);
	
		header('Content-Type: application/json');
		if ($paymentMethod === 'GCash') {
			// For GCash, we'll handle the payment in create_paymongo_source.php
			echo json_encode(['success' => true, 'message' => 'Order created successfully. Redirecting to GCash...']);
		} else {
			echo json_encode(['success' => true, 'message' => 'Order created successfully!']);
		}
		exit;
	}
			 


	function processwishlist(){
		if(isset($_GET['wishid'])){

		  $query ="UPDATE `tblwishlist` SET `WISHSTATS`=1  WHERE `WISHLISTID`=" .$_GET['wishid'];
		 $res =  mysql_query($query) or die(mysql_error());
		 if (isset($res)){
		 		message("Product has been removed in your wishlist", "success"); 		 
			redirect(web_root."index.php?q=profile");
		 }

		

		}
		

		}
			 

	function addwishlist(){
		global $mydb;
			$proid = $_GET['proid'];
			 	$id =$_SESSION['CUSID'];

	 $query="SELECT * FROM `tblwishlist` WHERE  CUSID=".$id." AND `PROID` ="  .$proid ;
	 $mydb->setQuery($query);
	 $result = $mydb->executeQuery();
	 var_dump($query);exit;
	 $maxrow = $mydb->num_rows($result);
	 // $row = mysql_fetch_assoc($result);
	
	if($maxrow>0){
				message("Product is already added to your wishlist", "error"); 		 
			redirect(web_root."index.php?q=profile"); 
		}else{
			$query ="INSERT INTO `tblwishlist` (`PROID`, `CUSID`, `WISHDATE`, `WISHSTATS`)  VALUES ('{$proid}','{$id}','".DATE('Y-m-d')."',0)";
			$mydb->setQuery($query);
			$mydb->executeQuery();
			  	// mysql_query($query) or die(mysql_error());
			 
	 	message("Product has been added to your wishlist", "success"); 		 
			redirect(web_root."index.php?q=profile"); 
		}
			 
			 
	 

		}
		function doupdateimage() {
			$allowed_types = ['image/png', 'image/jpg', 'image/jpeg'];
			$allowed_extensions = ['jpg', 'jpeg', 'png'];
			$max_file_size = 8000000; // 8MB in bytes
		
			$errorfile = $_FILES['photo']['error'];
			$type = $_FILES['photo']['type'];
			$temp = $_FILES['photo']['tmp_name'];
			$original_filename = $_FILES['photo']['name'];
		
			if ($errorfile > 0) {
				message("No Image Selected!", "error");
				redirect(web_root . "index?q=profile");
				return;
			}
		
			if ($_FILES['photo']['size'] > $max_file_size) {
				message("Uploaded file exceeds the maximum size of 8 MB!", "error");
				redirect(web_root . "index?q=profile");
				return;
			}
		
			$file_parts = explode('.', strtolower($original_filename));
			if (count($file_parts) > 2) {
				message("Multiple file extensions are not allowed!", "error");
				redirect(web_root . "index?q=profile");
				return;
			}
		
			$extension = end($file_parts);
			if (!in_array($extension, $allowed_extensions)) {
				message("Invalid file extension! Only .jpg, .jpeg, and .png are allowed.", "error");
				redirect(web_root . "index?q=profile");
				return;
			}
		
			$finfo = finfo_open(FILEINFO_MIME_TYPE);
			$detected_type = finfo_file($finfo, $temp);
			finfo_close($finfo);
		
			if (!in_array($detected_type, $allowed_types)) {
				message("Uploaded file is not a valid image format (PNG, JPG, JPEG)!", "error");
				redirect(web_root . "index?q=profile");
				return;
			}
		
			$image_size = getimagesize($temp);
			if ($image_size === FALSE) {
				message("Uploaded file is not a valid image!", "error");
				redirect(web_root . "index?q=profile");
				return;
			}
		
			$new_filename = uniqid() . '.' . $extension;
			$location = "customer_image/" . $new_filename;
		
			if (!validate_real_image($temp)) {
				message("File content validation failed! File is not a real image.", "error");
				redirect(web_root . "index?q=profile");
				return;
			}
		
			if (!scan_file_with_virustotal($temp)) {
				message("File failed VirusTotal scan! Possible malicious file.", "error");
				redirect(web_root . "index?q=profile");
				return;
			}
		
			if (!move_uploaded_file($temp, $location)) {
				message("Failed to upload file!", "error");
				redirect(web_root . "index?q=profile");
				return;
			}
		
			try {
				$customer = New Customer();
				$customer->CUSPHOTO = $location;
				$customer->update($_SESSION['CUSID']);
		
				$_SESSION['upload_success'] = true;
				redirect(web_root . "index?q=profile");
			} catch (Exception $e) {
				if (file_exists($location)) {
					unlink($location);
				}
				message("Database update failed!", "error");
				redirect(web_root . "index?q=profile");
			}
		}
		
		/**
		 * Validate file content to ensure it is a real image.
		 * @param string $file_path The temporary path of the uploaded file.
		 * @return bool Returns true if the file is a valid image, false otherwise.
		 */
		function validate_real_image($file_path) {
			try {
				$image = imagecreatefromstring(file_get_contents($file_path));
				if ($image === false) {
					return false;
				}
				imagedestroy($image);
		
				$image_info = getimagesize($file_path);
				if ($image_info === false) {
					return false;
				}
		
				return true;
			} catch (Exception $e) {
				return false;
			}
		}
		
		/**
		 * Scan file with VirusTotal API.
		 * @param string $file_path The temporary path of the uploaded file.
		 * @return bool Returns true if the file passes the scan, false otherwise.
		 */
		function scan_file_with_virustotal($file_path) {
			$api_key = 'ac58407d2e495be18934e259cf78479f85d166c8e637650426c5573d0f397b8b';
			$url = 'https://www.virustotal.com/api/v3/files';
		
			// Prepare file for upload
			$cfile = curl_file_create($file_path);
		
			// Initialize CURL
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => $cfile]);
			curl_setopt($ch, CURLOPT_HTTPHEADER, [
				'x-apikey: ' . $api_key,
			]);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
			// Execute CURL
			$response = curl_exec($ch);
			curl_close($ch);
		
			// Decode response
			$result = json_decode($response, true);
		
			// Check if the file is clean
			if (isset($result['data']['attributes']['last_analysis_stats']['malicious']) &&
				$result['data']['attributes']['last_analysis_stats']['malicious'] == 0) {
				return true;
			}
		
			return false;
		}

		function doChangePassword() {
			if (isset($_POST['save'])) {
				$customer = new Customer();
				$customer->CUSPASS = password_hash($_POST['CUSPASS'], PASSWORD_DEFAULT);
				$customer->update($_SESSION['CUSID']);
				$_SESSION['success_message'] = "Password has been updated successfully!";
				redirect(web_root . 'index?q=profile');
			}
		}
 
 
?>