<?php

namespace wockgod\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SpawnCommand extends Command {

    public function __construct() {
        parent::__construct("spawn", "Teleport to spawn point", "/spawn");
        $this->setPermission("starfall.spawn");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::RED . "This command can only be run by a player!");
            return false;
        }
        switch (strtolower($args[0] ?? "")) {
            case "set":
                if (!$sender->hasPermission("starfall.spawn.set")) { // Check for permission
                    $sender->sendMessage(TextFormat::RED . "You do not have permission to use this subcommand!");
                    return false;
                }
                $sender->getWorld()->setSpawnLocation($sender->getPosition());
                $sender->sendMessage(TextFormat::GREEN . "Spawn point set to your current location.");
                return true;
            default:
                $sender->teleport($sender->getWorld()->getSpawnLocation());
                $sender->sendMessage(TextFormat::GREEN . "Teleporting to spawn point.");
                return true;
        }
    }
}
