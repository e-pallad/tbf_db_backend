<?php
    $backupList = scandir('/');
    echo json_encode($backupList);
?>