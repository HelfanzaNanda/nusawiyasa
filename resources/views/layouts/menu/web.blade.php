<ul>
    <li class="menu-title">
        <span>Main</span>
    </li>
    <li {{ (request()->segment(1) == '') ? 'class=active' : '' }}>
        <a href="{{url('/')}}"><i class="la la-dashboard"></i> <span>Beranda</span></a>
    </li>
    @if(Session::get('_role_id') == 1 || Session::get('_role_id') == 2)
    <li {{ (request()->segment(1) == 'employe' || request()->segment(1) == 'employe-detail') ? 'class=active' : '' }}>
        <a href="{{url('/employe')}}"><i class="la la-user"></i> <span>Pegawai</span></a>
    </li>
    <li class="menu-title">
        <span>Marketing</span>
    </li>
{{--     <li {{ (request()->segment(1) == 'dashboard') ? 'class=active' : '' }}>
        <a href="{{url('/dashboard/marketing')}}"><i class="la la-dashboard"></i> <span>Beranda Marketing</span></a>
    </li> --}}
    <li class="submenu">
        <a href="#"><i class="la la-users"></i> <span> Konsumen</span> <span class="menu-arrow"></span></a>
        <ul style="display: none;">
            <li><a {{ (request()->segment(1) == 'customers') ? 'class=active' : '' }} href="{{url('/customers')}}">Data Konsumen</a></li>
            {{-- <li><a {{ (request()->segment(1) == 'customer-terms') ? 'class=active' : '' }} href="{{url('/customer-terms')}}">Dokumen</a></li> --}}
            <li><a {{ (request()->segment(1) == 'customer-payments') ? 'class=active' : '' }} href="{{url('/customer-payments')}}">Pembayaran</a></li>
        </ul>
    </li>
    <li class="submenu">
        <a href="#"><i class="la la-home"></i> <span> Perumahan</span> <span class="menu-arrow"></span></a>
        <ul style="display: none;">
            @if(Session::get('_role_id') == 1)
            <li><a {{ (request()->segment(1) == 'clusters') ? 'class=active' : '' }} href="{{url('/clusters')}}">Data Kluster</a></li>
            @endif
            <li><a {{ (request()->segment(1) == 'lots') ? 'class=active' : '' }} href="{{url('/lots')}}">Data Kavling</a></li>
        </ul>
    </li>
    <li {{ (request()->segment(1) == 'booking-page') ? 'class=active' : '' }}>
        <a href="{{url('/booking-page')}}"><i class="la la-hand-o-up"></i> <span>Customer Booking</span></a>
    </li>
    <li {{ (request()->segment(1) == 'spk-project') ? 'class=active' : '' }}>
        <a href="{{url('/spk-project')}}"><i class="la la-briefcase"></i> <span>SPK Project</span></a>
    </li>
    @endif

    @if(Session::get('_role_id') == 1 || Session::get('_role_id') == 3 || Session::get('_role_id') == 10)
    <li class="menu-title">
        <span>Project </span>
    </li>
{{--     <li>
        <a {{ (request()->segment(1) == 'dashboard') ? 'class=active' : '' }} href="{{url('/dashboard/project')}}"><i class="la la-dashboard"></i> <span>Beranda Project</span></a>
    </li> --}}
    @if(Session::get('_role_id') == 1 || Session::get('_role_id') == 3)
    <li class="submenu">
        <a href="#"><i class="la la-broadcast-tower"></i> <span> Konfirmasi Pembangunan</span> <span class="menu-arrow"></span></a>
        <ul style="display: none;">
            <li><a {{ (request()->segment(1) == 'customer-confirmation') ? 'class=active' : '' }} href="{{url('/customer-confirmation')}}">Konfirmasi Konsumen</a></li>
        </ul>
    </li>
    <li {{ (request()->segment(1) == 'work-agreement') ? 'class=active' : '' }}>
        <a href="{{url('/work-agreement')}}"><i class="la la-handshake"></i> <span>Surat Perjanjian Kerja</span></a>
    </li>
    <li {{ (request()->segment(1) == 'rap') ? 'class=active' : '' }}>
        <a href="{{url('/rap')}}"><i class="la la-clipboard-list"></i> <span>RAP</span></a>
    </li>
    @endif
    @if(Session::get('_role_id') == 1 || Session::get('_role_id') == 3 || Session::get('_role_id') == 10)
    <li {{ (request()->segment(1) == 'request-material') ? 'class=active' : '' }}>
        <a href="{{url('/request-material')}}"><i class="la la-dolly-flatbed"></i> <span>Pengajuan Bahan</span></a>
    </li>
    @endif
    @if(Session::get('_role_id') == 1 || Session::get('_role_id') == 3)
    <li {{ (request()->segment(1) == 'development-progress') ? 'class=active' : '' }}>
        {{-- <a href="{{url('/development-progress')}}"><i class="la la-paint-roller"></i> <span>Progress Pembangunan</span></a> --}}
        <a href="{{url('/development-progress')}}"><i class="la la-paint-roller"></i> <span>Laporan Harian</span></a>
    </li>
    @endif
    @endif

    @if(Session::get('_role_id') == 1 || Session::get('_role_id') == 5)
    <li class="menu-title">
        <span>Gudang </span>
    </li>
    <li class="submenu">
        <a href="#"><i class="la la-box"></i> <span> Inventory</span> <span class="menu-arrow"></span></a>
        <ul style="display: none;">
            <li><a {{ (request()->segment(1) == 'inventory') ? 'class=active' : '' }} href="{{url('/inventory')}}">Data Barang</a></li>
            {{-- <li><a {{ (request()->segment(1) == 'inventory-category') ? 'class=active' : '' }} href="{{url('/inventory-category')}}">Kategori Barang</a></li> --}}
            <li><a {{ (request()->segment(1) == 'unit') ? 'class=active' : '' }} href="{{url('/unit')}}">Unit</a></li>
        </ul>
    </li>
    <li {{ (request()->segment(1) == 'supplier') ? 'class=active' : '' }}>
        <a href="{{url('/supplier')}}"><i class="la la-user-tag"></i> <span>Supplier</span></a>
    </li>
    <li {{ (request()->segment(1) == 'inventory-history') ? 'class=active' : '' }}>
        <a href="{{url('/inventory-history')}}"><i class="la la-boxes"></i> <span>Riwayat Stok</span></a>
    </li>
    <li {{ (request()->segment(1) == 'receipt-of-goods-request') ? 'class=active' : '' }}>
        <a href="{{url('/receipt-of-goods-request')}}"><i class="la la-hand-holding"></i> <span>Bon Permintaan Barang</span></a>
    </li>
    <li {{ (request()->segment(1) == 'receipt-of-goods') ? 'class=active' : '' }}>
        <a href="{{url('/receipt-of-goods')}}"><i class="la la-hand-holding"></i> <span>Bukti Penerimaan Barang</span></a>
    </li>
    <li {{ (request()->segment(1) == 'delivery-order') ? 'class=active' : '' }}>
        <a href="{{url('/delivery-order')}}"><i class="la la-truck"></i> <span>Surat Jalan Keluar</span></a>
    </li>
    <li {{ (request()->segment(1) == 'report-used-inventory') ? 'class=active' : '' }}>
        <a href="{{url('/report-used-inventory')}}"><i class="la la-pallet"></i> <span>Lap. Pemakaian Bahan</span></a>
    </li>
    <li {{ (request()->segment(1) == 'report-stock-opname') ? 'class=active' : '' }}>
        <a href="{{url('/report-stock-opname')}}"><i class="la la-clipboard-check"></i> <span>Lap. Stock Opname</span></a>
    </li>
    @endif

    @if(Session::get('_role_id') == 1 || Session::get('_role_id') == 6)
    <li class="menu-title">
        <span>Purchasing </span>
    </li>
    <li {{ (request()->segment(1) == 'purchase-order') ? 'class=active' : '' }}>
        <a href="{{url('/purchase-order')}}"><i class="la la-money-bill-wave"></i> <span>Purchase Order</span></a>
    </li>
    <li {{ (request()->segment(1) == 'report-inventory-purchase') ? 'class=active' : '' }}>
        <a href="{{url('/report-inventory-purchase')}}"><i class="la la-file-alt"></i> <span>Lap. Pembelian Barang</span></a>
    </li>
    <li {{ (request()->segment(1) == 'report-outstanding-po') ? 'class=active' : '' }}>
        <a href="{{url('/report-outstanding-po')}}"><i class="la la-file-alt"></i> <span>Lap. Outstanding PO</span></a>
    </li>
    @endif

    @if(Session::get('_role_id') == 1)
    <li class="menu-title">
        <span>Pengaturan </span>
    </li>
    <li {{ (request()->segment(1) == 'user') ? 'class=active' : '' }}>
        <a href="{{url('/user')}}"><i class="la la-file-alt"></i> <span>Pengguna</span></a>
    </li>
    @endif

    @if(Session::get('_role_id') == 1 || Session::get('_role_id') == 10)
    <li class="menu-title">
        <span>Accounting </span>
    </li>
    @if(Session::get('_role_id') == 1)
    <li {{ (request()->segment(1) == 'debt') ? 'class=active' : '' }}>
        <a href="{{url('/debt')}}"><i class="la la-file-alt"></i> <span>Hutang</span></a>
    </li>

    <li {{ (request()->segment(1) == 'accounting-master') ? 'class=active' : '' }}>
        <a href="{{url('/accounting-master')}}"><i class="la la-file-alt"></i> <span>COA</span></a>
    </li>
    @endif
    @if(Session::get('_role_id') == 10 || Session::get('_role_id') == 1)
    <li {{ (request()->segment(1) == 'accounting-general-ledger') ? 'class=active' : '' }}>
        <a href="{{url('/accounting-general-ledger')}}"><i class="la la-file-alt"></i> <span>Jurnal Umum</span></a>
    </li>
    @endif
    @if(Session::get('_role_id') == 1)
    <li {{ (request()->segment(1) == 'accounting-ledger') ? 'class=active' : '' }}>
        <a href="{{url('/accounting-ledger')}}"><i class="la la-file-alt"></i> <span>Buku Besar</span></a>
    </li>

    <li {{ (request()->segment(1) == 'accounting-profit-loss') ? 'class=active' : '' }}>
        <a href="{{url('/accounting-profit-loss')}}"><i class="la la-file-alt"></i> <span>Laba Rugi</span></a>
    </li>

    <li {{ (request()->segment(1) == 'accounting-balance-sheet') ? 'class=active' : '' }}>
        <a href="{{url('/accounting-balance-sheet')}}"><i class="la la-file-alt"></i> <span>Neraca</span></a>
    </li>
    @endif
    @endif


    @if(Session::get('_role_id') == 1)
    <li class="menu-title">
        <span>SLF </span>
    </li>
    <li {{ (request()->segment(1) == 'slf-template') ? 'class=active' : '' }}>
        <a href="{{url('/slf-template')}}"><i class="la la-file-alt"></i> <span>SLF Template</span></a>
    </li>
    @endif
</ul>
