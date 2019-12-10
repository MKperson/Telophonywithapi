@extends('layouts.app')
@section('title', 'Agent Skills')
<style>
    .navbar {

        display: none !important;

    }
</style>
@section('sidebar')
@parent
@endsection
@section('content')
<div class="row h-25 justify-content-center">
    {{-- <div> --}}
    <br>

    <table>
        <tr>
            <th><label>Populate</label></th>
        </tr>
        <tbody>
            <tr>
                <td><textarea id="massel" style="width: 250px; height: 250px;"
                        placeholder="Paste Skills Here. Must be in correct form.    Press [alt] + [0][9] to insert tab if needed. List must be consistent"></textarea>
                </td>
            </tr>
            <tr>
                <td><button id="masselbutt" onclick="massel('skillselect')">Select</button></td>
            </tr>
        </tbody>
    </table>
    <table>
        <tr>
            <th>
                <label>Pick a Skill or Skills</label>
            </th>
            <th id="proflable"><label>Select Proficency</label></th>
        </tr>
        <tbody>
            <tr>

                <td class="top">
                    <select id="skillselect" class="selectpicker" multiple data-live-search="true"
                        data-actions-box="true" onchange="$('#subbutt').prop('disabled',false),changed('skillselect')">
                        {{-- <option>skill1</option>
                        <option>skill2</option>
                        <option>skill3</option> --}}
                    </select>
                </td>
                <td>
                    <select class="selectpicker" id="profselect">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3" selected>3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </td>
            </tr>
        </tbody>
    </table>
    <table id="recTable" style="width: 25%;background-color: white;" class="table display table-striped">
        <thead>
            <tr>
                <th id="skillorname">Skill Name</th>
                <th id="proforbl">Prof.</th>

            </tr>
        </thead>
        <tbody id=skillRec>

        </tbody>
    </table>
</div>


</div>
@endsection
@section('footer')
<input type="hidden" id="subfunc">
{{-- <iframe hidden onload="$('#mainbody').attr('onblur','self.focus()');"></iframe> --}}
{{-- <iframe hidden onload="self.blur();"></iframe> --}}

<script type="text/javascript" src="..\resources\js\agentskill.js"></script>
<!-- <iframe hidden onload="$('#skillselect').selectpicker()"></iframe> -->
@endsection
