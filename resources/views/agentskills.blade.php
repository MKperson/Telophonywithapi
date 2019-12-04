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
    <table class="table-bordered" style="width: 25%;">
        <tbody>
            <tr style="height: 19px;">
                <td class="col-form-label" style="width: 100%; height: 19px;">Please Choose an employee</td>
            </tr>
            <tr style="height: 19px;">
                <td style="width: 100%; height: 19px;"><select class="form-control" data-live-search="true"
                        onchange="load()" id="agents">
                        <option> {{ $message }}</option>
                    </select></td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center; padding: 10;" id="radiobutt" hidden><input name="select"
                        id="select-1" onchange="toggle()" type="radio" value="1" checked />All Skills
                    <input name="select" type="radio" id="select-2" value="2" onchange="toggle()" />Campaign </td>
            </tr>
            <tr>
                <td><select id="campaigns" style="width: 100%;" hidden>
                        <option>NOT IMPLIMENTED YET</option>
                    </select></td>
            </tr>
        </tbody>
    </table>
    <table id="recTable" style="width: 25%;background-color: white;" class="table display table-striped">
        <thead>
            <tr>
                <th>Skill Name</th>
                <th>Prof.</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id=skillRec>
            <!-- Do your loop for <tr> records here -->
            <!--<tr>
                <td>
                    Skill Name Goes Here
                </td>
                <td>
                    <select id="yourIDtoTargetWithValueLater" class="form-control" readonly>
                        <option value="0">0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </td>
                <td>
                    <a class="btn btn-sm btn-danger" data-id="youridgoeshere">Delete Icon</a>
                </td>
            </tr>-->
        </tbody>
    </table>
</div>

@endsection
@section('footer')
<script type="text/javascript" src="..\resources\js\agentskill.js"></script>
<iframe onload="popagents()" hidden></iframe>
@endsection
