<?php
include 'database.php';
session_start();

if (!$_SESSION['usercode']) {
    header("Location: index.php");
} else {
    if ($_SESSION['userlevel'] != '1') {
        header("Location: logout.php");
    }
    // Fetch user data from the database
    $usercode = $_SESSION['usercode'];
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
        <title>ข้อมูลผู้ใช้งานนักศึกษา</title>

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

            /* Hover State for Pagination Link */
            .pagination a:hover {
                background-color: black;
                color: #fff;
            }

            /* Active State for Pagination Link */
            .pagination .active a {
                background-color: #b9d9fd;
                color: #fff;
            }

            /* Previous and Next Button Styles */
            .pagination .page-link {
                color: black;
                border: 1px solid #007bff;
            }

            /* Hover State for Previous and Next Buttons */
            .pagination .page-link:hover {
                background-color: #b9d9fd;
                color: #fff;
            }
        </style>

    </head>

    <body>
        <!-- nav bar -->
        <div class="container rounded " style="background-color:darksalmon;">
            <header class="d-flex flex-wrap justify-content-between py-2 mb-3  p-3 rounded">
                <div class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                    <h4 class="p-2" style="font-weight:500;">ข้อมูลนักศึกษา</h4>
                </div>
            </header>
        </div>
        <!-- nav bar end -->



        <!-- Student Profile Section -->
        <div class="container mt-3 pt-4 shadow min-vh-50 py-2 col-8" style="background-color: whitesmoke; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
            <a class="btn mb-3 btn-sm rounded-pill bg-secondary text-white" href="student_search.php" role="button">ย้อนกลับ</a>
            <div class="row">
                <div class="container mb-3 col-7">
                    <p>ชื่อ-นามสกุล: <?php echo $userData['name']; ?></p>
                    <p>แผนก: <?php echo $userData['department']; ?></p>
                    <p>รหัสนักศึกษา: <?php echo $userData['username']; ?></p>
                    <p>จำนวนชั่วโมง: <?php echo $userData['hours']; ?></p>
                </div>
                <div class="container col-4">

                </div>
            </div>
        </div>
        <!-- Student Profile Section EnD -->



        <!-- Activities Tabs -->
        <h5 class="text-center mb-3 mt-4" data-wow-delay="0.1s">กิจกรรมที่ลงทะเบียนไว้</h5>


        <!-- เรียกข้อมูลของกิจกรรมมาแสดงทั้งหมด -->
        <?php
        $results_per_page = 3;
        $userCode = $_SESSION['usercode'];

        $sql_total = "SELECT COUNT(*) AS total FROM activities
                                      INNER JOIN registrations ON activities.id = registrations.activity_id
                                      WHERE registrations.user_code = '$userCode'";
        $result_total = mysqli_query($conn, $sql_total);
        $row_total = mysqli_fetch_assoc($result_total);
        $total_pages = ceil($row_total['total'] / $results_per_page);

        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $offset = ($page - 1) * $results_per_page;

        $sql = "SELECT * FROM activities
                                INNER JOIN registrations ON activities.id = registrations.activity_id
                                WHERE registrations.user_code = '$userCode'
                                ORDER BY startDate ASC
                                LIMIT $offset, $results_per_page";

        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) == 0) {
            echo '<p class="text-center">ไม่พบกิจกรรมที่ลงทะเบียน</p>';
        }


        echo '<nav aria-label="Page navigation" class="mt-3 d-flex justify-content-center">';
        echo '  <ul class="pagination">';
        if ($total_pages > 1) {
            // Previous button
            if ($page > 1) {
                echo '  <li class="page-item"><a class="page-link" href="?page=' . ($page - 1) . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
            }

            // Numbered pages
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<li class="page-item';
                echo ($i == $page) ? ' active' : '';
                echo '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
            }

            // Next button
            if ($page < $total_pages) {
                echo '  <li class="page-item"><a class="page-link" href="?page=' . ($page + 1) . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
            }
        }
        echo '  </ul>';
        echo '</nav>';

        while ($row = mysqli_fetch_array($result)) {

            $activityId = $row['id'];
            // status 0 คือยังไม่ดำเนินการ
            $check1 = "SELECT status FROM registrations WHERE status = '0' AND activity_id = $activityId ";
            $check1Result = $conn->query($check1);

            if ($check1Result->num_rows > 0) {

        ?>
                <div class="container mt-3 pt-4 shadow min-vh-50 py-2 col-8 rounded" style="background-color: white;">

                    <form action="delete_registration.php" method="post">

                        <div class="mb-3 col-3">
                            <label for="activity_name" class="form-label">ชื่อกิจกรรม</label>
                            <small class="form-text text-muted text-break"><?= $row['name'] ?></small>
                        </div>
                        <div class="mb-3">
                            <label for="activity_detail" class="form-label">รายละเอียดของกิจกรรม</label>
                            <br>
                            <small class="form-text text-muted text-break"><?= $row['detail'] ?></small>
                            <hr>
                        </div>
                        <div class="row mt-3">

                        
                        <?php
                                        //date format
                                        $startDate = date('j M Y', strtotime($row['startDate']));
                                        $aStart = date('j M Y', strtotime($row['applicationStart']));
                                        $aEnd = date('j M Y', strtotime( $row['applicationEnd']));

                                    ?>
                            <div class="mb-3 col-2">
                                <label for="activity_date" class="form-label" style="color: green;">เริ่มรับสมัคร</label>
                                <br>
                                <small class="form-text text-muted text-break"><?=  $aStart  ?></small>
                            </div>
                            <div class="mb-3 col-2">
                                <label for="activity_date" class="form-label" style="color: red;">ปิดรับสมัคร</label>
                                <br>
                                <small class="form-text text-muted text-break"><?= $aEnd ?></small>
                            </div>
                            <div class="mb-3 col-4">
                                <label for="activity_date" class="form-label" style="font-weight: bold;">วันที่เริ่มกิจกรรม</label>
                                <br>
                                <small class="form-text text-muted text-break"><?= $startDate ?></small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 col-2">
                                <label for="activity_organizerName" class="form-label">ชื่อผู้จัดกิจกรรม</label>
                                <br>
                                <small class="form-text text-muted text-break"><?= $row['organizerName'] ?></small>
                            </div>
                            <div class="mb-3 col-2">
                                <label for="activity_telNumber" class="form-label">เบอร์โทรศัพท์</label>
                                <br>
                                <small class="form-text text-muted text-break"><?= $row['telNumber'] ?></small>
                            </div>
                            <div class="mb-3 col-2">
                                <label for="activity_hours" class="form-label">จำนวนชั่วโมงที่ลด(ชม.)</label>
                                <br>
                                <small class="form-text text-muted text-break"><?= $row['hours'] ?></small>
                            </div>
                            <div class="mb-3 col-2">
                                <label for="activity_totalAdmissions" class="form-label">รับทั้งหมด(คน)</label>
                                <br>
                                <small class="form-text text-muted text-break"><?= $row['totalAdmissions'] ?></small>
                            </div>
                            <div class="mb-3 col-2">
                                <label for="activity_totalAdmissions" class="form-label">จำนวนผู้สมัคร(คน)</label>
                                <br>
                                <small class="form-text text-muted text-break"><?= $row['currentApplicant'] ?></small>
                            </div>
                        </div>
                        <?php

                        // Assuming $row is an associative array with keys 'startDate' and 'applicationEnd'
                        // and values representing the start and end dates in a format like 'YYYY-MM-DD'

                        $currentDate = date('Y-m-d'); // Get the current date in the same format

                        if ($currentDate <= $row['startDate'] && $currentDate <= $row['applicationEnd']) {
                            // The start date has not yet arrived and aplication is not end yet
                            ?>
                            <button type="button" class="btn mb-3 mt-2 btn-sm rounded-pill bg-danger text-white" data-bs-toggle="modal" data-bs-target="#confirm-submit<?= $row['id'] ?>">ยกเลิกลงทะเบียน</button>
                            <?php
                        }  else {
                           
                            echo "ไม่สามารถยกเลิกได้";
                        }

                        ?>
                        <a href="pdf.php?activity_id=<?= $row['id'] ?>" class="btn mb-3 mt-2 btn-sm rounded-pill bg-primary text-white" target="_blank">ปริ้นแบบฟอร์ม</a>

                        <div class="modal fade" id="confirm-submit<?= $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">ต้องการยกเลิกหรือไม่</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-danger">ต้องการยกเลิกหรือไม่</p>
                                        <p></p>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="activity_id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn btn-success">ยืนยัน</button>
                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ยกเลิก</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>


        <?php

            }
        }


        mysqli_close($conn);
        ?>

        <!-- Activities Table  END -->









        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    </body>

    </html>
<?php } ?>