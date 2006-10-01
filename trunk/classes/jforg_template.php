<?php
class jforg_template {
    
    var $connection;
    var $page;
    var $path;
    var $frame = array('startpage','fullpage','wikiwindow');
    /**
      * Bei der Einrichtung eine Verbindung zur Datenbank aufnehmen
      */
    function jforg_template() {
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
      * Erwartet den Namen des zu ersetzenden Stückes, und den Inhalt - parst im wiki code
      */
    function parse_wiki($content) {
        $zeilen = explode("\n",$content);
        $content = '';
        $last_row_is_apostroph = false;
        $last_row_is_list = false;
        $last_row_is_under_list = false;
        $table_of_content = '<ol>';
        $table_of_content_counter = 0;
        foreach( $zeilen as $zeile ) {
            $next_br = true;
            $parse_row = true;
            //Auf Code checken
            if ($zeile{0}==" ") {
                $zeile = substr($zeile, 1);
                if (!$last_row_is_apostroph) {
                    $zeile = '<code>'.htmlentities($zeile);
                }
                $last_row_is_apostroph = true;
                $parse_row = false;
            } else {
                if ($last_row_is_apostroph) {
                    $zeile = '</code>'.$zeile;
                }
                $last_row_is_apostroph = false;
            }
            //Auf Ueberschrift checken
            if (substr($zeile, 0, 2)=="==") {
                $table_of_content_counter++;
                $headline = preg_replace('/==([^=]+)==/','$1',$zeile);
                $zeile = '<a name="'.$table_of_content_counter.'"></a><h2>'.$headline.'</h2>';
                $table_of_content = $table_of_content.'<li><a href="#'.$table_of_content_counter.'">'.$headline.'</a></li>';
                $next_br = false;
            }
            if ($parse_row) {
                //Auf Liste checken
                if (substr($zeile, 0, 2)=="**") {
                    $zeile = substr($zeile, 2);
                    if (!$last_row_is_under_list) {
                        $laenge = strlen($content);
                        $content = substr($content, 0, $laenge - 5);
                        $zeile = '<ul><li>'.$zeile.'</li>';
                    } else {
                        $zeile = '<li>'.$zeile.'</li>';
                    }
                    $last_row_is_under_list = true;
                    $next_br = false;
                } elseif ($zeile{0}=="*") {
                    $zeile = substr($zeile, 1);
                    $zeile = '<li>'.$zeile.'</li>';
                    if ($last_row_is_under_list) {
                        $zeile = '</ul></li>'.$zeile;
                    }
                    $last_row_is_under_list = false;
                    if (!$last_row_is_list) {
                        $zeile = '<ul>'.$zeile;
                    }
                    $next_br = false;
                    $last_row_is_list = true;
                } else {
                    if ($last_row_is_list) {
                        $zeile = '</ul>'.$zeile;
                    }
                    if ($last_row_is_under_list) {
                        $zeile = '</ul></li>'.$zeile;
                    }
                    $last_row_is_list = false;
                    $last_row_is_under_list = false;
                }
                //Bilder einfügen
                $zeile = preg_replace('/\[image:([.\d\S\w]{4,})\]/','<a href="/upload/images/$1"><img src="/upload/thumbs/$1" alt="" /></a>',$zeile);
                //Dicken text
                $zeile = preg_replace('/\'\'\'([^\']+)\'\'\'/','<b>$1</b>',$zeile);
                //schräger text
                $zeile = preg_replace('/\'\'([^\']+)\'\'/','<i>$1</i>',$zeile);
                //Normale URLs in Links
                $zeile = preg_replace('/[^[](ftp|http|https):\/\/([\S]{3,})/',' <a href="$1://$2">$2</a>',$zeile);
                //Normale, beschriftete Links
                $zeile = preg_replace('/\[([\S]{4,})[\s]([^]]{3,})\]/','<a href="$1">$2</a>',$zeile);
            }
            if ($next_br) {
                $zeile = $zeile.'<br />';
            }
            $content = $content.$zeile;
        }
        //Wenn die letzte Zeile Fehlt, mache alles zu
        if ($last_row_is_under_list) {
            $content = $content.'</ul></li>';
        }
        if ($last_row_is_list) {
            $content = $content.'</ul>';
        }
        $table_of_content = $table_of_content.'</ol>';
        $content = str_replace('[TABLEOFCONTENT]',$table_of_content,$content);
        return $content;
    }
    function replace_wiki($name,$content) {
        $wiki = $this->parse_wiki($content);
        $this->replace($name,$wiki);
    }
    function highlight_cite($zitat) {
        $colors = array('b04b4b','4bb072','4b72b0','b0ae4b','864bb0');
        $zeilen = explode("\n",$zitat);
        $nicks = array();
        foreach($zeilen as $zeile) {
            $woerter = explode(' ',$zeile);
            if (preg_match('/[<][\S]+[>]/',$woerter[0])) {
                if (!in_array($woerter[0],$nicks)) {
                    $nicks[] = $woerter[0];
                }
            }
        }
        $zitat = htmlentities($zitat.'');
        $zitat = str_replace("\n",'<br />',$zitat);
        $i = 0;
        foreach($nicks as $nick) {
            if ($i == count($colors)) {
                $i = 0;
            }
            $zitat = str_replace(htmlentities($nick),'<span style="color: #'.$colors[$i].'"><b>'.htmlentities($nick).'</b></span>',$zitat);
            ++$i;
        }
        return $zitat;
    }
    function format_userdetails( $array, $max_per_search = 5, $suchwort = '' ) {
        if ($suchwort=='') {
            $bol = TRUE;
            $suche == 'test';
        } else {
            $bol = FALSE;
            $suche = $suchwort;
        }
        $array = array_map('htmlentities',$array);
        $details_match = "";
        $details_counter = 1;
        if (($array['jid']!="") && (stristr($array['jid'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_JID}: '.$array['jid'];
                $details_counter++;
            }
        }
        if (($array['REALNAME']!="") && (stristr($array['REALNAME'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_REALNAME}: '.$array['REALNAME'];
                $details_counter++;
            }
        }
        if (($array['COUNTRY']!="") && (stristr($array['COUNTRY'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_COUNTRY}: '.$array['COUNTRY'];
                $details_counter++;
            }
        }
        if (($array['CITY']!="") && (stristr($array['CITY'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_CITY}: '.$array['CITY'];
                $details_counter++;
            }
        }
        if (($array['ORIGINAL_FROM']!="") && (stristr($array['ORIGINAL_FROM'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_ORIGINAL_FROM}: '.$array['ORIGINAL_FROM'];
                $details_counter++;
            }
        }
        if (($array['LANGUAGES']!="") && (stristr($array['LANGUAGES'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_LANGUAGES}: '.$array['LANGUAGES'];
                $details_counter++;
            }
        }
        if (($array['HOBBYS']!="") && (stristr($array['HOBBYS'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_HOBBYS}: '.$array['HOBBYS'];
                $details_counter++;
            }
        }
        if (($array['COMPUTER']!="") && (stristr($array['COMPUTER'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_COMPUTER}: '.$array['COMPUTER'];
                $details_counter++;
            }
        }
        if (($array['COMPUTER_OS']!="") && (stristr($array['COMPUTER_OS'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_COMPUTER_OS}: '.$array['COMPUTER_OS'];
                $details_counter++;
            }
        }
        if (($array['FAVORITE_FILM']!="") && (stristr($array['FAVORITE_FILM'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_FAVORITE_FILM}: '.$array['FAVORITE_FILM'];
                $details_counter++;
            }
        }
        if (($array['FAVORITE_BOOK']!="") && (stristr($array['FAVORITE_BOOK'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_FAVORITE_BOOK}: '.$array['FAVORITE_BOOK'];
                $details_counter++;
            }
        }
        if (($array['FAVORITE_MUSIK']!="") && (stristr($array['FAVORITE_MUSIK'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_FAVORITE_MUSIK}: '.$array['FAVORITE_MUSIK'];
                $details_counter++;
            }
        }
        if (($array['FAVORITE_SERIES']!="") && (stristr($array['FAVORITE_SERIES'],$suche) !== $bol)) {
            if ($details_counter<=$max_per_search) {
            if ($details_match!="") {
                    $details_match = $details_match.", ";
                }
                $details_match = $details_match.'{LANG_FAVORITE_SERIES}: '.$array['FAVORITE_SERIES'];
                $details_counter++;
            }
        }
        return $details_match;
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
    
    /**
     * Generate a tag cloud
     */
    function generate_cloud($language,$user_id = 0){
        include('classes/jforg_tags.php');     
        $result .= '<div id="tags">';
        $tags = new jforg_tags();
        $cloud = $tags->tag_cloud($user_id);
        foreach($cloud as $tag){
            $result2 = '';
             $result2 .= "<a href=\"/".$language;
             $result2 .="/tag/";
             $result2 .= $tag['tag_value'].".htm\""; 
             $result2 .= "class=\"class".$tag['class']."\" >";
             $result2 .= $tag['tag_value']."</a> ";
             $unsorted[$tag['tag_value']] = $result2;
        }
        ksort($unsorted);
        foreach($unsorted as $sorted){
            $result .= $sorted;
        }
        $result .= '</div>';
        return $result; 
    }
}
?>
