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
<div class="row h-25 justify-content-center align-items-center">
    <br>
    <table>
        <tbody>
            <tr>
                <th>
                    <label>Pick a Skill or Skills</label>
                </th>
                <th><label>Select Proficency</label></th>
            <tr>
                <td><select id="skillselect" class="selectpicker" multiple data-live-search="true">
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
            <tr>
                <td>
                    <button onclick="addSkill()">Submit</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
@section('footer')
<script type="text/javascript" src="..\resources\js\agentskill.js"></script>
<!-- <iframe hidden onload="$('#skillselect').selectpicker()"></iframe> -->
@endsection
