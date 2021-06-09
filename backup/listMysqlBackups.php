<?php
    // https://stackoverflow.com/a/15774702
    $backupList = array_diff(scandir(getcwd()), array('.', '..', 'backupMysql.php', 'listMysqlBackups.php', 'restoreMysql.php'));
    echo json_encode($backupList);
?>