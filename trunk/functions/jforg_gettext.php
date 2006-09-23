<?php
function get_text($id,$language) {
    include('config.php');
    $connection = @mysql_connect($config['mysql_server'],$config['mysql_user'],$config['mysql_password']);
    if (!$connection) {
        die('get_text: Die Verbindung zur Datenbank ist fehlgeschlagen');
    }
    $select = @mysql_select_db($config['mysql_table']);
    if (!$select) {
        die('get_text: Die Auswahl der Tabelle ist fehlgeschlagen');
    }
    $sql = "SELECT id,$language FROM text WHERE id = $id";
    $result = @mysql_query($sql);
    if (!$result) {
        die('get_text: Abfrage fehlgeschlagen');
    }
    $row = mysql_fetch_array($result);
    return $row[$language];
}
?>