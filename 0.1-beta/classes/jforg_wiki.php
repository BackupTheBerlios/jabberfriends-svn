<?php
class jforg_wiki {
    var $connection;
    var $wiki_id = 0;
    var $content_language;
    var $content;
    function jforg_wiki() {
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
    function set_id_language($id,$language,$realid=0) {
        $this->content_language = $language;
        $this->wiki_id = $id;
        if ($realid==0) {
            $sql = 'SELECT id,wiki_id,language,title,text FROM `wiki` WHERE wiki_id = '.$this->wiki_id.' AND `language` = \''.$this->content_language.'\' ORDER BY id DESC LIMIT 1';
        } else {
            $sql = 'SELECT id,wiki_id,language,title,text FROM `wiki` WHERE id = '.$realid.' AND wiki_id = '.$this->wiki_id.' AND `language` = \''.$this->content_language.'\'';
        }
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.get_by_id: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $this->content = mysql_fetch_assoc($query);
        $anzahl = mysql_num_rows($query);
        //Fehler, entscheide welchen
        if ($anzahl!=1) {
            if ($realid!=0) {
                die('Error: Invaild version');
            } else {
                //Pr�fen ob die ID grunds�tzlich da ist - wenn nicht 404 error
                $sql = 'SELECT wiki_id FROM `wiki` WHERE wiki_id = '.$this->wiki_id.' LIMIT 1';
                $query = mysql_query($sql,$this->connection);
                if (!$query) {
                    die("jforg_wiki.get_by_id: Die SQL Abfrage ist fehlgeschlagen - $sql");
                }
            }
            $this->content = $error;
        }
    }
    function get_versions() {
        $sql = 'SELECT id,wiki_id,language,UNIX_TIMESTAMP(datetime) AS datetime,user_id,ip_addr FROM `wiki` WHERE wiki_id = '.$this->wiki_id.' AND `language` = \''.$this->content_language.'\' ORDER BY id DESC';
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.get_by_id: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        while ($row = mysql_fetch_assoc($query)) {
            $result[]=$row;
        }
        return $result;
    }
    function get_title() {
    
        if ($this->wiki_id==0) {
            die('jforg_wiki.get_titel: Keine ID angegeben');
        }
        return $this->content['title'];
    }
    function get_text() {
        if ($this->wiki_id==0) {
            die('jforg_wiki.get_text: Keine ID angegeben');
        }
        return $this->content['text'];
    }
    function get_authors() {
        if ($this->wiki_id==0) {
            die('jforg_wiki.get_authors: Keine ID angegeben');
        }
        $sql = 'SELECT user_id FROM wiki WHERE wiki_id = '.$this->wiki_id.' AND `language` = \''.$this->content_language.'\'';
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.get_authors_by_id: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $result = array();
        while ($row = mysql_fetch_assoc($query)) {
            if (!in_array($row['user_id'],$result)) {
                $result[]=$row['user_id'];
            }
        }
        return $result;
    }
    function exists_translation() {
        if ($this->content_language=='de') {
            $trans_lang = 'en';
        } elseif ($this->content_language=='en') {
            $trans_lang = 'de';
        } else {
            die('error');
        }
        $sql = 'SELECT id FROM `wiki` WHERE wiki_id = '.$this->wiki_id.' AND `language` = \''.$trans_lang.'\'';
        $query = mysql_query($sql);
        if (!$query) {
            die("jforg_wiki.is_translation: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        //die('Test: '.mysql_num_rows($query).$sql);
        if (mysql_num_rows($query) >= 1) {
            return true;
        } else {
            return false;
        }
    }
    function exists() {
        $sql = 'SELECT id FROM `wiki` WHERE wiki_id = '.$this->wiki_id.' AND `language` = \''.$this->content_language.'\'';
        $query = mysql_query($sql);
        if (!$query) {
            die("jforg_wiki.exists: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        if (mysql_num_rows($query) >= 1) {
            return true;
        } else {
            return false;
        }
    }
    function get_german_link() {
        $sql = 'SELECT id,wiki_id,title,text FROM `wiki` WHERE wiki_id = '.$this->wiki_id.' AND `language` = \'de\' ORDER BY id DESC LIMIT 1';
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.get_german_link: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $result = mysql_fetch_assoc($query);
        if ($result['title']=='') {
            $result['title'] = '404';
        }
        return $result['title'];
    }
    function get_english_link() {
        $sql = 'SELECT id,wiki_id,title,text FROM `wiki` WHERE wiki_id = '.$this->wiki_id.' AND `language` = \'en\' ORDER BY id DESC LIMIT 1';
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.get_english_link: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $result = mysql_fetch_assoc($query);
        if ($result['title']=='') {
            $result['title'] = '404';
        }
        return $result['title'];
    }
    function translate_article($id,$title,$text,$language,$author) {
        $title = str_replace("'","''",$title);
        $text = str_replace("'","''",$text);
        $ip_addr = $_SERVER['REMOTE_ADDR'];
        $datum = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `wiki` ( `id` , `wiki_id` , `language` , `title` , `text` , `datetime` , `user_id` , `ip_addr`) VALUES ('', '$id', '$language', '$title', '$text', '$datum', '$author', '$ip_addr')";
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.translate_article: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
    }
    function update_article($id,$text,$language,$author) {
        $title = str_replace("'","''",$this->get_title());
        $text = str_replace("'","''",$text);
        $ip_addr = $_SERVER['REMOTE_ADDR'];
        $datum = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `wiki` ( `id` , `wiki_id` , `language` , `title` , `text` , `datetime` , `user_id` , `ip_addr`) VALUES ('', '$id', '$language', '$title', '$text', '$datum', '$author', '$ip_addr')";
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.update_article: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
    }
    function create_article($title,$text,$language,$author) {
        $sql_find_id = 'SELECT wiki_id FROM `wiki` ORDER BY `wiki_id` DESC LIMIT 1';
        $query_find_id = mysql_query($sql_find_id,$this->connection);
        if (!$query_find_id) {
            die("jforg_wiki.create_article: Die SQL Abfrage ist fehlgeschlagen - $sql_find_id");
        }
        $result_find_id = mysql_fetch_assoc($query_find_id);
        $id = $result_find_id['wiki_id'] + 1;
        $title = str_replace("'","''",$title);
        $text = str_replace("'","''",$text);
        $ip_addr = $_SERVER['REMOTE_ADDR'];
        $datum = date('Y-m-d H:i:s');
        $sql = "INSERT INTO `wiki` ( `id` , `wiki_id` , `language` , `title` , `text` , `datetime` , `user_id` , `ip_addr`) VALUES ('', '$id', '$language', '$title', '$text', '$datum', '$author', '$ip_addr')";
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_wiki.update_article: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        return $id;
    }
}
?>