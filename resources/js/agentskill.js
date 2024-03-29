function toggle() {
    //debugger;
    if (!$('#select-2').prop('checked')) { //$('#campaigns').is(':visible') &&
        $('#skillorname').html('Skill Name');
        $('#proforbl').html('Prof.');
        $('#actionorbl').html('Action');
        $('#agents').prop('multiple', false);
        $('#agents').attr('onchange', "load()");
        $('#agents').removeAttr('data-actions-box');
        $('#agents').val([]);
        $('#skillRec').empty();
        $('#agents').selectpicker('destroy');
        $('#agents').selectpicker();

        $('#massel').prop('hidden', true);
        $('#masselbutt').prop('hidden', true);
        $('#agents').prop('disabled', false);
        $('#agents').selectpicker('refresh');
    }
    else {
        $('#skillorname').html('Name');
        $('#proforbl').html('');
        $('#actionorbl').html('');

        $('#agents').prop('multiple', true);
        $('#agents').attr('onchange', "changed('agents')");
        //$('#agents').removeAttr('onchange');
        $('#agents').attr('data-actions-box', true);

        $('#agents').val([]);
        $('#skillRec').empty();
        $('#agents').selectpicker('destroy');

        //$('#agents').selectpicker();
        $('#agents').selectpicker({
            selectAllText: 'Select All',
            deselectAllText: 'Deselect All'
        });
        $('#skillRec').append("<tr><td><button onclick='addSkill()'>Add/Modify Skills</button></td><td><button onclick='btndelete()'>Delete Skills</button></td><td></td></tr>");
        $('#massel').prop('hidden', false);
        $('#masselbutt').prop('hidden', false);
    }
}
function changed(id, prof = null) {
    var num = $('#' + id).val();
    $('#skillRec').empty();
    for (i = 0; i < num.length; i++) {
        let text = $("#" + id + " option[value='" + num[i] + "']").text();
        if (prof != null) {


        } else {
            $('#skillRec').append("<tr><td>" + text + "</td><td></td><td></td></tr>");
        }
        //console.log(text);
    }
    if (id == "agents") {
        $('#skillRec').append("<tr><td><button onclick='addSkill()'>Add/Modify Skills</button></td><td><button onclick='btndelete()'>Delete Skills</button></td><td></td></tr>");
    } else if (id == 'skillselect') {
        $('#skillRec').append("<tr><td><button id='subbutt' onclick=\"$('#subfunc').click()\">Submit</button></td><td></td><td></td></tr>");
    }

}
function massel(id) {

    var text = $('#massel').val();
    var names = text.split('\n');
    var ids = [];
    var skillidprof = [];
    var regexp = new RegExp("([A-Za-z0-9]+),\\s+([A-Za-z0-9]+)");

    if (id == 'skillselect') {
        for (i = 0; i < names.length; i++) {
            var test = names[i].split('\t')

            if (test.length == 2) {

                if (!test[0] == "" && !test[0] == " " && !test[1] == "" && !test[1] == " ") {
                    var v = $("#" + id + " option:contains(" + test[0] + ")").val();
                    if (v != null) {
                        ids.push(v);
                        skillidprof.push(v + '--' + test[1])
                    }
                }
                //create session var to handle multipul skill requireing different profs
            }
            else if (test.length == 1) {

                if (!test[0] == "" || !test[0] == " ") {
                    var v = $("#" + id + " option:contains(" + test[0] + ")").val();
                    if (v != null) {
                        ids.push(v);
                    }
                }
            }
        }
    } else {
        $('#skillRec').empty();
        for (i = 0; i < names.length; i++) {
            //alert(regexp.test(names[i]));
            if (!names[i] == "" && !names[i] == " " && regexp.test(names[i])) {
                var v = $("#" + id + " option:contains(" + names[i] + ")").val();
                if (v != null) {
                    $('#skillRec').append("<tr><td>" + names[i] + "</td><td></td><td></td></tr>")
                    ids.push(v);
                }
            }
        }
        $('#skillRec').append("<tr><td><button onclick='addSkill()'>Add/Modify Skills</button></td><td><button onclick='btndelete()'>Delete Skills</button></td><td></td></tr>");
    }
    if (ids.length == names.length) {
        $('#' + id).selectpicker('val', ids);
        $('#' + id).prop('disabled', true);
        $('#' + id).selectpicker('refresh');
        if (id == 'skillselect') {
            $('#subbutt').prop('disabled', false);
            if (ids.length == skillidprof.length) {

                $('#skillRec').empty();

                for (i = 0; i < names.length; i++) {
                    let split = names[i].split('\t')
                    $('#skillRec').append("<tr><td>" + split[0] + "</td><td>" + split[1] + "</td><td></td></tr>");
                }
                $('#skillRec').append("<tr><td><button id='subbutt' onclick=\"$('#subfunc').click()\">Submit</button></td><td></td><td></td></tr>");

                $('#profselect').selectpicker('destroy');
                $('#profselect').prop('hidden', true);
                $('#proflable').prop('hidden', true);
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },

                    url: "setskillprofs",
                    data: {
                        skillidprof: skillidprof,
                    },
                    method: "post",
                    dataType: 'json',
                    success: function (data) {
                        alert(data);
                    }
                });
            }
            else {
                if (skillidprof != 0) {
                    alert("Data must be consistent to set individual proficiency per skill\n(Note: If you are REMOVING skills you do not need proficiencys)")
                    changed(id);
                } else {
                    $('#skillRec').empty();
                    for (i = 0; i < names.length; i++) {
                        //let split = names[i].split('\t')
                        $('#skillRec').append("<tr><td>" + names[i] + "</td><td></td><td></td></tr>");
                    }
                    $('#skillRec').append("<tr><td><button id='subbutt' onclick=\"$('#subfunc').click()\">Submit</button></td><td></td><td></td></tr>");
                }
            }
        }

    } else {
        alert("Error in list please review and fix.\n(Note: Look for improper cApitLIsAtioN and\nalso check spaces at end of document and lines)\n(Note 2: Data must be consistent)");
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

            sagents = sorthelp(agents, ['lastName'], ['ASC']);

            //$('#agents').append('<option value=\'\'></option>');

            for (var i = 0; i < sagents.length; i++) {
                $('#agents').append('<option value=' + '\'' + sagents[i]['agentId'] + '\'' + '>' + sagents[i]['lastName'] + ', ' + sagents[i]['firstName'] + '</option>')
            };
            $('#agents').val([]);
            $('#agents').selectpicker();


            $('#loader').prop('style', 'display:none');
            //$('#main').prop('style', 'display:block');
        },
        error: function (message) {
            console.log(message);
        }
    });
}

function load() {

    $('#loader').prop('style', 'display:block');
    var val = $('#agents').val();
    var bool = Array.isArray(val);
    if (val != "" && Array.isArray(val) != true) {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            url: "getAgentSkills",
            data: {
                id: val,
            },
            method: "post",
            dataType: 'json',
            success: function (data) {

                data = sorthelp(data, ['SkillName'], ['ASC']);

                //console.log(data);

                //$('#recTable').prop('hidden', false);

                $('#skillRec').empty();

                for (var i = 0; i < data.length; i++) {
                    var options = '';
                    var prof = data[i]['Proficiency']
                    if (prof > 5) {
                        for (var x = 1; x <= prof; x++) {
                            options += '<option value="' + x + '"> ' + x + '</option>'
                        }
                    } else {
                        for (var x = 1; x < 6; x++) {
                            options += '<option value="' + x + '"> ' + x + '</option>'
                        }
                    }

                    $('#skillRec').append('<tr>' +
                        '<td>' +
                        data[i]['SkillName'] +
                        '</td>' +
                        '<td>' +
                        '<select id = "' + data[i]['SkillID'] + '" class="form-control" onchange="modprof(id)";>' +
                        options +
                        '</select>' +
                        '</td>' +
                        '<td style="text-align:center;">' +
                        '<a class="btn btn-sm btn-danger" onclick="btndelete(' + data[i]['SkillID'] + ')";><i class="fa fa-trash" aria-hidden="true" style="color:#fff;" ></i></a>' +
                        '</td>' +
                        '</tr>');

                    //$('#selected_prof'+i+' option[value=' + prof +']').attr('selected','selected');
                    $('#' + data[i]['SkillID']).val(prof);
                }
                $('#skillRec').append("<tr><td><button onclick='addSkill()'>Add/Modify Skills</button></td><td><button onclick='btndelete()'>Bulk Delete</button></td><td></td></tr>");

                //$('#radiobutt').prop('hidden', false);

                $('#sessiontest').empty();
                //$('#sessiontest').append("<div id='sessiontest' class=\"row\"><?php if (!empty(session('agents'))) {echo session('agents');} ?></div>");
                $('#loader').prop('style', 'display:none');

            },
            error: function (message) {
                debugger;
                console.log(message);
                $('#loader').prop('style', 'display:none');
            },

        });
    } else {
        //$('#radiobutt').prop('hidden', true);
        $('#skillRec').empty();
        $('#loader').prop('style', 'display:none');
        if (Array.isArray(val)) {
            $('#skillRec').append("<tr><td><button onclick='addSkill()'>Add/Modify Skills</button></td><td><button onclick='btndelete()'>Delete Skills</button></td><td></td></tr>" +
                "<tr><td>Updated " + val.length + " Agent/s </td><td></td><td></td></tr>");
        }
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

        success: function (data) {
            console.log(data);
            $('#loader').prop('style', 'display:none');
            load();
        },
        error: function (message) {
            console.log(message);
            $('#loader').prop('style', 'display:none');
        },
    })
}
function addSkill() {
    $('#loader').prop('style', 'display:block');
    var agentid = $('#agents').val();
    if (Array.isArray(agentid) && agentid.length == 0) {
        alert("Please check your list No one is selected");
        $('#loader').prop('style', 'display:none');
    }
    else if ($('#skillselect').val() == null) {

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: 'addskill',
            method: "get",
            data: {
                id: agentid,
            },
            dataType: "json",
            success: function (data) {
                //debugger;
                var sskills = sorthelp(data, ['skillName'], ['ASC']);

                let skillWindow = PopupCenter("./newskill", "SkillWindow", 1200, 500);

                skillWindow.focus();

                skillWindow.onload = function () {
                    skillWindow.$('#subfunc').attr('onclick', 'addSkill()');
                    //let html = `<div style="font-size:30px">Welcome!</div>`;
                    //skillWindow.document.body.insertAdjacentHTML("afterbegin",html);
                    skillWindow.$('#skillselect').empty()
                    // debugger;
                    for (var i = 0; i < sskills.length; i++) {
                        skillWindow.$('#skillselect').append("<option value=\"" + sskills[i]['skillId'] + "\">" + sskills[i]['skillName'] + "</option>")
                    }
                }
                console.log(data);
                // console.log(JSON.stringify(data));
                $('#loader').prop('style', 'display:none');
                // skillWindow.onclose = function(){
                //     debugger;
                //     load();
                // }
            },
            error: function (message) {
                console.log(message);
                $('#loader').prop('style', 'display:none');
            },

        })
    } else {
        sarr = $('#skillselect').val();
        prof = $('#profselect').val();
        if (sarr.length > 0) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: 'addskill',
                method: "post",
                data: {
                    sarr, prof,
                },
                // dataType: "json",
                success: function (data) {
                    //alert(data);
                    window.opener.load();
                    window.close();
                },
                error: function (message) {
                    debugger;
                    console.log(message);
                    alert(message);

                },

            });
        }
        else {
            alert('Please Select at least 1 Skill');
            $('#loader').prop('style', 'display:none');
        }

    }
}
function PopupCenter(url, title, w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var systemZoom = width / window.screen.availWidth;
    var left = (width - w) / 2 / systemZoom + dualScreenLeft
    var top = (height - h) / 2 / systemZoom + dualScreenTop
    return window.open(url, title, 'scrollbars=yes, width=' + w / systemZoom + ', height=' + h / systemZoom + ', top=' + top + ', left=' + left);
}
function sorthelp(ray, columns, ascdesc) {

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
    var sorted = helper.arr.multisort(ray, columns, ascdesc);

    return sorted;

}
function btndelete(id = null) {
    aid = $("#agents").val();
    if(aid == null || aid.length == 0){
        return alert("Is anyone Selected?");

    }
    $('#loader').prop('style', 'display:block');
    //var val = $('#skillselect').val();

    if ($('#skillselect').val() != null) {
        id = $('#skillselect').val();
    }
    if (id == null) {
        // aid = $("#agents").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "getSkills",
            data: {
                agentids: aid,
            },
            method: "get",
            dataType: "json",
            success: function (data) {
                $('#loader').prop('style', 'display:none');
                console.log(data);

                var sskills = sorthelp(data, ['skillName'], ['ASC']);

                let skillWindow = PopupCenter("./newskill", "SkillWindow", 1200, 500);

                skillWindow.focus();

                skillWindow.onload = function () {

                    skillWindow.$('#subfunc').attr('onclick', 'btndelete()');
                    skillWindow.$('#profselect').selectpicker('destroy');
                    skillWindow.$('#profselect').prop('hidden', true);
                    skillWindow.$('#proflable').prop('hidden', true);
                    //let html = `<div style="font-size:30px">Welcome!</div>`;
                    //skillWindow.document.body.insertAdjacentHTML("afterbegin",html);
                    skillWindow.$('#skillselect').empty()
                    // debugger;
                    for (var i = 0; i < sskills.length; i++) {
                        skillWindow.$('#skillselect').append("<option value=\"" + sskills[i]['skillId'] + "\">" + sskills[i]['skillName'] + "</option>")
                    }
                }

            },
            error: function (message) {
                console.log(message);
                alert("Error is someone 'SELECTED'? ")
                $('#loader').prop('style', 'display:none');
            },

        });
    } else {
        // aid = $("#agents").val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "delskill",
            data: {
                sid: id,
                agentid: aid,
            },
            method: "post",
            success: function (data) {
                console.log(data);
                if (Array.isArray(id)) {
                    window.opener.load();
                    window.close();
                }
                else {
                    load();
                    // $('#loader').prop('style', 'display:none');
                }
            },
            error: function (message) {
                console.log(message);
                alert("Error is something 'SELECTED'? ")
                $('#loader').prop('style', 'display:none');
            },

        });
        //alert("delete: " + id);
    }
}

