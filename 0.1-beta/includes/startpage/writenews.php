<?php
include('classes/jforg_news.php');
include('functions/jforg_cleanurl.php');
$id = $_GET['id'];
$id = $id + 0;
if (!is_int($id)) {
    die("Invalid ID - $id");
}
$news = new jforg_news();
$template->set_frame('fullpage','tuerkis');
if ($language=='de') {
    $news_link = 'neuigkeiten';
} else {
    $news_link = 'news';
}
if ($user->is_admin($_SESSION['nick'],$_SESSION['passwd'])) {
    if (!empty($_POST['safe'])) {
        if ($id==0) {
            
            if(!empty($_POST['title'])) {
                $link_id = $news->write($_POST['title'],$_POST['text']);
                $url = '/'.$language.'/neuigkeiten/'.$link_id.'-'.cleanurl($_POST['title']).'.htm';
                header("Location: $url");
            } else {
                $pre_content = '<em>{LANG_FILLIN}</em><br /><br />{FULLPAGE_TEXT}';
                $template->replace_wiki('FULLPAGE_TEXT',$pre_content);
            }
        } else {
            if(!empty($_POST['title'])) {
                $news->update($_POST['id'],$_POST['title'],$_POST['text']);
                $url = '/'.$language.'/'.$news_link.'/'.$_POST['id'].'-'.cleanurl($_POST['title']).'.htm';
                header("Location: $url");
            } else {
                $pre_content = '<em>{LANG_FILLIN}</em><br /><br />{FULLPAGE_TEXT}';
                $template->replace_wiki('FULLPAGE_TEXT',$pre_content);
            }
        }
    }
    if( !empty( $_POST['delete'] ) )
    {
       if ( $id !== 0 )
       {
            $news->delete($id);
            $deleted = true;
       }
    }
    $template->replace('LOGIN','{LANG_LOGOUT}');
    $template->replace('REGISTER','{LANG_OPTIONS}');
    $template->replace('LINK_LOGIN','{LINK_LOGOUT}');
    $template->replace('LINK_REGISTER','{LINK_OPTIONS}');
    //Test ob bearbeiten oder anlegen
    if ($id==0) {
        $template->replace('META_TITLE','{LANG_EDIT}: {LANG_CREATE_NEW}');
        $template->replace('FULLPAGE_HEADER','{LANG_EDIT}: {LANG_CREATE_NEW}');
    } else {
        $news_by_id = $news->get_by_id($id);
        $title = $news_by_id['title'];
        $text = $news_by_id['text'];
        $template->replace('META_TITLE','{LANG_EDIT}: '.$title);
        $template->replace('FULLPAGE_HEADER','{LANG_EDIT}: '.$title);
    }
    $content = '<form method="post" action="">';
    if ( $deleted === true )
    {
        $content .='{LANG_NEWS_DELETED}<br/>';
    }
    $content .='<input style="width: 90%;" type="text" name="title" value="'.$title.'" /><br /><br />
                <input type="hidden" name="id" value="'.$id.'">
                <textarea name="text" rows="25" style="width: 90%;">'.$text.'</textarea><br /><br />
                <input name="safe" class="submit" type="submit" value="{LANG_SAFE}" />
                <input name="delete" class="submit" type="submit" value="{LANG_DELETE_NEWS}" />';
} else {
    die('You are not admin');
}
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/news_editor/'.$id.'.htm');
$template->replace('LINK_ENGLISH','/en/news_editor/'.$id.'.htm');
$template->replace('META_TITLE','JabberFriends.org');
?>
