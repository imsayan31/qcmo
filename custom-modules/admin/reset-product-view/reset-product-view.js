/* Product View Reset */
function viewResetFunction() {
    var $ = jQuery;
    var r = confirm("Are you sure you want to reset the view counter?");
    if (r == true) {
        var data = {
            action: 'reset_product_view'
        };
        $.post(ResetProductView.ajaxurl, data, function (resp) {
            reset_redirect(resp.url);
        }, 'json');
    }
}

function reset_redirect(url) {
    setTimeout(function () {
        window.location.href = url;
    }, 1000);
}