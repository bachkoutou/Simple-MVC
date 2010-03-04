<?php
class Toolbox
{
	public static function getArrayParameter($array, $index, $default)
	{
		return (is_array($array) && isset($index) && isset($array[$index])) ? $array[$index] : $default;
	}
	/**
	 * transforms a simplexml to an array
	 *
	 * @param string $xml
	 * @param array $arr
	 * @return array
	 */
	public static function xml2phpArray($xml,$arr)
	{
		$iter = 0;
		foreach($xml->children() as $b){
			$a = $b->getName();
			if(!$b->children()){
				$arr[$a] = trim($b[0]);
			}
			else{
				$arr[$a][$iter] = array();
				$arr[$a][$iter] = xml2phpArray($b,$arr[$a][$iter]);
			}
			$iter++;
		}
		return $arr;
	}	
}