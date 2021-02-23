<?php
require_once "WerewolfGame.php";
class Seer extends Player
{
    const PRIORITY = "Before";
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

    public function power()
    {
        $target = '';
        while ($target == '')
        {
            echo $this->name . ", choose someone to know who they really are" . PHP_EOL;
            echo "valid targets are " . WerewolfGame::list_alive_players();
            $target = readline("Let me see who's hiding behind ");
            if (WerewolfGame::check($target) == false)
            {
                $target = '';
                echo 'You have to choose a valid target' . PHP_EOL;
            }
        }
        foreach (WerewolfGame::$Players as $player)
        {
            if ($player->name == $target)
            {
                if (get_class($player) == "SneakyWerewolf")
                {
                    echo $player->name . " is a villager" . PHP_EOL;
                }
                else
                { 
                    echo $player->name . " is a " . get_class($player) . PHP_EOL;
                }
            }
        }
        echo $this->name . ', you go back to sleep' . PHP_EOL;
        sleep(3);
        system('clear');
    }
}

