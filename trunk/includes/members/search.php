<?php
include('classes/jforg_usersearch.php');
$template->set_frame('fullpage','green');
$usersearch = new jforg_usersearch();
$searched_nick = ($_GET['search'] == '') ? $_POST['search'] : $_GET['search'];
$content = '<form action="{LINK_SEARCH}" method="post"><input value="'.$searched_nick.'" name="search" type="text" />&nbsp;<input class="submit" name="submit" type="submit" value="{LANG_SEARCH}" /></form>';
$max_per_search = 5;
if( isset($_GET['search']) AND !isset( $_POST['search'] ) )
{
    $_POST['search'] = $_GET['search'];
}
if (isset($_POST['search'])) {
    if (preg_match('/.{3,}/',$_POST['search'])) {
        $array = $usersearch->search_all($_POST['search']);
        $result_counter = 0;
        $number = $usersearch->get_number_of();
        $content = $content."<br /><br /><b>$number {LANG_MATCHES_FOR} ".$_POST['search']."</b><ol>";
        foreach($array as $row) {
            $id = $row['id'];
            $nick = $user->get_nick($id);
            $details_match = $template->format_userdetails($row,5,$_POST['search']);
            $content = $content."<li><b><a href=\"../$nick.htm\">$nick</a></b><br />$details_match";
        }
    } else {
        $content = $content."<br /><br /><b><em>{LANG_3CHAR}</em></b><br /><br />";
    }
}
$content = $content."</ol>";
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/mitglieder/suche/');
$template->replace('LINK_ENGLISH','/en/members/search/');
$template->replace('META_TITLE','{LANG_SEARCH}');
$template->replace('FULLPAGE_HEADER','{LANG_SEARCH}');
$template->replace('FULLPAGE_TEXT',$content);
?>
