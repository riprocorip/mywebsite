<?php
session_start();
include('database.php');

if (isset($_POST['username']) && $_POST['isStudent'] == true) {


    $username = $_POST['username'];
    $password = $_POST['password'];
    $department = $_POST['department'];
    $passwordenc = md5($password);

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND department = '$department' ";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_array($result);

        $_SESSION['usercode'] = $row['username'];
        $_SESSION['username'] = $row['name'];
        $_SESSION['userdepartment'] = $row['department'];
        $_SESSION['userhours'] = $row['hours'];
        $_SESSION['userlevel'] = $row['userlevel'];

        header("Location: activities.php");
    } else {
        $_SESSION['error'] = "กรุณากรอกข้อมูลใหม่อีกครั้ง";
        header("Location: index.php");
    }
} else if (isset($_POST['username']) && $_POST['isStudent'] == false)  { //no department in case of teacher or admin

    $username = $_POST['username'];
    $password = $_POST['password'];
    $passwordenc = md5($password);

    $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' ";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_array($result);

        $_SESSION['usercode'] = $row['username'];
        $_SESSION['username'] = $row['name'];
        $_SESSION['userdepartment'] = $row['department'];
        $_SESSION['userhours'] = $row['hours'];
        $_SESSION['userlevel'] = $row['userlevel'];

        header("Location: activities.php");
    } else {
        $_SESSION['error'] = "กรุณากรอกข้อมูลใหม่อีกครั้ง";
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}
