@extends('layouts.app')

@section('title', 'Agent Skills')

@section('sidebar')
@parent
<H1 style="text-align: center;">Agent Skills Manager</H1>

@endsection

@section('content')
<br>
<div style="display:flex; margin:0 auto; ">
<table style="width:20%; height:auto; border-color: black;">
   
    <tbody>
        <tr style="height: 19px;">
            <td style="width: 100%; height: 19px;">Please Choose an employee</td>
        </tr>
        <tr style="height: 19px;">
            <td style="width: 100%; height: 19px;"><select id="agents" style="width: 100%;">
                    <option>test</option>
                </select></td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 100%; height: 18px; text-align: center;"><input name="select" type="radio" value="1" />Option 1<input name="select" type="radio" value="2" />Option 2</td>
        </tr>
        <tr style="height: 18px;">
            <td style="width: 100%; height: 18px;">&nbsp;</td>
        </tr>
    </tbody>
   
</table>
<textarea id = "agentList" readonly rows="25" cols="25" style="resize: none;"></textarea>
</div>


@endsection