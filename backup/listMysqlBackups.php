<?php
    // https://stackoverflow.com/a/15774702
    $backupList = array_diff(scandir(getcwd()), array('.', '..', 'backupMysql.php', 'listMysqlBackups.php', 'restoreMysql.php', 'index.php'));

    header("Content-type: application/json");
    header('Access-Control-Allow-Origin: *');

    echo json_encode($backupList);
?>