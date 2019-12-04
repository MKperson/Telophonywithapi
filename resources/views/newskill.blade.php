@extends('layouts.app')
@section('title', 'Agent Skills')
@section('sidebar')
@parent
@endsection
@section('content')
<label for="skillselect">Pick a Skill or Skills to add</label>
<select id=skillselect class="selectpicker"  multiple data-live-search="true">
    <option>skill1</option>
    <option>skill2</option>
    <option>skill3</option>
</select>
<button onclick="addSkill()">Submit</button>
@endsection
@section('footer')
<script type="text/javascript" src="..\resources\js\agentskill.js"></script>
<!-- <iframe hidden onload="$('#skillselect').selectpicker()"></iframe> -->
@endsection