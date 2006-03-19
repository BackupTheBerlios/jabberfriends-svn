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
        $sql = 'SELECT * FROM `wiki` WHERE wiki_id = '.$id.' ORDER BY id DESC LIMIT 1';
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.get_by_id: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $result = mysql_fetch_assoc($query);
        //Title der anderen Sprache holen
        $sql2 = 'SELECT id,title FROM `wiki` WHERE wiki_id = '.$result['other_language'].' ORDER BY id DESC LIMIT 1';
        $query2 = mysql_query($sql2,$this->connection);
        if (!$query2) {
            die("jforg_wiki.get_by_id: Die SQL Abfrage ist fehlgeschlagen - $sql2");
        }
        $result2 = mysql_fetch_assoc($query2);
        if ($language=='de') {
            $result['de_title'] = $result['title'];
            $result['en_title'] = $result2['title'];
            $result['de_id'] = $id;
            $result['en_id'] = $result['other_language'];
        }
        if ($language=='en') {
            $result['de_title'] = $result2['title'];
            $result['en_title'] = $result['title'];
            $result['de_id'] = $result['other_language'];
            $result['en_id'] = $id;
        } 
        return $result;
    }
    function get_authors_by_id($id) {
        $sql = 'SELECT user_id FROM wiki WHERE wiki_id = '.$id;
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.get_authors_by_id: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        while ($row = mysql_fetch_assoc($query)) {
            if (!in_array($row['user_id'],$result)) {
                $result[]=$row['user_id'];
            }
        }
        return $result;
    }
    function update_article($id,$title,$text,$other_language,$author) {
        $datum = date();
        $sql = "INSERT INTO `wiki` ( `id` , `wiki_id` , `other_language` , `title` , `text` , `datetime` , `user_id` ) VALUES ('', '$id', '$other_language', '$title', '$text', '$date', '$author')";
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.update_article: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
    }
}
?>