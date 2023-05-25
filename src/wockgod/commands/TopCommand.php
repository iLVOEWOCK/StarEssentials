<?php

namespace wockgod\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\world\Position;

class TopCommand extends Command {

    public function __construct()
    {
        parent::__construct("top", "Teleport to the highest point from where you're standing", "/top");
        $this->setPermission("starfall.top");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof Player){
            if ($sender->hasPermission($this->getPermission())){
                $x = floor($sender->getPosition()->getX());
                $z = floor($sender->getPosition()->getZ());
                $get_top = $sender->getWorld()->getHighestBlockAt($x, $z);
                $sender->teleport(new Position($x + 0.5, $get_top + 4, $z + 0.5, $sender->getWorld()));
                return;
            } else {
                return;
            }
        }
    }
}