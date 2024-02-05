<?php
include 'database.php';
session_start();
// UPDATE HOURS
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $result = $_POST['result'];
    $backPage = $_POST['id'];
    $hoursToRemove = $_POST['hours_to_remove'];

    // Validate and sanitize form data
    $username = mysqli_real_escape_string($conn, $username);

    if ($result == '1') {
        // Update the hours in the users table
        $updateQuery = "UPDATE users SET hours = hours - $hoursToRemove WHERE username = '$username'";


        if ($conn->query($updateQuery) === TRUE) {
            echo '<script>alert("ดำเนินลดชั่วโมงสำเร็จ"); </script>';
            echo '<script>window.location.href = "view_applicant.php?id='. $backPage . '";</script>';

            $statusUpdate = $sql = "UPDATE registrations SET status = '1' WHERE user_code = '$username' AND activity_id = '$backPage'"; // backpage is ActivityID
            $conn->query($statusUpdate);
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }
    else{
        $updateQuery = "UPDATE users SET hours = hours + 15 WHERE username = '$username'";


        if ($conn->query($updateQuery) === TRUE) {
            echo '<script>alert("นักศึกษาได้รับการลงโทษแล้ว"); </script>';
            echo '<script>window.location.href = "view_applicant.php?id='. $backPage . '";</script>';

            $statusUpdate = $sql = "UPDATE registrations SET status = '3' WHERE user_code = '$username' AND activity_id = '$backPage'"; // backpage is ActivityID
            $conn->query($statusUpdate);
        } else {
            echo "Error updating record: " . $conn->error;
        }
    }


    $conn->close();
}
?>
