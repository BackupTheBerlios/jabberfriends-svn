<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
if (is_int($_GET['id']+0)) {
    $user_id = $_GET['id'];
} else {
    die('ID is not an int'.$_GET['id']);
}
$user = new jforg_user();
$template = new jforg_template();
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
$content = '<table cellpadding="0" cellspacing="2">';
$user_details = $user->get_details($user_id);
$user_details = array_map('htmlentities',$user_details);
$content = $content.'<tr><td valign="top">{LANG_NICK}</td><td valign="top">'.$user->get_nick($user_id).'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_JID}</td><td valign="top">'.$user->get_jid($user_id).'</td></tr>';
$content = $content.'<tr><td colspan="2"><br /><b>{LANG_ABOUT} '.$user->get_nick($user_id).'</b></td></tr>';
$content = $content.'<tr><td valign="top" class="left">{LANG_REALNAME}</td><td valign="top" class="right">'.$user_details['REALNAME'].'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_BIRTHDATE}</td><td valign="top">'.$user_details['BIRTHDATE'].'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_COUNTRY}</td><td valign="top">'.$user_details['COUNTRY'].'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_CITY}</td><td valign="top">'.$user_details['CITY'].'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_ORIGINAL_FROM}</td><td valign="top">'.$user_details['ORIGINAL_FROM'].'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_LANGUAGES}</td><td valign="top">'.$user_details['LANGUAGES'].'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_HOBBYS}</td><td valign="top">'.$user_details['HOBBYS'].'</td></tr>';
$content = $content.'<tr><td colspan="2"><br /><b>{LANG_COMPUTER_OF} '.$user->get_nick($user_id).'</b></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_COMPUTER}</td><td valign="top">'.$user_details['COMPUTER'].'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_COMPUTER_OS}</td><td valign="top">'.$user_details['COMPUTER_OS'].'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_GEEKCODE}</td><td valign="top">'.$user_details['GEEKCODE'].'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_PUBLICKEY}</td><td valign="top">';
if ($user_details['PUBLICKEY']!="") { $content = $content.'<a href="/'.$language.'/publickeys/'.$user_id.'-'.$user->get_nick($user_id).'.htm">{LANG_SHOW_PUBLICKEY}</a>'; }
$content = $content.'</td></tr>';
$content = $content.'<tr><td colspan="2"><br /><b>{LANG_FAVORITES} '.$user->get_nick($user_id).'</b></td></tr>';
$content = $content.'<tr><td valign="top">{LANG_FAVORITE_FILM}</td><td valign="top">'.$user_details['FAVORITE_FILM'].'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_FAVORITE_SERIES}</td><td valign="top">'.$user_details['FAVORITE_SERIES'].'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_FAVORITE_MUSIK}</td><td valign="top">'.$user_details['FAVORITE_MUSIK'].'</td></tr>';
$content = $content.'<tr><td valign="top">{LANG_FAVORITE_BOOK}</td><td valign="top">'.$user_details['FAVORITE_BOOK'].'</td></tr>';
$content = $content.'<tr><td colspan="2"><br /><b>Tags</b></td></tr>';
$template->replace('FULLPAGE_HEADER','{LANG_USER_PAGE_OF} '.$user->get_nick($user_id));
$template->replace('META_TITLE','{LANG_USER_PAGE_OF} '.$user->get_nick($user_id));
$content = $content.'</table>';
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LINK_GERMAN','/de/mitglieder/'.$user_id.'-'.$user->get_nick($user_id).'.htm');
$template->replace('LINK_ENGLISH','/en/members/'.$user_id.'-'.$user->get_nick($user_id).'.htm');
$template->translate($language);
include('includes/links.php');
$template->write();
?>