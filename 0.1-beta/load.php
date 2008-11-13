<?php
include('config.php');
include('classes/jforg_template.php');
include('classes/jforg_user.php');

$template = new jforg_template();
$user = new jforg_user();

$template->set_path('design');

//Pruefe ob ein gueltiger Sprachcode uebergeben wurde
if (in_array($_GET['language'],$config['languages'])) {
    $language = $_GET['language'];
} else {
    die('unknown language');
}


//Pruefe ob eine gueltige class uebergeben wurde
if (!in_array($_GET['class'],$config['class'])) {
    die('unknown class');
}
//Pruefe ob eine gueltige class uebergeben wurde
if (!in_array($_GET['method'],$config['method'])) {
    die('unknown method');
}

switch($_GET['class']) {
    case "startpage": $template->hover_on('tuerkis'); break;
    case "members": $template->hover_on('green'); break;
    case "portal": $template->hover_on('lila'); break;
    case "wiki": $template->hover_on('red'); break;
}
SESSION_START();
if ($user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    $template->replace('LOGIN','{LANG_LOGOUT}');
    $template->replace('REGISTER','{LANG_OPTIONS}');
    $template->replace('LINK_LOGIN','{LINK_LOGOUT}');
    $template->replace('LINK_REGISTER','{LINK_OPTIONS}');
} else {
    $template->replace('LOGIN','{LANG_LOGIN}');
    $template->replace('REGISTER','{LANG_REGISTER}');
}

//Lade Seite
if (!file_exists('includes/'.$_GET['class'].'/'.$_GET['method'].'.php')) {
    die('The method doesnt exists');
}
include('includes/'.$_GET['class'].'/'.$_GET['method'].'.php');

$template->translate($language);
include('includes/links.php');
$template->write();
?>