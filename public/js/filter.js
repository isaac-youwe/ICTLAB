/**
 * Created by Adriel Walter on 4/24/2015.
 * submitForm by Serhildan Akdeniz.
 */
$(document).ready(function () {
    $('#filter').sidr();
});
function submitForm() {
    if (document.getElementById('zb-buurt').value.length > 3) {
        document.getElementById('form').submit();
    }

}