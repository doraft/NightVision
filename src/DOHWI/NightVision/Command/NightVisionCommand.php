<?php

declare(strict_types=1);

namespace DOHWI\NightVision\Command;

use DOHWI\NightVision\NightVision;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\data\bedrock\EffectIds;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\network\mcpe\protocol\OnScreenTextureAnimationPacket;
use pocketmine\player\Player;

final class NightVisionCommand extends Command
{
    private string $giveMessage;
    private string $removeMessage;

    public function __construct()
    {
        parent::__construct(NightVision::$config->getNested("COMMANDS.NIGHT_VISION.NAME"), NightVision::$config->getNested("COMMANDS.NIGHT_VISION.DESCRIPTION"));
        $prefix = NightVision::$config->get("MESSAGE_PREFIX");
        $this->giveMessage = $prefix.NightVision::$config->getNested("MESSAGES.GIVE_EFFECT");
        $this->removeMessage = $prefix.NightVision::$config->getNested("MESSAGES.REMOVE_EFFECT");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if(!$sender instanceof Player) return;
        $nightVision = VanillaEffects::NIGHT_VISION();
        if($sender->getEffects()->has($nightVision)) {
            $sender->getEffects()->remove($nightVision);
            $sender->getNetworkSession()->sendDataPacket(OnScreenTextureAnimationPacket::create(EffectIds::BLINDNESS));
            $sender->sendMessage($this->removeMessage);
        } else {
            $sender->getEffects()->add(new EffectInstance($nightVision, 999999, 1, false));
            $sender->getNetworkSession()->sendDataPacket(OnScreenTextureAnimationPacket::create(EffectIds::NIGHT_VISION));
            $sender->sendMessage($this->giveMessage);
        }
    }
}