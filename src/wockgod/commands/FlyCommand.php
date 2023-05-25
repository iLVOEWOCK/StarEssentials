<?php

declare(strict_types=1);

namespace wockgod\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\player\PlayerToggleFlightEvent;
use pocketmine\player\Player;

class FlyCommand extends Command
{

    public function __construct()
    {
        parent::__construct("fly", "Allows the player to fly", "/fly");
        $this->setPermission("starfall.fly");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if(!$sender instanceof Player){
            $sender->sendMessage("This command can only be run by a player.");
            return true;
        }
        if($sender->hasPermission($this->getPermission())){
            if(!$sender->getAllowFlight()){
                $sender->setAllowFlight(true);
                $sender->sendMessage("§aYou can now fly!");
            } else{
                $sender->setAllowFlight(false);
                $sender->setFlying(false);
                $sender->resetFallDistance(); //reset fall distance when flight is turned off
                $sender->sendMessage("§cYou can no longer fly.");
            }
            return true;
        } else {
            $sender->sendMessage("§cYou do not have permission to use this command.");
            return true;
        }
    }
}
