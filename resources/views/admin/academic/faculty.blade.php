@extends('layouts.admin')

@section('title')
Faculty & Batch
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
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            background: #d1d3f0;
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
            padding: 0.5rem;
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
    </style>
@endsection

@section('content')
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-7 align-self-center">
                <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Faculty & Batches!</h3>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb m-0 p-0">
                            <li class="breadcrumb-item">
                                <a href="{{route('dashboard')}}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item text-muted active" aria-current="page">Faculty & Batches</li>
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
                    <!-- Add Faculty Button centered -->
                    <button class="btn btn-primary mx-auto" onclick="addFaculty()">
                        <i class="fas fa-plus me-2"></i>
                        Add Faculty
                    </button>

                    <!-- Search bar aligned to the right -->
                    <div class="d-flex align-items-center">
                        <input type="text" id="facultySearch" class="form-control" placeholder="Search Faculty" onkeyup="searchFaculty()" style="width: 200px;">
                        <i class="fas fa-search ms-2"></i>
                    </div>
                </div>
                {{-- <div class="d-flex justify-content-between align-items-center mb-4">
                    <button class="btn btn-primary" onclick="addFaculty()">
                        <i class="fas fa-plus me-2"></i>
                        Add Faculty
                    </button>
                </div> --}}

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
    let facultyDataa = '<?= json_encode($data); ?>'
    let facultyData = JSON.parse(facultyDataa);
    // Initialize the UI
    $(document).ready(function() {
        renderFacultyData();
        setupEventListeners();
    });

    function renderFacultyData() {
        const container = $('#faculty-container');
        container.empty();

        facultyData.forEach(faculty => {
            const facultyHtml = createFacultyHtml(faculty);
            container.append(facultyHtml);
        });
    }
    function searchFaculty() {
        const searchQuery = document.getElementById("facultySearch").value.toLowerCase();
        const facultyCards = document.querySelectorAll(".faculty-card");

        facultyCards.forEach(card => {
            const title = card.getAttribute("data-title").toLowerCase();
            if (title.includes(searchQuery)) {
                card.style.display = "";  // Show the card if it matches the search query
            } else {
                card.style.display = "none";  // Hide the card if it doesn't match
            }
        });
    }

    function createFacultyHtml(faculty) {
        return `
            <div class="col-md-6 card faculty-card" data-title="${faculty.title}" data-id="${faculty.id}" data-element="faculty-${faculty.id}">
                <div class="card-header">
                    <div class="item-header">
                        <div class="d-flex align-items-center">
                            <span class="level-indicator">FACULTY</span>
                            <input type="text" class="editable-title" value="${faculty.title}" 
                                    data-type="faculty" data-id="${faculty.id}" placeholder="Faculty Name">
                        </div>
                        <div class="item-actions">
                            <i class="fas fa-trash delete-btn" onclick="deleteCard(${faculty.id},'faculty')"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="batches-container">
                        ${faculty.batches.map(batch => createBatchHtml(batch)).join('')}
                    </div>
                    <div class="text-center mt-3">
                        <button class="add-button batch-add" data-id="${faculty.id}" onclick="addBatch(${faculty.id})">
                            <i class="fas fa-plus me-2"></i>Add Batch
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    function createBatchHtml(batch) {
        return `
            <div class="batch-item nested-item" data-id="${batch.id}" data-element="batch-${batch.id}">
                <div class="item-header">
                    <div class="d-flex align-items-center">
                        <span class="level-indicator">BATCH</span>
                        <input type="text" class="editable-title" value="${batch.title}" 
                                data-type="batch" data-id="${batch.id}" placeholder="Batch Name">
                    </div>
                    <div class="item-actions">
                        <i class="fas fa-trash delete-btn" onclick="deleteCard(${batch.id},'batch')"></i>
                    </div>
                </div>
                <div class="classes-container">
                    ${batch.classes.map(cls => createClassHtml(cls)).join('')}
                </div>
                <div class="text-center mt-3">
                    <button class="add-button class-add" data-id="${batch.id}" onclick="addClass(${batch.id})">
                        <i class="fas fa-plus me-2"></i>Add Class
                    </button>
                </div>
            </div>
        `;
    }

    function createClassHtml(cls) {
        return `
            <div class="class-item nested-item" data-id="${cls.id}" data-element="class-${cls.id}">
                <div class="item-header">
                    <div class="d-flex align-items-center">
                        <span class="level-indicator">CLASS</span>
                        <input type="text" class="editable-title" value="${cls.title}" 
                                data-type="class" data-id="${cls.id}" placeholder="Class Name">
                    </div>
                    <div class="item-actions">
                        <i class="fas fa-trash delete-btn" onclick="deleteCard(${cls.id},'class')"></i>
                    </div>
                </div>
                <div class="sections-container">
                    ${cls.sections.map(section => createSectionHtml(section)).join('')}
                </div>
                <div class="text-center mt-3">
                    <button class="add-button section-add" data-id="${cls.id}" onclick="addSection(${cls.id})">
                        <i class="fas fa-plus me-2"></i>Add Section
                    </button>
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
             
            // var closestElement = '';
            // (type == 'faculty') ? closestElement = '.faculty-card' : '';
            // console.log($this)
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
            case 'faculty':
                closestElement = '.faculty-card';
                const faculty = facultyData.find(f => f.id == id);
                if (faculty) faculty.title = newTitle;
                break;
            case 'batch':
                // closestElement = '.batch-item';
                facultyData.forEach(faculty => {
                    const batch = faculty.batches.find(b => b.id == id);
                    if (batch) batch.title = newTitle;
                });
                break;
            case 'class':
                // closestElement = '.class-item';
                facultyData.forEach(faculty => {
                    faculty.batches.forEach(batch => {
                        const cls = batch.classes.find(c => c.id == id);
                        if (cls) cls.title = newTitle;
                    });
                });
                break;
            case 'section':
                // closestElement = '.section-item';
                facultyData.forEach(faculty => {
                    faculty.batches.forEach(batch => {
                        batch.classes.forEach(cls => {
                            const section = cls.sections.find(s => s.id == id);
                            if (section) section.title = newTitle;
                        });
                    });
                });
                break;
        }
        element.closest(closestElement).attr('data-title',newTitle)
    }

    // Add functions
    function addFaculty() {
        const newId = Math.max(...facultyData.map(f => f.id), 0) + 1;
        const newFaculty = {
            id: newId,
            title: "New Faculty",
            user_id: 2,
            created_at: new Date().toISOString(),
            updated_at: new Date().toISOString(),
            batches: []
        };
        facultyData.unshift(newFaculty);
        renderFacultyData();
        addElementInServer(newFaculty,'faculty');
        console.log(`Added faculty with ID: ${newId}`);
    }

    function addBatch(facultyId) {
        const faculty = facultyData.find(f => f.id == facultyId);
        if (faculty) {
            const newId = Math.max(...getAllBatches().map(b => b.id), 0) + 1;
            const newBatch = {
                id: newId,
                title: "New Batch",
                faculty_id: facultyId,
                user_id: 2,
                created_at: new Date().toISOString(),
                updated_at: new Date().toISOString(),
                classes: []
            };
            faculty.batches.push(newBatch);
            renderFacultyData();
            addElementInServer(newBatch,'batch');
            console.log(`Added batch with ID: ${newId} to faculty ${facultyId}`);
        }
    }

    function addClass(batchId) {
        facultyData.forEach(faculty => {
            const batch = faculty.batches.find(b => b.id == batchId);
            if (batch) {
                const newId = Math.max(...getAllClasses().map(c => c.id), 0) + 1;
                const newClass = {
                    id: newId,
                    title: "New Class",
                    batch_id: batchId,
                    user_id: 2,
                    created_at: new Date().toISOString(),
                    updated_at: new Date().toISOString(),
                    sections: []
                };
                batch.classes.push(newClass);
                renderFacultyData();
                addElementInServer(newClass,'class');
                console.log(`Added class with ID: ${newId} to batch ${batchId}`);
            }
        });
    }

    function addSection(classId) {
        facultyData.forEach(faculty => {
            faculty.batches.forEach(batch => {
                const cls = batch.classes.find(c => c.id == classId);
                if (cls) {
                    const newId = Math.max(...getAllSections().map(s => s.id), 0) + 1;
                    const newSection = {
                        id: newId,
                        title: "New Section",
                        class_id: classId,
                        user_id: 2,
                        created_at: new Date().toISOString(),
                        updated_at: new Date().toISOString()
                    };
                    cls.sections.push(newSection);
                    renderFacultyData();
                    addElementInServer(newSection,'section');
                    console.log(`Added section with ID: ${newId} to class ${classId}`);
                }
            });
        });
    }

    // Delete functions
    // function deleteCard(facultyId,type) {
    //     if (confirm('Are you sure you want to delete this '+type+' and all its data?')) {
    //         facultyData = facultyData.filter(f => f.id != facultyId);
    //         renderFacultyData();
    //         console.log(`Deleted faculty with ID: ${facultyId}`);
    //     }
    // }
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

    // Helper functions
    function getAllBatches() {
        return facultyData.reduce((acc, faculty) => acc.concat(faculty.batches), []);
    }

    function getAllClasses() {
        return getAllBatches().reduce((acc, batch) => acc.concat(batch.classes), []);
    }

    function getAllSections() {
        return getAllClasses().reduce((acc, cls) => acc.concat(cls.sections), []);
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
                    // console.log(oldElementId);
                    // console.log(newElementId);

                    // Search for the appropriate element in the DOM and update the attributes
                    if (elementType === 'faculty') {
                        const facultyCard = $(`[data-element="faculty-${oldElementId}"]`);
                        facultyCard
                            .attr('data-id', newElementId)
                            .attr('data-element', `faculty-${newElementId}`);
                        facultyCard.find('.delete-btn').attr('onclick', `deleteCard(${newElementId}, 'faculty')`);
                        facultyCard.find('.editable-title').attr('data-id', newElementId);
                        
                        // Update facultyData
                        facultyData = facultyData.map(faculty => {
                            if (faculty.id === oldElementId) {
                                faculty.id = newElementId;
                                // Update faculty data with newElement data
                                return { ...faculty, ...newElement };
                            }
                            return faculty;
                        });
                        // Update the "Add Batch" button for the faculty
                        $(`[data-id="${oldElementId}"].batch-add`)
                            .attr('data-id', newElementId)
                            .attr('onclick', `addBatch(${newElementId})`);
                    } else if (elementType === 'batch') {
                        const batchCard = $(`[data-element="batch-${oldElementId}"]`);
                        batchCard
                            .attr('data-id', newElementId)
                            .attr('data-element', `batch-${newElementId}`);
                        batchCard.find('.delete-btn').attr('onclick', `deleteCard(${newElementId}, 'batch')`);
                        batchCard.find('.editable-title').attr('data-id', newElementId);

                        // Update facultyData
                        facultyData.forEach(faculty => {
                            faculty.batches = faculty.batches.map(batch => {
                                if (batch.id === oldElementId) {
                                    batch.id = newElementId;
                                    // Update batch data with newElement data
                                    return { ...batch, ...newElement };
                                }
                                return batch;
                            });
                        });
                        // Update the "Add Class" button for the batch
                        $(`[data-id="${oldElementId}"].class-add`)
                            .attr('data-id', newElementId)
                            .attr('onclick', `addClass(${newElementId})`);
                    } else if (elementType === 'class') {
                        const classCard = $(`[data-element="class-${oldElementId}"]`);
                        classCard
                            .attr('data-id', newElementId)
                            .attr('data-element', `class-${newElementId}`);
                        classCard.find('.delete-btn').attr('onclick', `deleteCard(${newElementId}, 'class')`);
                        classCard.find('.editable-title').attr('data-id', newElementId);

                        // Update facultyData
                        facultyData.forEach(faculty => {
                            faculty.batches.forEach(batch => {
                                batch.classes = batch.classes.map(cls => {
                                    if (cls.id === oldElementId) {
                                        cls.id = newElementId;
                                        // Update class data with newElement data
                                        return { ...cls, ...newElement };
                                    }
                                    return cls;
                                });
                            });
                        });
                        // Update the "Add Section" button for the class
                        $(`[data-id="${oldElementId}"].section-add`)
                            .attr('data-id', newElementId)
                            .attr('onclick', `addSection(${newElementId})`);
                    } else if (elementType === 'section') {
                        const sectionCard = $(`[data-element="section-${oldElementId}"]`);
                        sectionCard
                            .attr('data-id', newElementId)
                            .attr('data-element', `section-${newElementId}`);
                        sectionCard.find('.delete-btn').attr('onclick', `deleteCard(${newElementId}, 'section')`);
                        sectionCard.find('.editable-title').attr('data-id', newElementId);

                        // Update facultyData
                        facultyData.forEach(faculty => {
                            faculty.batches.forEach(batch => {
                                batch.classes.forEach(cls => {
                                    cls.sections = cls.sections.map(section => {
                                        if (section.id === oldElementId) {
                                            section.id = newElementId;
                                            // Update section data with newElement data
                                            return { ...section, ...newElement };
                                        }
                                        return section;
                                    });
                                });
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
</script>    
@endsection