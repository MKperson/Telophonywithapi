function getskills(){
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        url: "getallskills",
        method: "get",
        datatype: "json",
        success: function (data) {
            console.log(data);
            var skills = JSON.parse(data);
            $('#skills').empty();

            sskills = sorthelp(skills, ['skillName'], ['ASC']);

            //$('#agents').append('<option value=\'\'></option>');

            for (var i = 0; i < sskills.length; i++) {
                $('#skills').append('<option value=' + '\'' + sskills[i]['skillId'] + '\'' + '>' + sskills[i]['skillName'] + '</option>')
            };
            $('#skills').val([]);
            $('#skills').selectpicker();


            $('#loader').prop('style', 'display:none');
            $('#modalloader').prop('style', 'display:none');

        },
        error: function (message) {
            alert("ERROR");
            console.log(message)
        },
        complete: function () {
            // $('#reop').prop('hidden', false);
        },
    });
}


function getAgentsbySkill(){
    $('#loader').prop('style', 'display:block');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        url: "getAgentsbySkill",
        data:{'skillid': $('#skills').val(),},
        method: "post",
        datatype: "json",
        success: function (data) {
            console.log(data);
            var skills = JSON.parse(data);
            if(skills.length != 0){
                sagents = sorthelp(skills[0]['Agents'], ['AgentName'], ['ASC']);

                //console.log(data);

                //$('#recTable').prop('hidden', false);
                $('#agenthtml').empty();

                $('#agenthtml').append(
                '<div class="modal-header">'+
                '<h5 class="modal-title">Agents</h5>' +
                '<div">Count: '+ sagents.length +'</div>'+
                '</div>');

                $('#agenthtml').append(
                '<table id="recTable" style="background-color: white;" class="table display table-striped">'+
                '<thead>'+
                '<tr>'+
                '<th id="skillorname">Agent Name</th>'+
                '</tr>'+
                '</thead>'+
                '<tbody id=agentRec>'+
                '</tbody>'+
                '</table>');

                $('#agenthtml').append(
                '<div class="modal-footer">'+
                '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>'+
                '</div>');


                $('#agentRec').empty();

                for (var i = 0; i < sagents.length; i++) {
                    $('#agentRec').append(
                        '<tr>'+
                        '<td>'+
                        sagents[i]['AgentName']+
                        '</td>'+
                        '</tr>');
                    }
                // $('#sessiontest').empty();
                $('#loader').prop('style', 'display:none');
                $('#modalloader').prop('style', 'display:none');
                $('#agentmodal').modal('show');
                }else{
                    $('#agenthtml').empty();
                    alert("No Agents Found");
                }
        },
        error: function (message) {
            alert("ERROR");
            console.log(message)
        },
        complete: function () {
            // $('#agentmodal').modal('show');
            $('#loader').prop('style', 'display:none');
            $('#reop').prop('hidden', false);
        },
    });
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
