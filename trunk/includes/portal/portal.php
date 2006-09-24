<?php
include('functions/jforg_gettext.php');
$template->set_frame('fullpage','lila');
$template->replace('LINK_GERMAN','/de/portal/');
$template->replace('LINK_ENGLISH','/en/portal/');
$template->replace('META_TITLE','Portal');
$template->replace('FULLPAGE_HEADER','Portal');
$content .= '<h2>citeDB</h2>';
$content .= get_text(4,$language);
$template->replace('FULLPAGE_TEXT',$content);
?>