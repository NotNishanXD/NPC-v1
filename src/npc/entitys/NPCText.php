<?php

/*
*  _  _ ___  ___ 
* | \| | _ \/ __|
* | .` |  _/ (__ 
* |_|\_|_|  \___|
* v1 <NPCText>
* 
* @author     D4yv1d
* @type       Entity
*/

namespace npc\entitys;

use npc\NPCMain;
use npc\utils\TFC;
use pocketmine\Player;
use pocketmine\entity\{Entity, Item as ItemE, Human};
use pocketmine\item\Item;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\protocol\AddItemEntityPacket;
use pocketmine\network\protocol\AddPlayerPacket;
use pocketmine\network\protocol\RemoveEntityPacket;
use pocketmine\network\protocol\SetEntityDataPacket;

class NPCText extends Human {

	const TAG_ITEM = "itemHand";
	const ITEM_Y_INCRMENT = 2.8;


	protected $tick;
	private $player;

	/** @var Item $itemHand */
	private $itemHand;

	/** @var int $itemEntityId */
	private $itemEntityId;
	
	protected function initEntity() {
		parent::initEntity();
		$itemTag = $this->namedtag->{self::TAG_ITEM};
		if($itemTag instanceof CompoundTag) {
			$this->itemHand = NBT::getItemHelper($itemTag);
		} else {
			$this->itemHand = Item::get(0);
		}
		$this->itemEntityId = Entity::$entityCount++;
	}

	public function despawnFrom(Player $player){ 
		if(isset($this->hasSpawned[spl_object_hash($player)])) {
			$pk = new RemoveEntityPacket();
			$pk->eid = $this->itemEntityId;
			$player->dataPacket($pk);
		}

		parent::despawnFrom($player);
	}
	public function close(){
		parent::close();
		$pk = new RemoveEntityPacket();
		$pk->eid = $this->getId();
		foreach($this->server->getOnlinePlayers() as $p){
			$p->dataPacket($pk);
		}
		$pk = new RemoveEntityPacket();
		$pk->eid = $this->itemEntityId;
		foreach($this->server->getOnlinePlayers() as $p){
			$p->dataPacket($pk);
		}
	}
	public function spawnTo(Player $player){
		if($player !== $this and !isset($this->hasSpawned[$player->getLoaderId()])){
			$this->hasSpawned[$player->getLoaderId()] = $player;
		}
		$this->player = $player;
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
			Entity::DATA_LEAD_HOLDER => [Entity::DATA_TYPE_LONG, -1],
			Entity::DATA_LEAD => [Entity::DATA_TYPE_BYTE, 0]
		];
		$player->dataPacket($pk);

		$pk = new AddItemEntityPacket();
		$pk->eid = $this->itemEntityId;
		$pk->x = $this->x;
		$pk->y = $this->y + self::ITEM_Y_INCRMENT;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->item = $this->itemHand;
		$player->dataPacket($pk);

		$pk = new SetEntityDataPacket();
		$pk->eid = $this->itemEntityId;
		$pk->metadata = [
			Entity::DATA_NO_AI => [Entity::DATA_TYPE_BYTE, 1]
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
		foreach($this->level->getEntities() as $e){
			if($e->getId() == $this->itemEntityId){
				if($e instanceof ItemE){
					$e->setPickupDelay(9999999999);
					$e->age = 999999;
				}
			}
		}
		$players = $this->server->getLevelByName($this->namedtag["Map"])->getPlayers();
		$this->setDataProperty(2, 4, TFC::center(str_ireplace(["{o}", "{tps}", "{c}", "{date}", "{hh:mm:ss}"], [count($this->server->getOnlinePlayers()), $this->server->getTicksPerSecond(), count($players), date("d/m/Y"), date("H:i:s")], $this->namedtag["Name"])));
		parent::onUpdate($tick);
	}
}
