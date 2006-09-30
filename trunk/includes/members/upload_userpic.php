<?php
include('functions/jforg_gettext.php');
$template->set_frame('fullpage','green');
if (!$user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    die('You are not logged in');  
}
$user_id = $user->get_id($_SESSION['nick']);
if ($_POST['upload']!='') {
    $file = $_FILES['file'];
    if ($file['error']!=0) {
        $error = '<br /><br /><em>{LANG_ERROR_WHILE_UPLOADING}</em>';
    } else {
        if ($file['type']!='image/jpeg') {
            $error = '<br /><br /><em>{LANG_UNACCEPT_FILEFORMAT}</em>';
        } else {
            $image_src = imageCreateFromJPEG($file['tmp_name']);
            $image = imageCreate(150,150);
            $image_src_h = imagesx($image_src);
            $image_src_w = imagesy($image_src);
            imagecopyresampled($image,$image_src,0,0,0,0,150,150,$image_src_w,$image_src_h);
            imagejpeg($image,'upload/userpics/'.$user_id.'.jpg',100);
        }
    }
}
$content .= '<table style="width: 100%;" cellpadding="2" cellspacing="0" border="0">';
$content .= '<tr><td valign="top" style="width: 150px;">';
if (file_exists('upload/userpics/'.$user_id.'.jpg')) {
    $content .= '<img src="/upload/userpics/'.$user_id.'.jpg" width="150" />';
} else {
    $content .= '&nbsp;';
}
$content .= '</td><td valign="top">';
$content .= get_text(5,$language);
$content .= $error;
$content .= '<br /><br /><form method="POST" name="uploadpic" action="" enctype="multipart/form-data"><input name="file" type="file" /><br /><br /><input class="submit" type="submit" name="upload" value="{LANG_UPLOAD}" /></form></td></tr>';
$content .= '</table>';
$template->replace('FULLPAGE_TEXT',$content);
$template->replace('LOGIN','{LANG_LOGOUT}');
$template->replace('REGISTER','{LANG_OPTIONS}');
$template->replace('LINK_LOGIN','{LINK_LOGOUT}');
$template->replace('LINK_REGISTER','{LINK_OPTIONS}');
$template->replace('LOGIN','{LANG_LOGIN}');
$template->replace('REGISTER','{LANG_REGISTER}');
$template->replace('LINK_GERMAN','/de/benutzerbild_hochladen.htm');
$template->replace('LINK_ENGLISH','/en/upload_userpic.htm');
$template->replace('META_TITLE','{LANG_UPLOADUSERPIC}');
$template->replace('FULLPAGE_HEADER','{LANG_UPLOADUSERPIC}');
?>
