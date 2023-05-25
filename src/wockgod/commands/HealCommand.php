<?php

namespace wockgod\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use wockgod\Essentials;

class HealCommand extends Command {

    private $cooldowns = [];
    private $cooldown;


    public function __construct()
    {
        parent::__construct("heal");
        $this->setDescription("Restore your health back to full");
        $this->setUsage("/heal");
        $this->setPermission("starfall.heal");
        $this->cooldown = Essentials::getInstance()->getConfig()->get("heal-command-cooldown", 60);

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§r§cThis command must me used in-game.");
            return false;
        }

        // Check permission
        if (!$sender->hasPermission($this->getPermission())) {
            $sender->sendMessage("§r§cYou don't have permission to use this command.");
            return false;
        }

        $playerName = strtolower($sender->getName());
        $time = time();
        if (isset($this->cooldowns[$playerName]) && ($this->cooldowns[$playerName] > $time - $this->cooldown)) {
            $remainingTime = $this->cooldowns[$playerName] - $time + $this->cooldown;
            $sender->sendMessage(TF::RED . "You must wait " . $remainingTime . " seconds before using this command again.");
            return true;
        }
        $maxHealth = $sender->getMaxHealth();
        $sender->setHealth($maxHealth);
        $sender->sendMessage("§r§aYour health has been restored.");

        $this->cooldowns[$playerName] = $time;
        return true;
    }

}