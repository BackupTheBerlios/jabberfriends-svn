<?php
/**
 * Klasse um zweisprachige News aus einer Datenbank zu holen
 *
 * Autor: Daniel <tux.ICBlodd> Gultsch
 * Kontakt: daniel@gultsch.de
 *
 * Version: 0.1.1
 *
 * Geschrieben für JabberFriends.org
 *
 * Changelog:
 * - Fehler in set_language behoben
 *
 * Bekannte Fehler:
 * - Fehlerhafte Umlaute bei Newsausgabe
 *
 * ToDo-Liste:
 * - das Datums Format prüfen und ggf. verschönern
 * - Testen, testen, testen
 */
class jforg_news {
    var $language;
    var $newsID;
    var $news;
    var $number;
    /**
     * Erwartet den Sprachcode als String (entweder "de" oder "en")
     */
    function set_language($language) {
        if (($language=="de")||($language=="en")) {
            $this->language = $language;
        } else {
            die("jforg_news.set_language: Kein g&uuml;ltiger Sprachcode");
        }
    }
    /**
     * Die Methode muss aufgerufen werden, wenn man nur eine News haben will.
     * Erwartet die News-ID als Integer
     */
    function set_news_id($id) {
        if  (is_integer($id)) {
            $this->newsID = $id;
        } else {
            die("jforg_news.set_news_id: ID ist kein Integer");
        }
    }
    /**
     * Die Methode stellt nur die Verbindung zur Datenbank her und holt die News mit den gesetzten Parametern heraus. (Sprache, Anzahl)
     * Erwartet nichts und gibt nichts zurück
     */
    function query() {
        include_once("config.php");
        $connection = mysql_connect($config['mysql_server'],$config['mysql_user'],$config['mysql_pw']);
        if (!$connection) {
            die("jforg_news.query: Die Verbindung zur Datenbank ist fehlgeschlagen");
        }
        $select = mysql_select_db($config['mysql_table']);
        if (!$select) {
            die("jforg_news.query: Die Auswahl der Tabelle ist fehlgeschlagen");
        }
        $id = $this->newsID;
        $language = $this->language;
        if ($id=="") {
            $sql = "SELECT id,date,de_title,en_title,".$language."_body,commentary_id FROM `news` ORDER by date DESC";
        } else {
            $sql = "SELECT id,date,de_title,en_title,".$language."_body,commentary_id FROM `news` WHERE id = $id ORDER by date DESC";
        }
        $query = mysql_query($sql);
        if (!$query) {
            die("jforg_news.query: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        $number = 0;
        while ($row = mysql_fetch_array($query)) {
            $number++;
            $singlenews['title'] = $row[$language.'_title'];
            $singlenews['body'] = $row[$language.'_body'];
            $singlenews['commentary_id'] = $row['commentary_id'];
            $singlenews['unix'] = $row['date'];
            if ($language=="de") {
                $singlenews['date'] = date("d.n.Y H:i",$singlenews['unix']);
            }
            if ($language=="en") {
                $singlenews['date'] = date("n d, y h:i a",$singlenews['unix']);
            }
            $singlenews['title'] = htmlentities($singlenews['title']);
            $singlenews['body'] = htmlentities($singlenews['body']);
            $singlenews['body'] = bbcode($singlenews['body']);
            $singlenews['link'] = strtolower($singlenews['title']);
            $singlenews['link'] = $row['id'].'-'.str_replace(' ','-',$singlenews['link']).'.htm';
            $singlenews['link'] = str_replace('&szlig;','ss',$singlenews['link']);
            $singlenews['link'] = str_replace('&ouml;','oe',$singlenews['link']);
            $singlenews['link'] = str_replace('&uuml;','ue',$singlenews['link']);
            $singlenews['link'] = str_replace('&auml;','ae',$singlenews['link']);
            $news = $this->news;
            $news[] = $singlenews;
            $this->news = $news;
            $this->number = $number;
        }
    }
    /**
     * Liefert die Anzahl der News als Integer
     */
    function get_number_of_news() {
        $number = $this->number;
        return $number;
    }
    /**
     * Liefert die letzte News als array (title, body, date, unix, commentary_id)
     * Beim zweiten Aufruf liefert sie die vorletzte News, beim dritten die vorvorletzte usw.
     * date ist ein ausgeschriebenes Datum, welches an die Sprache angepasst ist. unix ist der Unixtimestamp
     */
    function get_single_news() {
        $news = $this->news;
        $singlenews = array_shift($news);
        $this->news = $news;
        return $singlenews;
    }
}
?>