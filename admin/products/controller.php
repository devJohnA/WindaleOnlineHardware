<?php

require_once ("../../include/initialize.php");

	 



$action = (isset($_GET['action']) && $_GET['action'] != '') ? $_GET['action'] : '';



switch ($action) {

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



	case 'banner' :

	setBanner();

	break;



 case 'discount' :

	setDiscount();

	break;

	}



   

	function doInsert() {
		if(isset($_POST['save'])) {
			$useExistingImage = empty($_FILES['image']['name']) && !empty($_POST['existingImage']);
			
			if ($useExistingImage) {
				// Use the existing image
				$sourceFile = "../stock/upload/" . $_POST['existingImage'];
				$myfile = $_POST['existingImage'];
			} else {
				// Handle new image upload
				$sourceFile = $_FILES['image']['tmp_name'];
				$myfile = $_FILES['image']['name'];
			}
	
			$location = "uploaded_photos/" . $myfile;
	
			// Copy or move the file to uploaded_photos/
			if (!copy($sourceFile, $location)) {
				message("Failed to copy image file!", "error");
				redirect("index.php?view=add");
				return;
			}
	
			if ($_POST['PRODESC'] == "" OR $_POST['PROPRICE'] == "") {
				$messageStats = false;
				message("All fields are required!", "error");
				redirect('index.php?view=add');
			} else {
				// Get the product ID
				$autonumber = New Autonumber();
				$res = $autonumber->set_autonumber('PROID');
	
				// Insert new product data
				$product = New Product(); 
				$product->PROID = $res->AUTO;
				$product->IMAGES = $location;
				$product->PRODESC = $_POST['PRODESC'];
				$description = preg_replace('/\r\n|\r|\n/', '<br />', $_POST['Description']);
				$description = str_replace(array("\r", "\n"), '', $description);
				$product->Description = $description;
				$product->CATEGID = $_POST['CATEGORY'];
				$product->PROQTY = $_POST['PROQTY'];
				$product->PROPRICE = $_POST['PROPRICE']; 
				$product->PROSTATS = 'Available';
				$product->create();
	
				// Insert promo data
				$promo = New Promo();  
				$promo->PROID = $res->AUTO;  
				$promo->PRODISPRICE = $_POST['PROPRICE'];     
				$promo->create();
	
				// Update autonumber
				$autonumber->auto_update('PROID');
	
				$productName = $_POST['PRODESC'];
				$productQty = $_POST['PROQTY'];
	
				// Update stock query
				$mydb = new Database();
				$updateStockQuery = "UPDATE stocks SET productStock = productStock - {$productQty} WHERE productName = '{$productName}'";
				$mydb->setQuery($updateStockQuery);
				$result = $mydb->executeQuery();
	
				if ($mydb->affected_rows() > 0) {
					$_SESSION['success_message'] = "New Product created successfully!";
				} else {
					$_SESSION['error_message'] = "Failed to update stock or no such product found!";
				}
	
				redirect("index.php?view=list");
			}
		}
	}

 

 

	function doEdit() {
		if (isset($_POST['save'])) {
			try {
				$product = new Product();
				$product->PRODESC = $_POST['PRODESC'];
				$description = preg_replace('/\r\n|\r|\n/', '<br />', $_POST['Description']);
				$description = str_replace(array("\r", "\n"), '', $description);
				$product->Description = $description;
				$product->CATEGID = $_POST['CATEGORY'];
				$product->PROPRICE = $_POST['PROPRICE'];
				$currentProduct = $product->single_product($_POST['PROID']); // Retrieve current product data
	
				$stockAdjustment = intval($_POST['STOCK_ADJUSTMENT']);
				$hasChanges = false;
	
				// Check if there are any changes in the product details
				if ($product->PRODESC != $currentProduct->PRODESC ||
					$product->Description != $currentProduct->Description ||
					$product->CATEGID != $currentProduct->CATEGID ||
					$product->PROPRICE != $currentProduct->PROPRICE) {
					$hasChanges = true;
				}
	
				if ($hasChanges || $stockAdjustment > 0) {
					if ($product->update($_POST['PROID'])) {
						if ($stockAdjustment > 0) {
							$mydb = new Database();
							
							// Check available stock in the stocks table
							$checkStockQuery = "SELECT productStock FROM stocks WHERE productName = '{$product->PRODESC}'";
							$mydb->setQuery($checkStockQuery);
							$stockResult = $mydb->loadSingleResult();
							
							if ($stockResult && $stockResult->productStock >= $stockAdjustment) {
								$newQty = $currentProduct->PROQTY + $stockAdjustment;
	
								// Update stock
								$updateStockQuery = "UPDATE stocks SET productStock = productStock - {$stockAdjustment} WHERE productName = '{$product->PRODESC}'";
								$mydb->setQuery($updateStockQuery);
								$result = $mydb->executeQuery();
	
								if ($mydb->affected_rows() > 0) {
									// Update the product quantity
									$product->PROQTY = $newQty;
									$product->update($_POST['PROID']);
									$_SESSION['success_message'] = "Product updated and stock added successfully!";
								} else {
									$_SESSION['info_message'] = "Product updated, but failed to update stock!";
								}
							} else {
								$_SESSION['error_message'] = "Not enough stock available in inventory!";
							}
						} else {
							$_SESSION['success_message'] = "Product updated successfully!";
						}
					} else {
						$_SESSION['error_message'] = "Failed to update product.";
					}
				} else {
					$_SESSION['info_message'] = "No changes made";
				}
			} catch (Exception $e) {
				$_SESSION['error_message'] = "Error updating product: " . $e->getMessage();
			}
			redirect("index.php");
		}
	}
	




	


	function doDelete(){



 

 



		if (isset($_POST['selector'])==''){

			message("Select the records first before you delete!","error");

			redirect('index.php');

			}else{



			$id = $_POST['selector'];

			$key = count($id);



			for($i=0;$i<$key;$i++){ 



			$product = New Product();

			$product->delete($id[$i]);

 



			$stockin = New StockIn();

			$stockin->delete($id[$i]);



			$promo = New Promo();   

			$promo->delete($id[$i]);



			message("Product has been Deleted!","info");

			redirect('index.php');



			}

		}



	}

	function minusproduct(){
		
	}
		 

	function doupdateimage(){

 

			$errofile = $_FILES['photo']['error'];

			$type = $_FILES['photo']['type'];

			$temp = $_FILES['photo']['tmp_name'];

			$myfile =$_FILES['photo']['name'];

		 	$location="uploaded_photos/".$myfile;





		if ( $errofile > 0) {

				message("No Image Selected!", "error");

				redirect("index.php?view=view&id=". $_POST['proid']);

		}else{

	 

				@$file=$_FILES['photo']['tmp_name'];

				@$image= addslashes(file_get_contents($_FILES['photo']['tmp_name']));

				@$image_name= addslashes($_FILES['photo']['name']); 

				@$image_size= getimagesize($_FILES['photo']['tmp_name']);



			if ($image_size==FALSE ) {

				message("Uploaded file is not an image!", "error");

				redirect("index.php?view=view&id=". $_POST['proid']);

			}else{

					//uploading the file

					move_uploaded_file($temp,"uploaded_photos/" . $myfile);

		 	

					 



						$product = New Product();

						$product->IMAGES 			= $location;

						$product->update($_POST['proid']); 



						redirect("index.php");

						 

							

					}

			}

			 

		}





	function setBanner(){

		$promo = New Promo();

		$promo->PROBANNER  =1;  

		$promo->update($_POST['PROID']);



	}



 	function setDiscount(){

 		if (isset($_POST['submit'])){



		$promo = New Promo();

		$promo->PRODISCOUNT  = $_POST['PRODISCOUNT']; 

		$promo->PRODISPRICE  = $_POST['PRODISPRICE']; 

		$promo->PROBANNER  =1;    

		$promo->update($_POST['PROID']);



		msgBox("Discount has been set.");



		redirect("index.php"); 

 		}

	

	}

	function removeDiscount(){

 		if (isset($_POST['submit'])){



		$promo = New Promo();

		$promo->PRODISCOUNT  = $_POST['PRODISCOUNT']; 

		$promo->PRODISPRICE  = $_POST['PRODISPRICE']; 

		$promo->PROBANNER  =1;    

		$promo->update($_POST['PROID']);



		msgBox("Discount has been set.");



		redirect("index.php"); 

 		}

	

	}

?>