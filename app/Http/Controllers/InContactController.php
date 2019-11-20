<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

    public function getAgents(Request $request)
    {
        $names = $request->get('/agents');

        //
    }
}
