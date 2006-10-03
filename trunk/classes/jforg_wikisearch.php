<?php
class jforg_wikisearch {
    var $connection;
    var $searchword;
    var $language;
    function jforg_wikisearch() {
        include('config.php');
        $this->connection = @mysql_connect($config['mysql_server'],$config['mysql_user'],$config['mysql_password']);
        if (!$this->connection) {
            die("jforg_template: Die Verbindung zur Datenbank ist fehlgeschlagen");
        }
        $select = @mysql_select_db($config['mysql_table'],$this->connection);
        if (!$select) {
            die("jforg_template: Die Auswahl der Tabelle ist fehlgeschlagen");
        }
    }
    function set_language($l) {
        $this->language = $l;
    }
    function set_searchword($s) {
        $this->searchword = $s;
    }
    function get_direct_id() {
        $language = $this->language;
        $sw = mysql_real_escape_string($this->searchword);
        $sql = "SELECT wiki_id FROM `wiki` WHERE `language` ='$language' AND `title` ='$sw'";
        $query = mysql_query($sql);
        if (!$query) {
            die('jforg_wikisearch.get_direct_id: sql schlug fehl');
        }
        $result = array();
        while ($row = mysql_fetch_assoc($query)) {
            if (!in_array($row['wiki_id'],$result)) {
                $result[]=$row['wiki_id'];
            }
        }
        if (count($result)==1) {
            return $result[0];
        } else {
            return 0;
        }
    }
}
?> 
