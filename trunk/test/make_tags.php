<?php
/* 
* This test just echo SQL INSERTS for the tags table.
* I used it to test the tag cloud.
*/

function randomkeys($length){
    $pattern = "abcdefghijklmnopqrstuvwxyz";
    for($i=0;$i<$length;$i++){
        $key .= $pattern{rand(0,35)};
    }
    return $key;
}
for ($i=0; $i<=110; $i++){
/*echo 'TAG: ',*/ $tag = randomkeys(8);
/*echo '<br /> WIE OFT: ', */ $counter = rand(1, 200)/*,"<br>"*/;	  

echo "INSERT INTO `tags` (`id`, `tag`, `counter` ) VALUES (NULL, '", $tag, "','", $counter, "' ); <br />";
}
?>

