<?php

class Monster
{
    protected $name;
    protected $pv;
    protected $mana;
    protected $initiative;
    protected $strength;
    protected $xp;
    protected $treasure;

    public function __construct($name, $health, $mana, $initiative, $strength, $xp, $treasure)
    {
        $this->name = $name;
        $this->pv = $pv;
        $this->mana = $mana;
        $this->initiative = $initiative;
        $this->strength = $strength;
        $this->xp = $xp;
        $this->treasure = $treasure;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPV()
    {
        return $this->pv;
    }

    public function getMana()
    {
        return $this->mana;
    }

    public function takeDamage($damage)
    {
        $this->pv -= $damage;
    }

    public function getInitiative(){
        return $this->initiative;
    }

    public function getStrength(){
        return $this->strength;
    }

    public function getXP()
    {
        return $this->xp;
    }

    public function getTreasure()
    {
        return $this->treasure;
    }
}

?>