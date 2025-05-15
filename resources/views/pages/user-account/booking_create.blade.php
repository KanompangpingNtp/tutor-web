@extends('layouts.app')
@section('title', 'Subject-category')
@section('content')
    <style>
        .bg-home {
            background: url('{{ asset('home/Bg.png') }}') no-repeat center center;
            background-size: cover;
            min-height: 85.2vh;
            padding: 2rem 2rem 2rem 2rem;
        }

        .course-card {
            border-radius: 16px;
            padding: 10px 15px;
            border: 0px solid #0000;
            overflow: hidden;
            background-color: #ffffff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-5px);
        }

        .course-card img {
            border-radius: 20px;
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .btn-detail {
            background: linear-gradient(to right, #91d5ff, #1186fc);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.5);
            color: white;
            font-size: 20px;
            border-radius: 20px;
            text-decoration: none;
            padding: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: all 0.3s ease;
        }

        .btn-detail:hover {
            background: linear-gradient(to left, #91d5ff, #3397fc);
            box-shadow: 0 6px 16px rgba(17, 134, 252, 0.45);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

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
            background: #ff9800;
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
            margin-bottom: 1px
        }

        .event:hover {
            background-color: #e68900;
            cursor: pointer;
            color: black;
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

    <body>
        <div class="bg-home d-flex align-items-start justify-content-center">
            <div class="container py-5">
                <div class="row">
                    <!-- ตัวอย่าง Card -->
                    <div class="">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="fw-bold py-3 mb-4 text-center">ตารางการสอน</h4>
                                <div class="calendar">
                                    <div class="calendar-header">
                                        <button class="btn-arrow" onclick="changeMonth(-1)">❮</button>
                                        <h2 id="monthYear"></h2>
                                        <button class="btn-arrow" onclick="changeMonth(1)">❯</button>
                                    </div>
                                    <div class="calendar-grid text-center" style="border-top: 1px solid #e0e0e0;">
                                        <div class="bg-primary text-white">อา.</div>
                                        <div class="bg-primary text-white">จ.</div>
                                        <div class="bg-primary text-white">อ.</div>
                                        <div class="bg-primary text-white">พ.</div>
                                        <div class="bg-primary text-white">พฤ.</div>
                                        <div class="bg-primary text-white">ศ.</div>
                                        <div class="bg-primary text-white">ส.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <script>
                        const events = {
                            "2025-05-03": [{
                                id: 1,
                                name: "กิจกรรมวันแรงงานเลื่อน"
                            }],
                            "2025-05-10": [{
                                    id: 2,
                                    name: "กิจกรรมประชุมใหญ่"
                                },
                                {
                                    id: 3,
                                    name: "เวิร์กช็อปออกแบบ"
                                }
                            ],
                            "2025-05-15": [{
                                id: 4,
                                name: "สอบกลางภาค"
                            }],
                            "2025-05-22": [{
                                id: 5,
                                name: "อบรมออนไลน์"
                            }]
                        };

                        let currentDate = new Date();

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

                            // สร้างวันที่ในปฏิทิน
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
                                    events[dateStr].forEach(event => {
                                        // สร้างลิงก์ของกิจกรรม และใส่ class "event" ไปที่ <a> เลย
                                        const dayCellLink = document.createElement("a");
                                        dayCellLink.href = `{{ route('TeachingScheduleDetails', ['id' => '__id__']) }}`.replace(
                                            '__id__', event.id);
                                        dayCellLink.className = "event"; // ใช้ class event ที่กำหนดใน CSS
                                        dayCellLink.textContent = event.name;

                                        // เพิ่มลิงก์กิจกรรมลงใน dayCell
                                        dayCell.appendChild(dayCellLink);
                                    });
                                }


                                // เพิ่ม dayCell ลงในตาราง
                                grid.appendChild(dayCell);
                            }
                        }

                        // ฟังก์ชันเปลี่ยนเดือน
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
            </div>
        </div>

    </body>
@endsection
