<?php

//This plugin was made by jojoe77777. © jojoe77777 2015 or whatever

namespace slapper;

use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\Double;
use pocketmine\nbt\tag\Enum;
use pocketmine\nbt\tag\Float;
use pocketmine\nbt\tag\Short;
use pocketmine\nbt\tag\String;
use pocketmine\nbt\tag\Byte;
use pocketmine\Player;
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByBlockEvent;



class main extends PluginBase implements Listener{
    public function onLoad(){
          $this->getLogger()->info("Slapper is loaded!");
    }
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->getLogger()->info("Slapper is enabled! Time to slap!");

   }


    public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		switch($command->getName()){
			case 'nothing':
                return true;
                break;

			case 'rca':
                if (count($args) < 2){
			$sender->sendMessage("Please enter a player and a command.");
			return false; }

		        $player = $this->getServer()->getPlayer($tuv = array_shift($args));
                if(!($player == null)){
                $commandForSudo = trim(implode(" ", $args));
                $this->getServer()->dispatchCommand($player, $commandForSudo);
                return true;
                break;
                }
                $sender->sendMessage(TextFormat::RED."Player not found.");
                return true;
                break;
			case "slapper":
                if($sender instanceof Player){
				$name = trim(implode(" ", $args));
                $defaultName = $sender->getDisplayName();
                if($name == null) $name = $defaultName;
                $senderSkin = $sender->getSkinData();
                $IsSlim = $sender->isSkinSlim();
                $playerX = $sender->getX();
                $playerY = $sender->getY();
                $playerZ = $sender->getZ();

                $outX=round($playerX,1);
                $outY=round($playerY,1);
                $outZ=round($playerZ,1);

                $playerLevel = $sender->getLevel()->getName();
                $playerYaw = $sender->getYaw();
                $playerPitch = $sender->getPitch();
                $humanInv = $sender->getInventory();
                $pHealth = $sender->getHealth();


  $nbt = new Compound;

        $motion = new Vector3(0,0,0);

        $nbt->Pos = new Enum("Pos", [

           new Double("", $playerX),
           new Double("", $playerY),
           new Double("", $playerZ)

        ]);

        $nbt->Motion = new Enum("Motion", [

           new Double("", $motion->x),
           new Double("", $motion->y),
           new Double("", $motion->z)

        ]);

        $nbt->Rotation = new Enum("Rotation", [

            new Float("", $playerYaw),
            new Float("", $playerPitch)

        ]);

        $nbt->Health = new Short("Health", $pHealth);

        $nbt->Inventory = new Enum("Inventory", $humanInv);

        $nbt->NameTag = new String("name",$name);

        $nbt->Invulnerable = new Byte("Invulnerable", 1);

        $nbt->CustomTestTag = new Byte("CustomTestTag", 1);

        $nbt->Skin = new Compound("Skin", [
          "Data" => new String("Data", $senderSkin),
          "Slim" => new Byte("Slim", $IsSlim)
        ]);

        $clonedHuman = Entity::createEntity("Human", $sender->getLevel()->getChunk($playerX>>4, $playerZ>>4),$nbt);
        $Inv = $clonedHuman->getInventory();

        $sender->sendMessage(TextFormat::GREEN."[". TextFormat::YELLOW . "Slapper" . TextFormat::GREEN . "] "."Human entity spawned with name ".TextFormat::WHITE."\"".TextFormat::BLUE.$name.TextFormat::WHITE."\"");

        $pHelm = $humanInv->getHelmet();
        $pChes = $humanInv->getChestplate();
        $pLegg = $humanInv->getLeggings();
        $pBoot = $humanInv->getBoots();

        $Inv->setHelmet($pHelm);
        $Inv->setChestplate($pChes);
        $Inv->setLeggings($pLegg);
        $Inv->setBoots($pBoot);
        $clonedHuman->getInventory()->setHeldItemSlot($sender->getInventory()->getHeldItemSlot());
        $clonedHuman->getInventory()->setItemInHand($sender->getInventory()->getItemInHand());
        $clonedHuman->spawnToAll();


 return true;
}else{
        $sender->sendMessage("This command only works in game.");

return true;
}
                	}


	}

	public function onEntityInteract(EntityDamageEvent $ev) {
		if ($ev->isCancelled()) return;
		$taker = $ev->getEntity();
		if(!($ev instanceof EntityDamageByBlockEvent)){
		$didItWork = "No";
		$isInvalid = "No";
		$doNotCheck = "No";
		$takerSaveId = "Default";
		$giverSaveId = "Default";
		$wasAnArrow = "No";
		$noCommand = "No";
		$takerName = "FallbackCommand";
		if(($taker instanceof Player)){ return; }
		if(!($ev instanceof EntityDamageByEntityEvent)){ $doNotCheck = "Yes"; }
		if(($ev instanceof EntityDamageByEntityEvent)){ $giver = $ev->getDamager(); if(!($giver instanceof Player)){ $giverSaveId = $giver->getSaveId(); }
		$takerSaveId = $taker->getSaveId(); };
		if(($ev instanceof EntityDamageByChildEntityEvent)){$noCommand = "Yes"; $wasAnArrow = "Yes"; }
		if(!($takerSaveId == "Human")){ $isInvalid = "Yes"; }
		if(!($takerSaveId == "Default")){
		if(($doNotCheck == "No")){
		$takerName = $taker->getName();
		$giverName = $giver->getName();
		if($giver->hasPermission("slapper.hit")){ $didItWork = "Yes"; }
		if($isInvalid == "No" && $didItWork == "No"){ $ev->setCancelled(); } }
		if($wasAnArrow == "Yes" && $isInvalid == "No"){ $ev->setCancelled(); }
		if(($didItWork === "No")){
		if($isInvalid == "No" && $noCommand == "No"){
		$configPart = $this->getConfig()->get($takerName);
		if($configPart == null){ $configPart = $this->getConfig()->get("FallbackCommand"); }
		foreach($configPart as $commandNew){
		$this->getServer()->dispatchCommand(new ConsoleCommandSender(), str_replace("{player}", $giverName, $commandNew));

		}
		}
		}
		}



		 }

	}


}