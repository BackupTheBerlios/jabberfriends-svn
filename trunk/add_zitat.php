<?php
include('includes/config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');
include('classes/jforg_cite.php');
include('classes/jforg_cleanurl.php');
$user = new jforg_user();
if (in_array($_GET['lang'],$config['languages'])) {
    $language = $_GET['lang'];
} else {
    die('Language ist nicht bekannt');
}
$template = new jforg_template();
$user = new jforg_user();
$cite = new jforg_cite();
$template->set_path('design');
$template->set_frame('fullpage','lila');
$template->hover_on('lila');
SESSION_START();
if ($user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    $template->replace('LOGIN','{LANG_LOGOUT}');
    $template->replace('REGISTER','{LANG_OPTIONS}');
    $template->replace('LINK_LOGIN','{LINK_LOGOUT}');
    $template->replace('LINK_REGISTER','{LINK_OPTIONS}');
} else {
    die('You are not logged in');
}
if (!empty($_POST['preview'])) {
    $zitat = $_POST['text'];
    $pre_content = $template->highlight_cite($_POST['text']).'<br /><br />{FULLPAGE_TEXT}';
    $template->replace_wiki('FULLPAGE_TEXT',$pre_content);
}
if (!empty($_POST['safe'])) {
    if ($language=='de') {
        $cite_link = 'zitat';
    } else {
        $cite_link = 'cite';
    }
    if(!empty($_POST['text'])) {
        $link_id = $cite->write($_POST['text'],$user->get_id($_SESSION['nick']),$language);
        $url = '/'.$language.'/portal/'.$cite_link.'-'.$link_id.'.htm';
        header("Location: $url");
    } else {
        $pre_content = '<em>{LANG_FILLIN}</em><br /><br />{FULLPAGE_TEXT}';
        $template->replace_wiki('FULLPAGE_TEXT',$pre_content);
    }
}
$content = '<form method="post" action=""><textarea name="text" rows="15" style="width: 90%;">'.$zitat.'</textarea><br /><br /><input class="submit" name="preview" type="submit" value="{LANG_PREVIEW}" /> <input name="safe" class="submit" type="submit" value="{LANG_ADDCITE}" /></form>';
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/portal/zitat-hinzufuegen.htm');
$template->replace('LINK_ENGLISH','/en/portal/add-cite.htm');
$template->replace('META_TITLE','{LANG_ADDCITE}');
$template->replace('FULLPAGE_HEADER','{LANG_ADDCITE}');
$template->replace('FULLPAGE_TEXT',$content);
$template->translate($language);
include('includes/links.php');
$template->write();
?>