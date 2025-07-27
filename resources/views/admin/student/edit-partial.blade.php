<style>
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
<form id="staffEditForm">
    <div class="form-content">
        <div class="avatar-section">
            <div class="avatar-container" id="avatarContainer">
                <?php
                    // Get the avatar path from the database
                    $avatarPath = $student->user->avatar;
                    // dd($student->user->avatar);
                    
                    // Check if the avatar exists in the storage folder
                    $avatarFilePath = storage_path('app/public/' . $avatarPath); // Path inside storage directory
                    $avatarUrl = null;

                    // If the file exists, generate the URL for the avatar
                    if ($avatarPath && file_exists($avatarFilePath)) {
                        $avatarUrl = asset('storage/' . $avatarPath); // Generate URL to display image
                    }
                ?>
                
                <!-- Avatar placeholder, shown if avatar is not available -->
                <div class="avatar-placeholder" id="avatarPlaceholder" style="<?= $avatarUrl ? 'display: none;' : ''; ?>">👤</div>
                
                <!-- Avatar preview image -->
                <img id="avatarPreview" src="<?= $avatarUrl ?>" class="avatar-preview" style="<?= $avatarUrl ? 'display: block;' : 'display: none;' ?>" alt="Avatar Preview">
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
            <input type="file" id="avatarInput" accept="image/*" >
        </div>

        <div class="form-section">
            <div class="form-row">     
                <div class="form-group">
                    <label class="form-label"  for="faculty-select-edit">Select Faculty:</label>
                    <select id="faculty-select-edit" class="faculty-select form-select">
                        <option value="">Select Faculty</option>
                        @foreach($faculties as $faculty)
                            <option {{ (isset($selected_faculty) && $selected_faculty == $faculty->id) ? 'selected' : '' }} value="{{ $faculty->id }}">{{ $faculty->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <!-- Batch Filter (Initially Disabled) -->
                    <label class="form-label"  for="batch-select-edit">Select Batch:</label>
                    <select id="batch-select-edit" class="form-select" @if(!isset($batches)) disabled @endif>
                        <option value="">Select Batch</option>
                        @if (isset($batches))
                            @foreach($batches as $batch)
                                <option {{ ($selected_batch == $batch->id) ? 'selected' : '' }}  value="{{ $batch->id }}">{{ $batch->title }}</option>
                            @endforeach                             
                        @endif
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <!-- Class Filter (Initially Disabled) -->
                    <label class="form-label"  for="class-select-edit">Select Class:</label>
                    <select id="class-select-edit" class="form-select">
                        <option value="">Select Class</option>
                        @if (isset($faculty_classes))
                            @foreach($faculty_classes as $class)
                                <option {{ ($student->class_id == $class->id) ? 'selected' : '' }}  value="{{ $class->id }}">{{ $class->title }}</option>
                            @endforeach                            
                        @else
                            @foreach($classesWithNoBatch as $class)
                                <option {{ ($student->class_id == $class->id) ? 'selected' : '' }}  value="{{ $class->id }}">{{ $class->title }}</option>
                            @endforeach                            
                        @endif
                    </select>
                </div>
                <div class="form-group">
                    <!-- Section Filter  @if(empty($sections)) disabled @endif-->
                    <label class="form-label" for="section-select-edit">Select Section:</label>
                    <select id="section-select-edit" class="form-select">
                        <option value="">Select Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" 
                                @if($section->id == $student->section_id) selected @endif>
                                {{ $section->title }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="name-edit">
                        Full Name <span class="required">*</span>
                    </label>
                    <input type="text" id="name-edit" name="name" class="form-input" value="{{ $student->name }}" required>
                    <div class="error-message" id="nameError">Please enter a valid name</div>
                </div>
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ $student->user->email }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="form-input" value="{{ $student->user->phone }}">
                </div>
                <div class="form-group">
                    <label class="form-label" for="gender">Gender</label>
                    <select id="gender" name="gender" class="form-select">
                        <option {{ ($student->gender == '' || $student->gender == null) ? 'selected' : '' }} value="">Select Gender</option>
                        <option {{ ($student->gender == 0) ? 'selected' : '' }} value="0">Male</option>
                        <option {{ ($student->gender == 1) ? 'selected' : '' }} value="1">Female</option>
                        <option {{ ($student->gender == 2) ? 'selected' : '' }} value="2">Other</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="handicapped-edit">Handicapped</label>
                    <select id="handicapped-edit" name="handicapped" class="form-select">
                        <option {{ ($student->handicapped == '' || $student->handicapped == null) ? 'selected' : '' }} value="">Select Yes/No</option>
                        <option {{ ($student->handicapped == 1) ? 'selected' : '' }} value="1">Yes</option>
                        <option {{ ($student->handicapped == 0) ? 'selected' : '' }} value="0">No</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group full-width">
                    <label class="form-label" for="address">Address</label>
                    <input type="text" id="address" name="address" class="form-input" placeholder="Enter full address..." value="{{ nl2br(e($student->address)) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-submit">
            ✅ Update
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
            const staffForm = $('#staffEditForm');

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
                const name = $('#name-edit').val().trim();

                // Reset error states
                $('.error-message').hide();
                $('.form-input').removeClass('invalid');

                // Validate name (required)
                if (name === '') {
                    $('#nameError').show();
                    $('#name-edit').addClass('invalid');
                    isValid = false;
                }

                return isValid;
            }

            // Handle form submission
           
            // Handle form submission
            staffForm.on('submit', function(e) {
                e.preventDefault();
                page = table.page();  // Get the current page index (zero-based)
                
                if (validateForm()) {
                    // Get form data
                    const formData = new FormData();
                    formData.append('name', $('#name-edit').val());
                    formData.append('email', $('#email').val());
                    formData.append('phone', $('#phone').val());
                    formData.append('gender', $('#gender').val());
                    formData.append('address', $('#address').val());
                    formData.append('faculty_id', $('#faculty-select-edit').val());
                    formData.append('batch_id', $('#batch-select-edit').val());
                    formData.append('class_id', $('#class-select-edit').val());
                    formData.append('section_id', $('#section-select-edit').val());
                    formData.append('handicapped', $('#handicapped-edit').val());
                    // formData.append('old_avatar', $('#old_avatar').val());
                    // console.log(avatarInput[0].files[0])

                    if (avatarInput[0].files[0]) {
                        formData.append('avatar', avatarInput[0].files[0]);
                    }
                    formData.append('_token', "{{ csrf_token() }}");  // Append CSRF token to form data

                    // Simulate form submission
                    const submitBtn = $('.btn-submit');
                    const originalText = submitBtn.text();
                    
                    var staffId = '{{$student->id}}';
                    submitBtn.text('Updating...').prop('disabled', true); 
                    // for (var pair of formData.entries()) {
                    //     console.log(pair[0]+ ': ' + pair[1]);
                    // }
                    console.log(staffId)
                    $.ajax({
                        url: "{{ route('students.update', '') }}/" + staffId,
                        type: 'POST',
                        headers: {  
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        data: formData,
                        processData: false, 
                        contentType: false,
                        success: function(response) {
                            // $('.spinner-div-edit-staff').show();
                            // submitBtn.text(originalText).prop('disabled', false);
                            toastr.success('Student updated successfully!');
                            $('#entityModal').modal('hide');
                            // loadStaffs(page); // Refresh the staff list
                            // Preserve the search term when reloading the table data
                            var searchValue = table.search();  // Get current search term
                            table.ajax.reload(function() {
                                table.search(searchValue).draw();  // Apply the previous search term
                            }, false);  // `false` to prevent page reset
                        },
                        error: function(xhr) {
                            // $('.spinner-div-staff').hide();
                            toastr.error('There was an error while creating the Student.');
                        }
                    });
                }
            });

            // function loadStaffs() {
            //     $('.spinner-div-staff').show();
            //     $.ajax({
            //         url: "{{ route('staffs.list-partial') }}", // Create a route for this
            //         type: 'GET',
            //         success: function (data) {
            //             setTimeout(() => {
            //                 $('.spinner-div-staff').hide();
            //                 $('#staff-container').html(data);                            
            //             }, 300);
            //         }
            //     });
            // }

            // Handle form reset
            staffForm.on('reset', function() {
                setTimeout(() => {
                    removeBtn.click();
                    $('.error-message').hide();
                    $('.form-input').removeClass('invalid');
                }, 0);
            });

            // Real-time validation
            $('#name-edit').on('input', function() {
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

            // When Faculty is selected
            $('#faculty-select-edit').on('change', function() {
                var facultyId = $(this).val();

                // Disable batch, class, and section initially
                $('#batch-select-edit').prop('disabled', true);
                $('#class-select-edit').prop('disabled', true);
                $('#section-select-edit').prop('disabled', true);
                // Reset the class and section selects to empty values
                $('#class-select-edit').empty().append('<option value="">Select Class</option>');
                $('#section-select-edit').empty().append('<option value="">Select Section</option>');

                if(facultyId) {
                    // Enable batch dropdown
                    $('#batch-select-edit').prop('disabled', false);

                    // Get batches based on selected faculty
                    $.ajax({
                        url: '/get-batches/' + facultyId,  // Route to get batches by faculty
                        type: 'GET',
                        success: function(data) {
                            $('#batch-select-edit').empty().append('<option value="">Select Batch</option>');
                            $.each(data, function(key, value) {
                                $('#batch-select-edit').append('<option value="' + value.id + '">' + value.title + '</option>');
                            });
                        }
                    });
                } else {
                    $('#batch-select-edit').empty().append('<option value="">Select Batch</option>');
                    $.ajax({
                        url: '/get-classes-without-batch',  // Route to get classes without a batch
                        type: 'GET',
                        success: function(data) {
                            $('#class-select-edit').empty().append('<option value="">Select Class</option>');
                            $.each(data, function(key, value) {
                                $('#class-select-edit').append('<option value="' + value.id + '">' + value.title + '</option>');
                            });

                            // Enable class dropdown
                            $('#class-select-edit').prop('disabled', false);
                        }
                    });
                }
            });

            // When Batch is selected
            $('#batch-select-edit').on('change', function() {
                var batchId = $(this).val();

                // Disable class and section filters initially
                $('#class-select-edit').prop('disabled', true);
                $('#section-select-edit').prop('disabled', true);

                if(batchId) {
                    // Enable class dropdown
                    $('#class-select-edit').prop('disabled', false);

                    // Get classes based on selected batch
                    $.ajax({
                        url: '/get-classes/' + batchId,  // Route to get classes by batch
                        type: 'GET',
                        success: function(data) {
                            $('#class-select-edit').empty().append('<option value="">Select Class</option>');
                            $.each(data, function(key, value) {
                                $('#class-select-edit').append('<option value="' + value.id + '">' + value.title + '</option>');
                            });
                        }
                    });
                } else {
                    $('#class-select-edit').empty().append('<option value="">Select Class</option>');
                }
            });

            // When Class is selected
            $('#class-select-edit').on('change', function() {
                var classId = $(this).val();

                // Disable section filter initially
                $('#section-select-edit').prop('disabled', true);

                if(classId) {
                    // Enable section dropdown
                    $('#section-select-edit').prop('disabled', false);

                    // Get sections based on selected class
                    $.ajax({
                        url: '/get-sections/' + classId,  // Route to get sections by class
                        type: 'GET',
                        success: function(data) {
                            $('#section-select-edit').empty().append('<option value="">Select Section</option>');
                            $.each(data, function(key, value) {
                                $('#section-select-edit').append('<option value="' + value.id + '">' + value.title + '</option>');
                            });
                        }
                    });
                } else {
                    $('#section-select').empty().append('<option value="">Select Section</option>');
                }
            });
        });
    </script>