<?php  
    if (!isset($_SESSION['USERID'])){
        redirect(web_root."index.php");
    }

    $PROID = $_GET['id'];
    $product = New Product();
    $singleproduct = $product->single_product($PROID);

      // Convert <br /> to newlines for textarea display
      $description = str_replace('<br />', "\n", $singleproduct->Description);
?>

<div class="container-fluid">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Update Product</h1>
                    </div>
                </div>
                <form class="form-horizontal span6" action="controller.php?action=edit" method="POST" onsubmit="return validateForm();">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-8">
                                <label class="col-md-4 control-label" for="PRODESC">Product Name:</label>
                                <div class="col-md-8">
                                    <input id="PROID" name="PROID" type="hidden" value="<?php echo $singleproduct->PROID; ?>">
                                    <textarea class="form-control input-sm" id="PRODESC" name="PRODESC" cols="1" rows="3" readonly><?php echo $singleproduct->PRODESC; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                        <div class="col-md-8">
                            <label class="col-md-4 control-label" for="Description">Description:</label>

                            <div class="col-md-8">
                                <textarea class="form-control input-sm" id="Description" name="Description" placeholder="Description"
                                    type="text"><?php echo htmlspecialchars($description); ?></textarea>
                            </div>

                        </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8">
                                <label class="col-md-4 control-label" for="CATEGORY">Category:</label>
                                <div class="col-md-8">
                                    <select class="form-control input-sm" name="CATEGORY" id="CATEGORY">
                                        <option value="None">Select Category</option>
                                        <?php
                                            $category = New Category();
                                            $singlecategory = $category->single_category($singleproduct->CATEGID);
                                            echo '<option SELECTED value='.$singlecategory->CATEGID.'>'.$singlecategory->CATEGORIES.'</option>';
                                            $mydb->setQuery("SELECT * FROM `tblcategory` where CATEGID <> '".$singlecategory->CATEGID."'");
                                            $cur = $mydb->loadResultList();
                                            foreach ($cur as $result) {
                                                echo '<option value='.$result->CATEGID.'>'.$result->CATEGORIES.'</option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <label class="col-md-2 control-label" for="PROPRICE">Price:</label>
                                <div class="col-md-3">
                                    <input class="form-control input-sm" id="PROPRICE" name="PROPRICE" placeholder="Price" type="number" step="any" value="<?php echo $singleproduct->PROPRICE; ?>" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <label class="col-md-4 control-label" for="PROQTY">In stock:</label>
                                <div class="col-md-4">
                                    <input class="form-control input-sm" id="PROQTY" name="PROQTY" placeholder="Current Quantity" type="number" value="<?php echo $singleproduct->PROQTY; ?>" readonly>
                                </div>
                                <label class="col-md-2 control-label" for="STOCK_ADJUSTMENT">Add Stock:</label>
                                <div class="col-md-2">
                                    <input class="form-control input-sm" id="STOCK_ADJUSTMENT" name="STOCK_ADJUSTMENT" placeholder="Add Quantity" type="number" value="0" min="0" max="999">
                                </div>
                            </div>
                        </div>

                      
                    </div>
                        <div class="form-group">
                            <div class="col-md-8">
                                <label class="col-md-4 control-label" for="idno"></label>
                                <div class="col-md-8">
                                    <button class="btn btn-primary btn-sm" name="save" type="submit"><span class="fa fa-save fw-fa"></span> Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
 function validateForm() {
        var stockAdjustment = document.getElementById('STOCK_ADJUSTMENT').value;
        var hasChanges = false;

        // Check if stock adjustment is the only change
        if (stockAdjustment != "0") {
            hasChanges = true;
        }

        // Check for changes in other fields
        var originalPRODESC = "<?php echo $singleproduct->PRODESC; ?>";
        var originalDescription = `<?php echo str_replace(array("\r\n", "\r", "\n"), '\n', addslashes($description)); ?>`;
        var originalCATEGORY = "<?php echo $singleproduct->CATEGID; ?>";
        var originalPROPRICE = "<?php echo $singleproduct->PROPRICE; ?>";

        if (document.getElementById('PRODESC').value != originalPRODESC) {
            hasChanges = true;
        }
        if (document.getElementById('Description').value != originalDescription) {
            hasChanges = true;
        }
        if (document.getElementById('CATEGORY').value != originalCATEGORY) {
            hasChanges = true;
        }
        if (document.getElementById('PROPRICE').value != originalPROPRICE) {
            hasChanges = true;
        }

        if (!hasChanges) {
            Swal.fire({
                icon: 'info',
                title: 'No Changes',
                text: 'No changes made',
                confirmButtonText: 'OK'
            });
            return false;
        }
        return true;
    }

</script>
