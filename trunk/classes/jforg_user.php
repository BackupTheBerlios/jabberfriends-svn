<?php
class jforg_user {
    var $connection;
    function jforg_user() {
        include('includes/config.php');
        $this->connection   =   @mysql_connect($config['mysql_server'],$config['mysql_user'],$config['mysql_password']);
        if (!$this->connection) {
            die("jforg_template: Die Verbindung zur Datenbank ist fehlgeschlagen");
        }
        $select             =   @mysql_select_db($config['mysql_table'],$this->connection);
        if (!$select) {
            die("jforg_template: Die Auswahl der Tabelle ist fehlgeschlagen");
        }
    }
    function create_new_user($jid,$nick,$passwd) {
        $passwd             =   crypt($nick,$passwd);
        $sql_user_login     =   "INSERT INTO `user_login` ( `id` , `jid` , `nick` , `passwd` ) VALUES ('', '$jid', '$nick', '$passwd');";
        $query              =   @mysql_query($sql_user_login,$this->connection);
        if (!$query) {
            die("jforg_user.create_new_user: Die SQL Abfrage ist fehlgeschlagen - $sql_user_login");
        }
        $user_id = mysql_insert_id($this->connection);
        $sql_user_details   =   "INSERT INTO `user_details` ( `id` ) VALUES ('$user_id');";
        $query              =   @mysql_query($sql_user_details,$this->connection);
        if (!$query) {
            die("jforg_user.create_new_user: Die SQL Abfrage ist fehlgeschlagen - $sql_user_details");
        }
    }
    
    // This function change the user password
    function change_password($nick, $passwd){
        $passwd             =   crypt($nick,$passwd);
        $sql_user_login     =   "UPDATE `user_login` SET `passwd` = '$passwd' WHERE `nick`='$nick';";
        $query              =   @mysql_query($sql_user_login,$this->connection);
        if ($query) {
             return true; 
        }else{
            die("jforg_user.create_new_user: Die SQL Abfrage ist fehlgeschlagen - $sql_user_login");
        }

    }
        
    function login($nick,$passwd) {
        $passwd             =   crypt($nick,$passwd);
        $sql                =   "SELECT nick,passwd FROM `user_login` WHERE `nick` = '$nick' AND `passwd` = '$passwd'";
        $query              =   @mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_user.if_login: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
       if (mysql_num_rows($query) == 1) { 
            return true;
        }else{
            return false;
        }
    }
    function is_admin($nick,$passwd) {
        $passwd             =   crypt($nick,$passwd);
        $sql                =   "SELECT nick,passwd FROM `user_login` WHERE `nick` = '$nick' AND `passwd` = '$passwd' AND status = 5";
        $query              =   @mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_user.is_admin: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        if (mysql_num_rows($query) == 1) {
            return true;
        } else {
            return false;
        }
    }
    function jid_exists($jid) {
        $sql                =   "SELECT nick,passwd FROM `user_login` WHERE `jid` = '$jid'";
        $query              =   @mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_user.if_exists_jid: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        if (mysql_num_rows($query) == 0) {
            return false;
        } else {
            return true;
        }
    }
    function nick_exists($nick) {
        $sql                =   "SELECT nick,passwd FROM `user_login` WHERE `nick` = '$nick'";
        $query              =   @mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_user.if_exists_jid: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        if (mysql_num_rows($query) == 0) {
            return false;
        } else {
            return true;
        }
    }
    function set_details($id,$array) {
        $array = str_replace("'","''",$array);
        $sql = 'UPDATE `user_details` SET
        REALNAME = \''.$array['realname'].'\',
        BIRTHDATE = \''.$array['birthdate'].'\',
        COUNTRY = \''.$array['country'].'\',
        CITY = \''.$array['city'].'\',
        ORIGINAL_FROM = \''.$array['original_from'].'\',
        LANGUAGES = \''.$array['languages'].'\',
        HOBBYS = \''.$array['hobbys'].'\',
        COMPUTER = \''.$array['computer'].'\',
        COMPUTER_OS = \''.$array['computer_os'].'\',
        GEEKCODE = \''.$array['geekcode'].'\',
        PUBLICKEY = \''.$array['publickey'].'\',
        FAVORITE_FILM = \''.$array['favorite_film'].'\',
        FAVORITE_SERIES = \''.$array['favorite_series'].'\',
        FAVORITE_MUSIK = \''.$array['favorite_musik'].'\',
        FAVORITE_BOOK = \''.$array['favorite_book'].'\'
        WHERE id = '.$id.';';
        $query              =   @mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_user.set_details: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
    }
    function get_details($id) {
        $sql                =   "SELECT * FROM `user_details` WHERE `id` = '$id'";
        $query              =   @mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_user.get_details: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $result = mysql_fetch_array($query,MYSQL_ASSOC);
        return $result;
    }
    function get_id($nick) {
        $sql                =   "SELECT id,nick FROM `user_login` WHERE `nick` = '$nick'";
        $query              =   @mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_user.get_id: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $row = mysql_fetch_array($query);
        return $row['id'];
    }
    function get_nick($id) {
        $sql                =   "SELECT id,nick FROM `user_login` WHERE `id` = '$id'";
        $query              =   @mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_user.get_nick: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $row = mysql_fetch_array($query);
        return $row['nick'];
    }
    function get_jid($id) {
        $sql                =   "SELECT id,jid FROM `user_login` WHERE `id` = '$id'";
        $query              =   @mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_user.get_jid: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $row = mysql_fetch_array($query);
        return $row['jid'];
    }
    function get_realname($id) {
        $sql                =   "SELECT id,realname FROM `user_details` WHERE `id` = '$id'";
        $query              =   @mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_user.get_realname: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $row = mysql_fetch_array($query);
        $realname = $row['realname'];
            if ($realname=="") {
            $sql                =   "SELECT id,nick FROM `user_login` WHERE `id` = '$id'";
            $query              =   @mysql_query($sql,$this->connection);
            if (!$query) {
                die("jforg_user.get_realname: Die SQL Abfrage ist fehlgeschlagen - $sql");
            }
            $row = mysql_fetch_array($query);
            $realname = $row['nick'];
        }
        return $realname;
    }
}
?>
