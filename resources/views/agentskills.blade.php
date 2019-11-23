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
                <td style="width: 100%; height: 19px;"><select class="selectpicker" data-show-subtext="true" data-live-search="true" onchange="load()" id="agents" >
                        <option>Please Wait</option>
                    </select></td>
            </tr>
            <tr>
                <td style="width: 100%; text-align: center; padding: 10;" id="radiobutt" hidden><input name="select" id="select-1" onchange="toggle()" type="radio" value="1" checked />All Skills
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
            </tr>-->
        </tbody>
    </table>
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
        $('#loader').prop('style', 'display:block');
        $('#main').prop('style', 'display:none');

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var sagents;
        $.ajax({
            /* the route pointing to the post function */
            url: 'https://www.tmsliveonline.com/DataService/DataService.svc/GetSkillAssignments',
            type: 'get',
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function(data) {
                //console.log(data['GetSkillAssignmentsResult']['Assignments']['Agents']);
                var agents = data['GetSkillAssignmentsResult']['Assignments']['Agents'];
                $('#agents').empty();

                sagents = sorthelp(agents);
                var temp = {};
                for (var i = 0; i < sagents.length; i++) {

                    temp[i] = sagents[i]
                };

                $('#agents').append('<option value=\'\'></option>');

                for (var i = 0; i < sagents.length; i++) {
                    $('#agents').append('<option value=' + '\'' + sagents[i]['AgentID'] + '\'' + '>' + sagents[i]['AgentName'] + '</option>')
                };
               

            }
        });
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': CSRF_TOKEN,
            },
            url: 'updateDB',
            type: 'get',
            success: function(data) {
                $('#loader').prop('style', 'display:none');
                $('#main').prop('style', 'display:block');
            }

        });

    }


    function sorthelp(ray) {


        if (typeof helper == 'undefined') {
            var helper = {};
        }

        helper.arr = {
            /**
             * Function to sort multidimensional array
             * 
             * <a href="/param">@param</a> {array} [arr] Source array
             * <a href="/param">@param</a> {array} [columns] List of columns to sort
             * <a href="/param">@param</a> {array} [order_by] List of directions (ASC, DESC)
             * @returns {array}
             */
            multisort: function(arr, columns, order_by) {
                if (typeof columns == 'undefined') {
                    columns = []
                    for (x = 0; x < arr[0].length; x++) {
                        columns.push(x);
                    }
                }

                if (typeof order_by == 'undefined') {
                    order_by = []
                    for (x = 0; x < arr[0].length; x++) {
                        order_by.push('ASC');
                    }
                }

                function multisort_recursive(a, b, columns, order_by, index) {
                    var direction = order_by[index] == 'DESC' ? 1 : 0;

                    var is_numeric = !isNaN(+a[columns[index]] - +b[columns[index]]);


                    var x = is_numeric ? +a[columns[index]] : a[columns[index]].toLowerCase();
                    var y = is_numeric ? +b[columns[index]] : b[columns[index]].toLowerCase();



                    if (x < y) {
                        return direction == 0 ? -1 : 1;
                    }

                    if (x == y) {
                        return columns.length - 1 > index ? multisort_recursive(a, b, columns, order_by, index + 1) : 0;
                    }

                    return direction == 0 ? 1 : -1;
                }

                return arr.sort(function(a, b) {
                    return multisort_recursive(a, b, columns, order_by, 0);
                });
            }
        };
        var sorted = helper.arr.multisort(ray, ['AgentName'], ['ASC']);

        return sorted;

    }

    function load() {
        var val = $('#agents').val();
        if (val != "") {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },

                url: "getAgent",
                data: {
                    id: val,
                },
                method: "post",
                dataType: 'json',
                success: function(data) {
                    console.log(data);

                    //$('#recTable').prop('hidden', false);

                    $('#skillRec').empty();

                    for (var i = 0; i < data['skills'].length; i++) {


                        var prof = data['skills'][i]['ProficiencyValue']

                        $('#skillRec').append(' <tr><td>' + data['skills'][i]['SkillName'] + '</td> <td><select id = "selected_prof' + i + '" class="form-control"><option value="1"> 1 </option><option value="2">2</option> <option value="3"> 3 </option><option value="4"> 4 </option> <option value="5"> 5 </option></select></td> </tr>');

                        //$('#selected_prof'+i+' option[value=' + prof +']').attr('selected','selected');
                        $('#selected_prof' + i).val(prof);
                    }

                    $('#radiobutt').prop('hidden', false);

                },

            });
        } else {
            $('#radiobutt').prop('hidden', true);
            $('#skillRec').empty();
        }
    }
</script>

<iframe onload="popagents()" hidden></iframe>

@endsection