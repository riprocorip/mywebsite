<?php
include 'database.php';
session_start();

if (!$_SESSION['usercode'] || $_SESSION['userlevel'] != '3') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['hours_to_remove']) && isset($_POST['username'])) {
    // Handle form submission
    $hoursToRemove = $_POST['hours_to_remove'];
    $username = $_POST['username'];

    // Sanitize user input
    $hoursToRemove = mysqli_real_escape_string($conn, $hoursToRemove);
    $username = mysqli_real_escape_string($conn, $username);

    // Update the hours in the users table
    $updateQuery = "UPDATE users SET hours = hours - $hoursToRemove WHERE username = '$username'";


    if ($conn->query($updateQuery) === TRUE) {
        echo '<script>alert("ดำเนินการสำเร็จ"); </script>';
        echo '<script>window.location.href = "student_search.php?username='. $username . '";</script>';
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    echo "Invalid request.";
}

// Close the database connection
$conn->close();
