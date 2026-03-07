<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>Sipariş Karşılama — Kalefrit</title>
    <meta content="Kalefrit Sipariş Karşılama Sistemi" name="description" />
    <meta content="Buğra GÖRMÜŞ" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App Icons -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon">

    <!-- morris css -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/morris/morris.css') }}">

    <!-- DataTables -->
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Sweet Alert -->
    <link href="{{ asset('assets/plugins/sweet-alert2/sweetalert2.css') }}" rel="stylesheet" type="text/css">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap4-theme@1.0.0/dist/select2-bootstrap4.min.css" rel="stylesheet" />

    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />

    @toastr_css
    @yield('styles')

    <!-- Universal System Styles -->
    <style>
        /* Modern Select2 Pill Design & Scrollable Container */
        .select2-container--bootstrap4 .select2-selection--multiple {
            min-height: 42px !important;
            padding: 3px 10px !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 10px !important;
        }
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__rendered {
            display: flex !important;
            flex-wrap: wrap !important;
            max-height: 120px !important;
            overflow-y: auto !important;
            padding: 0 !important;
            margin: 0 !important;
            gap: 5px !important;
        }
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
            background: #f0f4ff !important;
            border: 1px solid #dbeafe !important;
            color: #4f46e5 !important;
            border-radius: 20px !important;
            padding: 2px 10px !important;
            margin: 0 !important;
            font-size: 12px !important;
            font-weight: 600 !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
            color: #4f46e5 !important;
            border: none !important;
            background: transparent !important;
            margin-right: 6px !important;
            font-weight: bold !important;
            font-size: 14px !important;
        }
        .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove:hover {
            color: #ef4444 !important;
        }
    </style>
    <style>
        .mob-logout { display: none!important; }
        @media only screen and (max-width: 768px) { .mob-logout { display: block!important; } }

        /* ===== MODERN HEADER STYLES ===== */
        .modern-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1000;
        }
        .modern-header::before {
            content: '';
            position: absolute; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Top Navbar */
        .top-navbar { padding: 15px 0; position: relative; z-index: 2; }
        .nav-content { display: flex; align-items: center; justify-content: space-between; gap: 20px; }
        .logo-section { flex-shrink: 0; }
        .logo-link { text-decoration: none; color: white; display: flex; align-items: center; }
        .logo-image { height: 40px; width: auto; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2)); }
        .nav-actions { display: flex; align-items: center; gap: 20px; }
        .quick-actions { display: flex; gap: 12px; }
        .mobile-quick-actions { display: none; gap: 8px; margin-right: 12px; }
        .action-btn {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 16px; border-radius: 8px; text-decoration: none; color: white;
            font-weight: 500; font-size: 14px; transition: all 0.3s ease;
            backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);
        }
        .action-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.2); color: white; text-decoration: none; }

        /* User Menu */
        .user-menu { position: relative; z-index: 1002; }
        .user-dropdown-toggle {
            display: flex; align-items: center; gap: 12px; padding: 10px 16px;
            background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.2);
            border-radius: 10px; color: white; text-decoration: none; transition: all 0.3s ease;
            backdrop-filter: blur(10px); user-select: none; cursor: pointer;
        }
        .user-dropdown-toggle:hover { background: rgba(255,255,255,0.25); transform: translateY(-1px); color: white; text-decoration: none; }
        .user-avatar { width:36px; height:36px; background: linear-gradient(135deg,#667eea,#764ba2); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:16px; color:white; }
        .user-info { display: flex; flex-direction: column; text-align: left; }
        .user-name { font-weight: 600; font-size: 14px; line-height: 1; }
        .user-role { font-size: 12px; opacity: 0.8; margin-top: 2px; }
        .dropdown-arrow { font-size: 12px; transition: transform 0.3s ease; }
        .dropdown.show .dropdown-arrow { transform: rotate(180deg); }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none; border-radius: 12px; box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            backdrop-filter: blur(20px); background: rgba(255,255,255,0.95);
            padding: 8px 0; margin-top: 8px; position: absolute; top: 100%; right: 0;
            min-width: 220px; z-index: 1001; display: none;
        }
        .dropdown.show .dropdown-menu { display: block; }
        .user-menu .dropdown-menu { margin-top: 8px; right: 0; left: auto; }
        @media (max-width: 576px) { .user-menu .dropdown-menu { right:10px; left:10px; width: calc(100vw - 20px); min-width: auto; } }

        .dropdown-header { padding: 16px 20px 12px; border-bottom: 1px solid rgba(0,0,0,0.1); margin-bottom: 8px; }
        .user-avatar-large { width:48px; height:48px; background: linear-gradient(135deg,#667eea,#764ba2); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:20px; color:white; margin-bottom:12px; }
        .user-details h6 { margin: 0; font-weight: 600; color: #333; }
        .user-details small { color: #666; }
        .dropdown-item { padding: 12px 20px; color: #333; transition: all 0.3s ease; display: flex; align-items: center; gap: 12px; }
        .dropdown-item:hover { background: rgba(102,126,234,0.1); color: #667eea; }
        .dropdown-item.text-danger:hover { background: rgba(220,53,69,0.1); color: #dc3545; }
        .dropdown-item.text-primary:hover { background: rgba(102,126,234,0.1); color: #667eea; }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle { display: none; }
        .main-navbar.mobile-hidden { display: none; }
        .menu-toggle-btn {
            background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25);
            border-radius: 12px; padding: 12px 10px; cursor: pointer; transition: all 0.3s cubic-bezier(0.4,0,0.2,1);
            backdrop-filter: blur(10px); min-width: 44px; min-height: 44px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .menu-toggle-btn:hover { background: rgba(255,255,255,0.25); border-color: rgba(255,255,255,0.4); transform: translateY(-2px); box-shadow: 0 4px 16px rgba(0,0,0,0.15); }
        .menu-toggle-btn span { display: block; width: 22px; height: 2px; background: white; margin: 2px 0; transition: all 0.3s cubic-bezier(0.4,0,0.2,1); border-radius: 2px; transform-origin: center; }
        .menu-toggle-btn.active span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
        .menu-toggle-btn.active span:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .menu-toggle-btn.active span:nth-child(3) { transform: rotate(-45deg) translate(7px, -6px); }

        /* Main Navbar */
        .main-navbar { background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-top: 1px solid rgba(255,255,255,0.2); position: relative; z-index: 1; transition: all 0.3s ease; }
        .nav-menu { display: flex; align-items: center; justify-content: center; }
        .nav-menu-list { display: flex; list-style: none; margin: 0; padding: 0; gap: 8px; }
        .nav-item { position: relative; }
        .nav-link { display: flex; align-items: center; gap: 8px; padding: 16px 20px; color: rgba(255,255,255,0.9); text-decoration: none; font-weight: 500; transition: all 0.3s ease; border-radius: 8px; position: relative; }
        .nav-link:hover { color: white; background: rgba(255,255,255,0.15); text-decoration: none; transform: translateY(-1px); }
        .nav-item.active .nav-link { color: white; background: rgba(255,255,255,0.2); box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .nav-link i { font-size: 16px; }
        .nav-item.dropdown .dropdown-menu { margin-top: 4px; min-width: 200px; }
        .mobile-logout { display: none; }
        .mobile-logout-btn { display: flex; align-items: center; gap: 8px; padding: 12px 16px; color: #dc3545; text-decoration: none; border-radius: 8px; transition: all 0.3s ease; }
        .mobile-logout-btn:hover { background: rgba(220,53,69,0.1); color: #dc3545; text-decoration: none; }

        /* ===== FOOTER ===== */
        .modern-footer { background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%); color: white; margin-top: auto; position: relative; }
        .footer-content { position: relative; z-index: 2; }
        .footer-main { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; padding: 60px 0 40px; }
        .footer-section { display: flex; flex-direction: column; }
        .footer-logo { display: flex; align-items: center; gap: 12px; margin-bottom: 20px; }
        .footer-logo-text h5 { margin: 0; font-size: 20px; font-weight: 700; }
        .footer-logo-text p { margin: 0; font-size: 14px; opacity: 0.8; }
        .footer-description { font-size: 14px; line-height: 1.6; opacity: 0.9; margin: 0; }
        .footer-title { font-size: 16px; font-weight: 600; margin-bottom: 20px; color: white; position: relative; }
        .footer-title::after { content: ''; position: absolute; bottom: -8px; left: 0; width: 30px; height: 2px; background: linear-gradient(90deg, #667eea, #764ba2); border-radius: 1px; }
        .footer-links, .footer-info, .footer-contact { list-style: none; padding: 0; margin: 0; }
        .footer-links li, .footer-info li, .footer-contact li { margin-bottom: 12px; }
        .footer-links a { color: rgba(255,255,255,0.8); text-decoration: none; transition: all 0.3s ease; display: inline-block; padding: 4px 0; }
        .footer-links a:hover { color: white; transform: translateX(4px); }
        .footer-info li, .footer-contact li { display: flex; align-items: center; gap: 12px; color: rgba(255,255,255,0.8); }
        .footer-info i, .footer-contact i { width: 16px; color: #667eea; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding: 30px 0; }
        .footer-bottom-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; }
        .copyright p { margin: 0; font-size: 14px; color: rgba(255,255,255,0.8); }
        .developer-info { margin-top: 8px !important; font-size: 13px !important; opacity: 0.7 !important; }
        .developer-info i { color: #667eea; margin-right: 6px; }
        .developer-name { color: #667eea; font-weight: 600; font-size: 15px; text-decoration: none; transition: color 0.3s ease; }
        .developer-name:hover { color: #764ba2; }
        .footer-actions { display: flex; gap: 12px; }
        .footer-action-btn { display: flex; align-items: center; gap: 8px; padding: 8px 16px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 6px; color: rgba(255,255,255,0.8); text-decoration: none; font-size: 14px; transition: all 0.3s ease; backdrop-filter: blur(10px); }
        .footer-action-btn:hover { background: rgba(255,255,255,0.2); color: white; text-decoration: none; transform: translateY(-1px); }

        /* ===== MAIN WRAPPER ===== */
        .main-wrapper { flex: 1; background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); position: relative; }
        .main-wrapper::before { content: ''; position: absolute; top:0; left:0; right:0; bottom:0; background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(102,126,234,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>'); opacity: 0.3; }
        .main-content { position: relative; z-index: 2; padding: 30px 0; }
        body { display: flex; flex-direction: column; min-height: 100vh; margin: 0; padding: 0; }
        .modern-footer { margin-top: auto; flex-shrink: 0; }

        /* ===== PAGE COMPONENTS ===== */
        .page-header { margin-bottom: 28px; }
        .page-header h4 { font-size: 22px; font-weight: 700; color: #1e293b; margin: 0 0 4px; }
        .page-header p { color: #64748b; font-size: 14px; margin: 0; }
        .card-modern { border: none; border-radius: 14px; box-shadow: 0 2px 16px rgba(0,0,0,0.07); background: #fff; }
        .card-modern .card-header { background: transparent; border-bottom: 1px solid #f1f5f9; padding: 18px 24px; font-weight: 600; color: #334155; font-size: 15px; border-radius: 14px 14px 0 0; }
        .card-modern .card-body { padding: 24px; }
        .form-control { border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 14px; font-size: 14px; transition: border-color .2s, box-shadow .2s; }
        .form-control:focus { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.25); }
        .btn-primary { background: #667eea; border-color: #667eea; }
        .btn-primary:hover { background: #5a6fd6; border-color: #5a6fd6; }
        .btn-success { background: #059669; border-color: #059669; }
        .btn-success:hover { background: #047857; border-color: #047857; }
        .table { font-size: 14px; }
        .table-hover tbody tr:hover { background: #f8faff; }
        .badge-open { background: #dbeafe; color: #1e40af; }
        .badge-fulfilled { background: #d1fae5; color: #065f46; }
        .badge-partial { background: #fef3c7; color: #92400e; }
        .badge-cancelled { background: #fee2e2; color: #991b1b; }
        .badge-sufficient { background: #d1fae5; color: #065f46; }
        .badge-insufficient { background: #fee2e2; color: #991b1b; }

        /* Select2 Overrides */
        .select2-container--bootstrap4 .select2-selection { border-color: #e2e8f0; border-radius: 8px; min-height: 42px; }
        .select2-container--bootstrap4 .select2-selection--single { height: 42px; line-height: 42px; padding: 0 14px; display: flex; align-items: center; }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__rendered { line-height: 42px; color: #495057; padding-left: 0; padding-right: 20px; }
        .select2-container--bootstrap4 .select2-selection--single .select2-selection__arrow { height: 40px; top: 1px; }
        .select2-container--bootstrap4.select2-container--focus .select2-selection { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.25); outline: none; }
        .select2-dropdown { border-color: #e2e8f0; border-radius: 8px; box-shadow: 0 8px 24px rgba(0,0,0,0.12); }
        .select2-results__option--highlighted[aria-selected] { background-color: #667eea; }

        /* Responsive */
        @media (max-width: 768px) {
            .nav-content { flex-direction: column; gap: 20px; }
            .logo-section { order: 1; }
            .nav-actions { order: 2; width: 100%; justify-content: space-between; align-items: center; gap: 12px; }
            .quick-actions { display: none !important; }
            .mobile-quick-actions { display: flex !important; flex-wrap: wrap; gap: 6px; margin-right: 8px; }
            .mobile-menu-toggle { display: block; margin-left: 8px; }
            .main-navbar { display: none !important; }
            .main-navbar:not(.mobile-hidden) { display: block !important; }
            .user-menu .dropdown-menu { position: fixed; top: auto; bottom: 0; left: 0; right: 0; width: 100%; margin: 0; border-radius: 12px 12px 0 0; max-height: 80vh; overflow-y: auto; display: none; z-index: 9999; background: white; box-shadow: 0 -4px 20px rgba(0,0,0,0.15); }
            .user-menu .dropdown.show .dropdown-menu { display: block; }
            .mobile-logout { display: block; width: 100%; padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.1); }
            .footer-main { grid-template-columns: 1fr; gap: 30px; padding: 40px 0 30px; }
            .footer-bottom-content { flex-direction: column; text-align: center; }
            .footer-actions { justify-content: center; }
        }
    </style>

    <script>
        toastr = window.toastr || {};
        toastr.options = { "closeButton": true, "progressBar": true, "positionClass": "toast-top-right", "timeOut": "5000" };
        window.showSuccess = function(m, t='Başarılı!') { toastr.success(m, t); };
        window.showError = function(m, t='Hata!') { toastr.error(m, t); };
        $(document).ajaxError(function(event, xhr) {
            let msg = 'Bir hata oluştu!';
            if (xhr.status===403) msg='Bu işlem için yetkiniz yok!';
            else if (xhr.status===404) msg='Kaynak bulunamadı!';
            else if (xhr.status===422) msg='Form verilerinde hata!';
            else if (xhr.status===500) msg='Sunucu hatası!';
        });
    </script>
</head>

<body>

<!-- Modern Header -->
<header class="modern-header">
    <nav class="top-navbar">
        <div class="container-fluid">
            <div class="nav-content">

                <!-- Logo -->
                <div class="logo-section">
                    <a href="{{ route('system.selection.index') }}" class="logo-link">
                        <img src="{{ asset('assets/images/kale-seeklogo.png') }}" alt="Kalefrit Logo" class="logo-image">
                    </a>
                </div>

                <!-- Right Side -->
                <div class="nav-actions">

                    <!-- User Menu -->
                    <div class="user-menu">
                        <div class="dropdown">
                            <button class="user-dropdown-toggle" type="button" data-toggle="dropdown">
                                <div class="user-avatar"><i class="fas fa-user"></i></div>
                                <div class="user-info">
                                    <span class="user-name">{{ auth()->user() ? auth()->user()->name : 'Misafir' }}</span>
                                    <span class="user-role">Sistem Yönetimi</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="dropdown-header">
                                    <div class="user-avatar-large"><i class="fas fa-user"></i></div>
                                    <div class="user-details">
                                        <h6>{{ auth()->user() ? auth()->user()->name : 'Misafir' }}</h6>
                                        <small>{{ auth()->user() ? auth()->user()->email : '' }}</small>
                                    </div>
                                </div>
                                <a class="dropdown-item text-primary" href="{{ route('system.selection.change') }}">
                                    <i class="fas fa-exchange-alt"></i> Sistem Değiştir
                                </a>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form-orders').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                                </a>
                                <form id="logout-form-orders" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Toggle -->
                    <div class="mobile-menu-toggle d-lg-none">
                        <button class="menu-toggle-btn" type="button" title="Menüyü Aç/Kapat">
                            <span></span><span></span><span></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Navbar -->
    <nav class="main-navbar">
        <div class="container-fluid">
            <div class="nav-menu">
                <ul class="nav-menu-list">
                    <li class="nav-item {{ request()->is('siparis-karsilama') ? 'active' : '' }}">
                        <a href="{{ route('orders.home') }}" class="nav-link">
                            <i class="fas fa-home"></i><span>Anasayfa</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('siparis-karsilama/frit*') ? 'active' : '' }}">
                        <a href="{{ route('orders.frit.index') }}" class="nav-link">
                            <i class="fas fa-fire"></i><span>Frit Siparişleri</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('orders.granilya.index') }}" class="nav-link">
                            <i class="fas fa-layer-group"></i><span>Granilya Siparişleri</span>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('siparis-karsilama/toplu-yonetim*') ? 'active' : '' }}">
                        <a href="{{ route('orders.bulk.index') }}" class="nav-link">
                            <i class="fas fa-tasks"></i> Toplu Yönetim
                        </a>
                    </li>
                </ul>

                <!-- Mobile Logout -->
                <div class="mobile-logout d-lg-none">
                    <a href="{{ route('system.selection.change') }}" class="mobile-logout-btn text-primary" style="margin-bottom:10px;">
                        <i class="fas fa-exchange-alt"></i> Sistem Değiştir
                    </a>
                    <a href="{{ route('logout') }}" class="mobile-logout-btn"
                       onclick="event.preventDefault(); document.getElementById('logout-form-orders').submit();">
                        <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="main-wrapper">
    <main class="main-content container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" style="border:none;border-radius:10px;" role="alert">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" style="border:none;border-radius:10px;" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- Modern Footer -->
<footer class="modern-footer">
    <div class="footer-content">
        <div class="container-fluid">
            <div class="footer-main">
                <div class="footer-section">
                    <div class="footer-logo">
                        <div class="footer-logo-text">
                            <h5>Kalefrit</h5>
                            <p>Sipariş Karşılama Sistemi</p>
                        </div>
                    </div>
                    <p class="footer-description">Frit ve Granilya siparişlerini takip edin, stok durumunu anlık görün.</p>
                </div>
                <div class="footer-section">
                    <h6 class="footer-title">Hızlı Erişim</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('orders.home') }}">Ana Sayfa</a></li>
                        <li><a href="{{ route('orders.frit.index') }}">Frit Siparişleri</a></li>
                        <li><a href="{{ route('orders.granilya.index') }}">Granilya Siparişleri</a></li>
                        <li><a href="{{ route('orders.bulk.index') }}">Toplu Yönetim</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h6 class="footer-title">Sistem Bilgileri</h6>
                    <ul class="footer-info">
                        <li><i class="fas fa-clock"></i><span>Son Güncelleme: {{ date('d.m.Y H:i') }}</span></li>
                        <li><i class="fas fa-user"></i><span>Aktif Kullanıcı: {{ auth()->user() ? auth()->user()->name : 'Misafir' }}</span></li>
                        <li><i class="fas fa-shield-alt"></i><span>Güvenli Bağlantı</span></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h6 class="footer-title">İletişim</h6>
                    <ul class="footer-contact">
                        <li><i class="fas fa-envelope"></i><span>bugra.gormus@hotmail.com</span></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <div class="copyright">
                        <p>&copy; {{ date('Y') }} Kalefrit — Sipariş Karşılama Sistemi. Tüm hakları saklıdır.</p>
                        <p class="developer-info"><i class="fas fa-code"></i><span class="developer-name">Buğra GÖRMÜŞ</span> tarafından hazırlanmıştır</p>
                    </div>
                    <div class="footer-actions">
                        <a href="{{ route('system.selection.change') }}" class="footer-action-btn">
                            <i class="fas fa-exchange-alt"></i>
                            <span>Sistem Değiştir</span>
                        </a>
                        <a href="{{ route('about') }}" class="footer-action-btn">
                            <i class="fas fa-info-circle"></i>
                            <span>Hakkında</span>
                        </a>
                        <a href="{{ route('manual') }}" class="footer-action-btn">
                            <i class="fas fa-book"></i>
                            <span>Kullanım Kılavuzu</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
<script src="{{ asset('assets/js/waves.js') }}"></script>
<script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>

<!-- DataTables -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

<!-- Sweet Alert -->
<script src="{{ asset('assets/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- App js -->
<script src="{{ asset('assets/js/app.js') }}"></script>

@toastr_js
@toastr_render

@yield('scripts')

<script>
// Mobile Menu
document.addEventListener('DOMContentLoaded', function() {
    const menuToggleBtn = document.querySelector('.menu-toggle-btn');
    const mainNavbar    = document.querySelector('.main-navbar');
    if (menuToggleBtn && mainNavbar) {
        if (window.innerWidth <= 768) mainNavbar.classList.add('mobile-hidden');
        menuToggleBtn.addEventListener('click', function() {
            mainNavbar.classList.toggle('mobile-hidden');
            menuToggleBtn.classList.toggle('active');
        });
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                mainNavbar.classList.remove('mobile-hidden');
                menuToggleBtn.classList.remove('active');
            } else {
                mainNavbar.classList.add('mobile-hidden');
                menuToggleBtn.classList.remove('active');
            }
        });
    }

    // User Dropdown
    const toggle   = document.querySelector('.user-dropdown-toggle');
    const dropdown = document.querySelector('.user-menu .dropdown');
    if (toggle && dropdown) {
        toggle.addEventListener('click', function(e) { e.preventDefault(); e.stopPropagation(); dropdown.classList.toggle('show'); });
        document.addEventListener('click', function(e) {
            if (!toggle.contains(e.target) && !dropdown.querySelector('.dropdown-menu').contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
        document.addEventListener('keydown', function(e) { if (e.key === 'Escape') dropdown.classList.remove('show'); });
    }

    // Select2 — tüm .select2-search sınıflı elementler
    if (typeof $.fn.select2 !== 'undefined') {
        $('.select2-search').select2({
            theme: 'bootstrap4',
            language: 'tr',
            placeholder: $(this).data('placeholder') || '— Seçin —',
            allowClear: true,
            width: '100%'
        });
    }
    // Universal Delete Button Handler (System-Wide)
    // Works for both .btn-delete-order, .btn-cancel-order and #btnCancelOrder
    $(document).on('click', '.btn-delete-order, .btn-cancel-order, #btnCancelOrder', function (e) {
        e.preventDefault();
        const $btn = $(this);
        const url = $btn.data('url');
        const id  = $btn.data('id') || '#';
        
        if (!url) {
            console.error('[DELETE] URL not found on button!', $btn);
            return;
        }

        console.log('[DELETE] Global Triggered. ID:', id, 'URL:', url);

        Swal.fire({ 
            title: 'Emin misiniz?', 
            text: 'Bu işlem kalıcı olarak silinecek ve geri alınamaz.', 
            type: 'warning', 
            showCancelButton: true,
            confirmButtonColor: '#dc3545', 
            cancelButtonText: 'Vazgeç', 
            confirmButtonText: 'Sil'
        }).then(function(result) {
            if (result.value || result.isConfirmed) {
                console.log('[DELETE] Confirmed. Creating dynamic form...');
                try {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.style.display = 'none';

                    const token = $('meta[name="csrf-token"]').attr('content');
                    if (!token) {
                        console.error('[DELETE] CSRF Token is missing in layouts!');
                    }

                    const tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.name = '_token';
                    tokenInput.value = token;
                    form.appendChild(tokenInput);

                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    console.log('[DELETE] Submitting form to:', url);
                    form.submit();
                } catch (err) {
                    console.error('[DELETE] Error during submission:', err);
                    Swal.fire('Hata', 'İşlem sırasında bir hata oluştu.', 'error');
                }
            }
        });
    });
});
</script>
</body>
</html>
