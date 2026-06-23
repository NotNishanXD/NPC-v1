<?php

/*
*  _  _ ___  ___ 
* | \| | _ \/ __|
* | .` |  _/ (__ 
* |_|\_|_|  \___|
* v1 <NPCMain>
* 
* @author     D4yv1d
* @type       Main
*/

namespace npc;

use npc\commands\{
 SudoCommand,
 NPCCommand
};
use npc\entitys\{
 NPCLines,
 NPCText,
 NPCHuman
};
use pocketmine\plugin\PluginBase;
use pocketmine\entity\Entity;
use pocketmine\utils\Config;

class NPCMain extends PluginBase {
	public static $instance;
	public $addcmds = [];

	public function onEnable(){
	
		if(!

$this->getServer()->getPluginManager()->getPlugin("EconomyAPI")){
			$this->getServer()->getPluginManager()->disablePlugin($this);
			return;
		}
		$this->economy = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		self::$instance = $this;
		date_default_timezone_set('America/Sao_Paulo');
		$this->getServer()->getCommandMap()->registerAll("", [
			new NPCCommand($this),
			new SudoCommand($this)
		]);
		$this->getServer()->getPluginManager()->registerEvents(new NPCListener($this), $this);
		Entity::registerEntity(NPCText::class, true);
		Entity::registerEntity(NPCHuman::class, true);
		Entity::registerEntity(NPCLines::class, true);
	}
	
	public function getTop($position = 1){

       $money = $this->economy->getAllMoney()['money'];

       arsort($money);
        $pos = 0;
        foreach ($money as $player => $m) {
            $pos++;
            if($pos == $position) return "§bTop {$pos}°§f\n§e{$player}§f\n§7{$this->parseMoney($m)}";
       }
       return "§bTop {$position}°§f\n§e--/--§f\n§cNo player.";
    }

    public function parseMoney(int $money){
        $count = [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18];
        $param = [[1, 'K'], [2, 'K'], [3, 'K'], [1, 'M'], [2, 'M'], [3, 'M'], [1, 'B'], [2, 'B'], [3, 'B'], [1, 'T'], [2, 'T'], [3, 'T'], [1, 'Q'], [2, 'Q'], [3, 'Q']];
        $final = array_combine($count, $param);
        if(empty($final[strlen($money)])) return $money;
        return substr($money, 0, $final[strlen($money)][0]) . "." . substr($money, $final[strlen($money)][0], $final[strlen($money)][0]++) . $final[strlen($money)][1];
    }
}
