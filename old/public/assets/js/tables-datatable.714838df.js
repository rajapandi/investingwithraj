"use strict";

document.addEventListener("DOMContentLoaded", function () {
    const dataTable = new simpleDatatables.DataTable("#datatable1", {
        searchable: true,
        perPageSelect:[100,200,300,400,500],
        perPage:100,
        pageLength: 50,
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf'
        ]
    });

    function adjustTableColumns() {
        let columns = dataTable.columns();

        if (window.innerWidth > 900) {
            columns.show([2, 3, 4, 5]);
        } else if (window.innerWidth > 600) {
            columns.hide([4, 5]);
            columns.show([2, 3]);
        } else {
            columns.hide([2, 3, 4, 5]);
        }
    }

    adjustTableColumns();

    window.addEventListener("resize", adjustTableColumns);
});


document.addEventListener("DOMContentLoaded", function () {
    const dataTable = new simpleDatatables.DataTable("#postDatatable", {
        searchable: true,
        pageLength: 50,
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf'
        ]
    });

    function adjustTableColumns() {
        let columns = dataTable.columns();

        if (window.innerWidth > 900) {
            columns.show([2, 3, 4, 5]);
        } else if (window.innerWidth > 600) {
            columns.hide([4, 5]);
            columns.show([2, 3]);
        } else {
            columns.hide([2, 3, 4, 5]);
        }
    }

    adjustTableColumns();

    window.addEventListener("resize", adjustTableColumns);
});
