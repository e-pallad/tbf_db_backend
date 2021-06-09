<?php
    $backupList = scandir(getcwd());
    echo json_encode($backupList);
?>