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
                    
                    <div id="qrCodeContainer" style="display: none;" class="col-12 text-center mt-3">
                        <h6>Scan this QR code with Google Authenticator</h6>
                        <div id="qrCode"></div>
                        <p class="mt-2">Please save this backup code: <strong><span id="backupCode"></span></strong></p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" id="generateQRBtn" class="btn btn-info">Generate QR Code</button>
                        <button type="submit" id="saveUserBtn" name="save" class="btn btn-primary" style="display: none;">Save User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>


// Generate QR Code button handler
document.getElementById('generateQRBtn').addEventListener('click', function() {
    const form = document.querySelector('#addUserForm');
    
    // Check form validity
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    const formData = new FormData(form);
    formData.append('generate_qr', 'true');
    
    this.disabled = true;
    this.textContent = 'Generating...';
    
    fetch('controller.php?action=generate_qr', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
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
            qrImg.src = data.qrCodeUrl;
            qrImg.alt = 'QR Code';
            qrImg.className = 'img-fluid';
            
            // Show backup code
            document.getElementById('backupCode').textContent = data.secretKey;
            
            // Show save button and hide generate button
            document.getElementById('generateQRBtn').style.display = 'none';
            document.getElementById('saveUserBtn').style.display = 'block';
            
            // Store the secret key for final submission
            form.querySelector('input[name="secret_key"]') || 
                form.insertAdjacentHTML('beforeend', 
                    `<input type="hidden" name="secret_key" value="${data.secretKey}">`);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to generate QR code'
            });
            this.disabled = false;
            this.textContent = 'Generate QR Code';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while generating the QR code'
        });
        this.disabled = false;
        this.textContent = 'Generate QR Code';
    });
});

// Save User form handler
document.querySelector('#addUserForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const saveButton = document.getElementById('saveUserBtn');
    const modal = document.getElementById('addUserModal');
    saveButton.disabled = true;
    saveButton.textContent = 'Saving...';
    
    const formData = new FormData(this);
    formData.append('save', 'true');
    
    fetch('controller.php?action=add', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // First hide the modal
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            bootstrapModal.hide();
            
            // Reset the form
            resetFormAndModal();
            
            // Then show the success message
            setTimeout(() => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: data.message
                }).then((result) => {
                    // Refresh the page
                    window.location.reload();
                });
            }, 500);
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to add user'
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while saving the user'
        });
    })
    .finally(() => {
        saveButton.disabled = false;
        saveButton.textContent = 'Save User';
    });
});

// Function to reset form and modal state
function resetFormAndModal() {
    const form = document.querySelector('#addUserForm');
    const qrCodeContainer = document.getElementById('qrCodeContainer');
    const qrCode = document.getElementById('qrCode');
    const generateQRBtn = document.getElementById('generateQRBtn');
    const saveUserBtn = document.getElementById('saveUserBtn');
    
    // Reset form
    form.reset();
    
    // Enable all form inputs
    form.querySelectorAll('input, select').forEach(input => {
        input.disabled = false;
    });
    
    // Hide QR code container and clear its contents
    qrCodeContainer.style.display = 'none';
    qrCode.innerHTML = '';
    document.getElementById('backupCode').textContent = '';
    
    // Reset buttons
    generateQRBtn.style.display = 'block';
    generateQRBtn.disabled = false;
    saveUserBtn.style.display = 'none';

    // Remove any hidden secret_key input if it exists
    const secretKeyInput = form.querySelector('input[name="secret_key"]');
    if (secretKeyInput) {
        secretKeyInput.remove();
    }
}

// Make sure modal is properly initialized
document.addEventListener('DOMContentLoaded', function() {
    const addUserModal = document.getElementById('addUserModal');
    if (addUserModal) {
        new bootstrap.Modal(addUserModal);
    }
});
</script>