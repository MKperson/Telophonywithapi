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
        $rnum = rand(0, 9);
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
            case 9:
                $message = 'Eric sees all Please Wait';
                break;
            default:
                break;
        }

        return view('agentskills', ['message' => $message]);
    }

    private function curlcalls($url, $method, $payload = null)
    {
        if ($method == "get" or $method == "GET") {
            $ch = curl_init();
            //this is a get
            curl_setopt($ch, CURLOPT_URL, $url);
            // SSL important
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            $err = curl_error($ch);
            if ($err) {
                curl_close($ch);
                return "- cURL Error #:" . $err;
            } else {
                curl_close($ch);
                return $result;
            }
        } elseif ($method == "POST" or $method == "post") {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            if (!$payload == null) {
                $data_string = json_encode($payload);
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
            }
            $result = curl_exec($ch);
            $err = curl_error($ch);
            if ($err) {
                curl_close($ch);
                return "- cURL Error #:" . $err;
            } else {
                curl_close($ch);
                return $result;
            }
        } else {
            return "ERROR";
        }
    }
    public function updateDB(Request $request)
    {
        //$url = "https://www.tmsliveonline.com/DataService/DataService.svc/GetSkillAssignments";
        $skillurl = 'https://www.tmsliveonline.com/DataService/DataService.svc/GetICSkills';
        $agenturl = 'https://www.tmsliveonline.com/DataService/DataService.svc/GetICAgents';

        $agents = json_decode($this->curlcalls($agenturl, "get"))->GetICAgentsResult->agents;

        $skills = json_decode($this->curlcalls($skillurl, "get"))->GetICSkillsResult->skills;

        session()->forget('skills');
        session()->put('skills', $skills);
        //return json_encode(session('agents'));
        return json_encode($agents);
    }
    public function setAgentsProf(Request $request)
    {

        $url = 'https://www.tmsliveonline.com/DataService/DataService.svc/ModifyAgentSkills';
        $data = $_POST['payload'];
        $responce = $this->curlcalls($url, "POST", $data);
    }

    public function getAgentSkills(Request $request)
    {
        $url = 'https://www.tmsliveonline.com/DataService/DataService.svc/GetAgentSkills?AgentID=' . $request['id'];
        $skills = json_decode($this->curlcalls($url, "get"))->GetAgentSkillsResult->Skills;
        return json_encode($skills);
    }
    public function addSkill(Request $request)
    {
        if ($request->isMethod('post')) {
            $arr = [];
            foreach ($request['sarr'] as $skillId) {
                array_push($arr, ['SkillID' => $skillId]);
            }
            $payload = array(
                'AgentID' => session('currentagentid'), 'Skills' => $arr, 'Proficiency' => 3
            );
            $url = 'https://www.tmsliveonline.com/DataService/DataService.svc/AddAgentSkills';
            $responce = $this->curlcalls($url, "POST", $payload);
            if (!$responce == true) {
                return "ERROR";
            }
        } else {
            session()->forget('currentagentid');
            session(['currentagentid' => $request['id']]);
            return json_encode(session('skills'));
        }
    }
    function delSkill(Request $request)
    {
        if ($request->isMethod("post")) {
            $skillid = $request['sid'];
            $agentid = $request['agentid'];

            $url = 'https://www.tmsliveonline.com/DataService/DataService.svc/RemoveAgentSkills';
            $payload = array('AgentID' => $agentid, 'Skills' => array(['SkillID' => $skillid]));
            // return json_encode($payload);

            $responce = json_encode($this->curlcalls($url, "post", $payload));
            return $responce;
        } else {
            return "false";
        }
    }
}
