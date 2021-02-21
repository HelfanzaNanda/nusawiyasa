@php
    $user = App\Http\Models\Users::find(Session::get('_id'));
@endphp
<ul>
    <li class="menu-title"> 
        <span>Main</span>
    </li>
    <li {{ (request()->segment(1) == '') ? 'class=active' : '' }}>
        <a href="{{url('/')}}"><i class="la la-dashboard"></i> <span>Beranda</span></a>
    </li>

    @if($user->can('employe'))
        <li {{ (request()->segment(1) == 'employe' || request()->segment(1) == 'employe-detail') ? 'class=active' : '' }}>
            <a href="{{url('/employe')}}"><i class="la la-user"></i> <span>Pegawai</span></a>
        </li>
    @endif

    @if($user->hasAnyPermission(['customers', 'customer-payments', 'clusters', 'lots', 'booking-page', 'spk-project']))
        <li class="menu-title">
            <span>Marketing</span>
        </li>
        <li class="submenu">
            <a href="#"><i class="la la-users"></i> <span> Konsumen</span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                @if ($user->can('customers'))
                    <li><a {{ (request()->segment(1) == 'customers') ? 'class=active' : '' }} href="{{url('/customers')}}">Data Konsumen</a></li>    
                @endif
                @if ($user->can('customer-payments'))
                    <li><a {{ (request()->segment(1) == 'customer-payments') ? 'class=active' : '' }} href="{{url('/customer-payments')}}">Pembayaran</a></li>    
                @endif
                {{-- <li><a {{ (request()->segment(1) == 'customer-terms') ? 'class=active' : '' }} href="{{url('/customer-terms')}}">Dokumen</a></li> --}}
                
            </ul>
        </li>
        <li class="submenu">
            <a href="#"><i class="la la-home"></i> <span> Perumahan</span> <span class="menu-arrow"></span></a>
            <ul style="display: none;">
                @if($user->can('clusters'))
                <li><a {{ (request()->segment(1) == 'clusters') ? 'class=active' : '' }} href="{{url('/clusters')}}">Data Kluster</a></li>
                @endif
                @if ($user->can('lots'))
                <li><a {{ (request()->segment(1) == 'lots') ? 'class=active' : '' }} href="{{url('/lots')}}">Data Kavling</a></li>
                @endif
            </ul>
        </li>
        @if ($user->can('booking-page'))
            <li {{ (request()->segment(1) == 'booking-page') ? 'class=active' : '' }}>
                <a href="{{url('/booking-page')}}"><i class="la la-hand-o-up"></i> <span>Customer Booking</span></a>
            </li>
        @endif
        @if ($user->can('spk-project'))
            <li {{ (request()->segment(1) == 'spk-project') ? 'class=active' : '' }}>
                <a href="{{url('/spk-project')}}"><i class="la la-briefcase"></i> <span>SPK Project</span></a>
            </li>    
        @endif
    @endif
    
    @if($user->hasAnyPermission(['customer-confirmation', 'work-agreement', 'rap', 'rab', 'request-material', 'development-progress']))
        <li class="menu-title">
            <span>Project </span>
        </li>
        @if ($user->can('customer-confirmation'))
            <li class="submenu">
                <a href="#"><i class="la la-broadcast-tower"></i> <span> Konfirmasi Pembangunan</span> <span class="menu-arrow"></span></a>
                <ul style="display: none;">
                    <li><a {{ (request()->segment(1) == 'customer-confirmation') ? 'class=active' : '' }} href="{{url('/customer-confirmation')}}">Konfirmasi Konsumen</a></li>
                </ul>
            </li>
        @endif
        @if ($user->can('work-agreement'))
            <li {{ (request()->segment(1) == 'work-agreement') ? 'class=active' : '' }}>
                <a href="{{url('/work-agreement')}}"><i class="la la-handshake"></i> <span>Surat Perjanjian Kerja</span></a>
            </li>    
        @endif
        @if ($user->can('rap'))
            <li {{ (request()->segment(1) == 'rap') ? 'class=active' : '' }}>
                <a href="{{url('/rap')}}"><i class="la la-clipboard-list"></i> <span>RAP</span></a>
            </li>
        @endif
        @if ($user->can('rab'))
            <li {{ (request()->segment(1) == 'rab') ? 'class=active' : '' }}>
                <a href="{{url('/rab')}}"><i class="la la-clipboard-list"></i> <span>RAB</span></a>
            </li>
        @endif
        @if($user->can('request-material'))
            <li {{ (request()->segment(1) == 'request-material') ? 'class=active' : '' }}>
                <a href="{{url('/request-material')}}"><i class="la la-dolly-flatbed"></i> <span>Pengajuan Bahan</span></a>
            </li>
        @endif
        @if($user->can('development-progress'))
            <li {{ (request()->segment(1) == 'development-progress') ? 'class=active' : '' }}>
                {{-- <a href="{{url('/development-progress')}}"><i class="la la-paint-roller"></i> <span>Progress Pembangunan</span></a> --}}
                <a href="{{url('/development-progress')}}"><i class="la la-paint-roller"></i> <span>Laporan Harian</span></a>
            </li>
        @endif
    @endif

    @if($user->hasAnyPermission(['inventory', 'unit', 'supplier', 'inventory-history', 'receipt-of-goods-request', 
        'receipt-of-goods', 'delivery-order', 'report-used-inventory', 'report-stock-opname']))
        <li class="menu-title">
            <span>Gudang </span>
        </li>
        <li class="submenu">
            @if ($user->hasAnyPermission(['inventory', 'unit']))
                <a href="#"><i class="la la-box"></i> <span> Inventory</span> <span class="menu-arrow"></span></a>
                <ul style="display: none;">
                    @if ($user->can('inventory'))
                        <li><a {{ (request()->segment(1) == 'inventory') ? 'class=active' : '' }} href="{{url('/inventory')}}">Data Barang</a></li>    
                    @endif
                    @if ($user->can('unit'))
                        <li><a {{ (request()->segment(1) == 'unit') ? 'class=active' : '' }} href="{{url('/unit')}}">Unit</a></li>    
                    @endif
                    {{-- <li><a {{ (request()->segment(1) == 'inventory-category') ? 'class=active' : '' }} href="{{url('/inventory-category')}}">Kategori Barang</a></li> --}}                
                </ul>    
            @endif
        </li>
        
        @if ($user->can('supplier'))
            <li {{ (request()->segment(1) == 'supplier') ? 'class=active' : '' }}>
                <a href="{{url('/supplier')}}"><i class="la la-user-tag"></i> <span>Supplier</span></a>
            </li>
        @endif

        @if ($user->can('inventory-history'))
            <li {{ (request()->segment(1) == 'inventory-history') ? 'class=active' : '' }}>
                <a href="{{url('/inventory-history')}}"><i class="la la-boxes"></i> <span>Riwayat Stok</span></a>
            </li>
        @endif

        @if ($user->can('receipt-of-goods-request'))
            <li {{ (request()->segment(1) == 'receipt-of-goods-request') ? 'class=active' : '' }}>
                <a href="{{url('/receipt-of-goods-request')}}"><i class="la la-hand-holding"></i> <span>Bon Permintaan Barang</span></a>
            </li>
        @endif
        @if ($user->can('receipt-of-goods'))
            <li {{ (request()->segment(1) == 'receipt-of-goods') ? 'class=active' : '' }}>
                <a href="{{url('/receipt-of-goods')}}"><i class="la la-hand-holding"></i> <span>Bukti Penerimaan Barang</span></a>
            </li>
        @endif
        @if ($user->can('delivery-order'))
            <li {{ (request()->segment(1) == 'delivery-order') ? 'class=active' : '' }}>
                <a href="{{url('/delivery-order')}}"><i class="la la-truck"></i> <span>Surat Jalan Keluar</span></a>
            </li>
        @endif
        @if ($user->can('report-used-inventory'))
            <li {{ (request()->segment(1) == 'report-used-inventory') ? 'class=active' : '' }}>
                <a href="{{url('/report-used-inventory')}}"><i class="la la-pallet"></i> <span>Lap. Pemakaian Bahan</span></a>
            </li>
        @endif
        @if ($user->can('report-stock-opname'))
            <li {{ (request()->segment(1) == 'report-stock-opname') ? 'class=active' : '' }}>
                <a href="{{url('/report-stock-opname')}}"><i class="la la-clipboard-check"></i> <span>Lap. Stock Opname</span></a>
            </li>
        @endif

    @endif

    @if($user->hasAnyPermission(['purchase-order', 'report-inventory-purchase', 'report-outstanding-po']))
        <li class="menu-title">
            <span>Purchasing </span>
        </li>

        @if ($user->can('purchase-order'))
            <li {{ (request()->segment(1) == 'purchase-order') ? 'class=active' : '' }}>
                <a href="{{url('/purchase-order')}}"><i class="la la-money-bill-wave"></i> <span>Purchase Order</span></a>
            </li>
        @endif
        @if ($user->can('report-inventory-purchase'))
            <li {{ (request()->segment(1) == 'report-inventory-purchase') ? 'class=active' : '' }}>
                <a href="{{url('/report-inventory-purchase')}}"><i class="la la-file-alt"></i> <span>Lap. Pembelian Barang</span></a>
            </li>
        @endif
        @if ($user->can('report-outstanding-po'))
            <li {{ (request()->segment(1) == 'report-outstanding-po') ? 'class=active' : '' }}>
                <a href="{{url('/report-outstanding-po')}}"><i class="la la-file-alt"></i> <span>Lap. Outstanding PO</span></a>
            </li>
        @endif
    @endif

    @if($user->hasAnyPermission(['financial-submission']))
        <li class="menu-title">
            <span>Admin Umum </span>
        </li>

        @if ($user->can('financial-submission'))
            <li {{ (request()->segment(1) == 'financial-submission') ? 'class=active' : '' }}>
                <a href="{{url('/financial-submission')}}"><i class="la la-file-alt"></i> <span>Pengajuan Keuangan</span></a>
            </li>
        @endif

        @if ($user->can('salary-submission'))
            <li {{ (request()->segment(1) == 'salary-submission') ? 'class=active' : '' }}>
                <a href="{{url('/salary-submission')}}"><i class="la la-file-alt"></i> <span>Pengajuan Upah</span></a>
            </li>
        @endif
    @endif
    
    @if($user->hasAnyPermission(['debt', 'accounting-master', 'accounting-general-ledger', 
        'accounting-ledger', 'accounting-profit-loss', 'accounting-balance-sheet']))
        <li class="menu-title">
            <span>Accounting </span>
        </li>

        @if ($user->can('debt'))
            <li {{ (request()->segment(1) == 'debt') ? 'class=active' : '' }}>
                <a href="{{url('/debt')}}"><i class="la la-file-alt"></i> <span>Hutang</span></a>
            </li>
        @endif

        @if ($user->can('accounting-master'))
            <li {{ (request()->segment(1) == 'accounting-master') ? 'class=active' : '' }}>
                <a href="{{url('/accounting-master')}}"><i class="la la-file-alt"></i> <span>COA</span></a>
            </li>
        @endif

        @if ($user->can('accounting-general-ledger'))
            <li {{ (request()->segment(1) == 'accounting-general-ledger') ? 'class=active' : '' }}>
                <a href="{{url('/accounting-general-ledger')}}"><i class="la la-file-alt"></i> <span>Jurnal Umum</span></a>
            </li>
        @endif
        
        @if ($user->can('accounting-ledger'))
            <li {{ (request()->segment(1) == 'accounting-ledger') ? 'class=active' : '' }}>
                <a href="{{url('/accounting-ledger')}}"><i class="la la-file-alt"></i> <span>Buku Besar</span></a>
            </li>
        @endif

        @if ($user->can('accounting-profit-loss'))
            <li {{ (request()->segment(1) == 'accounting-profit-loss') ? 'class=active' : '' }}>
                <a href="{{url('/accounting-profit-loss')}}"><i class="la la-file-alt"></i> <span>Laba Rugi</span></a>
            </li>
        @endif

        @if ($user->can('accounting-balance-sheet'))
            <li {{ (request()->segment(1) == 'accounting-balance-sheet') ? 'class=active' : '' }}>
                <a href="{{url('/accounting-balance-sheet')}}"><i class="la la-file-alt"></i> <span>Neraca</span></a>
            </li>
        @endif
    @endif

    @if ($user->can('slf-template'))
        <li class="menu-title">
            <span>SLF </span>
        </li>
        <li {{ (request()->segment(1) == 'slf-template') ? 'class=active' : '' }}>
            <a href="{{url('/slf-template')}}"><i class="la la-file-alt"></i> <span>SLF Template</span></a>
        </li>
    @endif

    @if($user->hasAnyPermission(['user', 'user-permissions', 'roles', 'customer-cost', 'customer-term']))
        <li class="menu-title">
            <span>Pengaturan </span>
        </li>
        @if ($user->can('user'))
            <li {{ (request()->segment(1) == 'user') ? 'class=active' : '' }}>
                <a href="{{url('/user')}}"><i class="la la-file-alt"></i> <span>Pengguna</span></a>
            </li>
        @endif
        @if ($user->can('user-permissions'))
        <li {{ (request()->segment(1) == 'user-permissions') ? 'class=active' : '' }}>
            <a href="{{url('/user-permissions')}}"><i class="la la-wrench"></i> <span>Izin Pengguna</span></a>
        </li>
        @endif
        @if ($user->can('roles'))
        <li {{ (request()->segment(1) == 'roles') ? 'class=active' : '' }}>
            <a href="{{url('/roles')}}"><i class="la la-gears"></i> <span>Hak Akses</span></a>
        </li>
        @endif
        @if ($user->can('customer-cost'))
        <li {{ (request()->segment(1) == 'customer-cost') ? 'class=active' : '' }}>
            <a href="{{url('/customer-cost')}}"><i class="la la-gears"></i> <span>Master Biaya</span></a>
        </li>
        @endif
        @if ($user->can('customer-term'))
        <li {{ (request()->segment(1) == 'customer-term') ? 'class=active' : '' }}>
            <a href="{{url('/customer-term')}}"><i class="la la-gears"></i> <span>Master Persyaratan</span></a>
        </li>
        @endif
    @endif
</ul>
