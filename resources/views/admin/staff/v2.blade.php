@extends('layouts.admin')

@section('title')
    Staffs
@endsection

@section('styles')
    <style>
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header h1 {
            color: #333;
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .controls {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
            margin-right: auto;
            margin-left: auto;
        }

        .search-container {
            position: relative;
        }

        .search-input {
            padding: 12px 40px 12px 16px;
            border: 2px solid #e1e8ed;
            border-radius: 25px;
            width: 300px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .search-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
        }

        .items-per-page {
            padding: 12px 16px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 16px;
            background: white;
        }

        .create-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .create-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .staff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .staff-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            position: relative;
            border: 1px solid #f0f0f0;
        }

        .staff-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .delete-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #ff4757;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.3s ease;
            opacity: 0.7;
            z-index: 9;
        }

        .delete-btn:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        .staff-image-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
        }

        .staff-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #667eea;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .staff-image:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .initials-image {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            font-weight: bold;
            border: 4px solid #667eea;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .initials-image:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .staff-info {
            text-align: center;
        }

        .staff-name {
            font-size: 1.4rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .staff-department {
            color: #666;
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .staff-actions {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .view-btn, .edit-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 6px;
            flex: 1;
            justify-content: center;
            min-width: 80px;
        }

        .view-btn {
            background: linear-gradient(135deg, #5cb85c, #449d44);
        }

        .view-btn:hover, .edit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(102, 126, 234, 0.3);
        }

        .view-btn:hover {
            box-shadow: 0 8px 15px rgba(92, 184, 92, 0.3);
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 30px;
            border-radius: 16px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            animation: modalSlide 0.3s ease;
        }

        @keyframes modalSlide {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #667eea;
        }

        .modal-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .btn-primary, .btn-secondary {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #f8f9fa;
            color: #666;
            border: 2px solid #e1e8ed;
        }

        .btn-secondary:hover {
            background: #e9ecef;
        }

        .image-preview-modal .modal-content {
            max-width: 600px;
            text-align: center;
        }

        .preview-image {
            max-width: 100%;
            max-height: 400px;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .image-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-top: 20px;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #666;
        }

        .no-results {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-results i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: stretch;
            }

            .controls {
                justify-content: center;
            }

            .search-input {
                width: 250px;
            }

            .staff-grid {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Staffs!</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Staffs</li>
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
                            {{-- <h1><i class="fas fa-users"></i> Staff Management</h1> --}}
                            <div class="controls">
                                <div class="search-container">
                                    <input type="text" class="search-input" placeholder="Search staff members..." id="searchInput">
                                    <i class="fas fa-search search-icon"></i>
                                </div>
                                <select class="items-per-page" id="departmentFilter">
                                    <option value="">All Departments</option>
                                    <option value="Human Resources">Human Resources</option>
                                    <option value="Engineering">Engineering</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Operations">Operations</option>
                                    <option value="Customer Support">Customer Support</option>
                                </select>
                                <select class="items-per-page" id="itemsPerPage">
                                    <option value="6">6 per page</option>
                                    <option value="12">12 per page</option>
                                    <option value="24">24 per page</option>
                                    <option value="50">50 per page</option>
                                </select>
                                <button class="create-btn" id="createStaffBtn">
                                    <i class="fas fa-plus"></i> Add Staff
                                </button>
                            </div>
                        </div>

                        <div class="staff-grid" id="staffGrid">
                            <!-- Staff cards will be populated here -->
                        </div>

                        <div class="loading" id="loading" style="display: none;">
                            <i class="fas fa-spinner fa-spin"></i> Loading more staff...
                        </div>

                        <div class="no-results" id="noResults" style="display: none;">
                            <i class="fas fa-search"></i>
                            <h3>No staff members found</h3>
                            <p>Try adjusting your search criteria</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 <!-- Staff Modal -->
    <div id="staffModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title" id="modalTitle">Add New Staff</h2>
                <span class="close" id="closeModal">&times;</span>
            </div>
            <form id="staffForm">
                <div class="form-group">
                    <label for="staffName">Full Name</label>
                    <input type="text" id="staffName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="staffDepartment">Department</label>
                    <select id="staffDepartment" name="department" required>
                        <option value="">Select Department</option>
                        <option value="Human Resources">Human Resources</option>
                        <option value="Engineering">Engineering</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Sales">Sales</option>
                        <option value="Finance">Finance</option>
                        <option value="Operations">Operations</option>
                        <option value="Customer Support">Customer Support</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="staffEmail">Email</label>
                    <input type="email" id="staffEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="staffPhone">Phone</label>
                    <input type="tel" id="staffPhone" name="phone">
                </div>
                <div class="form-group">
                    <label for="staffImage">Profile Image</label>
                    <input type="file" id="staffImage" name="image" accept="image/*">
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn-secondary" id="cancelBtn">Cancel</button>
                    <button type="submit" class="btn-primary" id="saveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Staff Modal -->
    <div id="viewStaffModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Staff Details</h2>
                <span class="close" id="closeViewModal">&times;</span>
            </div>
            <div id="viewStaffContent">
                <div style="text-align: center; margin-bottom: 25px;">
                    <div id="viewStaffImageContainer"></div>
                    <h3 id="viewStaffName" style="margin: 15px 0 5px 0; color: #333;"></h3>
                    <p id="viewStaffDepartment" style="color: #667eea; font-weight: 600; margin: 0;"></p>
                </div>
                <div style="background: #f8f9fa; padding: 20px; border-radius: 12px;">
                    <div style="display: grid; gap: 15px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-envelope" style="color: #667eea; width: 20px;"></i>
                            <span id="viewStaffEmail"></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-phone" style="color: #667eea; width: 20px;"></i>
                            <span id="viewStaffPhone"></span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="fas fa-building" style="color: #667eea; width: 20px;"></i>
                            <span id="viewStaffDepartmentFull"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn-primary" id="editFromViewBtn">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <button type="button" class="btn-secondary" id="closeViewBtn">Close</button>
            </div>
        </div>
    </div>
    <div id="imagePreviewModal" class="modal image-preview-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Profile Image</h2>
                <span class="close" id="closeImageModal">&times;</span>
            </div>
            <img id="previewImage" class="preview-image" src="" alt="Staff Image">
            <div class="image-actions">
                <button class="btn-secondary" id="editImageBtn">
                    <i class="fas fa-edit"></i> Change Image
                </button>
            </div>
        </div>
    </div>

    <!-- Hidden file input for image editing -->
    <input type="file" id="hiddenImageInput" accept="image/*" style="display: none;">

@endsection

@section('scripts')  
    <script>
        class StaffManager {
            constructor() {
                this.staff = [];
                this.filteredStaff = [];
                this.currentPage = 0;
                this.itemsPerPage = 6;
                this.editingStaff = null;
                this.currentImageTarget = null;
                
                this.initializeData();
                this.bindEvents();
                this.renderStaff();
            }

            initializeData() {
                // Sample data with some images
                this.staff = [
                    { id: 1, name: "John Doe", department: "Engineering", email: "john@company.com", phone: "+1-234-567-8901", image: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&h=150&fit=crop&crop=face" },
                    { id: 2, name: "Jane Smith", department: "Marketing", email: "jane@company.com", phone: "+1-234-567-8902", image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face" },
                    { id: 3, name: "Mike Johnson", department: "Sales", email: "mike@company.com", phone: "+1-234-567-8903", image: null },
                    { id: 4, name: "Sarah Wilson", department: "Human Resources", email: "sarah@company.com", phone: "+1-234-567-8904", image: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&h=150&fit=crop&crop=face" },
                    { id: 5, name: "David Brown", department: "Finance", email: "david@company.com", phone: "+1-234-567-8905", image: null },
                    { id: 6, name: "Lisa Davis", department: "Operations", email: "lisa@company.com", phone: "+1-234-567-8906", image: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&h=150&fit=crop&crop=face" },
                    { id: 7, name: "Tom Anderson", department: "Customer Support", email: "tom@company.com", phone: "+1-234-567-8907", image: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&h=150&fit=crop&crop=face" },
                    { id: 8, name: "Emma Thompson", department: "Engineering", email: "emma@company.com", phone: "+1-234-567-8908", image: null },
                    { id: 9, name: "Robert Garcia", department: "Marketing", email: "robert@company.com", phone: "+1-234-567-8909", image: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&h=150&fit=crop&crop=face" },
                    { id: 10, name: "Maria Rodriguez", department: "Human Resources", email: "maria@company.com", phone: "+1-234-567-8910", image: null },
                ];
                this.filteredStaff = [...this.staff];
            }

            bindEvents() {
                // Search functionality
                $('#searchInput').on('input', () => this.handleSearch());
                
                // Department filter
                $('#departmentFilter').on('change', () => this.handleSearch());
                
                // Items per page
                $('#itemsPerPage').on('change', () => this.handleItemsPerPageChange());
                
                // Create staff
                $('#createStaffBtn').on('click', () => this.openCreateModal());
                
                // Modal events
                $('#closeModal, #cancelBtn').on('click', () => this.closeModal());
                $('#staffForm').on('submit', (e) => this.handleFormSubmit(e));
                
                // View modal events
                $('#closeViewModal, #closeViewBtn').on('click', () => this.closeViewModal());
                $('#editFromViewBtn').on('click', () => this.editFromView());
                
                // Image preview modal
                $('#closeImageModal').on('click', () => this.closeImagePreviewModal());
                $('#editImageBtn').on('click', () => this.openImageSelector());
                $('#hiddenImageInput').on('change', (e) => this.handleImageChange(e));
                
                // Close modals when clicking outside
                $(window).on('click', (e) => {
                    if (e.target.id === 'staffModal') this.closeModal();
                    if (e.target.id === 'imagePreviewModal') this.closeImagePreviewModal();
                    if (e.target.id === 'viewStaffModal') this.closeViewModal();
                });

                // Infinite scroll
                $(window).on('scroll', () => this.handleScroll());
            }

            handleSearch() {
                const query = $('#searchInput').val().toLowerCase();
                const departmentFilter = $('#departmentFilter').val();
                
                this.filteredStaff = this.staff.filter(staff => {
                    const matchesSearch = staff.name.toLowerCase().includes(query) || 
                                        staff.department.toLowerCase().includes(query);
                    const matchesDepartment = !departmentFilter || staff.department === departmentFilter;
                    return matchesSearch && matchesDepartment;
                });
                
                this.currentPage = 0;
                this.renderStaff();
            }

            handleItemsPerPageChange() {
                this.itemsPerPage = parseInt($('#itemsPerPage').val());
                this.currentPage = 0;
                this.renderStaff();
            }

            handleScroll() {
                if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100) {
                    this.loadMoreStaff();
                }
            }

            loadMoreStaff() {
                const startIndex = this.currentPage * this.itemsPerPage;
                const endIndex = startIndex + this.itemsPerPage;
                
                if (startIndex < this.filteredStaff.length) {
                    $('#loading').show();
                    setTimeout(() => {
                        this.currentPage++;
                        this.renderStaffPage();
                        $('#loading').hide();
                    }, 500);
                }
            }

            renderStaff() {
                $('#staffGrid').empty();
                this.currentPage = 0;
                this.renderStaffPage();
            }

            renderStaffPage() {
                const startIndex = this.currentPage * this.itemsPerPage;
                const endIndex = startIndex + this.itemsPerPage;
                const staffToShow = this.filteredStaff.slice(startIndex, endIndex);

                if (this.currentPage === 0 && staffToShow.length === 0) {
                    $('#noResults').show();
                    return;
                } else {
                    $('#noResults').hide();
                }

                staffToShow.forEach(staff => {
                    const card = this.createStaffCard(staff);
                    $('#staffGrid').append(card);
                });
            }

            createStaffCard(staff) {
                const initials = this.getInitials(staff.name);
                const imageHtml = staff.image 
                    ? `<img class="staff-image" src="${staff.image}" alt="${staff.name}" data-staff-id="${staff.id}">` 
                    : `<div class="initials-image" data-staff-id="${staff.id}">${initials}</div>`;

                return `
                    <div class="staff-card" data-staff-id="${staff.id}">
                        <button class="delete-btn" data-staff-id="${staff.id}">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="staff-image-container">
                            ${imageHtml}
                        </div>
                        <div class="staff-info">
                            <div class="staff-name">${staff.name}</div>
                            <div class="staff-department">${staff.department}</div>
                            <div class="staff-actions">
                                <button class="view-btn" data-staff-id="${staff.id}">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="edit-btn" data-staff-id="${staff.id}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }

            getInitials(name) {
                return name.split(' ').map(n => n[0]).join('').toUpperCase();
            }

            openCreateModal() {
                this.editingStaff = null;
                $('#modalTitle').text('Add New Staff');
                $('#staffForm')[0].reset();
                $('#staffModal').show();
            }

            openEditModal(staffId) {
                this.editingStaff = this.staff.find(s => s.id === staffId);
                if (!this.editingStaff) return;

                $('#modalTitle').text('Edit Staff');
                $('#staffName').val(this.editingStaff.name);
                $('#staffDepartment').val(this.editingStaff.department);
                $('#staffEmail').val(this.editingStaff.email);
                $('#staffPhone').val(this.editingStaff.phone);
                $('#staffModal').show();
            }

            openViewModal(staffId) {
                const staff = this.staff.find(s => s.id === staffId);
                if (!staff) return;

                // Set staff image
                const initials = this.getInitials(staff.name);
                const imageHtml = staff.image 
                    ? `<img class="staff-image" src="${staff.image}" alt="${staff.name}" style="width: 120px; height: 120px;">` 
                    : `<div class="initials-image" style="width: 120px; height: 120px; font-size: 36px;">${initials}</div>`;
                
                $('#viewStaffImageContainer').html(imageHtml);
                $('#viewStaffName').text(staff.name);
                $('#viewStaffDepartment').text(staff.department);
                $('#viewStaffEmail').text(staff.email);
                $('#viewStaffPhone').text(staff.phone || 'N/A');
                $('#viewStaffDepartmentFull').text(staff.department);
                
                // Store current staff for edit button
                this.editingStaff = staff;
                
                $('#viewStaffModal').show();
            }

            closeViewModal() {
                $('#viewStaffModal').hide();
            }

            closeModal() {
                $('#staffModal').hide();
                this.editingStaff = null;
            }

            handleFormSubmit(e) {
                e.preventDefault();
                
                const formData = {
                    name: $('#staffName').val(),
                    department: $('#staffDepartment').val(),
                    email: $('#staffEmail').val(),
                    phone: $('#staffPhone').val()
                };

                const imageFile = $('#staffImage')[0].files[0];
                if (imageFile) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        formData.image = e.target.result;
                        this.saveStaff(formData);
                    };
                    reader.readAsDataURL(imageFile);
                } else {
                    if (this.editingStaff) {
                        formData.image = this.editingStaff.image;
                    }
                    this.saveStaff(formData);
                }
            }

            saveStaff(formData) {
                if (this.editingStaff) {
                    // Update existing staff
                    Object.assign(this.editingStaff, formData);
                } else {
                    // Create new staff
                    const newStaff = {
                        id: Date.now(),
                        ...formData
                    };
                    this.staff.push(newStaff);
                }

                this.handleSearch(); // Refresh the filtered list
                this.closeModal();

                swal("Success!", this.editingStaff ? "Staff updated successfully!" : "Staff created successfully!", "success");
            }

            deleteStaff(staffId) {
                const staff = this.staff.find(s => s.id === staffId);
                if (!staff) return;

                swal({
                    title: "Are you sure?",
                    text: `Do you want to delete ${staff.name}?`,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        this.staff = this.staff.filter(s => s.id !== staffId);
                        this.handleSearch(); // Refresh the filtered list
                        swal("Deleted!", "Staff member has been deleted.", "success");
                    }
                });
            }

            openImagePreview(staffId) {
                const staff = this.staff.find(s => s.id === staffId);
                if (!staff) return;

                this.currentImageTarget = staff;

                if (staff.image) {
                    $('#previewImage').attr('src', staff.image);
                    $('#imagePreviewModal').show();
                } else {
                    this.openImageSelector();
                }
            }

            closeImagePreviewModal() {
                $('#imagePreviewModal').hide();
                this.currentImageTarget = null;
            }

            openImageSelector() {
                $('#hiddenImageInput').click();
            }

            handleImageChange(e) {
                const file = e.target.files[0];
                if (!file || !this.currentImageTarget) return;

                const reader = new FileReader();
                reader.onload = (e) => {
                    this.currentImageTarget.image = e.target.result;
                    this.handleSearch(); // Refresh the display
                    this.closeImagePreviewModal();
                    swal("Success!", "Profile image updated successfully!", "success");
                };
                reader.readAsDataURL(file);
            }
        }

        // Initialize the application
        $(document).ready(() => {
            const staffManager = new StaffManager();

            // Event delegation for dynamically created elements
            $(document).on('click', '.view-btn', function(e) {
                e.stopPropagation();
                const staffId = parseInt($(this).data('staff-id'));
                staffManager.openViewModal(staffId);
            });

            $(document).on('click', '.edit-btn', function(e) {
                e.stopPropagation();
                const staffId = parseInt($(this).data('staff-id'));
                staffManager.openEditModal(staffId);
            });

            $(document).on('click', '.delete-btn', function(e) {
                e.stopPropagation();
                const staffId = parseInt($(this).data('staff-id'));
                staffManager.deleteStaff(staffId);
            });

            $(document).on('click', '.staff-image, .initials-image', function(e) {
                e.stopPropagation();
                const staffId = parseInt($(this).data('staff-id'));
                staffManager.openImagePreview(staffId);
            });
        });
    </script>
@endsection