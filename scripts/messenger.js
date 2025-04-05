const maxFileSize = 10 * 1024 * 1024; // 10 MB

$(document).ready(function() {




    // Validate the uploaded file
    function validateFile(file) {
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        return validTypes.includes(file.type) && file.size <= maxFileSize;
    }

    // Handle file upload button click
    $('#uploadPhotoButton').on('click', function() {
        if (!$(this).hasClass('remove-attachment')) {
            $('#imageUploadInput').click(); // Trigger file input
        } else {
            resetUploadButton();
            $('#imageFileName').text(''); // Clear file name display
            $('#imageUploadInput').val(''); // Reset file input
        }
    });

    // Handle file selection
    $('#imageUploadInput').on('change', function(event) {
        const file = event.target.files[0];
        if (file && validateFile(file)) {
            $('#imageFileName').text(file.name); // Display the selected file name
            showUploadSuccess(); // Show success indicator
        } else {
            alert('🤔 Hmmm... looks like this isn\'t an image file, or else it\'s over 10MB. Please try another file.');
            resetUploadButton(); // Reset the button if the file is invalid
        }
    });

    // Show a success icon on the upload button and change the state to indicate an attachment is added
    function showUploadSuccess() {
        $('#uploadPhotoButton')
            .html('✔️') // Show a checkmark
            .css('background', 'var(--emblem-green)'); // Change background color to green

        // After a short delay, change the button to a paperclip icon and enable removal
        setTimeout(function() {
            $('#uploadPhotoButton')
                .html('📎') // Change to paperclip icon
                .css('background', 'grey') // Reset background color
                .addClass('attachment-added remove-attachment') // Update the class for removal
                .attr('title', 'Click to remove attachment'); // Update the tooltip
        }, 1000); // 1-second delay for user feedback
    }



    // Handle hover behavior for attachment removal state
    $('#uploadPhotoButton').hover(
        function() {
            // If the button has an attachment, show the "remove" icon on hover
            if ($(this).hasClass('remove-attachment')) {
                $(this).html('❌'); // Show the delete icon
            }
        },
        function() {
            // If the button has an attachment, revert back to the paperclip icon when not hovered
            if ($(this).hasClass('remove-attachment')) {
                $(this).html('📎'); // Revert to the paperclip icon
            }
        }
    );

});


//OPEN PHOTO MODAL:


// Function to open the modal with the full image
function openPhotoModal(imageUrl) {
    const modal = document.getElementById('form-modal-message');
    const contentBox = modal.querySelector('.modal-content-box');
    const photoBox = modal.querySelector('.modal-photo-box');
    const photoContainer = modal.querySelector('.modal-photo');
    const userId = '<?php echo $buwana_id; ?>'; // Get the user's ID from PHP

    // Hide the content box and show the photo box
    contentBox.style.display = 'none'; // Hide the content box
    photoBox.style.display = 'block'; // Make sure the photo box is visible

    // Clear previous images from the photo container
    photoContainer.innerHTML = '';

    // Create and append the image to the photo container
    const img = document.createElement('img');
    img.src = imageUrl;
    img.alt = "Full-size image preview";
    img.style.maxWidth = '90%';
    img.style.maxHeight = '75vh';
    img.style.minHeight = "400px";
    img.style.minWidth = "400px";
    img.style.margin = 'auto';
    photoContainer.appendChild(img);

    // Show the modal
    modal.style.display = 'flex';

    // Blur out background
    document.getElementById('page-content')?.classList.add('blurred');
    document.getElementById('footer-full')?.classList.add('blurred');
    document.body.classList.add('modal-open');
}


// Reset the upload button to its original state (camera icon)
function resetUploadButton() {
    $('#uploadPhotoButton')
        .html('📸') // Revert to the camera icon
        .css('background', '#434343') // Original background color
        .removeClass('attachment-added remove-attachment') // Remove added classes
        .attr('title', 'Upload Photo'); // Restore the original title

    // Clear the file input and displayed file name
    $('#imageFileName').text(''); // Clear any displayed file name
    $('#imageUploadInput').val(''); // Reset the file input
}