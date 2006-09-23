<?php
include('classes/jforg_wiki.php');
include('functions/jforg_cleanurl.php');
$wiki = new jforg_wiki();
$id = $_GET['id'];
$id = $id + 0;
if (!is_int($id)) {
    die("Invalid ID - $id");
}
$realid = $_GET['realid'];
$id = $id + 0;
$template->set_frame('wikiwindow','red');

//Muss eine ältre Version angeziegt werden?
if ($realid!='') {
    $wiki->set_id_language($id,$language,$realid);
} else {
    $wiki->set_id_language($id,$language);
}
$english_link = '/en/wiki/'.$id.'-'.cleanurl($wiki->get_english_link()).'.htm';
$german_link = '/de/wiki/'.$id.'-'.cleanurl($wiki->get_german_link()).'.htm';

$template->replace('META_TITLE',$wiki->get_title());
$template->replace('WIKI_HEADER',$wiki->get_title());

if ($language=="de") {
    if ($realid!='') {
        $options = '<a href="/de/editor/'.$id.'_'.$realid.'.htm">Diese Seite Bearbeiten</a><br />';
    } else {
        $options = '<a href="/de/editor/'.$id.'.htm">Diese Seite Bearbeiten</a><br />';
    }
    $options = $options.'
    <a href="/de/wiki/versionen_von_'.$id.'.htm">Versionen anzeigen</a><br /><br />
    <a href="/de/editor/neu.htm">Neue Seite anlegen</a><br />';
    $memberlink = '/de/mitglieder/';
} elseif ($language=="en") {
    if ($realid!='') {
        $options = '<a href="/en/editor/'.$id.'_'.$realid.'.htm">Edit this page</a><br />';
    } else {
        $options = '<a href="/en/editor/'.$id.'.htm">Edit this page</a><br />';
    }
    $options = $options.'
    <a href="/en/wiki/versions_of_'.$id.'.htm">List versions</a><br /><br />
    <a href="/en/editor/new.htm">Create a new page</a><br />';
    $memberlink = '/en/members/';
}
$authors = $wiki->get_authors();
$info_text = '';
foreach($authors as $author) {
    $nickname = $user->get_nick($author);
    $info_text = $info_text.'<a href="'.$memberlink.$author.'-'.$nickname.'.htm">'.$nickname.'</a><br />';
}
$template->replace_wiki('WIKI_TEXT',$wiki->get_text());
$template->replace('INFO_TEXT1',$options);
$template->replace('INFO_TEXT2',$info_text);
$template->replace('INFO_HEADER1','{LANG_OPTIONS}');
$template->replace('INFO_HEADER2','{LANG_AUTHORS}');
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN',$german_link);
$template->replace('LINK_ENGLISH',$english_link);
$template->replace('META_TITLE','JabberFriends.org');
?>