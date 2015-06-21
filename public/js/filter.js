/**
 * Author Adriel Walter
 * Author Serhildan Akdeniz
 */
$(document).ready(function () {
    $('#filter').sidr();
});
function submitForm() {
    if (document.getElementById('zb-buurt').value.length > 3) {
        document.getElementById('form').submit();
    }

}