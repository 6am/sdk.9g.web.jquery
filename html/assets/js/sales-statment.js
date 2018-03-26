// In your Javascript (external .js resource or <script> tag)
$(document).ready(function() {
    // Select2
    $('.sales-statment-select2').select2();
    //Date Pickers
    $('#datepicker-start, #datepicker-end').datepicker();

    // Initialize datatable showing a search box at the top right corner
    var initTableWithSearch = function() {
        var table = $('#tableWithSearch');

        var settings = {
            "sDom": "<t><'row'<p i>>",
            "destroy": true,
            "scrollCollapse": true,
            "oLanguage": {
                "sLengthMenu": "_MENU_ ",
                "sInfo": "Showing <b>_START_ to _END_</b> of _TOTAL_ entries"
            },
            "iDisplayLength": 5
        };

        table.dataTable(settings);

        // search box for table
        $('#search-table').keyup(function() {
            table.fnFilter($(this).val());
        });
    }
    initTableWithSearch();


});