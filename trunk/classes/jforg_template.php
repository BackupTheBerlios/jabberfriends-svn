<?php
class jforg_template {
    var $connection;
    var $page;
    var $path;
    var $frame = array('startpage','fullpage','wikiwindow','tablepage');
    /**
      * Bei der Einrichtung eine Verbindung zur Datenbank aufnehmen
      */
    function jforg_template() {
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
      * Setze den Pfad wo alle Design Dateien liegen
      */
    function set_path($path) {
        if (!file_exists($path.'/main.htm')) {
            die("jforg_template.load: Die Design Datei wurde nicht gefunden");
        }
        $this->path = $path;
        $this->page = file_get_contents($path.'/main.htm');
    }
    /**
      * Welches Theme soll verwendet werden
      */
    function set_frame($frame,$color = null) {
        if ($color=="tuerkis") {
            $realcolor="4ba7b0";
        } elseif ($color=="green") {
            $realcolor="4bb072";
        } elseif ($color=="lila") {
            $realcolor="864bb0";
        } elseif ($color=="red") {
            $realcolor="b04b4b";
        } elseif ($color=="yellow") {
            $realcolor="b0ae4b";
        } else {
            $realcolor=$color;
        }
        if (!in_array($frame,$this->frame)) {
            die("jforg_template.set_frame: Frame ist ungueltig");
        }
        if (!file_exists($this->path.'/'.$frame.'.htm')) {
            die("jforg_template.set_frame: Frame existiert nicht");
        }
        $framecontent = file_get_contents($this->path.'/'.$frame.'.htm');
        $framecontent = str_replace('{COLOR}',$realcolor,$framecontent);
        $this->page = str_replace('{FRAME_CSS}',$frame,$this->page);
        $this->page = str_replace('{CONTENT}',$framecontent,$this->page);
    }
    /**
      * Erwartet den Namen des zu ersetzenden Stückes, und den Inhalt
      */
    function replace($name,$content) {
        $name = '{'.$name.'}';
        $this->page = str_replace($name,$content,$this->page);
    }
    /**
      * Erwartet den Namen des zu ersetzenden Stückes, und den Inhalt
      */
    function replace_bb($name,$content) {
        $content = str_replace("\n","<br />",$text);
        $content = preg_replace('/\[b\]([^\]]*)\[\/b\]/','<b>$1</b>',$content);
        $content = preg_replace('/\[u\]([^\]]*)\[\/u\]/','<u>$1</u>',$content);
        $content = preg_replace('/\[i\]([^\]]*)\[\/i\]/','<i>$1</i>',$content);
        $name = '{'.$name.'}';
        $this->page = str_replace($name,$content,$this->page);
    }
    /**
      * Wählt welche Farbe in der Navigation markiert sein soll
      */
    function hover_on($color) {
        if ($color=="tuerkis") {
            $this->page = str_replace('<a class="tuerkis"','<a style="background-color: #4ba7b0;" class="tuerkis"',$this->page);
        } elseif ($color=="green") {
            $this->page = str_replace('<a class="green"','<a style="background-color: #4bb072;" class="green"',$this->page);
        } elseif ($color=="lila") {
            $this->page = str_replace('<a class="lila"','<a style="background-color: #864bb0;" class="lila"',$this->page);
        } elseif ($color=="red") {
            $this->page = str_replace('<a class="red"','<a style="background-color: #b04b4b;" class="red"',$this->page);
        } elseif ($color=="yellow") {
            $this->page = str_replace('<a class="yellow"','<a style="background-color: #b0ae4b;" class="yellow"',$this->page);
        }
    }
    /**
      * Übersetzt alle language Variabeln, siehe Datenbank Tabelle 'language'
      */
    function translate($language) {
        $sql="SELECT name,".$language." FROM `language`";
        $query = mysql_query($sql,$this->connection);
        if (!$query) {
            die("jforg_template.translate: Die SQL Abfrage ist fehlgeschlagen - $sql");
        }
        while ($row = mysql_fetch_array($query)) {
            $var=$row['name'];
            $replacer=$row[$language];
            $var = '{LANG_'.$var.'}';
            $replacer = htmlentities($replacer);
            $this->page = str_replace($var,$replacer,$this->page);
        }
    }
    /**
      * Gibt die Seite aus
      */
    function write() {
        echo $this->page;
    }
}
?>