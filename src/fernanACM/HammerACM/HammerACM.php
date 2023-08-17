<?php

#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
  
namespace fernanACM\HammerACM;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

use pocketmine\event\Listener;

use pocketmine\event\block\BlockBreakEvent;

use pocketmine\command\CommandSender;
use pocketmine\command\Command;
# Libs
use DaPigGuy\libPiggyUpdateChecker\libPiggyUpdateChecker;
# My files
use fernanACM\HammerACM\manager\HammerManager;
use fernanACM\HammerACM\utils\PluginUtils;

class HammerACM extends PluginBase implements Listener{

    /** @var Config $config */
    public Config $config;

    /** @var HammerACM $instance */
    private static HammerACM $instance;

    # CheckConfig
    private const CONFIG_VERSION = "1.0.0";

    /**
     * @return void
     */
    public function onLoad(): void{
        self::$instance = $this;
        $this->loadFiles();
    }

    /**
     * @return void
     */
    public function onEnable(): void{
        $this->loadCheck();
        $this->loadVirions();
        Server::getInstance()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @param CommandSender $sender
     * @param Command $command
     * @param string $label
     * @param array $args
     * @return boolean
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool{
        switch($command->getName()){
            case "hammeracm":
                if($sender instanceof Player){
                    if($sender->getInventory()->canAddItem(HammerManager::getInstance()->itemHammer($sender))){
                        $sender->sendMessage(self::getPrefix(). self::getMessage($sender, "Messages.success.successfully-delivered"));
                        PluginUtils::PlaySound($sender, "random.levelup", 1, 5.6);
                        $sender->getInventory()->addItem(HammerManager::getInstance()->itemHammer($sender));
                    }else{
                        $sender->sendMessage(self::getPrefix(). self::getMessage($sender, "Messages.error.inventory-full"));
                        PluginUtils::PlaySound($sender, "mob.villager.no", 1, 1);
                    }
                }else{
                    $sender->sendMessage("Use this command in-game");
                }
            break;
        }
        return true;
    }

    /**
     * @return void
     */
    private function loadFiles(): void{
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . "config.yml");
    }

    /**
     * @return void
     */
    private function loadCheck(): void{
         # CONFIG
         if((!$this->config->exists("config-version")) || ($this->config->get("config-version") != self::CONFIG_VERSION)){
            rename($this->getDataFolder() . "config.yml", $this->getDataFolder() . "config_old.yml");
            $this->saveResource("config.yml");
            $this->getLogger()->critical("Your configuration file is outdated.");
            $this->getLogger()->notice("Your old configuration has been saved as config_old.yml and a new configuration file has been generated. Please update accordingly.");
        }
    }

    /**
     * @return void
     */
    private function loadVirions(): void{
        foreach([
            "libPiggyUpdateChecker" => libPiggyUpdateChecker::class
            ] as $virion => $class
        ){
            if(!class_exists($class)){
                $this->getLogger()->error($virion . " virion not found. Please download HammerACM from Poggit-CI or use DEVirion (not recommended).");
                $this->getServer()->getPluginManager()->disablePlugin($this);
                return;
            }
        }
        # Update
        libPiggyUpdateChecker::init($this);
    }

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBreak(BlockBreakEvent $event): void{
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if(HammerManager::getInstance()->getWorldsEnabled($player)){
            if($player->getInventory()->getItemInHand()->equals(HammerManager::getInstance()->itemHammer($player)) && $player->hasPermission("hammeracm.use")){
                HammerManager::getInstance()->actionHammer($block);
            }
        }
    }

    /**
     * @return HammerACM
     */
    public static function getInstance(): HammerACM{
        return self::$instance;
    }

    /**
     * @param Player $player
     * @param string $key
     * @return string
     */
    public static function getMessage(Player $player, string $key): string{
        $messageArray = self::$instance->config->getNested($key, []);
        if(!is_array($messageArray)){
            $messageArray = [$messageArray];
        }
        $message = implode("\n", $messageArray);
        return PluginUtils::codeUtil($player, $message);
    }

    /**
     * @return string
     */
    public static function getPrefix(): string{
        return TextFormat::colorize(self::$instance->config->get("Prefix"));
    }
}