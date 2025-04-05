<?php

require_once '../gobrikconn_env.php'; // Include the database connection

// Function to rotate an image by the specified degrees and save it back
function rotateEcobrickPhoto($sourcePath, $rotationDegrees, $targetPath = null) {
    error_log("Starting photo rotation for file: $sourcePath. Rotation degrees: $rotationDegrees");

    list($width, $height, $type) = getimagesize($sourcePath);
    if (!$width || !$height || !$type) {
        error_log("Failed to get image details for file: $sourcePath");
        return false;
    }

    switch ($type) {
        case IMAGETYPE_JPEG:
            $sourceImage = imagecreatefromjpeg($sourcePath);
            break;
        case IMAGETYPE_PNG:
            $sourceImage = imagecreatefrompng($sourcePath);
            break;
        case IMAGETYPE_WEBP:
            $sourceImage = imagecreatefromwebp($sourcePath);
            break;
        default:
            error_log("Unsupported image type for file: $sourcePath");
            return false;
    }

    if (!$sourceImage) {
        error_log("Failed to create image resource for file: $sourcePath");
        return false;
    }

    $rotatedImage = imagerotate($sourceImage, -$rotationDegrees, 0);
    if (!$rotatedImage) {
        error_log("Failed to rotate image for file: $sourcePath");
        return false;
    }

    if ($targetPath === null) {
        $targetPath = $sourcePath;
    }

    switch ($type) {
        case IMAGETYPE_JPEG:
            $success = imagejpeg($rotatedImage, $targetPath, 90);
            break;
        case IMAGETYPE_PNG:
            $success = imagepng($rotatedImage, $targetPath);
            break;
        case IMAGETYPE_WEBP:
            $success = imagewebp($rotatedImage, $targetPath);
            break;
    }

    if ($success) {
        error_log("Image successfully rotated and saved to: $targetPath");
    } else {
        error_log("Failed to save rotated image to: $targetPath");
        return false;
    }

    imagedestroy($sourceImage);
    imagedestroy($rotatedImage);

    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $photoUrl = $_POST['photo_url'] ?? '';
    $thumbUrl = $_POST['thumb_url'] ?? '';
    $rotationDegrees = $_POST['rotation'] ?? 0;

    if (!empty($photoUrl) && $rotationDegrees) {
        $mainPhotoSuccess = rotateEcobrickPhoto($photoUrl, $rotationDegrees);
        $thumbPhotoSuccess = true;

        if (!empty($thumbUrl)) {
            $thumbPhotoSuccess = rotateEcobrickPhoto($thumbUrl, $rotationDegrees);
        }

        if ($mainPhotoSuccess && $thumbPhotoSuccess) {
            // Increment the photo_version in the database
            try {
                $stmt = $gobrik_conn->prepare("UPDATE tb_ecobricks SET photo_version = photo_version + 1 WHERE ecobrick_full_photo_url = ?");
                if (!$stmt) {
                    throw new Exception("Failed to prepare statement: " . $gobrik_conn->error);
                }
                $stmt->bind_param("s", $photoUrl);

                if ($stmt->execute()) {
                    echo "Image and thumbnail rotated successfully. Photo version updated.";
                } else {
                    throw new Exception("Failed to execute statement: " . $stmt->error);
                }

                $stmt->close();
            } catch (Exception $e) {
                error_log($e->getMessage());
                echo "Image rotation successful, but failed to update photo version.";
            }
        } else {
            echo "Failed to rotate the image or thumbnail.";
        }
    } else {
        echo "Invalid request data.";
    }
} else {
    echo "Invalid request method.";
}

?>
