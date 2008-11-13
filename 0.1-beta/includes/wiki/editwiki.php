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
$template->set_frame('fullpage','red');
SESSION_START();
if (!$user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    die('you are not login');
}
$template->replace('LOGIN','{LANG_LOGOUT}');
$template->replace('REGISTER','{LANG_OPTIONS}');
$template->replace('LINK_LOGIN','{LINK_LOGOUT}');
$template->replace('LINK_REGISTER','{LINK_OPTIONS}');
$make_translation = false;
$make_new = false;
if ($id!=0) {
    //Muss eine ältre Version angeziegt werden?
    if ($realid!='') {
        $wiki->set_id_language($id,$language,$realid);
    } else {
        $wiki->set_id_language($id,$language);
    }
    if (($wiki->exists_translation())&&(!$wiki->exists())) {
        $make_translation = true;
        $template->replace('META_TITLE','{LANG_EDIT}: {LANG_CREATE_NEW}');
        $template->replace('FULLPAGE_HEADER','{LANG_EDIT}: {LANG_CREATE_NEW}');
    } elseif ((!$wiki->exists_translation())&&(!$wiki->exists())) {
        die('Error. You cannot edit an non exist ID');
    } else {
        $w_title = $wiki->get_title();
        $w_text = $wiki->get_text();
        $template->replace('META_TITLE','{LANG_EDIT}: '.$w_title);
        $template->replace('FULLPAGE_HEADER','{LANG_EDIT}: '.$w_title);
    }
} else {
    $make_new = true;
    $template->replace('META_TITLE','{LANG_EDIT}: {LANG_CREATE_NEW}');
    $template->replace('FULLPAGE_HEADER','{LANG_EDIT}: {LANG_CREATE_NEW}');
}


//Vorschau parsen
if (!empty($_POST['preview'])) {
    $pre_content = $_POST['text'].'<br /><br />{FULLPAGE_TEXT}';
    $template->replace_wiki('FULLPAGE_TEXT',$pre_content);
    $w_text = $_POST['text'];
    $w_title = $_POST['title'];
}

//Speichern
if (!empty($_POST['safe'])) {
    
    //Einen neuen Artikel anlegen
    if ($make_new) {
        if (($_POST['title']!="")) {
            $link_id = $wiki->create_article($_POST['title'],$_POST['text'],$language,$user->get_id($_SESSION['nick']));
            $url = '/'.$language.'/wiki/'.$link_id.'-'.cleanurl($_POST['title']).'.htm';
            header("Location: $url");
        } else {
            $pre_content = '<em>{LANG_FILLIN}</em><br /><br />{FULLPAGE_TEXT}';
            $template->replace_wiki('FULLPAGE_TEXT',$pre_content);
        }

    //Einen Artikel updaten
    } elseif ($make_translation) {
        if (($_POST['title']!="")) {
            $wiki->translate_article($id,$_POST['title'],$_POST['text'],$language,$user->get_id($_SESSION['nick']));
            $url = '/'.$language.'/wiki/'.$id.'-'.cleanurl($_POST['title']).'.htm';
            header("Location: $url");
        } else {
            $pre_content = '<em>{LANG_FILLIN}</em><br /><br />{FULLPAGE_TEXT}';
            $template->replace_wiki('FULLPAGE_TEXT',$pre_content);
        }
    } else {
        $w_text = $wiki->get_text();
        if (($_POST['text']!=$w_text)) {
            $wiki->update_article($id,$_POST['text'],$language,$user->get_id($_SESSION['nick']));
        }
        $link_id = $id;
        $url = '/'.$language.'/wiki/'.$link_id.'-'.cleanurl($w_title).'.htm';
        header("Location: $url");
    }
}
$content = '<form method="post" action="">';
if (($make_new)||($make_translation)) {
    $content = $content.'<input style="width: 90%;" type="text" name="title" value="'.$w_title.'" /><br /><br />';
}
$content = $content.'<input type="hidden" name="id" value="'.$id.'">
              <textarea name="text" rows="25" style="width: 90%;">'.$w_text.'</textarea><br /><br />
              <br />
              <input class="submit" name="preview" type="submit" value="{LANG_PREVIEW}" /> <input name="safe" class="submit" type="submit" value="{LANG_SAFE}" />
            </form>';
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/editor/'.$id.'.htm');
$template->replace('LINK_ENGLISH','/en/editor/'.$id.'.htm');
$template->replace('FULLPAGE_TEXT',$content);
?>