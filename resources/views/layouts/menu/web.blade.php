<ul>
    <li class="menu-title"> 
        <span>Main</span>
    </li>
    <li> 
        <a class="active" href="{{url('/')}}"><i class="la la-dashboard"></i> <span>Dashboard</span></a>
    </li>
    <li class="menu-title"> 
        <span>Marketing</span>
    </li>
    <li> 
        <a class="active" href="{{url('/dashboard/marketing')}}"><i class="la la-dashboard"></i> <span>Dashboard Marketing</span></a>
    </li>
    <li class="submenu">
        <a href="#"><i class="la la-users"></i> <span> Konsumen</span> <span class="menu-arrow"></span></a>
        <ul style="display: none;">
            <li><a href="{{url('/customers')}}">Data Konsumen</a></li>
            <li><a href="{{url('/customer-terms')}}">Dokumen</a></li>
            <li><a href="{{url('/customer-costs')}}">Pembayaran</a></li>
        </ul>
    </li>
    <li class="submenu">
        <a href="#"><i class="la la-home"></i> <span> Perumahan</span> <span class="menu-arrow"></span></a>
        <ul style="display: none;">
            <li><a href="{{url('/clusters')}}">Data Kluster</a></li>
            <li><a href="{{url('/lots')}}">Data Kavling</a></li>
        </ul>
    </li>
    <li> 
        <a href="{{url('/booking-page')}}"><i class="la la-hand-o-up"></i> <span>Booking</span></a>
    </li>
    <li> 
        <a href="{{url('/development-progress')}}"><i class="la la-crosshairs"></i> <span>Progress Pembangunan</span></a>
    </li>
    <li class="menu-title"> 
        <span>Pengaturan </span>
    </li>
</ul>