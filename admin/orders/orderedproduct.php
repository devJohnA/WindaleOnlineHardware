<head>
    <style>
 
 

 @media print {
            body * {
                visibility: hidden;
            }

            #printout,
            #printout * {
                visibility: visible;
            }

            #printout {
                position: absolute;
                left: 0;
                top: 50%;
                transform: translateY(-50%);
                width: 100%;
                text-align: center;
            }

             @page {
        margin: 0.5cm; /* Minimized margins */
    }
            a,
            a:visited {
                text-decoration: underline;
            }

            .table {
                border-collapse: collapse !important;
                width: 100%; /* Adjusted to 100% of the container */
                margin: 0 auto;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid #000 !important;
                padding: 8px;
                vertical-align: middle;
            }

            header,
            footer,
            .navbar,
            .sidebar {
                display: none !important;
            }

            th {
                font-size: 12px !important;
            }

            td, th {
                text-align: left;
            }

            td {
                border-bottom: 1px solid #000 !important;
            }

            .receipt-container,
            .receipt-content p {
                text-align: justify;
            }
        }

        .receipt-container {
            font-family: 'Courier New', monospace;
            max-width: 600px;
            margin: 0 auto;
            padding: 9px;
            text-align: justify;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .receipt-header h4 {
            margin-bottom: 5px;
        }

        .receipt-header p {
            font-size: 0.9em;
            margin-bottom: 10px;
        }

        .receipt-content p {
            margin-bottom: 10px;
            text-align: justify;
        }

        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 150px;
            margin-right: 5px;
        }
    </style>
</head>
<?php
require_once ("../../include/initialize.php");
	 if (!isset($_SESSION['USERID'])){
      redirect(web_root."index.php");
     }


// if (isset($_POST['id'])){

// if ($_POST['actions']=='confirm') {
// 							# code...
// 	$status	= 'Confirmed';	
// 	// $remarks ='Your order has been confirmed. The ordered products will be yours in the exact date and time that you have set.';
	 
// }elseif ($_POST['actions']=='cancel'){
// 	// $order = New Order();
// 	$status	= 'Cancelled';
// 	// $remarks ='Your order has been cancelled due to lack of communication and incomplete information.';
// }

// $order = New Order();
// $order->STATS       = $status;
// $order->update($_POST['id']);


// }

if(isset($_POST['close'])){
	unset($_SESSION['ordernumber']);
}

if (isset($_POST['ordernumber'])){
	$_SESSION['ordernumber'] = $_POST['ordernumber'];
}


$query = "SELECT * FROM tblsummary s ,tblcustomer c 
				WHERE   s.CUSTOMERID=c.CUSTOMERID and ORDEREDNUM=".$_SESSION['ordernumber'];
		$mydb->setQuery($query);
		$cur = $mydb->loadSingleResult();


?>

<div class="modal-dialog" style="width: 90%; margin: 20px auto; font-family: 'Courier New', monospace;">
    <span id="printout">
    <div class="print-layout">
        <div style="padding: 5px;">
           
            <div style="display: flex; justify-content: center;">
            <div style="width: 100%;">
                <table id="table" class="table" style="border-collapse: collapse; table-layout: fixed;">
                    <thead>
                        <tr>
                            <th style="width:70%; text-align:center; border: 1px solid #000; padding: 8px;">PRODUCT</th>
                            <th style="width:70%; text-align:center; border: 1px solid #000; padding: 8px;">QUANTITY</th>
                            <th style="width:70%; text-align:center; border: 1px solid #000; padding: 8px;">AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $subtot = 0;
                        $query = "SELECT * 
                                  FROM  tblproduct p, tblcategory ct,  tblcustomer c,  tblorder o,  tblsummary s
                                  WHERE p.CATEGID = ct.CATEGID 
                                  AND p.PROID = o.PROID 
                                  AND o.ORDEREDNUM = s.ORDEREDNUM 
                                  AND s.CUSTOMERID = c.CUSTOMERID 
                                  AND o.ORDEREDNUM=".$_SESSION['ordernumber'];
                        $mydb->setQuery($query);
                        $cur = $mydb->loadResultList(); 
                        foreach ($cur as $result) {
                            $result->ORDEREDPRICE = $result->PROPRICE * $result->ORDEREDQTY;
                            echo '<tr>';  
                            echo '<td style="border: 1px solid #000; padding: 2px;">'. $result->PRODESC.'</td>';      
                            echo '<td style="border: 1px solid #000; padding: 2px;">'. $result->ORDEREDQTY.'</td>';
                            echo '<td style="border: 1px solid #000; padding: 2px;"> &#8369;'.number_format($result->PROPRICE,2).' </td>';
                            echo '</tr>';
                            $subtot += $result->ORDEREDPRICE;
                        }
                        ?>
                        <?php 
                        $query = "SELECT * FROM tblsummary s ,tblcustomer c 
                                  WHERE s.CUSTOMERID=c.CUSTOMERID and ORDEREDNUM=".$_SESSION['ordernumber'];
                        $mydb->setQuery($query);
                        $cur = $mydb->loadSingleResult();

                        $price = (in_array($cur->PAYMENTMETHOD, ["Cash on Delivery", "GCash"])) ? $cur->DELFEE : 0.00;
                        $cur->PAYMENT = $subtot + $price;
                        ?>
                    <tr>
            <td style="border: 1px solid #000; padding: 2px;">Total Price</td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px;">&#8369;<?php echo number_format($subtot,2); ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 2px;">Delivery Fee</td>   
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px;">&#8369;<?php echo number_format($price,2); ?></td>
        </tr>
        <tr>
            <td style="border: 1px solid #000; padding: 2px;"><strong>Overall Total</strong></td>
            <td style="border: 1px solid #000; padding: 2px;"></td>
            <td style="border: 1px solid #000; padding: 2px;"><strong>&#8369;<?php echo number_format($cur->PAYMENT,2); ?></strong></td>
        </tr>
        <tr>
            <td colspan="3" style="border: 1px solid #000; padding: 2px;">Ordered Date: <?php echo date_format(date_create($cur->ORDEREDDATE),"M/d/Y h:i:s "); ?></td>
        </tr>
        <tr>
            <td colspan="3" style="border: 1px solid #000; padding: 2px;">Payment Method: <?php echo $cur->PAYMENTMETHOD; ?></td>
        </tr>
                    </tbody>
                </table>
            </div>

            <div class="receipt-container">
    <div class="receipt-header">
        <h4 style="font-weight:bold;">Windale Hardware</h4>
        <p>
            Purok Tulingan, Mancilang, Madridejos, Cebu, Cebu 6053, Philippines<br>
            Owned & Operated By: Windale Hardware, Inc.
        </p>
        <p style="text-align:left; font-weight:bold;">OFFICIAL RECEIPT</p>
    </div>
    <div class="receipt-content">
        <p>Date: <span class="underline"><?php echo date("m/d/Y"); ?></span> Received from:   <span class="underline"><?php echo $cur->FNAME . ' ' . $cur->LNAME; ?></span>    Landmark: <span class="underline"><?php echo $cur->LMARK; ?></span> </p>
        <p>Address: <span class="underline"><?php echo $cur->CUSHOMENUM . ' ' . $cur->STREETADD . ', ' . $cur->BRGYADD . ', ' . $cur->CITYADD; ?></span></p>
        <p>Contact Number: <span class="underline"><?php echo $cur->PHONE; ?></span> full payment for <span class="underline">Order #<?php echo $_SESSION['ordernumber']; ?></span></p>
    </div>
</div>
        </div>
    </span>
</div>

        </div>
        </div>
    </span>
</div>

<div class="modal-footer">
    <div id="divButtons" name="divButtons">
        <?php if($cur->ORDEREDSTATS!='Pending' || $cur->ORDEREDSTATS!='Cancelled' ){ ?>
            <button onclick="tablePrint();" class="btn btn-pup pull-right"><span class="fa fa-print"></span> Print</button>
        <?php } ?>
    </div>
</div>

<script>
function tablePrint() {
    window.print();
}

var table = document.getElementById('table');
var items = table.getElementsByTagName('output');
var sum = 0;

// total price
for (var i = 0; i < items.length; i++)
    sum += parseInt(items[i].value);

// for cart
var totprice = document.getElementById('sum');
if (totprice) {
    totprice.innerHTML = sum.toFixed(2);
}
</script>