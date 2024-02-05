<?php
session_start();
include('database.php');
// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the activity_id from the POST request
    $activity_id = $_POST["activity_id"];

    // Validate and sanitize the input if needed

    // Perform the delete operation
    $sql = "DELETE FROM activities WHERE id = ?";
    
    $stmt = mysqli_prepare($conn, $sql);

    // Check if the prepare statement succeeded
    if ($stmt) {
        // Bind the parameters
        mysqli_stmt_bind_param($stmt, "i", $activity_id);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("สร้างกิจกรรมเรียบร้อยแล้ว!");</script>';
            echo '<script>window.location.href = "activities.php";</script>';
        } else {
            echo '<script>alert("error!");</script>';
            echo '<script>window.location.href = "activities.php";</script>';
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle the case where prepare statement fails
        echo '<script>alert("error!");</script>';
        echo '<script>window.location.href = "activities.php";</script>';
    }
} else {
    // Redirect if the request method is not POST
    header("Location: index.php");
}
?>
