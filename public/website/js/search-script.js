$(document).ready(function() {
    // Mock data for demonstration
    const mockData = {
        '12345': {
            studentName: 'John Doe',
            symbolNumber: '12345',
            buildingName: 'Engineering Block A',
            roomNumber: 'Room 301',
            seatNumber: 'A-15',
            examDate: '2024-03-15',
            examTime: '10:00 AM - 1:00 PM',
            subject: 'Computer Science Fundamentals',
            floor: '3rd Floor',
            instructions: [
                'Bring your admit card',
                'Arrive 30 minutes early',
                'No electronic devices allowed'
            ]
        },
        '67890': {
            studentName: 'Jane Smith',
            symbolNumber: '67890',
            buildingName: 'Science Block B',
            roomNumber: 'Room 205',
            seatNumber: 'B-08',
            examDate: '2024-03-16',
            examTime: '2:00 PM - 5:00 PM',
            subject: 'Mathematics Advanced',
            floor: '2nd Floor',
            instructions: [
                'Bring calculator',
                'Arrive 30 minutes early',
                'No mobile phones'
            ]
        }
    };

    // Search form submission
    $('#searchForm').submit(function(e) {
        e.preventDefault();
        
        const symbolNumber = $('#symbolNumber').val().trim();
        
        if (!symbolNumber) {
            showToast('Please enter a valid symbol number', 'error');
            return;
        }

        const $searchBtn = $('#searchBtn');
        const $btnText = $searchBtn.find('.btn-text');
        const $btnLoading = $searchBtn.find('.btn-loading');
        
        // Show loading state
        $searchBtn.prop('disabled', true);
        $btnText.hide();
        $btnLoading.show();
        
        // Hide previous results
        $('#resultCard').hide();
        
        // Simulate API call
        setTimeout(function() {
            const result = mockData[symbolNumber];
            
            // Hide loading state
            $searchBtn.prop('disabled', false);
            $btnText.show();
            $btnLoading.hide();
            
            if (result) {
                displaySearchResult(result);
                showToast('Your seat details have been found.', 'success');
            } else {
                showToast('No seat assignment found for this symbol number. Please check and try again.', 'error');
            }
        }, 1000);
    });

    // Display search results
    function displaySearchResult(result) {
        // Update result description
        $('#resultDescription').text(`Here are your exam details for symbol number: ${result.symbolNumber}`);
        
        // Update student information
        $('#studentName').text(result.studentName);
        $('#studentSymbol').text(result.symbolNumber);
        
        // Update location details
        $('#buildingName').text(result.buildingName);
        $('#roomNumber').text(result.roomNumber);
        $('#floorNumber').text(result.floor);
        $('#seatNumber').text(result.seatNumber);
        
        // Update exam details
        $('#examSubject').text(result.subject);
        $('#examDate').text(result.examDate);
        $('#examTime').text(result.examTime);
        
        // Update instructions
        const instructionsList = $('#instructionsList');
        instructionsList.empty();
        result.instructions.forEach(function(instruction) {
            instructionsList.append(`<li>${instruction}</li>`);
        });
        
        // Show result card with animation
        $('#resultCard').fadeIn(500);
        
        // Scroll to results
        $('html, body').animate({
            scrollTop: $('#resultCard').offset().top - 100
        }, 800);
    }

    // Print functionality
    $(document).on('click', '.btn:contains("Print")', function() {
        showToast('Print functionality would be implemented here', 'success');
    });

    // Clear form when input changes
    $('#symbolNumber').on('input', function() {
        $('#resultCard').hide();
    });

    // Auto-focus on symbol number input
    $('#symbolNumber').focus();

    // Enter key handling
    $('#symbolNumber').keypress(function(e) {
        if (e.which === 13) {
            $('#searchForm').submit();
        }
    });

    // Add input validation
    $('#symbolNumber').on('input', function() {
        const value = $(this).val();
        const numericValue = value.replace(/\D/g, '');
        $(this).val(numericValue);
    });

    // Placeholder animation
    let placeholderIndex = 0;
    const placeholders = [
        'Enter your symbol number (e.g., 12345)',
        'Try 12345 for demo',
        'Try 67890 for demo'
    ];
    
    setInterval(function() {
        if (!$('#symbolNumber').is(':focus') && $('#symbolNumber').val() === '') {
            placeholderIndex = (placeholderIndex + 1) % placeholders.length;
            $('#symbolNumber').attr('placeholder', placeholders[placeholderIndex]);
        }
    }, 3000);

    // Add hover effects to info items
    $('.info-item').hover(
        function() {
            $(this).addClass('hovered');
        },
        function() {
            $(this).removeClass('hovered');
        }
    );
});

// Toast notification function
function showToast(message, type = 'success') {
    const toast = $('#toast');
    const toastIcon = toast.find('.toast-icon');
    const toastMessage = toast.find('.toast-message');
    
    // Set toast content
    toastMessage.text(message);
    
    // Set toast type and icon
    toast.removeClass('success error');
    toast.addClass(type);
    
    if (type === 'success') {
        toastIcon.removeClass().addClass('toast-icon fas fa-check-circle');
    } else if (type === 'error') {
        toastIcon.removeClass().addClass('toast-icon fas fa-exclamation-circle');
    }
    
    // Show toast
    toast.addClass('show');
    
    // Hide toast after 4 seconds
    setTimeout(function() {
        toast.removeClass('show');
    }, 4000);
}

// Add additional styles for search page
const searchStyles = `
    .info-item.hovered {
        background: hsla(var(--primary), 0.05);
        padding: 0.5rem;
        border-radius: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .seat-badge {
        animation: glow 2s ease-in-out infinite alternate;
    }
    
    @keyframes glow {
        from {
            box-shadow: 0 0 5px hsla(var(--secondary), 0.5);
        }
        to {
            box-shadow: 0 0 20px hsla(var(--secondary), 0.8);
        }
    }
    
    .result-card {
        animation: slideIn 0.5s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;

// Inject search-specific styles
$('<style>').text(searchStyles).appendTo('head');