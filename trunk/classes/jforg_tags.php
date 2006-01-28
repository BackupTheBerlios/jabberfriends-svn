<?php
class jforg_tags{
	var $connection;
	var $tag_id;

 function jforg_tags() {
        include('includes/config.php');
        $this->connection = @mysql_connect($config['mysql_server'],$config['mysql_user'],$config['mysql_password']);
        if (!$this->connection) {
            die("jforg_template: Die Verbindung zur Datenbank ist fehlgeschlagen");
        }
        $select = @mysql_select_db($config['mysql_table'],$this->connection);
        if (!$select) {
            die("jforg_template: Die Auswahl der Tabelle ist fehlgeschlagen");
        }
    }


    function get_user_tags($user_id) {
		$user_tags 	= 	"SELECT * FROM `user_tags` WHERE `user_id` = '$user_id';";
		$query		=	@mysql_query($user_tags->connection);
		
		if ($query) {
            die("jforg_tags.get_user_tags: Die SQL Abfrage ist fehlgeschlagen - $user_tags");
		}
		return mysql_fetch_array($query,MYSQL_NUM);		
	}
  
    function add_tag($tag,$user_id) {
 		$tag_exists				=	"SELECT * FROM `tags`WHERE `tag` = '$tag';";
		$query					=	@mysql_query($tag_exists->connection);

		if($query){
			$tag_id				=	"SELECT `id` FROM `tags` WHERE `tag` = '$tag';";
			$query				=	@mysql_query($tag_id->connection);
			if (!$query) {
            	die("jforg_tags.add_tags: Die SQL Abfrage ist fehlgeschlagen - $tag_id");
			}

			$add_tag_to_user 	= 	"INSERT INTO `user_tags` ( `user_id` , `tag_id` ) VALUES ('$user_id', '$tag_id');";
			$query				=	@mysql_query($add_tag_to_user->connection);
			if (!$query) {
            	die("jforg_tags.add_tags: Die SQL Insert ist fehlgeschlagen - $add_tag_to_user");
			}
		}

		if(!$query){
			$add_tag			=	"INSERT INTO `tags` ( `id` , `tag` ) VALUES ('', '$tag');";
			$query				=	@mysql_query($add_tag->connection);
			if (!$query) {
            	die("jforg_tags.add_tags: Die SQL Insert ist fehlgeschlagen - $add_tag");
			}		
	
			$tag_id				=	"SELECT `id` FROM `tags` WHERE `tag` = '$tag';";
			$query				=	@mysql_query($tag_id->connection);
			if (!$query) {
            	die("jforg_tags.add_tags: Die SQL Abfrage ist fehlgeschlagen - $tag_id");
			}

			$add_tag_to_user 	= 	"INSERT INTO `user_tags` ( `user_id` , `tag_id` ) VALUES ('$user_id', '$tag_id');";
			$query				=	@mysql_query($add_tag_to_user->connection);
			if (!$query) {
            	die("jforg_tags.add_tags: Die SQL Insert ist fehlgeschlagen - $add_tag_to_user");
			}

		}	
	}

    function remove_tag($tag,$user_id){

    }
    function list_user($tag){
    }
}
?>
