@extends('layouts.admin')

@section('title')
Class & Section
@endsection

@section('styles')
    <style>
        .card {
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            margin-bottom: 1rem;
            margin-right: auto;
            margin-left: auto;
            width: 100%;
        }
        .btn-primary{
            margin-right: auto;
            margin-left: auto;
        }
        .card-header {
            background: linear-gradient(to right, #8971ea, #7f72ea, #7574ea, #6a75e9, #5f76e8);
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            /* background: #d1d3f0; */
        }
        .class-item .card-header .level-indicator{
            color: white;
        }
        .nested-item {
            margin-left: 20px;
            border-left: 2px solid #e3e6f0;
            padding-left: 15px;
            margin-bottom: 0.5rem;
        }
        .batch-item {
            background-color: #f8f9fc;
            border-radius: 0.25rem;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            margin-top: 1rem;
        }
        .class-item {
            background-color: #ffffff;
            border: 1px solid #e3e6f0;
            border-radius: 0.25rem;
            /* padding: 0.5rem; */
            margin-bottom: 0.5rem;
        }
        .section-item {
            background-color: #f1f3f4;
            border-radius: 0.25rem;
            padding: 0.5rem;
            margin-bottom: 0.25rem;
        }
        .editable-title {
            background: none;
            border: none;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            padding: 0.25rem;
            border-radius: 0.25rem;
        }
        .editable-title:focus {
            outline: 2px solid #4e73df;
            background-color: white;
        }
        .add-button {
            border: 2px dashed #6c757d;
            background: none;
            color: #6c757d;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: block;
            margin: 0 auto;
            text-align: center;
        }
        .add-button:hover {
            border-color: #4e73df;
            color: #4e73df;
        }
        .delete-btn {
            color: #e74a3b;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 0.25rem;
            transition: all 0.3s ease;
        }
        .delete-btn:hover {
            background-color: #e74a3b;
            color: white;
        }
        .item-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        .item-actions {
            display: flex;
            gap: 0.5rem;
        }
        .level-indicator {
            font-size: 0.75rem;
            color: #6c757d;
            margin-right: 0.5rem;
        }
        .search-div{
            background: linear-gradient(to right, #8971ea, #7f72ea, #7574ea, #6a75e9, #5f76e8);
            padding: 5px 10px 5px 5px;
            border-radius: 5px;
        }
        .search-div input{
            background: #ffffff;
        }
        .search-div input:focus{
            background: #ffffff;
        }
        .search-div i{
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Class & Sections!</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Class & Sections</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12 col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <button class="btn btn-primary" onclick="addClass()">
                        <i class="fas fa-plus me-2"></i>
                        Add Class
                    </button>
                    <!-- Search bar aligned to the right -->
                    <div class="d-flex align-items-center search-div">
                        <input type="text" id="facultySearch" class="form-control" placeholder="Search Class" onkeyup="searchFaculty()" style="width: 200px;">
                        <i class="fas fa-search ms-2"></i>
                    </div>
                </div>

                <!-- Faculty Container -->
                <div id="faculty-container">
                    <!-- Faculty items will be dynamically loaded here -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // $(document).ready(function () {
        // Sample data structure
        let classDataa = '<?= json_encode($data); ?>'
        let classData = JSON.parse(classDataa);

        // Initialize the UI
        $(document).ready(function() {
            renderClassData();
            setupEventListeners();
        });

        function renderClassData() {
            const container = $('#faculty-container');
            container.empty();

            classData.forEach(classData => {
                const classHtml = createClassHtml(classData);
                container.append(classHtml);
            });
        }
        function searchFaculty() {
            const searchQuery = document.getElementById("facultySearch").value.toLowerCase();
            const facultyCards = document.querySelectorAll(".class-item");

            facultyCards.forEach(card => {
                const title = card.getAttribute("data-title").toLowerCase();
                if (title.includes(searchQuery)) {
                    card.style.display = "";  // Show the card if it matches the search query
                } else {
                    card.style.display = "none";  // Hide the card if it doesn't match
                }
            });
        }

        function createClassHtml(classData) {
            console.log(classData)
            return `
                <div class="col-md-6 card class-item" data-title="${classData.title}"  data-id="${classData.id}" data-element="class-${classData.id}">
                    <div class="card-header">
                        <div class="item-header">
                            <div class="d-flex align-items-center">
                                <span class="level-indicator">Class</span>
                                <input type="text" class="editable-title" value="${classData.title}" 
                                        data-type="class" data-id="${classData.id}" placeholder="Class Name">
                            </div>
                            <div class="item-actions">
                                <i class="fas fa-trash delete-btn" onclick="deleteCard(${classData.id},'class')"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="batches-container">
                            ${classData.sections.map(section => createSectionHtml(section)).join('')}
                        </div>
                        <div class="text-center mt-3">
                            <button class="add-button section-add" data-id="${classData.id}" onclick="addSection(${classData.id})">
                                <i class="fas fa-plus me-2"></i>Add Section
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        function createSectionHtml(section) {
            return `
                <div class="section-item nested-item" data-id="${section.id}" data-element="section-${section.id}">
                    <div class="item-header">
                        <div class="d-flex align-items-center">
                            <span class="level-indicator">SECTION</span>
                            <input type="text" class="editable-title" value="${section.title}" 
                                   data-type="section" data-id="${section.id}" placeholder="Section Name">
                        </div>
                        <div class="item-actions">
                            <i class="fas fa-trash delete-btn" onclick="deleteCard(${section.id},'section')"></i>
                        </div>
                    </div>
                </div>
            `;
        }

        function setupEventListeners() {
            // Title change event
            $(document).on('blur', '.editable-title', function() {
                const $this = $(this);
                const type = $this.data('type');
                const id = $this.data('id');
                const newTitle = $this.val();
                
                console.log(`Title changed: ${type} (ID: ${id}) -> "${newTitle}"`);
                
                // Update the data structure
                updateTitleInData($this, type, id, newTitle);
                
                // Here you would typically make an AJAX call to update the server
                updateTitleOnServer(type, id, newTitle);
            });

            // Enter key handling for title editing
            $(document).on('keypress', '.editable-title', function(e) {
                if (e.which === 13) { // Enter key
                    $(this).blur();
                }
            });
        }

        function updateTitleInData(element, type, id, newTitle) {
            var closestElement = '';
            switch(type) {
                case 'class':
                    closestElement = '.class-item';
                    const classItem = classData.find(f => f.id == id);
                    if (classItem) classItem.title = newTitle;
                    break;
                case 'section':
                    closestElement = '.section-item';
                    classData.forEach(classItem => {
                        const section = classItem.sections.find(b => b.id == id);
                        if (section) section.title = newTitle;
                    });
                    break;
            }
            element.closest(closestElement).attr('data-title',newTitle)
        }

        // Add functions
        function addClass() {
            const newId = Math.max(...classData.map(f => f.id), 0) + 1;
            const newClass = {
                id: newId,
                title: "New Class",
                user_id: 2,
                created_at: new Date().toISOString(),
                updated_at: new Date().toISOString(),
                sections: []
            };
            classData.unshift(newClass);
            renderClassData();
            addElementInServer(newClass,'class');
            console.log(`Added class with ID: ${newId}`);
        }

        function addSection(classId) {
            const classItem = classData.find(f => f.id == classId);
                console.log('123')
            if (classItem) {
                console.log('object')
                const newId = Math.max(...getAllSections().map(b => b.id), 0) + 1;
                const newSection = {
                    id: newId,
                    title: "New Section",
                    class_id: classId,
                    user_id: 2,
                    created_at: new Date().toISOString(),
                    updated_at: new Date().toISOString(),
                    classes: []
                };
                classItem.sections.push(newSection);
                renderClassData();
                addElementInServer(newSection,'section');
                console.log(`Added Section with ID: ${newId} to class ${classId}`);
            }
        }

        function deleteCard(elementId, type) {
            Swal.fire({
                title: 'Are you sure?',
                text: `You are about to delete this ${type} and all its data! This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed to delete the element
                    deleteElement(elementId, type);
                }
            });
        }

        function deleteElement(elementId, type) {
            $.ajax({
                url: `/delete-element`,  // Controller URL for deleting elements
                method: 'POST',
                data: {
                    id: elementId,
                    type: type
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
                },
                success: function(response) {
                    if (response.success) {
                        // Remove the deleted element from the DOM
                        $(`[data-element="${type}-${elementId}"]`).remove();
                        toastr.success(`${type.charAt(0).toUpperCase() + type.slice(1)} deleted successfully!`);
                        // console.log(type);
                        // console.log(facultyData)

                        if (type === 'class') {
                            classData = classData.filter(f => f.id != elementId);
                            // classData.forEach(faculty => {
                            //     faculty.batches.forEach(batch => {
                            //         batch.classes = batch.classes.filter(c => c.id != elementId);
                            //     });
                            // });
                        } else if (type === 'section') {
                            classData.forEach(faculty => {
                                faculty.sections = faculty.sections.filter(b => b.id != elementId);
                            });
                            // classData.forEach(faculty => {
                            //     faculty.batches.forEach(batch => {
                            //         batch.classes.forEach(cls => {
                            //             cls.sections = cls.sections.filter(s => s.id != elementId);
                            //         });
                            //     });
                            // });
                        }
                        renderFacultyData();
                        // console.log(facultyData)
                        console.log(`${type} with ID: ${elementId} deleted successfully`);
                    } else {
                        toastr.error('Failed to delete the ' + type);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error(`Error deleting ${type}:`, error);
                    console.error(`Error deleting ${type}:`, error);
                }
            });
        }
        // Delete functions
        function deleteClass(classId) {
            if (confirm('Are you sure you want to delete this class and all its data?')) {
                classData = classData.filter(f => f.id != classId);
                renderClassData();
                console.log(`Deleted class with ID: ${classId}`);
            }
        }

        function deleteSection(batchId) {
            if (confirm('Are you sure you want to delete this section?')) {
                classData.forEach(classItem => {
                    classItem.sections = classItem.sections.filter(b => b.id != sectionId);
                });
                renderClassData();
                console.log(`Deleted batch with ID: ${sectionId}`);
            }
        }
        // Helper functions
        function getAllSections() {
            return classData.reduce((acc, classItem) => acc.concat(classItem.sections), []);
        }

        function addElementInServer(newElement, type) {
            console.log(newElement);
            $.ajax({
                url: `/add-element`,  // URL for the controller action
                method: 'POST',
                data: {
                    postData: newElement,
                    type: type
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Set the CSRF token header
                },
                success: function(response) {
                    if (response && response.data && response.type) {
                        const newElementId = response.data.id; // Get the new ID from the response
                        const elementType = response.type; // Get the type from the response
                        const oldElementId = newElement.id;
                        console.log(oldElementId);
                        console.log(newElementId);

                        // Search for the appropriate element in the DOM and update the attributes
                        if (elementType === 'class') {
                            const classCard = $(`[data-element="class-${oldElementId}"]`);
                            classCard
                                .attr('data-id', newElementId)
                            .attr('data-element', `class-${newElementId}`);
                            classCard.find('.delete-btn').attr('onclick', `deleteCard(${newElementId}, 'class')`);
                            classCard.find('.editable-title').attr('data-id', newElementId);

                            // Update facultyData
                            classData = classData.map(faculty => {
                                if (faculty.id === oldElementId) {
                                    faculty.id = newElementId;
                                    // Update faculty data with newElement data
                                    return { ...faculty, ...newElement };
                                }
                                return faculty;
                            });
                            // Update the "Add Section" button for the class
                            $(`[data-id="${oldElementId}"].section-add`)
                                .attr('data-id', newElementId)
                                .attr('onclick', `addSection(${newElementId})`);
                            // classData.forEach(faculty => {
                            //     faculty.batches.forEach(batch => {
                            //         batch.classes = batch.classes.map(cls => {
                            //             if (cls.id === oldElementId) {
                            //                 cls.id = newElementId;
                            //                 // Update class data with newElement data
                            //                 return { ...cls, ...newElement };
                            //             }
                            //             return cls;
                            //         });
                            //     });
                            // });
                        } else if (elementType === 'section') {
                            const sectionCard = $(`[data-element="section-${oldElementId}"]`);
                            sectionCard
                                .attr('data-id', newElementId)
                                .attr('data-element', `section-${newElementId}`);
                            sectionCard.find('.delete-btn').attr('onclick', `deleteCard(${newElementId}, 'section')`);
                            sectionCard.find('.editable-title').attr('data-id', newElementId);

                            // Update facultyData
                            classData.forEach(faculty => {
                                faculty.sections = faculty.sections.map(section => {
                                    if (section.id === oldElementId) {
                                        section.id = newElementId;
                                        // Update batch data with newElement data
                                        return { ...section, ...newElement };
                                    }
                                    return section;
                                });
                            });
                        }

                        console.log(`${elementType} added successfully!`);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(`Error adding ${type}:`, error);
                }
            });
        }

        // Function to simulate server updates (replace with actual AJAX calls)
        function updateTitleOnServer(type, id, newTitle) {
            $.ajax({
                url: `/change-title`,  // URL for the controller action
                method: 'PUT',
                data: {
                    type: type,  // Type (e.g., 'classes', 'sections')
                    id: id,      // ID of the record
                    title: newTitle  // New title
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Set the CSRF token header
                },
                success: function(response) {
                    // console.log(response)
                    console.log(`${type} title updated successfully with new ID: ${id}`);
                    // toastr.success(`${type} title updated successfully!`);  // Show success message with Toastr
                },
                error: function(xhr, status, error) {
                    // toastr.error(`Error updating ${type} title: ${error}`);  // Show error message with Toastr
                    console.error(`Error updating ${type} title:`, error);
                }
            });
        }
        // });
</script>    
@endsection