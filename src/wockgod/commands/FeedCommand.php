<?php

namespace wockgod\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use wockgod\Essentials;

class FeedCommand extends Command {

    private $cooldowns = [];
    private $cooldown;


    public function __construct()
    {
        parent::__construct("feed");
        $this->setDescription("Gives you maximum hunger.");
        $this->setUsage("/feed");
        $this->setPermission("starfall.feed");
        $this->cooldown = Essentials::getInstance()->getConfig()->get("feed-command-cooldown", 60);

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

        $sender->getHungerManager()->setFood(20);
        $sender->sendMessage("§r§aYour hunger has been settled.");

        $this->cooldowns[$playerName] = $time;
        return true;
    }

}