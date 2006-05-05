<?php
/*
* This php class jforg_tags is a part of the jabberfriends.org project and is 
* responsible for working with the tags. It can be useful for all sorts of 
* social software.
*
* Copyright (C) 2006  	Bahtiar Gadimov <blase16@blase16.de>
*                       Daniel Gultsch  <daniel@gultsch.de>
* 
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
*/

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
    
	//This function add a tag to an user.
    function add_tag($tag,$user_id) {
		$tag					=	strtolower($tag); //Turn all the letters to small letters.
 		$tag_id					=	"SELECT `id` FROM `tags`WHERE `tag` = '$tag';";
		$query					=	@mysql_query($tag_id,$this->connection);
		$result					= 	mysql_num_rows($query);
		
		if($result > 0){
			//Test if the user has this tag already set
           	$tag_id2 = $this->get_tag_id($tag);
			$user_has_tag	=	"SELECT '$user_id' FROM `user_tags`WHERE `tag_id` = '$tag_id2';";
			$query2			=	mysql_query($user_has_tag,$this->connection);
			$exists 		= 	mysql_num_rows($query2);
			//If the user hasn't this tag already set, so set it now.
			if($exists == 0){
					$add_tag_to_user 	= 	"INSERT INTO `user_tags` ( `user_id` , `tag_id` ) VALUES ('$user_id', '$tag_id2');";
					$query				=	@mysql_query($add_tag_to_user,$this->connection);
					if (!$query) {
						die("jforg_tags.add_tags: Der SQL Insert ist fehlgeschlagen - $add_tag_to_user");
					}
			}
		}else{
			$add_tag			=	"INSERT INTO `tags` ( `id` , `tag` ) VALUES ('', '$tag');";
			$query				=	@mysql_query($add_tag,$this->connection);
			if (!$query) {
         	die("jforg_tags.add_tags: Die SQL Insert ist fehlgeschlagen - $add_tag");
			}		

			$tagid				=	mysql_insert_id();
			$add_tag_to_user 	= 	"INSERT INTO `user_tags` ( `user_id` , `tag_id` ) VALUES ('$user_id', '$tagid');";
			$query				=	@mysql_query($add_tag_to_user,$this->connection);
    		
			if (!$query) {
            	die("jforg_tags.add_tags: Die SQL Insert ist fehlgeschlagen - $add_tag_to_user");
			}

		}	
	}

	//This function remove a tag from an user.
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
			$remove_tag			= 	"DELETE FROM `user_tags` WHERE `user_id` = '$user_id' AND `tag_id` = '$tag_id';";
			$query				=	@mysql_query($remove_tag,$this->connection);
    		
			if (!$query) {
            	die("jforg_tags.remove_tag: Das SQL DELETE ist fehlgeschlagen - $remove_tag");
			}
		}
	}
    
	//This function return an array, where are listed all users, which have the selected tag
    function list_user($tag){
		
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
				
				if (!$tag_id_result	) {
						die("jforg_tags.get_tag_id: Das SQL SELCT ist fehlgeschlagen - $tag_id_result");
				}
				
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

}
?>
