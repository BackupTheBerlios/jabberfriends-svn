<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_tags.php');
$user = new jforg_user();
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$template = new jforg_template();
$user = new jforg_user();
$tags = new jforg_tags();
$template->set_path('design');
$template->set_frame('formpage','green');
$template->hover_on('green');
SESSION_START();
$user_id = $user->get_id($_SESSION['nick']);
if (!$user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    die('You are not logged in');  
}
////var $user_tags[];
$user_tags 	= $tags->get_user_tags($user_id);

$content = $content.'<tr><td valign="top">{LANG_TAGS}</td><td><textarea cols="200" rows="3" name="tags" value"">'; 
$content = $content.'</textarea></td></tr>';
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LOGIN','{LANG_LOGOUT}');
$template->replace('REGISTER','{LANG_OPTIONS}');
$template->replace('LINK_LOGIN','{LINK_LOGOUT}');
$template->replace('LINK_REGISTER','{LINK_OPTIONS}');
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
//$template->replace('FORM_ACTION','');
$template->replace('LINK_GERMAN','/de/tags_aendern.htm');
$template->replace('LINK_ENGLISH','/en/change_tags.htm');
$template->replace('META_TITLE','{LANG_CHANGEDETAILS}');
$template->replace('FULLPAGE_HEADER','{LANG_CHANGETAGS}');
$template->translate($language);
include('includes/links.php');
$template->write();
?>