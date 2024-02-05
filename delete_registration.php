<?php
session_start();

// Delete registration
if (isset($_POST['activity_id'])) {

    include('database.php');

    // รับข้อมูลจากแบบฟอร์ม
    $activity_id = $_POST["activity_id"];
    $code = $_SESSION['usercode'];

    // Delete registration
    $deleteQuery = "DELETE FROM registrations WHERE activity_id = '$activity_id' AND user_code = '$code'";
    $deleteResult = mysqli_query($conn, $deleteQuery);

    if ($deleteResult) {

        if ($deleteResult) {
            // Update currentApplicant in Activities table
            $updateQuery = "UPDATE activities SET currentApplicant = currentApplicant - 1 WHERE id = '$activity_id'";
            mysqli_query($conn, $updateQuery);

            echo '<script>alert("ยกเลิกการลงทะเบียนแล้ว!");</script>';
            echo '<script>window.location.href = "profile_student.php";</script>';
        } else {
            echo '<script>alert("เกิดข้อผิดพลาด");</script>';
            echo '<script>window.location.href = "profile_student.php";</script>';
        }
    } else {
        echo "Error deleting registration: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
}
