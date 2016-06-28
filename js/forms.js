$(document).ready(function() {
    $('.add_form').submit(function(event) {
        var formValid = $(this).form('is valid');

        if (!formValid) {
            console.log("Form is invalid.");
            event.preventDefault();
            return false;
        }
        var formId = $(this).attr('id');
        var formData = $(this).serialize();
        var formIn = {
            'formId'    : formId,
            'formData'  : formData
        };

        // process the form
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'process_add_form.php', // the url where we want to POST
            data        : formIn, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode      : true
        }).done(function(dataOut) {
            $('.form_error').remove();
            $('.form_success').remove();
            if (dataOut.success) {
                $("#" + formId).append('<div class="ui green message form_success">' + dataOut.message + '</div>');
                $('.form_success').transition({
                    animation : 'fade',
                    duration   : 3000
                });
            } else {
                var errorDisplayMessage = dataOut.errors[0].displayMessage + " " + dataOut.errors[0].errorMessage;
                if (errorDisplayMessage.length != 0) {
                    $("#" + formId).append('<div class="ui red message form_error">' + errorDisplayMessage + '</div>');
                }
            }
        });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
        return false;
    });

    function FetchStockAvailable(medicationName, callbackStockAvailable) {
        dataIn = {
            'type' : 'stock_available',
            'key'  : medicationName
        }
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'fetch_value.php', // the url where we want to POST
            data        : dataIn,
            dataType    : 'json', // what type of data do we expect back from the server
            encode      : true
        }).done(function(dataOut) {
            if (dataOut.success) {
                callbackStockAvailable(dataOut.results);
            } else {
                callbackStockAvailable(0);
            }
        });
    }

    $('#medication_names').change(function() {
        var selectedValue = $('#medication_names').val();
        if (selectedValue != '') {
            FetchStockAvailable(selectedValue, function (stockAvailable) {
                $('#stock_available_message').removeClass('hidden');
                $('#stock_available_message').text("" + stockAvailable + " Items Available");
                if (stockAvailable > 0) {
                    $('#stock_available_message').addClass('green');
                    $('#sale_amount_field').removeClass('disabled');
                    $('#add_sale_submit_button').removeClass('disabled');
                } else {
                    $('#stock_available_message').addClass('red');
                }     
            });                   
        } else {
            $('#sale_amount_field').addClass('disabled');
            $('#add_sale_submit_button').addClass('disabled');
            $('#stock_available_message').addClass('hidden');
        }
    });   

    // Form Validation
    $('.ui.form').form({
        inline: true,
        fields: {
            // Medication Form
            medication_name: {
                identifier: 'medication_name',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter the name of medication.'
                    }
                ]
            },
            medication_generic_equivalent: {
                identifier: 'medication_generic_equivalent',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter a generic equivalent.'
                    }
                ]
            },
            medication_price: {
                identifier: 'medication_price',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter a price.'
                    },
                    {
                        type: 'regExp[/^\\d+(.\\d{1,2})?$/]',
                        prompt: 'Please enter a valid price.'
                    }
                ]
            },
            medication_stock_available: {
                identifier: 'medication_stock_available',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter the amount of stock available.'
                    },
                    {
                        type: 'regExp[/^\\d{1,8}$/]',
                        prompt: 'Please enter a valid integer.'
                    }
                ]
            },
            medication_manufacturer_name: {
                identifier: 'medication_manufacturer_name',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please select a manufacturer from the list.'
                    }
                ]
            },
            // Manufacturer form
            manufacturer_name: {
                identifier: 'manufacturer_name',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter the name of the manufacturer.' 
                    }
                ]
            },
            manufacturer_address: {
                identifier: 'manufacturer_address',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter the address of the manufacturer.' 
                    }
                ]
            },
            manufacturer_email: {
                identifier: 'manufacturer_email',
                optional: true,
                rules: [
                    {
                        type   : 'email',
                        prompt : 'Please enter a valid email address.' 
                    }
                ]
            },
            // Sales form
            sale_medication_name: {
                identifier: 'sale_medication_name',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter the name of the medication.' 
                    }
                ]
            },
            sale_amount: {
                identifier: 'sale_amount',
                rules: [
                    {
                        type   : 'empty',
                        prompt : 'Please enter the sale amount.' 
                    },
                    {
                        type: 'integer',
                        prompt: 'Please enter a valid sale amount.'
                    }
                ]
            }
        }
    });
}); 