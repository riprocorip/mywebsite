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
                        <input type="text" class="form-control" placeholder="กรุณาใส่ชื่อของนักศึกษาหรือรหัสนักศึกษา" name="search_query" style="height: 35px;">
                        <button type="submit" class="btn btn-success" style="height: 35px;">ค้นหา</button>
                    </div>
                </form>
            </div>

            <?php

            include 'database.php';
            // Function to sanitize input (prevent SQL injection)

            function sanitize($input)
            {
                global $conn;
                $sanitizedInput = mysqli_real_escape_string($conn, $input);
                return $sanitizedInput;
            }


            // Check if the form is submitted
            if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['search_query'])) {
                // Get the search query from the form
                $search_query = sanitize($_POST['search_query']);
            } elseif (isset($_GET['username'])) {
                // Use the username from the GET method as the default search value
                $search_query = sanitize($_GET['username']);
            } else {
                // Display a message if the search field is empty
                echo "กรุณาใส่ข้อมูล";
            }
            // Query to fetch data based on user_id or name
            $buttonText = ($_SESSION['userlevel'] == '2') ? 'ยื่นคำร้องมอบหมายชั่วโมง' : 'มอบหมายชั่วโมง';

            if (!empty($search_query)) {
                // Query to fetch data based on user_id or name
                $sql = "SELECT * FROM users WHERE username = '$search_query' OR name LIKE '%$search_query%'";
                $result = $conn->query($sql);

                $buttonText = ($_SESSION['userlevel'] == '2') ? 'ยื่นคำร้องมอบหมายชั่วโมง' : 'มอบหมายชั่วโมง';

                // Check if any results are found
                if ($result->num_rows > 0) {
                    // Output data for each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='panel panel-default'>";
                        echo "<div class='panel-heading'>";
                        echo "<h4 class='panel-title'>รหัสนักศึกษา: " . $row['username'] . "</h4>";
                        echo "</div>";
                        echo "<div class='panel-body'>";
                        echo "<p>ชื่อ-นามสกุล: " . $row['name'] . "</p>";
                        echo "<p>แผนก: " . $row['department'] . "</p>";
                        echo "<p>จำนวนชั่วโมงที่ต้องรับผิดชอบ: " . $row['hours'] . " ชั่วโมง</p>";
                        // Link to another PHP file
                        echo "<a href='misconduct_history.php?username=" . $row['username'] . "' class='btn btn-danger btn-sm'>ตรวจสอบประวัติการกระทำผิด</a>";

                        // Add spacing between the two buttons
                        echo "<div class='mb-2'></div>";

                        echo "<a href='assign_hours.php?username=" . $row['username'] . "' class='btn btn-danger btn-sm'>$buttonText</a>";
                        if ($_SESSION['userlevel'] == '3') {
                            echo "<form id='removeHoursForm' action='remove_hours.php' method='post'>";
                            echo "<div class='mb-3 col-4'>";
                            echo "<label for='hours_to_remove' class='form-label'>จำนวนชั่วโมงที่ต้องการลด</label>";
                            echo "<input type='number' class='form-control' id='hours_to_remove' name='hours_to_remove' required>";
                            echo "</div>";
                            echo "<input type='hidden' name='username' value='" . $row['username'] . "'>";

                            // Confirm button with modal trigger
                            echo "<button type='button' class='btn btn-success btn-sm' data-bs-toggle='modal' data-bs-target='#confirmRemoveModal'>ดำเนินการลดชั่วโมง</button>";

                            // Confirmation Modal
                            echo "<div class='modal fade' id='confirmRemoveModal' tabindex='-1' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
                            echo "<div class='modal-dialog'>";
                            echo "<div class='modal-content'>";
                            echo "<div class='modal-header'>";
                            echo "<h5 class='modal-title' id='exampleModalLabel'>ยืนยันการลดชั่วโมง</h5>";
                            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
                            echo "</div>";
                            echo "<div class='modal-body'>";
                            echo "<p class='text-danger'>คำเตือน</p>";
                            echo "<p>การลดชั่วโมงจะถูกบันทึกและไม่สามารถเรียกคืนได้</p>";
                            echo "</div>";
                            echo "<div class='modal-footer'>";
                            echo "<button type='submit' class='btn btn-success'>ยืนยัน</button>";
                            echo "<button type='button' class='btn btn-danger' data-bs-dismiss='modal'>ยกเลิก</button>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                            echo "</form>";
                        }
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "ไม่พบข้อมูลของนักศึกษาดังกล่าว";
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