$(document).ready(function() {
    $('#physical_same input').on('change', function() {
        var $checked = $('#physical_same').find('input');
        if($checked.prop("checked")) {
            // Checked
            $('#physical_block').hide();

            $('#physical_attention').val('');
            $('#physical_street').val('');
            $('#physical_city').val('');
            $('#physical_state').val('');
            $('#physical_postcode').val('');
            $('#physical_country').val('');
        }
        else {
            // Not checked
            $('#physical_block').show();
        }
    });

    $('div.contacts').on('click', '#remove_row', function() {
        var rows = 0;
        $('div.contacts').find('div.contact').each(function() {
            rows++;
        });

        var $row = $($(this).parents('div.contact').get(0));
        if(rows == 1) {
            // Clear row
            $('#add-contact').click();
            $('div.contact').find('input.form-control').each(function() {
                $(this).val('');
            });
        }

        $row.remove();
    });
});
