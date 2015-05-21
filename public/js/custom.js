/**
 * Created by Serhildan Akdeniz on 20-5-2015.
 */
$('.myForms').submit(function () {
    return true;
})

$("#bla").click(function () {
    $(".myForms").trigger('submit');

});