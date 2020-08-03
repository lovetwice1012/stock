<?php

namespace Lovetwice1012\Renametug;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

	private $fly;
	public $myConfig;
		       
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
       
		$this->myConfig = new Config($this->getDataFolder() . "MyConfig.yml", Config::YAML);

	}

	public function onJoin(PlayerJoinEvent $event){
		$config = $this->myConfig;
  $player = $event->getPlayer();
  /** @var Config $config */
  if($config->exists($player->getName())){
	  $player->setNameTag($config->get($player->getName()));
	  $player->setDisplayName($config->get($player->getName()));
  }		
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
	{
		$config = $this->myConfig;
        if ($label === "atama") {
            if ($sender->isOp()) {
		if(isset($args[0])){
		if(isset($args[1])){    
			$player = $this->getServer()->getPlayer($args[0]);
                    $tag = $args[1]; 
		    $config->set($player->getName(), "[§d".$tag."§r]".$player->getName());
		    $config->save();
		    $player->setNameTag("[§d".$tag."§r]".$player->getName());
		    $player->setDisplayName("[§d".$tag."§r]".$player->getName());
		}else{
			$player = $this->getServer()->getPlayer($args[0]);
			$config->set($player->getName(),$player->getName());
			$config->save();
               
                $player->setNameTag($player->getName());
                $player->setDisplayName($player->getName());
		}
	    	    $sender->sendMessage("頭の上の名前表示が".$config->get($player->getName())."になりました");
		}else{
	
		    $sender->sendMessage("§c使用方法:/atama 変更したい人の名前　変更後の名前");
			}
		
            }else{
	        $sender->sendMessage("§c権限がありません");
	    }
	
        }
        return true;
    }

}
