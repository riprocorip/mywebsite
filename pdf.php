<?php
require('pdf/fpdf.php');
session_start();
include('database.php');


if (isset($_GET['activity_id'])) {
    $activity_id = $_GET['activity_id'];

    $query = "SELECT * FROM activities WHERE id = $activity_id";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        // Fetch user data from the database
        $usercode = $_SESSION['usercode'];
        $query = "SELECT * FROM users WHERE username = '$usercode'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            $userData = mysqli_fetch_assoc($result);
        }

        // Create PDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->AddFont('sarabun', '', 'THSarabunNew.php');
        $pdf->SetFont('sarabun', '', 12);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'Activity ID : '. $row['id']), 0, 0,    'R');
        $pdf->SetFont('sarabun', '', 16);
        $pdf->Ln();
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'แบบฟอร์มเข้าร่วมกิจกรรมลดชั่วโมงบำเพ็ญประโยชน์'), 0, 0,    'C');
        $pdf->Ln();
        $pdf->Cell(32);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "ชื่อผู้ขอเข้าร่วมกิจกรรม : " . $userData['name']));
        $pdf->Ln();
        $pdf->Cell(32);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "รหัสนักศึกษา : " . $userData['username'] ));
        $pdf->Ln();
        $pdf->Cell(32);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "แผนก " . $userData['department'] ));
        $pdf->Ln();
        $pdf->Cell(32);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "ชื่อกิจกรรมที่เข้าร่วม .................................................................................................................."));
        $pdf->Ln();
        $pdf->Cell(16);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "ชื่อผู้จัดกิจกรรม ...................................................... วันที่(เริ่มกิจกรรม) ......................................................"));
        $pdf->Ln();
        $pdf->Cell(16);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "จำนวนชั่วโมงของกิจกรรม ...................................... ผลการเข้าร่วมกิจกรรม .............................................." ));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell(32);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "                                                                                   ลงชื่อนักศึกษา"));
        $pdf->Ln();
        $pdf->Cell(20);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "                                                                                 ....................................................." ));
        $pdf->Ln();
        $pdf->Cell(20);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "                                                                                 (...................................................)" ));
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell(32);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "                                                                                ลงชื่อผู้จัดกิจกรรม"));
        $pdf->Ln();
        $pdf->Cell(20);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "                                                                                 ....................................................." ));
        $pdf->Ln();
        $pdf->Cell(20);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "                                                                                 (...................................................)" ));      
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Cell(32);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "                                                                                ลงชื่อฝ่ายปกครอง"));
        $pdf->Ln();
        $pdf->Cell(20);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "                                                                                 ....................................................." ));
        $pdf->Ln();
        $pdf->Cell(20);
        $pdf->Cell(0, 10, iconv('utf-8', 'cp874',  "                                                                                 (...................................................)" ));      



        $pdf->Output();
    } else {
        // Handle database query error
        echo "Error fetching data from the database: " . mysqli_error($conn);
    }
} else {
    header("Location: index.php");
}
