<?php
class jforg_usersearch {
    var $connection;
    function jforg_usersearch() {
        include('includes/config.php');
        $this->connection   =   @mysql_connect($config['mysql_server'],$config['mysql_user'],$config['mysql_password']);
        if (!$this->connection) {
            die("jforg_usersearch: Die Verbindung zur Datenbank ist fehlgeschlagen");
        }
        $select             =   @mysql_select_db($config['mysql_table'],$this->connection);
        if (!$select) {
            die("jforg_usersearch: Die Auswahl der Tabelle ist fehlgeschlagen");
        }
    }
    function search_last($count) {
        $sql = "SELECT id,nick,jid FROM `user_login` ORDER BY `id` DESC LIMIT $count";
        $query = mysql_query($sql);
        if (!$query) {
            die('jforg_usersearch.search_last: Abfrage schlug fehl');
        }
        while ($row = mysql_fetch_assoc($query)) {
            $result[]=$row;
        }
        return $result;
    }
}
?>