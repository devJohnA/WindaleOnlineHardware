<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUserForm" class="row g-3" action="controller.php?action=add" method="POST">
                    <div class="col-md-6">
                        <label for="U_NAME" class="form-label">Name:</label>
                        <input type="text" class="form-control" id="U_NAME" name="U_NAME" placeholder="Account Name" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="U_USERNAME" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="U_USERNAME" name="U_USERNAME" placeholder="Username" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="U_CON" class="form-label">Contact No:</label>
                        <input type="tel" class="form-control" id="U_CON" name="U_CON" 
                               inputmode="numeric" pattern="[0-9]{11}" maxlength="11" 
                               required oninput="this.value = this.value.replace(/\D/g, '').slice(0, 11);" 
                               placeholder="Contact Number">
                    </div>
                    
                    <div class="col-md-6">
                        <label for="U_PASS" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="U_PASS" name="U_PASS" placeholder="Account Password" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label for="U_ROLE" class="form-label">Role:</label>
                        <select class="form-control" name="U_ROLE" id="U_ROLE" required>
                            <option value="Administrator">Administrator</option>
                            <option value="Staff">Staff</option>
                        </select>
                    </div>
                    
                    <div id="qrCodeContainer" style="display: none;" class="col-12 text-center">
                        <h6>Scan this QR code with Google Authenticator</h6>
                        <div id="qrCode"></div>
                        <p class="mt-2">Please save this backup code: <strong><span id="backupCode"></span></strong></p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="save" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelector('#addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show loading state
    const submitButton = this.querySelector('button[type="submit"]');
    const originalButtonText = submitButton.innerHTML;
    submitButton.disabled = true;
    submitButton.innerHTML = 'Processing...';
    
    const formData = new FormData(this);
    formData.append('save', 'true'); // Add the 'save' parameter expected by PHP
    
    fetch('controller.php?action=add', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show QR code container
            const qrCodeContainer = document.getElementById('qrCodeContainer');
            qrCodeContainer.style.display = 'block';
            
            // Create and append QR code image
            const qrImg = new Image();
            qrImg.onload = function() {
                const qrContainer = document.getElementById('qrCode');
                qrContainer.innerHTML = '';
                qrContainer.appendChild(qrImg);
            };
            qrImg.onerror = function() {
                console.error('Failed to load QR code image');
                alert('Failed to load QR code. Please try again or contact support.');
            };
            qrImg.src = data.qrCodeUrl;
            qrImg.alt = 'QR Code';
            qrImg.className = 'img-fluid';
            
            // Show backup code
            document.getElementById('backupCode').textContent = data.secretKey;
            
            // Disable form inputs
            this.querySelectorAll('input, select').forEach(input => input.disabled = true);
            
            // Show success message
            alert("User added successfully. Please scan the QR code in your authenticator app.");
        } else {
            alert(data.message || 'An error occurred while creating the user.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while processing your request. Please try again.');
    })
    .finally(() => {
        // Reset button state
        submitButton.disabled = false;
        submitButton.innerHTML = originalButtonText;
    });
});
</script>