<?php

/*
*  _  _ ___  ___ 
* | \| | _ \/ __|
* | .` |  _/ (__ 
* |_|\_|_|  \___|
* v1 <SudoCommand>
* 
* @author     D4yv1d
* @type       Command
*/

namespace npc\commands;

use npc\NPCMain;
use pocketmine\Player;
use pocketmine\command\{CommandSender, Command};

class SudoCommand extends Command {
	private $owner;
	public function __construct(NPCMain $owner){
		$this->owner = $owner;
		parent::__construct("sudo", "", "", ["rca"]);
	}
	public function execute(CommandSender $s, $l, array $args){
		if(empty($args)){
			return $s->sendMessage("§l§aNPC§r§a v1§r§l »§r Usage: §a\"/sudo <player:string> <command...:string>\"");
		}
		if(isset($args[0])){
			if($args[0] == ""){
				return $s->sendMessage("§l§aNPC§r§a v1§r§l »§r Please provide a player name.");
			}
			$p = $this->owner->getServer()->getPlayerExact($args[0]);
			if(is_null($p)){
				return $s->sendMessage("§l§aNPC§r§a v1§r§l »§r Player not found.");
			}
			array_shift($args);
			$cmd = trim(implode(" ", $args));
			$this->owner->getServer()->dispatchCommand($p, $cmd);
		}
	}
}
