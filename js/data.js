$(document).ready(function() {
    $('.fetch_list').each(function() {
        var listId = $(this).attr('id');
        var dataIn = {
            'listId'    : listId
        };
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'fetch_list.php', // the url where we want to POST
            data        : dataIn,
            dataType    : 'json', // what type of data do we expect back from the server
            encode      : true
        }).done(function(dataOut) {
            if (dataOut.success) {
                $("#" + listId).append('<option value="">---</option>');
                var numberOfRows = dataOut.results.length;
                for (var rowIndex = 0; rowIndex < numberOfRows; rowIndex++) {
                    $.each(dataOut.results[rowIndex], function(key, value) {
                        $("#" + listId).append('<option value="' + value + '">' + value + '</option>');
                    });
                }
            } else {
                var errorDisplayMessage = dataOut.errors[0].displayMessage;
                if (errorDisplayMessage.length != 0) {
                    $("#" + listId).after('<div class="alert label">' + errorDisplayMessage + '</div>');
                }                
            }
        });
    });

    // Act on all tables that show lists
    $('.fetch_table').each(function() {
        var tableId = $(this).attr('id');
        var dataIn = {
            'tableId'   : tableId
        };
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'fetch_table.php', // the url where we want to POST
            data        : dataIn,
            dataType    : 'json', // what type of data do we expect back from the server
            encode      : true
        }).done(function(dataOut) {
            if (!dataOut.success) {
                var errorDisplayMessage = dataOut.errors[0].displayMessage;
                if (errorDisplayMessage.length != 0) {
                    $("#" + tableId).append('<div class="alert label">' + errorDisplayMessage + '</div>');
                }
            } else {
                var numberOfRows = dataOut.results.length;
                for (var rowIndex = 0; rowIndex < numberOfRows; rowIndex++) {
                    var $tr = $('<tr></tr>');
                    $.each(dataOut.results[rowIndex], function(key, value) {
                        if (value === null || value === '') {
                            value = '-';
                        }
                        $tr.append('<td class="' + key + '">' + value + '</td>');
                    });
                    $('.table_body').append($tr);
                }
            }
            $("#" + tableId).DataTable({
                "sDom": '<"toolbar">frtip'
            });
            $("div.toolbar").html('');
        });
    });
}); 