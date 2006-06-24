<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_wiki.php');
include('classes/jforg_cleanurl.php');
$user = new jforg_user();
$wiki = new jforg_wiki();
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$id = $_GET['id'];
$id = $id + 0;
if (!is_int($id)) {
    die("Invalid ID - $id");
}
$template = new jforg_template();
$user = new jforg_user();
$template->set_path('design');
$template->set_frame('fullpage','red');
$template->hover_on('red');
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
$wiki->set_id_language($id,$language);
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('META_TITLE','{LANG_VERSIONS}: '.$wiki->get_title());
$template->replace('FULLPAGE_HEADER','{LANG_VERSIONS}: '.$wiki->get_title());
$versionen = $wiki->get_versions();
foreach($versionen as $version) {
    $nick = $user->get_nick($version['user_id']);
    $datum=date('d.m.Y H:i', $version['datetime']);
    $content .= '[<a href="/'.$language.'/wiki/version_'.$id.'_'.$version['id'].'.htm">{LANG_VIEW}</a>] [<a href="/'.$language.'/editor/'.$id.'_'.$version['id'].'.htm">{LANG_EDIT}</a>] - Geschrieben von <a href="/'.$language.'/mitglieder/'.$version['user_id'].'-'.cleanurl($nick).'.htm">'.$nick.'</a> am '.$datum.'<br />';
}
$english_link = '/en/wiki/versions_of_'.$id.'.htm';
$german_link = '/de/wiki/versionen_von_'.$id.'.htm';
$template->replace('LINK_GERMAN',$german_link);
$template->replace('LINK_ENGLISH',$english_link);
$template->replace('FULLPAGE_TEXT',$content);
$template->translate($language);
include('includes/links.php');
$template->write();
?>