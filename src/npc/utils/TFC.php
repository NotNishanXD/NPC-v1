<?php

namespace npc\utils;

use pocketmine\utils\TextFormat;

class TFC {
	public static function format(String $format, ...$args){
		return str_ireplace(["%s", "%a", "%b", "%c", "%d", "%e", "%f", "%g", "%h", "%i", "%j", "%k", "%l", "%m", "%n", "%o", "%p", "%q", "%r", "%s", "%t", "%u", "%v", "%w", "%x", "%y", "%z"], $args, $format);
	}
	public static function center(String $str) : String
	{
		$colors = ["§1", "§2", "§3", "§4", "§5", "§6", "§7", "§8", "§9", "§0", "§a", "§b", "§c", "§d", "§e", "§f", "§l", "§o", "§u", "§n", "§r"];
		$array = explode("\n", str_ireplace($colors, "", self::ccenter($str)));
		$max = max(array_map("strlen", $array));
		$textarray = explode("\n", self::ccenter($str));
		$i = 0;
		$lens = [];
		$newStr = [];
		$s = 0;
		foreach($textarray as $text)
		{
			if(strlen($text) !== strlen($array[$i]))
			{
				$lens[] = strlen($text) - strlen($array[$i]);
				$s = strlen($text) - strlen($array[$i]);
				$newStr[] = str_pad($text, ($max + $s), " ", STR_PAD_BOTH);
			} else {
				$newStr[] = str_pad($text, $max, " ", STR_PAD_BOTH);
			}
			$i++;
		}
		return implode("\n", $newStr);
	}
	public static function ccenter(String $str) : String
	{
		$colors = ["§1", "§2", "§3", "§4", "§5", "§6", "§7", "§8", "§9", "§0", "§a", "§b", "§c", "§d", "§e", "§f", "§l", "§o", "§u", "§n", "§r"];
		$textarray = explode("\n", $str);
		$max = max(array_map("strlen", $textarray));
		$newStr = [];
		foreach($textarray as $text)
		{
			if(strlen($text) == $max) 
				$newStr[] = $text;
			else
				$newStr[] = str_repeat("  ", 1) . $text;
		}
		return implode("\n", $newStr);
	}
}
