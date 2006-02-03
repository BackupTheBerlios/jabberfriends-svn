<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_usersearch.php');
include('classes/jforg_gettext.php');
$user = new jforg_user();
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$template = new jforg_template();
$user = new jforg_user();
$template->set_path('design');
$template->set_frame('fullpage','green');
$template->hover_on('green');
SESSION_START();
if ($user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    $template->replace('LOGIN','{LANG_LOGOUT}');
    $template->replace('REGISTER','{LANG_OPTIONS}');
    $template->replace('LINK_LOGIN','{LINK_LOGOUT}');
    $template->replace('LINK_REGISTER','{LINK_OPTIONS}');
} else {
    $template->replace('LOGIN','{LANG_LOGIN}');
    $template->replace('REGISTER','{LANG_REGISTER}');
}
$usersearch = new jforg_usersearch();
$text = get_text(2,$language);
if ($_GET['search']=='normal') {
    $content = $text.'<br /><br /><form action="{LINK_SEARCH}" method="post"><input value="'.$_POST['search'].'" name="search" type="text" />&nbsp;<input class="submit" name="submit" type="submit" value="{LANG_SEARCH}" /></form>';
}
if ($_GET['search']=='extended') {
    
}
$max_per_search = 5;
if (isset($_POST['search'])) {
    if (preg_match('/.{3,}/',$_POST['search'])) {
        $array = $usersearch->search_all($_POST['search']);
        $result_counter = 0;
        $number = $usersearch->get_number_of();
        $content = $content."<br /><br /><b>$number {LANG_MATCHES_FOR} ".$_POST['search']."</b><ol>";
        foreach($array as $row) {
            $details_match = "";
            $details_counter = 1;
            $id = $row['id'];
            $nick = $user->get_nick($id);
            $content = $content."<li><b><a href=\"$id-$nick.htm\">$nick</a></b><br />";
            if(stristr($row['REALNAME'],$_POST['search']) !== FALSE) {
                if ($details_counter<=$max_per_search) {
                    if ($details_match!="") {
                        $details_match = $details_match.", ";
                    }
                    $details_match = $details_match.'{LANG_REALNAME}: '.$row['REALNAME'];
                    $details_counter++;
                }
            }
            if(stristr($row['COUNTRY'],$_POST['search']) !== FALSE) {
                if ($details_counter<=$max_per_search) {
                    if ($details_match!="") {
                        $details_match = $details_match.", ";
                    }
                    $details_match = $details_match.'{LANG_COUNTRY}: '.$row['COUNTRY'];
                    $details_counter++;
                }
            }
            if(stristr($row['CITY'],$_POST['search']) !== FALSE) {
                if ($details_counter<=$max_per_search) {
                    if ($details_match!="") {
                        $details_match = $details_match.", ";
                    }
                    $details_match = $details_match.'{LANG_CITY}: '.$row['CITY'];
                    $details_counter++;
                }
            }
            if(stristr($row['ORIGINAL_FROM'],$_POST['search']) !== FALSE) {
                if ($details_counter<=$max_per_search) {
                    if ($details_match!="") {
                        $details_match = $details_match.", ";
                    }
                    $details_match = $details_match.'{LANG_ORIGINAL_FROM}: '.$row['ORIGINAL_FROM'];
                    $details_counter++;
                }
            }
            if(stristr($row['LANGUAGES'],$_POST['search']) !== FALSE) {
                if ($details_counter<=$max_per_search) {
                    if ($details_match!="") {
                        $details_match = $details_match.", ";
                    }
                    $details_match = $details_match.'{LANG_LANGUAGES}: '.$row['LANGUAGES'];
                    $details_counter++;
                }
            }
            if(stristr($row['HOBBYS'],$_POST['search']) !== FALSE) {
                if ($details_counter<=$max_per_search) {
                    if ($details_match!="") {
                        $details_match = $details_match.", ";
                    }
                    $details_match = $details_match.'{LANG_HOBBYS}: '.$row['HOBBYS'];
                    $details_counter++;
                }
            }
            if(stristr($row['COMPUTER'],$_POST['search']) !== FALSE) {
                if ($details_counter<=$max_per_search) {
                    if ($details_match!="") {
                        $details_match = $details_match.", ";
                    }
                    $details_match = $details_match.'{LANG_COMPUTER}: '.$row['COMPUTER'];
                    $details_counter++;
                }
            }
            if(stristr($row['COMPUTER_OS'],$_POST['search']) !== FALSE) {
                if ($details_counter<=$max_per_search) {
                    if ($details_match!="") {
                        $details_match = $details_match.", ";
                    }
                    $details_match = $details_match.'{LANG_COMPUTER_OS}: '.$row['COMPUTER_OS'];
                    $details_counter++;
                }
            }
            if(stristr($row['FAVORITE_FILM'],$_POST['search']) !== FALSE) {
                if ($details_counter<=$max_per_search) {
                    if ($details_match!="") {
                        $details_match = $details_match.", ";
                    }
                    $details_match = $details_match.'{LANG_FAVORITE_FILM}: '.$row['FAVORITE_FILM'];
                    $details_counter++;
                }
            }
            if(stristr($row['FAVORITE_BOOK'],$_POST['search']) !== FALSE) {
                if ($details_counter<=$max_per_search) {
                    if ($details_match!="") {
                        $details_match = $details_match.", ";
                    }
                    $details_match = $details_match.'{LANG_FAVORITE_BOOK}: '.$row['FAVORITE_BOOK'];
                    $details_counter++;
                }
            }
            if(stristr($row['FAVORITE_MUSIK'],$_POST['search']) !== FALSE) {
                if ($details_counter<=$max_per_search) {
                    if ($details_match!="") {
                        $details_match = $details_match.", ";
                    }
                    $details_match = $details_match.'{LANG_FAVORITE_MUSIK}: '.$row['FAVORITE_MUSIK'];
                    $details_counter++;
                }
            }
            if(stristr($row['FAVORITE_SERIES'],$_POST['search']) !== FALSE) {
                if ($details_counter<=$max_per_search) {
                    if ($details_match!="") {
                        $details_match = $details_match.", ";
                    }
                    $details_match = $details_match.'{LANG_FAVORITE_SERIES}: '.$row['FAVORITE_SERIES'];
                    $details_counter++;
                }
            } 
            $content = $content.$details_match."</li>";
        }
    } else {
        $content = $content."<br /><br /><b><em>{LANG_3CHAR}</em></b><br /><br />";
    }
}
$content = $content."</ol>";
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
if ($_GET['search']=='normal') {
    $template->replace('LINK_GERMAN','/de/mitglieder/suche.htm');
    $template->replace('LINK_ENGLISH','/en/members/search.htm');
}
if ($_GET['search']=='extended') {
    $template->replace('LINK_GERMAN','/de/mitglieder/erweiterte_suche.htm');
    $template->replace('LINK_ENGLISH','/en/members/extended_search.htm');
}
$template->replace('META_TITLE','{LANG_SEARCH}');
$template->replace('FULLPAGE_HEADER','{LANG_SEARCH}');
$template->replace('FULLPAGE_TEXT',$content);
$template->translate($language);
include('includes/links.php');
$template->write();
?>