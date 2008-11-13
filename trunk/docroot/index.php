<?php
set_include_path('../include');
require_once 'Solar.php';
$config_file = '../config/JForg.conf.php';
Solar::start($config_file);
$front = Solar::factory('Solar_Controller_Front');
$front->display();
Solar::stop();
?>