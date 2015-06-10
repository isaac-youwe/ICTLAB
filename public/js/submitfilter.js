/**
 * Created by serhildan on 10-6-15.
 */

function subm() {
    if (document.getElementById('searchid').value == 0) {
        alert("Vul een buurt of plaats in aub");
    }
    else {
        document.getElementById('form').submit();
    }
}
