<?php include 'database.php';
session_start();

if (!$_SESSION['usercode'] || $_SESSION['userlevel'] == '1') {
    header("Location: index.php");
} else {

    $destinationURL = ($_SESSION['userlevel'] == '2') ? 'profile_teacher.php' : 'activities.php';
    $linkText = ($_SESSION['userlevel'] == '2') ? 'ย้อนกลับ' : 'ยกเลิก';

    // Check if the activity_id is set and not empty
    // Check if activity_id is provided through either POST or GET
    if (isset($_POST['activity_id']) && !empty($_POST['activity_id'])) {
        $activity_id = mysqli_real_escape_string($conn, $_POST['activity_id']);
    } elseif (isset($_GET['id']) && !empty($_GET['id'])) {
        $activity_id = mysqli_real_escape_string($conn, $_GET['id']);
    } else {
        // Handle the case when activity_id is not provided
        echo "Error: Activity ID not provided.";
        // You may want to redirect the user or display an error message
        exit();
    }

    // Construct the SQL query
    $sql = "SELECT * FROM activities WHERE id = $activity_id";

    // Execute the query
    $result = mysqli_query($conn, $sql);

    // Check if the query was successful
    if ($result) {
        // Fetch the data as an associative array
        $row = mysqli_fetch_assoc($result);

        // Populate form placeholders with the retrieved data
        $name = $row['name'];
        $detail = $row['detail'];
        $startDate = $row['startDate'];
        $organizerName = $row['organizerName'];
        $telNumber = $row['telNumber'];
        $hours = $row['hours'];
        $totalAdmissions = $row['totalAdmissions'];
    } else {
        // Handle the error if the query fails
        echo "Error: " . mysqli_error($conn);
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



        <!--หัวข้อหน้าเว็บ-->
        <title>แก้ไขข้อมูลกิจกรรม</title>

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

            <a class="btn mb-3 btn-sm rounded-pill bg-secondary text-white" href="<?php echo $destinationURL; ?>" role="button"><?php echo $linkText; ?></a>
            <form action="create_and_edit_activity.php" method="post">
                <div class="mb-3 col-4">
                    <label for="activity_name" class="form-label">ชื่อกิจกรรม</label>
                    <input type="text" class="form-control" id="activity_name" name="activity_name" placeholder="" value="<?php echo $name ?>" required>
                </div>
                <div class="mb-3">
                    <label for="activity_detail" class="form-label">รายละเอียดของกิจกรรม</label>
                    <textarea class="form-control" id="activity_detail" name="activity_detail" rows="5" maxlength="500" oninput="updateCharacterCount()"><?php echo $detail ?></textarea>
                    <small id="characterCount" class="form-text text-muted">จำนวนตัวอักษรที่กรอกได้: <span id="currentCount">500</span> ตัวอักษร</small>
                </div>
                <div class="row">


                    <div class="mb-3 col-2">
                    <label for="activity_date" class="form-label" style="font-weight: bold;">วันที่เริ่มกิจกรรม</label>
                        <input class="form-control-sm" id="activity_date" name="activity_date" type="date" value="<?= htmlspecialchars($startDate) ?>" />
                    </div>
                    <div class="mb-3 col-2">
                    <label for="activity_date" class="form-label" style="color: green;">เริ่มรับสมัคร</label>
                        <input class="form-control-sm" id="applicationStart" name="applicationStart" type="date" value="<?= htmlspecialchars($row['applicationStart']) ?>" />

                    </div>
                    <div class="mb-3 col-2">
                    <label for="activity_date" class="form-label" style="color: red;">ปิดรับสมัคร</label>
                        <input class="form-control-sm" id="applicationEnd" name="applicationEnd" type="date" value="<?= htmlspecialchars($row['applicationEnd']) ?>" />
                    </div>
                </div>
                <div class="row">
                    <div class="mb-3 col-4">
                        <label for="activity_organizerName" class="form-label">ชื่อผู้จัดกิจกรรม</label>
                        <input type="text" class="form-control" id="activity_organizerName" name="activity_organizerName" placeholder="" value="<?php echo $organizerName ?>" required>
                    </div>
                    <div class="mb-3 col-3">
                        <label for="activity_telNumber" class="form-label">เบอร์โทรศัพท์</label>
                        <input type="text" class="form-control" id="activity_telNumber" name="activity_telNumber" value="<?php echo $telNumber ?>" required>
                    </div>
                    <div class="mb-3 col-2">
                        <label for="activity_hours" class="form-label">จำนวนชั่วโมงที่ลด(ชม.)</label>
                        <input type="number" class="form-control" id="activity_hours" name="activity_hours" placeholder="" value="<?php echo $hours ?>" required>
                    </div>
                    <div class="mb-3 col-2">
                        <label for="activity_totalAdmissions" class="form-label">รับทั้งหมด(คน)</label>
                        <input type="number" class="form-control" id="activity_totalAdmissions" name="activity_totalAdmissions" placeholder="" value="<?php echo $totalAdmissions ?>" required>
                    </div>
                </div>
                <?php
                if ($_SESSION['userlevel'] == '2') {
                ?>
                    <input type="hidden" name="activity_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn mb-3 mt-2 btn-sm rounded-pill bg-success text-white" onclick="confirmSubmit()">ส่งคำร้องขอแก้ไขกิจกรรม</button>
                    <p><strong>*หมายเหตุ* การส่งคำร้องแก้ไขงานกิจกรรมจำเป็นต้องได้รับการอนุมัติจากฝ่ายปกครองหรือแอดมินอีกครั้ง **</strong></p>
                <?php
                }

                if ($_SESSION['userlevel'] == '3') {
                ?>
                    <input type="hidden" name="activity_id" value="<?= $row['id'] ?>">
                    <button type="submit" class="btn mb-3 mt-2 btn-sm rounded-pill bg-success text-white" onclick="confirmSubmit()">บันทึก</button>
                <?php
                }
                ?>
            </form>
        </div>

        <script>
            function updateCharacterCount() {
                var textarea = document.getElementById('activity_detail');
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