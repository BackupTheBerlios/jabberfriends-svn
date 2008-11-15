<?php
$config = array();
$config['Solar_Uri_Action']['path'] = '/';
$config['Solar_Controller_Front']['classes'] = array(
    'JForg_App'
);
$config['Solar_Controller_Front']['default'] = 'startpage';
$config['Solar']['registry_set']['sql'] = array(
    'Solar_Sql',
    array(
        'adapter'   => 'Solar_Sql_Adapter_Mysql',
        'host'      => 'localhost',
        'user'      => 'root',
        'pass'      => '',
        'name'      => 'jforg'
    )
);
return $config;
?>