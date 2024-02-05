<?php include 'database.php';
session_start();

if (!$_SESSION['usercode'] || $_SESSION['userlevel'] == '1') {
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
        <title>ค้นหาข้อมูลนักศึกษา</title>

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


            .panel {
                margin-bottom: 20px;
                border: 1px solid #ddd;
                border-radius: 8px;
            }

            .panel-heading {
                background-color: #f5f5f5;
                padding: 10px;
            }

            .panel-title {
                margin-bottom: 0;
                font-size: 18px;
            }

            .panel-body {
                padding: 20px;
            }
        </style>

    </head>

    <body>

        <div class="container mt-3 pt-4 shadow min-vh-50 py-2 col-8" style="background-color: whitesmoke; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
            <a class="btn mb-3 btn-sm rounded-pill bg-secondary text-white" href="activities.php" role="button">ย้อนกลับ</a>
            <div class='mb-3 col-6'>
                <!-- ค้นหานักศึกษา -->
                <form action="#" method="post" class="mb-3" id="searchForm">
                    <div class="input-group">
                        <input type="text" class="form-control-sm" placeholder="ค้นหากิจกรรมจาก ID" name="search_query" style="height: 35px;">
                        <button type="submit" class="btn btn-success" style="height: 35px;">ค้นหา</button>
                    </div>
                </form>
            </div>

            <?php
            include 'database.php';

            function sanitize($input)
            {
                global $conn;
                $sanitizedInput = mysqli_real_escape_string($conn, $input);
                return $sanitizedInput;
            }

            if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search_query'])) {
                $search_query = sanitize($_POST['search_query']);
            } else {
                echo "กรุณากรอกไอดีของกิจกรรมที่ต้องการจะตรวจสอบ";
            }

            if (!empty($search_query)) {
                // Query to fetch data based on user_id or name
                $sql = "SELECT * FROM activities WHERE id = '$search_query'";
                $result = $conn->query($sql);

                if ($result) {
                    if ($result->num_rows > 0) {
                        // Redirect to view_applicant.php with the search query as a parameter
                        header("Location: view_applicant2.php?id=" . $search_query);
                        exit(); // Ensure that no further code is executed after the header redirect
                    } else {
                        echo "ไม่พบ ID กิจกรรมดังกล่าว กรุณากรอกไอดีของกิจกรรมที่ต้องการจะตรวจสอบใหม่";
                    }
                } else {
                    // Handle the SQL query error
                    echo "Error executing query: " . $conn->error;
                }
            }



            ?>
            <script>
                function showPenaltyForm(username) {
                    var formId = 'penaltyForm_' + username;
                    var form = document.getElementById(formId);

                    // Toggle the visibility of the form
                    if (form.style.display === 'none') {
                        form.style.display = 'block';
                    } else {
                        form.style.display = 'none';
                    }
                }
            </script>


            <?php


            // Close the database connection
            $conn->close();

            ?>

        </div>

        <script>
            $(document).ready(function() {
                // Attach input event listener to the textarea
                $('#exampleFormControlTextarea1').on('input', function() {
                    // Get the current character count
                    var currentLength = $(this).val().length;
                    // Calculate remaining characters
                    var remaining = 1000 - currentLength;
                    // Update the character count display
                    $('#characterCount').text('จำนวนตัวอักษรที่กรอกได้: ตัวอักษร' + remaining);
                });
            });
        </script>


        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    </body>

    </html>
<?php } ?>