<?php

declare(strict_types=1);

namespace GamerZone7s\JoinGear;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {

    private Config $playerData;

    public function onEnable(): void {
        $this->getLogger()->info("JoinGear Plugin Enabled!");

        // Register the event listener
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        // Load or create player data file
        @mkdir($this->getDataFolder()); // Ensure data folder exists
        $this->playerData = new Config($this->getDataFolder() . "players.yml", Config::YAML, []);
    }

    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $playerName = $player->getName();

        // Check if player has already received the kit
        if (!$this->playerData->get($playerName)) {
            // Create the items
            $ironPickaxe = VanillaItems::IRON_PICKAXE();
            $ironAxe = VanillaItems::IRON_AXE();
            $ironShovel = VanillaItems::IRON_SHOVEL();
            $waterBucket = VanillaItems::WATER_BUCKET();
            $food = VanillaItems::BREAD()->setCount(10); // 10 Bread
            
            // Add the items to the player's inventory
            $player->getInventory()->addItem($ironPickaxe);
            $player->getInventory()->addItem($ironAxe);
            $player->getInventory()->addItem($ironShovel);
            $player->getInventory()->addItem($waterBucket);
            $player->getInventory()->addItem($food);

            // Send a welcome message to the player
            $player->sendMessage("Welcome to the server! Here are some tools to get you started.");

            // Mark player as having received the kit
            $this->playerData->set($playerName, true);
            $this->playerData->save();
        }
    }

    public function onDisable(): void {
        $this->getLogger()->info("JoinGear Plugin Disabled!");
    }
}