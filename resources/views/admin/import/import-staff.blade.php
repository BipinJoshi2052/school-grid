@extends('layouts.admin')

@section('title')
    Import
@endsection

@section('styles')
    <style>
        .help-button {
            position: absolute;
            top: 30px;
            right: 30px;
            background: rgb(19 30 148);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
        }

        .help-button:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }

        .help-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .help-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 800px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
        }

        .help-header {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }

        .help-header h2 {
            color: #4facfe;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .help-section {
            margin-bottom: 30px;
        }

        .help-section h3 {
            color: #333;
            font-size: 1.3rem;
            margin-bottom: 15px;
            border-bottom: 2px solid #4facfe;
            padding-bottom: 5px;
        }

        .help-list {
            list-style: none;
            padding: 0;
        }

        .help-list li {
            background: #f8f9fa;
            margin-bottom: 8px;
            padding: 10px 15px;
            border-radius: 8px;
            border-left: 4px solid #28a745;
        }

        .help-list li.required {
            border-left-color: #dc3545;
            background: #fff5f5;
        }

        .help-list li.optional {
            border-left-color: #ffc107;
            background: #fffbf0;
        }

        .download-sample {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            margin: 20px 0;
        }

        .download-sample:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(40, 167, 69, 0.4);
            text-decoration: none;
            color: white;
        }

        .close-help {
            position: absolute;
            top: 15px;
            right: 20px;
            background: none;
            border: none;
            font-size: 2rem;
            color: #999;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close-help:hover {
            color: #333;
        }

        .validation-progress {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .validation-errors {
            display: none;
            background: #f8d7da;
            border: 2px solid #f5c6cb;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .validation-errors h4 {
            color: #721c24;
            margin-bottom: 15px;
        }

        .error-list {
            list-style: none;
            padding: 0;
        }

        .error-list li {
            background: white;
            padding: 10px 15px;
            margin-bottom: 8px;
            border-radius: 8px;
            border-left: 4px solid #dc3545;
            color: #721c24;
        }

        .status-info {
            background: #e7f3ff;
            border: 2px solid #b3d9ff;
            border-radius: 10px;
            padding: 15px;
            margin: 15px 0;
            color: #0056b3;
        }

        .header {
            background: linear-gradient(to right, #8971ea, #7f72ea, #7574ea, #6a75e9, #5f76e8);
            /* background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); */
            color: white;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .upload-section {
            padding: 40px;
            text-align: center;
        }

        .upload-area {
            border: 3px dashed #4facfe;
            border-radius: 15px;
            padding: 60px 20px;
            background: linear-gradient(135deg, #f8f9ff 0%, #e8f4f8 100%);
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .upload-area:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #e8f4f8 0%, #f8f9ff 100%);
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(79, 172, 254, 0.3);
        }

        .upload-area::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(79, 172, 254, 0.1) 0%, transparent 70%);
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 0.8; }
        }

        .upload-icon {
            font-size: 4rem;
            color: #4facfe;
            margin-bottom: 20px;
            display: block;
        }

        .upload-text {
            font-size: 1.3rem;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .upload-hint {
            color: #666;
            font-size: 0.9rem;
        }

        #fileInput {
            display: none;
        }

        .loading-container {
            display: none;
            padding: 30px;
            text-align: center;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #4facfe;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .loading-text {
            font-size: 1.2rem;
            color: #333;
            font-weight: 600;
        }

        .mapping-section {
            display: none;
            padding: 40px;
        }

        .mapping-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 30px;
        }

        .column {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 30px;
            border: 2px solid #e9ecef;
        }

        .column h3 {
            color: #333;
            margin-bottom: 20px;
            font-size: 1.5rem;
            text-align: center;
            padding-bottom: 15px;
            border-bottom: 3px solid #4facfe;
        }

        .csv-headings {
            border-color: #28a745;
        }

        .csv-headings h3 {
            border-bottom-color: #28a745;
        }

        .required-headings {
            border-color: #dc3545;
        }

        .required-headings h3 {
            border-bottom-color: #dc3545;
        }

        .heading-item {
            background: white;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
            font-weight: 600;
        }

        .csv-heading {
            cursor: pointer;
            border-left: 5px solid #28a745;
        }

        .csv-heading:hover {
            background: #e8f5e8;
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.2);
        }

        .csv-heading.selected {
            background: #d4edda;
            border-color: #155724;
            transform: translateX(5px);
        }

        .required-heading {
            border-left: 5px solid #dc3545;
            position: relative;
        }

        .required-heading.mapped {
            background: #d1ecf1;
            border-left-color: #0c5460;
        }

        .mapped-info {
            font-size: 0.9rem;
            color: #0c5460;
            margin-top: 8px;
            font-style: italic;
        }

        .validation-message {
            padding: 20px;
            border-radius: 10px;
            margin-top: 10px;
            font-weight: 600;
            text-align: center;
            display: none;
        }

        .validation-error {
            background: #f8d7da;
            color: #721c24;
            border: 2px solid #f5c6cb;
        }

        .validation-success {
            background: #d4edda;
            color: #155724;
            border: 2px solid #c3e6cb;
        }

        .action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(79, 172, 254, 0.4);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            color: #8b4513;
        }

        .btn-secondary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(252, 182, 159, 0.4);
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .error-message {
            color: #dc3545;
            background: #f8d7da;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            border: 2px solid #f5c6cb;
            display: none;
            margin-bottom: 5px;
        }

        .warning-message{
            color: #000;
            background: #fdc16a;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            border: 2px solid #f5c6cb;
            display: none;
            margin-bottom: 5px;
        }

        .success-message {
            color: #155724;
            background: #d4edda;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            border: 2px solid #c3e6cb;
            display: none;
        }

        .upload-progress {
            display: none;
            margin-top: 20px;
        }

        .progress-bar {
            width: 100%;
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #4facfe, #00f2fe);
            width: 0%;
            transition: width 0.3s ease;
        }

        @media (max-width: 768px) {
            .mapping-container {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Import!</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Import Staff</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="header">
                            <div class="help-button" onclick="showHelp()" title="Help & Instructions">
                                ?
                            </div>
                            <h1>CSV Upload & Mapping System</h1>
                            <p>Upload your CSV file and map the columns to our system</p>
                        </div>

                        <div class="upload-section">
                            <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                                <span class="upload-icon">📁</span>
                                <div class="upload-text">Click to upload CSV file</div>
                                <div class="upload-hint">Drag and drop or click to select your CSV file</div>
                            </div>
                            <input type="file" id="fileInput" accept=".csv" />
                        </div>

                        <div class="loading-container">
                            <div class="loading-spinner"></div>
                            <div class="loading-text">Processing your CSV file...</div>
                        </div>


                        <div class="mapping-section">
                            
                            <div class="mapping-container">
                                <div class="column csv-headings">
                                    <h3>CSV File Headings</h3>
                                    <div id="csvHeadings"></div>
                                </div>
                                
                                <div class="column required-headings">
                                    <h3>Required System Headings</h3>
                                    <div id="requiredHeadings"></div>
                                </div>
                            </div>

                            <div class="action-buttons">
                                <button class="btn btn-secondary" onclick="resetSystem()">Reset</button>
                                <button class="btn btn-primary" id="validateDataBtn" onclick="validateData()">Validate & Upload Data</button>
                            </div>


                            <div class="error-message" id="errorMessage"></div>
                            <div class="warning-message" id="warningMessage"></div>
                            <div class="validation-message" id="validationMessage"></div>


                            <div class="validation-progress">
                                <div class="loading-spinner"></div>
                                <div class="loading-text" id="validationText">Validating Data...</div>
                            </div>

                            <div class="upload-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill"></div>
                                </div>
                                {{-- <div style="text-align: center; margin-top: 10px;">Uploading data...</div> --}}
                            </div>
                            <div class="validation-errors" id="validationErrors">
                                <h4>❌ Validation Errors Found:</h4>
                                <ul class="error-list" id="errorList"></ul>
                            </div>
                        </div>

                        <div class="success-message" id="successMessage"></div>
                    </div>

                    <!-- Help Modal -->
                    <div class="help-modal" id="helpModal">
                        <div class="help-content">
                            <button class="close-help" onclick="hideHelp()">&times;</button>
                            <div class="help-header">
                                <h2>📋 CSV Import Instructions</h2>
                                <p>Learn how to properly format and import your data</p>
                            </div>

                            <div class="help-section">
                                <h3>🎯 How It Works</h3>
                                <p>Our system allows you to import data from any CSV file format. You don't need to worry about having exact column names - simply map your CSV columns to our system requirements and we'll handle the rest!</p>
                            </div>

                            <div class="help-section">
                                <h3>📊 Required vs Optional Columns</h3>
                                <ul class="help-list">
                                    <li class="required"><strong>Name</strong> - Full name of the person (Required)</li>
                                    <li class="required"><strong>Email</strong> - Valid email address (Required)</li>
                                    <li class="optional"><strong>Phone</strong> - Phone number (Optional)</li>
                                    <li class="optional"><strong>Status</strong> - Employee status (Optional)</li>
                                    <li class="optional"><strong>Department</strong> - Work department (Optional)</li>
                                    <li class="optional"><strong>Position</strong> - Job position/title (Optional)</li>
                                    <li class="optional"><strong>Gender</strong> - Gender information (Optional)</li>
                                    <li class="optional"><strong>Joined Date</strong> - Date of joining (Optional)</li>
                                    <li class="optional"><strong>Address</strong> - Physical address (Optional)</li>
                                </ul>
                            </div>

                            <div class="help-section">
                                <h3>⚙️ Special Column Requirements</h3>
                                <div class="status-info">
                                    <strong>Status Column:</strong> Can contain values like:
                                    <br>• <code>1</code> or <code>0</code> (Active/Inactive)
                                    <br>• <code>yes</code> or <code>no</code> (Active/Inactive)
                                    <br>• <code>active</code> or <code>inactive</code>
                                </div>
                                <div class="status-info">
                                    <strong>Gender Column:</strong> Can contain values like:
                                    <br>• <code>male</code> or <code>female</code> or <code>other</code> (Active/Inactive)
                                </div>
                                <div class="status-info">
                                    <strong>Joined Date:</strong> Should be in valid date formats like:
                                    <br>• <code>YYYY-MM-DD</code> (2024-01-15)
                                    <br>• <code>MM/DD/YYYY</code> (01/15/2024)
                                    <br>• <code>DD/MM/YYYY</code> (15/01/2024)
                                </div>
                            </div>

                            <div class="help-section">
                                <h3>📝 Step-by-Step Process</h3>
                                <ol style="padding-left: 20px; color: #333;">
                                    <li style="margin-bottom: 10px;"><strong>Upload:</strong> Choose your CSV file</li>
                                    <li style="margin-bottom: 10px;"><strong>Map:</strong> Click on your CSV column, then click on the corresponding system column</li>
                                    <li style="margin-bottom: 10px;"><strong>Validate:</strong> Click "Validate Data" to check for errors</li>
                                    <li style="margin-bottom: 10px;"><strong>Import:</strong> If validation passes, data will be sent to our system</li>
                                </ol>
                                <p><b>Note :</b> Data is created or updated based on the email. If user with same email exists, then the users data will be updated.</p>
                            </div>

                            <div class="help-section">
                                <h3>📥 Sample File</h3>
                                <p>Download our sample CSV file to see the correct format:</p>
                                <button class="download-sample" onclick="downloadSample()">
                                    📊 Download Sample CSV File
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')  
    <script src="https://cdn.jsdelivr.net/npm/papaparse@5.5.3/papaparse.min.js"></script>
    <script>
        // System configuration
        const requiredHeadings = ['Name', 'Email', 'Phone', 'Status', 'Department', 'Position', 'Gender', 'Joined Date', 'Address'];
        const mandatoryHeadings = ['Name', 'Email'];
        let csvData = [];
        let csvHeadings = [];
        let mappings = {};
        let selectedCsvHeading = null;
        let validationErrors = [];

        // Sample data for download
        const sampleData = [
            {
                'Name': 'John Doe',
                'Email': 'john.doe@company.com',
                'Phone': '+1-555-0123',
                'Status': 'active',
                'Department': 'Engineering',
                'Position': 'Software Developer',
                'Gender': 'Male',
                'Joined Date': '2023-01-15',
                'Address': '123 Main St, New York, NY 10001'
            },
            {
                'Name': 'Jane Smith',
                'Email': 'jane.smith@company.com',
                'Phone': '+1-555-0456',
                'Status': 'inactive',
                'Department': 'Marketing',
                'Position': 'Marketing Manager',
                'Gender': 'Female',
                'Joined Date': '2022-08-20',
                'Address': '456 Oak Ave, Los Angeles, CA 90210'
            }
        ];

        // File input handling
        $('#fileInput').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                processFile(file);
            }
        });

        // Drag and drop functionality
        $('.upload-area').on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('dragover');
        });

        $('.upload-area').on('dragleave', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
        });

        $('.upload-area').on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('dragover');
            const file = e.originalEvent.dataTransfer.files[0];
            if (file) {
                processFile(file);
            }
        });

        function processFile(file) {
            // Reset previous states
            hideMessages();
            
            // Check if file is CSV
            if (!file.name.toLowerCase().endsWith('.csv')) {
                showError('Please upload a valid CSV file.');
                return;
            }

            // Show loading
            $('.upload-section').hide();
            $('.loading-container').show();

            // Parse CSV
            Papa.parse(file, {
                header: true,
                skipEmptyLines: true,
                dynamicTyping: true,
                complete: function(results) {
                    setTimeout(() => {
                        if (results.errors.length > 0) {
                            showError('Error parsing CSV file: ' + results.errors[0].message);
                            resetToUpload();
                            return;
                        }

                        csvData = results.data;
                        csvHeadings = results.meta.fields || [];
                        
                        if (csvHeadings.length === 0) {
                            showError('No headings found in CSV file.');
                            resetToUpload();
                            return;
                        }

                        // Clean headings (remove whitespace)
                        csvHeadings = csvHeadings.map(h => h.trim()).filter(h => h);
                        
                        showMappingInterface();
                    }, 1500); // Simulate processing time
                },
                error: function(error) {
                    showError('Error reading CSV file: ' + error.message);
                    resetToUpload();
                }
            });
        }

        function showMappingInterface() {
            $('.loading-container').hide();
            $('.mapping-section').show();
            
            // Reset mappings
            mappings = {};
            selectedCsvHeading = null;
            
            // Populate CSV headings
            const csvContainer = $('#csvHeadings');
            csvContainer.empty();
            csvHeadings.forEach(heading => {
                const headingElement = $(`
                    <div class="heading-item csv-heading" data-heading="${heading}">
                        ${heading}
                    </div>
                `);
                csvContainer.append(headingElement);
            });

            // Populate required headings
            const requiredContainer = $('#requiredHeadings');
            requiredContainer.empty();
            requiredHeadings.forEach(heading => {
                const headingElement = $(`
                    <div class="heading-item required-heading" data-required="${heading}">
                        ${heading}
                        <div class="mapped-info" style="display: none;"></div>
                    </div>
                `);
                requiredContainer.append(headingElement);
            });

            // Add click handlers
            addClickHandlers();
        }

        function addClickHandlers() {
            // CSV heading click handler
            $('.csv-heading').on('click', function() {
                hideMessages();
                $('.csv-heading').removeClass('selected');
                $(this).addClass('selected');
                selectedCsvHeading = $(this).data('heading');
                updateValidation();
            });

            // Required heading click handler
            $('.required-heading').on('click', function() {
                if (selectedCsvHeading) {
                    const requiredHeading = $(this).data('required');
                    
                    // Remove previous mapping for this CSV heading
                    Object.keys(mappings).forEach(key => {
                        if (mappings[key] === selectedCsvHeading) {
                            delete mappings[key];
                        }
                    });

                    // Add new mapping
                    mappings[requiredHeading] = selectedCsvHeading;
                    console.log(mappings)
                    
                    // Update UI
                    updateMappingDisplay();
                    
                    // Clear selection
                    $('.csv-heading').removeClass('selected');
                    selectedCsvHeading = null;
                    
                    updateValidation();
                }
            });
        }

        function updateMappingDisplay() {
            $('.required-heading').each(function() {
                const requiredHeading = $(this).data('required');
                const mappedInfo = $(this).find('.mapped-info');
                
                if (mappings[requiredHeading]) {
                    $(this).addClass('mapped');
                    mappedInfo.text(`Mapped to: ${mappings[requiredHeading]}`).show();
                } else {
                    $(this).removeClass('mapped');
                    mappedInfo.hide();
                }
            });
        }

        function updateValidation() {
            const unmappedMandatory = mandatoryHeadings.filter(heading => !mappings[heading]);
            const validationMessage = $('#validationMessage');
            
            if (unmappedMandatory.length === 0) {
                const totalMapped = Object.keys(mappings).length;
                validationMessage.removeClass('validation-error').addClass('validation-success');
                validationMessage.text(`✅ All required headings mapped! (${totalMapped}/${requiredHeadings.length} columns mapped)`);
                validationMessage.show();
            } else {
                validationMessage.removeClass('validation-success').addClass('validation-error');
                validationMessage.text(`❌ Please map required headings: ${unmappedMandatory.join(', ')}`);
                validationMessage.show();
            }
        }

        function validateData() {
            // Reset validation state
            validationErrors = [];
            hideMessages();
            $('.validation-errors').hide();
            
            // Check if mandatory headings are mapped
            const unmappedMandatory = mandatoryHeadings.filter(heading => !mappings[heading]);
            
            if (unmappedMandatory.length > 0) {
                showError(`Please map all required headings: ${unmappedMandatory.join(', ')}`);
                return;
            }

            // Show validation progress
            $('.validation-progress').show();
            $('#validationText').text('Validating Data...');

            // Simulate validation delay
            setTimeout(() => {
                performDataValidation();
            }, 1500);
        }

        function performDataValidation() {
            validationErrors = [];

            // Validate each row of data
            csvData.forEach((row, index) => {
                const rowNumber = index + 1;

                // Check required fields
                mandatoryHeadings.forEach(heading => {
                    const csvColumn = mappings[heading];
                    const value = row[csvColumn];
                    
                    if (!value || value.toString().trim() === '') {
                        validationErrors.push(`Row ${rowNumber}: Missing required field '${heading}'`);
                    }
                });

                // Validate email format
                if (mappings['email']) {
                    const email = row[mappings['email']];
                    if (email && !isValidEmail(email)) {
                        validationErrors.push(`Row ${rowNumber}: Invalid email format '${email}'`);
                    }
                }

                // Validate phone format
                if (mappings['phone']) {
                    const phone = row[mappings['phone']];
                    if (phone && !isValidPhone(phone)) {
                        validationErrors.push(`Row ${rowNumber}: Invalid phone format '${phone}'`);
                    }
                }

                // Validate date format
                if (mappings['joined_date']) {
                    const date = row[mappings['joined_date']];
                    if (date && !isValidDate(date)) {
                        validationErrors.push(`Row ${rowNumber}: Invalid date format '${date}' (use YYYY-MM-DD, MM/DD/YYYY, or DD/MM/YYYY)`);
                    }
                }

                // Validate status values
                if (mappings['status']) {
                    const status = row[mappings['status']];
                    if (status && !isValidStatus(status)) {
                        validationErrors.push(`Row ${rowNumber}: Invalid status '${status}' (use: active/inactive, yes/no, or 1/0)`);
                    }
                }
            });

            $('.validation-progress').hide();

            if (validationErrors.length > 0) {
                showValidationErrors();
            } else {
                const mappedData = csvData.map(row => {
                    const mappedRow = {};
                    Object.keys(mappings).forEach(requiredHeading => {
                        const csvHeading = mappings[requiredHeading];
                        mappedRow[requiredHeading] = row[csvHeading] || '';
                    });
                    return mappedRow;
                });

                $.ajax({
                    url: '/validate-staff-import', // Validation route
                    type: 'POST',
                    data: {
                        data: mappedData,
                        mappings: mappings,
                    },
                    headers: {  
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // If validation passes, trigger data upload
                        if (response.status === 'success') {
                            console.log('Validation passed, proceeding to upload...');
                            uploadData();
                            // uploadData(data, mappings);
                        } else {
                            console.log(response)
                            
                            showWarning(`Duplicate Data: ${response.message} These users will be updated. Press Upload if you wish to go on or update your file.`);
                            $('.action-buttons').html(`
                                <button class="btn btn-secondary" onclick="resetSystem()">Reset</button>
                                <button class="btn btn-primary" onclick="uploadData()">Upload Data</button>
                            `);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('reached error section of performValidation');
                        // console.log(xhr)
                        // console.log(xhr.responseJSON)
                        $('#validateDataBtn').attr('disabled','disabled');
                        $('.validation-progress').hide();
                        let errorMessage = 'Failed to upload data to server.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message.join(', ');
                        } else if (xhr.responseText) {
                            errorMessage = xhr.responseText;
                        }
                        showError(`❌ Upload failed: ${errorMessage}. Please fix the issue and reupload the file.`);
                    }
                });
                // showValidationSuccess();
            }
        }

        function showValidationErrors() {
            const errorList = $('#errorList');
            errorList.empty();
            
            validationErrors.forEach(error => {
                errorList.append(`<li>${error}</li>`);
            });
            
            $('.validation-errors').show();
        }

        function showValidationSuccess() {
            $('.validation-errors').hide();
            $('#validationMessage').removeClass('validation-error').addClass('validation-success');
            $('#validationMessage').text('✅ Data validation passed! Ready to upload.').show();
            uploadData();
            // Change button to upload
            // $('.action-buttons').html(`
            //     <button class="btn btn-secondary" onclick="resetSystem()">Reset</button>
            //     <button class="btn btn-primary" onclick="uploadData()">Upload Data</button>
            // `);
        }

        function uploadData() {
            // Prepare data for upload
            hideMessages();

            const mappedData = csvData.map(row => {
                const mappedRow = {};
                Object.keys(mappings).forEach(requiredHeading => {
                    const csvHeading = mappings[requiredHeading];
                    mappedRow[requiredHeading] = row[csvHeading] || '';
                });
                return mappedRow;
            });

            // Show upload progress
            $('.upload-progress').show();
            $('#validationText').text('Sending Data to our server...');
            $('.validation-progress').show();
            animateProgress();

            // Simulate upload to backend
            setTimeout(() => {
                uploadToBackend(mappedData);
            }, 2000);
        }

        // Validation helper functions
        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email.toString().trim());
        }

        function isValidPhone(phone) {
            const phoneStr = phone.toString().trim();
            // Allow various phone formats
            const phoneRegex = /^[\+]?[1-9][\d]{0,15}$|^[\+]?[(]?[\d\s\-\(\)]{10,}$/;
            return phoneRegex.test(phoneStr.replace(/[\s\-\(\)]/g, ''));
        }

        function isValidDate(dateStr) {
            const date = new Date(dateStr);
            return date instanceof Date && !isNaN(date);
        }

        function isValidStatus(status) {
            const statusStr = status.toString().toLowerCase().trim();
            const validStatuses = ['active', 'inactive', 'yes', 'no', '1', '0', 'true', 'false'];
            return validStatuses.includes(statusStr);
        }

        // Help modal functions
        function showHelp() {
            $('#helpModal').fadeIn(300);
        }

        function hideHelp() {
            $('#helpModal').fadeOut(300);
        }

        // Download sample CSV
        function downloadSample() {
            const timestamp = new Date().getTime();  // Get the current timestamp
            window.location.href = `/download/sample?timestamp=${timestamp}`;
            // window.location.href = '/download/sample';
        }

        // Close help modal when clicking outside
        $(document).on('click', function(e) {
            if ($(e.target).is('#helpModal')) {
                hideHelp();
            }
        });

        function uploadToBackend(data) {
            const backendUrl = '/import-staff-data';

            $.ajax({
                url: backendUrl,
                method: 'POST',
                data: JSON.stringify({
                    data: data,
                    mappings: mappings,
                    originalHeadings: csvHeadings
                }),
                headers: {  
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                contentType: 'application/json',
                success: function(response) {
                    console.log('success')
                    console.log(response)
                    $('.upload-progress').hide();
                    $('.validation-progress').hide();
                    showSuccess(`🎉 Successfully uploaded ${response.imported} records to the system!`);
                    setTimeout(() => {
                        resetSystem();
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    console.log('error')
                    console.log(status)
                    $('.upload-progress').hide();
                    $('.validation-progress').hide();
                    let errorMessage = 'Failed to upload data to server.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message.join(', ');
                    } else if (xhr.responseText) {
                        errorMessage = xhr.responseText;
                    }
                    showError(`❌ Upload failed: ${errorMessage}`);
                }
            });
        }

        function uploadToBackend2(data) {
            // Replace this URL with your actual backend endpoint
            console.log([
                data,
                mappings,
                csvHeadings
            ])
            const backendUrl = '/api/upload-csv-data';
            
            $.ajax({
                url: backendUrl,
                method: 'POST',
                data: JSON.stringify({
                    data: data,
                    mappings: mappings,
                    originalHeadings: csvHeadings
                }),
                contentType: 'application/json',
                success: function(response) {
                    $('.upload-progress').hide();
                    $('.validation-progress').hide();
                    showSuccess(`🎉 Successfully uploaded ${data.length} records to the system!`);
                    setTimeout(() => {
                        resetSystem();
                    }, 3000);
                },
                error: function(xhr, status, error) {
                    $('.upload-progress').hide();
                    $('.validation-progress').hide();
                    
                    let errorMessage = 'Failed to upload data to server.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMessage = xhr.responseText;
                    }
                    
                    showError(`❌ Upload failed: ${errorMessage}`);
                    
                    // For demo purposes, show success after error
                    setTimeout(() => {
                        showSuccess(`🎉 Demo mode: Successfully processed ${data.length} records!`);
                        setTimeout(() => {
                            resetSystem();
                        }, 3000);
                    }, 2000);
                }
            });
        }

        function animateProgress() {
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 15;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);
                }
                $('.progress-fill').css('width', progress + '%');
            }, 100);
        }

        function resetSystem() {
            csvData = [];
            csvHeadings = [];
            mappings = {};
            selectedCsvHeading = null;
            validationErrors = [];
            
            $('.mapping-section').hide();
            $('.upload-section').show();
            $('.loading-container').hide();
            $('.validation-progress').hide();
            $('.validation-errors').hide();
            $('.upload-progress').hide();
            $('#fileInput').val('');
            
            // Reset action buttons
            $('.action-buttons').html(`
                <button class="btn btn-secondary" onclick="resetSystem()">Reset</button>
                <button class="btn btn-primary" onclick="validateData()">Validate Data</button>
            `);
            
            hideMessages();
        }

        function resetToUpload() {
            $('.loading-container').hide();
            $('.upload-section').show();
        }

        function showError(message) {
            $('#errorMessage').text(message).show();
            setTimeout(() => {
                $('#errorMessage').hide();
            }, 10000);
        }
        function showWarning(message) {
            $('#warningMessage').text(message).show();
            // setTimeout(() => {
            //     $('#warningMessage').hide();
            // }, 10000);
        }

        function showSuccess(message) {
            $('#successMessage').text(message).show();
            setTimeout(() => {
                $('#successMessage').hide();
            }, 5000);
        }

        function hideMessages() {
            $('#errorMessage').hide();
            $('#successMessage').hide();
            $('#validationMessage').hide();
        }
    </script>
@endsection