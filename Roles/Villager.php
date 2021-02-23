<?php
class Villager extends Player
{
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
    }
}
