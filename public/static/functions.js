jQuery(function($) {
    $('.stepqty').click(function() {
        id = $(this).attr("id");
        field = $(this).attr("field");
        step = parseInt($('#'+field).data("step"));
        var qtyvalue = parseInt($('#'+field).val());
        if($(this).data("move") == "more") {
            if(!isNaN(qtyvalue)) $('#'+field).val(qtyvalue + step);
            else $('#'+field).val(step);
        } else if($(this).data("move") == "less") {
            if(!isNaN(qtyvalue) && qtyvalue < step) $('#'+field).val(0);
            else if(!isNaN(qtyvalue) && qtyvalue > 0) $('#'+field).val(qtyvalue - step);
            else $('#'+field).val(0);
        }
    });
    $('#custom_date_switch').on("change", function() {
        if($('#custom_date_switch').is(':checked')) {
            $('#custom_date').show();
            $('#custom_date').prop("disabled", false);
        } else {
            $('#custom_date').hide();
            $('#custom_date').prop("disabled", true);
            $('#custom_date').val("");
        }
    });
});
