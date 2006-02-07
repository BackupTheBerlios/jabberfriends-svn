<?php
class jforg_tags{
	var $connection;

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
		$query		=	@mysql_query($user_tags,$this->connection);
		if (!$query) {
            die("jforg_tags.get_user_tags: Die SQL Abfrage ist fehlgeschlagen - $user_tags");
		}
		return mysql_fetch_array($query,MYSQL_NUM);		
	}
  
    function add_tag($tag,$user_id) {
 		$tag_id					=	"SELECT `id` FROM `tags`WHERE `tag` = '$tag';";
		$query					=	@mysql_query($tag_exists,$this->connection);
		$result 				= 	($query);

		if($result > 0){
			//Test if the user has this tag already set
           	$user_has_tag	=	"SELECT '$user_id' FROM `tags`WHERE `tag` = '$tag';";
			$query					=	@mysql_query($user_has_tag,$this->connection);
			$exists = ($query);
			//If the user hasn't this tag already set, so set it now.
			if($exists > 0){
					$add_tag_to_user 	= 	"INSERT INTO `user_tags` ( `user_id` , `tag_id` ) VALUES ('$user_id', '$tag_id');";
					$query				=	@mysql_query($add_tag_to_user,$this->connection);
					if (!$query) {
						die("jforg_tags.add_tags: Der SQL Insert ist fehlgeschlagen - $add_tag_to_user");
					}
			}
		}

		if($result == 0){
			$add_tag			=	"INSERT INTO `tags` ( `id` , `tag` ) VALUES ('', '$tag');";
			$query				=	@mysql_query($add_tag,$this->connection);
			if (!$query) {
         	die("jforg_tags.add_tags: Die SQL Insert ist fehlgeschlagen - $add_tag");
			}		

			$tag_id				=	mysql_insert_id();
			$add_tag_to_user 	= 	"INSERT INTO `user_tags` ( `user_id` , `tag_id` ) VALUES ('$user_id', '$tag_id');";
			$query				=	@mysql_query($add_tag_to_user,$this->connection);
    		
			if (!$query) {
            	die("jforg_tags.add_tags: Die SQL Insert ist fehlgeschlagen - $add_tag_to_user");
			}

		}	
	}

    function remove_tag($tag,$user_id){
		if(gettype($tag) == "integer"){
			$remove_tag	= 	"DELETE FROM `user_tags` WHERE `user_id` = '$user_id' AND `tag_id` = '$tag';";
			$query		=	@mysql_query($remove_tag,$this->connection);
    		
			if (!$query) {
            	die("jforg_tags.remove_tag: Das SQL DELETE ist fehlgeschlagen - $remove_tag");
			}
		}
		if(gettype($tag) == "string"){
			$get_tag_id			=	"SELECT `id` FROM `tags`WHERE `tag` = '$tag';";
			$query				=	@mysql_query($get_tag_id,$this->connection);
			$result				=	@mysql_fetch_array($query);
			$tag_id				=	$result[0];
			echo $tag_id;
			$remove_tag	= 	"DELETE FROM `user_tags` WHERE `user_id` = '$user_id' AND `tag_id` = '$tag_id';";
			$query		=	@mysql_query($remove_tag,$this->connection);
    		
			if (!$query) {
            	die("jforg_tags.remove_tag: Das SQL DELETE ist fehlgeschlagen - $remove_tag");
			}
		}
	}
	
    function list_user($tag){
		
		if(is_int($tag)){
			$find_users	= 	"SELECT `user_id` FROM `user_tags` WHERE `tag_id` = '$tag';";
			$query		=	@mysql_query($find_users,$this->connection);  		
			if (!$query) {
            	die("jforg_tags.list_user: Das SQL SELECT ist fehlgeschlagen - $query");
			}
			while ($row = mysql_fetch_assoc($query)) {
			//	print_r ($row);
                $userids[] = $row['user_id']; 
		}
		elseif(is_string($tag)){
            $get_tag_id		=	@mysql_query("SELECT `id` FROM `tags`WHERE `tag` = '$tag';",$this->connection);
			$tag_id_result	=	@mysql_fetch_array($get_tag_id);
			$tag_id			=	(int) $tag_id_result[0];
			
			$find_users	= 	"SELECT `user_id` FROM `user_tags` WHERE `tag_id` = '$tag_id';";
			$query		=	@mysql_query($find_users,$this->connection);
    		
			if (!$query) {
            	die("jforg_tags.list_user: Das SQL SELCT ist fehlgeschlagen - $query");
			}
			while ($row = mysql_fetch_assoc($query)) {
			//	print_r ($row);
                $userids[] = $row['user_id']; 
            }
		} else {
			die("$tag is not an int or a string");
        }
		return $userids;
	}
}
?>
