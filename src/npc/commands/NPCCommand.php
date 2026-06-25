<?php

/*
*  _  _ ___  ___ 
* | \| | _ \/ __|
* | .` |  _/ (__ 
* |_|\_|_|  \___|
* v1 <NPCCommand>
* 
* @author     D4yv1d
* @type       Command
*/

namespace npc\commands;

use npc\NPCMain;
use npc\utils\TFC;
use npc\entitys\NPCText;
use pocketmine\Player;
use pocketmine\command\{CommandSender, Command};
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\level\sound\PopSound;
use pocketmine\math\Vector3;
use pocketmine\nbt\NBT;
use pocketmine\nbt\tag\{StringTag, DoubleTag, IntTag, FloatTag, ListTag, CompoundTag};

class NPCCommand extends Command {
	public function __construct(NPCMain $o){
		$this->owner = $o;
		parent::__construct("npc", "", "", []);
	}
	public function execute(CommandSender $s, $l, array $args){
		if(!$s->hasPermission("npc.command")) {
			return;
		}
		if(!($s instanceof Player)) {
			return;
		}
		if(empty($args)){
			$s->getLevel()->addSound(new BlazeShootSound(new Vector3($s->getX(), $s->getY(), $s->getZ())));
			return $s->sendMessage("§l§aNPC§r§a v1§r§l »§r Use: §a\"/npc <addcmd|add> <add:type|addcmd:command> <add:type> <add:level> <add:text...>\"");
		}
		if(isset($args[0])){
			switch(strtolower($args[0])){
				case "add":
					array_shift($args);
					$type = array_shift($args);

					switch(strtolower($type)){
						case "floating":
							$map = array_shift($args);
							$lvl = $s->getServer()->getLevelByName($map);
							if($lvl == null){
								$s->getLevel()->addSound(new BlazeShootSound(new Vector3($s->getX(), $s->getY(), $s->getZ())));
								return $s->sendPopup("§l§aNPC§r§a v1", TFC::format("§eWorld %s not found.", $map));
							}
							$inventoryItem = $s->getInventory()->getItemInHand();
							$itemHand = $inventoryItem->getId() != 0 ? $inventoryItem : Item::getCreativeItems()[array_rand(Item::getCreativeItems(), 1)];
							$itemTag = NBT::putItemHelper($itemHand);
							$itemTag->setName(NPCText::TAG_ITEM);
							$x = $s->getX();
							$y = $s->getY();
							$z = $s->getZ();
							$pitch = $s->getPitch();
							$yaw = $s->getYaw();
							$text = str_replace("{n}", "\n", trim(implode(" ", $args)));
							$yx = $y + 1.67;
							$nbta = new CompoundTag("", [
								new StringTag("CustomName", TFC::center($text)),
								new StringTag("Name", $text),
								new StringTag("Map", $map),
								new FloatTag("Health", 1),
								"Inventory" => new ListTag("Inventory", []),
								"Pos" => new ListTag("Pos", [
									new DoubleTag("", $x),
									new DoubleTag("", $y),
									new DoubleTag("", $z)
								]),
								"Rotation" => new ListTag("Rotation", [
									new FloatTag("", $yaw),
									new FloatTag("", $pitch)
								]),
								new CompoundTag("Commands", []),
								new CompoundTag("Skin", [
									"Data" => new StringTag("Data", $s->getSkinId()),
									"Name" => new StringTag("Name", $s->getSkinData())
								]),
								NPCText::TAG_ITEM => $itemTag
							]);
							$human = Entity::createEntity("NPCText", $s->getLevel()->getChunk($x >> 4, $z >> 4), $nbta);
							$human->spawnToAll();
							$s->sendPopup("§l§aNPC§r§a v1", "§eEntity spawned successfully!");
							$s->sendMessage(TFC::center("§l§aNPC§r§a v1§r§l »§r The spawned entity contains the following text:\n".$text));
							$s->getLevel()->addSound(new PopSound(new Vector3($s->getX(), $s->getY(), $s->getZ())));
						break;
						case "human":
							$map = array_shift($args);
							$lvl = $s->getServer()->getLevelByName($map);
							if($lvl == null){
								$s->getLevel()->addSound(new BlazeShootSound(new Vector3($s->getX(), $s->getY(), $s->getZ())));
								return $s->sendPopup("§l§aNPC§r§a v1", TFC::format("§eWorld %s not found.", $map));
							}
							$x = $s->getX();
							$y = $s->getY();
							$z = $s->getZ();
							$pitch = $s->getPitch();
							$yaw = $s->getYaw();
							$text = str_replace("{n}", "\n", trim(implode(" ", $args)));
							$yx = $y + 1.67;
							$nbta = new CompoundTag("", [
								new StringTag("CustomName", TFC::center($text)),
								new StringTag("Name", $text),
								new StringTag("Map", $map),
								new FloatTag("Health", 1),
								"Inventory" => new ListTag("Inventory", []),
								"Pos" => new ListTag("Pos", [
									new DoubleTag("", $x),
									new DoubleTag("", $y),
									new DoubleTag("", $z)
								]),
								"Rotation" => new ListTag("Rotation", [
									new FloatTag("", $yaw),
									new FloatTag("", $pitch)
								]),
								new CompoundTag("Commands", []),
								new CompoundTag("Skin", [
									"Data" => new StringTag("Data", $s->getSkinId()),
									"Name" => new StringTag("Name", $s->getSkinData())
								])
							]);
							$human = Entity::createEntity("NPCHuman", $s->getLevel()->getChunk($x >> 4, $z >> 4), $nbta);
							$human->spawnToAll();
							$s->sendPopup("§l§aNPC§r§a v1", "§eEntity spawned successfully!");
							$s->sendMessage("§l§aNPC§r§a v1§r§l »§r The spawned entity contains the following text:\n".$text);
							$s->getLevel()->addSound(new PopSound(new Vector3($s->getX(), $s->getY(), $s->getZ())));
						break;
						case "text":
							$map = array_shift($args);
							$lvl = $s->getServer()->getLevelByName($map);
							if($lvl == null){
								$s->getLevel()->addSound(new BlazeShootSound(new Vector3($s->getX(), $s->getY(), $s->getZ())));
								return $s->sendPopup("§l§aNPC§r§a v1", TFC::format("§eWorld %s not found.", $map));
							}
							$inventoryItem = $s->getInventory()->getItemInHand();
							$itemHand = $inventoryItem->getId() != 0 ? $inventoryItem : Item::getCreativeItems()[array_rand(Item::getCreativeItems(), 1)];
							$itemTag = NBT::putItemHelper($itemHand);
							$itemTag->setName(NPCText::TAG_ITEM);
							$x = $s->getX();
							$y = $s->getY();
							$z = $s->getZ();
							$pitch = $s->getPitch();
							$yaw = $s->getYaw();
							$input = trim(implode(" ", $args));
							$lines = explode("{n}", $input);
							$text = $lines[0];
							$yx = $y;
							$nbta = new CompoundTag("", [
								new StringTag("CustomName", TFC::center($text)),
								new StringTag("Name", $text),
								new StringTag("Map", $map),
								new FloatTag("Health", 1),
								new CompoundTag("Commands", []),
								"Inventory" => new ListTag("Inventory", []),
								"Pos" => new ListTag("Pos", [
									new DoubleTag("", $x),
									new DoubleTag("", $y),
									new DoubleTag("", $z)
								]),
								"Rotation" => new ListTag("Rotation", [
									new FloatTag("", $yaw),
									new FloatTag("", $pitch)
								]),
								new CompoundTag("Commands", []),
								new CompoundTag("Skin", [
									"Data" => new StringTag("Data", $s->getSkinId()),
									"Name" => new StringTag("Name", $s->getSkinData())
								]),
								NPCText::TAG_ITEM => $itemTag
							]);
							$human = Entity::createEntity("NPCText", $s->getLevel()->getChunk($x >> 4, $z >> 4), $nbta);
							$human->spawnToAll();
							array_shift($lines);
							foreach($lines as $line){
								if($line == "")
								 $line = "§a";
								$yx -= 0.241;
								$nbt = new CompoundTag("", [
									new StringTag("CustomName", $line),
									new StringTag("Name", $line),
									new StringTag("Map", $map),
									new FloatTag("Health", 1),
									"Inventory" => new ListTag("Inventory", []),
									"Pos" => new ListTag("Pos", [
										new DoubleTag("", $x),
										new DoubleTag("", $yx),
										new DoubleTag("", $z)
									]),
									"Rotation" => new ListTag("Rotation", [
										new FloatTag("", $yaw),
										new FloatTag("", $pitch)
									]),
									new CompoundTag("Skin", [
										"Data" => new StringTag("Data", $s->getSkinId()),
										"Name" => new StringTag("Name", $s->getSkinData())
									])
								]);
								$txt = Entity::createEntity("NPCLines", $s->getLevel()->getChunk($x >> 4, $z >> 4), $nbt);
								$txt->spawnToAll();
							}
						break;
						default:
							$s->getLevel()->addSound(new BlazeShootSound(new Vector3($s->getX(), $s->getY(), $s->getZ())));
							return $s->sendMessage("§l§aNPC§r§a v1§r§l »§r Use §a\"/npc list-entitys\" for a list of entities.");
						break;
					}
				break;
				case "addcmd":
					array_shift($args);
					if(!isset($args[0])) {
						$s->getLevel()->addSound(new BlazeShootSound(new Vector3($s->getX(), $s->getY(), $s->getZ())));
						return $s->sendMessage("§l§aNPC§r§a v1§r§l »§r Please provide a command.");
					}
					else {
						$this->owner->addcmds[$s->getName()] = trim(implode(" ", $args));
						$s->getLevel()->addSound(new PopSound(new Vector3($s->getX(), $s->getY(), $s->getZ())));
						$s->sendPopup("§l§aNPC §r§av1", "§eClick an NPC to add the command.");
					}
				break;
				case "list-entitys":
					return $s->sendMessage("§l§aNPC§r§a v1§r§l »§r Available NPC types (2): §aHuman, Floating");
				break;
				default:
					$s->getLevel()->addSound(new BlazeShootSound(new Vector3($s->getX(), $s->getY(), $s->getZ())));
					$s->sendMessage("§l§aNPC§r§a v1§r§l »§r Use: §a\"/npc <addcmd|add> <add:type|addcmd:command> <add:type> <add:level> <add:text...>\"");
				break;
			}
		}
	}
}
