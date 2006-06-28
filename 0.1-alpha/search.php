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
$content = '<form action="{LINK_SEARCH}" method="post"><input value="'.$_POST['search'].'" name="search" type="text" />&nbsp;<input class="submit" name="submit" type="submit" value="{LANG_SEARCH}" /></form>';
$max_per_search = 5;
if (isset($_POST['search'])) {
    if (preg_match('/.{3,}/',$_POST['search'])) {
        $array = $usersearch->search_all($_POST['search']);
        $result_counter = 0;
        $number = $usersearch->get_number_of();
        $content = $content."<br /><br /><b>$number {LANG_MATCHES_FOR} ".$_POST['search']."</b><ol>";
        foreach($array as $row) {
            $id = $row['id'];
            $nick = $user->get_nick($id);
            $details_match = $template->format_userdetails($row,5,$_POST['search']);
            $content = $content."<li><b><a href=\"$id-$nick.htm\">$nick</a></b><br />$details_match";
        }
    } else {
        $content = $content."<br /><br /><b><em>{LANG_3CHAR}</em></b><br /><br />";
    }
}
$content = $content."</ol>";
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/mitglieder/suche.htm');
$template->replace('LINK_ENGLISH','/en/members/search.htm');
$template->replace('META_TITLE','{LANG_SEARCH}');
$template->replace('FULLPAGE_HEADER','{LANG_SEARCH}');
$template->replace('FULLPAGE_TEXT',$content);
$template->translate($language);
include('includes/links.php');
$template->write();
?>