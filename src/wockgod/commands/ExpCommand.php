<?php

namespace wockgod\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\sound\FizzSound;
use pocketmine\world\sound\XpCollectSound;

class ExpCommand extends Command
{

    public function __construct()
    {
        parent::__construct("xp", "view your xp", "/exp", ["exp", "myxp"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage("§r§cThis command must me used in-game.");
            return;
        }
        if (empty($args)) {
            $exp = number_format($sender->getXpManager()->getCurrentTotalXp(), 1);
            $level = $sender->getXpManager()->getXpLevel();
            $levelup = self::getExpToLevelUp($sender->getXpManager()->getCurrentTotalXp());
            $sender->sendMessage($sender->getNameTag() . " §r§6has §r§c$exp EXP §r§6(level §r§c" . $level . "§r§6) §r§6and needs $levelup more exp to level up.");
            return;
        }
        switch ($args[0]) {
            case 'add':
                if (!$sender->hasPermission('starfall.xp.add')) {
                    $sender->sendMessage("§cYou do not have permission to use this command.");
                    return;
                }
                if (count($args) !== 3) {
                    $sender->sendMessage("§cUsage: /xp add <player> <amount>");
                    return;
                }
                $player = Server::getInstance()->getPlayerByPrefix($args[1]);
                if (!$player) {
                    $sender->sendMessage("§cPlayer not found.");
                    return;
                }
                $amount = (int) $args[2];
                if ($amount <= 0) {
                    $sender->sendMessage("§cAmount must be a positive integer.");
                    return;
                }
                $currentXp = $player->getXpManager()->getCurrentTotalXp();
                $player->getXpManager()->addXp($amount);
                $newXp = $player->getXpManager()->getCurrentTotalXp();
                $sender->getWorld()->addSound($sender->getPosition(), new XpCollectSound());
                $sender->sendMessage("§aAdded " . number_format($amount) ."XP to " . $player->getName() . ". Their new XP is " . number_format($newXp) . ".");
                break;
            case 'remove':
                if (!$sender->hasPermission('starfall.xp.remove')) {
                    $sender->sendMessage("§cYou do not have permission to use this command.");
                    return;
                }
                if (count($args) !== 3) {
                    $sender->sendMessage("§cUsage: /xp remove <player> <amount>");
                    return;
                }
                $player = Server::getInstance()->getPlayerByPrefix($args[1]);
                if (!$player) {
                    $sender->sendMessage("§cPlayer not found.");
                    return;
                }
                $amount = (int) $args[2];
                if ($amount <= 0) {
                    $sender->sendMessage("§cAmount must be a positive integer.");
                    return;
                }
                $currentXp = $player->getXpManager()->getCurrentTotalXp();
                if ($amount > $currentXp) {
                    $sender->sendMessage("§c" . $player->getName() . " does not have that much XP.");
                    return;
                }
                $player->getXpManager()->subtractXp($amount);
                $newXp = $player->getXpManager()->getCurrentTotalXp();
                $sender->getWorld()->addSound($sender->getPosition(), new FizzSound());
                $sender->sendMessage("§aRemoved " . number_format($amount) . "XP from " . $player->getName() . ". Their new XP is ". number_format($newXp) . ".");
                break;
            default:
                $sender->sendMessage("§cUsage: /xp [add|remove] <player> <amount>");
                break;
        }
    }

    /**
     * @param int $level
     * @return int
     */
    public static function getExpToLevelUp(int $level): int
    {
        if ($level <= 15) {
            return 2 * $level + 7;
        } else if ($level <= 30) {
            return 5 * $level - 38;
        } else {
            return 9 * $level - 158;
        }
    }
}
