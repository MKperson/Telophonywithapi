<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Facade\FlareClient\Http\Response;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Session\Session;

class InContactController extends Controller
{


    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */

    public function agentskills()
    {
        return view('agentskills');
    }

    public function updateDB(Request $request)
    {

        $ch = curl_init();
        //this is a get
        curl_setopt($ch, CURLOPT_URL, "https://www.tmsliveonline.com/DataService/DataService.svc/GetSkillAssignments"); //or . $method_request
        // SSL important
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // no headers needed
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $output = curl_exec($ch);
        curl_close($ch);


        $this->response = json_decode($output);

        $agents = $this->response->GetSkillAssignmentsResult->Assignments->Agents; //['GetSkillAssignmentsResult']['Assignments']['Agents']
        //var_dump(json_encode($agents));

        //var_dump($_POST);
        
        //$value = $request->session()->get('XSRF-TOKEN');
        //$request->session()->flush();
        //$request->session()->put('XSRF-TOKEN',$value);
        foreach ($agents as $agent) {
            //var_dump($agent);

            // DB::table('agents')
            //     ->updateOrInsert(

            //         ['agentid' => $agent->AgentID],
            //         ['agentname' =>  $agent->AgentName , 'skills' =>   serialize($agent->Skills) ],

            //     );
        }
    }
    public function setAgents()
    {
        var_dump(sizeof($_POST));
        return $_POST;
    }

    public function getAgent(Request $request)

    {
        $query = DB::table('agents')->where('agentid',$_POST['id'])->get();
        $agent = $query[0];
        $temp = unserialize($agent->skills);
        $agent->skills =$temp;
        //var_dump(json_encode($agent));
        return json_encode($agent);
    }
}
