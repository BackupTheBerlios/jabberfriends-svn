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
$realid = $realid + 0;
$template->set_frame('wikiwindow','red');

//Muss eine ältre Version angeziegt werden?
if ($realid!='') {
    $wiki->set_id_language($id,$language,$realid);
} else {
    $wiki->set_id_language($id,$language);
}
//Pruefe ob die Seite existiert, oder keine Uebersetzung da ist
if (($wiki->exists_translation())&&(!$wiki->exists())) {
    $wiki_text = "Für diesen Eintrag gibt es keine Übersetzung";
    $wiki_title = '{LANG_NOTRANSLATION}';
    $show_edit_option = true;
    $show_version_option = false;
} elseif ((!$wiki->exists_translation())&&(!$wiki->exists())) {
    $wiki_text = "Im Wiki existiert diese Seite noch nicht";
    $wiki_title = '{LANG_NOTFOUND}';
    $show_edit_option = false;
    $show_version_option = false;
} else {
    $wiki_text = $wiki->get_text();
    $wiki_title = $wiki->get_title();
    $show_edit_option = true;
    $show_version_option = true;
}
$english_link = '/en/wiki/'.$id.'-'.cleanurl($wiki->get_english_link()).'.htm';
$german_link = '/de/wiki/'.$id.'-'.cleanurl($wiki->get_german_link()).'.htm';
$template->replace('META_TITLE',$wiki_title);
$template->replace('WIKI_HEADER',$wiki_title);
if ($language=="de") {
    if ($show_edit_option) {
        if ($realid!='') {
            $options = '<a href="/de/editor/'.$id.'_'.$realid.'.htm">Diese Seite Bearbeiten</a><br />';
        } else {
            $options = '<a href="/de/editor/'.$id.'.htm">Diese Seite Bearbeiten</a><br />';
        }
    }
    if ($show_version_option) {
        $options .= '<a href="/de/wiki/versionen_von_'.$id.'.htm">Versionen anzeigen</a><br /><br />';
    }
    $options .= '<a href="/de/editor/neu.htm">Neue Seite anlegen</a><br />';
    $memberlink = '/de/mitglieder/';
} elseif ($language=="en") {
    if ($show_edit_option) {
        if ($realid!='') {
            $options = '<a href="/en/editor/'.$id.'_'.$realid.'.htm">Edit this page</a><br />';
        } else {
            $options = '<a href="/en/editor/'.$id.'.htm">Edit this page</a><br />';
        }
    }
    if ($show_version_option) {
        $options .= '<a href="/en/wiki/versions_of_'.$id.'.htm">List versions</a><br /><br />';
    }
    $options .= '<a href="/en/editor/new.htm">Create a new page</a><br />';
    $memberlink = '/en/members/';
}
$authors = $wiki->get_authors();
$info_text = '';
foreach($authors as $author) {
    $nickname = $user->get_nick($author);
    $info_text = $info_text.'<a href="'.$memberlink.$nickname.'.htm">'.$nickname.'</a><br />';
}
$template->replace_wiki('WIKI_TEXT',$wiki_text);
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
