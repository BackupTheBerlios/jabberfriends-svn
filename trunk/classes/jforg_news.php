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
    function get_latest($anzahl = 1)
    {
        $sql = 'SELECT id,title,text,UNIX_TIMESTAMP(datetime) AS datetime FROM news ORDER BY datetime desc LIMIT '.$anzahl;      $query = mysql_query($sql,$this->connection);
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
    function get_by_id($id) {
        $sql = 'SELECT title, text, UNIX_TIMESTAMP(datetime) AS datetime FROM news WHERE id = '.$id;
        $query = mysql_query($sql,$this->connection);
        if (!$query)
        {
            die('jforg_news: Abfrage schlug fehl '.$sql);    
        }
        return  mysql_fetch_assoc($query);
    }
    function write($title,$text) {
        $sql = 'INSERT INTO `news` ( `id` , `datetime` , `title` , `text` ) VALUES (\'\', \''.date('Y-m-d H:i:s').'\', \''.mysql_real_escape_string($title).'\', \''.mysql_real_escape_string($text).'\');';
        $query = mysql_query($sql,$this->connection);
        if (!$query)
        {
            die('jforg_news: Abfrage schlug fehl '.$sql);    
        }
        return mysql_insert_id($this->connection);
    }
    function update($id,$title,$text) {
        $sql = 'UPDATE `news` SET `title` = \''.mysql_real_escape_string($title).'\',`text` = \''.mysql_real_escape_string($text).'\' WHERE `id` ='.$id;
        $query = mysql_query($sql,$this->connection);
        if (!$query)
        {
            die('jforg_news: Abfrage schlug fehl '.$sql);    
        }
    }
}
?>