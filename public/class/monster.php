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

    public function __construct($name, $pv, $mana, $initiative, $strength, $xp, $treasure = null)
    {
        $this->name = $name;
        $this->pv = (int)$pv;
        $this->mana = $mana === null ? null : (int)$mana;
        $this->initiative = (int)$initiative;
        $this->strength = (int)$strength;
        $this->xp = (int)$xp;
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