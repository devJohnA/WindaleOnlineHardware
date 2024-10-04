<?php
   if (!isset($_SESSION['USERID'])){
      redirect(web_root."index.php");
     }

      // $autonum = New Autonumber();
      // $result = $autonum->single_autonumber(4);

      $query = "SELECT DISTINCT productName FROM stocks WHERE productName NOT IN (SELECT PRODESC FROM tblproduct)";
      $result = $conn->query($query);

?>

<style>
.preserve-newlines {
    white-space: pre-wrap;
    word-wrap: break-word;
}
</style>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form class="form-horizontal span6" action="controller.php?action=add" method="POST"
                    enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">Add New Product</h1>
                        </div>
                        <!-- /.col-lg-12 -->
                    </div>

                    <!-- <div class="form-group">
                        <div class="col-md-8">
                            <label class="col-md-4 control-label" for="OWNERNAME">Owner:</label>

                            <div class="col-md-8">
                                <input class="form-control input-sm" id="OWNERNAME" name="OWNERNAME"
                                    placeholder="Owner Name" type="text" value="">
                            </div>
                        </div>
                    </div> -->

                    <!-- <div class="form-group">
                        <div class="col-md-8">
                            <label class="col-md-4 control-label" for="OWNERPHONE">Phone:</label>

                            <div class="col-md-8">
                                <input class="form-control input-sm" id="OWNERPHONE" name="OWNERPHONE"
                                    placeholder="+63 0000000000" type="number" value="">
                            </div>
                        </div>
                    </div> -->

                    <div class="form-group">
                        <div class="col-md-8">
                            <label class="col-md-4 control-label" for="PRODESC">Product Name</label>

                            <div class="col-md-8">
                            <select class="form-control input-sm" id="PRODESC" name="PRODESC" required>
                                    <option value="">Select a Product</option>
                                    <?php
                                    while($row = $result->fetch_assoc()) {
                                        echo "<option value='" . $row['productName'] . "'>" . $row['productName'] . "</option>";
                                    }
                                    ?>
                                </select>
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
                $mydb->setQuery("SELECT * FROM `tblcategory`");
                $categories = $mydb->loadResultList();

                foreach ($categories as $category) {
                    echo '<option value="'.$category->CATEGID.'">'.$category->CATEGORIES.'</option>';
                }
                ?>
            </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8">
                            <!-- <label class="col-md-4 control-label" for="ORIGINALPRICE">Original Price:</label>

                            <div class="col-md-3">
                                <input class="form-control input-sm" id="ORIGINALPRICE" name="ORIGINALPRICE"
                                    placeholder="Original Price" type="number" value="" step="any">
                            </div> -->
                            <label class="col-md-2 control-label" for="PROPRICE">Price:</label>

                            <div class="col-md-3">
                                <input class="form-control input-sm" id="PROPRICE" step="any" name="PROPRICE"
                                    placeholder="&#8369 Price " type="number" value="" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8">
                            <label class="col-md-4 control-label" for="PROQTY">Quantity:</label>

                            <div class="col-md-8">
                                <input class="form-control input-sm" id="PROQTY" name="PROQTY" placeholder="Quantity"
                                    type="number" value="">
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8">
                            <label class="col-md-4 control-label" for="Description">Description:</label>

                            <div class="col-md-8">
                                <textarea class="form-control input-sm preserve-newlines" id="Description" name="Description" placeholder="Description"
                                    rows="4"><?php echo isset($_POST['Description']) ? htmlspecialchars($_POST['Description']) : ''; ?></textarea>
                            </div>

                        </div>
                    </div>

                    <div class="form-group">
        <div class="col-md-8">
            <label class="col-md-4" align="right" for="image">Product Image:</label>
            
            <div class="col-md-8">
                <img id="productImage" src="" alt="Product Image" style="max-width: 200px; max-height: 200px; display: none; margin-bottom: 10px;">
                <!-- <input type="file" name="image" id="image" /> -->
                <input type="hidden" name="existingImage" id="existingImage" required>
                <!-- <small class="form-text text-muted mt-2">Leave empty to use existing image. Only JPG, JPEG, PNG, and GIF files are accepted.</small> -->
            </div>
        </div>
    </div>

                    <div class="form-group">
                        <div class="col-md-8">
                            <label class="col-md-4 control-label" for="idno"></label>

                            <div class="col-md-8">
                                <button class="btn  btn-primary btn-sm" name="save" type="submit"><span
                                        class="fa fa-save fw-fa"></span>
                                    Save</button>
                                
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="rows">
                            <div class="col-md-6">
                                <label class="col-md-6 control-label" for="otherperson"></label>

                                <div class="col-md-6">

                                </div>
                            </div>

                            <div class="col-md-6" align="right">


                            </div>

                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
document.getElementById('PRODESC').addEventListener('change', function() {
    var productName = this.value;
    if(productName) {
        fetch('get_product_details.php?productName=' + encodeURIComponent(productName))
            .then(response => response.json())
            .then(data => {
                let categorySelect = document.getElementById('CATEGORY');
                for(let i = 0; i < categorySelect.options.length; i++) {
                    if(categorySelect.options[i].text === data.productCategory) {
                        categorySelect.selectedIndex = i;
                        break;
                    }
                }
                document.getElementById('PROPRICE').value = data.productPrice;
                document.getElementById('PROQTY').value = data.productStock;
                
                // Update image
                let productImage = document.getElementById('productImage');
                if(data.images) {
                    // Extract just the filename from the full path
                    let imageName = data.images.split('/').pop();
                    productImage.src = '../stock/upload/' + imageName;
                    productImage.style.display = 'block';
                    // Store only the filename in the hidden input
                    document.getElementById('existingImage').value = imageName;
                } else {
                    productImage.style.display = 'none';
                    document.getElementById('existingImage').value = '';
                }
            })
            .catch(error => console.error('Error:', error));
    } else {
        document.getElementById('CATEGORY').selectedIndex = 0;
        document.getElementById('PROPRICE').value = '';
        document.getElementById('PROQTY').value = '';
        document.getElementById('productImage').style.display = 'none';
        document.getElementById('existingImage').value = '';
    }
});
</script>
