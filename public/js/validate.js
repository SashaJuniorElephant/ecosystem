function validParam() {
    $(this).val($.trim($(this).val()));
    return true;
}

var ruleSetDigital = {
    required: true,
    min: 1
};

var ruleSetWords = {
    required: {depends: validParam},
    minlength: 3
};

$('form').each(function () {
    $(this).validate({
        onkeyup: false,
        rules: {
            'manual_menu[name]': ruleSetWords,
            'manual_menu[dimension]': {min: 2},
            'manual_menu[amountSteps]': {min: 1, max: 20},
            'manual_menu[amountSimplePlants]': ruleSetDigital,
            'manual_menu[amountPoisonPlants]': ruleSetDigital,
            'manual_menu[amountHerbivores]': ruleSetDigital,
            'manual_menu[amountPredators]': ruleSetDigital,
            'manual_menu[amountBigPredators]': ruleSetDigital,
            'manual_menu[amountVisitors]': ruleSetDigital,
            'csv_menu[name]': ruleSetWords,
            'csv_menu[file]': {required: true},
            'continue_menu[game]': {required: true}
        },
    });
});
