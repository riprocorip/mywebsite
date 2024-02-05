    <?php include 'database.php';
    session_start();

    if (!$_SESSION['usercode']) {
        header("Location: index.php");
    } else {


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
            <title>หน้าหลัก</title>

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
            <div class="container rounded" style="background-color:darksalmon;">
                <header class="d-flex flex-wrap justify-content-between py-2 mb-3 p-3">
                    <div class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
                        <img class="img-fluid" src="img\tcicon.png" alt="tcicon" style="width: 100px; height: 100px;">
                        <h4 class="p-2" style="font-weight:500;">กิจกรรมลดชั่วโมงบำเพ็ญประโยชน์</h4>
                    </div>
                    <ul class="nav nav-pills mt-5 fs-6">
                        <li class="nav-item"><a href="activities.php" class="nav-link">สำรวจกิจกรรม</a></li>

                        <!-- admin -->
                        <?php
                        if ($_SESSION['userlevel'] == '3') {
                        ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">เพิ่มเติม</a>
                                <ul class="dropdown-menu">
                                    <!-- Add these lines where you want to display the notification counts -->
                                    <li>
                                        <a class="dropdown-item" href="approvalHours.php" id="hoursRequestsLink">
                                            ตรวจสอบคำร้องขอเพิ่มชั่วโมงนักศึกษา
                                            <span id="hoursRequestsCount" class="badge bg-danger">0</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="approval.php" id="activityRequestsLink">
                                            ตรวจสอบคำร้องขอกิจกรรม
                                            <span id="activityRequestsCount" class="badge bg-danger">0</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="search_activity.php">
                                            ตรวจสอบกิจกรรม
                                        </a>
                                    </li>
                                    <li><a class="dropdown-item" href="profile_teacher.php">ตรวจสอบกิจกรรมที่โพสต์ไว้</a></li>
                            </li>
                            <script>
                                function fetchNotificationCounts() {
                                    fetch('get_notification_count.php')

                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error('Network response was not ok');
                                            }
                                            return response.json();
                                        })
                                        .then(data => {
                                            document.getElementById('activityRequestsCount').innerText = data.activities_notificationCount;
                                            document.getElementById('hoursRequestsCount').innerText = data.misconduct_notificationCount;
                                        })
                                        .catch(error => console.error('Error fetching notification counts:', error));


                                }

                                fetchNotificationCounts();

                                setInterval(fetchNotificationCounts, 300000);
                            </script>

                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="create.php">โพสต์กิจกรรม</a></li>
                            <li><a class="dropdown-item" href="student_search.php">ค้นหานักศึกษา</a></li>
                    </ul>
                    </li>
                <?php
                        }
                ?>
                <!-- admin end -->


                <!-- teacher -->
                <?php
                if ($_SESSION['userlevel'] == '2') {
                ?>
                    <li class="nav-item dropdown" id="teacherDropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">เพิ่มเติม</a>
                        <ul class="dropdown-menu">
                            <li>
                            <li><a class="dropdown-item" href="profile_teacher.php">ตรวจสอบกิจกรรมที่โพสต์</a></li>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li><a class="dropdown-item" href="create.php">ยื่นคำร้องขอกิจกรรม</a></li>
                    <li><a class="dropdown-item" href="student_search.php">ค้นหาข้อมูลนักศึกษา</a></li>
                    </ul>
                    </li>
                <?php
                }
                ?>
                <script>
                    // Use jQuery to handle the hover event
                    $(document).ready(function() {
                        $('#teacherDropdown').hover(
                            function() {
                                // Trigger Bootstrap's dropdown method
                                $(this).addClass('show');
                                $(this).find('.dropdown-menu').addClass('show');
                            },
                            function() {
                                // Remove the 'show' class when mouse leaves
                                $(this).removeClass('show');
                                $(this).find('.dropdown-menu').removeClass('show');
                            }
                        );
                    });
                </script>
                <!-- teacher end -->






                <!-- student -->
                <?php
                if ($_SESSION['userlevel'] == '1') {
                ?>
                    <li class="nav-item"><a href="profile_student.php" class="nav-link">ข้อมูลผู้ใช้</a></li>
                <?php
                }
                ?>
                <!-- student end -->



                <li class="nav-item"><a href="logout.php" class="nav-link text-danger">ออกจากระบบ</a></li>
                </ul>

                </header>
            </div>
            <!-- nav bar end -->

            <div class="container">

                <div class="row">


                    <!-- 
                    <h5 class="text-center " data-wow-delay="0.1s">งานกิจกรรมทั้งหมด</h5>
                        Activities Tabs -->

                    <!-- เรียกข้อมูลของกิจกรรมมาแสดงทั้งหมด -->
                    <?php
                    $results_per_page = 5;

                    // Check if the page parameter is set in the URL, otherwise set it to 1
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;

                    // offset cal
                    $offset = ($page - 1) * $results_per_page;
                    $userCode = $_SESSION['usercode'];

                    // fetch
                    $sql = "SELECT * FROM activities 
            WHERE currentApplicant < totalAdmissions 
            AND NOT EXISTS (
                SELECT 1 FROM registrations 
                WHERE registrations.activity_id = activities.id 
                AND registrations.user_code = '$userCode'
                )
            AND activities.status = '1'
            AND DATE(activities.applicationEnd) >= CURDATE() -- ดูว่าปิดรับสมัครหรือยัง
            ORDER BY startDate ASC
            LIMIT $offset, $results_per_page";
                    $result = mysqli_query($conn, $sql);

                    // nav show 5 fetch        
                    // Total count query with the same conditions as the main query
                    $sql_total = "SELECT COUNT(*) AS total FROM activities 
            WHERE currentApplicant < totalAdmissions 
              AND NOT EXISTS (
                  SELECT 1 FROM registrations 
                  WHERE registrations.activity_id = activities.id 
                    AND registrations.user_code = '$userCode'
              )
              AND activities.status = '1'
              AND DATE(activities.applicationEnd) >= CURDATE()  -- Check if startDate is not today ";

                    $result_total = mysqli_query($conn, $sql_total);
                    $row_total = mysqli_fetch_assoc($result_total);
                    $total_pages = ceil($row_total['total'] / $results_per_page);


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
                    // nav show 5 fetch 
                    ?>


                    <div id="carouselExampleIndicators" class="carousel slide col-4 rounded" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="img\poster 1.png" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-item">
                                <img src="img\poster 2.png" class="d-block w-100" alt="...">
                            </div>
                            <div class="carousel-indicators">
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>

                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                        <div class="py-2 mt-3" style="background :none;">
                            <h6 class="mt-2" style="margin-bottom: 0;"><a href="docs\rulesforstudents.pdf" target="_blank">
                                    <img class="rounded" src="img\rulesForStudent.png" alt="..">
                                </a></h6>
                            <h6 class="mt-2" style="margin-bottom: 0;"><a href="docs\guide_for_students.pdf" target="_blank">
                                    <img class="rounded" src="img\linkGuideForStudents.png" alt="..">
                                </a></h6>
                            <h6 class="mt-2" style="margin-bottom: 0;"><a href="http://www2.chainat.ac.th/webpage/03personal.php?mid=4.1" target="_blank">
                                    <img class="rounded" src="img\personal3.png" alt="..">
                                </a></h6>
                            <h6 class="mt-2" style="margin-bottom: 0;"><a href="http://www2.chainat.ac.th/webpage/index01.php" target="_blank">
                                    <img class="rounded" src="img\tcweblinkicon.png" alt="..">
                                </a></h6>
                        </div>

                    </div>
                    <div class="container col-8 rounded shadow py-3" style="background-color:darksalmon;">
                        <?php

                        if ($row_total['total'] == 0) {
                            echo '<h6 style="text-align: center;">ไม่พบกิจกรรมที่เข้าร่วมได้ในขณะนี้</h6>';
                        } else if ($row_total['total']) {
                            echo '<h6 style="text-align: center;">ขณะนี้มีทั้งหมด ' . $row_total['total'] . ' กิจกรรม</h6>';
                        }
                        while ($row = mysqli_fetch_array($result)) {
                        ?>
                            <div class="card mt-3" style="background-color: whitesmoke; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
                            <small>ID. <?php echo $row['id'];; ?></small>
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
                                        <small class="form-text text-muted text-break"><?= $aEnd  ?></small>
                                    </div>
                                    <div class="mb-3 col-4">
                                        <label for="activity_date" class="form-label" style="font-weight: bold;">วันที่เริ่มกิจกรรม</label>
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
                                <!-- ปุ่มสมัครกิจกรรม -->
                                <?php
                                if ($_SESSION['userlevel'] == '1') { ?>
                                    <form action="registration.php" method="post">
                                        <button type="button" class="btn mb-3 mt-2 rounded-pill bg-success text-white" data-bs-toggle="modal" data-bs-target="#confirm-submit<?= $row['id'] ?>">ลงทะเบียน</button>
                                        <div class="modal fade" id="confirm-submit<?= $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">ยืนยันการลงทะเบียน</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-danger">คำเตือน</p>
                                                        <p>หากไม่เข้ารับการแก้กิจกรรมตามเวลาที่กำหนด นักศึกษาจะได้รับการลงโทษ</p>
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
                                <?php
                                }
                                ?>
                                <!-- ปุ่มสมัครกิจกรรม -->




                                <!-- ปุ่มลบ -->
                                <?php
                                if ($_SESSION['userlevel'] == '3') { ?>

                                    <form action="del_activity.php" method="post">
                                        <button type="button" class="btn btn-sm bg-danger text-white" data-bs-toggle="modal" data-bs-target="#confirm-submit<?= $row['id'] ?>">ลบกิจกรรม</button>
                                        <div class="modal fade" id="confirm-submit<?= $row['id'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">ต้องการลบกิจกรรมหรือไม่</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p class="text-danger">คำเตือน</p>
                                                        <p>หากลบกิจกรรมแล้วจะไม่สามารถกู้คืนได้</p>
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

                                    <form action="edit.php" method="post">

                                        <input type="hidden" name="activity_id" value="<?= $row['id'] ?>">
                                        <button type="submit" class="btn mb-3 mt-2 btn-sm bg-secondary text-white">แก้ไขกิจกรรม</button>
                                        <a href='view_applicant.php?id=<?= $row['id'] ?>' class='btn btn-success btn-sm  mb-3 mt-2'>ดูรายชื่อผู้สมัคร</a>


                                    </form>
                                <?php
                                }
                                ?>
                                <!-- ปุ่มลบ -->



                            </div>


                        <?php

                        }
                        mysqli_close($conn);
                        ?>
                    </div>


                </div> <!--- row --->

            </div> <!--- container for row--->
            <!--- end --->


            <!--- footer --->
            <div class="container col-11 rounded" style="background-color:darksalmon;">
                <footer class="mt-3 p-2">
                    <div class="row">
                        <div class="text-left mt-2">
                            <img src="img\gps2.png" alt="Footer Image" class="img-fluid" style="width: 30px;">
                            <p style="display: inline-block;">
                                วิทยาลัยเทคนิคชัยนาท ที่อยู่ 336 ถ.พหลโยธิน ต.บ้านกล้วย อ.เมือง จ.ชัยนาท 17000 Tel : (056)411276 , Fax : (056)411847</p>
                        </div>
                    </div>
                </footer>


            </div>
            <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
        </body>

        </html>
    <?php } ?>