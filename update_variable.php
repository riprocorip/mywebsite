<?php
// Handle the update from AJAX request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["toggleValue"])) {
        $showTypeSQL = $_POST["toggleValue"];
        
        echo "Current value of \$showTypeSQL: $showTypeSQL";
    }
}
?>
