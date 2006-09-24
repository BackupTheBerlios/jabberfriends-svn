<?php
include('classes/jforg_cite.php');
include('functions/jforg_cleanurl.php');
$cite = new jforg_cite();
$template->set_frame('fullpage','lila');
if (!$user->login($_SESSION['nick'],$_SESSION['passwd'])) {
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
        $url = '/'.$language.'/portal/citedb/'.$cite_link.'-'.$link_id.'.htm';
        header("Location: $url");
    } else {
        $pre_content = '<em>{LANG_FILLIN}</em><br /><br />{FULLPAGE_TEXT}';
        $template->replace_wiki('FULLPAGE_TEXT',$pre_content);
    }
}
$content = '<form method="post" action=""><textarea name="text" rows="15" style="width: 90%;">'.$zitat.'</textarea><br /><br /><input class="submit" name="preview" type="submit" value="{LANG_PREVIEW}" /> <input name="safe" class="submit" type="submit" value="{LANG_ADDCITE}" /></form>';
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/portal/citedb/zitat-hinzufuegen.htm');
$template->replace('LINK_ENGLISH','/en/portal/citedb/add-cite.htm');
$template->replace('META_TITLE','{LANG_ADDCITE}');
$template->replace('FULLPAGE_HEADER','{LANG_ADDCITE}');
$template->replace('FULLPAGE_TEXT',$content);
?>