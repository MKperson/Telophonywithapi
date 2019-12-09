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
                        onchange="load()" id="agents" data-width="auto" data-actions-box="true" data-selected-text-format="count > 3" data-count-selected-text="{0} People Selected">
                        <option> {{ $message }}</option>
                    </select></td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center; padding: 10;" id="radiobutt"><input name="select"
                        id="select-1" onchange="toggle()" type="radio" value="1" checked />Single Person
                    <input name="select" type="radio" id="select-2" value="2" onchange="toggle()" />Bulk </td>
            </tr>
            <tr>
                <td><textarea id="massel" style="width: 100%; height: 250px;" hidden placeholder="Paste Names Here. Must be in form &quot;Last, First&quot; please note that the space after the COMMA is REQUIRED"></textarea></td>
            </tr>
            <tr>
                <td><button id="masselbutt" hidden onclick="massel('agents')">Select</button></td>
            </tr>
        </tbody>
    </table>
    <table id="recTable" style="width: 25%;background-color: white;" class="table display table-striped">
        <thead>
            <tr>
                <th id="skillorname">Skill Name</th>
                <th id="proforbl">Prof.</th>
                <th id="actionorbl">Action</th>
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
