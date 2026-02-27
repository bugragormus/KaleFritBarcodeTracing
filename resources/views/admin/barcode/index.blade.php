@extends('layouts.app')

@section('styles')
    <link href="{{ asset('assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
    <style>
        body, .main-content, .modern-barcode-management {
            background: #f8f9fa !important;
        }
        .modern-barcode-management {
            background: #ffffff;
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .page-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 2rem;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }
        
        .page-title-modern {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .page-title-modern i {
            margin-right: 1rem;
            font-size: 2rem;
        }
        
        .page-subtitle-modern {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 0;
        }
        
        .card-modern {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        
        .card-header-modern {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .card-title-modern {
            font-size: 1.3rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
        }
        
        .card-title-modern i {
            margin-right: 0.5rem;
            color: #667eea;
        }
        
        .card-subtitle-modern {
            color: #6c757d;
            margin-bottom: 0;
        }
        
        .card-body-modern {
            padding: 2rem;
        }
        
        .btn-modern {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        
        .btn-success-modern {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-info-modern {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }
        
        .btn-danger-modern {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        .btn-secondary-modern {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
        }
        
        .btn-warning-modern {
            background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
            color: #212529;
        }
        
        .btn-primary-modern {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
        }
        
        /* Küçük butonlar için */
        .btn-xs-modern {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 8px;
        }
        
        /* Buton grubu */
        .btn-group-modern {
            display: inline-flex;
            gap: 0.25rem;
            flex-wrap: wrap;
        }
        
        .btn-group-modern .btn-modern {
            margin: 0;
        }
        
        /* Hover efektleri */
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-decoration: none;
        }
        
        .btn-xs-modern:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
        }
        
        /* Disabled durumu */
        .btn-modern:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none !important;
        }
        
        .btn-modern:disabled:hover {
            transform: none !important;
            box-shadow: none !important;
        }
        
        /* Responsive tasarım */
        @media (max-width: 768px) {
            .btn-group-modern {
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
            }
            
            .btn-group-modern .btn-modern {
                width: 100%;
                justify-content: center;
            }
            
            .btn-xs-modern {
                padding: 0.5rem 1rem;
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 576px) {
            .btn-modern {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }
            
            .btn-xs-modern {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
        }

        /* Mobil Uyumluluk - Barkod Yönetimi */
        @media (max-width: 991px) {
            .modern-barcode-management {
                padding: 1rem 0;
            }
            
            .page-header-modern {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
                border-radius: 15px;
            }
            
            .page-title-modern {
                font-size: 2rem;
                margin-bottom: 0.75rem;
            }
            
            .page-subtitle-modern {
                font-size: 1rem;
            }
            
            .card-header-modern {
                padding: 1.25rem 1.5rem;
            }
            
            .card-body-modern {
                padding: 1.5rem;
            }
            
            .filter-header {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }
            
            .filter-actions {
                flex-direction: column;
                gap: 0.75rem;
                width: 100%;
            }
            
            .filter-actions .btn-modern {
                width: 100%;
                justify-content: center;
            }
            
            .filter-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .filter-item {
                margin-bottom: 0;
            }
            
            .filter-label {
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }
            
            .filter-select {
                font-size: 0.9rem;
                padding: 0.5rem;
            }
            
            .filter-date {
                font-size: 0.9rem;
                padding: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            .page-header-modern {
                padding: 1.25rem;
                margin-bottom: 1.25rem;
            }
            
            .page-title-modern {
                font-size: 1.75rem;
                text-align: center;
            }
            
            .page-subtitle-modern {
                text-align: center;
                font-size: 0.95rem;
            }
            
            .page-header-modern .col-md-4 {
                text-align: center !important;
                margin-top: 1rem;
            }
            
            .page-header-modern .btn-modern {
                width: 100%;
                justify-content: center;
            }
            
            .card-header-modern {
                padding: 1rem;
                text-align: center;
            }
            
            .card-title-modern {
                font-size: 1.2rem;
                justify-content: center;
            }
            
            .card-subtitle-modern {
                font-size: 0.9rem;
                text-align: center;
            }
            
            .column-filters {
                padding: 1rem;
            }
            
            .filter-header h6 {
                font-size: 1rem;
                margin-bottom: 0.75rem;
            }
            
            .filter-grid {
                gap: 0.75rem;
            }
            
            .filter-item {
                padding: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .modern-barcode-management {
                padding: 0.75rem 0;
            }
            
            .page-header-modern {
                padding: 1rem;
                margin-bottom: 1rem;
                border-radius: 12px;
            }
            
            .page-title-modern {
                font-size: 1.5rem;
            }
            
            .page-subtitle-modern {
                font-size: 0.9rem;
            }
            
            .card-modern {
                border-radius: 15px;
            }
            
            .card-header-modern {
                padding: 0.75rem;
            }
            
            .card-body-modern {
                padding: 1rem;
            }
            
            .card-title-modern {
                font-size: 1.1rem;
            }
            
            .card-subtitle-modern {
                font-size: 0.85rem;
            }
            
            .filter-header {
                gap: 0.75rem;
            }
            
            .filter-actions {
                gap: 0.5rem;
            }
            
            .filter-grid {
                gap: 0.5rem;
            }
            
            .filter-item {
                padding: 0.5rem;
            }
            
            .filter-label {
                font-size: 0.85rem;
                margin-bottom: 0.4rem;
            }
            
            .filter-select,
            .filter-date {
                font-size: 0.85rem;
                padding: 0.4rem;
            }
            
            .btn-modern {
                padding: 0.5rem 1rem;
                font-size: 0.85rem;
            }
            
            .btn-xs-modern {
                padding: 0.35rem 0.7rem;
                font-size: 0.8rem;
            }
        }

        /* DataTable Mobil Uyumluluk */
        @media (max-width: 991px) {
            .yajra-datatable {
                font-size: 0.9rem;
            }
            
            .yajra-datatable th,
            .yajra-datatable td {
                padding: 0.5rem 0.25rem;
            }
            
            /* Mobilde tablo başlıklarını optimize et */
            .yajra-datatable th {
                white-space: nowrap;
                min-width: 80px;
            }
            
            /* Mobilde tablo hücrelerini optimize et */
            .yajra-datatable td {
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 120px;
            }
        }

        @media (max-width: 768px) {
            .yajra-datatable {
                font-size: 0.8rem;
            }
            
            .yajra-datatable th,
            .yajra-datatable td {
                padding: 0.4rem 0.2rem;
                font-size: 0.8rem;
            }
            
            .yajra-datatable .btn {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 576px) {
            .yajra-datatable {
                font-size: 0.75rem;
            }
            
            .yajra-datatable th,
            .yajra-datatable td {
                padding: 0.3rem 0.15rem;
                font-size: 0.75rem;
            }
            
            .yajra-datatable .btn {
                padding: 0.2rem 0.4rem;
                font-size: 0.7rem;
            }
        }

        /* Mobil için tablo scroll */
        @media (max-width: 991px) {
            .card-body-modern {
                overflow-x: auto;
            }
            
            .yajra-datatable {
                min-width: 800px;
            }
        }

        /* Mobil için buton grupları */
        @media (max-width: 768px) {
            .btn-group-modern {
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
            }
            
            .btn-group-modern .btn-modern {
                width: 100%;
                justify-content: center;
            }
        }

        /* Mobil için filter grid düzeni */
        @media (max-width: 768px) {
            .filter-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 1rem;
            }
        }

        @media (max-width: 576px) {
            .filter-grid {
                gap: 0.75rem;
            }
        }

        /* Mobil için page header düzeni */
        @media (max-width: 768px) {
            .page-header-modern .row {
                flex-direction: column;
                gap: 1rem;
            }
            
            .page-header-modern .col-md-8,
            .page-header-modern .col-md-4 {
                width: 100%;
                text-align: center;
            }
        }

        /* Mobil için ek iyileştirmeler */
        @media (max-width: 480px) {
            .modern-barcode-management {
                padding: 0.5rem 0;
            }
            
            .page-header-modern {
                padding: 0.75rem;
                margin-bottom: 0.75rem;
                border-radius: 10px;
            }
            
            .page-title-modern {
                font-size: 1.25rem;
            }
            
            .page-subtitle-modern {
                font-size: 0.8rem;
            }
            
            .card-modern {
                border-radius: 12px;
            }
            
            .card-header-modern {
                padding: 0.5rem;
            }
            
            .card-body-modern {
                padding: 0.75rem;
            }
            
            .card-title-modern {
                font-size: 1rem;
            }
            
            .card-subtitle-modern {
                font-size: 0.8rem;
            }
            
            .column-filters {
                padding: 0.75rem;
            }
            
            .filter-header {
                gap: 0.5rem;
            }
            
            .filter-header h6 {
                font-size: 0.9rem;
            }
            
            .filter-actions {
                gap: 0.4rem;
            }
            
            .filter-grid {
                gap: 0.4rem;
            }
            
            .filter-item {
                padding: 0.4rem;
            }
            
            .filter-label {
                font-size: 0.8rem;
                margin-bottom: 0.3rem;
            }
            
            .filter-select,
            .filter-date,
            .filter-input {
                font-size: 0.8rem;
                padding: 0.3rem;
                min-height: 35px;
            }
            
            .btn-modern {
                padding: 0.4rem 0.8rem;
                font-size: 0.8rem;
            }
            
            .btn-xs-modern {
                padding: 0.3rem 0.6rem;
                font-size: 0.75rem;
            }
        }

        /* Mobil için DataTable ek optimizasyonlar */
        @media (max-width: 480px) {
            .yajra-datatable {
                font-size: 0.7rem;
            }
            
            .yajra-datatable th,
            .yajra-datatable td {
                padding: 0.25rem 0.1rem;
                font-size: 0.7rem;
            }
            
            .yajra-datatable .btn {
                padding: 0.15rem 0.3rem;
                font-size: 0.65rem;
            }
            
            .status-badge {
                padding: 4px 8px;
                font-size: 10px;
                min-width: 80px;
            }
            
            .badge {
                padding: 2px 6px;
                font-size: 10px;
            }
        }

        /* Mobil için tablo scroll iyileştirmeleri */
        @media (max-width: 991px) {
            .card-body-modern {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                scrollbar-width: thin;
                scrollbar-color: #667eea #f8f9fa;
            }
            
            .yajra-datatable {
                min-width: 800px;
            }
            
            /* Mobilde tablo scroll göstergesi */
            .card-body-modern::after {
                content: '← Kaydırın →';
                display: block;
                text-align: center;
                color: #6c757d;
                font-size: 0.8rem;
                padding: 0.5rem;
                background: rgba(108, 117, 125, 0.1);
                border-radius: 8px;
                margin-top: 0.5rem;
            }
            
            /* Webkit scrollbar stilleri */
            .card-body-modern::-webkit-scrollbar {
                height: 8px;
            }
            
            .card-body-modern::-webkit-scrollbar-track {
                background: #f8f9fa;
                border-radius: 4px;
            }
            
            .card-body-modern::-webkit-scrollbar-thumb {
                background: #667eea;
                border-radius: 4px;
            }
            
            .card-body-modern::-webkit-scrollbar-thumb:hover {
                background: #5a6fd8;
            }
        }

        /* Mobil için buton grupları ek iyileştirmeler */
        @media (max-width: 768px) {
            .btn-group-modern {
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
            }
            
            .btn-group-modern .btn-modern {
                width: 100%;
                justify-content: center;
                text-align: center;
            }
            
            .action-buttons-container {
                flex-direction: column;
                gap: 0.5rem;
                width: 100%;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
                text-align: center;
            }
        }

        /* Mobil için filter grid ek düzenlemeler */
        @media (max-width: 768px) {
            .filter-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .filter-item {
                margin-bottom: 0;
            }
            
            /* Mobilde filter alanlarını optimize et */
            .filter-select,
            .filter-date,
            .filter-input {
                font-size: 0.9rem;
                padding: 0.6rem;
                min-height: 40px;
            }
            
            .filter-label {
                font-size: 0.9rem;
                margin-bottom: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            .filter-grid {
                gap: 0.5rem;
            }
            
            .filter-item {
                padding: 0.5rem;
            }
        }

        @media (max-width: 480px) {
            .filter-grid {
                gap: 0.4rem;
            }
            
            .filter-item {
                padding: 0.4rem;
            }
            
            /* Çok küçük ekranlarda ek optimizasyonlar */
            .filter-select,
            .filter-date,
            .filter-input {
                font-size: 0.75rem;
                padding: 0.25rem;
                min-height: 32px;
            }
            
            .filter-label {
                font-size: 0.75rem;
                margin-bottom: 0.25rem;
            }
            
            .btn-modern {
                padding: 0.35rem 0.7rem;
                font-size: 0.75rem;
            }
        }

        /* Mobil için ek genel iyileştirmeler */
        @media (max-width: 768px) {
            /* Mobilde container padding'i azalt */
            .container-fluid {
                padding-left: 0.75rem;
                padding-right: 0.75rem;
            }
            
            /* Mobilde card margin'i azalt */
            .card-modern {
                margin-bottom: 1rem;
            }
            
            /* Mobilde filter header'ı optimize et */
            .filter-header {
                margin-bottom: 1rem;
                padding-bottom: 0.75rem;
            }
            
            /* Mobilde filter actions'ı optimize et */
            .filter-actions {
                margin-top: 0.5rem;
            }
        }

        @media (max-width: 576px) {
            /* Çok küçük ekranlarda container padding'i daha da azalt */
            .container-fluid {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }
            
            /* Çok küçük ekranlarda card margin'i daha da azalt */
            .card-modern {
                margin-bottom: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            /* En küçük ekranlarda container padding'i minimum yap */
            .container-fluid {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }
            
            /* En küçük ekranlarda card margin'i minimum yap */
            .card-modern {
                margin-bottom: 0.5rem;
            }
        }
        
        .btn-info-modern {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
            color: white;
        }
        
        .btn-warning-modern {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
        }
        
        .btn-danger-modern {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }
        
        .btn-secondary-modern {
            background: linear-gradient(135deg, #adb5bd 0%, #6c757d 100%);
            color: white;
        }
        
        /* Column Filters */
        .column-filters {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
        }
        
        .filter-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
        }
        
        .filter-actions {
            display: flex;
            gap: 0.75rem;
        }
        
        .filter-header h6 {
            margin: 0;
            font-weight: 600;
            color: #495057;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
        }
        
        .filter-header h6 i {
            margin-right: 0.5rem;
            color: #667eea;
            font-size: 1.2rem;
        }
        
        .filter-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        /* Mobil için filter grid düzeni */
        @media (max-width: 1200px) {
            .filter-grid {
                grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
                gap: 0.875rem;
            }
        }

        @media (max-width: 991px) {
            .filter-grid {
                grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
                gap: 0.75rem;
            }
        }
        
        .filter-item {
            display: flex;
            flex-direction: column;
        }
        
        .filter-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .filter-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
            white-space: normal;
            word-wrap: break-word;
            height: auto;
            min-height: 45px;
            cursor: pointer;
        }
        
        .filter-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .filter-select option {
            font-size: 0.875rem;
            padding: 0.5rem;
            white-space: normal;
            word-wrap: break-word;
        }
        
        /* Input filter styles */
        .filter-input {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
            white-space: normal;
            word-wrap: break-word;
            height: auto;
            min-height: 45px;
        }
        
        .filter-input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .filter-input::placeholder {
            color: #adb5bd;
            font-size: 0.875rem;
        }
        
        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            height: auto;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: #ffffff;
            min-height: 45px;
        }
        
        .select2-container--default .select2-selection--single:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .select2-container--default .select2-selection--single:hover {
            border-color: #667eea;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #495057;
            padding: 0;
            line-height: 1.5;
            font-size: 0.875rem;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 1rem;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #667eea transparent transparent transparent;
            border-width: 6px 4px 0 4px;
        }
        
        .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
            border-color: transparent transparent #667eea transparent;
            border-width: 0 4px 6px 4px;
        }
        
        .select2-dropdown {
            border: 2px solid #667eea;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
            background: #ffffff;
        }
        
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }
        
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .select2-container--default .select2-results__option {
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: #495057;
            transition: all 0.2s ease;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .select2-container--default .select2-results__option[aria-selected=true] {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }
        
        .select2-container {
            width: 100% !important;
        }
        
        /* Select2 Dropdown Positioning */
        .select2-dropdown {
            z-index: 9999;
        }
        
        .filter-date {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            background: white;
            width: 100%;
            cursor: pointer;
        }
        
        .filter-date:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }
        
        .filter-date::placeholder {
            color: #adb5bd;
            font-size: 0.875rem;
        }
        
        /* Datepicker Styling */
        .datepicker {
            border-radius: 10px !important;
            border: 2px solid #e9ecef !important;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important;
            background: white !important;
            color: #495057 !important;
            z-index: 9999 !important;
            padding: 1rem !important;
            font-size: 0.875rem !important;
            max-width: 300px !important;
        }
        
        .datepicker table {
            background: white !important;
            width: 100% !important;
            font-size: 0.875rem !important;
        }
        
        .datepicker table tr td,
        .datepicker table tr th {
            border-radius: 8px !important;
            margin: 2px !important;
            color: #495057 !important;
            background: white !important;
            text-align: center !important;
            padding: 0.5rem !important;
            font-size: 0.875rem !important;
            width: 35px !important;
            height: 35px !important;
            line-height: 25px !important;
        }
        
        .datepicker table tr td.active,
        .datepicker table tr td.active:hover,
        .datepicker table tr td.active.disabled,
        .datepicker table tr td.active.disabled:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border-color: #667eea !important;
            color: white !important;
        }
        
        .datepicker table tr td.today {
            background: linear-gradient(135deg, #ffc107, #e0a800) !important;
            border-color: #ffc107 !important;
            color: white !important;
        }
        
        .datepicker table tr td:hover {
            background: #f8f9fa !important;
        }
        
        .datepicker table tr td.old,
        .datepicker table tr td.new {
            color: #adb5bd !important;
        }
        
        .datepicker .datepicker-switch {
            font-weight: 600 !important;
            color: #495057 !important;
            background: #f8f9fa !important;
            padding: 0.5rem !important;
            font-size: 0.875rem !important;
            height: 35px !important;
            line-height: 25px !important;
        }
        
        .datepicker .prev,
        .datepicker .next {
            color: #667eea !important;
            background: #f8f9fa !important;
            padding: 0.5rem !important;
            font-size: 0.875rem !important;
            height: 35px !important;
            line-height: 25px !important;
        }
        
        .datepicker .prev:hover,
        .datepicker .next:hover {
            background: #e9ecef !important;
            color: #5a6fd8 !important;
        }
        
        .datepicker .dow {
            font-weight: 600 !important;
            color: #495057 !important;
            background: #f8f9fa !important;
            padding: 0.5rem !important;
            font-size: 0.75rem !important;
            height: 30px !important;
            line-height: 20px !important;
        }
        
        /* DataTable Styling */
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            margin: 1rem 0;
        }
        
        .dataTables_wrapper .dataTables_length select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.5rem 0.75rem;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 0.75rem 1rem;
        }
        
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 10px;
            margin: 0 2px;
            border: 2px solid #e9ecef;
            background: white;
            color: #495057;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            border-color: #5a6fd8;
            color: white;
        }
        
        /* Status Badges */
        .status-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
            text-align: center;
            min-width: 100px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            border: none;
            transition: all 0.3s ease;
        }
        
        .status-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        
        .status-waiting {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: #212529;
        }
        
        .status-control-repeat {
            background: linear-gradient(135deg, #fd7e14, #e55a00);
            color: white;
        }
        
        .status-pre-approved {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .status-shipment-approved {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }
        
        .status-rejected {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        
        .status-customer-transfer {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
            color: white;
        }
        
        .status-delivered {
            background: linear-gradient(135deg, #20c997, #17a2b8);
            color: white;
        }
        
        .status-merged {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
            color: white;
        }
        
        /* Badge styles for merge status */
        .badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge-success {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .badge-secondary {
            background: linear-gradient(135deg, #6c757d, #5a6268);
            color: white;
        }
        
        /* Table styling for better status badge display */
        .table td {
            vertical-align: middle;
        }
        
        .table th {
            vertical-align: middle;
            font-weight: 600;
            color: #495057;
        }
        
        /* DataTable specific styling */
        .yajra-datatable td {
            vertical-align: middle;
        }
        
        .yajra-datatable th {
            vertical-align: middle;
            font-weight: 600;
            color: #495057;
        }
        
        /* Modern Action Buttons */
        .action-buttons-container {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .action-btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            border: none;
            min-width: 80px;
            position: relative;
            overflow: hidden;
        }
        
        .action-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }
        
        .action-btn:hover::before {
            left: 100%;
        }
        
        .action-btn:hover {
            transform: translateY(-2px);
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .action-btn i {
            margin-right: 0.25rem;
            font-size: 0.875rem;
        }
        
        /* Hareketler Butonu */
        .btn-history {
            background: linear-gradient(135deg, #17a2b8, #138496);
            color: white;
        }
        
        .btn-history:hover {
            background: linear-gradient(135deg, #138496, #117a8b);
            color: white;
        }
        
        /* Düzenle Butonu */
        .btn-edit {
            background: linear-gradient(135deg, #ffc107, #e0a800);
            color: white;
        }
        
        .btn-edit:hover {
            background: linear-gradient(135deg, #e0a800, #d39e00);
            color: white;
        }
        
        /* Sil Butonu */
        .btn-delete {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        
        .btn-delete:hover {
            background: linear-gradient(135deg, #c82333, #bd2130);
            color: white;
        }
        
        /* Görüntüle Butonu */
        .btn-view {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }
        
        .btn-view:hover {
            background: linear-gradient(135deg, #218838, #1ea085);
            color: white;
        }
        
        /* Yazdır Butonu */
        .btn-print {
            background: linear-gradient(135deg, #6f42c1, #5a32a3);
            color: white;
        }
        
        .btn-print:hover {
            background: linear-gradient(135deg, #5a32a3, #4a2a8a);
            color: white;
        }
        
        /* Responsive action buttons */
        @media (max-width: 768px) {
            .action-buttons-container {
                flex-direction: column;
                gap: 0.25rem;
            }
            
            .action-btn {
                width: 100%;
                justify-content: center;
            }
            
            .page-title-modern {
                font-size: 2rem;
            }
            
            .filter-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }
            
            .filter-header {
                flex-direction: column;
                gap: 0.75rem;
                text-align: center;
            }
            
            .filter-actions {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .filter-actions .btn {
                width: 100%;
            }
            
            .column-filters {
                padding: 1rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="modern-barcode-management">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-barcode"></i> Barkod Yönetimi
                        </h1>
                        <p class="page-subtitle-modern">Sistemdeki tüm barkodları görüntüleyin, arayın ve yönetin</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('barcode.create') }}" class="btn-modern btn-success-modern">
                            <i class="fas fa-plus"></i> Yeni Barkod
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modern Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-list"></i> Barkod Listesi
                    </h3>
                    <p class="card-subtitle-modern">
                        Aşağıdaki listede sisteme kayıtlı tüm barkodları görebilir, düzenleyebilir ve silebilirsiniz
                    </p>
                </div>

                <!-- Column Filters -->
                <div class="column-filters">
                    <div class="filter-header">
                        <h6><i class="fas fa-filter"></i> Sütun Filtreleri</h6>
                        <div class="filter-actions">
                            <button class="btn-modern btn-info-modern" onclick="applyFilters()">
                                <i class="fas fa-check"></i> Filtreleri Uygula
                            </button>
                            <button class="btn-modern btn-secondary-modern" onclick="clearFilters()">
                                <i class="fas fa-times"></i> Filtreleri Temizle
                            </button>
                        </div>
                    </div>
                    <div class="filter-grid">
                        <div class="filter-item">
                            <label class="filter-label">Stok</label>
                            <select class="filter-select" data-column="0">
                                <option value="">Tüm Stoklar</option>
                                @foreach($stocks as $stock)
                                    <option value="{{ $stock->name }}">{{ $stock->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Parti No</label>
                            <select class="filter-select" id="party-number-filter">
                                <option value="">Tüm Partiler</option>
                                @php
                                    $uniqueParties = \App\Models\Barcode::whereNotNull('party_number')
                                        ->where('party_number', '!=', '')
                                        ->distinct()
                                        ->pluck('party_number')
                                        ->sort()
                                        ->values();
                                @endphp
                                @foreach($uniqueParties as $party)
                                    <option value="{{ $party }}">{{ $party }}</option>
                                @endforeach 
                            </select>
                        </div>
                        
                        <div class="filter-item">
                            <label class="filter-label">Barkod No</label>
                            <input type="text" class="filter-input" id="barcode-id-filter" placeholder="Barkod numarası giriniz">
                        </div>
                        
                        <div class="filter-item">
                            <label class="filter-label">Şarj No</label>
                            <input type="text" class="filter-input" id="load-number-filter" placeholder="Şarj numarası giriniz">
                        </div>
                            <div class="filter-item">
                                <label class="filter-label">Durum</label>
                                <select class="filter-select" data-column="2">
                                    <option value="">Tüm Durumlar</option>
                                    <option value="Beklemede">Beklemede</option>
                                    <option value="Kontrol Tekrarı">Kontrol Tekrarı</option>
                                    <option value="Ön Onaylı">Ön Onaylı</option>
                                    <option value="Sevk Onaylı">Sevk Onaylı</option>
                                    <option value="Reddedildi">Reddedildi</option>
                                    <option value="Düzeltme Faaliyetinde Kullanıldı">Düzeltme Faaliyetinde Kullanıldı</option>
                                    <option value="Müşteri Transfer">Müşteri Transfer</option>
                                    <option value="Teslim Edildi">Teslim Edildi</option>
                                    <option value="Birleştirildi">Birleştirildi</option>
                                </select>
                            </div>

                            <div class="filter-item">
                                <label class="filter-label">İstisnai Onay</label>
                                <select class="filter-select" id="exceptionally-approved-filter">
                                    <option value="">Tüm Ürünler</option>
                                    <option value="1">İstisnai Onaylı</option>
                                    <option value="0">Normal Onaylı</option>
                                </select>
                            </div>

                            <div class="filter-item">
                                <label class="filter-label">İade</label>
                                <select class="filter-select" id="returned-filter">
                                    <option value="">Tüm Ürünler</option>
                                    <option value="1">İade Edildi</option>
                                    <option value="0">İade Değil</option>
                                </select>
                            </div>

                        <div class="filter-item">
                            <label class="filter-label">Fırın</label>
                            <select class="filter-select" data-column="14">
                                <option value="">Tüm Fırınlar</option>
                                @foreach($kilns as $kiln)
                                    <option value="{{ $kiln->name }}">{{ $kiln->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Depo</label>
                            <select class="filter-select" data-column="4">
                                <option value="">Tüm Depolar</option>
                                @foreach($wareHouses as $wareHouse)
                                    <option value="{{ $wareHouse->name }}">{{ $wareHouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Firma</label>
                            <select class="filter-select" data-column="5">
                                <option value="">Tüm Firmalar</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->name }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Lab Tarihi Bşl.</label>
                            <input type="text" class="filter-date" id="lab-date-start" placeholder="Başlangıç Tarihi" autocomplete="off">
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Lab Tarihi Bitiş</label>
                            <input type="text" class="filter-date" id="lab-date-end" placeholder="Bitiş Tarihi" autocomplete="off">
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Oluşturan</label>
                            <select class="filter-select" data-column="9">
                                <option value="">Tüm Kullanıcılar</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->name }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Oluşturulma Tarihi Bşl.</label>
                            <input type="text" class="filter-date" id="created-date-start" placeholder="Başlangıç Tarihi" autocomplete="off">
                        </div>
                        <div class="filter-item">
                            <label class="filter-label">Oluşturulma Tarihi Bitiş</label>
                            <input type="text" class="filter-date" id="created-date-end" placeholder="Bitiş Tarihi" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="card-body-modern">
                    <table class="table table-bordered dt-responsive yajra-datatable nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Stok</th>
                                <th>Şarj No</th>
                                <th class="text-center">Durum</th>
                                <th>Miktar</th>
                                <th>Depo</th>
                                <th>Firma</th>
                                <th>Lab Tarihi</th>
                                <th>Oluşturulma Tarihi</th>
                                <th>İşlemler</th>
                                <th>Oluşturan</th>
                                <th>Lab Personeli</th>
                                <th>Depo Transfer Eden</th>
                                <th>Teslim Eden</th>
                                <th>Depo Transfer Tarihi</th>
                                <th>Fırın No</th>
                                <th>Müşteri Transfer Tarihi</th>
                                <th>Parti No</th>
                                <th>Barkod No</th>
                                <th>Genel Not</th>
                                <th>Lab Notu</th>
                                <th>Birleştirilme Durumu</th>
                                <th>Düzeltme Durumu</th>
                                <th>İşlem Süresi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    
    <script>
        // Global configuration for JS module
        window.BarcodeConfig = {
            indexRoute: "{{ route('barcode.index') }}",
            deleteRoutePrefix: "{{ url('/barkod') }}",
            tokens: {
                csrf: "{{ csrf_token() }}"
            }
        };
    </script>
    
    <script src="{{ asset('assets/js/modules/barcode-index.js') }}"></script>
@endsection