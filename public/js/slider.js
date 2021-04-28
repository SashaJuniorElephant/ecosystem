$( function () {
    $( "#slider-amount-steps" ).slider({
        range: "min",
        value: 10,
        min: 1,
        max: 20,
        slide: function( event, ui ) {
            $( "#manual_menu_amountSteps" ).val( ui.value );
        }
    });
    $( "#manual_menu_amountSteps" ).val($( "#slider-amount-steps" ).slider( "value" ) );

    $("input#manual_menu_amountSteps").change(function(){
        var min = 1;
        var max = 20;
        var value = $("input#manual_menu_amountSteps").val();

        if(parseInt(value) > max){
            value = max;
            $("input#manual_menu_amountSteps").val(value);
        } else if (parseInt(value) < min) {
            value = min;
            $("input#manual_menu_amountSteps").val(value);
        }
        $("#slider-amount-steps").slider("value", value);
    });
} );