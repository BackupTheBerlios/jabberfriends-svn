<?php
/**
 * Enter description here...
 * @author Daniel Gultsch <daniel@gultsch.de>
 */
class JForg_View_Helper_Intro extends Solar_View_Helper {
    public function intro($fulltext,$minchars = null,$imprecision = null) {
        
        //if no minimal character count is give just set it to a default value
        if ($minchars==null) {
            $minchars = 50;
        }
        
        //if the given fulltext string is shorter than minmal character count
        //just return the fulltext string
        if (strlen($fulltext) <= $minchars) {
            return $fulltext;
        }
        
        //if no imprecision is given (maximal char count = minimal char count + imprecision)
        //set imprecision to five percent of the min char count
        //but imprecision has to be at least 8 chars
        if ($imprecision==null) {
            $imprecision = round($minchars * 0.05);
            if ($imprecision < 8) {
                $imprecision = 8;
            }
        }
        //put the interseting part of the fulltext. the part where we want to split it
        //into an char array
        $range = str_split(substr($fulltext,$minchars,$imprecision));
        
        //initialize an array with characters on which we want to split the text
        //if the first char appears in the text we use it if not we try the second and
        //then the third and so on
        //value has to be -1 because later on we will store the position of the char
        //in the range in it.
        $split_chars = array(
            '.' => -1,
            '?' => -1,
            '!' => -1,
            ':' => -1,
            ';' => -1,
            ',' => -1,
            ' ' => -1
        );
        
        //go through the range and store the first position of each char in $split_chars
        for($i = 0; $i < count($range); ++$i) {
            if (array_key_exists($range[$i],$split_chars)) {
                if ($split_chars[$range[$i]] == -1) {
                    $split_chars[$range[$i]] = $i;
                }
            }
        }
        
        //go through the split char array and use the first char with an position mark
        //to split the text
        //if the last char is an ; , or and : remove it because it looks ugly on the end
        //an intro
        foreach($split_chars as $char => $position) {
            if ($position >= 0) {
                return rtrim(substr($fulltext,0,$minchars + $position + 1),';,:');  
            }
        }
        
        //if no matching split char was found, just return the first minchars of the fulltext
        return substr($fulltext,0,$minchars);
    }
}
?>