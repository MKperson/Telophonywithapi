@extends('layouts.app')
@section('title', 'Poll Skills')
@section('sidebar')
@parent
<div class="jumbotron text-center">
    <H1>Poll Skills</H1>
    {{-- <h5 style="color: red">Currently under development not all functions working<h5> --}}
</div>
@endsection
@section('content')

<div style="display: flex; justify-content: center;">
    <table class="table-bordered" style="width: 25%;">
        <tbody>
            <tr style="height: 19px;">
                <td class="col-form-label" style="width: 100%; height: 19px;">Please Choose a Skill</td>
            </tr>
            <tr style="height: 19px;">
                <td style="width: 100%; height: 19px;"><select class="form-control" data-live-search="true"
                        onchange="getAgentsbySkill();$('#reop').prop('hidden',false);" id="skills" data-width="100%"
                        data-actions-box="true" data-selected-text-format="count > 3"
                        data-count-selected-text="{0} Skills Selected">
                        <option></option>
                    </select></td>
            </tr>
            <tr>
                <td>
                    <button id="reop" class="btn btn-sm btn-primary" onclick="$('#agentmodal').modal('show');"
        style="max-height:50px; margin: auto 0px auto 10px;" hidden>Reopen View</button>
                </td>
            </tr>
        </tbody>
    </table>





<div id="agentmodal" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" style="overflow-y: scroll">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="agenthtml" style="padding: 25px;">
                <div class="modal-header">
                    <h5 class="modal-title">Agents</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
@section('footer')
<script type="text/javascript" src="..\resources\js\pollagents.js"></script>
<script type="text/javascript">
    $(document).ready(function(){getskills(); $('#loader').prop('style', 'display:block');});
</script>
@endsection
