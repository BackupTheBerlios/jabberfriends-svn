<?php
class jforg_cite {
    var $connection;
    function jforg_cite() {
        include('config.php');
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
            $sql = 'SELECT id,zitat,user,UNIX_TIMESTAMP(datetime) AS datetime FROM zitate ORDER BY RAND() LIMIT 1';
        } else {
            $sql = 'SELECT zitat FROM zitate WHERE language = \''.$language.'\' ORDER BY RAND( ) LIMIT 1';
        }
        $query = mysql_query($sql,$this->connection);
        if (!$query)
        {
            die('jforg_cite: Abfrage schlug fehl '.$sql);    
        }
        $result = mysql_fetch_assoc($query);
        return $result;
    }
    function get_last($anzahl = 1, $language = '') {
        $sql = 'SELECT id,zitat,user,UNIX_TIMESTAMP(datetime) AS datetime FROM zitate ORDER BY datetime DESC LIMIT '.$anzahl;
        $query = mysql_query($sql,$this->connection);
        if (!$query)
        {
            die('jforg_cite: Abfrage schlug fehl '.$sql);    
        }
        while ($row = mysql_fetch_assoc($query)) {
            $result[]=$row;
        }
        return $result;
    }
    
    function get_top($anzahl = 1, $language = '') {
        $sql = 'SELECT id,zitat,user,UNIX_TIMESTAMP(datetime) AS datetime FROM zitate ORDER BY rating DESC LIMIT '.$anzahl;
        $query = mysql_query($sql,$this->connection);
        if (!$query)
        {
            die('jforg_cite: Abfrage schlug fehl '.$sql);    
        }
        while ($row = mysql_fetch_assoc($query)) {
            $result[]=$row;
        }
        return $result;
    }
    function get_by_id($id) {
        $sql = 'SELECT id,zitat,user,UNIX_TIMESTAMP(datetime) AS datetime FROM zitate WHERE id ='.$id;
        $query = mysql_query($sql,$this->connection);
        if (!$query)
        {
            die('jforg_cite: Abfrage schlug fehl '.$sql);    
        }
        $result = mysql_fetch_assoc($query);
        return $result;
    }
    function write($zitat,$user,$language) {
        $sql = 'INSERT INTO `zitate` ( `id` , `zitat` , `language` , `user` , `datetime` , `ip_addr` )  VALUES  (\'\', \''.mysql_real_escape_string($zitat).'\', \''.$language.'\', \''.$user.'\', \''.date('Y-m-d H:i:s').'\', \''.$_SERVER['REMOTE_ADDR'].'\');';
        $query = mysql_query($sql,$this->connection);
        if (!$query)
        {
            die('jforg_news: Abfrage schlug fehl '.$sql);    
        }
        return mysql_insert_id($this->connection);
    }
    function update($id,$zitat) {
        
    }
    function rate($cite_id,$user_id,$rating) {
        $sql = 'SELECT * FROM `zitate_rate` WHERE `cite_id` = '.$cite_id.' AND `user_id` = '.$user_id;
        $query = mysql_query($sql);
        if (mysql_num_rows($query)==0) {
            //hole altes rating
            $sql_rating = 'SELECT rating FROM `zitate` WHERE `id` ='.$cite_id;
            $result_rating = mysql_fetch_assoc(mysql_query($sql_rating));
            $old_rating = $result_rating['rating'];
            $new_rating = $old_rating + ($rating * $old_rating / 100);
            $sql_update_rating = 'UPDATE `zitate` SET `rating` = \''.$new_rating.'\' WHERE `id` ='.$cite_id;
            mysql_query($sql_update_rating);
            $sql_insert_user = 'INSERT INTO `zitate_rate` ( `cite_id` , `user_id` ) VALUES (\''.$cite_id.'\', \''.$user_id.'\');';
            mysql_query($sql_insert_user);
            return true;
        } else {
            return false;
        }
    }
}
?>