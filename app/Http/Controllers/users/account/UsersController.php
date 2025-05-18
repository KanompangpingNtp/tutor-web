<?php

namespace App\Http\Controllers\users\account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseBooking;
use Illuminate\Support\Facades\Auth;
use App\Models\BookingLogs;
use App\Models\CourseTeaching;
use Carbon\Carbon;

class UsersController extends Controller
{
    public $channelToken = 'rXAqVM4Sst0WA8i3aSmEAhKcwqdY4o4zO+dOkzAY3E/h7Ykx1FOUaXdE2avc1a4IvsOW9EntkqXExh0DN4BHBzMv5gLBRXjdyIiVMh3wP4Ff5yMIRMM+t7zDE2Umab5h2ka39GPidW3dzYxqS2Q0NgdB04t89/1O/w1cDnyilFU=';
    public $group_id = 'Ca15d5804ebf27251b427533420e27e6f';

    public function UsersAccount()
    {
        $userId = Auth::id();

        $booking = CourseBooking::with(['course.teachings.day', 'teachings', 'course.user'])
            ->where('user_id', $userId)
            ->get();

        // teaching_id ที่ยังมี status != 2 (แปลว่าเรียนยังไม่ครบ)
        $incompleteTeachingIds = BookingLogs::where('status', '!=', 2)
            ->pluck('teaching_id')
            ->unique()
            ->toArray();

        return view('pages.user-account.page', compact('booking', 'incompleteTeachingIds'));
    }

    public function schedule(Request $request, $id)
    {
        $request->validate([
            'scheduled_datetime' => 'required|array|min:1',
            'scheduled_datetime.*' => 'required|integer|exists:course_teachings,id',
            'note' => 'nullable|string',
        ]);

        $booking = CourseBooking::findOrFail($id);
        $booking->note = $request->note ?? null;

        $scheduledIds = $request->scheduled_datetime;
        $booking->scheduled_datetime = implode(',', $scheduledIds);
        $booking->save();

        $teachings = CourseTeaching::whereIn('id', $scheduledIds)->with('day')->get();

        $startDate = Carbon::parse($booking->created_at)->startOfDay();

        // จัด teaching ตามวัน เช่น Monday => [teaching1, teaching2]
        $teachingsByDay = [];
        foreach ($teachings as $teaching) {
            $dayName = $teaching->day->day_name ?? null;
            if ($dayName) {
                $teachingsByDay[$dayName][] = $teaching;
            }
        }

        $requiredHours = $booking->amount_times;
        $totalHours = 0;
        $currentDate = $startDate->copy();

        // วนหาวันที่จะสอนและหยุดเมื่อครบจำนวนชั่วโมงที่กำหนด
        while ($totalHours < $requiredHours) {
            $dayName = $currentDate->format('l');

            if (isset($teachingsByDay[$dayName])) {
                foreach ($teachingsByDay[$dayName] as $teaching) {
                    $start = Carbon::parse($teaching->course_starttime);
                    $end = Carbon::parse($teaching->course_endtime);
                    $hours = $end->floatDiffInHours($start); // คำนวณจำนวนชั่วโมง

                    // ถ้าเพิ่มแล้วไม่เกินชั่วโมงที่ต้องเรียน
                    if ($totalHours + $hours <= $requiredHours) {
                        BookingLogs::create([
                            'user_id' => $booking->user_id,
                            'course_id' => $booking->course_id,
                            'course_booking_id' => $booking->id,
                            'booking_day' => $dayName,
                            'scheduled_datetime' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                            'teaching_schedule_day' => $currentDate->format('Y-m-d'),
                            'teaching_id' => $teaching->id,
                            'status' => 1,
                        ]);

                        $totalHours += $hours;

                        // ถ้าครบชั่วโมงแล้ว ออกจากลูปทั้งหมด
                        if ($totalHours >= $requiredHours) {
                            break 2;
                        }
                    }
                }
            }

            $currentDate->addDay();
        }

        $userName = $booking->user->name ?? '-';
        $courseName = $booking->course->course_name ?? '-';
        $tutorName = $booking->course->user->name ?? '-';

        // ดึง BookingLogs ล่าสุดที่สร้าง
        $latestLogs = BookingLogs::where('course_booking_id', $booking->id)
            ->orderBy('teaching_schedule_day')
            ->get();

        // เตรียมข้อความตามรูปแบบ
        $message = "ชื่อผู้จอง : {$userName}\n";
        $message .= "จองคอร์สชื่อ : {$courseName}\n";
        $message .= "ครูผู้สอน : {$tutorName}\n";

        // วนทุก log เพื่อแนบข้อมูลวัน/เวลาเรียน
        foreach ($latestLogs as $log) {
            $date = Carbon::parse($log->teaching_schedule_day)->format('d/m/Y');
            $message .= "เรียนวันที่ : {$date} เวลาเรียน : {$log->scheduled_datetime}\n";
        }

        // รวมจำนวนชั่วโมงทั้งหมด
        $totalHours = $latestLogs->sum(function ($log) {
            [$start, $end] = explode(' - ', $log->scheduled_datetime);
            return Carbon::parse($end)->floatDiffInHours(Carbon::parse($start));
        });

        $message .= "จำนวนชั่วโมง : {$totalHours} ชั่วโมง";

        $this->sendChat($message);

        return redirect()->back()->with('success', 'บันทึกวันเวลาเรียนสำเร็จแล้ว');
    }

    public function sendChat($data)
    {
        $messageData = [
            'to' => $this->group_id,
            'messages' => [
                [
                    'type' => 'text',
                    'text' => $data
                ],
            ]
        ];

        $ch = curl_init('https://api.line.me/v2/bot/message/push');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->channelToken
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));

        $result = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatus == 200) {
            return true;
        } else {
            return false;
        }
    }

    public function UsersAccountBooking($id)
    {
        $booking = CourseBooking::with(['course.teachings', 'teachings'])->findOrFail($id);

        return view('pages.user-account.booking_create', compact('booking'));
    }

    public function UserTeachingSchedule($id)
    {
        $courseBooking = CourseBooking::with(['course', 'teachings', 'user'])->findOrFail($id);

        $bookingLogs = BookingLogs::with(['user', 'course', 'bookings', 'teaching'])
            ->where('course_booking_id', $id)
            ->get();

        return view('pages.user-account.teaching_schedule', compact('bookingLogs', 'courseBooking'));
    }
}
