<?php
require_once "WerewolfGame.php";
class Witch extends Player
{
    const PRIORITY = "After";
    public $LifePotion;
    public $DeathPotion;

    public function __construct($name = "Anonymous")
    {
        $this->name = $name;
        $this->alignment = "Village";
        $this->isAlive = true;
        $this->isAttacked = array(
            "byWerewolves" => false,
            "byWitch" => false,
            "byVillage" => false
        );
        $this->Votes = [];
        $this->LifePotion = true;
        $this->DeathPotion = true;
    }

    public function power()
    {
        foreach (WerewolfGame::$Players as $player)
        {
            if ($player->isAttacked['byWerewolves'] == true)
            {
                echo $player->name . " a été désigné par les loups" . PHP_EOL;
            }
        }
    }
}