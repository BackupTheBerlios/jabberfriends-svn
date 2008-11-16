<?php
/**
 * A View Helper which returns only the first X chars of a given string
 * It tries to cut the string after a sentence and if this not possible
 * after a word. (If this is not possible too it just return the first
 * X chars)
 * 
 * The minimal character count can be configured as well as the range
 * where it tries to find the end of a sentence or a word.
 * 
 * By default this range (imprecision) is 5% of the minimal character
 * count but at least 8 chars.
 * 
 * @example
 *      //gives the first 200 chars and tries to find a break in 200 + 5% range
 *      $this->intro($longtext,200);
 * 
 *      //gives the first 100 chars and tries to find a
 *      //break between char# 100 and 120
 *      $this->intro($longtext,100,20);
 * @author Daniel Gultsch <daniel@gultsch.de>
 * @version 0.1
 * @license http://opensource.org/licenses/bsd-license.php BSD
 */
class JForg_View_Helper_Intro extends Solar_View_Helper {
    
    protected $_JForg_View_Helper_Intro = array(
        'minchars' => 100,
        'imprecision' => 0.05, // 5%
        'min_imprecision' => 8,
        'split_chars' => array( //all chars to use to split the text
            '.' => -1,
            '?' => -1,
            '!' => -1,
            ':' => -1,
            ';' => -1,
            ',' => -1,
            ' ' => -1
        ),
        'remove' => ';:,' //if one of this is on the last position of the text it will be removed
    );
    
    public function intro($fulltext,$minchars = null,$imprecision = null) {
        
        //if no minimal character count is give just set it to a default value
        if ($minchars==null) {
            $minchars = (int) $this->_config['minchars'];
        }
        
        //if the given fulltext string is shorter than minmal character count
        //just return the fulltext string
        if (strlen($fulltext) <= $minchars) {
            return $fulltext;
        }
        
        if ($imprecision==null) {
            $imprecision = round($minchars * $this->_config['imprecision']);
            if ($imprecision < $this->_config['min_imprecision']) {
                $imprecision = $this->_config['min_imprecision'];
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
        $split_chars = $this->_config['split_chars'];
        
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
                $intro = substr($fulltext,0,$minchars + $position + 1);
                return rtrim($intro,$this->_config['remove']);  
            }
        }
        
        //if no matching split char was found, just return the first minchars of the fulltext
        return substr($fulltext,0,$minchars);
    }
}
?>