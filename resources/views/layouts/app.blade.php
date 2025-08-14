<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <title>Kalefrit - Stok Yönetim Sistemi</title>
    <meta content="Kalefrit - Stok Yönetim Sistemi" name="description" />
    <meta content="Buğra GÖRMÜŞ" name="author" />
    <meta content="Admin Dashboard" name="description" />
    <meta content="ThemeDesign" name="author" />
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

    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Sweet Alert -->
    <link href="{{ asset('assets/plugins/sweet-alert2/sweetalert2.css') }}" rel="stylesheet" type="text/css">

    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />

    @toastr_css

    @yield('styles')
    <style>
        .mob-logout {
            display: none!important;
        }

        @media only screen and (max-width: 768px) {
            .mob-logout {
                display: block!important;
            }
        }

        /* Breadcrumb Styles */
        .breadcrumb-nav {
            margin-left: 20px;
        }
        
        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
        }
        
        .breadcrumb-item {
            font-size: 14px;
        }
        
        .breadcrumb-item a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .breadcrumb-item a:hover {
            color: #fff;
        }
        
        .breadcrumb-item.active {
            color: #fff;
            font-weight: 500;
        }
        
        .breadcrumb-item + .breadcrumb-item::before {
            content: ">";
            color: rgba(255, 255, 255, 0.6);
            margin: 0 8px;
        }

        /* Custom Toast Styles */
        .toast-success {
            background-color: #28a745 !important;
        }
        
        .toast-error {
            background-color: #dc3545 !important;
        }
        
        .toast-warning {
            background-color: #ffc107 !important;
        }
        
        .toast-info {
            background-color: #17a2b8 !important;
        }

        /* Progress Indicator Styles */
        .progress-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }

        .progress-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .progress-spinner {
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .progress-text {
            font-size: 16px;
            color: #333;
            margin-bottom: 10px;
        }

        .progress-subtext {
            font-size: 14px;
            color: #666;
        }

        .progress-bar-container {
            width: 300px;
            height: 6px;
            background: #f3f3f3;
            border-radius: 3px;
            overflow: hidden;
            margin: 15px auto 0;
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #007bff, #0056b3);
            width: 0%;
            transition: width 0.3s ease;
        }

        /* ===== MODERN HEADER STYLES ===== */
        .modern-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 1000;
        }

        .modern-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Top Navbar */
        .top-navbar {
            padding: 15px 0;
            position: relative;
            z-index: 2;
        }

        .nav-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        /* Logo Section */
        .logo-section {
            flex-shrink: 0;
        }

        .logo-link {
            text-decoration: none;
            color: white;
            display: flex;
            align-items: center;
        }

        .logo-image {
            height: 40px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        }



        /* Nav Actions */
        .nav-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        /* Quick Actions */
        .quick-actions {
            display: flex;
            gap: 12px;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .action-btn.primary {
            background: linear-gradient(135deg, #4CAF50, #45a049);
        }

        .action-btn.secondary {
            background: linear-gradient(135deg, #2196F3, #1976D2);
        }

        .action-btn.tertiary {
            background: linear-gradient(135deg, #9C27B0, #7B1FA2);
        }

        .action-btn.quaternary {
            background: linear-gradient(135deg, #FF9800, #F57C00);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            color: white;
            text-decoration: none;
        }

        /* User Menu */
        .user-menu {
            position: relative;
        }

        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            -webkit-tap-highlight-color: transparent;
            touch-action: manipulation;
            user-select: none;
        }

        .user-dropdown-toggle:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
            color: white;
            text-decoration: none;
        }

        .user-avatar {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: white;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            text-align: left;
        }

        .user-name {
            font-weight: 600;
            font-size: 14px;
            line-height: 1;
        }

        .user-role {
            font-size: 12px;
            opacity: 0.8;
            margin-top: 2px;
        }

        .dropdown-arrow {
            font-size: 12px;
            transition: transform 0.3s ease;
        }

        .dropdown.show .dropdown-arrow {
            transform: rotate(180deg);
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.95);
            padding: 8px 0;
            margin-top: 8px;
            position: absolute;
            top: 100%;
            right: 0;
            min-width: 220px;
            z-index: 1001;
            display: none;
        }

        .dropdown.show .dropdown-menu {
            display: block;
        }

        /* User Menu Specific Dropdown */
        .user-menu .dropdown-menu {
            margin-top: 8px;
            right: 0;
            left: auto;
        }

        /* Prevent dropdown from going off-screen */
        @media (max-width: 1200px) {
            .user-menu .dropdown-menu {
                right: 0;
                left: auto;
            }
        }

        @media (max-width: 576px) {
            .user-menu .dropdown-menu {
                right: 10px;
                left: 10px;
                width: calc(100vw - 20px);
                min-width: auto;
            }
        }

        /* Ensure dropdown is above other elements */
        .user-menu {
            position: relative;
            z-index: 1002;
        }

        .dropdown-header {
            padding: 16px 20px 12px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            margin-bottom: 8px;
        }

        .user-avatar-large {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            margin-bottom: 12px;
        }

        .user-details h6 {
            margin: 0;
            font-weight: 600;
            color: #333;
        }

        .user-details small {
            color: #666;
        }

        .dropdown-item {
            padding: 12px 20px;
            color: #333;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .dropdown-item:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .dropdown-item.text-danger:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
        }

        /* Mobile Menu State */
        .main-navbar.mobile-hidden {
            display: none;
        }

        .menu-toggle-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .menu-toggle-btn span {
            display: block;
            width: 24px;
            height: 2px;
            background: white;
            margin: 4px 0;
            transition: all 0.3s ease;
            border-radius: 1px;
        }

        /* Main Navbar */
        .main-navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-menu-list {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
            gap: 8px;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 16px 20px;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
            position: relative;
        }

        .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.15);
            text-decoration: none;
            transform: translateY(-1px);
        }

        .nav-item.active .nav-link {
            color: white;
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .nav-link i {
            font-size: 16px;
        }

        /* Dropdown in Main Nav */
        .nav-item.dropdown .dropdown-menu {
            margin-top: 4px;
            min-width: 200px;
        }

        .nav-item.dropdown .dropdown-item {
            padding: 12px 16px;
        }

        /* Mobile Logout */
        .mobile-logout {
            display: none;
        }

        .mobile-logout-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 16px;
            color: #dc3545;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .mobile-logout-btn:hover {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
            text-decoration: none;
        }

        /* ===== MODERN FOOTER STYLES ===== */
        .modern-footer {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            margin-top: auto;
            position: relative;
        }

        .modern-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="rgba(255,255,255,0.03)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.5;
        }

        .footer-content {
            position: relative;
            z-index: 2;
        }

        .footer-main {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
            padding: 60px 0 40px;
        }

        .footer-section {
            display: flex;
            flex-direction: column;
        }

        /* Footer Logo */
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .footer-logo-img {
            height: 40px;
            width: auto;
            filter: brightness(0) invert(1);
        }

        .footer-logo-text h5 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
        }

        .footer-logo-text p {
            margin: 0;
            font-size: 14px;
            opacity: 0.8;
        }

        .footer-description {
            font-size: 14px;
            line-height: 1.6;
            opacity: 0.9;
            margin: 0;
        }

        /* Footer Titles */
        .footer-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 20px;
            color: white;
            position: relative;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: -8px;
            left: 0;
            width: 30px;
            height: 2px;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 1px;
        }

        /* Footer Lists */
        .footer-links,
        .footer-info,
        .footer-contact {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li,
        .footer-info li,
        .footer-contact li {
            margin-bottom: 12px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            padding: 4px 0;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(4px);
        }

        .footer-info li,
        .footer-contact li {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255, 255, 255, 0.8);
        }

        .footer-info i,
        .footer-contact i {
            width: 16px;
            color: #667eea;
        }

        /* Footer Bottom */
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px 0;
        }

        .footer-bottom-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .copyright p {
            margin: 0;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.8);
        }

        .developer-info {
            margin-top: 8px !important;
            font-size: 13px !important;
            opacity: 0.7 !important;
        }

        .developer-info i {
            color: #667eea;
            margin-right: 6px;
        }

        .developer-name {
            color: #667eea;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .developer-name:hover {
            color: #764ba2;
        }

        /* Footer Actions */
        .footer-actions {
            display: flex;
            gap: 12px;
        }

        .footer-action-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .footer-action-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            transform: translateY(-1px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-content {
                flex-direction: column;
                gap: 20px;
            }

            .logo-section {
                order: 1;
            }

            .nav-actions {
                order: 2;
                width: 100%;
                justify-content: space-between;
            }

            .quick-actions {
                display: none !important;
            }

            .mobile-menu-toggle {
                display: block;
            }

            /* Hide main navbar by default on mobile */
            .main-navbar {
                display: none !important;
            }

            /* Show when not hidden */
            .main-navbar:not(.mobile-hidden) {
                display: block !important;
            }

            /* Mobile User Menu Dropdown */
            .user-menu .dropdown-menu {
                position: fixed;
                top: auto;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                margin: 0;
                border-radius: 12px 12px 0 0;
                max-height: 80vh;
                overflow-y: auto;
                display: none;
                z-index: 9999;
                background: white;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
            }

            .user-menu .dropdown.show .dropdown-menu {
                display: block;
            }

            /* Improve mobile user dropdown toggle */
            .user-dropdown-toggle {
                position: relative;
                z-index: 1000;
            }

            /* Mobile dropdown backdrop */
            .user-menu .dropdown.show::before {
                content: '';
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 9998;
            }

            /* Ensure mobile menu is properly hidden by default */
            .main-navbar.mobile-hidden {
                display: none !important;
            }

            /* Mobile menu animation */
            .main-navbar {
                transform: translateY(-100%);
                transition: all 0.3s ease;
                position: relative;
                z-index: 1000;
                max-height: 0;
                overflow: hidden;
            }

            .main-navbar:not(.mobile-hidden) {
                transform: translateY(0);
                max-height: 500px; /* Adjust based on your menu height */
            }

            .nav-menu-list {
                flex-direction: column;
                width: 100%;
                gap: 0;
            }

            .nav-item {
                width: 100%;
            }

            .nav-link {
                border-radius: 0;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .mobile-logout {
                display: block;
                width: 100%;
                padding: 16px 20px;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .footer-main {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 40px 0 30px;
            }

            .footer-bottom-content {
                flex-direction: column;
                text-align: center;
            }

            .footer-actions {
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .logo-image {
                height: 35px;
            }

            .action-btn {
                padding: 8px 12px;
                font-size: 13px;
            }

            .user-dropdown-toggle {
                padding: 8px 12px;
            }

            .user-name {
                font-size: 13px;
            }

            .user-role {
                font-size: 11px;
            }
        }

        /* ===== MAIN WRAPPER STYLES ===== */
        .main-wrapper {
            flex: 1;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            position: relative;
        }

        .main-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="dots" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="1" fill="rgba(102,126,234,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23dots)"/></svg>');
            opacity: 0.3;
        }

        .main-content {
            position: relative;
            z-index: 2;
            padding: 30px 0;
        }

        /* Ensure footer stays at bottom */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .modern-footer {
            margin-top: auto;
            flex-shrink: 0;
        }
    </style>

    <script>
        // Toastr Configuration
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        // Custom Toast Helper Functions
        window.showSuccess = function(message, title = 'Başarılı!') {
            toastr.success(message, title);
        };

        window.showError = function(message, title = 'Hata!') {
            toastr.error(message, title);
        };

        window.showWarning = function(message, title = 'Uyarı!') {
            toastr.warning(message, title);
        };

        window.showInfo = function(message, title = 'Bilgi') {
            toastr.info(message, title);
        };

        // AJAX Error Handler
        $(document).ajaxError(function(event, xhr, settings, error) {
            let message = 'Bir hata oluştu!';
            
            if (xhr.status === 403) {
                message = 'Bu işlem için yetkiniz yok!';
            } else if (xhr.status === 404) {
                message = 'İstenen kaynak bulunamadı!';
            } else if (xhr.status === 422) {
                message = 'Form verilerinde hata var!';
            } else if (xhr.status === 500) {
                message = 'Sunucu hatası oluştu!';
            }
            
            showError(message);
        });

        // Progress Indicator Functions
        window.showProgress = function(message = 'İşlem yapılıyor...', subtext = 'Lütfen bekleyin') {
            if (!$('#progressOverlay').length) {
                $('body').append(`
                    <div id="progressOverlay" class="progress-overlay">
                        <div class="progress-container">
                            <div class="progress-spinner"></div>
                            <div class="progress-text">${message}</div>
                            <div class="progress-subtext">${subtext}</div>
                            <div class="progress-bar-container">
                                <div class="progress-bar"></div>
                            </div>
                        </div>
                    </div>
                `);
            }
            $('#progressOverlay').fadeIn();
        };

        window.hideProgress = function() {
            $('#progressOverlay').fadeOut();
        };

        window.updateProgress = function(percent, message = null, subtext = null) {
            $('.progress-bar').css('width', percent + '%');
            if (message) {
                $('.progress-text').text(message);
            }
            if (subtext) {
                $('.progress-subtext').text(subtext);
            }
        };

        // Confirmation Dialog Functions
        window.confirmAction = function(message, title = 'Onay', confirmText = 'Evet', cancelText = 'Hayır') {
            return new Promise((resolve) => {
                Swal.fire({
                    title: title,
                    text: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: confirmText,
                    cancelButtonText: cancelText,
                    reverseButtons: true
                }).then((result) => {
                    resolve(result.isConfirmed);
                });
            });
        };

        window.confirmDelete = function(message = 'Bu öğeyi silmek istediğinizden emin misiniz?') {
            return confirmAction(message, 'Silme Onayı', 'Evet, Sil', 'İptal');
        };

        window.confirmUpdate = function(message = 'Bu değişiklikleri kaydetmek istediğinizden emin misiniz?') {
            return confirmAction(message, 'Güncelleme Onayı', 'Evet, Kaydet', 'İptal');
        };

        window.showSuccessDialog = function(message, title = 'Başarılı!') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'success',
                confirmButtonText: 'Tamam'
            });
        };

        window.showErrorDialog = function(message, title = 'Hata!') {
            Swal.fire({
                title: title,
                text: message,
                icon: 'error',
                confirmButtonText: 'Tamam'
            });
        };
    </script>
</head>

<body>

<!-- Modern Header with Glassmorphism -->
<header class="modern-header">
    <!-- Top Navigation Bar -->
    <nav class="top-navbar">
        <div class="container-fluid">
            <div class="nav-content">
                
                <!-- Logo Section -->
                <div class="logo-section">
                    <a href="{{ route('home') }}" class="logo-link">
                        <img src="{{ asset('assets/images/kale-seeklogo.png') }}" alt="Kalefrit Logo" class="logo-image">
                    </a>
                </div>



                <!-- Data Integrity Warning -->
                @if(session('data_integrity_warning'))
                    <div class="data-integrity-warning">
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Veri Bütünlüğü Uyarısı:</strong> 
                            {{ session('data_integrity_warning.message') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Right Side Actions -->
                <div class="nav-actions">
                    
                    <!-- Quick Actions -->
                    <div class="quick-actions d-none d-lg-flex">
                        <a href="{{ route('barcode.create') }}" class="action-btn primary">
                            <i class="mdi mdi-plus"></i>
                            <span>Barkod Oluştur</span>
                        </a>
                        <a href="{{ route('barcode.qr-read') }}" class="action-btn secondary">
                            <i class="fas fa-qrcode"></i>
                            <span>Barkod Sorgula</span>
                        </a>
                        <a href="{{ route('barcode.merge') }}" class="action-btn tertiary">
                            <i class="fas fa-link"></i>
                            <span>Barkod Birleştir</span>
                        </a>
                        <a href="{{ route('barcode.printPage.layout') }}" class="action-btn quaternary">
                            <i class="fas fa-print"></i>
                            <span>Barkod Yazdır</span>
                        </a>
                    </div>

                    <!-- User Menu -->
                    <div class="user-menu">
                        <div class="dropdown">
                            <button class="user-dropdown-toggle" type="button" data-toggle="dropdown">
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="user-info">
                                    <span class="user-name">{{ auth()->user() ? auth()->user()->name : 'Misafir' }}</span>
                                    <span class="user-role">Kullanıcı</span>
                                </div>
                                <i class="fas fa-chevron-down dropdown-arrow"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <div class="dropdown-header">
                                    <div class="user-avatar-large">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div class="user-details">
                                        <h6>{{ auth()->user() ? auth()->user()->name : 'Misafir' }}</h6>
                                        <small>{{ auth()->user() ? auth()->user()->email : 'misafir@example.com' }}</small>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ auth()->user() ? route('user.edit', ['user' => auth()->id()]) : '#' }}">
                                    <i class="fas fa-user-edit"></i> Profili Düzenle
                                </a>
                                <a class="dropdown-item" href="{{ route('settings.show') }}">
                                    <i class="fas fa-cog"></i> Ayarlar
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Mobile Menu Toggle -->
                    <div class="mobile-menu-toggle d-lg-none">
                        <button class="menu-toggle-btn" type="button">
                            <span></span>
                            <span></span>
                            <span></span>
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </nav>

    <!-- Main Navigation Menu -->
    <nav class="main-navbar">
        <div class="container-fluid">
            <div class="nav-menu">
                
                <ul class="nav-menu-list">

                    <li class="nav-item {{ request()->is('anasayfa') ? 'active' : ''}}">
                        <a href="{{ route('home') }}" class="nav-link">
                            <i class="fas fa-home"></i>
                            <span>Ana Sayfa</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is('kullanici/*') ? 'active' : ''}}">
                        <a href="{{ route('user.index') }}" class="nav-link">
                            <i class="fas fa-users"></i>
                            <span>Kullanıcı Yönetimi</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is('barkod/*') ? 'active' : ''}}">
                        <a href="{{ route('barcode.index') }}" class="nav-link">
                            <i class="fas fa-qrcode"></i>
                            <span>Barkod Yönetimi</span>
                        </a>
                    </li>

                    @if(auth()->user() && auth()->user()->hasPermission(\App\Models\Permission::LAB_PROCESSES))
                    <li class="nav-item {{ request()->is('laboratuvar/*') ? 'active' : ''}}">
                        <a href="{{ route('laboratory.dashboard') }}" class="nav-link">
                            <i class="fas fa-flask"></i>
                            <span>Laboratuvar</span>
                        </a>
                    </li>
                    @endif

                    <li class="nav-item {{ request()->is('stok/*') ? 'active' : ''}}">
                        <a href="{{ route('stock.index') }}" class="nav-link">
                            <i class="fas fa-boxes"></i>
                            <span>Stok Yönetimi</span>
                        </a>
                    </li>

                    <li class="nav-item {{ request()->is('warehouse/*') ? 'active' : ''}}">
                        <a href="{{ route('warehouse.index') }}" class="nav-link">
                            <i class="fas fa-warehouse"></i>
                            <span>Depo Yönetimi</span>
                        </a>
                    </li>

                    <li class="nav-item dropdown {{ request()->is('kiln/*') || request()->is('quantity/*') || request()->is('departman/*') ? 'active' : ''}}">
                        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                            <i class="fas fa-archive"></i>
                            <span>Diğer</span>
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('company.index') }}">
                                <i class="fas fa-building"></i> Firma Yönetimi
                            </a>
                            <a class="dropdown-item" href="{{ route('kiln.index') }}">
                                <i class="fas fa-fire"></i> Fırın Yönetimi
                            </a>
                            <a class="dropdown-item" href="{{ route('quantity.index') }}">
                                <i class="fas fa-calculator"></i> Adet Yönetimi
                            </a>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}" class="nav-link">
                            <i class="fas fa-chart-bar"></i>
                            <span>Günlük Rapor</span>
                        </a>
                    </li>

                </ul>

                <!-- Mobile Logout -->
                <div class="mobile-logout d-lg-none">
                    <a href="{{ route('logout') }}" class="mobile-logout-btn"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> Çıkış Yap
                    </a>
                </div>

            </div>
        </div>
    </nav>

</header>
<!-- header-bg -->

<div class="main-wrapper">
    <main class="main-content">
        @yield('content')
    </main>
</div>
<!-- end wrapper -->


<!-- Modern Footer -->
<footer class="modern-footer">
    <div class="footer-content">
        <div class="container-fluid">
            <div class="footer-main">
                
                <!-- Company Info -->
                <div class="footer-section">
                    <div class="footer-logo">
                        <div class="footer-logo-text">
                            <h5>Kalefrit</h5>
                            <p>Stok Yönetim Sistemi</p>
                        </div>
                    </div>
                    <p class="footer-description">
                        Modern ve kullanıcı dostu stok yönetim çözümü ile işletmenizi dijitalleştirin.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="footer-section">
                    <h6 class="footer-title">Hızlı Erişim</h6>
                    <ul class="footer-links">
                        <li><a href="{{ route('home') }}">Ana Sayfa</a></li>
                        <li><a href="{{ route('barcode.index') }}">Barkod Yönetimi</a></li>
                        <li><a href="{{ route('stock.index') }}">Stok Yönetimi</a></li>
                        <li><a href="{{ route('user.index') }}">Kullanıcı Yönetimi</a></li>
                    </ul>
                </div>

                <!-- System Info -->
                <div class="footer-section">
                    <h6 class="footer-title">Sistem Bilgileri</h6>
                    <ul class="footer-info">
                        <li>
                            <i class="fas fa-clock"></i>
                            <span>Son Güncelleme: {{ date('d.m.Y H:i') }}</span>
                        </li>
                        <li>
                            <i class="fas fa-user"></i>
                            <span>Aktif Kullanıcı: {{ auth()->user() ? auth()->user()->name : 'Misafir' }}</span>
                        </li>
                        <li>
                            <i class="fas fa-shield-alt"></i>
                            <span>Güvenli Bağlantı</span>
                        </li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div class="footer-section">
                    <h6 class="footer-title">İletişim</h6>
                    <ul class="footer-contact">
                        <li>
                            <i class="fas fa-envelope"></i>
                            <span>onurcansahin@kale.com.tr</span>
                        </li>
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Dijital Dönüşüm Ofisi</span>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <div class="copyright">
                        <p>&copy; {{ date('Y') }} Kalefrit - Stok Yönetim Sistemi. Tüm hakları saklıdır.</p>
                        <p class="developer-info">
                            <i class="fas fa-code"></i>
                            Dijital Dönüşüm Ofisi'nin Katkılarıyla 
                            <span class="developer-name">Buğra GÖRMÜŞ</span> tarafından hazırlandı
                        </p>
                    </div>
                    <div class="footer-actions">
                        <a href="{{ route('settings.show') }}" class="footer-action-btn">
                            <i class="fas fa-cog"></i>
                            <span>Ayarlar</span>
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

<!-- jQuery  -->
<script src="{{ asset('assets/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
<script src="{{ asset('assets/js/waves.js') }}"></script>
<script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>

<!--Morris Chart-->
<script src="{{ asset('assets/plugins/morris/morris.min.js') }}"></script>
<script src="{{ asset('assets/plugins/raphael/raphael.min.js') }}"></script>

<!-- dashboard js -->
<script src="{{ asset('assets/pages/dashboard.int.js') }}"></script>

<!-- Bootstrap inputmask js -->
<script src="{{ asset('assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js') }}"></script>

<!-- Required datatable js -->
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<!-- Buttons examples -->
<script src="{{ asset('assets/plugins/datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/jszip.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/pdfmake.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/vfs_fonts.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/buttons.colVis.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ asset('assets/plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/responsive.bootstrap4.min.js') }}"></script>

<!-- Datatable init js -->
<script src="{{ asset('assets/pages/datatables.init.js') }}"></script>

<!-- Sweet-Alert  -->
<script src="{{ asset('assets/plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
<script src="{{ asset('assets/pages/sweet-alert.init.js') }}"></script>

<!-- App js -->
<script src="{{ asset('assets/js/app.js') }}"></script>

@toastr_js
@toastr_render

@yield('scripts')

<script>
// Mobile Menu Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const menuToggleBtn = document.querySelector('.menu-toggle-btn');
    const mainNavbar = document.querySelector('.main-navbar');
    const menuToggleSpans = document.querySelectorAll('.menu-toggle-btn span');
    
    if (menuToggleBtn && mainNavbar) {
        // Ensure menu is hidden by default on mobile
        if (window.innerWidth <= 768) {
            mainNavbar.classList.add('mobile-hidden');
        }
        
        menuToggleBtn.addEventListener('click', function() {
            // Toggle menu visibility
            mainNavbar.classList.toggle('mobile-hidden');
            
            // Toggle hamburger animation
            menuToggleSpans.forEach((span, index) => {
                if (index === 0) {
                    span.style.transform = mainNavbar.classList.contains('mobile-hidden') ? 'rotate(0deg)' : 'rotate(45deg) translate(5px, 5px)';
                } else if (index === 1) {
                    span.style.opacity = mainNavbar.classList.contains('mobile-hidden') ? '1' : '0';
                } else if (index === 2) {
                    span.style.transform = mainNavbar.classList.contains('mobile-hidden') ? 'rotate(0deg)' : 'rotate(-45deg) translate(7px, -6px)';
                }
            });
        });
        
        // Menu will only close when toggle button is clicked or page is resized
        // This prevents accidental closing when clicking on navigation elements
        
        // Close menu when window is resized to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                mainNavbar.classList.remove('mobile-hidden');
                // Reset hamburger animation
                menuToggleSpans.forEach((span, index) => {
                    if (index === 0) {
                        span.style.transform = 'rotate(0deg)';
                    } else if (index === 1) {
                        span.style.opacity = '1';
                    } else if (index === 2) {
                        span.style.transform = 'rotate(0deg)';
                    }
                });
            } else {
                // Ensure menu is hidden on mobile resize
                mainNavbar.classList.add('mobile-hidden');
                // Reset hamburger animation
                menuToggleSpans.forEach((span, index) => {
                    if (index === 0) {
                        span.style.transform = 'rotate(0deg)';
                    } else if (index === 1) {
                        span.style.opacity = '1';
                    } else if (index === 2) {
                        span.style.transform = 'rotate(0deg)';
                    }
                });
            }
        });
    }
    
    // Improve mobile user dropdown behavior
    const userDropdownToggle = document.querySelector('.user-dropdown-toggle');
    const userDropdown = document.querySelector('.user-menu .dropdown');
    
    if (userDropdownToggle && userDropdown) {
        // Close dropdown when clicking outside on mobile
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768) {
                if (!userDropdownToggle.contains(event.target) && !userDropdown.querySelector('.dropdown-menu').contains(event.target)) {
                    userDropdown.classList.remove('show');
                }
            }
        });
        
        // Close dropdown when clicking backdrop
        document.addEventListener('click', function(event) {
            if (window.innerWidth <= 768 && userDropdown.classList.contains('show')) {
                if (event.target === document.querySelector('.user-menu .dropdown.show::before')) {
                    userDropdown.classList.remove('show');
                }
            }
        });
    }
});
</script>

</body>
</html>
