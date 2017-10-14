<?php

/*
 * Author: YiQiang <yiqiang.dai@gmail.com>
 * 2013 - ? Rights Reserved.
 */

/**
 * Description of Common
 *
 * @author Yiqiang <yiqiang.dai@gmail.com>
 */

class Common {
    //put your code here
	public function clean_dom($str){
		$str=strip_tags($str);
		$str=str_replace('&nbsp;',' ',$str);
		$str=str_replace('&#46;','.',$str);
		return trim($str);
	}
	
    public function get_int($str){
        preg_match_all('!\d+!', $str, $matches);
        $int=implode('', $matches[0]);
        return $int;
    }
        
	public function array_insert(&$array, $position, $insert){
		if (is_int($position)) {
			array_splice($array, $position, 0, $insert);
		} else {
			$pos   = array_search($position, array_keys($array));
			$array = array_merge(
				array_slice($array, 0, $pos),
				$insert,
				array_slice($array, $pos)
			);
		}
	}
	
	public function space_to_under($string){
	    $string=trim($string);
	    return str_replace(' ', '_', $string);
	}
    
	public function slash_to_under($string){
	    $string=trim($string);
	    return str_replace('/', '_', $string);
	}
}

?>
