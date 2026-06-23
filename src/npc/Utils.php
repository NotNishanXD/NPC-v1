<?php

/*
*  _  _ ___  ___ 
* | \| | _ \/ __|
* | .` |  _/ (__ 
* |_|\_|_|  \___|
* v1 <Utils>
* 
* @author     D4yv1d
* @type       Utils
*/

namespace npc;

class Utils {
	public static function format(String $format, ...$args){
		return str_ireplace(["%s", "%a", "%b", "%c", "%d", "%e", "%f", "%g", "%h", "%i", "%j", "%k", "%l", "%m", "%n", "%o", "%p", "%q", "%r", "%s", "%t", "%u", "%v", "%w", "%x", "%y", "%z"], $args, $format);
	}
}