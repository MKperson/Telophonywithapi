<?php


namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ErrorException;
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
        // $rnum = rand(0, 9);
        //$rnum = 6;
        $daysuntilxmas = ceil((mktime(0, 0, 0, 12, 25, date('Y')) - time()) / 86400);

        $marr = array(
            'Its all Sephens fault',
            'Waiting on InContact',
            'Will Broke it. Fix in progress. Please Wait',
            'Constructing Additional Pylons',
            'Waiting on Stephen\'s API',
            'All of IT is Working on it Please Wait',
            'To load or not to load that is the question',
            'New guy has been notified of your long Wait',
            'I lost the GAME',
            'Eric sees all Please Wait',
            'Please do 30 push-ups to complete loading',
            $daysuntilxmas . ' Days to Christmas',
            'We are the Borg you will be assimilated resistance is futile.'
        );
        $rnum = rand(0, count($marr) - 1);
        return view('agentskills', ['message' => $marr[$rnum]]);
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
            $delurl = 'https://www.tmsliveonline.com/DataService/DataService.svc/RemoveAgentSkills';
            $ids = session('currentagentid');
            $url = 'https://www.tmsliveonline.com/DataService/DataService.svc/AddAgentSkills';
            $arr = [];
            $skillidprof = session('skillidprof');
            session()->forget('skillidprof');
            if (is_array($skillidprof) && count($skillidprof) > 0) {
                $profarr1 = [];
                $profarr2 = [];
                $profarr3 = [];
                $profarr4 = [];
                $profarr5 = [];

                //$skillidprof should look like (#####<tab>#,#####<tab>#,......) tab is writen for readability only # = (a number from 0-9)
                foreach ($skillidprof as $profsplit) {
                    $resultarr = explode('--', $profsplit);
                    var_dump(json_encode($resultarr));
                    switch ($resultarr[1]) {
                        case '1':
                            array_push($profarr1, ['SkillID' => $resultarr[0]]);
                            break;
                        case '2':
                            array_push($profarr2, ['SkillID' => $resultarr[0]]);
                            break;
                        case '3':
                            array_push($profarr3, ['SkillID' => $resultarr[0]]);
                            break;
                        case '4':
                            array_push($profarr4, ['SkillID' => $resultarr[0]]);
                            break;
                        case '5':
                            array_push($profarr5, ['SkillID' => $resultarr[0]]);
                            break;
                        default:
                            break;
                    }
                }
                $allarrs = [$profarr1, $profarr2, $profarr3, $profarr4, $profarr5];
                if (is_array($ids)) {
                    foreach ($ids as $id) {
                        $count = 1;
                        foreach ($allarrs as $profarr) {
                            if (count($profarr) > 0) {

                                $delpayload = array('AgentID' => $id, 'Skills' => $profarr);
                                $this->curlcalls($delurl, "POST", $delpayload);
                                $payload = array(
                                    'AgentID' => $id, 'Skills' => $profarr, 'Proficiency' => $count
                                );
                                $responce = $this->curlcalls($url, "POST", $payload);
                            }
                            $count++;
                        }
                    }
                } else {
                    $count = 1;
                    foreach ($allarrs as $profarr) {
                        if (count($profarr) > 0) {


                            $delpayload = array('AgentID' => $ids, 'Skills' => $profarr);
                            $this->curlcalls($delurl, "POST", $delpayload);
                            $payload = array(
                                'AgentID' => $ids, 'Skills' => $profarr, 'Proficiency' => $count
                            );
                            $responce = $this->curlcalls($url, "POST", $payload);
                        }
                        $count++;
                    }
                }

                if (!$responce == true) {
                    return "ERROR";
                }
            } else {


                foreach ($request['sarr'] as $skillId) {
                    array_push($arr, ['SkillID' => $skillId]);
                }
                if (is_array($ids)) {
                    foreach ($ids as $id) {


                        // $payload = array('AgentID' => $id, 'Skills' => $profarr, 'Proficiency' => $count);
                        $delpayload = array('AgentID' => $id, 'Skills' => $arr);
                        $this->curlcalls($delurl, "POST", $delpayload);

                        $payload = array(
                            'AgentID' => $id, 'Skills' => $arr, 'Proficiency' => $request['prof']
                        );
                        $responce = $this->curlcalls($url, "POST", $payload);
                    }
                } else {

                    $delpayload = array('AgentID' => $ids, 'Skills' => $arr);
                    $this->curlcalls($delurl, "POST", $delpayload);

                    $payload = array(
                        'AgentID' => $ids, 'Skills' => $arr, 'Proficiency' => $request['prof']
                    );
                    $responce = $this->curlcalls($url, "POST", $payload);
                }

                if (!$responce == true) {
                    return "ERROR";
                }
            }
        } else {

            session()->forget('currentagentid');
            session(['currentagentid' => $request['id']]);
            return json_encode(session('skills'));
        }
    }
    function setSkillProfs(Request $request)
    {
        if (is_array($request['skillidprof'])) {
            var_dump(session('skillidprof'));
            var_dump($request['skillidprof']);

            session()->forget('skillidprof');
            session(['skillidprof' => $request['skillidprof']]);
        }
        else{
            $rid = explode('--',$request['skillidprof']);
            var_dump($rid);
            $found = false;
            $arr = session('skillidprof');
            foreach($arr as $key=>$ids){
                var_dump(array('var1'=>$arr));
                $resultarr = explode('--', $ids);
                if($resultarr[0] == $rid[0]){
                    $arr[$key] = $resultarr[0] ."--". $rid[1];
                    $found = true;
                }
                var_dump(array('var2'=>$arr));
            }
            if(!$found){
            array_push($arr,$request['skillidprof']);
            }

            var_dump(array('arrcomplete'=>$arr));
            session(['skillidprof' => $arr]);
        }

    }
    function getSkills(Request $request)
    {
        session()->forget('currentagentid');
        //var_dump($request['agentids']);
        if ($request['agentids'] == null || (is_array($request['agentids']) && count($request['agentids']) == 0)) {
            header('HTTP/1.1 500 Internal Server Booboo');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(array('message' => 'ERROR', 'code' => 500)));
        } else if ($request['agentids'] == null || $request['agentids'] == "") {
            header('HTTP/1.1 500 Internal Server Booboo');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(array('message' => 'ERROR', 'code' => 500)));
        } else {
            session(['currentagentid' => $request['agentids']]);
            return json_encode(session('skills'));
        }
    }
    function delSkill(Request $request)
    {
        session()->forget('skillidprof');
        if ($request->isMethod("post")) {
            $url = 'https://www.tmsliveonline.com/DataService/DataService.svc/RemoveAgentSkills';
            $skillid = $request['sid'];
            $agentid = $request['agentid'];

            if ($skillid == null) {
                return "popwindow";
            }
            if (is_array($skillid)) {

                $agentids = session('currentagentid');
                $arr = [];
                foreach ($skillid as $skillId) {
                    array_push($arr, ['SkillID' => $skillId]);
                }
                if (is_array($agentids)) {
                    foreach ($agentids as $agentId) {
                        $payload = array('AgentID' => $agentId, 'Skills' => $arr);
                        $responce = json_encode($this->curlcalls($url, "post", $payload));
                    }
                    return $responce;
                } else {
                    $payload = array('AgentID' => $agentids, 'Skills' => $arr);
                    $responce = json_encode($this->curlcalls($url, "post", $payload));
                    return $responce;
                }
            } else {
                $payload = array('AgentID' => $agentid, 'Skills' => array(['SkillID' => $skillid]));
                // return json_encode($payload)
                $responce = json_encode($this->curlcalls($url, "post", $payload));
                return $responce;
            }
        } else {
            return "false";
        }
    }
}
