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
        $message = 'Please Wait';
        $rnum = rand(0, 8);
        //$rnum = 6;
        switch ($rnum) {
            case 0:
                $message = 'Its all Sephens fault';
                break;
            case 1:
                $message = 'Waiting on InContact';
                break;
            case 2:
                $message = 'Will Broke it. Fix in progress. Please Wait';
                break;
            case 3:
                $message = 'Constructing Additional Pylons';
                break;
            case 4:
                $message = 'Waiting on Stephen\'s API';
            break;
            case 5:
                $message = 'All of IT is busy Please Wait';
            break;
            case 6:
                $message = 'To load or not to load that is the question';
            break;
            case 7:
                $message = 'New guy has been notified of your long Wait';
            break;
            case 8: 
                $message = 'I lost the GAME';
            break;
            default:
                break;
        }

        return view('agentskills', ['message' => $message]);
    }

    public function updateDB(Request $request)
    {
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
            
            session()->forget($agent->AgentID . 'A');
            session()->put($agent->AgentID . 'A', $agent);
        }
        $skills = $this->response->GetSkillAssignmentsResult->Assignments->Skills;
        session()->forget('skills');
        session()->put('skills', $skills);
        //return json_encode(session('agents'));
        return json_encode($agents);
    }
    public function setAgentsProf(Request $request)
    {

        $url = 'https://www.tmsliveonline.com/DataService/DataService.svc/ModifyAgentSkills';
        $XCSRFTOKEN = $_POST['X-CSRF-TOKEN'];
        $data = $_POST['payload'];

        $data_string = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type:application/json',
                'Content-Length: ' . strlen($data_string)
            )
        );

        $result = curl_exec($ch);
        $err = curl_error($ch);

        //var_dump($data_string);
        if ($err) {
            echo "- cURL Error #:" . $err;
        } else {
            $agent = session($_POST['payload']['AgentID'] . 'A');
            // $agents = session('agents');

            // foreach ($agents as $agent) {
            //     if ($agent->AgentID == $_POST['payload']['AgentID']) {
            foreach ($agent->Skills as $skills) {
                if ($skills->SkillID == $_POST['payload']['Skills'][0]["SkillID"]) {
                    $skills->ProficiencyValue = $_POST['payload']['Proficiency'];
                    break;
                }
            }
            //         break;
            //     }
            // }
            session()->put($agent->AgentID . 'A', $agent);
        }
        curl_close($ch);
    }

    public function getAgent(Request $request)

    {
        $agent = session($_POST['id'] . 'A');
        return json_encode($agent);

        // $agents = session('agents');
        // foreach ($agents as $agent) {

        //     if ($agent->AgentID == $_POST['id']){
        //         //var_dump($agent);
        //         return json_encode($agent);
        //     }

        // }

    }
    public function addSkill()
    {
        return json_encode(session('skills'));
    }
}
