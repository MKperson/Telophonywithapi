<?php
$ch = curl_init();
        //this is a get
        curl_setopt($ch, CURLOPT_URL, "https://www.tmsliveonline.com/DataService/DataService.svc/GetSkillAssignments");
        // SSL important
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $output = curl_exec($ch);
        curl_close($ch);

        $this->response = json_decode($output);

        $agents = $this->response->GetSkillAssignmentsResult->Assignments->Agents;

        foreach ($agents as $agent) {
            echo $agent->AgentID.'<br />';
        }