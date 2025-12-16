$(document).ready(function () {
  $('#reservationTable').DataTable({
    dom: '<"d-flex justify-content-between align-items-center mb-2"Bf>rt<"d-flex justify-content-between mt-3"lip>',
    buttons: [
          { extend: 'copy', className: 'btn btn-secondary btn-sm' },
          { extend: 'csv', className: 'btn btn-info btn-sm' },
          { extend: 'excel', className: 'btn btn-success btn-sm' },
          { extend: 'pdf', className: 'btn btn-danger btn-sm' },
          { extend: 'print', className: 'btn btn-primary btn-sm' }
    ],
    order: [[6, 'asc']], // Sort by booking date
    pageLength: 10,
    responsive: true,
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Search reservations..."
    }
  });
});