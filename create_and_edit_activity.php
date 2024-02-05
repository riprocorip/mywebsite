<?php
session_start();

//ลงทะเบียน
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  include('database.php');


  // Collect form data
  $activity_name = $_POST["activity_name"];
  $activity_detail = $_POST["activity_detail"];
  $activity_date = $_POST["activity_date"];
  $applicationStart = $_POST["applicationStart"];
  $applicationEnd = $_POST["applicationEnd"];
  $activity_organizerName = $_POST["activity_organizerName"];
  $activity_telNumber = $_POST["activity_telNumber"];
  $activity_hours = (int)$_POST["activity_hours"];
  $activity_totalAdmissions = (int)$_POST["activity_totalAdmissions"];
  $activity_constructor = $_SESSION['usercode']; //รหัส นศ. ถ้าเป็นครูหรือแอดมิน จะเก็บเป็น username ที่ใช้ล็อคอิน

  if ($_SESSION['userlevel'] == '2') {
    $activity_status = "0"; //รออนุมัติ
  }
  if ($_SESSION['userlevel'] == '3') {
    $activity_status = "1"; //อนุมัติเลย
  }

  if (isset($_POST['activity_id'])) {
    // Update existing activity data
    $activity_id = mysqli_real_escape_string($conn, $_POST['activity_id']);
    $sql = "UPDATE activities SET name='$activity_name', detail='$activity_detail', startDate='$activity_date',applicationStart='$applicationStart' ,applicationEnd='$applicationEnd' ,organizerName='$activity_organizerName', telNumber='$activity_telNumber', hours='$activity_hours', totalAdmissions='$activity_totalAdmissions', constructor='$activity_constructor', status='$activity_status' WHERE id=$activity_id";

    if ($_SESSION['userlevel'] == '2') {
      echo '<script>alert("ยื่นคำร้องขอแก้ไขกิจกรรมสำเร็จ!");</script>';
    }
    if ($_SESSION['userlevel'] == '3') {
      echo '<script>alert("แก้ไขกิจกรรมเรียบร้อยแล้ว!");</script>';
    }
  } else {
    // Insert new activity data
    $sql = "INSERT INTO activities (name, detail, startDate,  applicationStart  ,applicationEnd , organizerName, telNumber, hours, totalAdmissions ,constructor , status)
             VALUES ('$activity_name', '$activity_detail', '$activity_date', '$applicationStart', '$applicationEnd' , '$activity_organizerName', '$activity_telNumber', '$activity_hours', '$activity_totalAdmissions' , '$activity_constructor' , '$activity_status')";

    if ($_SESSION['userlevel'] == '2') {
      echo '<script>alert("ยื่นคำร้องขอกิจกรรมเรียบร้อยแล้ว!");</script>';
    }
    if ($_SESSION['userlevel'] == '3') {
      echo '<script>alert("สร้างกิจกรรมเรียบร้อยแล้ว!");</script>';
    }
  }
  if ($conn->query($sql) === TRUE) {
    echo '<script>window.location.href = "activities.php";</script>';
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
} else {
  header("Location: index.php");
}
