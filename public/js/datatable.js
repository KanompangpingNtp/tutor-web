$(document).ready(function () {
    var dataTable = $('#data_table').DataTable({
        lengthMenu: [
            [20, 40, 80, -1],
            [20, 40, 80, 'ทั้งหมด']
        ],
        language: {
            processing: "กำลังดำเนินการ...",
            search: "ค้นหา :",
            lengthMenu: "แสดง _MENU_ รายการ",
            info: "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
            infoEmpty: "ไม่มีข้อมูล",
            infoFiltered: "(กรองข้อมูล _MAX_ รายการทั้งหมด)",
            infoPostFix: "",
            loadingRecords: "กำลังโหลดข้อมูล...",
            zeroRecords: "ไม่พบข้อมูล",
            emptyTable: "ไม่มีข้อมูล",
            paginate: {
                first: "หน้าแรก",
                previous: "ก่อนหน้า",
                next: "ถัดไป",
                last: "หน้าสุดท้าย",
            },
        },
    });
});
