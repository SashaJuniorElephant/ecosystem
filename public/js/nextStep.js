$("#formNextStep").submit(function (event) {
    event.preventDefault(); // Отключить действие события по умолчанию
    $.ajax({
        type: "PUT",
        url: "/observation",
        dataType: "html",
        async: true,

        success: function (data) {
            $('#map-states').append(data);
            console.log(data);
        },
        error: function() {
            alert("Что-то пошло не так при выполнении AJAX-запроса");
        }
    })
});
