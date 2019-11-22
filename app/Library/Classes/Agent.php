<?php

namespace App\Library\Classes;

class Agent
{
    private $id;
    private $name;
    private $skills;


    public function __construct($id, $name, $skills)
    {
        setID($id);
        setNAME($name);
        setSKILLS($skills);
    }

    private function setID($ID)
    {
        $this->id = $ID;
    }

    private function getID()
    {
        return $this->id;
    }
    private function setNAME($NAME)
    {
        $this->name = $NAME;
    }

    private function getNAME()
    {
        return $this->name;
    }
    private function setSKILLS($SKILLS)
    {
        $this->skills = $SKILLS;
    }

    private function getSKILLS()
    {
        return $this->skills;
    }


    public function __set($name, $value)
    {
        switch ($name) { //this is kind of silly example, bt shows the idea
            case 'ID':
                return $this->setID($value);
            case 'NAME':
                return $this->setNAME($value);
            case 'SKILLS':
                return $this->setSKILLS($value);
        }
    }

    public function __get($name)
    {
        switch ($name) {
            case 'ID':
                return $this->getID();
            case 'NAME':
                return $this->getNAME();
            case 'SKILLS':
                return $this->getSKILLS();
        }
    }
}
