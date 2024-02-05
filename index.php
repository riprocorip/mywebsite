<?php
session_start();

if (isset($_SESSION['usercode'])) {
    header("Location: activities.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-dtGfTbBMt1I5a0+5FPOCmqL4t5stHGo+AdeI6uY4CmKLOw5Yfr7C0czNlFI3GDEcb8ZO4eMCyYYo7jqgc63ifw==" crossorigin="anonymous" />


    <title>เข้าสู่ระบบ</title>

    <style>
        body {
            background-image: url('img/R.jpg');
            background-size: cover;
            background-position: center;
            height: 80vh;
            margin: 0;
            font-size: 90%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</head>

<body>

    <?php if (isset($_SESSION['success'])) : ?>
        <div class="success">
            <?php
            echo $_SESSION['success'];
            ?>
        </div>
    <?php endif; ?>



    <div class="container mt-5 col-md-4 justify-content-center">
        <?php if (isset($_SESSION['error'])) : ?>
            <div class="alert alert-danger text-center" role="alert">
                ข้อมูลไม่ถูกต้อง กรุณากรอกข้อมูลใหม่อีกครั้ง
            </div>
        <?php endif; ?>

        <div class="row justify-content-center shadow">
            <div class="card">
                <div class="card-header text-center">
                    <h5><strong>เข้าสู่ระบบ</strong></h5>
                </div>
                <div class="card-body">

                    <form action="login.php" method="post">
                        <div class="form-group">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username..." required>
                        </div>
                        <div class="form-group mt-2">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password..." required>
                        </div>
                        <div class="form-group mt-3">
                            <label for="isStudent">เป็นนักศึกษาใช่หรือไม่:</label>
                            <input type="checkbox" id="isStudent" name="isStudent" onchange="toggleDepartmentInput()">
                        </div>

                        <div class="form-group mt-3" id="departmentInput" style="display: none;">
                            <label for="department">กรุณาเลือกแผนก</label>
                            <select class="form-control-sm" id="department" name="department" placeholder="Enter your username" required>
                                <?php
                                include('database.php');

                                $result = $conn->query("SELECT DISTINCT department FROM users WHERE department IS NOT NULL AND department <> ''");

                                while ($row = $result->fetch_assoc()) {
                                    echo "<option value='" . $row['department'] . "'>" . $row['department'] . "</option>";
                                }

                                $conn->close();
                                ?>
                            </select>
                        </div>



                        <script>
                            function toggleDepartmentInput() {
                                var departmentInput = document.getElementById('departmentInput');
                                var isStudentCheckbox = document.getElementById('isStudent');

                                if (isStudentCheckbox.checked) {
                                    departmentInput.style.display = 'block';
                                } else {
                                    departmentInput.style.display = 'none';
                                }
                            }
                        </script>


                        <button type="submit" class="btn btn-success btn-sm btn-block mx-auto mt-3 d-block rounded">เข้าสู่ระบบ</button>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


</body>

</html>


<?php

if (isset($_SESSION['success']) || isset($_SESSION['error'])) {
    session_destroy();
}

?>