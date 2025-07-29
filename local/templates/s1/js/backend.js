$(document).ready(function () {
    console.log("back");
    forms();
});

function forms() {
    $(document).on("submit", "[data-from]", function (e) {
        e.preventDefault();
        const form = $(this);
        const dateValue = form.find('[name="currency_date"]').val();
        const url = window.location.pathname + '?ajax=Y&currency_date=' + dateValue;
        
        $.ajax({
            type: "GET",
            url: url,
            dataType: 'html',
            cache: false,
            success: function (response) {

                // console.log('Успешный ответ:', response);
            },
            error: function (xhr, status, error) {
                // console.error("AJAX error:", status, error);
            }
        });
    });
}