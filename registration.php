<?php
session_start();

//ลงทะเบียน
if (isset($_POST['activity_id'])) {

    include('database.php');

    // รับข้อมูลจากแบบฟอร์ม
    $activity_id = $_POST["activity_id"];
    $code = $_SESSION['usercode'];


    // ดูว่าลงทะเบียนแล้วหรือยัง
    $checkQuery = "SELECT * FROM registrations WHERE activity_id = '$activity_id' AND user_code = '$code'";
    $result = mysqli_query($conn, $checkQuery);


    if ($result && mysqli_num_rows($result) > 0) {
        // ลงทะเบียนแล้ว
        echo "Registration already exists!";
    } else {
        // ดูว่าเต็มแล้วหรือยัง
        $activityCheckQuery = "SELECT totalAdmissions, currentApplicant FROM activities WHERE id = '$activity_id'";
        $activityCheckResult = mysqli_query($conn, $activityCheckQuery);

        if ($activityCheckResult && mysqli_num_rows($activityCheckResult) > 0) {
            $activityData = mysqli_fetch_assoc($activityCheckResult);

            if ($activityData['currentApplicant'] < $activityData['totalAdmissions']) {
                // ยังสามารถลงทะเบียนได้อยู่
                $insertQuery = "INSERT INTO registrations (activity_id, user_code, registration_time) VALUES ('$activity_id', '$code', CURRENT_TIMESTAMP)";
                $insertResult = mysqli_query($conn, $insertQuery);

                if ($insertResult) {
                    // อัพเดท
                    $updateQuery = "UPDATE activities SET currentApplicant = currentApplicant + 1 WHERE id = '$activity_id'";
                    mysqli_query($conn, $updateQuery);

                    echo '<script>alert("ลงทะเบียนสำเร็จ!");</script>';
                    echo '<script>window.location.href = "activities.php";</script>';
                } else {
                    echo '<script>alert("เกิดข้อผิดพลาด");</script>';
                    echo '<script>window.location.href = "activities.php";</script>';
                }
            } else {
                echo '<script>alert("error ");</script>';
                echo '<script>window.location.href = "activities.php";</script>';
            }
        } else {
            echo "Error checking activity: " . mysqli_error($conn);
        }
    }
} else {
    header("Location: index.php");
}
