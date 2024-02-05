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
        <title>ข้อมูลของครู</title>


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

        <!-- Activities Tabs -->
        <div class="container mt-3 pt-4 min-vh-50 py-2 col-10">
            <a class="btn mb-3 btn-sm rounded-pill bg-secondary text-white" href="activities.php" role="button">ย้อนกลับ</a>
            <h5 class="text-center mb-3" data-wow-delay="0.1s">กิจกรรมที่สร้างไว้</h5>

            <!-- เรียกข้อมูลของกิจกรรมมาแสดงทั้งหมด -->
            <?php
            $results_per_page = 3;

            $sql_total = "SELECT COUNT(*) AS total FROM activities
                                      WHERE constructor = '$username'";
            $result_total = mysqli_query($conn, $sql_total);
            $row_total = mysqli_fetch_assoc($result_total);
            $total_pages = ceil($row_total['total'] / $results_per_page);

            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $results_per_page;

            $sql = "SELECT * FROM activities
                                WHERE constructor = '$username'
                                ORDER BY id ASC
                                LIMIT $offset, $results_per_page";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 0) {
                echo '<p class="text-center">ไม่พบกิจกรรม</p>';
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
            ?>
                <div class="card mt-3" style="background-color: whitesmoke; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
                    <form action="" method="post">
                        <div class="mb-3 col-4">
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
                                <small class="form-text text-muted text-break"><?= $aStart ?></small>
                            </div>
                            <div class="mb-3 col-2">
                                <label for="activity_date" class="form-label" style="color: red;">ปิดรับสมัคร</label>
                                <br>
                                <small class="form-text text-muted text-break"><?=$aEnd  ?></small>
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
                        <a href='view_applicant.php?id=<?= $row['id'] ?>' class='btn btn-success btn-sm'>ดูรายชื่อผู้สมัคร</a>
                        <a href='edit.php?id=<?= $row['id'] ?>' class='btn btn-secondary btn-sm'>แก้ไขกิจกรรม</a>

                    </form>
                </div>
            <?php
            }

            // PHP code to process
            if ($_SERVER["REQUEST_METHOD"] == "POST") {


                $activity_id = $_POST['activity_id'];
                $update_query = "UPDATE activities SET status = '1' WHERE id = '$activity_id'";
                $update_result = mysqli_query($conn, $update_query);

                if ($update_result) {
                    // Successful update
                    echo '<script>alert("อนุมัติกิจกรรมเรียบร้อยแล้ว");</script>';
                    echo '<meta http-equiv="refresh" content="0;url=approval.php">';
                } else {
                    // Error in update
                    echo '<script>alert("เกิดข้อผิดพลาดในการอนุมัติกิจกรรม");</script>';
                    echo '<meta http-equiv="refresh" content="0;url=approval.php">';
                }
            }
            ?>


            <!-- Activities Table  END -->

            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    </body>

    </html>
<?php } ?>