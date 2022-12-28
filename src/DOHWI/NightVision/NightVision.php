<?php

declare(strict_types=1);

namespace DOHWI\NightVision;

use DOHWI\NightVision\Command\NightVisionCommand;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Symfony\Component\Filesystem\Path;

final class NightVision extends PluginBase
{
    public static Config $config;

    private function initConfig(): void
    {
        $this->saveDefaultConfig();
        $lang = $this->getConfig()->get("language");
        $this->saveResource("$lang.json");
        $file = Path::join($this->getDataFolder(), "$lang.json");
        self::$config = new Config($file, Config::JSON);
    }

    protected function onEnable(): void
    {
        $this->initConfig();
        $this->getServer()->getCommandMap()->register($this->getName(), new NightVisionCommand());
    }
}