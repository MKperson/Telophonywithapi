<?php

namespace App\Library\Classes;

use App\Library\Classes\Agent;

class Agents
{
    private $listagent;
    ///takes array of agents
    public function __construct($agents)
    {
        array_push($this->listagent, $agents);
        foreach ($agents as $agent) {
            //$temp = new Agent($agent['AgentID'], $agent['AgentName'], $agent["Skills"]);

            //array_push($this->listagent, $agents);
        }
    }
    public function getAgents()
    {
        return $this->listagent;
    }
}
