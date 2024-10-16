<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<?php
    
    if (!isset($_SESSION['USERID'])) {
        redirect(web_root."admin/index.php");
    }

    
    check_message();
    ?>
    <style>
/* Adjust the modal dialog */
.modal-dialog {
    margin: 30px auto; /* Center vertically and horizontally */
    max-width: 80%; /* Set maximum width to 80% of the screen */
    width: 80%; /* Set a fixed width, adjust as needed */
}

/* Add padding to modal content */
.modal-content {
    padding: 20px;
    border-radius: 8px; /* Optional: rounded corners */
}

/* Style the modal header */
.modal-header {
    border-bottom: 1px solid #e5e5e5;
    padding-bottom: 15px;
    margin-bottom: 20px;
}

/* Style the modal body */
.modal-body {
    padding: 0;
}

/* Ensure the modal table takes full width */
.modal-body table {
    width: 100%;
}

/* Add some spacing between table cells */
.modal-body td, .modal-body th {
    padding: 8px;
}

/* Style the Customer Receipt section */
/* .modal-body .customer-receipt {
    margin-top: 20px;
    border-top: 1px solid #e5e5e5;
    padding-top: 20px;
} */

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-dialog {
        width: 95%;
        margin: 10px auto;
    }
}
</style>
<div class="tab-content" id="orders-table-tab-content">
    <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
        <div class="app-card app-card-orders-table shadow-sm mb-5">
        <div class="app-card-body" style="margin: 0 15px;">
                <div class="table-responsive">
                    <table id="myTable" class="table app-table-hover mb-0 text-left">
                        <thead>
                            <tr>
                                <th class="cell">Order No:</th>
                                <th class="cell">Customer</th>
                                <th class="cell">DateOrdered</th>
                                <th class="cell">Price</th>
                                <th class="cell">PaymentMethod</th>
                                <th class="cell">Status</th>
                                <th class="cell">Action</th>
                                <th class="cell">View Order</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    $query = "SELECT * FROM tblsummary s ,tblcustomer c 
                                              WHERE s.CUSTOMERID=c.CUSTOMERID ORDER BY ORDEREDNUM desc ";
                                    $mydb->setQuery($query);
                                    $cur = $mydb->loadResultList();

                                    foreach ($cur as $result) {
                                        echo '<tr>';
                                        echo '<td width="3%" align="center">' . $result->ORDEREDNUM . '</td>';
                                        echo '<td><a href="index.php?view=customerdetails&customerid=' . $result->CUSTOMERID . '" title="View customer information">' . $result->FNAME . ' ' . $result->LNAME . '</a></td>';
                                        echo '<td>' . date_format(date_create($result->ORDEREDDATE), "M/d/Y h:i:s ") . '</td>';
                                        echo '<td> &#8369;' . number_format($result->PAYMENT, 2) . '</td>';
                                        echo '<td>' . $result->PAYMENTMETHOD . '</td>';
                                        echo '<td>' . $result->ORDEREDSTATS . '</td>';

                                        // Actions based on order status
                                        if ($result->ORDEREDSTATS == 'Pending') {
                                            echo '<td style="text-align: center;">
                                                   
                                                   <button onclick="confirmOrder(' . $result->ORDEREDNUM . ', ' . $result->CUSTOMERID . ')" class="btn btn-warning btn-sm">Confirm</button>
                                                </td>';
                                        } elseif ($result->ORDEREDSTATS == 'Cancelled') {
                                            echo '<td style="text-align: center;">
                                                    <a href="#" class="btn btn-danger btn-sm" disabled>Cancelled</a>
                                                </td>';
                                        } elseif ($result->ORDEREDSTATS == 'Confirmed') { 
                                                echo '<td style="text-align: center;">
                                                        <a href="#" class="btn btn-success btn-sm" disabled>Confirmed</a>
                                                         <button onclick="prepareDelivery(' . $result->ORDEREDNUM . ', ' . $result->CUSTOMERID . ')" class="btn btn-primary btn-sm">Click to Prepare for Delivery</button>
                                                    </td>';                                
                                        } elseif ($result->ORDEREDSTATS == 'Preparing for Delivery') {
                                            echo '<td style="text-align: center;">
                                                    <button onclick="approachDestination(' . $result->ORDEREDNUM . ', ' . $result->CUSTOMERID . ')" class="btn btn-info btn-sm">Update to Approaching Destination</button>
                                                </td>';

                                            } elseif ($result->ORDEREDSTATS == 'Approaching Destination') {
                                                echo '<td style="text-align: center;">
                                                        <button onclick="markDelivered(' . $result->ORDEREDNUM . ', ' . $result->CUSTOMERID . ')" class="btn btn-success btn-sm">Click to Mark Order as Delivered</button>
                                                    </td>';

                                        } elseif ($result->ORDEREDSTATS == 'Delivered Successfully') {
                                            echo '<td style="text-align: center;">
                                                    <a href="#" class="btn btn-success btn-sm" disabled>Completed</a>
                                                </td>';
                                        }

                                        // View Order button
                                        echo '<td><center><a href="#" title="View list Of ordered" class="orders btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal" data-id="' . $result->ORDEREDNUM . '">View</a></center></td>';
                                        echo '</tr>';
                                    }
                                    ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
 <!-- Modal -->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Order Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="modal-body-content">
                <!-- Content will be loaded here from orderedproduct.php -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>



</div>

<script src="assets/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('.orders').click(function(e) {
        e.preventDefault();
        var ordernumber = $(this).data('id');
        $.ajax({
            url: 'orderedproduct.php',
            type: 'post',
            data: {
                ordernumber: ordernumber
            },
            success: function(response) {
                $('#modal-body-content').html(response);
                $('#myModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    });
});

$('#myModal').on('hidden.bs.modal', function() {
    $('#modal-body-content').html('');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('html, body').animate({
        scrollTop: 0
    }, 'fast');
});

function handleModalClose() {
    $('#myModal').modal('hide');
}
</script>
<script>$(document).ready(function() {
    $('#myTable').DataTable({
        "order": [[0, "desc"]]
    });
});

// Confirm alert
function confirmOrder(orderId, customerId) {
    Swal.fire({
        title: 'Confirm Order?',
        text: "Are you sure you want to confirm this order?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, confirm it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'controller.php',
                type: 'GET',
                data: {
                    action: 'edit',
                    id: orderId,
                    customerid: customerId,
                    actions: 'confirm'
                },
                success: function(response) {
                    Swal.fire(
                        'Confirmed!',
                        'Order confirmed successfully.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'There was a problem confirming the order.',
                        'error'
                    );
                }
            });
        }
    });
}

//Preparing of Delivery
function prepareDelivery(orderId, customerId) {
    Swal.fire({
        title: 'Prepare for Delivery?',
        text: "Are you sure you want to prepare this order for delivery?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, prepare it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'controller.php',
                type: 'GET',
                data: {
                    action: 'edit',
                    id: orderId,
                    customerid: customerId,
                    actions: 'deliver'
                },
                success: function(response) {
                    Swal.fire(
                        'Prepared!',
                        'Order will now be delivered.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'There was a problem preparing the order for delivery.',
                        'error'
                    );
                }
            });
        }
    });
}

//approach destination
function approachDestination(orderId, customerId) {
    Swal.fire({
        title: 'Approaching Destination?',
        text: "Are you sure the order is approaching the destination?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update status!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'controller.php',
                type: 'GET',
                data: {
                    action: 'edit',
                    id: orderId,
                    customerid: customerId,
                    actions: 'approaching'
                },
                success: function(response) {
                    Swal.fire(
                        'Updated!',
                        'Order is now approaching the destination.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'There was a problem updating the order status.',
                        'error'
                    );
                }
            });
        }
    });
}

//delivered
function markDelivered(orderId, customerId) {
    Swal.fire({
        title: 'Mark as Delivered?',
        text: "Are you sure the order has been delivered successfully?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, mark as delivered!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'controller.php',
                type: 'GET',
                data: {
                    action: 'edit',
                    id: orderId,
                    customerid: customerId,
                    actions: 'delivered'
                },
                success: function(response) {
                    Swal.fire(
                        'Delivered!',
                        'Order has been marked as delivered successfully.',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'There was a problem marking the order as delivered.',
                        'error'
                    );
                }
            });
        }
    });
}


//password
$(document).ready(function() {
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        
        var password = $('input[name="CUSPASS"]').val();
        
        $.ajax({
            url: '<?php echo web_root; ?>customer/controller.php?action=changepassword',
            type: 'POST',
            data: {
                CUSPASS: password,
                save: true
            },
            dataType: 'json',  // Expect JSON response
            success: function(response) {
                if (response.status === "success") {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
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
                        text: response.message || 'There was a problem updating the password.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: 'Error!',
                    text: 'There was a problem connecting to the server. ' + textStatus + ': ' + errorThrown,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
});
</script>