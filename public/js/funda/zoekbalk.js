$(document).ready(function () {
// Instantiate the Bloodhound suggestion engine
    var buurten = new Bloodhound({
        datumTokenizer: function (datum) {
            return Bloodhound.tokenizers.whitespace(datum.value);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: 'zb-funda.php?buurt=%QUERY',
            wildcard: '%QUERY',
            filter: function (buurten) {
                return $.map(buurten.Results, function (buurt) {
                    return {
                        value: buurt.Display.Naam,
                        city: buurt.Display.Parent
                    };
                });
            }
        }
    });

// Initialize the Bloodhound suggestion engine
    buurten.initialize();

// Instantiate the Typeahead UI
    $('#zb-buurt').typeahead(
        {
            hint: true,
            highlight: true,
            minLength: 4
        }, {
            displayKey: 'value',
            source: buurten.ttAdapter(),
            templates: {
                suggestion: Handlebars.compile("<p><b>{{value}}</b> - {{city}}</p>"),
                footer: Handlebars.compile("<b>Searched for '{{query}}'</b>")
            }
        }).on('typeahead:selected', function (event, data) {
            $('#zb-stad').val(data.city);
            $('#zb-buurt2').val(data.value);
        })
});