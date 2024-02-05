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
        <title>มอบหมายชั่วโมง</title>

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

        <div class="container mt-3 pt-4 shadow min-vh-50 py-2 col-8" style="background-color: whitesmoke; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
            <a class="btn mb-3 btn-sm rounded-pill bg-secondary text-white" href="student_search.php" role="button">ยกเลิก</a>
            <div class="mb-3">
                <p>ชื่อ-นามสกุล: <?php echo $userRow['name']; ?></p>
                <p>แผนก: <?php echo $userRow['department']; ?></p>
                <p>รหัสนักศึกษา: <?php echo $userRow['username']; ?></p>
                <p>จำนวนชั่วโมง: <?php echo $userRow['hours']; ?></p>
            </div>
        </div>

        <div class="container mt-3 pt-4 shadow min-vh-50 py-2 col-8" style="background-color: whitesmoke; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">

            <form action="#" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="misconduct_detail" class="form-label">รายละเอียดการกระทำผิด</label>
                    <textarea class="form-control" id="misconduct_detail" name="misconduct_detail" rows="5" maxlength="500" oninput="updateCharacterCount()"></textarea>
                    <small id="characterCount" class="form-text text-muted">จำนวนตัวอักษรที่กรอกได้: <span id="currentCount">500</span> ตัวอักษร</small>
                </div>
                <div class="mb-3 col-2">
                    <label for="misconduct_hours" class="form-label">จำนวนชั่วโมง</label>
                    <input type="number" class="form-control" id="misconduct_hours" name="misconduct_hours" required>
                </div>
                <div class="mb-3 col-2">
                    <label for="misconduct_date" class="form-label">วันที่กระทำผิด</label>
                    <input class="form-control-sm" id="misconduct_date" name="misconduct_date" type="date" required>

                </div>
                <div class="mb-3 col-4">
                    <label for="misconduct_images" class="form-label">แนบรูปภาพหลักฐาน (เลือกได้หลายรูป)</label>
                    <input type="file" class="form-control" id="misconduct_images" name="misconduct_images[]" accept="image/*" multiple>
                </div>
                <div id="image-preview" class="mb-3 col-4"></div> <!-- div for image preview -->
                <style>
                    #image-preview img {
                        max-width: 50%;
                        height: auto;
                        margin-top: 10px;
                    }

                    #misconduct_images {
                        padding: 5px;

                        height: auto;

                    }

                    #misconduct_hours {
                        padding: 5px;

                        height: auto;

                    }
                </style>

                <script>
                    $(document).ready(function() {
                        // Handle file input change
                        $('#misconduct_images').on('change', function() {
                            displayImagePreview(this);
                        });

                        // Function to display image preview
                        function displayImagePreview(input) {
                            var previewContainer = $('#image-preview');
                            previewContainer.empty(); // Clear previous previews

                            // Check if any file is selected
                            if (input.files && input.files.length > 0) {
                                // Loop through each selected file
                                for (var i = 0; i < input.files.length; i++) {
                                    var reader = new FileReader();

                                    reader.onload = function(e) {
                                        // Create an image element and set its source
                                        var image = $('<img>').attr('src', e.target.result);
                                        previewContainer.append(image);
                                    };

                                    // Read the file as a data URL
                                    reader.readAsDataURL(input.files[i]);
                                }
                            }
                        }
                    });
                </script>


                <?php
                if ($_SESSION['userlevel'] == '2') {
                ?>
                    <button type="submit" class="btn mb-3 mt-2 btn-sm rounded-pill bg-success text-white" onclick="confirmSubmit()">ยืนร้องขอกิจกรรม</button>
                <?php
                }

                if ($_SESSION['userlevel'] == '3') {
                ?>
                    <button type="submit" class="btn mb-3 mt-2 btn-sm rounded-pill bg-success text-white" onclick="confirmSubmit()">ยืนยัน</button>
                <?php
                }
                ?>
            </form>
        </div>

        <?php

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Get form data
            $username = $_GET['username'];
            $misconduct_detail = $_POST['misconduct_detail'];
            $misconduct_hours = $_POST['misconduct_hours'];
            $misconduct_date = $_POST['misconduct_date'];

            // Validate and sanitize form data
            $username = mysqli_real_escape_string($conn, $username);
            $misconduct_detail = mysqli_real_escape_string($conn, $misconduct_detail);
            $misconduct_hours = intval($misconduct_hours); // Ensure it's an integer
            $misconduct_date = mysqli_real_escape_string($conn, $misconduct_date);

            // Handle image uploads
            $uploadedImages = [];
            if (!empty($_FILES['misconduct_images']['name'][0])) {
                $uploadDir = 'misconduct_images_uploads/'; // Specify your upload directory
                foreach ($_FILES['misconduct_images']['name'] as $key => $imageName) {
                    $targetFile = $uploadDir . basename($imageName);
                    if (move_uploaded_file($_FILES['misconduct_images']['tmp_name'][$key], $targetFile)) {
                        $uploadedImages[] = $targetFile;
                    }
                }
            }

            // Prepare and execute the SQL query to insert data
            $imagePaths = implode(",", $uploadedImages);
            $sql = "INSERT INTO misconduct ( username , misconduct_detail, misconduct_hours, misconduct_date, image_paths)
                    VALUES ( '$username' ,'$misconduct_detail', $misconduct_hours, '$misconduct_date', '$imagePaths')";


            if ($conn->query($sql) === TRUE) {
                echo '<script>alert("ดำเนินการสำเร็จ"); </script>';
                echo '<script>window.location.href = "student_search.php";</script>';
            } else {
                echo '<script>alert("Error: ' . $sql . '<br>' . $conn->error . '");</script>';
                echo '<script>window.location.href = "student_search.php";</script>';
            }

            // Close the database connection
            $conn->close();
        }
        ?>


        <script>
            function updateCharacterCount() {
                var textarea = document.getElementById('misconduct_detail');
                var countSpan = document.getElementById('currentCount');
                var maxLength = 500;

                var currentLength = textarea.value.length;
                var remainingLength = maxLength - currentLength;

                countSpan.textContent = remainingLength;

                // Optionally, you can add some visual indication or disable the textarea if the limit is reached
                if (remainingLength < 0) {
                    // Example: add a red border to indicate exceeding the limit
                    textarea.style.border = '2px solid red';
                } else {
                    // Example: remove the red border if within the limit
                    textarea.style.border = '';
                }
            }
        </script>


        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

    </body>

    </html>
<?php } ?>