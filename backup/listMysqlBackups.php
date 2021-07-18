<?php
    // https://stackoverflow.com/a/15774702
    $backupList = asort(array_diff(scandir(getcwd()), array('.', '..', 'backupMysql.php', 'listMysqlBackups.php', 'restoreMysql.php', 'index.php')));

    header("Content-type: application/pdf");
    header('Access-Control-Allow-Origin: *');
    header('Content-Disposition: attachment; filename="SEF-EVerbraucherliste.pdf";');

    echo json_encode($backupList);
?>