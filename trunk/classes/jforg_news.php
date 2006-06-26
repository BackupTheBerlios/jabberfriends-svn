<?php
class jforg_news  {
    var $connection;
    function jforg_news() {
        include('includes/config.php');
        $this->connection = @mysql_connect($config['mysql_server'],$config['mysql_user'],$config['mysql_password']);
        if (!$this->connection) {
            die("jforg_news: Die Verbindung zur Datenbank ist fehlgeschlagen");
        }
        $select = @mysql_select_db($config['mysql_table'],$this->connection);
        if (!$select) {
            die("jforg_news: Die Auswahl der Tabelle ist fehlgeschlagen");
        }
    }
    function get_latest($language,$anzahl = 1)
    {
        $sql = 'SELECT id,'.$language.'_title AS title,'.$language.'_text AS text, UNIX_TIMESTAMP(datetime) AS datetime FROM news ORDER BY datetime desc LIMIT '.$anzahl;
        $query = mysql_query($sql,$this->connection);
        if (!$query)
        {
            die('jforg_news: Abfrage schlug fehl '.$sql);    
        }
        if ($anzahl==1)
        {
            $result = mysql_fetch_assoc($query);
        }
        else
        {
            while ($row = mysql_fetch_assoc($query)) {
                $result[]=$row;
            }
        }
        return $result;
    }
    function get_by_id($language,$id) {
        $sql = 'SELECT '.$language.'_title AS title,'.$language.'_text AS text, UNIX_TIMESTAMP(datetime) AS datetime FROM news WHERE id = '.$id;
        $query = mysql_query($sql,$this->connection);
        if (!$query)
        {
            die('jforg_news: Abfrage schlug fehl '.$sql);    
        }
        return  mysql_fetch_assoc($query);
    }
    function write($de_title,$en_title,$de_text,$en_text) {
        $sql = 'INSERT INTO `news` ( `id` , `datetime` , `de_title` , `en_title` , `de_text` , `en_text` ) VALUES (\'\', \''.date('Y-m-d H:i:s').'\', \''.mysql_real_escape_string($de_title).'\', \''.mysql_real_escape_string($en_title).'\', \''.mysql_real_escape_string($de_text).'\', \''.mysql_real_escape_string($en_text).'\');';
        $query = mysql_query($sql,$this->connection);
        if (!$query)
        {
            die('jforg_news: Abfrage schlug fehl '.$sql);    
        }
    }
    function update($id,$de_title,$en_title,$de_text,$en_text) {
        
    }
}
?>