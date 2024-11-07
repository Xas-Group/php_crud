<?php
include 'db.php';

// Create Gallery
function createGallery($name, $description) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Galleries (name, description) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $description);
    $stmt->execute();
    $stmt->close();
}

// Read Gallery
function getGallery($gallery_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Galleries WHERE gallery_id = ?");
    $stmt->bind_param("i", $gallery_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $gallery = $result->fetch_assoc();
    $stmt->close();
    return $gallery;
}

// Update Gallery
function updateGallery($gallery_id, $name, $description) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Galleries SET name = ?, description = ? WHERE gallery_id = ?");
    $stmt->bind_param("ssi", $name, $description, $gallery_id);
    $stmt->execute();
    $stmt->close();
}

// Delete Gallery
function deleteGallery($gallery_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Galleries WHERE gallery_id = ?");
    $stmt->bind_param("i", $gallery_id);
    $stmt->execute();
    $stmt->close();
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        createGallery($_POST['name'], $_POST['description']);
    } elseif (isset($_POST['update'])) {
        updateGallery($_POST['gallery_id'], $_POST['name'], $_POST['description']);
    } elseif (isset($_POST['delete'])) {
        deleteGallery($_POST['gallery_id']);
    }
}
?>