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
$max_per_search = 5;
$text = get_text(2,$language);
$usersearch = new jforg_usersearch();
$content = $text.'<br /><br /><form action="{LINK_SEARCH}" method="post"><input name="search" type="text" />&nbsp;<input class="submit" name="submit" type="submit" value="{LANG_SEARCH}" /></form>';
$content = $content.'<br /><br /><b>{LANG_LAST_MEMBERS}</b><ol>';
$array = $usersearch->search_last(5);
foreach($array as $row) {
    $details = $user->get_details($row['id']);
    $details = array_map('htmlentities',$details);
    $details_match = "";
    $details_counter = 1;
    if($details['REALNAME']!="") {
        if ($details_counter<=$max_per_search) {
           if ($details_match!="") {
                $details_match = $details_match.", ";
            }
            $details_match = $details_match.'{LANG_REALNAME}: '.$details['REALNAME'];
            $details_counter++;
        }
    }
    if($details['COUNTRY']!="") {
        if ($details_counter<=$max_per_search) {
           if ($details_match!="") {
                $details_match = $details_match.", ";
            }
            $details_match = $details_match.'{LANG_COUNTRY}: '.$details['COUNTRY'];
            $details_counter++;
        }
    }
    if($details['CITY']!="") {
        if ($details_counter<=$max_per_search) {
           if ($details_match!="") {
                $details_match = $details_match.", ";
            }
            $details_match = $details_match.'{LANG_CITY}: '.$details['CITY'];
            $details_counter++;
        }
    }
    if($details['ORIGINAL_FROM']!="") {
        if ($details_counter<=$max_per_search) {
           if ($details_match!="") {
                $details_match = $details_match.", ";
            }
            $details_match = $details_match.'{LANG_ORIGINAL_FROM}: '.$details['ORIGINAL_FROM'];
            $details_counter++;
        }
    }
    if($details['LANGUAGES']!="") {
        if ($details_counter<=$max_per_search) {
           if ($details_match!="") {
                $details_match = $details_match.", ";
            }
            $details_match = $details_match.'{LANG_LANGUAGES}: '.$details['LANGUAGES'];
            $details_counter++;
        }
    }
    if($details['HOBBYS']!="") {
        if ($details_counter<=$max_per_search) {
           if ($details_match!="") {
                $details_match = $details_match.", ";
            }
            $details_match = $details_match.'{LANG_HOBBYS}: '.$details['HOBBYS'];
            $details_counter++;
        }
    }
    if($details['COMPUTER']!="") {
        if ($details_counter<=$max_per_search) {
           if ($details_match!="") {
                $details_match = $details_match.", ";
            }
            $details_match = $details_match.'{LANG_COMPUTER}: '.$details['COMPUTER'];
            $details_counter++;
        }
    }
    if($details['COMPUTER_OS']!="") {
        if ($details_counter<=$max_per_search) {
           if ($details_match!="") {
                $details_match = $details_match.", ";
            }
            $details_match = $details_match.'{LANG_COMPUTER_OS}: '.$details['COMPUTER_OS'];
            $details_counter++;
        }
    }
    if($details['FAVORITE_FILM']!="") {
        if ($details_counter<=$max_per_search) {
           if ($details_match!="") {
                $details_match = $details_match.", ";
            }
            $details_match = $details_match.'{LANG_FAVORITE_FILM}: '.$details['FAVORITE_FILM'];
            $details_counter++;
        }
    }
    if($details['FAVORITE_BOOK']!="") {
        if ($details_counter<=$max_per_search) {
           if ($details_match!="") {
                $details_match = $details_match.", ";
            }
            $details_match = $details_match.'{LANG_FAVORITE_BOOK}: '.$details['FAVORITE_BOOK'];
            $details_counter++;
        }
    }
    if($details['FAVORITE_MUSIK']!="") {
        if ($details_counter<=$max_per_search) {
           if ($details_match!="") {
                $details_match = $details_match.", ";
            }
            $details_match = $details_match.'{LANG_FAVORITE_MUSIK}: '.$details['FAVORITE_MUSIK'];
            $details_counter++;
        }
    }
    if($details['FAVORITE_SERIES']!="") {
        if ($details_counter<=$max_per_search) {
           if ($details_match!="") {
                $details_match = $details_match.", ";
            }
            $details_match = $details_match.'{LANG_FAVORITE_SERIES}: '.$details['FAVORITE_SERIES'];
            $details_counter++;
        }
    }
    $content = $content.'<li><b><a href="'.$row['id'].'-'.$row['nick'].'.htm">'.$row['nick'].'</a></b><br />'.$details_match.'</li>';
}
$content = $content.'</ol>';
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/mitglieder/');
$template->replace('LINK_ENGLISH','/en/members/');
$template->replace('META_TITLE','{LANG_MEMBERS}');
$template->replace('FULLPAGE_HEADER','{LANG_MEMBERS}');
$template->replace('FULLPAGE_TEXT',$content);
$template->translate($language);
include('includes/links.php');
$template->write();
?>
