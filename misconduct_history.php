<?php include 'database.php';
session_start();

if (!$_SESSION['usercode'] || $_SESSION['userlevel'] == '1') {
    header("Location: index.php");
} else {
    $username = $_GET['username'];
    // Retrieve user information from the database
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $userRow = $result->fetch_assoc();
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
        <title>ประวัติการกระทำผิด</title>

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
                background-color:black;
                color: #fff;
            }

            /* Active State for Pagination Link */
            .pagination .active a {
                background-color:#b9d9fd;
                color: #fff;
            }

            /* Previous and Next Button Styles */
            .pagination .page-link {
                color: black;
                border: 1px solid #007bff;
            }

            /* Hover State for Previous and Next Buttons */
            .pagination .page-link:hover {
                background-color:#b9d9fd;
                color: #fff;
            }
        </style>


    </head>

    <body>

        <div class="container mt-3 pt-4 shadow min-vh-50 py-2 col-8" style="background-color: whitesmoke; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
            <a class="btn mb-3 rounded-pill btn-sm bg-secondary text-white" href="student_search.php?username=<?php echo $username; ?>" role="button">ย้อนกลับ</a>

            <div class="mb-3">
                <p>ชื่อ-นามสกุล: <?php echo $userRow['name']; ?></p>
                <p>แผนก: <?php echo $userRow['department']; ?></p>
                <p>รหัสนักศึกษา: <?php echo $userRow['username']; ?></p>
                <p>จำนวนชั่วโมง: <?php echo $userRow['hours']; ?></p>
            </div>
        </div>

        <?php
        // nav show 5 fetch        
        $results_per_page = 5;

        // Check if the page parameter is set in the URL, otherwise set it to 1
        $page = isset($_GET['page']) ? $_GET['page'] : 1;

        // offset cal
        $offset = ($page - 1) * $results_per_page;

        $sql_total = "SELECT COUNT(*) AS total FROM misconduct 
        WHERE username = '$username'";

        $result_total = mysqli_query($conn, $sql_total);
        $row_total = mysqli_fetch_assoc($result_total);
        $total_pages = ceil($row_total['total'] / $results_per_page);

        // SQL query for fetching misconduct records with LIMIT and OFFSET
        $misconductQuery = "SELECT * FROM misconduct WHERE username = '$username' AND status = '1' ORDER BY misconduct_date DESC LIMIT $offset, $results_per_page";

        // Execute the query
        $misconductResult = $conn->query($misconductQuery);


        echo '<nav aria-label="Page navigation" class="mt-3 d-flex justify-content-center">';
        echo '  <ul class="pagination">';
        if ($total_pages > 1) {
            // Previous button
            if ($page > 1) {
                echo '<li class="page-item"><a class="page-link" href="?username=' . $username . '&page=' . ($page - 1) . '" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
            }

            // Numbered pages
            for ($i = 1; $i <= $total_pages; $i++) {
                echo '<li class="page-item';
                echo ($i == $page) ? ' active' : '';
                echo '"><a class="page-link" href="?username=' . $username . '&page=' . $i . '">' . $i . '</a></li>';
            }

            // Next button
            if ($page < $total_pages) {
                echo '<li class="page-item"><a class="page-link" href="?username=' . $username . '&page=' . ($page + 1) . '" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
            }
        }
        echo '  </ul>';
        echo '</nav>';
        ?>

        <div class="container mt-3 pt-4 shadow min-vh-50 py-2 col-8">

            <?php if ($misconductResult->num_rows > 0) : ?>
                <h3><strong>ประวัติการกระทำผิด</strong></h3>
                <?php while ($misconductRow = $misconductResult->fetch_assoc()) : ?>
                    <div class="card mb-3" style="background-color: whitesmoke; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
                        <div class="card-body">
                            <p class="card-text">รายละเอียดการกระทำผิด: <?php echo $misconductRow['misconduct_detail']; ?></p>
                            <p class="card-text">จำนวนชั่วโมงที่มอบหมาย: <?php echo $misconductRow['misconduct_hours']; ?> ชั่วโมง</p>
                            <?php
                                        //date format
                                        
                                        $misconduct_date = date('j M Y', strtotime( $misconductRow['misconduct_date']));

                                    ?>
                            <p class="card-text">วันที่กระทำผิด: <?php echo  $misconduct_date ?></p>

                        </div>
                        <div class="row">

                            <?php


                            $imagePaths = explode(',', $misconductRow['image_paths']);
                            if (!empty($imagePaths[0])) :
                                foreach ($imagePaths as $imagePath) :
                            ?>
                                    <img src="<?php echo $imagePath; ?>" class="card-img-top" alt="Misconduct Image" style="width: 300px; height: 200px; margin-bottom: 10px; padding: 10px;">
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                <?php endwhile; ?>

            <?php else : ?>
                <p>ไม่พบประวัติการกระทำผิด</p>
            <?php endif; ?>
        </div>





        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    </body>

    </html>
<?php } ?>