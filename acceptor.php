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

// Function to validate and sanitize blood group input
function validateBloodGroup($bloodGroup) {
    $allowedBloodGroups = array('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-');
    if (!in_array($bloodGroup, $allowedBloodGroups)) {
        throw new Exception("Invalid blood group");
    }
    return $bloodGroup;
}

// Get blood group input from user
$bloodGroup = $_POST['bloodGroup'];
try {
    $bloodGroup = validateBloodGroup($bloodGroup);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit;
}

// Retrieve donors with matching blood type
$sql = "SELECT name, contact, email, bloodType FROM donors WHERE bloodType = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}
$stmt->bind_param("s", $bloodGroup);
if (!$stmt->execute()) {
    echo "Error executing statement: " . $stmt->error;
    exit;
}
$result = $stmt->get_result();

// Display results
if ($result->num_rows > 0) {
    echo "<h2>Donors with $bloodGroup blood type:</h2>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>$row[name] ($row[contact]) - $row[email]</li>";
    }
    echo "</ul>";
} else {
    echo "No donors found with $bloodGroup blood type.";
}

// Close statement and connection
$stmt->close();
$conn->close();
?>