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
$text = get_text(2,$language);
$usersearch = new jforg_usersearch();
$content = $text.'<br /><br /><form action="{LINK_SEARCH}" method="post"><input name="search" type="text" />&nbsp;<input class="submit" name="submit" type="submit" value="{LANG_SEARCH}" /></form>';
$content = $content.'<br /><b>{LANG_LAST_MEMBERS}</b>';
$array = $usersearch->search_last(5);
foreach($array as $row) {
    $content = $content.'<br />{LANG_NICK}: <a href="'.$row['id'].'-'.$row['nick'].'.htm">'.$row['nick'].'</a>, {LANG_JID}: <a href="'.$row['id'].'-'.$row['nick'].'.htm">'.$row['jid'].'</a>';
}
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