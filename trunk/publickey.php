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
$user_details = $user->get_details($user_id);
if ($language=="de") {
    $link = "mitglieder";
}
if ($language=="en") {
    $link = "members";
}
$content = '<a href="/'.$language.'/'.$link.'/'.$user_id.'-'.$user->get_nick($user_id).'.htm">{LANG_SHOW_USER_PAGE_OF} '.$user->get_nick($user_id).'</a><br /><br />';
$content = $content.str_replace("\n","<br />",$user_details['PUBLICKEY']);
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('FULLPAGE_HEADER','{LANG_PUBLICKEY_OF} '.$user->get_nick($user_id));
$template->replace('META_TITLE','{LANG_PUBLICKEY_OF} '.$user->get_nick($user_id));
$template->replace('LINK_GERMAN','/de/publickeys/'.$user_id.'-'.$user->get_nick($user_id).'.htm');
$template->replace('LINK_ENGLISH','/en/publickeys/'.$user_id.'-'.$user->get_nick($user_id).'.htm');
$template->translate($language);
include('includes/links.php');
$template->write();