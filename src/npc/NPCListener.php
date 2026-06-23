<?php

/*
*  _  _ ___  ___ 
* | \| | _ \/ __|
* | .` |  _/ (__ 
* |_|\_|_|  \___|
* v1 <NPCListener>
* 
* @author     D4yv1d
* @type       EventHandler
*/

namespace npc;

use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\nbt\tag\{StringTag, CompoundTag};
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\level\sound\AnvilUseSound;
use npc\entitys\{
	NPCLines,
	NPCText,
	NPCHuman
};
use npc\utils\TFC;
use pocketmine\event\entity\EntityDamageByEntityEvent;

class NPCListener implements Listener {

	/** @var NPCMain */
	private $owner;

	public function __construct(NPCMain $owner) {
		$this->owner = $owner;
	}

	public function onDamage(EntityDamageEvent $event) {
		$entity = $event->getEntity();
		if ($event->getCause() == 1) {
			/** @var EntityDamageByEntityEvent $event */
			$damager = $event->getDamager();

			if(!($damager instanceof Player)) {
				return;
			}
			if (isset($this->owner->addcmds[$damager->getName()])) {
				if ($entity instanceof NPCHuman or $entity instanceof NPCText) {
					$event->setCancelled();
					$entity->namedtag->Commands[$this->owner->addcmds[$damager->getName()]] = new StringTag($this->owner->addcmds[$damager->getName()], $this->owner->addcmds[$damager->getName()]);
					unset($this->owner->addcmds[$damager->getName()]);
					$damager->sendMessage(TFC::center("§l§aNPC§r§a v1\n§eCommand added."));
					$damager->getLevel()->addSound(new AnvilUseSound(new Vector3($damager->getX(), $damager->getY(), $damager->getZ())));
					return;
				}
			}
			if ($entity instanceof NPCText || $entity instanceof NPCLines || $entity instanceof NPCHuman) {
				if ($damager->isOp() && $damager->getInventory()->getItemInHand()->getId() == 352) {
					$entity->close();
					$entity->kill();
					$damager->sendMessage("§l§aNPC§r§a v1§f§l »§r Entity removed");
				} else {
					$event->setCancelled();
					if (isset($entity->namedtag["Commands"])) {
						$damagerv = $this->owner->getServer();
						foreach ($entity->namedtag["Commands"] as $cmd) {
							$damagerv->dispatchCommand(new ConsoleCommandSender(), str_ireplace("{player}", $damager->getName(), $cmd->getValue()));
						}
					} else {
						$entity->namedtag["Commands"] = new CompoundTag("Commands", []);
					}
				}
			}
		} else {
			if ($entity instanceof NPCText || $entity instanceof NPCLines || $entity instanceof NPCHuman)
			{
				$event->setCancelled();
			}
		}
	}
	
}