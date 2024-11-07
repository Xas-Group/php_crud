<?php
include 'db.php';

// Add Photo to Gallery
function addPhotoToGallery($gallery_id, $photo_id) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Gallery_Photos (gallery_id, photo_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $gallery_id, $photo_id);
    $stmt->execute();
    $stmt->close();
}

// Remove Photo from Gallery
function removePhotoFromGallery($gallery_id, $photo_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Gallery_Photos WHERE gallery_id = ? AND photo_id = ?");
    $stmt->bind_param("ii", $gallery_id, $photo_id);
    $stmt->execute();
    $stmt->close();
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add'])) {
        addPhotoToGallery($_POST['gallery_id'], $_POST['photo_id']);
    } elseif (isset($_POST['remove'])) {
        removePhotoFromGallery($_POST['gallery_id'], $_POST['photo_id']);
    }
}
?>