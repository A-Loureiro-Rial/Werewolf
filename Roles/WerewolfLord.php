<?php
class WerewolfLord extends Player
{
    const Priority = "After";
    public $DoubleKill;

    public function __construct($name = "Anonymous")
    {
        $this->name = $name;
        $this->alignment = "Werewolves";
        $this->isAlive = true;
        $this->isAttacked = array(
            "byWerewolves" => false,
            "byWitch" => false,
            "byVillage" => false
        );
        $this->Votes = [];
        $this->DoubleKill = false;
    }

    public function power()
    {
        $target = "";

        echo "Select your target dear Lord." . PHP_EOL . "Available Preys: ";
        foreach(WerewolfGame::$Players as $player)
        {
            if ($player->isAlive == true)
            {
                echo $player->name . " ";
            }
        }
        if ($this->DoubleKill == false)
        {
            while ($target == "")
            {
                $target = readline ("My next meal is ");
                if (WerewolfGame::check($target) == false)
                {
                    echo 'You have to select a valid target.' . PHP_EOL;
                    $target = "";
                }
            }
        }
    }
}