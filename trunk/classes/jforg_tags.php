<?php

class jforg_tags{
	var $connection;

	//This is the constructor of this class, it connects to the DB. 
	function jforg_tags() {
        include('includes/config.php');
        
		$this->connection = @mysql_connect($config['mysql_server'],$config['mysql_user'],$config['mysql_password']);
        
		if (!$this->connection) {
            die("jforg_tags: Die Verbindung zur Datenbank ist fehlgeschlagen");
        }
        
		$select = @mysql_select_db($config['mysql_table'],$this->connection);
        
		if (!$select) {
            die("jforg_tags: Die Auswahl der Tabelle ist fehlgeschlagen");
        }
    }

	//This function returns an array with the tag_id's of an user
    function get_user_tags($user_id) {
		$user_tags 	= 	"SELECT * FROM `user_tags` WHERE `user_id` = '$user_id';";
		$query		=	@mysql_query($user_tags,$this->connection);
		if (!$query) {
            die("jforg_tags.get_user_tags: Die SQL Abfrage ist fehlgeschlagen - $user_tags");
		}
		while ($row = mysql_fetch_assoc($query)) {
                $tagids[] = $this->get_tag_value((int) $row['tag_id']); 
        }
		return $tagids;		
	}
    
	//This function add a tag to the user.
    function add_tag($tag_value, $user_id) {
        $tag_exist      =   $this->tag_exist($tag_value);

        if($tag_exist == FALSE){
  		    $add_tag_to_tags 	= 	"INSERT INTO `tags` (`id`, `tag` ) VALUES (NULL, '$tag_value');";
		    $query				=	mysql_query($add_tag_to_tags,$this->connection);
            
            // In case of emergency just print and die
            if (!$query) {
			    die("jforg_tags.add_tags: Der SQL Insert ist fehlgeschlagen - $add_tag_to_tags");
		    }
        }

        $tag_id         =   $this->get_tag_id($tag_value);       
        $user_have_tag  =   $this->has_user_tag($tag_id, $user_id);

        if($user_have_tag == FALSE){
            $add_tag_to_user 	= 	"INSERT INTO `user_tags` ( `user_id` , `tag_id` ) VALUES ('$user_id', '$tag_id');";
		    $query				=	@mysql_query($add_tag_to_user,$this->connection);
		
            // In case of emergency just print and die
            if (!$query) {
			    die("jforg_tags.add_tag: Der SQL Insert ist fehlgeschlagen - $add_tag_to_user");
		    }
        }
	}  

	//This function remove a tag from an user.
    function remove_tag($tag,$user_id){
		if(is_int($tag)){
			$remove_tag	= 	"DELETE FROM `user_tags` WHERE `user_id` = '$user_id' AND `tag_id` = '$tag';";
			$query		=	@mysql_query($remove_tag,$this->connection);
    		
			if (!$query) {
            	die("jforg_tags.remove_tag: Das SQL DELETE ist fehlgeschlagen - $remove_tag");
			}
		}
		if(is_string($tag)){
			$get_tag_id			=	"SELECT `id` FROM `tags`WHERE `tag` = '$tag';";
			$query				=	@mysql_query($get_tag_id,$this->connection);
			$result				=	@mysql_fetch_array($query);
			$tag_id				=	$result[0];
			$remove_tag			= 	"DELETE FROM `user_tags` WHERE `user_id` = '$user_id' AND `tag_id` = '$tag_id';";
			$query				=	@mysql_query($remove_tag,$this->connection);
    		
			if (!$query) {
            	die("jforg_tags.remove_tag: Das SQL DELETE ist fehlgeschlagen - $remove_tag");
			}
		}
	}
    
	//This function return an array, where are listed all users, which have the selected tag
    function list_users($tag){
		
		if(is_int($tag)){
			$find_users	= 	"SELECT `user_id` FROM `user_tags` WHERE `tag_id` = '$tag';";
			$query		=	@mysql_query($find_users,$this->connection);  		
			if (!$query) {
            	die("jforg_tags.list_user: Das SQL SELECT ist fehlgeschlagen - $query");
			}
			while ($row = mysql_fetch_assoc($query)) {
                $userids[] = $row['user_id']; 
			}
		}	elseif(is_string($tag)){
            $tag_id = $this->get_tag_id($tag);
			
			$find_users	= 	"SELECT `user_id` FROM `user_tags` WHERE `tag_id` = '$tag_id';";
			$query		=	@mysql_query($find_users,$this->connection);
    		
			if (!$query) {
            	die("jforg_tags.list_user: Das SQL SELCT ist fehlgeschlagen - $query");
			}
			while ($row = mysql_fetch_assoc($query)) {
                $userids[] = $row['user_id']; 
            }
		} else {
			die("$tag is not an int or a string");
        }
		return $userids;
	}
	
	// This is a simply function which return the tag_id from a tag, which is a string
	function get_tag_id($tag_string){                         
			if(is_string($tag_string)){
				$get_tag_id		=	@mysql_query("SELECT `id` FROM `tags` WHERE `tag` = '$tag_string';",$this->connection);
				$tag_id_result	=	@mysql_fetch_array($get_tag_id);
				$tag_id			=	(int) $tag_id_result[0];
				
				//if (!$tag_id_result	) {
			    //		die("jforg_tags.get_tag_id: Das SQL SELCT ist fehlgeschlagen - $tag_id_result");
				//}
				
				return $tag_id;
			}else{
				$vartype = gettype($tag_string);
				die("jforg_tags.get_tag_id: \$tag_string muss ein String sein, es wurde aber ein  $vartype uebrgeben.");
			}
	} 
	
	// This is a function which return the tag_value from a tag_id, which is a integer
	function get_tag_value($tag_id){
			if(is_int($tag_id)){
				$get_tag_value		=	@mysql_query("SELECT `tag` FROM `tags` WHERE `id` = '$tag_id';",$this->connection);
				$tag_value_result	=	@mysql_fetch_array($get_tag_value);
				$tag_value			=	(string) $tag_value_result[0];
				if (!$tag_value_result) {
						die("jforg_tags.get_tag_value: Das SQL SELCT ist fehlgeschlagen - $tag_value_result");
				}
			return $tag_value;

			}else{
				$vartype = gettype($tag_id);
				die("jforg_tags.get_tag_value: \$tag_id muss ein int sein, es wurde aber ein  $vartype uebrgeben.");
			}
			
	}
	
    // This function test if the tag exists or not
	function tag_exist($tag_value){
	    $get_tag_value		=	@mysql_query("SELECT `id` FROM `tags` WHERE `tag` = '$tag_value';",$this->connection);
		$tag_value_result	=	@mysql_fetch_array($get_tag_value);
		$tag_value			=	(string) $tag_value_result[0];
	    
	    if (!$tag_value_result){
	        $exist = FALSE;
	    }else{
	        $exist = TRUE;
	    }
	    
	    return $exist;   
	}
    
    // This function test if the user has set the specified tag	
	function has_user_tag($tag_id, $user_id){
		$user_has_tag	=	"SELECT `user_id`='$user_id' FROM `user_tags` WHERE `tag_id` = '$tag_id' ;";
		$query			=	mysql_query($user_has_tag,$this->connection);
		$exists 		= 	mysql_num_rows($query);
        
		//If the user hasn't this tag already set, so set it now.
		if((string) $exists[0] == "" ){
			return $he_have_it = FALSE;
		}else{
		    return $he_have_it = TRUE;
		}
	}

}
?>
