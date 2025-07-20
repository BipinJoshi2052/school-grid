<style>
    /* .form-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 900px;
        border: 1px solid rgba(255, 255, 255, 0.2);
    } */

    /* .form-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .form-header h1 {
        color: #2c3e50;
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .form-header p {
        color: #7f8c8d;
        font-size: 1.1rem;
    } */

    .form-content {
        display: flex;
        /* gap: 40px; */
        align-items: flex-start;
        padding: 10px;
    }

    .avatar-section {
        flex: 0 0 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .avatar-container {
        position: relative;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        border: 4px solid #e1e8ed;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 20px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .avatar-container:hover {
        border-color: #667eea;
        transform: scale(1.05);
    }

    .avatar-placeholder {
        color: #95a5a6;
        font-size: 3rem;
        transition: all 0.3s ease;
    }

    .avatar-preview {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 50%;
    }

    .avatar-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        justify-content: center;
    }

    .form-section {
        flex: 1;
    }

    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
    }

    .form-group {
        flex: 1;
    }

    .form-group.full-width {
        flex: 1 1 100%;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #2c3e50;
        font-size: 0.95rem;
    }

    .required {
        color: #e74c3c;
        margin-left: 4px;
    }

    .form-input {
        width: 100%;
        padding: 10px 10px;
        border: 2px solid #e1e8ed;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-input:invalid {
        border-color: #e74c3c;
    }

    .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e1e8ed;
        border-radius: 12px;
        font-size: 1rem;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-textarea {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e1e8ed;
        border-radius: 12px;
        font-size: 1rem;
        resize: vertical;
        min-height: 100px;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-textarea:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 40px;
    }

    .btn-submit {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .btn-reset {
        background: #6c757d;
        color: white;
        padding: 15px 40px;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .btn-reset:hover {
        background: #5a6268;
        transform: translateY(-2px);
        color: white;
    }

    #avatarInput {
        display: none;
    }

    .error-message {
        color: #e74c3c;
        font-size: 0.85rem;
        margin-top: 5px;
        display: none;
    }

    @media (max-width: 768px) {
        .form-content {
            flex-direction: column;
            /* gap: 30px; */
            text-align: center;
            align-items: center;
        }
        
        .avatar-section {
            flex: none;
            align-self: center;
        }
        
        .form-row {
            flex-direction: column;
            gap: 15px;
        }
        
        /* .form-container {
            padding: 20px;
        } */
    }

    .fade-in {
        animation: fadeIn 0.5s ease-in;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<form id="staffForm">
    <div class="form-content">
        <div class="avatar-section">
            <div class="avatar-container" id="avatarContainer">
                <div class="avatar-placeholder" id="avatarPlaceholder">👤</div>
                <img id="avatarPreview" class="avatar-preview" style="display: none;" alt="Avatar Preview">
            </div>
            <div class="avatar-actions">
                <button type="button" class="btn btn-primary" id="uploadBtn">
                    📷 Upload Photo
                </button>
                <button type="button" class="btn btn-secondary" id="editBtn" style="display: none;">
                    ✏️ Edit
                </button>
                <button type="button" class="btn btn-danger" id="removeBtn" style="display: none;">
                    🗑️ Remove
                </button>
            </div>
            <input type="file" id="avatarInput" accept="image/*">
        </div>

        <div class="form-section">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="name">
                        Full Name <span class="required">*</span>
                    </label>
                    <input type="text" id="name" name="name" class="form-input" required>
                    <div class="error-message" id="nameError">Please enter a valid name</div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-input">
                </div>
                <div class="form-group">
                    <label class="form-label" for="gender">Gender</label>
                    <select id="gender" name="gender" class="form-select">
                        <option value="">Select Gender</option>
                        <option value="0">Male</option>
                        <option value="1">Female</option>
                        <option value="2">Other</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="departmentId">Department</label>
                    <select id="departmentId" name="department_id" class="form-select">
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label" for="positionId">Position</label>
                    <select id="positionId" name="position_id" class="form-select">
                        <option value="">Select Position</option>
                        @foreach ($positions as $position)
                            <option value="{{ $position->id }}">{{ $position->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="joinedDate">Joined Date</label>
                    <input type="date" id="joinedDate" name="joined_date" class="form-input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label class="form-label" for="address">Address</label>
                    <textarea id="address" name="address" class="form-textarea" placeholder="Enter full address..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-submit">
            ✅ Create
        </button>
        <button type="reset" class="btn btn-reset">
            🔄 Reset
        </button>
    </div>
</form>

 <script>
        $(document).ready(function() {
            const avatarInput = $('#avatarInput');
            const avatarPreview = $('#avatarPreview');
            const avatarPlaceholder = $('#avatarPlaceholder');
            const uploadBtn = $('#uploadBtn');
            const editBtn = $('#editBtn');
            const removeBtn = $('#removeBtn');
            const staffForm = $('#staffForm');

            $('#joinedDate').on('click', function() {
                this.showPicker();
            });
            // Handle avatar upload
            uploadBtn.on('click', function() {
                avatarInput.click();
            });

            editBtn.on('click', function() {
                avatarInput.click();
            });

            // Handle file selection
            avatarInput.on('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        alert('Please select a valid image file.');
                        return;
                    }

                    // Validate file size (max 5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        alert('Please select an image smaller than 5MB.');
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.attr('src', e.target.result);
                        avatarPreview.show();
                        avatarPlaceholder.hide();
                        uploadBtn.hide();
                        editBtn.show();
                        removeBtn.show();
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Handle avatar removal
            removeBtn.on('click', function() {
                avatarPreview.hide();
                avatarPlaceholder.show();
                uploadBtn.show();
                editBtn.hide();
                removeBtn.hide();
                avatarInput.val('');
            });

            // Form validation
            function validateForm() {
                let isValid = true;
                const name = $('#name').val().trim();

                // Reset error states
                $('.error-message').hide();
                $('.form-input').removeClass('invalid');

                // Validate name (required)
                if (name === '') {
                    $('#nameError').show();
                    $('#name').addClass('invalid');
                    isValid = false;
                }

                return isValid;
            }

            // Handle form submission
            staffForm.on('submit', function(e) {
                e.preventDefault();
                
                if (validateForm()) {
                    // Get form data
                    const formData = new FormData();
                    formData.append('name', $('#name').val());
                    formData.append('email', $('#email').val());
                    formData.append('phone', $('#phone').val());
                    formData.append('gender', $('#gender').val());
                    formData.append('address', $('#address').val());
                    formData.append('joined_date', $('#joinedDate').val());
                    formData.append('department_id', $('#departmentId').val());
                    formData.append('position_id', $('#positionId').val());
                    
                    if (avatarInput[0].files[0]) {
                        formData.append('avatar', avatarInput[0].files[0]);
                    }
                    
                    formData.append('_token', "{{ csrf_token() }}");

                    // Simulate form submission
                    const submitBtn = $('.btn-submit');
                    const originalText = submitBtn.text();
                    
                    submitBtn.text('Creating...').prop('disabled', true);

                    $.ajax({
                        url: "{{ route('staffs.store') }}",
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $('.spinner-div-staff').hide();
                            // submitBtn.text(originalText).prop('disabled', false);
                            toastr.success('Staff created successfully!');
                            $('#entityModal').modal('hide');
                            loadStaffs(); // Refresh the staff list
                        },
                        error: function(xhr) {
                            $('.spinner-div-staff').hide();
                            toastr.error('There was an error while creating the staff.');
                        }
                    });
                    
                    // setTimeout(() => {
                    //     alert('Staff member created successfully!');
                    // }, 2000);
                }
            });

            function loadStaffs() {
                $('.spinner-div-staff').show();
                $.ajax({
                    url: "{{ route('staffs.list-partial') }}", // Create a route for this
                    type: 'GET',
                    success: function (data) {
                        setTimeout(() => {
                            $('.spinner-div-staff').hide();
                            $('#staff-container').html(data);                            
                        }, 300);
                    }
                });
            }
            // Handle form reset
            staffForm.on('reset', function() {
                setTimeout(() => {
                    removeBtn.click();
                    $('.error-message').hide();
                    $('.form-input').removeClass('invalid');
                }, 0);
            });

            // Real-time validation
            $('#name').on('input', function() {
                const name = $(this).val().trim();
                if (name !== '') {
                    $('#nameError').hide();
                    $(this).removeClass('invalid');
                }
            });

            // Add smooth animations
            $('.form-input, .form-select, .form-textarea').on('focus', function() {
                $(this).parent().addClass('focused');
            }).on('blur', function() {
                $(this).parent().removeClass('focused');
            });
        });
    </script>