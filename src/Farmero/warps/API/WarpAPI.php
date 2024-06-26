<?php

declare(strict_types=1);

namespace Farmero\warps\API;

use pocketmine\world\Position;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\Server;

use Farmero\warps\Warps;

class WarpAPI
{
    private Config $data;

    public function __construct()
    {
        $this->data = new Config(Warps::getInstance()->getDataFolder() . "WarpsData.json", Config::JSON);
    }

    public function getAllWarps(): array
    {
        $warps = [];
        foreach ($this->data->getAll() as $name => $pos) {
            $warps[] = $name;
        }
        return $warps;
    }

    public function existWarp(string $name): bool
    {
        return in_array($name, $this->getAllWarps());
    }

    public function addWarp(Player $pos, string $name): void
    {
        $this->data->set($name, [$pos->getPosition()->getX(), $pos->getPosition()->getY(), $pos->getPosition()->getZ(), $pos->getWorld()->getDisplayName()]);
        $this->save();
    }

    public function getWarp(string $name): Position
    {
        $pos = $this->data->get($name);
        return new Position($pos[0], $pos[1], $pos[2], Server::getInstance()->getWorldManager()->getWorldByName($pos[3]));
    }

    public function delWarp(string $name): void
    {
        $this->data->remove($name);
        $this->save();
    }

    public function save(): void
    {
        $this->data->save();
    }
}
