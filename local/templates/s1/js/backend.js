$(document).ready(function () {
    console.log("back");
    forms();
});

function forms() {
    $(document).on("submit", "[data-from]", function (e) {
        e.preventDefault();
        const form = $(this);
        const dateValue = form.find('[name="currency_date"]').val();
        
        BX.ajax({
            url: window.location.pathname + '?ajax=Y&currency_date=' + dateValue,
            method: 'GET',
            dataType: 'html',
            onsuccess: function(response) {
                var tempDiv = document.createElement('div');
                tempDiv.innerHTML = response;
                
                var responseContent = tempDiv.querySelector('[data-content]');
                var pageContent = document.querySelector('[data-content]');
                
                if (responseContent && pageContent) {
                    BX.adjust(pageContent, {
                        html: responseContent.innerHTML
                    });
                }
            },
            onfailure: function(response) {
                console.error('AJAX error:', response);
            }
        });
    });
}