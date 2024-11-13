<style>
#orderContainer {
    height: 300px;
    overflow-y: auto;
}

.order-details-container {
    background-color: white;
    border: 1px solid #dee2e6;
}

.del-color{
    color:red;
}

.print-ic{
    color:#fd2323;
    background:transparent;

}

.add-ic{
    color:#fd2323;
}
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <input type="text" class="form-control" id="productSearch" placeholder="Search for a product...">
            </div>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="productTable">
                    <!-- Product rows will be added here -->
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <div class="order-details-container bg-white p-3 rounded shadow-sm">
                <h2>Order Details</h2>
                <div id="orderContainer" class="border p-3 mb-3">
                    <p>No items in the cart.</p>
                </div>
                <div class="mt-3">
                    <h4>Total: &#8369;<span id="orderTotal">0.00</span></h4>
                </div>
                <button id="btnPrint" class="btn print-ic mt-3">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer" viewBox="0 0 16 16">
                        <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                        <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
let orNumber = null;
const orderItems = {}; // Object to keep track of products and quantities

function generateORNumber() {
    return Math.floor(10000 + Math.random() * 90000);
}

function resetOrderDetails() {
    $('#orderContainer').html('<p>No items in the cart.</p>');
    $('#orderTotal').text('0.00');
    orNumber = null;
    Object.keys(orderItems).forEach(key => delete orderItems[key]);
}

function resetSearch() {
    $('#productSearch').val('');
    $('#productTable').empty();
}

function validateQuantity($input) {
    const enteredQuantity = parseInt($input.val());
    const maxStock = parseInt($input.attr('max'));
    
    if (enteredQuantity > maxStock) {
        Swal.fire({
            title: 'Exceeds Available Stock',
            text: `You can't set higher because only ${maxStock} items of this product are available`,
            icon: 'warning',
            confirmButtonText: 'OK'
        }).then(() => {
            $input.val(maxStock);  // Set to max available stock
            updateQuantityInput($input);  // Update input state
        });
    } else if (enteredQuantity < 1 || isNaN(enteredQuantity)) {
        updateQuantityInput($input);  // Reset to appropriate value
    }
}

function updateQuantityInput($input) {
    const max = parseInt($input.attr('max'));
    if (max === 0) {
        $input.val(0);
        $input.prop('disabled', true);
        $input.closest('tr').find('.add-to-cart').prop('disabled', true);
    } else {
        $input.val(1);
        $input.prop('disabled', false);
        $input.closest('tr').find('.add-to-cart').prop('disabled', false);
    }
}

$('#productSearch').on('input', function() {
    const query = $(this).val().trim();
    if (query === '') {
        $('#productTable').empty();
        return;
    }

    $.ajax({
        type: 'POST',
        url: 'fetch_products.php',
        data: { query: query },
        dataType: 'json',
        success: function(products) {
            $('#productTable').empty();
            if (products.length > 0) {
                products.forEach(product => {
                    const currentStock = product.productStock - (orderItems[product.id] ? orderItems[product.id].quantity : 0);
                    const isOutOfStock = currentStock <= 0;
                    const row = `<tr data-product-id="${product.id}">
                        <td><img src="${product.imageUrl}" style="height:50px; width:50px;"></td>
                        <td>${product.productName}</td>
                        <td>&#8369;${parseFloat(product.productPrice).toFixed(2)}</td>
                        <td><input type="number" min="1" value="${isOutOfStock ? 0 : 1}" max="${currentStock}" class="form-control quantity" ${isOutOfStock ? 'disabled' : ''}></td>
                      <td> <button class="btn add-ic add-to-cart" ${isOutOfStock ? 'disabled' : ''}>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2Z"/>
                    </svg>
                </button></td>
                    </tr>`;
                    $('#productTable').append(row);
                });
            } else {
                $('#productTable').append('<tr><td colspan="5">No products found</td></tr>');
            }
        }
    });
});

$(document).ready(function() {
    $('#productTable').on('input change', '.quantity', function() {
        validateQuantity($(this));
    });
});

$(document).on('click', '.add-to-cart', function() {
    const row = $(this).closest('tr');
    const id = row.data('product-id');
    const name = row.find('td').eq(1).text();
    const price = parseFloat(row.find('td').eq(2).text().substring(1));
    const quantityInput = row.find('.quantity');
    const quantity = parseInt(quantityInput.val());
    const stock = parseInt(quantityInput.attr('max'));
    const total = price * quantity;

    if (quantity > stock) {
        Swal.fire({
            title: 'Not enough stock',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    if (!orNumber) {
        orNumber = generateORNumber();
        $('#orderContainer').prepend(`<h4>OR Number: ${orNumber}</h4>`);
    }

    if (orderItems[id]) {
        orderItems[id].quantity += quantity;
        orderItems[id].totalPrice += total;
    } else {
        orderItems[id] = {
            name: name,
            quantity: quantity,
            totalPrice: total,
            price: price
        };
    }

    // Update stock immediately
    const newStock = stock - quantity;
    quantityInput.attr('max', newStock);
    
    // Set quantity input to 0 if no stock left, otherwise to 1
    quantityInput.val(newStock === 0 ? 0 : 1);
    
    // Disable the add to cart button and quantity input if stock is 0
    if (newStock === 0) {
        $(this).prop('disabled', true);
        quantityInput.prop('disabled', true);
    }

    updateOrderDisplay();
});

$(document).on('click', '.cancel-item', function() {
    const id = $(this).data('product-id');
    const canceledQuantity = orderItems[id].quantity;

    delete orderItems[id];

    updateOrderDisplay();

    // Restore the stock
    const productRow = $(`#productTable tr[data-product-id="${id}"]`);
    const quantityInput = productRow.find('.quantity');
    const currentStock = parseInt(quantityInput.attr('max'));
    const newStock = currentStock + canceledQuantity;
    quantityInput.attr('max', newStock);
    quantityInput.val(1); // Always reset to 1 when canceling
    
    // Re-enable the add to cart button and quantity input
    productRow.find('.add-to-cart').prop('disabled', false);
    quantityInput.prop('disabled', false);

    if (Object.keys(orderItems).length === 0) {
        resetOrderDetails();
    }
});

function updateOrderDisplay() {
    $('#orderContainer').empty();
    if (orNumber) {
        $('#orderContainer').append(`<h4>OR Number: ${orNumber}</h4>`);
    }

    let subtotal = 0;
    for (const [id, details] of Object.entries(orderItems)) {
        const orderRow = `
            <div class="order-item">
                <p>${details.name} x ${details.quantity} - &#8369;${details.totalPrice.toFixed(2)}
                <button class="btn btn-sm del-color cancel-item" data-product-id="${id}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                </button>
            </div>`;
        $('#orderContainer').append(orderRow);
        subtotal += details.totalPrice;
    }

    const vat = subtotal * 0.03;
    const totalAmount = subtotal + vat;

    $('#orderContainer').append(`
        <hr>
        <p>Subtotal: &#8369;${subtotal.toFixed(2)}</p>
        <p>Percentage Tax (3%): &#8369;${vat.toFixed(2)}</p>
         <p>Zero-rated: &#8369; 0.00</p>
         <p>VAT-exempted: &#8369; 0.00</p>
         <p>Non-VAT: &#8369; 0.00</p>
         <p>VAT amount: &#8369; 0.00</p>
    `);

    $('#orderTotal').text(totalAmount.toFixed(2));

    if (Object.keys(orderItems).length === 0) {
        $('#orderContainer').html('<p>No items in the cart.</p>');
    } else {
        // Add payment input form
        $('#orderContainer').append(`
            <div class="mt-3">
                <label for="paymentInput">Payment Received:</label>
                <input type="number" id="paymentInput" class="form-control" min="${totalAmount.toFixed(2)}" step="0.01">
            </div>
            <div class="mt-2">
                <p>Change: &#8369;<span id="changeAmount">0.00</span></p>
            </div>
        `);

        // Add event listener for payment input
        $('#paymentInput').on('input', function() {
            const payment = parseFloat($(this).val()) || 0;
            const change = payment - totalAmount;
            $('#changeAmount').text(change >= 0 ? change.toFixed(2) : '0.00');
        });
    }
}

function generateReceipt() {
    let subtotal = 0;
    let receiptContent = `
    <div id="receipt-content" style="font-family: 'Courier New', monospace; width: 200px; margin: 0 auto; padding: 0; text-align: center;">
        <div style="position: relative; height: 60px; margin-bottom: 10px;">
            <img src="../win.png" alt="Company Logo" style="position: absolute; top: 0; right: 0; width: 100px; height: 100px;">
        </div>
        <p style="margin: 0; padding: 1px 0;">Burgos Street, Mancilang, Madridejos, Cebu</p>
        <p style="margin: 0; padding: 1px 0;">Phone: (+63)9692870485</p>
        <h3 style="margin: 0; padding: 5px 0;">Order Receipt</h3>
        <p style="margin: 0; padding: 2px 0;">OR Number: ${orNumber}</p>
        <hr style="margin: 2px 0; border: none; border-top: 1px dashed #000;">
        <h4 style="margin: 0; padding: 2px 0;">Order Details:</h4>
        <div style="text-align: left; padding: 0; margin: 0;">`;

    for (const details of Object.values(orderItems)) {
        receiptContent += `<div style="margin: 0; padding: 1px 0;">${details.name}<br>x ${details.quantity} - ₱${details.totalPrice.toFixed(2)}</div>`;
        subtotal += details.totalPrice;
    }

    const vat = subtotal * 0.03;
    const totalAmount = subtotal + vat;
    const paymentReceived = parseFloat($('#paymentInput').val()) || 0;
    const change = paymentReceived - totalAmount;

    receiptContent += `
        </div>
        <hr style="margin: 2px 0; border: none; border-top: 1px dashed #000;">
        <p style="margin: 0; text-align: left; padding: 2px 0;">Subtotal: ₱${subtotal.toFixed(2)}</p>
        <p style="margin: 0; text-align: left; padding: 2px 0;">VAT (5%): ₱${vat.toFixed(2)}</p>
          <p style="margin: 0; text-align: left;  padding: 2px 0;">Payment Received: ₱${paymentReceived.toFixed(2)}</p>
          <p style="margin: 0; text-align: left;  padding: 2px 0;">Zero-rated: &#8369; 0.00</p>
         <p style="margin: 0; text-align: left;  padding: 2px 0;">VAT-exempted: &#8369; 0.00</p>
         <p style="margin: 0; text-align: left;  padding: 2px 0;">Non-VAT: &#8369; 0.00</p>
         <p style="margin: 0; text-align: left;  padding: 2px 0;">VAT amount: &#8369; 0.00</p>
        <p style="margin: 0; text-align: left;  padding: 2px 0;">Change: ₱${change.toFixed(2)}</p>
        <hr style="margin: 2px 0; border: none; border-top: 1px dashed #000;">
        <p style="margin: 0; text-align: right; padding: 2px 0;"><strong>Total Amount: ₱${totalAmount.toFixed(2)}</strong></p>
      
        <p style="margin: 0; padding: 2px 0;">Thank you for your purchase!</p>
    </div>`;

    return receiptContent;
}


// Update the print styles in the printReceipt function as well
function printReceipt() {
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);

    iframe.contentDocument.write(`
    <html>
    <head>
        <title>Print Receipt</title>
        <style>
            @page {
                size: 58mm auto;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                width: 58mm;
                font-family: 'Courier New', monospace;
                line-height: 1.2;
            }
            #receipt-content {
                margin: 0;
                padding: 0;
            }
            .logo-container {
                position: relative;
                height: 60px;
                margin-bottom: 10px;
            }
            img {
                position: absolute;
                top: 0;
                right: 0;
                width: 60px;
                height: 60px;
            }
            hr {
                margin: 2px 0;
                border: none;
                border-top: 1px dashed #000;
            }
            p, div {
                margin: 0;
                padding: 1px 0;
            }
        </style>
    </head>
    <body>
        ${generateReceipt()}
    </body>
    </html>
    `);
    iframe.contentDocument.close();

    iframe.onload = function() {
        setTimeout(function() {
            iframe.contentWindow.print();
            document.body.removeChild(iframe);
        }, 100);
    };
}

$('#btnPrint').on('click', function() {
    if (Object.keys(orderItems).length === 0) {
        Swal.fire({
            title: 'Empty Cart',
            text: 'Add to cart first',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    const paymentReceived = parseFloat($('#paymentInput').val()) || 0;
    const totalPrice = parseFloat($('#orderTotal').text());

    if (paymentReceived < totalPrice) {
        Swal.fire({
            title: 'Insufficient Payment',
            text: 'The payment received is less than the total amount.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }

    const orderDetails = [];
    let productDetailsString = '';
    let subtotal = 0;

    for (const [id, details] of Object.entries(orderItems)) {
        orderDetails.push({
            productName: details.name,
            quantity: details.quantity,
            price: details.totalPrice
        });
        productDetailsString += `${details.name}:${details.quantity}, `;
        subtotal += details.totalPrice;
    }

    productDetailsString = productDetailsString.slice(0, -2);

    const vat = subtotal * 0.05;
    const change = paymentReceived - totalPrice;

    $.ajax({
        type: 'POST',
        url: 'insert_order.php',
        data: {
            orNumber: orNumber,
            productDetails: productDetailsString,
            totalPrice: totalPrice.toFixed(2),
            subtotal: subtotal.toFixed(2),
            vat: vat.toFixed(2),
            paymentReceived: paymentReceived.toFixed(2),
            change: change.toFixed(2)
        },
        success: function(response) {
            Swal.fire({
                title: 'Order Placed!',
                text: 'Checkout successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    printReceipt();
                    resetOrderDetails();
                    resetSearch();
                }
            });
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
});

</script>
