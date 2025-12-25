<?php
class Hero
{
    protected $name;
    protected $pv;
    protected $mana;
    protected $strength;
    protected $inventory;
    protected $initiative;
    protected $xp;
    protected $currentLevel;

    public function __construct($name, $pv, $mana, $strength, $inventory, $initiative, $xp, $currentLevel) {
        $this->name = $name;
        $this->pv = $pv;
        $this->mana = $mana;
        $this->strength = $strength;
        $this->inventory = $inventory;
        $this->initiative = $initiative;
        $this->xp = $xp;
        $this->currentLevel = $currentLevel;
    }

    public function getName() {
        return $this->name;
    }

    public function getPV() {
        return $this->pv;
    }

    public function getMana() {
        return $this->mana;
    }

    public function getStrength(){
        return $this->strength;
    }

    public function getInventory(){
        return $this->inventory;
    }

    public function getInitiative(){
        return $this->initiative;
    }

    public function getXP() {
        return $this->xp;
    }

    public function getCurrentLevel(){
        return $this->currentLevel;
    }

    public function setPV($pv) {
        $this->pv = $pv;
    }

    public function setMana($mana){
        $this->mana = $mana;
    }

    public function setStrength($strength){
        $this->strength = $strength;
    }

    public function setInitiative($initiative){
        $this->initiative = $initiative;
    }

    public function setInventory($inventory){
        $this->inventory = $inventory;
    }

    public function setXP($xp) {
        $this->xp = $xp;
    }

    public function setCurrentLevel($currentLevel) {
        $this->currentLevel = $currentLevel;
    }
}
?>