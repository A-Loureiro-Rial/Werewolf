<?php

function my_autoloader($class)
{
    include "Roles/" . $class . ".php";
}

class WerewolfGame
{
    public $nb_villagers;
    public $nb_werewolves;
    public static $Players;
    public $Turns;
    public $PowersEnabled;

    public function __construct()
    {
        self::$Players = [];
    }

//Adds a player to the current players list
    public function addPlayer($name, $role)
    {
        spl_autoload_register('my_autoloader');
        self::$Players [] = new $role($name);
        if (self::$Players[count(self::$Players) - 1]->alignment == 'Werewolves')
        {
            $this->nb_werewolves++;
        }
        else
        {
            $this->nb_villagers++;
        }
    }

    public static function check($target)
    {
        foreach (self::$Players as $player)
        {
            if ($player->name == $target && $player->isAlive == true)
            {
                return (true);
            }
        }
        return (false);
    }

    public static function list_alive_players()
    {
        $list = '';
        foreach (self::$Players as $player)
        {
            if ($player->isAlive == true)
            {
                $list .= ', ' . $player->name;
            }
        }
        $list = substr($list, 2, strlen($list) - 2);
        $list .= PHP_EOL;
        return $list;
    }

    //Check is target villager is alive, return true if it is, false if player is dead or doesn't exist (björf)
    public static function check_villager($target)
    {
        foreach (self::$Players as $player)
        {
            if ($player->name == $target && $player->isAlive == true && $player->alignment == 'Village')
            {
                return (true);
            }
        }
        return (false);
    }
    
    //Returns every player that is alive and is of the given alignment
    public static function available_target($alignment)
    {
        $str = '';
        foreach (self::$Players as $player)
        {
            if ($player->alignment == $alignment && $player->isAlive == true)
            {
                $str .= '-' . $player->name . PHP_EOL;
            }
        }
            return ($str);
    }

    //Launch the power of every living player given the priority (before or after the vote of werewolves for example)
    private function Priority($priority)
    {
        foreach(self::$Players as $Player)
        {
            if (defined(get_class($Player)."::PRIORITY"))
            {
                if ((get_class($Player))::PRIORITY == $priority && $Player->isAlive == true)
                {
                    $Player->power();
                }
            }
        }
    }

    // search for a certain player and returns it, return null if needle doesn't exist
    private static function find($needle)
    {
        foreach(self::$Players as $player)
        {
            if ($player->name == $needle)
            {
                return $player;
            }
        }
        return null;
    }
    private static function set_attack($group)
    {
        //search for the player with most votes on themselves
        $tmp = self::$Players[0];
        for ($i = 1; $i < count(self::$Players); $i++)
        {
            if (self::$Players[$i]->Votes > $tmp->Votes)
            {
                $tmp = self::$Players[$i];
            }
        }
        //set the attack by the group concerned
        foreach ($tmp->isAttacked as $key => $value)
        {
            if ($key == $group)
            {
                $value = true;
            }
        }
        //reset votes
        foreach (self::$Players as $player)
        {
            $player->Votes = 0;
        }
    }

    //Plays a turn during night for all werewolves
    private function WolvesVote()
    {
        echo 'Werewolves, wake up' . PHP_EOL;
        foreach (self::$Players as $player)
        {
            if ($player->alignment == "Werewolves")
            {
                echo 'targets available:'. PHP_EOL . PHP_EOL . self::available_target('Village') . PHP_EOL;
                $target = readline($player->name . ", Choose someone to eat ");
                while (!self::check_villager($target))
                {
                    echo 'you need to choose a valid target' . PHP_EOL;
                    $target = readline($player->name . ", Choose someone to kill ");
                }
                if (($target = self::find($target)) != null)
                {
                    $target->Votes++;
                }
            }
            self::set_attack('byWerewolves');
        }
    }

    private function kill_people()
    {
        sleep(30);
        foreach(self::$Players as $player)
        {
            foreach($player->isAttacked as $by => $attack)
            {
                if ($attack)
                {
                    if (get_class($player) == 'Ancient' && $by == 'byWerewolves' && $attack == true)
                    {
                        $player->lives--;
                        if ($player->lives <= 0)
                        {
                            $player->isAlive = false;
                            echo $player->name . "died. He was " . get_class($player);

                        }
                    }
                    else
                    {
                        $player->isAlive = false;
                        echo $player->name . "died. He was " . get_class($player);
                    }
                }
            }
        }
    }

    private function NightPhase()
    {
        $this->Priority("Before");
        $this->WolvesVote();
        $this->Priority("After");
        $this->kill_people();
    }

    private function DayPhase()
    {
        echo 'the village awaken' . PHP_EOL;
    }

    private function reset()
    {
        $this->Turns = 1;
        $this->PowersEnabled = true;
        foreach (self::$Players as $player)
        {
            $player->isAlive = true;
            foreach($player->isAttacked as $Attack)
            {
                $Attack = false;
            }
        }
    }

    public function game()
    {
        $this->reset();
        while ($this->nb_villagers > $this->nb_werewolves && $this->nb_werewolves > 0)
        {
            $this->NightPhase();
            $this->DayPhase();
            $this->Turns++;
        }
        if ($this->nb_werewolves > 0)
        {
            echo "Werewolves won the game MUAHAHAH UwU!" . PHP_EOL;
        }
        else
        {
            echo "The village has won! Hurray! \o/" . PHP_EOL;
        }
    }
}

$test = new WerewolfGame;
$test->addPlayer('Neko', 'Seer');
$test->addPlayer('Rick', 'Villager');
$test->addPlayer('Morty', 'Villager');
$test->addPlayer('Bjorflord', 'Werewolf');
$test->game();
