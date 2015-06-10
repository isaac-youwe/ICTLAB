/**
 * Created by Adriel Walter on 4/24/2015.
 * submitForm by Serhildan Akdeniz.
 */
$(document).ready(function () {
    $('#filter').sidr();
});
function submitForm() {
    if (document.getElementById('searchid').value <= 2) {
        //do nothing
    }
    else {
        document.getElementById('form').submit();
    }
}