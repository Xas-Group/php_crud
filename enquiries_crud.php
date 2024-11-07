<?php
include 'db.php';

// Create Enquiry
function createEnquiry($user_id, $event_type, $event_date, $location, $message, $status) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO Enquiries (user_id, event_type, event_date, location, message, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $user_id, $event_type, $event_date, $location, $message, $status);
    $stmt->execute();
    $stmt->close();
}

// Read Enquiry
function getEnquiry($enquiry_id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Enquiries WHERE enquiry_id = ?");
    $stmt->bind_param("i", $enquiry_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $enquiry = $result->fetch_assoc();
    $stmt->close();
    return $enquiry;
}

// Update Enquiry
function updateEnquiry($enquiry_id, $user_id, $event_type, $event_date, $location, $message, $status) {
    global $conn;
    $stmt = $conn->prepare("UPDATE Enquiries SET user_id = ?, event_type = ?, event_date = ?, location = ?, message = ?, status = ? WHERE enquiry_id = ?");
    $stmt->bind_param("isssssi", $user_id, $event_type, $event_date, $location, $message, $status, $enquiry_id);
    $stmt->execute();
    $stmt->close();
}

// Delete Enquiry
function deleteEnquiry($enquiry_id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM Enquiries WHERE enquiry_id = ?");
    $stmt->bind_param("i", $enquiry_id);
    $stmt->execute();
    $stmt->close();
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['create'])) {
        createEnquiry($_POST['user_id'], $_POST['event_type'], $_POST['event_date'], $_POST['location'], $_POST['message'], $_POST['status']);
    } elseif (isset($_POST['update'])) {
        updateEnquiry($_POST['enquiry_id'], $_POST['user_id'], $_POST['event_type'], $_POST['event_date'], $_POST['location'], $_POST['message'], $_POST['status']);
    } elseif (isset($_POST['delete'])) {
        deleteEnquiry($_POST['enquiry_id']);
    }
}
?>