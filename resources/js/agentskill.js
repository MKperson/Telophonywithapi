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
    //$('#main').prop('style', 'display:none');
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    var sagents;
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
        },
        url: 'updateDB',
        type: 'get',

        success: function (data) {

            var agents = JSON.parse(data);
            $('#agents').empty();

            sagents = sorthelp(agents);
            // var temp = {};
            // for (var i = 0; i < sagents.length; i++) {

            //     temp[i] = sagents[i]
            // };

            $('#agents').append('<option value=\'\'></option>');

            for (var i = 0; i < sagents.length; i++) {
                $('#agents').append('<option value=' + '\'' + sagents[i]['AgentID'] + '\'' + '>' + sagents[i]['AgentName'] + '</option>')
            };
            $('#agents').selectpicker();

            $('#loader').prop('style', 'display:none');
            //$('#main').prop('style', 'display:block');
        },
        error: function (message) {
            console.log(message);
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
        multisort: function (arr, columns, order_by) {
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

            return arr.sort(function (a, b) {
                return multisort_recursive(a, b, columns, order_by, 0);
            });
        }
    };
    var sorted = helper.arr.multisort(ray, ['AgentName'], ['ASC']);

    return sorted;

}

function load() {


    //$('#loader').prop('style', 'display:block');
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
            success: function (data) {

                //console.log(data);

                //$('#recTable').prop('hidden', false);

                $('#skillRec').empty();

                for (var i = 0; i < data['Skills'].length; i++) {


                    var prof = data['Skills'][i]['ProficiencyValue']

                    $('#skillRec').append('<tr><td>' + data['Skills'][i]['SkillName'] + '</td> <td><select id = "' + data['Skills'][i]['SkillID'] + '" class="form-control" onchange="modprof(id)";  ><option value="1"> 1 </option><option value="2"> 2 </option> <option value="3"> 3 </option><option value="4"> 4 </option> <option value="5"> 5 </option></select></td> </tr>');

                    //$('#selected_prof'+i+' option[value=' + prof +']').attr('selected','selected');
                    $('#' + data['Skills'][i]['SkillID']).val(prof);
                }
                $('#skillRec').append("<td><button onclick='addSkill()'>Add Skill</button></td>");

                $('#radiobutt').prop('hidden', false);

                $('#sessiontest').empty();
                //$('#sessiontest').append("<div id='sessiontest' class=\"row\"><?php if (!empty(session('agents'))) {echo session('agents');} ?></div>");
                $('#loader').prop('style', 'display:none');

            },
            error: function (message) {
                debugger;
                console.log(message);
            },

        });
    } else {
        $('#radiobutt').prop('hidden', true);
        $('#skillRec').empty();
    }
}

function modprof(id) {
    $('#loader').prop('style', 'display:block');

    var arr = {
        "AgentID": $('#agents').val(),
        "Skills": [
            {
                "SkillID": id
            }
        ],
        "Proficiency": $('#' + id).val()
    };

    //alert(JSON.stringify(arr));

    $.ajax({

        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: 'setAgentsProf',
        data: {
            payload: arr,
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        },
        method: "post",
        dataType: "json",
        success: function (data) {
            console.log(data);
            console.log(JSON.stringify(data));
            $('#loader').prop('style', 'display:none');
        },
        error: function (message) {

            console.log(message);
            $('#loader').prop('style', 'display:none');
        },
    })
}
function addSkill() {
    $('#loader').prop('style', 'display:block');

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: 'addskill',
        method: "get",
        dataType: "json",
        success: function (data) {
            // debugger;
            var sskills = sorthelp(data);

            let skillWindow = window.open("./newskill", "SkillWindow", "width=500,height=250");

            skillWindow.focus();

            skillWindow.onload = function(){
                //let html = `<div style="font-size:30px">Welcome!</div>`;
                //skillWindow.document.body.insertAdjacentHTML("afterbegin",html);
                skillWindow.$('#skillselect').empty()
                // debugger;
                for (var i = 0; i < sskills.length; i++){
                    skillWindow.$('#skillselect').append("<option value=\""+sskills[i]['SkillID']+"\">"+ sskills[i]['SkillName'] + "</option>")
                } 
            }
            console.log(data);
            // console.log(JSON.stringify(data));
            $('#loader').prop('style', 'display:none');
        },
        error: function (message) {
            console.log(message);
            $('#loader').prop('style', 'display:none');
        },

    })



}