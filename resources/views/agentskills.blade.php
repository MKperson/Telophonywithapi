@extends('layouts.app')

@section('title', 'Agent Skills')

@section('sidebar')
@parent
<div class="jumbotron text-center">
    <H1>Agent Skills Manager</H1>
</div>
@endsection

@section('content')
<div style="display: flex; justify-content: center;">
    <table>

        <tbody>
            <tr style="height: 19px;">
                <td style="width: 100%; height: 19px;">Please Choose an employee</td>
            </tr>
            <tr style="height: 19px;">
                <td style="width: 100%; height: 19px;"><select onchange="load()" id="agents" style="width: 100%;">
                        <option>test</option>
                    </select></td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center; padding: 10;"><input name="select" id="select-1" onchange="toggle()" type="radio" value="1" checked />All Skills
                    <input name="select" type="radio" id="select-2" value="2" onchange="toggle()" />Campaign </td>
            </tr>
            <tr>
                <td><select id="campaigns" style="width: 100%;" hidden>
                        <option></option>
                    </select></td>
            </tr>
        </tbody>

    </table>
    <textarea id="agentList" readonly rows="25" cols="25" style="resize: none;"></textarea>
</div>



@endsection

@section('footer')
<script type="text/javascript">
    function toggle() {
        //debugger;
        if ($('#campaigns').is(':visible') && !$('#select-2').prop('checked')) {
            $('#campaigns').prop('hidden', true);
        } else {
            $('#campaigns').prop('hidden', false);
        }
    }

    function popagents() {

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            /* the route pointing to the post function */
            url: 'https://www.tmsliveonline.com/DataService/DataService.svc/GetSkillAssignments',
            type: 'get',
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function(data) {
                console.log(data['GetSkillAssignmentsResult']['Assignments']['Agents']);
                var agents = data['GetSkillAssignmentsResult']['Assignments']['Agents'];
                $('#agents').empty();
                
                for(var i = 0; i < agents.length; i++){
                    $('#agents').append('<option value="' + agents[i]['AgentID'] + '">' + agents[i]['AgentName'] + '</option>')
                } 
            }
        });
    }
</script>

<iframe onload="popagents()" hidden></iframe>

@endsection