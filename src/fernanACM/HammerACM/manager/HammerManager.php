<?php

#      _       ____   __  __ 
#     / \     / ___| |  \/  |
#    / _ \   | |     | |\/| |
#   / ___ \  | |___  | |  | |
#  /_/   \_\  \____| |_|  |_|
# The creator of this plugin was fernanACM.
# https://github.com/fernanACM
  
namespace fernanACM\HammerACM\manager;

use pocketmine\Server;
use pocketmine\player\Player;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;

use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;

use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;

use pocketmine\world\Position;

use fernanACM\HammerACM\HammerACM;

class HammerManager{

    /** @var HammerManager|null $instance */
    private static ?HammerManager $instance = null;

    /** @var mixed $worldGuard */
    private static mixed $worldGuard;

    /** @var array $blocks */
    private static array $blocks;

    private const BLACKLIST = "blacklist";
    private const WHITELIST = "whitelist";

    private function __construct(){
        self::$blocks = [
            VanillaBlocks::BEDROCK()->getTypeId(), VanillaBlocks::OBSIDIAN()->getTypeId(),
            VanillaBlocks::CRYING_OBSIDIAN()->getTypeId(), VanillaBlocks::GLOWING_OBSIDIAN()->getTypeId(),
            VanillaBlocks::REINFORCED_DEEPSLATE()->getTypeId()
        ];
    }

    /**
     * @return void
     */
    public function initWorldGuard(): void{
        if(!is_null(Server::getInstance()->getPluginManager()->getPlugin("WorldGuard"))){
            self::$worldGuard = Server::getInstance()->getPluginManager()->getPlugin("WorldGuard");
            HammerACM::getInstance()->getLogger()->notice("WorldGuard support enabled for HammerACM");
        }
    }

    /**
     * @param Block $block
     * @return void
     */
    public function actionHammer(Block $block): void{
        $blockPos = $block->getPosition();
        $blockWorld = $blockPos->getWorld();

        $minX = $blockPos->x - 1;
        $maxX = $blockPos->x + 1;

        $minY = $blockPos->y - 1;
        $maxY = $blockPos->y + 1;

        $minZ = $blockPos->z - 1;
        $maxZ = $blockPos->z + 1;

        for($x = $minX; $x <= $maxX; $x++){
            for($y = $minY; $y <= $maxY; $y++){
                for($z = $minZ; $z <= $maxZ; $z++){
                    $block = $blockWorld->getBlockAt($x, $y, $z);
                    if(!in_array($block->getTypeId(), self::$blocks)){
                        $position = new Vector3($x, $y, $z);
                        $blockWorld->setBlock($position, VanillaBlocks::AIR());
                        $blockWorld->dropItem($position, $this->addItem($block));
                    }
                }
            }
        }
    }

    /**
     * @param Block $block
     * @return Item
     */
    private function addItem(Block $block): Item{
        switch($block->getTypeId()){
            case VanillaBlocks::AIR()->getTypeId();
            return VanillaItems::AIR();
            
            case VanillaBlocks::GRASS()->getTypeId();
            return VanillaBlocks::DIRT()->asItem();

            case VanillaBlocks::GLASS_PANE()->getTypeId();
            case VanillaBlocks::GLASS()->getTypeId();
            case VanillaBlocks::STAINED_GLASS()->getTypeId();
            case VanillaBlocks::STAINED_GLASS_PANE()->getTypeId();
            case VanillaBlocks::HARDENED_GLASS()->getTypeId();
            case VanillaBlocks::HARDENED_GLASS_PANE()->getTypeId();
            return VanillaItems::AIR();

            case VanillaBlocks::STONE()->getTypeId():
            return VanillaBlocks::COBBLESTONE()->asItem();

            case VanillaBlocks::IRON_ORE()->getTypeId();
            case VanillaBlocks::DEEPSLATE_IRON_ORE()->getTypeId();
            return VanillaItems::RAW_IRON();

            case VanillaBlocks::GOLD_ORE()->getTypeId();
            case VanillaBlocks::DEEPSLATE_GOLD_ORE()->getTypeId();
            return VanillaItems::RAW_GOLD();

            case VanillaBlocks::COPPER_ORE()->getTypeId();
            case VanillaBlocks::DEEPSLATE_COPPER_ORE()->getTypeId();
            return VanillaItems::RAW_COPPER();

            case VanillaBlocks::COAL_ORE()->getTypeId();
            case VanillaBlocks::DEEPSLATE_COAL_ORE()->getTypeId();
            return VanillaItems::COAL()->setCount(mt_rand(1, 2));

            case VanillaBlocks::DIAMOND_ORE()->getTypeId();
            case VanillaBlocks::DEEPSLATE_DIAMOND_ORE()->getTypeId();
            return VanillaItems::DIAMOND();

            case VanillaBlocks::REDSTONE_ORE()->getTypeId();
            case VanillaBlocks::DEEPSLATE_REDSTONE_ORE()->getTypeId();
            return VanillaItems::REDSTONE_DUST()->setCount(mt_rand(2, 3));

            case VanillaBlocks::LAPIS_LAZULI_ORE()->getTypeId();
            case VanillaBlocks::DEEPSLATE_LAPIS_LAZULI_ORE()->getTypeId();
            return VanillaItems::LAPIS_LAZULI()->setCount(mt_rand(2, 3));

            case VanillaBlocks::EMERALD_ORE()->getTypeId();
            case VanillaBlocks::DEEPSLATE_EMERALD_ORE()->getTypeId();
            return VanillaItems::EMERALD()->setCount(mt_rand(1, 2));

            case VanillaBlocks::NETHER_QUARTZ_ORE()->getTypeId();
            return VanillaItems::NETHER_QUARTZ()->setCount(mt_rand(1, 2));;

            case VanillaBlocks::NETHER_GOLD_ORE()->getTypeId();
            return VanillaItems::GOLD_NUGGET()->setCount(mt_rand(1, 3));

            case VanillaBlocks::GLOWSTONE()->getTypeId();
            return VanillaItems::GLOWSTONE_DUST()->setCount(mt_rand(1, 2));

            case VanillaBlocks::SEA_LANTERN()->getTypeId();
            return VanillaItems::PRISMARINE_CRYSTALS()->setCount(mt_rand(1, 2));

            case VanillaBlocks::END_PORTAL_FRAME()->getTypeId();
            return VanillaItems::AIR();

            default:
            return $block->asItem();
        }
    }

    /**
     * @param Player $player
     * @return Item
     */
    public function itemHammer(Player $player): Item{
        $item = VanillaItems::NeTHERITE_PICKAXE();
        $item->setNamedTag(CompoundTag::create()->setString("HammerACM", "HammerACM"));
        $item->setCustomName(HammerACM::getMessage($player,"Hammer.item-name"));
        $item->setLore([HammerACM::getMessage($player,"Hammer.item-lore")]);
        $item->addEnchantment(new EnchantmentInstance(VanillaEnchantments::UNBREAKING()));
        $item->setUnbreakable(true);
        return $item;
    }

    /**
     * @param Position $position
     * @return boolean
     */
    public function getWorldGuardProtection(Position $position): bool{
        if(isset(self::$worldGuard)){
            $result = true;
            $region = self::$worldGuard->getRegionFromPosition($position);
            if($region !== ""){
                if($region->getFlag("block-break") === "false"){
                    $result = false;
                }
            }
            return $result;
        }
        return true;
    }

    /**
     * @param Player $player
     * @return boolean
     */
    public function getWorldsEnabled(Player $player): bool{
        $mode = HammerACM::getInstance()->config->getNested("Settings.WorldManager.mode");
        switch(strtolower($mode)){
            case self::BLACKLIST:
                if($this->isBlacklistMode($player->getWorld()->getFolderName())){
                    return false;
                }
            break;
    
            case self::WHITELIST:
                if(!$this->isWhitelistMode($player->getWorld()->getFolderName())){
                    return false;
                }
            break;
    
            default:
                return $this->isWhitelistMode($player->getWorld()->getFolderName());
        }
        return true;
    }
    
    /**
     * @param string $worldName
     * @return boolean
     */
    public function isWhitelistMode(string $worldName): bool{
        $worldsWhitelist = HammerACM::getInstance()->config->getNested("Settings.WorldManager.worlds-whitelist");
        return in_array($worldName, $worldsWhitelist);
    }
    
    /**
     * @param string $worldName
     * @return boolean
     */
    public function isBlacklistMode(string $worldName): bool{
        $worldsBlacklist = HammerACM::getInstance()->config->getNested("Settings.WorldManager.worlds-blacklist");
        return !in_array($worldName, $worldsBlacklist);
    }

    /**
     * @return self
     */
    public static function getInstance(): self{
        if(is_null(self::$instance)) self::$instance = new self();
        return self::$instance;
    }
}