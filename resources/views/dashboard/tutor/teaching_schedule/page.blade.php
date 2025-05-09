@extends('dashboard.layouts.master')

@section('title', 'ตารางการสอน')

@section('content')
    <style>
        .calendar {
            margin: 30px auto;
            padding: 16px;
            background: #fff;
            font-family: sans-serif;
        }

        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .calendar-header h2 {
            margin: 0;
            font-size: 24px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);

        }

        .calendar-grid div {
            border-bottom: 1px solid #e0e0e0;
            border-left: 1px solid #e0e0e0;
            border-right: 1px solid #e0e0e0;
            min-height: 130px;
            padding: 4px;
            font-size: 14px;
            overflow-y: auto;
            max-height: 130px;
            position: relative;
        }

        .calendar-grid div .date-number {
            font-weight: bold;
            margin-bottom: 4px;
            display: block;
        }

        .calendar-grid .today {
            background-color: #e7e7ff;
            border: 2px solid #696cff;
        }

        .event {
            background: #696cff;
            color: white;
            font-size: 14px;
            padding: 2px 4px;
            border-radius: 20px;
            margin-top: 4px;
            display: block;
            white-space: nowrap;
            text-overflow: ellipsis;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .event:hover {
            background-color: #5a5ae6;
            cursor: pointer;
        }


        .btn-arrow {
            border-radius: 50%;
            padding: 5px 15px;
            font-size: 16px;
            border: 2px solid #696cff;
            background-color: #e7e7ff;
            color: #696cff;
            transition: all 0.3s ease;
        }

        .btn-arrow:hover {
            background-color: #696cff;
            color: white;
            border-color: #696cff;
        }

        
    </style>
    <div class="row">
        <!-- ตัวอย่าง Card -->
        <div class="">
            <div class="card">
                <div class="card-body">
                    <h4 class="fw-bold py-3 mb-4">ตารางการสอน</h4>
                    <div class="calendar">
                        <div class="calendar-header">
                            <button class="btn-arrow" onclick="changeMonth(-1)">❮</button>
                            <h2 id="monthYear"></h2>
                            <button class="btn-arrow" onclick="changeMonth(1)">❯</button>
                        </div>
                        <div class="calendar-grid text-center" style="border-top: 1px solid #e0e0e0;">
                            <div>อา.</div>
                            <div>จ.</div>
                            <div>อ.</div>
                            <div>พ.</div>
                            <div>พฤ.</div>
                            <div>ศ.</div>
                            <div>ส.</div>
                            <!-- วันที่จะถูกเติมด้วย JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            let currentDate = new Date();

            const events = {
                '2025-05-12': [{
                        id: 1,
                        name: 'เรียนออนไลน์'
                    },
                    {
                        id: 2,
                        name: 'ประชุม Zoom'
                    },
                    {
                        id: 3,
                        name: 'ตรวจงาน'
                    },
                    {
                        id: 4,
                        name: 'ประชุม Zoom'
                    },
                ],
                '2025-05-18': [{
                    id: 4,
                    name: 'สอบกลางภาค'
                }],
                '2025-06-22': [{
                    id: 5,
                    name: 'สอบปลายภาค'
                }],
            };

            function renderCalendar(date) {
                const year = date.getFullYear();
                const month = date.getMonth();
                const firstDay = new Date(year, month, 1).getDay();
                const lastDate = new Date(year, month + 1, 0).getDate();
                const today = new Date();

                document.getElementById("monthYear").innerText =
                    `${date.toLocaleString('th-TH', { month: 'long' })} ${year + 543}`;

                const grid = document.querySelector(".calendar-grid");

                // ล้างเฉพาะวัน (ไม่ลบหัวตารางวันในสัปดาห์)
                while (grid.children.length > 7) {
                    grid.removeChild(grid.lastChild);
                }

                // เติมช่องว่างก่อนวันที่ 1
                for (let i = 0; i < firstDay; i++) {
                    const empty = document.createElement("div");
                    grid.appendChild(empty);
                }

                for (let day = 1; day <= lastDate; day++) {
                    const dayCell = document.createElement("div");
                    const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                    const dateNumber = document.createElement("span");
                    dateNumber.className = "date-number";
                    dateNumber.textContent = day;
                    dayCell.appendChild(dateNumber);

                    // ถ้าวันนี้
                    if (
                        day === today.getDate() &&
                        month === today.getMonth() &&
                        year === today.getFullYear()
                    ) {
                        dayCell.classList.add("today");
                    }

                    // ถ้ามีกิจกรรม
                    if (events[dateStr]) {
                        const link = document.createElement("a");
                        // ใช้ URL ของกิจกรรมที่มี id (สำหรับเปลี่ยนไปยังหน้าแสดงรายละเอียดกิจกรรม)
                        link.href = `/event/${dateStr}`; // หรือสามารถปรับใช้ ID ได้เช่น `/event/${event.id}`
                        link.style.textDecoration = "none";
                        

                        events[dateStr].forEach(event => {
                            const ev = document.createElement("a");
                            link.style.color = "#ffff";
                            ev.className = "event";
                            ev.textContent = event.name;
                            link.appendChild(ev);
                        });

                        dayCell.appendChild(link);
                    }

                    // เพิ่ม dayCell ลงในตาราง
                    grid.appendChild(dayCell);
                }
            }

            function changeMonth(offset) {
                currentDate.setMonth(currentDate.getMonth() + offset);
                renderCalendar(currentDate);
            }

            // แสดงปฏิทินตอนโหลด
            document.addEventListener("DOMContentLoaded", function() {
                renderCalendar(currentDate);
            });
        </script>

    </div>

@endsection
