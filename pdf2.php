<?php
require('pdf/fpdf.php');
session_start();
include('database.php');

if (isset($_GET['activity_id'])) {
    $activity_id = ($_GET['activity_id']);

    // Query to fetch activity details
    $activityDetailsQuery = "SELECT * FROM activities WHERE id = '$activity_id'";
    $activityDetailsResult = $conn->query($activityDetailsQuery);

    if ($activityDetailsResult->num_rows > 0) {
        $activityDetails = $activityDetailsResult->fetch_assoc();
        $activityName = $activityDetails['name'];
        $startDate = $activityDetails['startDate'];
        $totalApplicant = $activityDetails['currentApplicant'];
        $totalAdmissions = $activityDetails['totalAdmissions'];
        $organizerName = $activityDetails['organizerName'];
    }

    $startDate = date('j M Y', strtotime($activityDetails['startDate']));
    $aStart = date('j M Y', strtotime($activityDetails['applicationStart']));
    $aEnd = date('j M Y', strtotime($activityDetails['applicationEnd']));

    // Create PDF
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->AddFont('sarabun', '', 'THSarabunNew.php');
    $pdf->SetFont('sarabun', '', 12);
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'Activity ID : ' . $activityDetails['id']), 0, 0, 'R');
    $pdf->SetFont('sarabun', '', 18);
    $pdf->Ln();
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', $activityDetails['name']), 0, 0, 'L');
    $pdf->SetFont('sarabun', '', 16);
    $pdf->Ln();
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'วันที่เริ่มกิจกรรม: ' . $startDate), 0, 0, 'L');
    $pdf->Ln();
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'ระยะเวลารับสมัคร: ' .  $aStart . ' จนถึง ' .  $aEnd), 0, 0, 'L');
    $pdf->Ln();
    $pdf->Cell(0, 10, iconv('utf-8', 'cp874', 'ผู้จัดกิจกรรม: ' . $activityDetails['organizerName']), 0, 0, 'L');
    $pdf->Ln();

    // MAKE STUDENTS LIST
    $sql = "SELECT * FROM registrations
            INNER JOIN users ON registrations.user_code = users.username
            WHERE registrations.activity_id = '$activity_id'
            ORDER BY registrations.registration_time ASC";
    $result = $conn->query($sql);

    // Extracting data for the students list
    $studentsData = array();
    $counter=1;
    while ($row = $result->fetch_assoc()) {
        $studentsData[] = array(
            $counter, 
            $row['name'],
            $row['username'],
            $row['department']

        );
        $counter++;
    }

    // Students list header
    $header = array('ลำดับ', 'ชื่อ-นามสกุล', 'รหัสนักศึกษา' , 'แผนก'); // Adjust column headers as needed

    //MAKE LIST
    // Header
    foreach ($header as $col)
        $pdf->Cell(40, 7, iconv('utf-8', 'cp874', $col), 1); 
    $pdf->Ln();
    // Data
    foreach ($studentsData as $row) {
        foreach ($row as $col)
            $pdf->Cell(40, 6, iconv('utf-8', 'cp874', $col), 1);
        $pdf->Ln();
    }

    $pdf->Output();
} else {
    header("Location: index.php");
}
