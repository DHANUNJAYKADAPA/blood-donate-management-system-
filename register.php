<?php
// Configuration
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';
$db_name = 'blood_donation_system';

// Create connection
$conn = new mysqli($db_localhost, $db_dhanunjay, $db_Dhanunjay@1234, $db_blood_donation_system);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$donorID = $_POST['donorID'];
$name = $_POST['name'];
$email = $_POST['email'];
$bloodType = $_POST['bloodType'];
$contact = $_POST['contact'];

// Validate user input data
if (empty($donorID) || empty($name) || empty($email) || empty($bloodType) || empty($contact)) {
    echo "Please fill in all fields.";
    exit;
}

// Check if donor ID already exists
$sql = "SELECT * FROM donors WHERE donorID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $donorID);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "Donor ID already exists.";
    exit;
}

// Insert data into database
$sql = "INSERT INTO donors (donorID, name, email, bloodType, contact) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $donorID, $name, $email, $bloodType, $contact);
$stmt->execute();

// Close statement and connection
$stmt->close();
$conn->close();


exit;
?>