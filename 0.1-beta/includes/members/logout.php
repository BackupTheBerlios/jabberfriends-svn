<?php
include('functions/jforg_gettext.php');
SESSION_START();
if ($user->login($_SESSION['nick'],$_SESSION['passwd'])) {
    SESSION_DESTROY();
    HEADER("Location: /$language/");
} else {
    die('You are not logged in');
}
?>