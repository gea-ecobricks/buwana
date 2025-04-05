

// ROTATE Photo

// SECTION 1: Function to send rotation request to the PHP function
function rotateEcobrickPhoto(photoUrl, thumbUrl, rotationDegrees, photoId, totalRotationDegrees) {
    // Create an AJAX request to send the rotation degrees to the server
    var xhr = new XMLHttpRequest();
    var url = "../scripts/rotate_photo.php"; // PHP file that handles the photo rotation
    var params = "photo_url=" + encodeURIComponent(photoUrl) +
                 "&thumb_url=" + encodeURIComponent(thumbUrl) +
                 "&rotation=" + rotationDegrees;

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Handle the server's response
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                console.log("Server response: " + xhr.responseText);

                // Check if the response contains a success message
                if (xhr.responseText.trim().includes("rotated successfully")) {
                    // Alert the user of the successful rotation
                    alert("Your photo has been rotated " + totalRotationDegrees + " degrees clockwise and saved to the server.");
                    console.log("Image rotation successful for: " + photoUrl);

                    // SECTION 2: Preserve the current rotation after confirmation
                    // Do not reset the image to 0 degrees after confirmation.
                    // The image will stay at its current rotation.

                } else {
                    // Handle error response from the server
                    alert("Something went wrong saving your rotation. Error: " + xhr.responseText);
                }
            } else {
                // Handle the error if the request was unsuccessful
                alert("An error occurred. Status: " + xhr.status);
            }
        }
    };


    // Send the rotation degrees to the server
    xhr.send(params);
}

// SECTION 3: Function to adjust the height of the container after the image rotates
function adjustContainerHeight(photo, container) {
    var currentRotation = parseInt(photo.getAttribute('data-rotation')) || 0;

    // Adjust height when the image is rotated by 90 or 270 degrees
    if (currentRotation % 180 !== 0) {
        var newHeight = photo.width;
        container.style.height = newHeight + 'px';
    } else {
        // Set container height to auto when image is not rotated (0 or 180 degrees)
        container.style.height = 'auto';
    }
}

// SECTION 4: Function to handle the rotate button clicks
document.querySelectorAll('.rotate-button').forEach(function(button) {
    button.addEventListener('click', function() {
        var photoContainer = this.closest('.photo-container');
        var photo = photoContainer.querySelector('.rotatable-photo');
        var confirmButton = photoContainer.querySelector('.confirm-rotate-button');

        // Get the current rotation from the data attribute
        var currentRotation = parseInt(photo.getAttribute('data-rotation')) || 0;
        var direction = this.getAttribute('data-direction');

        // Rotate the image based on the direction
        if (direction === 'left') {
            currentRotation = (currentRotation - 90) % 360;
        } else if (direction === 'right') {
            currentRotation = (currentRotation + 90) % 360;
        }

        // Apply the rotation and update the data-rotation attribute
        photo.style.transform = 'rotate(' + currentRotation + 'deg)';
        photo.setAttribute('data-rotation', currentRotation);

        // Show the confirm button
        confirmButton.style.display = 'block';

        // Adjust the container height based on the new image rotation
        adjustContainerHeight(photo, photoContainer);
    });
});

// SECTION 5: Handle the confirmation button click to send the rotation to the server
document.querySelectorAll('.confirm-rotate-button').forEach(function(button) {
    button.addEventListener('click', function() {
        var photoContainer = this.closest('.photo-container');
        var photo = photoContainer.querySelector('.rotatable-photo');
        var currentRotation = parseInt(photo.getAttribute('data-rotation')) || 0;
        var photoUrl = this.previousElementSibling.getAttribute('data-photo-url'); // Get the original photo URL from the rotate button
        var thumbUrl = this.getAttribute('data-thumb-url'); // Get the thumbnail URL from the confirm button

        // Calculate total clockwise rotation (normalize it to 0-360)
        var totalRotationDegrees = (currentRotation + 360) % 360;

        // Trigger the PHP function to rotate the actual photo
        var photoId = photo.getAttribute('id'); // Assuming the photo ID corresponds to the ecobrick ID or serial_no
        rotateEcobrickPhoto(photoUrl, thumbUrl, currentRotation, photoId, totalRotationDegrees);
    });
});


