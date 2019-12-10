@extends('layouts.app')
@section('title', 'Agent Skills')
@section('sidebar')
@parent
<div class="jumbotron text-center">
    <H1>Agent Skills Manager</H1>
    <h5 style="color: red">Currently under development not all functions working<h5>
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
                        onchange="load();$('#reop').prop('hidden',false);" id="agents" data-width="100%"
                        data-actions-box="true" data-selected-text-format="count > 3"
                        data-count-selected-text="{0} People Selected">
                        <option> {{ $message }}</option>
                    </select></td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center; padding: 10;" id="radiobutt"><input name="select"
                        id="select-1" onchange="toggle()" type="radio" value="1" checked />Single Person
                    <input name="select" type="radio" id="select-2" value="2" onchange="toggle()" />Bulk </td>
            </tr>
            <tr>
                <td><textarea class="form-control" id="massel" style="width: 100%; height: 250px;" hidden
                        placeholder="Paste Names Here. Must be in form &quot;Last, First&quot; please note that the space after the COMMA is REQUIRED"></textarea>
                </td>
            </tr>
            <tr>
                <td><button class="btn btn-sm btn-primary" id="masselbutt" hidden onclick="massel('agents')">Select</button></td>
            </tr>
        </tbody>
    </table>
    <button id="reop" class="btn btn-sm btn-primary" onclick="$('#skillrecmodal').modal('show');"
        style="max-height:50px; margin: auto 0px auto 10px;" hidden>Reopen view</button>
    <div id="skillrecmodal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Skills</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> --}}
                </div>
                <table id="recTable" style="background-color: white;" class="table display table-striped">
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
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addSkill()">Add/Modify</button>
                    <button type="button" class="btn btn-danger" onclick="btndelete()">Bulk Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>



    <div id="addskillmodal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" style="overflow-y: scroll">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="addskillhtml" style="padding: 25px;">
                <div class="modal-header">
                    <h5 class="modal-title">Skills</h5>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="addSkill()">Add/Modify</button>
                    <button type="button" class="btn btn-danger" onclick="btndelete()">Bulk Delete</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>


</div>


@endsection
@section('footer')
<script type="text/javascript" src="..\resources\js\agentskill.js"></script>
<script>
    $(document).ready(function(){
    $('#addskillmodal').on('hidden.bs.modal', function () {
        // Load up a new modal...
        $('#skillrecmodal').modal('show');
        $('#skillselect').remove();
    })
    $('#skillselect').remove();
    popagents();
})

</script>
{{-- <iframe onload="" hidden></iframe> --}}
@endsection
