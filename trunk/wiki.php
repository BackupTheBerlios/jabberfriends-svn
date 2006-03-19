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
if (isset($_GET['developer'])) {
    $template->set_frame('wikiwindow','yellow');
    $template->hover_on('yellow');
} else {
    $template->set_frame('wikiwindow','red');
    $template->hover_on('red');
}
$content = $wiki->get_by_id($id,$language);
$english_link = $content['en_id']."-".cleanurl($content['en_title']).".htm";
$german_link = $content['de_id']."-".cleanurl($content['de_title']).".htm";
if ($language=="de") {
    $template->replace('META_TITLE',$content['de_title']);
    $template->replace('WIKI_HEADER',$content['de_title']);
    $options = '
    <a href="">Diese Seite Drucken</a><br />
    <a href="/de/editor/'.$id.'.htm">Diese Seite Bearbeiten</a><br />
    <a href="">Versionen anzeigen</a><br />
    <a href="">Neue Seite anlegen</a><br />';
    $memberlink = '/de/mitglieder/';
} elseif ($language=="en") {
    $template->replace('META_TITLE',$content['en_title']);
    $template->replace('WIKI_HEADER',$content['en_title']);
    $options = '
    <a href="">Print this page</a><br />
    <a href="/en/editor/'.$id.'.htm">Edit this page</a><br />
    <a href="">List versions</a><br />
    <a href="">Create a new page</a><br />';
    $memberlink = '/en/members/';
}
$authors = $wiki->get_authors_by_id($id);
$info_text = '';
foreach($authors as $author) {
    $nickname = $user->get_nick($author['user_id']);
    $info_text = $info_text.'<a href="'.$memberlink.$author['user_id'].'-'.$nickname.'.htm">'.$nickname.'</a><br />';
}
$template->replace_wiki('WIKI_TEXT',$content['text']);
$template->replace('INFO_TEXT1',$options);
$template->replace('INFO_TEXT2',$info_text);
$template->replace('INFO_HEADER1','{LANG_OPTIONS}');
$template->replace('INFO_HEADER2','{LANG_AUTHORS}');
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN',"/de/wiki/$german_link");
$template->replace('LINK_ENGLISH',"/en/wiki/$english_link");
$template->replace('META_TITLE','JabberFriends.org');
$template->translate($language);
include('includes/links.php');
$template->write();
?>