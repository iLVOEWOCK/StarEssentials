<?php

namespace wockgod;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use wockgod\commands\ExpCommand;
use wockgod\commands\FeedCommand;
use wockgod\commands\FlyCommand;
use wockgod\commands\HealCommand;
use wockgod\commands\SpawnCommand;
use wockgod\commands\TopCommand;

class Essentials extends PluginBase implements Listener {

    private static $instance;

    public function onEnable(): void
    {
        self::$instance = $this;
        $this->saveDefaultConfig();
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->registerCommands();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    public function registerCommands() {
        $commandMap = $this->getServer()->getCommandMap();
        $commandMap->register("feed", new FeedCommand());
        $commandMap->register("heal", new HealCommand());
        $commandMap->register("fly", new FlyCommand());
        $commandMap->register("top", new TopCommand());
        $commandMap->register("xp", new ExpCommand());
        $commandMap->register("spawn", new SpawnCommand());
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function onToggleFlight(PlayerToggleFlightEvent $event){
        $player = $event->getPlayer();
        if(!$player->hasPermission("starfall.fly")){
            $player->setAllowFlight(false);
            $player->setFlying(false);
            $player->resetFallDistance(); //reset fall distance when flight is turned off
            $event->cancel();
        }
    }

    public function onEntityDamage(EntityDamageByEntityEvent $event) {
        $damager = $event->getDamager();
        $target = $event->getEntity();
        if ($damager instanceof Player && $target instanceof Player) {
            $damager->setFlying(false);
            $target->setFlying(false);
            return true;
        } elseif ($damager instanceof Player) {
            $damager->setFlying(false);
            return true;
        } elseif ($target instanceof Player) {
            $target->setFlying(false);
            return true;
        }
        return true;
    }
}
