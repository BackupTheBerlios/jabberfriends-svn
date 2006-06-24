<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_wiki.php');
include('classes/jforg_cleanurl.php');
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
    if ($id!=0) {
        $wiki->set_id_language($id,$language);
        $w_title = $wiki->get_title();
        $w_text = $wiki->get_text();
    }
    if (!empty($_POST['preview'])) {
        $pre_content = $_POST['text'].'<br /><br />{FULLPAGE_TEXT}';
        $template->replace_wiki('FULLPAGE_TEXT',$pre_content);
        $w_text = $_POST['text'];
        $w_title = $_POST['title'];
    }
    if (!empty($_POST['safe'])) {
        if ($id==0) {
            $link_id = $wiki->create_article($_POST['title'],$_POST['text'],$language,$user->get_id($_SESSION['nick']));
        } else {
            $wiki->update_article($id,$_POST['title'],$_POST['text'],$language,$user->get_id($_SESSION['nick']));
            $link_id = $id;
        }
        $wiki->update_article($id,$_POST['title'],$_POST['text'],$language,$user->get_id($_SESSION['nick']));
        $url = '/'.$language.'/wiki/'.$link_id.'-'.cleanurl($_POST['title']).'.htm';
        header("Location: $url");
    }
    $content = '<form method="post" action="">
              <input style="width: 90%;" type="text" name="title" value="'.$w_title.'" />
              <input type="hidden" name="id" value="'.$id.'">
              <br /><br />
              <textarea name="text" rows="25" style="width: 90%;">'.$w_text.'</textarea><br /><br />
              <br />
              <input class="submit" name="preview" type="submit" value="{LANG_PREVIEW}" /> <input name="safe" class="submit" type="submit" value="{LANG_SAFE}" />
            </form>';
} else {
    $template->replace('LOGIN','{LANG_LOGIN}');
    $template->replace('REGISTER','{LANG_REGISTER}');
    $content = '{LANG_SHOULDLOGGEDIN}';
}

$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/editor/'.$id.'.htm');
$template->replace('LINK_ENGLISH','/en/editor/'.$id.'.htm');
$template->replace('META_TITLE','Editor');
$template->replace('FULLPAGE_HEADER','Editor');
$template->replace('FULLPAGE_TEXT',$content);
$template->translate($language);
include('includes/links.php');
$template->write();
?>