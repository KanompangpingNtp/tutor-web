<!-- Sidebar -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ Auth::user()->level == '1' ? route('AdminIndex') : route('TutorIndex') }}" class="app-brand-link">
            <span class="app-brand-logo demo">

            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2" style="font-size: 21px;">ระบบจัดการข้อมูล</span>
        </a>
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <ul class="menu-inner py-1">
        @if (Auth::user()->level == '1')
        <li class="menu-header small text-uppercase"><span class="menu-header-text">จัดการข้อมูล</span></li>

        <li class="menu-item {{ request()->is('admin/tutor_information*') ? 'active' : '' }}">
            <a href="{{ route('TutorInformationPage') }}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user'></i>
                <div data-i18n="Analytics">จัดการข้อมูลบุคคล</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/subject*') ||
        request()->is('admin/subject_users*') ||
        request()->is('admin/courses_offered*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bx-book'></i>
                <div data-i18n="User interface">จัดการข้อมูลคอร์สเรียน</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('admin/subject') ? 'active' : '' }}">
                    <a href="{{ route('SubjectPage') }}" class="menu-link">
                        <div>จัดการวิชาที่เปิดสอน</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('admin/subject_users') ? 'active' : '' }}">
                    <a href="{{route('SubjectUsersPage')}}" class="menu-link">
                        <div>จัดการครูประจำวิชา</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('admin/courses_offered') ? 'active' : '' }}">
                    <a href="{{route('CoursesOfferedPage')}}" class="menu-link">
                        <div>จัดการคอร์สที่เปิดสอน</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">เมนูระบบ</span></li>

        <li class="menu-item {{ request()->is('admin/income_summary*') ? 'active' : '' }}">
            <a href="{{route('IncomeSummaryPage')}}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-dollar-circle'></i>
                <div data-i18n="Analytics">สรุปรายได้ของติวเตอร์</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('admin/booking_history*') ? 'active' : '' }}">
            <a href="{{route('AdminBookingHistoryPage')}}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-news'></i>
                <div data-i18n="Analytics">ประวัติการจองคอร์ส</div>
            </a>
        </li>

        @endif

        @if (Auth::user()->level == '2')
        <li class="menu-header small text-uppercase"><span class="menu-header-text">จัดการข้อมูล</span></li>

        <li class="menu-item {{ request()->is('tutor/subject_users*') ||
            request()->is('tutor/courses_offered*') ? 'active open' : '' }}">
            <a href="javascript:void(0)" class="menu-link menu-toggle">
                <i class='menu-icon tf-icons bx bx-book'></i>
                <div data-i18n="User interface">จัดการข้อมูลคอร์สเรียน</div>
            </a>

            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('tutor/subject_users') ? 'active' : '' }}">
                    <a href="{{route('SubjectTutorPage')}}" class="menu-link">
                        <div>จัดการประจำวิชา</div>
                    </a>
                </li>

                <li class="menu-item {{ request()->is('tutor/courses_offered') ? 'active' : '' }}">
                    <a href="{{route('TutorCoursesOfferedPage')}}" class="menu-link">
                        <div>จัดการคอร์สที่เปิดสอน</div>
                    </a>
                </li>
            </ul>
        </li>

        <li class="menu-item {{ request()->is('tutor/teacher_information*') ? 'active' : '' }}">
            <a href="{{route('TeacherInformationPage')}}" class="menu-link">
                <i class='menu-icon tf-icons bx bxs-user'></i>
                <div data-i18n="Analytics">จัดการข้อมูลส่วนตัว</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('tutor/teaching_schedule*') ? 'active' : '' }}">
            <a href="{{route('TeachingSchedulePage')}}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-table'></i>
                <div data-i18n="Analytics">ตารางการสอน</div>
            </a>
        </li>

        <li class="menu-item {{ request()->is('tutor/teaching_history*') ? 'active' : '' }}">
            <a href="{{route('TutorTeachingHistory')}}" class="menu-link">
                <i class='menu-icon tf-icons bx bx-history'></i>
                <div data-i18n="Analytics">ประวัติการสอน</div>
            </a>
        </li>

        @endif
    </ul>
</aside>
<!-- / Sidebar -->
