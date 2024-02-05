<?php
include 'database.php';
session_start();

if (!$_SESSION['usercode']) {
    header("Location: index.php");
} else {

    if ($_SESSION['userlevel'] == '1') {
        header("Location: logout.php");
    }
    // Fetch user data from the database
    $usercode = $_SESSION['usercode'];
    $username =   $usercode;
    $query = "SELECT * FROM users WHERE username = '$usercode'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $userData = mysqli_fetch_assoc($result);
    }
?>
    <!DOCTYPE html>
    <html>

    <head>
        <!--ข้อมูลพื้นฐานของเว็บ-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="index">


        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-dtGfTbBMt1I5a0+5FPOCmqL4t5stHGo+AdeI6uY4CmKLOw5Yfr7C0czNlFI3GDEcb8ZO4eMCyYYo7jqgc63ifw==" crossorigin="anonymous" />


        <!--หัวข้อหน้าเว็บ-->
        <title>ดูข้อมูลผู้สมัคร</title>

        <style>
            body {
                font-size: 90%;
                /* Adjust the global font size */
                background-color: #b9d9fd;
            }


            .centered-row {
                width: 100%;
                margin-left: auto;
                margin-right: auto;
            }
        </style>

    </head>

    <body>
        <!-- view aplicant list -->
        <div class="container mt-3 pt-4 shadow min-vh-50 py-2 col-10" style="background-color: whitesmoke; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
            <a class="btn mb-3 btn-sm rounded-pill bg-secondary text-white" href="<?php echo ($_SESSION['userlevel'] == '3') ? 'activities.php' : 'profile_teacher.php'; ?>" role="button">ย้อนกลับ</a>


            <?php
            if (isset($_GET['id'])) {

                $activity_id = ($_GET['id']);
                // Query to fetch activity details
                $activityDetailsQuery = "SELECT * FROM activities WHERE id = '$activity_id'";
                $activityDetailsResult = $conn->query($activityDetailsQuery);

                if ($activityDetailsResult->num_rows > 0) {
                    $activityDetails = $activityDetailsResult->fetch_assoc();
                    $activityName = $activityDetails['name'];
                    $startDate = $activityDetails['startDate'];
                    $totalApplicant = $activityDetails['currentApplicant'];
                    $totalAdmissions = $activityDetails['totalAdmissions'];
                    $organizerName = $activityDetails['organizerName'];
                }
            ?>
                <a class="btn mb-3 btn-sm rounded-pill bg-success text-white" href="pdf2.php?activity_id=<?= $activity_id ?>" role="button">ปริ้นแบบฟอร์มรายชื่อ</a>
                <?php
                // Query 
                $sql = "SELECT * FROM registrations
            INNER JOIN users ON registrations.user_code = username
            WHERE registrations.activity_id = '$activity_id'
            ORDER BY registrations.registration_time ASC";

                $result = $conn->query($sql);
                $startDate = date('j M Y', strtotime($activityDetails['startDate']));
                $aStart = date('j M Y', strtotime($activityDetails['applicationStart']));
                $aEnd = date('j M Y', strtotime( $activityDetails['applicationEnd']));
            
                ?>
                <!-- Header -->
                <div class="text-black">
                <small>ID ของกิจกรรม : <?php echo $activityDetails['id'];; ?></small>
                    <h4><?php echo $activityName; ?></h4>
                    <p>วันที่เริ่มกิจกรรม: <?php echo $startDate; ?> <br> เปิดรับสมัครเมื่อ: <?php echo $aStart?> จนถึง <?php echo    $aEnd ?> </p>
                    <p><?= $activityDetails['detail']; ?></p>
                    <p>ชื่อผู้จัดกิจกรรม: <?php echo $organizerName; ?> | เบอร์โทรศัพท์ <?= $activityDetails['telNumber']; ?> <br> <small>
                            ได้รับการลดชั่วโมงเป็นจำนวน <?= $activityDetails['hours']; ?> ชั่วโมง </small></p>
                    <small>จำนวนนักศึกษาที่เข้าร่วม: <?php echo $totalApplicant; ?> / <?= $activityDetails['totalAdmissions']; ?> คน</small>

                </div>


                <?php
                // Display the applicants
                if ($result->num_rows > 0) {
                ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>ชื่อ-นามสกุล</th>
                                    <th>รหัสนักศึกษา</th>
                                    <th>แผนก</th>
                                    <?php
                                    // if admin
                                    if ($_SESSION['userlevel'] == '3') {
                                    ?>
                                        <th>การเข้ากิจกรรมลดชั่วโมง</th>

                                    <?php
                                    }
                                    ?>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $counter = 1;
                                while ($row = $result->fetch_assoc()) {
                                ?>
                                    <tr>
                                        <td><?php echo $counter; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['username']; ?></td>
                                        <td><?php echo $row['department']; ?></td>
                                        <?php
                                        // if admin
                                        if ($_SESSION['userlevel'] == '3') {
                                            $n = $row['username'];
                                            $userStatusCheck = "SELECT status FROM registrations WHERE user_code = '$n';";
                                            $userStatusCheckresult = mysqli_query($conn, $userStatusCheck);
                                            $userStatusCheckresultData = mysqli_fetch_assoc($userStatusCheckresult);


                                            if ($userStatusCheckresultData['status'] == '1') {
                                        ?>
                                                <td style="color:green">ได้รับการลดชั่วโมงแล้ว</td>
                                            <?php
                                            } else if ($userStatusCheckresultData['status'] == '3') {
                                            ?>
                                                <td style="color:red">ได้รับการลงโทษแล้ว</td>
                                            <?php
                                            } else {


                                            ?>

                                                <td>
                                                    <form method="post" action="updateHours2.php">


                                                        <button type="button" class="btn btn-sm bg-success text-white" data-bs-toggle="modal" data-bs-target="#confirm-submit<?= $row['id'] ?>">ยืนยันการลดชั่วโมง</button>
                                                        <button type="button" class="btn btn-sm bg-danger text-white" data-bs-toggle="modal" data-bs-target="#confirm-reject<?= $row['id'] ?>">ไม่อนุมัติ</button>
                                                        <div class="modal fade" id="confirm-submit<?= $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">ต้องการยืนยันหรือไหม</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p class="text">ยืนยันว่า <?php echo $row['name']; ?> ได้ดำเนินการกิจกรรมลดชั่วโมงแล้วเรียบร้อย</p>
                                                                        <p class="text">และจะได้รับการลงชั่วโมงเป็นจำนวน <?= $activityDetails['hours']; ?> ชั่วโมง</p>
                                                                        <p class="text-danger"><strong>หากยืนยันแล้วจะไม่สามารถแก้ไขได้</strong></p>
                                                                        <p></p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <form method="post" action="updateHours2.php">
                                                                            <input type="hidden" name="username" value="<?= $row['username'] ?>">
                                                                            <input type="hidden" name="id" value="<?= $activity_id ?>">
                                                                            <input type="hidden" name="result" value="1">
                                                                            <input type="hidden" name="hours_to_remove" value="<?= $activityDetails['hours']; ?>">
                                                                            <button type="submit" class="btn btn-success">ยืนยัน</button>
                                                                        </form>
                                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ยกเลิก</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="modal fade" id="confirm-reject<?= $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <!-- Modal Content for Reject -->
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="exampleModalLabel">ไม่อนุมัติใช่หรือไม่</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <p class="text-danger"> <?php echo $row['name']; ?> ไม่ได้ดำเนินการกิจกรรมลดชั่วโมงตามกำหนดการหรือไม่รับผิดชอบต่อหน้าที่ โดยนักศึกษาคนดังกล่าวจะได้รับโทษจำนวน 15 ชั่วโมง</p>
                                                                        <p class="text-danger"><strong>หากยืนยันแล้วจะไม่สามารถแก้ไขได้</strong></p>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <form method="post" action="updateHours2.php">
                                                                            <input type="hidden" name="username" value="<?= $row['username'] ?>">
                                                                            <input type="hidden" name="id" value="<?= $activity_id ?>">
                                                                            <input type="hidden" name="result" value="0">
                                                                            <input type="hidden" name="hours_to_remove" value="<?= $activityDetails['hours']; ?>">
                                                                            <button type="submit" class="btn btn-danger">ยืนยัน</button>
                                                                        </form>
                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </td>
                                        <?php
                                            }
                                        }

                                        ?>

                                        <!-- Add more table data cells for additional fields -->
                                    </tr>
                                <?php
                                    $counter = $counter + 1;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

            <?php

                } else {
                    //ถ้ายังไม่มีใครสมัคร
                }
            } else {
                echo "ID กิจกรรมไม่ถูกต้อง";
            }


            ?>

        </div>




        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    </body>

    </html>
<?php } ?>