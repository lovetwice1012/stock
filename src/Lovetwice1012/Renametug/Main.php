<?php

namespace Lovetwice1012\stock;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\scheduler\Task;

class Main extends PluginBase implements Listener {
	
	public $config;
		       
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		if($this->getServer()->getPluginManager()->getPlugin("EconomyAPI") != null){
                $this->EconomyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
                }else{
                $this->getLogger()->warning("Economy API is not found.");
                $this->getServer()->getPluginManager()->disablePlugin($this);
                }
		$this->config1 = new Config($this->getDataFolder() . "stock.yml", Config::YAML);
		$time = 5; 
                $this->getScheduler()->scheduleRepeatingTask(new TimeTask($this->config1), $time);
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
	{
		$config = $this->config;
                switch($label){
			case "sellstock":
			    if(!isset($args[0])){
				$sender->sendMessage("§4使用法: /sellstock amount");
				return true;
			    }
			    $havestock = $config->get($sender->getName()); 
			    $amount = $args[0];
			    if($havestock<$amount){
				$sender->sendMessage("§4所持株数が足りません。");    
				return true;
			    }
			    $downprice = floor($amount/2);
			    $this->EconomyAPI->addMoney($sender->getName(),$config->get("price")*$amount);
		            $config->set("price",$config->get("price")-$downprice);    
			    $config->set($sender->getName,$havestock-$amount);
			    $sender->sendMessage("株を売却しました。");
			    break;
		       	case "buystock":
			    if(!isset($args[0])){
				$sender->sendMessage("§4使用法: /buystock amount");
				return true;
			    }
			    $money = $this->EconomyAPI->myMoney($sender->getName());
			    $havestock = $config->get($sender->getName()); 
			    $amount = $args[0];
			    $price = $config->get("price");
			    if($money<$price*$amount){
				$sender->sendMessage("§4所持金が足りません。");    
				return true;
			    }
			    $upprice = floor($amount/2);
			    $this->EconomyAPI->reduceMoney($sender->getName(),$price*amount);
		            $config->set("price",$config->get("price")+$upprice);    
			    $config->set($sender->getName,$havestock+$amount);
			    $sender->sendMessage("株を売却しました。");
			    break;
			case "stock":	
		            $sender->sendMessage("現在の株価は ".$config->get("price")." 円です。");
			    break;
		} 
		$config->save();
                return true;
    }

}

class TimeTask extends Task
{

    public function __construct($config)
    { 
        $this->config = $config;
    }

    public function onRun()
    {
        
    }
}
