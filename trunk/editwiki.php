<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_wiki.php');
$wiki = new jforg_wiki();
$user = new jforg_user();
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
    $wiki_content = $wiki->get_by_id($id,$language);
    $content = '<form method="post">
              <input style="width: 90%;" type="text" name="title" value="'.$wiki_content['de_title'].'" /><br /><br />
              <textarea rows="25" style="width: 90%;">'.$wiki_content['text'].'</textarea><br /><br />
              <input class="submit" type="submit" value="{LANG_PREVIEW}" /> <input class="submit" type="submit" value="{LANG_SAFE}" />
            </form>';
} else {
    $template->replace('LOGIN','{LANG_LOGIN}');
    $template->replace('REGISTER','{LANG_REGISTER}');
    $content = '{LANG_SHOULDLOGGEDIN}';
}

$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/');
$template->replace('LINK_ENGLISH','/en/');
$template->replace('META_TITLE','Editor');
$template->replace('FULLPAGE_HEADER','Editor');
$template->replace('FULLPAGE_TEXT',$content);
$template->translate($language);
include('includes/links.php');
$template->write();
?>