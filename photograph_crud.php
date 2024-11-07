<?php
include 'db.php';

// Create Photograph
function createPhotograph($title, $description, $image, $category, $upload_date) {
    global $conn;
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {
        // Check if file already exists
        if (!file_exists($target_file)) {
            // Check file size (limit to 5MB)
            if ($image["size"] <= 5000000) {
                // Allow certain file formats
                if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($image["tmp_name"], $target_file)) {
                        // Insert image URL into database
                        $stmt = $conn->prepare("INSERT INTO Photographs (title, description, image_url, category, upload_date) VALUES (?, ?, ?, ?, ?)");
                        $stmt->bind_param("sssss", $title, $description, $target_file, $category, $upload_date);
                        $stmt->execute();
                        $stmt->close();
                        return true;
                    }
                }
            }
        }
    }
    return false;
}

// Read Photograph
function getPhotograph($photo_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Photographs WHERE photo_id = ?");
    $stmt->bind_param("i", $photo_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $photo = $result->fetch_assoc();
    $stmt->close();
    return $photo;
}

// Update Photograph
function updatePhotograph($photo_id, $title, $description, $image, $category, $upload_date) {
    global $conn;
    $photo = getPhotograph($photo_id);
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($image["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {
        // Check if file already exists
        if (!file_exists($target_file)) {
            // Check file size (limit to 5MB)
            if ($image["size"] <= 5000000) {
                // Allow certain file formats
                if ($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "gif") {
                    // Move the uploaded file to the target directory
                    if (move_uploaded_file($image["tmp_name"], $target_file)) {
                        // Remove the old image
                        if (file_exists($photo['image_url'])) {
                            unlink($photo['image_url']);
                        }
                        // Update image URL in database
                        $stmt = $conn->prepare("UPDATE Photographs SET title = ?, description = ?, image_url = ?, category = ?, upload_date = ? WHERE photo_id = ?");
                        $stmt->bind_param("sssssi", $title, $description, $target_file, $category, $upload_date, $photo_id);
                        $stmt->execute();
                        $stmt->close();
                        return true;
                    }
                }
            }
        }
    }
    return false;
}

// Delete Photograph
function deletePhotograph($photo_id) {
    global $conn;
    $photo = getPhotograph($photo_id);
    if (file_exists($photo['image_url'])) {
        unlink($photo['image_url']);
    }
    $stmt = $conn->prepare("DELETE FROM Photographs WHERE photo_id = ?");
    $stmt->bind_param("i", $photo_id);
    $stmt->execute();
    $stmt->close();
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        createPhotograph($_POST['title'], $_POST['description'], $_FILES['image'], $_POST['category'], $_POST['upload_date']);
    } elseif (isset($_POST['update'])) {
        updatePhotograph($_POST['photo_id'], $_POST['title'], $_POST['description'], $_FILES['image'], $_POST['category'], $_POST['upload_date']);
    } elseif (isset($_POST['delete'])) {
        deletePhotograph($_POST['photo_id']);
    }
}
?>