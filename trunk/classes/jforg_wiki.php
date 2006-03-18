<?php
class jforg_wiki {
    var $connection;
    function jforg_wiki() {
        include('includes/config.php');
        $this->connection = @mysql_connect($config['mysql_server'],$config['mysql_user'],$config['mysql_password']);
        if (!$this->connection) {
            die("jforg_template: Die Verbindung zur Datenbank ist fehlgeschlagen");
        }
        $select = @mysql_select_db($config['mysql_table'],$this->connection);
        if (!$select) {
            die("jforg_template: Die Auswahl der Tabelle ist fehlgeschlagen");
        }
    }
    /**
      * Liefert beide Titel und den Inhalt der gewaehlten Sprache an hand der ID
      */
    function get_by_id($id,$language) {
        $sql = 'SELECT id,de_title,en_title,'.$language.'_id FROM wiki_title WHERE id = '.$id;
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.get_by_id: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $result = mysql_fetch_assoc($query);
        $varname = $language."_id";
        $sql2 = "SELECT text FROM wiki_content WHERE id = ".$result[$varname];
        $query2 = mysql_query($sql2,$this->connection);
        if (!$query2) {
            die("jforg_wiki.get_by_id: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $result2 = mysql_fetch_assoc($query2);
        $result['text'] = $result2['text'];
        return $result;
    }
    function get_authors_by_id($id) {
        $sql = 'SELECT user_id FROM wiki_authors WHERE wiki_id = '.$id;
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.get_authors_by_id: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        while ($row = mysql_fetch_assoc($query)) {
            $result[]=$row;
        }
        return $result;
    }
}
?>