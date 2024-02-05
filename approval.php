<?php
include 'database.php';
session_start();

if (!$_SESSION['usercode']) {
    header("Location: index.php");
} else {
    if ($_SESSION['userlevel'] != '3') {
        header("Location: logout.php");
    }

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
        <title>คำร้องขอเพิ่มกิจกรรม</title>

        <style>
            body {
                /* Adjust the global font size */
                background-color: #b9d9fd;

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
        <div class="container mt-3 pt-4 min-vh-50 py-2 col-10 rounded" style="background-color:darksalmon;">
            <!-- Activities Tabs -->
            <a class="btn mb-3 btn-sm rounded-pill bg-secondary text-white" href="activities.php" role="button">ย้อนกลับ</a>
            <h5 class="text-center mb-3" data-wow-delay="0.1s"><strong>คำร้องขอเพิ่มกิจกรรม</strong></h5>

            <?php
            $results_per_page = 5;

            $sql_total = "SELECT COUNT(*) AS total FROM activities
                                      WHERE status = '0'";
            $result_total = mysqli_query($conn, $sql_total);
            $row_total = mysqli_fetch_assoc($result_total);
            $total_pages = ceil($row_total['total'] / $results_per_page);

            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $results_per_page;

            $sql = "SELECT * FROM activities
                                WHERE status = '0'
                                ORDER BY id ASC
                                LIMIT $offset, $results_per_page";

            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) == 0) {
                echo '<p class="text-center">ไม่พบคำร้องขอ</p>';
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
                <div class="mt-3 col-8 mx-auto" style="background-color: whitesmoke; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
                    <div class="">
                        <label for="activity_name" class="form-label">ชื่อกิจกรรม</label>
                        <small class="form-text text-muted text-break"><?= $row['name'] ?></small>
                    </div>
                    <div class="">
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
                        $aEnd = date('j M Y', strtotime($row['applicationEnd']));

                        ?>
                        <div class="mb-3 col-2">
                            <label for="activity_date" class="form-label">เริ่มรับสมัคร</label>
                            <br>
                            <small class="form-text text-muted text-break"><?=  $aStart ?></small>
                        </div>
                        <div class="mb-3 col-2">
                            <label for="activity_date" class="form-label">ปิดรับสมัคร</label>
                            <br>
                            <small class="form-text text-muted text-break"><?= $aEnd  ?></small>
                        </div>
                        <div class="mb-3 col-2">
                            <label for="activity_date" class="form-label">วันที่เริ่มกิจกรรม</label>
                            <br>
                            <small class="form-text text-muted text-break"><?= $startDate ?></small>
                        </div>
                    </div>
                    <div class="row mt-3">
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
                        <div class="mb-3 col-3">
                            <label for="activity_totalAdmissions" class="form-label">จำนวนผู้สมัครปัจจุบัน(คน)</label>
                            <br>
                            <small class="form-text text-muted text-break"><?= $row['currentApplicant'] ?></small>
                        </div>

                    </div>
                    <button type="button" class="btn mb-3 mt-2 btn-sm rounded-pill bg-success text-white" data-bs-toggle="modal" data-bs-target="#confirm-submit<?= $row['id'] ?>">อนุมัติ</button>
                    <div class="modal fade" id="confirm-submit<?= $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">ต้องการอนุมัติหรือไม่</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-success">ต้องการอนุมัติหรือไม่</p>
                                    <p></p>
                                </div>
                                <div class="modal-footer">
                                    <form method="post" action="">
                                        <input type="hidden" name="activity_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="status_update" value="1">
                                        <button type="submit" class="btn btn-success">ยืนยัน</button>
                                    </form>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ยกเลิก</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn mb-3 mt-2 btn-sm rounded-pill bg-danger text-white" data-bs-toggle="modal" data-bs-target="#confirm-reject<?= $row['id'] ?>">ไม่อนุมัติ</button>
                    <div class="modal fade" id="confirm-reject<?= $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <!-- Modal Content for Reject -->
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">ไม่อนุมัติใช่หรือไม่</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p class="text-danger">ไม่อนุมัติใช่หรือไม่</p>
                                    <p></p>
                                </div>
                                <div class="modal-footer">
                                    <form method="post" action="">
                                        <input type="hidden" name="activity_id" value="<?= $row['id'] ?>">
                                        <input type="hidden" name="status_update" value="3">
                                        <button type="submit" class="btn btn-danger">ยืนยัน</button>
                                    </form>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }

            // PHP code to process
            if ($_SERVER["REQUEST_METHOD"] == "POST") {


                $activity_id = $_POST['activity_id'];
                $status = $_POST['status_update'];
                $update_query = "UPDATE activities SET status = '$status' WHERE id = '$activity_id'";
                $update_result = mysqli_query($conn, $update_query);

                if ($update_result) {
                    // Successful update
                    echo '<script>alert("ดำเนินการเรียบร้อย");</script>';
                    echo '<meta http-equiv="refresh" content="0;url=approval.php">';
                } else {
                    // Error in update
                    echo '<script>alert("เกิดข้อผิดพลาด");</script>';
                    echo '<meta http-equiv="refresh" content="0;url=approval.php">';
                }
            }
            ?>

        </div>
        <!-- Activities Table  END -->
        <!-- Bootstrap JS -->


        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    </body>

    </html>
<?php } ?>