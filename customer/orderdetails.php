<?php 

if (!isset($_SESSION['CUSID'])){
redirect(web_root."index.php");
}
 

     

$customerid =$_SESSION['CUSID'];
$customer = New Customer();
$singlecustomer = $customer->single_customer($customerid);

  ?>

<?php 
  $autonumber = New Autonumber();
  $res = $autonumber->set_autonumber('ordernumber'); 
?>


<form onsubmit="return processOrder(event)" action="customer/controller.php?action=processorder" method="post">
    <section id="cart_items">
        <div class="container">
            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">Order Details</li>
                </ol>
            </div>
            <div class="row">
                <div class="col-md-6 pull-left">
                    <div class="col-md-2 col-lg-2 col-sm-2" style="float:left">
                        Name:
                    </div>
                    <div class="col-md-8 col-lg-10 col-sm-3" style="float:left">
                        <?php echo $singlecustomer->FNAME .' '.$singlecustomer->LNAME; ?>
                    </div>
                    <div class="col-md-2 col-lg-2 col-sm-2" style="float:left">
                        Address:
                    </div>
                    <div class="col-md-8 col-lg-10 col-sm-3" style="float:left">
                        <?php echo $singlecustomer->CUSHOMENUM . ' ' . $singlecustomer->STREETADD . ' ' .$singlecustomer->BRGYADD . ' ' . $singlecustomer->CITYADD . ' ' .$singlecustomer->PROVINCE . ' ' .$singlecustomer->COUNTRY; ?>
                    </div>
                    <div class="col-md-2 col-lg-2 col-sm-2" style="float:left">
                        Landmark:
                    </div>
                    <div class="col-md-8 col-lg-10 col-sm-3" style="float:left">
                        <?php echo $singlecustomer->LMARK;  ?>
                    </div>
                </div>

                <div class="col-md-6 pull-right">
                    <div class="col-md-10 col-lg-12 col-sm-8">
                    <input type="hidden" value="<?php echo ltrim($res->AUTO, '0'); ?>" id="ORDEREDNUM" name="ORDEREDNUM">
                    Order Number :<?php echo ltrim($res->AUTO, '0'); ?>
                    </div>
                </div>

                <div class="col-md-6 pull-right">
                    <div class="col-md-10 col-lg-12 col-sm-8">
                    Contact Number :<?php echo $singlecustomer->PHONE; ?>
                    </div>
                </div>
            </div>
            <div class="table-responsive cart_info">

                <table class="table table-condensed" id="table">
                    <thead>
                        <tr class="cart_menu">
                            <th style="width:12%; align:center; ">Product</th>
                            <th>Description</th>
                            <th style="width:15%; align:center; ">Quantity</th>
                            <th style="width:15%; align:center; ">Price</th>
                            <th style="width:15%; align:center; ">Total</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php

              $tot = 0;
                if (!empty($_SESSION['gcCart'])){ 
                      $count_cart = @count($_SESSION['gcCart']);
                      for ($i=0; $i < $count_cart  ; $i++) { 

                      $query = "SELECT * FROM `tblpromopro` pr , `tblproduct` p , `tblcategory` c
                           WHERE pr.`PROID`=p.`PROID` AND  p.`CATEGID` = c.`CATEGID`  and p.PROID='".$_SESSION['gcCart'][$i]['productid']."'";
                        $mydb->setQuery($query);
                        $cur = $mydb->loadResultList();
                        foreach ($cur as $result){ 
              ?>

                        <tr>
                            <!-- <td></td> -->
                            <td><img src="admin/products/<?php echo $result->IMAGES ?>" width="50px" height="50px"></td>
                            <td><?php echo $result->PRODESC ; ?></td>
                            <td align="center"><?php echo $_SESSION['gcCart'][$i]['qty']; ?></td>
                            <td>&#8369 <?php echo  $result->PRODISPRICE ?></td>
                            <td>&#8369 <output><?php echo $_SESSION['gcCart'][$i]['price']?></output></td>
                        </tr>
                        <?php
              $tot +=$_SESSION['gcCart'][$i]['price'];
                        }

                      }
                }
              ?>


                    </tbody>

                </table>
                <div class="  pull-right">
                    <p align="right">
                    <div> Total Price : &#8369 <span id="sum">0.00</span></div>
                    <div> Delivery Fee : &#8369 <span id="fee">0.00</span></div>
                    <div> Overall Price : &#8369 <span id="overall"><?php echo $tot ;?></span></div>
                    <input type="hidden" name="alltot" id="alltot" value="<?php echo $tot ;?>" />
                    </p>
                </div>

            </div>
        </div>
    </section>

    <section id="do_action">
        <div class="container">
            <!-- <div class="heading">
                <h3>What would you like to do next?</h3>
                <p>Choose if you have a discount code or reward points you want to use or would like to estimate your
                    delivery cost.</p>
            </div> -->
            <div class="row">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label> Payment Method : </label>
                            <div class="radio">
                                <label>
                                    <input type="radio" class="paymethod" name="paymethod" id="deliveryfee"
                                        value="Cash on Delivery" checked="true" data-toggle="collapse"
                                        data-parent="#accordion" data-target="#collapseOne">Cash on Delivery

                                </label>
                            </div>
                        <div class="radio d-flex justify-content-between align-items-center">
    <label>
        <input type="radio" class="paymethod" name="paymethod" id="gcash" value="GCash">
        GCash
    </label>
    <!-- GCash logo placed on the right side -->
    <img src="<?php echo web_root; ?>images/gcash.png" alt="GCash Logo" style="height: 30px;">
</div>
                        </div>
                        <div class="panel">
                            <div class="panel-body">
                                <div class="form-group ">
                                    <label>Address where to deliver</label>


                                    <div class="col-md-12">
                                        <label class="col-md-4 control-label" for="PLACE">Place(Brgy/City):</label>

                                        <div class="col-md-8">
                                            <select class="form-control paymethod" name="PLACE" id="PLACE"
                                                onchange="validatedate()">
                                                <option value="0">Select</option>
                                                <?php 
                                            $query = "SELECT * FROM `tblsetting` ";
                                            $mydb->setQuery($query);
                                            $cur = $mydb->loadResultList();

                                            foreach ($cur as $result) {  
                                              echo '<option value='.$result->DELPRICE.'>'.$result->BRGY.' '.$result->PLACE.' </option>';
                                            }
                                            ?>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <input type="hidden" placeholder="HH-MM-AM/PM" id="CLAIMEDDATE" name="CLAIMEDDATE"
                            value="<?php echo date('y-m-d h:i:s') ?>" class="form-control" />

                    </div>



                </div>
                <br />
                <div class="row">
                    <div class="col-md-6">
                        <a href="index.php?q=cart" class="btn btn-primary pull-left"><span
                                class=""></span>&nbsp;<strong>Back to Cart</strong></a>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-success  pull-right " name="btn" id="btn"
                            onclick="return validatedate();"> Submit Order<span class=""></span></button>
                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--/#do_action-->
</form>
<script>

function updateDeliveryFee() {
    var place = document.getElementById('PLACE');
    var fee = document.getElementById('fee');
    var sum = document.getElementById('sum');
    var overall = document.getElementById('overall');
    var alltot = document.getElementById('alltot');

    fee.innerHTML = place.value;
    overall.innerHTML = (parseFloat(sum.innerHTML) + parseFloat(place.value)).toFixed(2);
    alltot.value = overall.innerHTML;
}

function processOrder(event) {
    event.preventDefault();
    
    if (typeof orderfilter === 'function' && !orderfilter()) {
        console.log('Order filter failed');
        return false;
    }
    
    const form = event.target;
    const formData = new FormData(form);
    const paymentMethod = formData.get('paymethod');

    formData.append('customername', '<?php echo $singlecustomer->FNAME . ' ' . $singlecustomer->LNAME; ?>');
    formData.append('customeremail', '<?php echo $singlecustomer->CUSUNAME; ?>');
    formData.append('customerno', '<?php echo $singlecustomer->PHONE; ?>');

    // Add delivery fee to formData
    const deliveryFee = document.getElementById('PLACE').value;
    formData.append('PLACE', deliveryFee);

    if (paymentMethod === 'GCash') {
        // Create a PayMongo source for GCash
        fetch('<?php echo web_root; ?>customer/create_paymongo_source.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to GCash authorization page
                window.location.href = data.checkoutUrl;
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'There was a problem creating the payment. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'There was a problem processing your payment. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    } else {
        // Process Cash on Delivery order
        fetch(form.action, {
            method: form.method,
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: data.message || 'Order submitted successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?php echo web_root; ?>index.php?q=profile';
                    }
                });
            } else {
                Swal.fire({
                    title: 'Error!',
                    text: data.message || 'There was a problem submitting your order. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                title: 'Error!',
                text: 'There was a problem submitting your order. Please try again.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    }
}

// Update delivery fee when payment method changes
document.querySelectorAll('.paymethod').forEach(function(elem) {
    elem.addEventListener("change", updateDeliveryFee);
});

// Initial update of delivery fee
updateDeliveryFee();
</script>