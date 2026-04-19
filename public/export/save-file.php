<?php
if(isset($_POST['data'])){
    $data = $_POST['data'];
    $filename = $_POST['filename'];
    $type = $_POST['export_type'];

    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment; filename=\"$filename.xls\"");
    echo $data;
    exit;
}
?>
