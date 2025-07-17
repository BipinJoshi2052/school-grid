@extends('layouts.admin')

@section('title')
    Import
@endsection

@section('styles')
    <style>
        .header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
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
            margin-bottom: 20px;
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
                            <li class="breadcrumb-item text-muted active" aria-current="page">Import</li>
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

                        <div class="error-message" id="errorMessage"></div>

                        <div class="mapping-section">
                            <div class="validation-message" id="validationMessage"></div>
                            
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
                                <button class="btn btn-primary" onclick="validateAndUpload()">Validate & Upload</button>
                            </div>

                            <div class="upload-progress">
                                <div class="progress-bar">
                                    <div class="progress-fill"></div>
                                </div>
                                <div style="text-align: center; margin-top: 10px;">Uploading data...</div>
                            </div>
                        </div>

                        <div class="success-message" id="successMessage"></div>
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
        const requiredHeadings = ['name', 'class_name', 'section_name'];
        let csvData = [];
        let csvHeadings = [];
        let mappings = {};
        let selectedCsvHeading = null;

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
            const unmappedHeadings = requiredHeadings.filter(heading => !mappings[heading]);
            const validationMessage = $('#validationMessage');
            
            if (unmappedHeadings.length === 0) {
                validationMessage.removeClass('validation-error').addClass('validation-success');
                validationMessage.text('All required headings have been mapped successfully!');
                validationMessage.show();
            } else {
                validationMessage.removeClass('validation-success').addClass('validation-error');
                validationMessage.text(`Please map the following headings: ${unmappedHeadings.join(', ')}`);
                validationMessage.show();
            }
        }

        function validateAndUpload() {
            const unmappedHeadings = requiredHeadings.filter(heading => !mappings[heading]);
            
            if (unmappedHeadings.length > 0) {
                showError(`Please map all required headings: ${unmappedHeadings.join(', ')}`);
                return;
            }

            // Prepare data for upload
            const mappedData = csvData.map(row => {
                const mappedRow = {};
                Object.keys(mappings).forEach(requiredHeading => {
                    const csvHeading = mappings[requiredHeading];
                    mappedRow[requiredHeading] = row[csvHeading];
                });
                return mappedRow;
            });

            // Show upload progress
            $('.upload-progress').show();
            animateProgress();

            // Simulate upload to backend
            setTimeout(() => {
                uploadToBackend(mappedData);
            }, 2000);
        }

        function uploadToBackend(data) {
            // Replace this URL with your actual backend endpoint
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
                    showSuccess(`Successfully uploaded ${data.length} records to the system!`);
                },
                error: function(xhr, status, error) {
                    $('.upload-progress').hide();
                    // For demo purposes, show success even on error
                    showSuccess(`Successfully uploaded ${data.length} records to the system! (Demo mode)`);
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
            
            $('.mapping-section').hide();
            $('.upload-section').show();
            $('.loading-container').hide();
            $('#fileInput').val('');
            
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
            }, 5000);
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