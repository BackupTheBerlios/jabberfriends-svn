<?php
class jforg_usersearch {
    var $connection;
    var $counter = 0;
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
    function search_all($string) {
        $sql = "SELECT *
FROM `user_details`
WHERE `REALNAME` LIKE '%$string%'
OR `COUNTRY` LIKE '%$string%'
OR `CITY` LIKE '%$string%'
OR `ORIGINAL_FROM` LIKE '%$string%'
OR `LANGUAGES` LIKE '%$string%'
OR `HOBBYS` LIKE '%$string%'
OR `COMPUTER` LIKE '%$string%'
OR `COMPUTER_OS` LIKE '%$string%'
OR `FAVORITE_FILM` LIKE '%$string%'
OR `FAVORITE_BOOK` LIKE '%$string%'
OR `FAVORITE_MUSIK` LIKE '%$string%'
OR `FAVORITE_SERIES` LIKE '%$string%'";
        $query = mysql_query($sql);
        if (!$query) {
            die('jforg_usersearch.search_all: Abfrage 1 schlug fehl');
        }
        $sql2 = "SELECT COUNT(*) AS counter
FROM `user_details`
WHERE `REALNAME` LIKE '%$string%'
OR `COUNTRY` LIKE '%$string%'
OR `CITY` LIKE '%$string%'
OR `ORIGINAL_FROM` LIKE '%$string%'
OR `LANGUAGES` LIKE '%$string%'
OR `HOBBYS` LIKE '%$string%'
OR `COMPUTER` LIKE '%$string%'
OR `COMPUTER_OS` LIKE '%$string%'
OR `FAVORITE_FILM` LIKE '%$string%'
OR `FAVORITE_BOOK` LIKE '%$string%'
OR `FAVORITE_MUSIK` LIKE '%$string%'
OR `FAVORITE_SERIES` LIKE '%$string%'";
        $query2 = mysql_query($sql2);
        if (!$query2) {
            die('jforg_usersearch.search_all: Abfrage 2 schlug fehl');
        }
        $result2 = mysql_fetch_array($query2);
        $this->counter = $result2['counter'];
        while ($row = mysql_fetch_assoc($query)) {
            $result[]=$row;
        }
        return $result;
    }
    function get_number_of() {
        return $this->counter;
    }
}
?>