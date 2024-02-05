<?php
include 'database.php';

// ตรวจสอบกิจกรรมที่ยังไม่ได้อนุมัติ
$sqlActivities = "SELECT COUNT(*) AS activity_count FROM activities WHERE status = '0'";
$resultActivities = mysqli_query($conn, $sqlActivities);

if ($resultActivities) {
    $rowActivities = mysqli_fetch_assoc($resultActivities);
    $activities_notificationCount = $rowActivities['activity_count'];
} else {
    $activities_notificationCount = 0; // Return 0 in case of an error
}

// ตรวจสอบคำร้องเพิ่มชั่วโมง
$sqlMisconduct = "SELECT COUNT(*) AS misconduct_count FROM misconduct WHERE status = '0'";
$resultMisconduct = mysqli_query($conn, $sqlMisconduct);

if ($resultMisconduct) {
    $rowMisconduct = mysqli_fetch_assoc($resultMisconduct);
    $misconduct_notificationCount = $rowMisconduct['misconduct_count'];
} else {
    $misconduct_notificationCount = 0; // Return 0 in case of an error
}

mysqli_close($conn);

// Echo the notification counts
echo json_encode([
    'activities_notificationCount' => $activities_notificationCount, //คำร้องขอกิจกรรม
    'misconduct_notificationCount' => $misconduct_notificationCount, //คำร้องเพิ่มชั่วโมง
]); 
