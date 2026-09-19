document.addEventListener("DOMContentLoaded", function () {
  const tableEl = document.getElementById("joined-users-table-wrapper");
  if (!tableEl) return;

  $("#joined-users-table").DataTable({
    processing: true,
    serverSide: true,
    autoWidth: false, // Menghindari konflik flexbox Bootstrap
    responsive: true,
    ajax: {
      url: tableEl.getAttribute("data-url"),
      type: "GET",
    },
    columnDefs: [
      {
        targets: 0,
        className: "dtr-control", // Kolom 0 jadi tombol (+)
        orderable: false,
        searchable: false,
        data: null,
        defaultContent: "",
      },
    ],
    columns: [
      { data: null },
      {
        data: null,
        orderable: false,
        searchable: false,
        render: function (data, type, row, meta) {
          return meta.row + meta.settings._iDisplayStart + 1;
        },
      },
      { data: "user_name", name: "user_name", defaultContent: "-" },
      { data: "phone_number", name: "phone_number", defaultContent: "-" },
      { data: "current_step", name: "current_step", defaultContent: "0" },
      {
        data: "screenshots_sent",
        name: "screenshots_sent",
        defaultContent: "0",
      },
      { data: "started_at", name: "started_at", defaultContent: "-" },
      { data: "completed_at", name: "completed_at", defaultContent: "-" },
      {
        data: "admin_sent_at",
        name: "admin_sent_at",
        defaultContent: "Belum dikirim",
      },
      { data: "last_active", name: "last_active", defaultContent: "-" },
    ],
    order: [[7, "desc"]],
  });
});
