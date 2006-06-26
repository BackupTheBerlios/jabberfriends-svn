<?php
class jforg_cite {
    var $connection;
    function jforg_cite() {
        include('includes/config.php');
        $this->connection = @mysql_connect($config['mysql_server'],$config['mysql_user'],$config['mysql_password']);
        if (!$this->connection) {
            die("jforg_news: Die Verbindung zur Datenbank ist fehlgeschlagen");
        }
        $select = @mysql_select_db($config['mysql_table'],$this->connection);
        if (!$select) {
            die("jforg_cite: Die Auswahl der Tabelle ist fehlgeschlagen");
        }
    }
    function get_random($language = '') {
        if ($language=='') {
            $sql = 'SELECT zitat FROM zitate ORDER BY RAND() LIMIT 1';
        } else {
            $sql = 'SELECT zitat FROM zitate WHERE language = \''.$language.'\' ORDER BY RAND( ) LIMIT 1';
        }
        $query = mysql_query($sql,$this->connection);
        if (!$query)
        {
            die('jforg_cite: Abfrage schlug fehl '.$sql);    
        }
        $result = mysql_fetch_assoc($query);
        return $result['zitat'];
    }
    function get_last($language = '', $anzahl = 1) {
        
    }
}
?>