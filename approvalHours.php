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
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['approve_misconduct'])) {
            $misconductId = $_POST['misconduct_id'];
            $updateQuery = "UPDATE misconduct SET status = '1' WHERE id = $misconductId";
            $result = mysqli_query($conn, $updateQuery);

            if ($result) {
                header('Location: approvalHours.php');
                exit;
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        } elseif (isset($_POST['disapprove_misconduct'])) {
            $misconductId = $_POST['misconduct_id'];
            $updateQuery = "UPDATE misconduct SET status = '2' WHERE id = $misconductId";
            $result = mysqli_query($conn, $updateQuery);

            if ($result) {
                header('Location: approvalHours.php');
                exit;
            } else {
                echo "Error updating record: " . mysqli_error($conn);
            }
        }
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
        <title>คำร้องขอเพิ่มชั่วโมง</title>

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
        <!-- คำร้องเพิ่มชั่วโมงจากการกระทำผิดของนักศึกษา -->
        <div class="container mt-3 pt-4 min-vh-50 py-2 col-10 rounded" style="background-color:darksalmon;">
            <a class="btn mb-3 btn-sm rounded-pill bg-secondary text-white" href="activities.php" role="button">ย้อนกลับ</a>
            <h5 class="text-center mb-3" data-wow-delay="0.1s"><strong>คำร้องเพิ่มชั่วโมงจากการกระทำผิดของนักศึกษา</strong></h5>



            <!-- เรียกข้อมูลของกิจกรรมมาแสดงทั้งหมด -->
            <?php
            $results_per_page = 3;

            $sql_total = "SELECT COUNT(*) AS total FROM misconduct
                                      WHERE status = '0'"; // ที่ยังไม่ได้อนุมัติ
            $result_total = mysqli_query($conn, $sql_total);
            $row_total = mysqli_fetch_assoc($result_total);
            $total_pages = ceil($row_total['total'] / $results_per_page);

            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            $offset = ($page - 1) * $results_per_page;

            $sql = "SELECT * FROM misconduct
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
            while ($misconductRow = mysqli_fetch_array($result)) {
            ?>

                <div class="card mb-3" style="background-color: whitesmoke; border: 1px solid #ddd; border-radius: 8px; padding: 15px; ">
                    <div class="card-body">
                        <p class="card-text">รายละเอียดการกระทำผิด: <?php echo $misconductRow['misconduct_detail']; ?></p>
                        <p class="card-text">จำนวนชั่วโมงที่มอบหมาย: <?php echo $misconductRow['misconduct_hours']; ?> ชั่วโมง</p>
                        <?php
                                        //date format
                                        
                                        $misconduct_date = date('j M Y', strtotime( $misconductRow['misconduct_date']));

                                    ?>
                        <p class="card-text">วันที่กระทำผิด: <?php echo $misconduct_date ?></p>
                        <!-- Add additional information as needed -->
                        <div class="d-flex">

                            <form action="#" method="post" onsubmit="return confirm('ยืนยันคำขอร้องเพิ่มชั่วโมงใช่หรือไม่?')">
                                <input type="hidden" name="misconduct_id" value="<?php echo $misconductRow['id']; ?>">
                                <button type="submit" name="approve_misconduct" class="btn btn-primary btn-sm mt-3 pt-2">ยืนยันคำขอร้องเพิ่มชั่วโมง</button>
                            </form>

                            <form action="#" method="post" onsubmit="return confirm('ไม่อนุมัติใช่หรือไม่?')">
                                <input type="hidden" name="misconduct_id" value="<?php echo $misconductRow['id']; ?>">
                                <button type="submit" name="disapprove_misconduct" class="btn btn-danger btn-sm mt-3 pt-2">ไม่อนุมัติ</button>
                            </form>


                        </div>

                    </div>

                    <?php


                    $imagePaths = explode(',', $misconductRow['image_paths']);
                    if (!empty($imagePaths[0])) :
                        foreach ($imagePaths as $imagePath) :
                    ?>
                            <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="Misconduct Image" style="width: 300px; height: 200px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin-bottom: 10px; padding: 10px;">
                    <?php
                        endforeach;
                    endif;
                    ?>



                </div>


            <?php
            }



            ?>

        </div>

        <!-- Activities Table  END -->

        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    </body>

    </html>
<?php } ?>