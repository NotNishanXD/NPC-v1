<?php

/*
*  _  _ ___  ___ 
* | \| | _ \/ __|
* | .` |  _/ (__ 
* |_|\_|_|  \___|
* v1 <NPCLines>
* 
* @author     D4yv1d
* @type       Entity
*/

namespace npc\entitys;

use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\entity\{Entity, Human};
use pocketmine\Player;
use pocketmine\item\Item;
use npc\NPCMain;

class NPCLines extends Human {
	public function spawnTo(Player $player){
		if($player !== $this and !isset($this->hasSpawned[$player->getLoaderId()])){
			$this->hasSpawned[$player->getLoaderId()] = $player;
		}
		$pk = new AddPlayerPacket();
		$pk->uuid = $this->getUniqueId();
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->pitch = $this->pitch;
		$pk->yaw = $this->yaw;
		$pk->item = Item::get(0, 0, 1);
		$pk->metadata = [
			Entity::DATA_NAMETAG => [Entity::DATA_TYPE_STRING, $this->getDataProperty(2)],
			Entity::DATA_SHOW_NAMETAG => [Entity::DATA_TYPE_BYTE, 1,],
			Entity::DATA_FLAGS => [Entity::DATA_TYPE_BYTE, 1 << Entity::DATA_FLAG_INVISIBLE],
			Entity::DATA_NO_AI => [Entity::DATA_TYPE_BYTE, 1],
		//	Entity::DATA_LEAD_HOLDER => [Entity::DATA_TYPE_LONG, -1],
		//	Entity::DATA_LEAD => [Entity::DATA_TYPE_BYTE, 0]
		];
		$player->dataPacket($pk);
		$this->inventory->sendArmorContents($player);
	}
	public function getName() : string {
		return $this->namedtag["Name"];
	}
	public function getDisplayName() : string {
		return $this->getDataProperty(2);
	}
	public function onUpdate($tick){
		if(!$this->server->isLevelLoaded($this->namedtag["Map"]))
			$this->server->loadLevel($this->namedtag["Map"]);
			if(is_null($this->server->getLevelByName($this->namedtag["Map"])))
				$this->close();
		$players = $this->server->getLevelByName($this->namedtag["Map"])->getPlayers();
		$this->setDataProperty(2, 4, str_ireplace(["{o}", "{tps}", "{c}", "{date}", "{hh:mm:ss}"], [count($this->server->getOnlinePlayers()), $this->server->getTicksPerSecond(), count($players), date("d/m/Y"), date("H:i:s")], $this->namedtag["Name"]));
		parent::onUpdate($tick);
	}
}