@extends('layouts.granilya')

@section('styles')
<style>
    body, .main-content, .modern-dashboard {
        background: #f8f9fa !important;
    }
    .modern-dashboard {
        background: #ffffff;
        min-height: 100vh;
        padding: 2rem 0;
    }
    
    .page-header-modern {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
    }
    
    .page-title-section {
        flex: 1;
    }
    
    .page-title-modern {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.4rem;
        display: flex;
        align-items: center;
    }
    
    .page-title-modern i {
        margin-right: 0.8rem;
        font-size: 1.6rem;
    }
    
    .page-subtitle-modern {
        font-size: 1rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    /* Filtreler Bölümü Stilleri */
    .filters-section {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 1.25rem;
        margin: 1.25rem 0;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.15);
        overflow: visible;
    }

    .filters-container {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        overflow: visible;
    }

    .filter-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-start;
        overflow: visible;
    }

    .filter-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        min-height: 50px;
        padding: 0.25rem 0;
    }

    .filter-item label {
        color: #1a1a1a;
        font-weight: 700;
        font-size: 1rem;
        white-space: nowrap;
        line-height: 1.2;
        display: flex;
        align-items: center;
        height: 100%;
        margin: 0;
        text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8);
        background: rgba(255, 255, 255, 0.9);
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .filter-item input[type="date"],
    .filter-item select {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 10px;
        padding: 0.6rem 0.8rem;
        color: #333;
        font-weight: 500;
        font-size: 0.95rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        min-width: 130px;
        height: 40px;
        line-height: 1.2;
        vertical-align: middle;
    }

    .filter-item input[type="date"]:focus,
    .filter-item select:focus {
        outline: none;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .filter-item input[type="date"]:hover,
    .filter-item select:hover {
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.12);
    }
    
    .filter-item select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6,9 12,15 18,9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        padding-right: 40px;
        cursor: pointer;
    }
    
    .filter-item select option {
        padding: 8px 12px;
        font-size: 0.95rem;
        line-height: 1.4;
        background: white;
        color: #333;
    }

    .custom-date-filter {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .custom-date-filter .btn-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        border: none;
        border-radius: 8px;
        padding: 0.75rem 1.25rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
        white-space: nowrap;
    }

    .custom-date-filter .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
        background: linear-gradient(135deg, #0056b3 0%, #004085 100%);
    }

    .filter-info {
        text-align: center;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }

    .filter-info small {
        color: #2c3e50;
        font-size: 0.9rem;
        line-height: 1.4;
        font-weight: 500;
        background: rgba(255, 255, 255, 0.95);
        padding: 0.6rem 1rem;
        border-radius: 10px;
        display: inline-block;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .filter-info small:hover {
        background: rgba(255, 255, 255, 1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        transform: translateY(-1px);
    }

    .btn-warning { background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); border: none; border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(255, 193, 7, 0.3); color: white; }
    .btn-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); border: none; border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3); color: white; }
    .btn-success:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4); background: linear-gradient(135deg, #218838 0%, #1ea085 100%); }
    .btn-info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); border: none; border-radius: 10px; padding: 0.5rem 1rem; font-weight: 600; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3); color: white; }

    .card-modern {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .card-header-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e9ecef;
        position: relative;
    }

    .card-header-modern::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
        opacity: 0.7;
    }
    
    .card-title-modern {
        font-size: 1.3rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    
    .card-title-modern i {
        margin-right: 0.8rem;
        color: #667eea;
        font-size: 1.2rem;
        background: rgba(102, 126, 234, 0.1);
        padding: 0.8rem;
        border-radius: 12px;
        border: 2px solid rgba(102, 126, 234, 0.2);
    }
    
    .card-body-modern {
        padding: 1.5rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .shift-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .shift-card {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 5px 15px rgba(44, 62, 80, 0.2);
        position: relative;
        overflow: hidden;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .shift-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        border: 1px solid #dee2e6;
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 1rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
    }

    .summary-item i {
        font-size: 1.2rem;
        color: #667eea;
        background: rgba(102, 126, 234, 0.1);
        padding: 0.8rem;
        border-radius: 10px;
        border: 2px solid rgba(102, 126, 234, 0.2);
    }

    .summary-item span {
        font-weight: 600;
        color: #495057;
        font-size: 0.95rem;
    }

    .shift-name {
        font-size: 1.3rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        background: rgba(255, 255, 255, 0.15);
        padding: 1rem 1.5rem;
        border-radius: 15px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        position: relative;
        margin-bottom: 1.5rem;
        width: 100%;
        text-align: center;
    }
    
    .shift-name i { margin-right: 0.5rem; }

    .shift-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        width: 100%;
        align-content: center;
    }

    .shift-stat {
        background: rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 1.2rem 0.8rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        position: relative;
        overflow: hidden;
        min-height: 90px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-width: 100px;
    }

    .shift-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        color: #ffffff;
        line-height: 1;
    }

    .shift-stat-label {
        font-size: 0.75rem;
        opacity: 0.9;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: rgba(255, 255, 255, 0.95);
        text-align: center;
        line-height: 1.2;
    }

    .table-modern {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
        width: 100%;
    }

    .table-modern th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        padding: 1.5rem 1.5rem;
        font-weight: 600;
        color: #495057;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
    }

    .table-modern td {
        border: none;
        padding: 1.5rem;
        border-bottom: 1px solid #f1f3f4;
        font-size: 0.95rem;
        vertical-align: middle;
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
    
    .chart-container {
        height: 300px;
        margin: 1rem 0;
    }

    .badge-modern { padding: 0.6rem 1.2rem; border-radius: 25px; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); transition: all 0.3s ease; }
    .badge-success { background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: 1px solid rgba(255,255,255,0.2); }
    .badge-danger { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: white; border: 1px solid rgba(255,255,255,0.2); }
    .badge-warning { background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%); color: #212529; border: 1px solid rgba(255,255,255,0.2); }
    .badge-info { background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; border: 1px solid rgba(255,255,255,0.2); }

    /* Monthly Comparison Styles */
    .monthly-comparison-overview { margin-bottom: 2rem; }
    .month-card { background: white; border-radius: 15px; padding: 1.5rem; display: flex; align-items: center; justify-content: space-between; border: 1px solid #e9ecef; box-shadow: 0 4px 15px rgba(0,0,0,0.05); transition: all 0.3s ease; height: 100%; }
    .month-card.current-month { border-left: 5px solid #667eea; }
    .month-card.previous-month { border-left: 5px solid #6c757d; }
    .month-header { display: flex; align-items: center; gap: 1rem; }
    .month-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; }
    .current-month .month-icon { background: rgba(102, 126, 234, 0.1); color: #667eea; }
    .previous-month .month-icon { background: rgba(108, 117, 125, 0.1); color: #6c757d; }
    .month-title { font-size: 1.2rem; font-weight: 700; margin: 0; color: #495057; }
    .month-period { font-size: 0.9rem; color: #6c757d; margin: 0; }
    .month-stats { display: flex; gap: 1.5rem; }
    .month-stat-item { text-align: right; }
    .month-stat-value { font-size: 1.5rem; font-weight: 800; color: #343a40; line-height: 1.2; }
    .month-stat-label { font-size: 0.8rem; color: #6c757d; text-transform: uppercase; font-weight: 600; }
    .change-analysis-section { background: #f8f9fa; border-radius: 15px; border: 1px solid #e9ecef; padding: 1.5rem; }
    .change-metric-card { background: white; border-radius: 12px; padding: 1.2rem; height: 100%; display: flex; flex-direction: column; justify-content: space-between; border: 1px solid #e9ecef; box-shadow: 0 2px 8px rgba(0,0,0,0.03); }
    .metric-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem; }
    .metric-icon { width: 45px; height: 45px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; }
    .metric-icon.production { background: rgba(102, 126, 234, 0.1); color: #667eea; }
    .metric-title { font-size: 1.1rem; font-weight: 700; color: #495057; margin: 0; }
    .metric-subtitle { font-size: 0.85rem; color: #6c757d; margin: 0; }
    .metric-body { flex: 1; display: flex; flex-direction: column; justify-content: center; }
    .change-value { display: flex; align-items: baseline; justify-content: center; gap: 0.3rem; margin-bottom: 0.5rem; }
    .change-sign { font-size: 1.5rem; font-weight: 700; }
    .change-number { font-size: 3rem; font-weight: 800; line-height: 1; }
    .change-unit { font-size: 1.5rem; font-weight: 700; }
    .positive-change { color: #28a745; }
    .negative-change { color: #dc3545; }
    .neutral-change { color: #6c757d; }
    /* Stok Yaşı Dashboard Stilleri */
    .stock-age-summary {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 1.2rem;
        margin-bottom: 1.2rem;
        border: 1px solid #dee2e6;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
    }

    .stock-age-card {
        background: white;
        border-radius: 15px;
        padding: 1rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        transition: all 0.4s ease;
        height: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .stock-age-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: currentColor;
        opacity: 0.3;
    }

    .stock-age-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }

    .stock-age-card.critical {
        border-left: 3px solid #dc3545;
        background: linear-gradient(135deg, #fff5f5 0%, #fef2f2 100%);
        color: #dc3545;
    }

    .stock-age-card.warning {
        border-left: 3px solid #ffc107;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
        color: #d97706;
    }

    .stock-age-card.attention {
        border-left: 3px solid #17a2b8;
        background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
        color: #0891b2;
    }

    .stock-age-card.normal {
        border-left: 3px solid #28a745;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        color: #16a34a;
    }

    .stock-age-icon {
        font-size: 1.5rem;
        margin-bottom: 0.6rem;
        opacity: 0.8;
    }

    .stock-age-value {
        font-size: 1.8rem;
        font-weight: 800;
        margin-bottom: 0.3rem;
        line-height: 1;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .stock-age-label {
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stock-age-subtitle {
        font-size: 0.75rem;
        opacity: 0.7;
        margin-bottom: 0.6rem;
        font-weight: 500;
    }

    .stock-age-quantity {
        font-size: 0.8rem;
        font-weight: 600;
        opacity: 0.8;
        background: rgba(255, 255, 255, 0.5);
        padding: 0.3rem 0.6rem;
        border-radius: 10px;
        display: inline-block;
    }

    /* Detail Section Styles */
    .stock-age-detail-section {
        margin-bottom: 1.2rem;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
    }

    .section-header {
        display: flex;
        align-items: center;
        padding: 1rem 1.2rem;
        gap: 1rem;
    }

    .section-header.critical-header {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .section-header.warning-header {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #212529;
    }

    .section-icon {
        font-size: 1.5rem;
        opacity: 0.9;
    }

    .section-content {
        flex: 1;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .section-subtitle {
        font-size: 0.8rem;
        opacity: 0.9;
        margin-bottom: 0;
    }

    .section-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.6rem;
        border-radius: 10px;
        min-width: 60px;
    }

    .section-badge.critical-badge {
        background: rgba(255, 255, 255, 0.2);
    }

    .section-badge.warning-badge {
        background: rgba(0, 0, 0, 0.1);
    }

    .badge-number {
        font-size: 1.3rem;
        font-weight: 800;
        line-height: 1;
    }

    .badge-text {
        font-size: 0.65rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.9;
    }

    .section-body {
        background: white;
        padding: 1.2rem;
    }

    .section-footer {
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e9ecef;
    }

    /* Table Enhancements */
    .stock-age-table th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        padding: 0.8rem 0.6rem;
        font-weight: 700;
        color: #495057;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        text-align: center;
    }

    .stock-age-table td {
        border: none;
        padding: 0.8rem 0.6rem;
        border-bottom: 1px solid #f1f3f4;
        font-size: 0.75rem;
        vertical-align: middle;
        text-align: center;
    }

    .stock-age-table tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transform: scale(1.01);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
    }

    .stock-age-table.table-danger {
        background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    }

    .stock-age-table.table-warning {
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    }

    /* Enhanced Elements */
    .barcode-id {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.3rem 0.6rem;
        border-radius: 12px;
        font-size: 0.75rem;
        display: inline-block;
    }

    .product-info {
        text-align: left;
    }

    .product-name {
        display: block;
        font-size: 0.85rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 0.2rem;
    }

    .product-code {
        display: block;
        font-size: 0.7rem;
        color: #6c757d;
        font-weight: 500;
        background: #f8f9fa;
        padding: 0.15rem 0.3rem;
        border-radius: 5px;
    }

    .quantity-badge {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.75rem;
        display: inline-block;
    }

    .process-info {
        display: block;
        padding: 0.15rem 0.4rem;
        border-radius: 6px;
        font-weight: 500;
        font-size: 0.7rem;
    }

    .process-info.lab {
        background: #e3f2fd;
        color: #1565c0;
    }

    .process-info.transfer {
        background: #fff3e0;
        color: #ef6c00;
    }

    .process-info.created {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    /* Status Badge Enhancements */
    .stock-age-status-badge {
        padding: 0.3rem 0.6rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .stock-age-status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .stock-age-status-badge.waiting {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #212529;
    }

    .stock-age-status-badge.control_repeat {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }

    .stock-age-status-badge.pre_approved {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
        color: white;
    }

    .stock-age-status-badge.shipment_approved {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        color: white;
    }

    .stock-age-status-badge.rejected {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .stock-age-status-badge.customer_transfer {
        background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
        color: white;
    }

    .stock-age-status-badge.delivered {
        background: linear-gradient(135deg, #20c997 0%, #1a9f7a 100%);
        color: white;
    }

    /* Age Badge Enhancements */
    .stock-age-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .stock-age-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .stock-age-badge.critical {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .stock-age-badge.warning {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #212529;
    }

    .stock-age-badge.attention {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
    }

    .stock-age-badge.normal {
        background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
        color: white;
    }

    /* Info Badge Enhancement */
    .stock-age-info-badge {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border: none;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-size: 0.75rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
        transition: all 0.3s ease;
        margin-right: 1rem;
    }

    .stock-age-info-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(23, 162, 184, 0.4);
    }

    .stock-age-info-badge i {
        margin-right: 0.3rem;
    }

    /* Product Analysis Enhancement */
    .stock-age-product-analysis {
        background: white;
        border-radius: 15px;
        padding: 1rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        height: 100%;
        transition: all 0.3s ease;
    }

    .stock-age-product-analysis:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .stock-age-product-analysis .card-header-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 0.8rem 1rem;
        border-bottom: 2px solid #dee2e6;
        border-radius: 12px 12px 0 0;
    }

    .stock-age-product-analysis .card-title-modern {
        font-size: 0.9rem;
        margin-bottom: 0;
        color: #495057;
    }

    .stock-age-product-analysis .card-title-modern i {
        font-size: 0.8rem;
        margin-right: 0.3rem;
        color: #667eea;
    }

    .stock-age-product-analysis .table-sm th,
    .stock-age-product-analysis .table-sm td {
        padding: 0.5rem 0.4rem;
        font-size: 0.75rem;
        text-align: center;
    }

    .stock-age-product-analysis .badge-light {
        background: #e9ecef;
        color: #495057;
        border: 1px solid #ced4da;
    }

    .stock-age-product-analysis .badge-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .stock-age-product-analysis .badge-warning {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #212529;
    }

    /* Action Recommendations Enhancement */
    .action-recommendations {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        padding: 1.5rem;
        border: 1px solid #dee2e6;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        margin-top: 1.2rem;
    }

    /* Recommendations Header */
    .recommendations-header {
        display: flex;
        align-items: center;
        gap: 1.2rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #e9ecef;
    }

    .header-icon {
        font-size: 2rem;
        color: #ffc107;
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
        padding: 1rem;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(255, 193, 7, 0.3);
    }

    .header-content {
        flex: 1;
    }

    .header-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #495057;
        margin-bottom: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .header-subtitle {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0;
        font-weight: 500;
    }

    .header-stats {
        background: white;
        padding: 1rem;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
        border: 2px solid #e9ecef;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        display: block;
        font-size: 1.6rem;
        font-weight: 900;
        color: #dc3545;
        line-height: 1;
        margin-bottom: 0.3rem;
    }

    .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Recommendation Cards */
    .recommendation-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        overflow: hidden;
        transition: all 0.4s ease;
        height: 100%;
        margin-bottom: 1rem;
    }

    .recommendation-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .recommendation-card.critical-card {
        border-left: 4px solid #dc3545;
        background: linear-gradient(135deg, #fff5f5 0%, #fef2f2 100%);
    }

    .recommendation-card.warning-card {
        border-left: 4px solid #ffc107;
        background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    }

    /* Card Header Section */
    .card-header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.2rem;
        background: rgba(255, 255, 255, 0.8);
        border-bottom: 1px solid #e9ecef;
    }

    .priority-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .priority-badge.critical-priority {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
    }

    .priority-badge.warning-priority {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #212529;
    }

    .urgency-indicator {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .pulse-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #dc3545;
        animation: pulse 2s infinite;
    }

    .pulse-dot.warning-pulse {
        background: #ffc107;
        animation: pulse-warning 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }

    @keyframes pulse-warning {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Card Body Section */
    .card-body-section {
        padding: 1.2rem;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 800;
        color: #495057;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .card-title i {
        color: #dc3545;
        font-size: 1rem;
    }

    .warning-card .card-title i {
        color: #ffc107;
    }

    /* Stats Summary */
    .stats-summary {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0.6rem;
        margin-bottom: 1.2rem;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.8);
    }

    .stat-box {
        text-align: center;
        padding: 0.6rem;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .stat-box:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        display: block;
        font-size: 1.2rem;
        margin-bottom: 0.3rem;
    }

    .stat-value {
        display: block;
        font-size: 1.2rem;
        font-weight: 900;
        color: #dc3545;
        line-height: 1;
        margin-bottom: 0.2rem;
    }

    .warning-card .stat-value {
        color: #ffc107;
    }

    .stat-label {
        font-size: 0.65rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Action List */
    .action-list {
        margin-bottom: 1rem;
    }

    .action-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 0.6rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .action-items {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .action-item {
        display: flex;
        align-items: flex-start;
        gap: 0.6rem;
        padding: 0.6rem;
        margin-bottom: 0.6rem;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
    }

    .action-item:hover {
        background: white;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .action-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
    }

    .action-content {
        flex: 1;
    }

    .action-content strong {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 0.3rem;
    }

    .action-content p {
        font-size: 0.75rem;
        color: #6c757d;
        margin-bottom: 0;
        line-height: 1.4;
    }

    /* Card Footer Section */
    .card-footer-section {
        padding: 1rem 1.2rem;
        background: rgba(255, 255, 255, 0.6);
        border-top: 1px solid #e9ecef;
    }

    .progress-indicator {
        display: flex;
        align-items: center;
        gap: 0.6rem;
    }

    .progress-bar {
        flex: 1;
        height: 5px;
        background: #e9ecef;
        border-radius: 6px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 6px;
        transition: width 1s ease;
    }

    .progress-fill.critical-fill {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    .progress-fill.warning-fill {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
    }

    .progress-text {
        font-size: 0.75rem;
        font-weight: 600;
        color: #495057;
        white-space: nowrap;
    }

    /* Success Recommendation */
    .success-recommendation {
        text-align: center;
        padding: 1.2rem;
    }

    .success-card {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.2);
        border: 2px solid #28a745;
    }

    .success-icon {
        font-size: 2.5rem;
        color: #28a745;
        margin-bottom: 1rem;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-6px); }
        60% { transform: translateY(-3px); }
    }

    .success-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #155724;
        margin-bottom: 0.6rem;
    }

    .success-description {
        font-size: 0.9rem;
        color: #155724;
        margin-bottom: 1.2rem;
        font-weight: 500;
    }

    .success-tips {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 0.6rem;
        margin-top: 1.2rem;
    }

    .tip-item {
        display: flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.6rem;
        background: rgba(255, 255, 255, 0.8);
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.9);
        transition: all 0.3s ease;
    }

    .tip-item:hover {
        background: white;
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .tip-item i {
        color: #28a745;
        font-size: 1rem;
        width: 18px;
        text-align: center;
    }

    .tip-item span {
        font-size: 0.8rem;
        color: #155724;
        font-weight: 600;
    }

    /* Monthly Comparison Enhanced Styles */
    .monthly-comparison-badge {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        box-shadow: 0 3px 12px rgba(23, 162, 184, 0.3);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        margin-right: 1rem;
    }

    .monthly-comparison-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(23, 162, 184, 0.4);
    }

    .monthly-comparison-overview {
        margin-bottom: 2rem;
    }

    .month-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        transition: all 0.4s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
    }

    .month-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        border-radius: 20px 20px 0 0;
    }

    .month-card.current-month::before {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .month-card.previous-month::before {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
    }

    .month-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .month-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f8f9fa;
    }

    .month-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .month-info {
        flex: 1;
    }

    .month-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #495057;
        margin-bottom: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .month-period {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0;
        font-weight: 500;
    }

    .month-badge {
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .month-badge.current {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .month-badge.previous {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }

    .month-stats {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .month-stat-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .month-stat-item:hover {
        transform: translateX(5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.3);
    }

    .stat-content {
        flex: 1;
    }

    .stat-value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #495057;
        margin-bottom: 0.3rem;
        line-height: 1;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-trend {
        width: 35px;
        height: 35px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
        flex-shrink: 0;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .stat-trend.up {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
    }

    .stat-trend.down {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        color: white;
    }

    /* Change Analysis Section */
    .change-analysis-section {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 20px;
        border: 1px solid #dee2e6;
    }

    .section-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .section-title {
        font-size: 1.4rem;
        font-weight: 800;
        color: #495057;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
    }

    .section-title i {
        color: #667eea;
        font-size: 1.3rem;
    }

    .section-subtitle {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 0;
        font-weight: 500;
    }

    .change-metrics {
        margin-bottom: 1.5rem;
    }

    .change-metric-card {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
        transition: all 0.4s ease;
        height: 100%;
    }

    .change-metric-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .metric-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f8f9fa;
    }

    .metric-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .metric-info {
        flex: 1;
    }

    .metric-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 0.3rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metric-subtitle {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0;
        font-weight: 500;
    }

    .metric-content {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .change-value {
        display: flex;
        align-items: baseline;
        justify-content: center;
        gap: 0.3rem;
        margin-bottom: 1rem;
    }

    .change-sign {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .change-number {
        font-size: 3rem;
        font-weight: 900;
        line-height: 1;
    }

    .change-unit {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .change-value.positive {
        color: #28a745;
    }

    .change-value.negative {
        color: #dc3545;
    }

    .change-description {
        margin-bottom: 1rem;
    }

    .trend-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .trend-indicator.positive {
        background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        color: #155724;
        border: 1px solid #b1dfbb;
    }

    .trend-indicator.negative {
        background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
        color: #721c24;
        border: 1px solid #f1b0b7;
    }

    .metric-footer {
        margin-top: 1rem;
    }

    .progress-container {
        margin-bottom: 0.5rem;
    }

    .progress-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .progress-bar {
        width: 100%;
        height: 8px;
        background: #e9ecef;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .progress-fill {
        height: 100%;
        border-radius: 6px;
        transition: width 1.5s ease;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .progress-fill.positive {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    }

    .progress-fill.negative {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    }

    /* Performance Insights */
    .performance-insights {
        background: white;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border: 1px solid #e9ecef;
    }

    .insights-header {
        text-align: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f8f9fa;
    }

    .insights-title {
        font-size: 1.3rem;
        font-weight: 800;
        color: #495057;
        margin-bottom: 0;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.8rem;
    }

    .insights-title i {
        color: #ffc107;
        font-size: 1.2rem;
    }

    .insights-content {
        margin-bottom: 1rem;
    }

    .insight-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
        height: 100%;
    }

    .insight-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .insight-icon {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
    }

    .insight-text {
        flex: 1;
        font-size: 0.9rem;
        color: #495057;
        line-height: 1.5;
    }

    .insight-text strong {
        color: #343a40;
        font-weight: 700;
    }

    /* Responsive Design for Monthly Comparison */
    @media (max-width: 1200px) {
        .month-card {
            margin-bottom: 1.5rem;
        }
        
        .change-metric-card {
            margin-bottom: 1.5rem;
        }
        
        .insight-item {
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 768px) {
        .month-header {
            flex-direction: column;
            text-align: center;
            gap: 0.8rem;
        }
        
        .month-icon {
            width: 45px;
            height: 45px;
            font-size: 1.3rem;
        }
        
        .month-title {
            font-size: 1.2rem;
        }
        
        .month-period {
            font-size: 0.85rem;
        }
        
        .month-badge {
            padding: 0.3rem 0.8rem;
            font-size: 0.7rem;
        }
        
        .month-stat-item {
            padding: 0.8rem;
            gap: 0.8rem;
        }
        
        .stat-icon {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
        
        .stat-value {
            font-size: 1.5rem;
        }
        
        .stat-label {
            font-size: 0.8rem;
        }
        
        .stat-trend {
            width: 30px;
            height: 30px;
            font-size: 0.9rem;
        }
        
        .change-analysis-section {
            padding: 1rem;
        }
        
        .section-title {
            font-size: 1.2rem;
        }
        
        .section-subtitle {
            font-size: 0.9rem;
        }
        
        .change-metric-card {
            padding: 1rem;
        }
        
        .metric-header {
            flex-direction: column;
            text-align: center;
            gap: 0.8rem;
        }
        
        .metric-icon {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }
        
        .metric-title {
            font-size: 1rem;
        }
        
        .metric-subtitle {
            font-size: 0.8rem;
        }
        
        .change-value {
            gap: 0.2rem;
        }
        
        .change-sign {
            font-size: 1.3rem;
        }
        
        .change-number {
            font-size: 2.5rem;
        }
        
        .change-unit {
            font-size: 1.3rem;
        }
        
        .trend-indicator {
            padding: 0.4rem 0.8rem;
            font-size: 0.8rem;
        }
        
        .performance-insights {
            padding: 1rem;
        }
        
        .insights-title {
            font-size: 1.2rem;
        }
        
        .insight-item {
            padding: 0.8rem;
            gap: 0.8rem;
        }
        
        .insight-icon {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
        
        .insight-text {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 576px) {
        .month-card {
            padding: 1rem;
        }
        
        .month-header {
            gap: 0.6rem;
        }
        
        .month-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
        .month-title {
            font-size: 1.1rem;
        }
        
        .month-period {
            font-size: 0.8rem;
        }
        
        .month-badge {
            padding: 0.25rem 0.6rem;
            font-size: 0.65rem;
        }
        
        .month-stat-item {
            padding: 0.6rem;
            gap: 0.6rem;
        }
        
        .stat-icon {
            width: 30px;
            height: 30px;
            font-size: 0.9rem;
        }
        
        .stat-value {
            font-size: 1.3rem;
        }
        
        .stat-label {
            font-size: 0.75rem;
        }
        
        .stat-trend {
            width: 25px;
            height: 25px;
            font-size: 0.8rem;
        }
        
        .change-analysis-section {
            padding: 0.8rem;
        }
        
        .section-title {
            font-size: 1.1rem;
        }
        
        .section-subtitle {
            font-size: 0.85rem;
        }
        
        .change-metric-card {
            padding: 0.8rem;
        }
        
        .metric-header {
            gap: 0.6rem;
        }
        
        .metric-icon {
            width: 35px;
            height: 35px;
            font-size: 1rem;
        }
        
        .metric-title {
            font-size: 0.95rem;
        }
        
    .metric-subtitle {
        font-size: 0.75rem;
    }
        
        .change-value {
            gap: 0.15rem;
        }
        
        .change-sign {
            font-size: 1.1rem;
        }
        
        .change-number {
            font-size: 2rem;
        }
        
        .change-unit {
            font-size: 1.1rem;
        }
        
        .trend-indicator {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
        }
        
        .performance-insights {
            padding: 0.8rem;
        }
        
        .insights-title {
            font-size: 1.1rem;
        }
        
        .insight-item {
            padding: 0.6rem;
            gap: 0.6rem;
        }
        
        .insight-icon {
            width: 30px;
            height: 30px;
            font-size: 0.9rem;
        }
        
        .insight-text {
            font-size: 0.8rem;
        }
    }
    
    .card-modern {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    
    .card-header-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e9ecef;
        position: relative;
    }

    .card-header-modern::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2, #667eea);
        opacity: 0.7;
    }
    
    .card-title-modern {
        font-size: 1.3rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    
    .card-title-modern i {
        margin-right: 0.8rem;
        color: #667eea;
        font-size: 1.2rem;
        background: rgba(102, 126, 234, 0.1);
        padding: 0.8rem;
        border-radius: 12px;
        border: 2px solid rgba(102, 126, 234, 0.2);
    }
    
    .card-body-modern {
        padding: 1.5rem;
    }
    
    .card-subtitle-modern {
        color: #6c757d;
        margin-bottom: 0;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .shift-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-bottom: 2rem;
    }

    .shift-card {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 5px 15px rgba(44, 62, 80, 0.2);
        position: relative;
        overflow: hidden;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .shift-card.no-data {
        background: linear-gradient(135deg, #6c757d 0%, #495057 100%);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.2);
        opacity: 0.8;
    }





    .shift-card.no-data .btn {
        background: rgba(255, 255, 255, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.3);
        color: white;
    }

    .shift-summary {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 15px;
        border: 1px solid #dee2e6;
    }

    .summary-item {
        display: flex;
        align-items: center;
        gap: 0.8rem;
        padding: 1rem;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        border: 1px solid #e9ecef;
    }

    .summary-item i {
        font-size: 1.2rem;
        color: #667eea;
        background: rgba(102, 126, 234, 0.1);
        padding: 0.8rem;
        border-radius: 10px;
        border: 2px solid rgba(102, 126, 234, 0.2);
    }

    .summary-item span {
        font-weight: 600;
        color: #495057;
        font-size: 0.95rem;
    }

    .shift-actions .btn {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        border: none;
        border-radius: 8px;
        padding: 0.5rem 0.8rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
    }

    .shift-actions .btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }

    .shift-name {
        font-size: 1.3rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        background: rgba(255, 255, 255, 0.15);
        padding: 1rem 1.5rem;
        border-radius: 15px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        position: relative;
        margin-bottom: 1.5rem;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .shift-name i {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .shift-name::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: rgba(255, 255, 255, 0.6);
        border-radius: 2px;
    }

    .shift-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        width: 100%;
        align-content: center;
    }

    .shift-stat {
        background: rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        padding: 1.2rem 0.8rem;
        border: 1px solid rgba(255, 255, 255, 0.25);
        position: relative;
        overflow: hidden;
        min-height: 90px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-width: 100px;
    }

    .shift-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
        color: #ffffff;
        line-height: 1;
    }

    .shift-stat-label {
        font-size: 0.75rem;
        opacity: 0.9;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        color: rgba(255, 255, 255, 0.95);
        text-align: center;
        line-height: 1.2;
    }

    .shift-card.gece,
    .shift-card.gunduz,
    .shift-card.aksam {
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        box-shadow: 0 5px 15px rgba(44, 62, 80, 0.2);
    }

    /* Responsive Design for Shift Report */
    @media (max-width: 1200px) {
        .shift-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }
        
        .shift-card {
            min-height: 180px;
            padding: 1.5rem;
        }
        
        .shift-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.8rem;
        }
        
        .shift-stat {
            min-height: 80px;
            padding: 1rem 0.6rem;
        }
        
        .shift-stat-value {
            font-size: 1.3rem;
        }
        
        .shift-stat-label {
            font-size: 0.7rem;
        }
    }

    @media (max-width: 768px) {
        .shift-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .shift-card {
            min-height: auto;
            padding: 1.5rem;
        }
        
        .shift-stats {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.6rem;
        }
        
        .shift-stat {
            min-height: 70px;
            padding: 0.8rem 0.4rem;
            min-width: 80px;
        }
        
        .shift-stat-value {
            font-size: 1.1rem;
        }
        
        .shift-stat-label {
            font-size: 0.65rem;
            line-height: 1.1;
        }
        
        .shift-name {
            font-size: 1.1rem;
            padding: 0.8rem 1.2rem;
            margin-bottom: 1rem;
        }
    }

    @media (max-width: 576px) {
        .shift-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }
        
        .shift-stat {
            min-height: 60px;
            padding: 0.6rem 0.3rem;
            min-width: 70px;
        }
        
        .shift-stat-value {
            font-size: 1rem;
        }
        
        .shift-stat-label {
            font-size: 0.6rem;
        }
        
        .shift-name {
            font-size: 1rem;
            padding: 0.6rem 1rem;
        }

        .shift-name i {
            font-size: 0.9rem;
        }
    }

    /* Extra small devices */
    @media (max-width: 480px) {
        .shift-grid {
            gap: 0.8rem;
        }
        
        .shift-card {
            padding: 1rem;
        }
        
        .shift-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.4rem;
        }
        
        .shift-stat {
            min-height: 55px;
            padding: 0.5rem 0.2rem;
            min-width: 65px;
        }
        
        .shift-stat-value {
            font-size: 0.9rem;
        }
        
        .shift-stat-label {
            font-size: 0.55rem;
        }
        
        .shift-name {
            font-size: 0.9rem;
            padding: 0.5rem 0.8rem;
            margin-bottom: 0.8rem;
        }
        
        .shift-name i {
            font-size: 0.8rem;
        }
    }



    .chart-container {
        height: 300px;
        margin: 1rem 0;
    }

    .table-modern {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 1px solid #e9ecef;
    }

    .table-modern th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: none;
        padding: 1.5rem 1.5rem;
        font-weight: 600;
        color: #495057;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
    }

    .table-modern td {
        border: none;
        padding: 1.5rem;
        border-bottom: 1px solid #f1f3f4;
        font-size: 0.95rem;
        vertical-align: middle;
        transition: all 0.2s ease;
    }

    .table-modern tbody tr:hover {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .table-modern tbody tr:hover td {
        border-bottom-color: #dee2e6;
    }

    .badge-modern {
        padding: 0.6rem 1.2rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .badge-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .badge-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .badge-danger {
        background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .badge-warning {
        background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
        color: #212529;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .badge-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .badge-secondary {
        background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
        color: white;
        border: 1px solid rgba(255,255,255,0.2);
    }

    /* New styles for Trend Analysis */
    .ai-insights-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
        margin-bottom: 1.5rem;
    }

    .insight-header {
        display: flex;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 0.8rem;
        border-bottom: 1px solid #dee2e6;
    }

    .insight-header i {
        font-size: 1.8rem;
        margin-right: 1rem;
        color: #667eea;
    }

    .insight-header h5 {
        margin-bottom: 0;
        color: #495057;
    }

    .insight-content {
        font-size: 0.95rem;
        color: #6c757d;
        line-height: 1.6;
    }

    .prediction-item {
        margin-bottom: 0.5rem;
    }

    .prediction-label {
        font-weight: 600;
        color: #343a40;
    }

    .prediction-value {
        font-weight: 700;
        color: #667eea;
    }

    .trend-indicator {
        display: flex;
        align-items: center;
        margin-top: 0.5rem;
    }

    .trend-indicator i {
        font-size: 0.8rem;
        margin-right: 0.5rem;
    }

    .risk-indicator {
        margin-bottom: 0.5rem;
    }

    .risk-label {
        font-weight: 600;
        color: #343a40;
    }

    .risk-badge {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        border-radius: 15px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .risk-low {
        background: #e9ecef;
        color: #495057;
        border: 1px solid #ced4da;
    }

    .risk-medium {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeeba;
    }

    .risk-high {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .anomaly-item {
        background: #fdfdfe;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.8rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .anomaly-type {
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.3rem;
    }

    .anomaly-description {
        font-size: 0.9rem;
        color: #495057;
        margin-bottom: 0.5rem;
    }

    .anomaly-severity {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .severity-low {
        color: #28a745;
        background: #e9f5ea;
        border: 1px solid #c3e6cb;
    }

    .severity-medium {
        color: #ffc107;
        background: #fffbeb;
        border: 1px solid #ffe58f;
    }

    .severity-high {
        color: #dc3545;
        background: #fde3e3;
        border: 1px solid #f5c6cb;
    }

    .no-anomalies {
        display: flex;
        align-items: center;
        color: #28a745;
        font-size: 0.9rem;
    }

    .no-anomalies i {
        margin-right: 0.5rem;
    }

    .no-recommendations {
        display: flex;
        align-items: center;
        color: #17a2b8;
        font-size: 0.9rem;
    }

    .no-recommendations i {
        margin-right: 0.5rem;
    }

    .recommendation {
        margin-top: 1rem;
        font-style: italic;
        color: #6c757d;
    }

    .recommendation strong {
        font-weight: 600;
        color: #343a40;
    }

    .recommendation-item {
        margin-bottom: 0.8rem;
    }

    .recommendation-category {
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.3rem;
    }

    .recommendation-text {
        font-size: 0.9rem;
        color: #495057;
        margin-bottom: 0.3rem;
    }

    .recommendation-impact {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .impact-label {
        font-weight: 600;
        color: #343a40;
    }

    .impact-value {
        font-weight: 700;
        color: #667eea;
    }

    /* General Responsive Design */
    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .shift-summary {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        
        .card-header-modern {
            padding: 1.5rem 2rem;
        }
        
        .card-body-modern {
            padding: 1.5rem;
        }
        
        .page-header-modern {
            padding: 1.2rem;
        }
        
        .page-title-modern {
            font-size: 1.8rem;
        }
        
        .page-title-modern i {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 768px) {
        .modern-dashboard {
            padding: 1rem 0;
        }
        
        .page-header-modern {
            padding: 1rem;
            margin-bottom: 1rem;
        }
        
        .page-title-modern {
            font-size: 1.6rem;
        }
        
        .page-title-modern i {
            font-size: 1.4rem;
        }
        
        .page-subtitle-modern {
            font-size: 1rem;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .shift-summary {
            grid-template-columns: 1fr;
            gap: 0.8rem;
            padding: 1rem;
        }
        
        .summary-item {
            padding: 0.8rem;
        }
        
        .summary-item i {
            font-size: 1rem;
            padding: 0.6rem;
        }
        
        .summary-item span {
            font-size: 0.9rem;
        }
        
        .card-header-modern {
            padding: 1rem 1.5rem;
        }
        
        .card-body-modern {
            padding: 1rem;
        }
        

        
        .table-modern th,
        .table-modern td {
            padding: 1rem 0.8rem;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 576px) {
        .page-header-modern {
            padding: 0.8rem;
            border-radius: 12px;
        }
        
        .page-title-modern {
            font-size: 1.4rem;
        }
        
        .page-title-modern i {
            font-size: 1.2rem;
            margin-right: 0.6rem;
        }
        
        .shift-summary {
            padding: 0.8rem;
            gap: 0.6rem;
        }
        
        .summary-item {
            padding: 0.6rem;
        }
        
        .summary-item i {
            font-size: 0.9rem;
            padding: 0.5rem;
        }
        
        .summary-item span {
            font-size: 0.85rem;
        }
        
        .shift-actions .btn {
            padding: 0.4rem 0.6rem;
            font-size: 0.8rem;
        }
        
        .card-modern {
            border-radius: 15px;
            margin-bottom: 1rem;
        }
        
        .card-header-modern {
            padding: 0.8rem 1rem;
        }
        
        .card-title-modern {
            font-size: 1.1rem;
        }
        
        .card-title-modern i {
            font-size: 1rem;
            padding: 0.6rem;
            margin-right: 0.6rem;
        }
        
        .card-body-modern {
            padding: 0.8rem;
        }
        
        .date-selector {
            padding: 0.8rem;
        }
        
        .date-selector label {
            font-size: 1rem;
            margin-right: 0.8rem;
        }
        
        .date-selector input {
            min-width: 120px;
            padding: 0.4rem 0.8rem;
            font-size: 0.9rem;
        }
        
        .table-modern th,
        .table-modern td {
            padding: 0.8rem 0.6rem;
            font-size: 0.85rem;
        }
        
        .table-modern th {
            font-size: 0.9rem;
        }
    }

    .impact-low {
        color: #28a745;
    }

    .impact-medium {
        color: #ffc107;
    }

    .impact-high {
        color: #dc3545;
    }

    .ml-status-section {
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #dee2e6;
    }

    .model-status-card {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.2rem;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
        text-align: center;
    }

    .status-header {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.8rem;
    }

    .status-header i {
        font-size: 2rem;
        margin-right: 0.8rem;
        color: #667eea;
    }

    .status-header span {
        font-weight: 700;
        color: #495057;
        font-size: 1.1rem;
    }

    .status-indicator {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.4rem 1rem;
        border-radius: 15px;
    }

    .status-active {
        background: #e9ecef;
        color: #495057;
        border: 1px solid #ced4da;
    }
    
    .status-inactive {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .model-accuracy {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }
    
    .accuracy-bar {
        width: 100%;
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }
    
    .accuracy-fill {
        height: 100%;
        background: linear-gradient(90deg, #28a745, #20c997);
        border-radius: 4px;
        transition: width 0.8s ease;
    }
    
    .accuracy-text {
        font-weight: 600;
        color: #495057;
    }
    

    
    /* Production Efficiency Styles */
    .efficiency-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .efficiency-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .efficiency-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        line-height: 1;
    }
    
    .efficiency-value.excellent {
        color: #28a745;
    }
    
    .efficiency-value.good {
        color: #20c997;
    }
    
    .efficiency-value.average {
        color: #ffc107;
    }
    
    .efficiency-value.poor {
        color: #fd7e14;
    }
    
    .efficiency-value.critical {
        color: #dc3545;
    }
    
    .efficiency-value.availability {
        color: #17a2b8;
    }
    
    .efficiency-value.performance {
        color: #6f42c1;
    }
    
    .efficiency-value.quality {
        color: #e83e8c;
    }
    
    .efficiency-label {
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .efficiency-level {
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 0.4rem 1rem;
        border-radius: 15px;
        display: inline-block;
    }
    
    .efficiency-level.excellent {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .efficiency-level.good {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
    
    .efficiency-level.average {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }
    
    .efficiency-level.poor {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .efficiency-level.critical {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .efficiency-desc {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 0.5rem;
    }
    
    .efficiency-details {
        background: #f8f9fa;
        border-radius: 15px;
        padding: 1.5rem;
        border: 1px solid #e9ecef;
    }
    
    .efficiency-stats {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .efficiency-stats li {
        padding: 0.5rem 0;
        border-bottom: 1px solid #dee2e6;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .efficiency-stats li:last-child {
        border-bottom: none;
    }
    
    .efficiency-stats strong {
        color: #495057;
        font-weight: 600;
    }
    
    /* Info Button Styles */
    .card-header-modern .btn-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        border: none;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .card-header-modern .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(23, 162, 184, 0.3);
    }
    
    .card-header-modern .btn-info i {
        margin-right: 0.5rem;
    }
    
    /* Modal Styles */
    .info-modal .modal-header {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        color: white;
        border-radius: 15px 15px 0 0;
    }
    
    .info-modal .modal-title {
        font-weight: 600;
        font-size: 1.3rem;
    }
    
    .info-modal .modal-body {
        padding: 2rem;
        font-size: 1rem;
        line-height: 1.6;
    }
    
    .info-modal .metric-explanation {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin: 1rem 0;
        border-left: 4px solid #17a2b8;
    }
    
    .info-modal .metric-title {
        font-weight: 600;
        color: #17a2b8;
        margin-bottom: 0.5rem;
        font-size: 1.1rem;
    }
    
    .info-modal .metric-desc {
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .info-modal .metric-formula {
        background: #e9ecef;
        padding: 0.75rem;
        border-radius: 8px;
        font-family: 'Courier New', monospace;
        font-size: 0.9rem;
        color: #495057;
        margin: 0.5rem 0;
    }
    
    .info-modal .example-box {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 10px;
        padding: 1.5rem;
        margin: 1rem 0;
    }
    
    .info-modal .example-title {
        font-weight: 600;
        color: #856404;
        margin-bottom: 0.5rem;
    }
    
    .info-modal .level-indicator {
        display: inline-block;
        padding: 0.3rem 0.8rem;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 600;
        margin: 0.2rem;
    }
    
    .level-indicator.excellent { background: #d4edda; color: #155724; }
    .level-indicator.good { background: #d1ecf1; color: #0c5460; }
    .level-indicator.average { background: #fff3cd; color: #856404; }
    .level-indicator.poor { background: #f8d7da; color: #721c24; }
    .level-indicator.critical { background: #f8d7da; color: #721c24; }

</style>
@endsection

@section('content')
<div class="modern-dashboard">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <div class="d-flex justify-content-between align-items-start">
                <div class="page-title-section">
                    <h1 class="page-title-modern">
                        <i class="fas fa-chart-bar"></i>
                        Granilya Üretim Raporu
                    </h1>
                    <p class="page-subtitle-modern">Granilya hattı geneli istatistikler ve Kırıcı (Crusher) bazlı analizler</p>
                </div>
            </div>
        </div>
        
        <!-- Date and Period Filters -->
        <div class="filters-section">
            <div class="filters-container">
                <div class="filter-group">
                    <div class="filter-item">
                        <form method="GET" action="{{ route('granilya.report') }}" class="d-flex align-items-center">
                            <label for="date">📅 Rapor Tarihi:</label>
                            <input type="date" id="date" name="date" value="{{ $selectedDate }}" 
                                   class="form-control ml-2" onchange="this.form.submit()">
                        </form>
                    </div>
                    
                    <div class="filter-item">
                        <form method="GET" action="{{ route('granilya.report') }}" class="d-flex align-items-center">
                            <label for="period">📊 Periyot:</label>
                            <select id="period" name="period" class="form-control ml-2">
                                <option value="daily" {{ $period === 'daily' ? 'selected' : '' }}>Günlük</option>
                                <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Haftalık</option>
                                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Aylık</option>
                                <option value="quarterly" {{ $period === 'quarterly' ? 'selected' : '' }}>3 Aylık</option>
                                <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yıllık</option>
                                <option value="custom" {{ $period === 'custom' ? 'selected' : '' }}>Özel Tarih Aralığı</option>
                            </select>
                        </form>
                    </div>
                    
                    <!-- Custom Date Range Selector -->
                    <div class="filter-item custom-date-filter" id="customDateSelector" style="display: {{ $period === 'custom' ? 'block' : 'none' }};">
                        <form method="GET" action="{{ route('granilya.report') }}" class="d-flex align-items-center">
                            <label for="start_date">📅 Başlangıç:</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDateStr ?? '' }}" 
                                   class="form-control ml-1 mr-1" max="{{ date('Y-m-d') }}">
                            <label for="end_date" class="ms-3">📅 Bitiş:</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDateStr ?? '' }}" 
                                   class="form-control ml-1" max="{{ date('Y-m-d') }}">
                            <input type="hidden" name="period" value="custom">
                            <button type="submit" class="btn btn-primary btn-sm ms-3 ml-3">
                                <i class="fas fa-search"></i> Uygula
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="filter-info">
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> 
                        Seçilen periyoda göre veriler toplanır
                        @if($period === 'custom')
                            • Gelecekteki tarihler otomatik olarak bugüne sınırlanır
                        @endif
                    </small>
                </div>
            </div>
        </div>

        <!-- Production Stats -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-calendar-day"></i>
                    {{ $periodInfo['range'] }} {{ $periodInfo['name'] }} Üretim Özeti
                </h3>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-info-circle"></i> 
                    Periyot: {{ $periodInfo['start_date_formatted'] }} - {{ $periodInfo['end_date_formatted'] }}
                </small>
            </div>
            <div class="card-body-modern">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($productionData['total_barcodes']) }}</div>
                        <div class="stat-label">Toplam Palet</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($productionData['total_quantity'], 1) }}</div>
                        <div class="stat-label">Toplam Miktar (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($productionData['accepted_quantity'], 1) }}</div>
                        <div class="stat-label">Kabul Edilen (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($productionData['testing_quantity'], 1) }}</div>
                        <div class="stat-label">Test Sürecinde (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($productionData['delivery_quantity'], 1) }}</div>
                        <div class="stat-label">Teslimat Sürecinde (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($productionData['rejected_quantity'], 1) }}</div>
                        <div class="stat-label">Reddedilen (ton)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Red Sebepleri Analizi (Simplified vs DashboardController) -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-exclamation-triangle"></i>
                        Red Sebepleri Analizi
                    </h3>
                </div>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    @forelse($rejectionReasonsAnalysis as $reasonData)
                        <div class="col-md-4 mb-3">
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                                <div>
                                    <span class="badge badge-danger">{{ $reasonData->reason_name }}</span>
                                    <div class="small text-muted mt-1">
                                        {{ $reasonData->count }} ürün ({{ number_format($reasonData->total_quantity, 1) }} KG)
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-danger font-weight-bold">
                                        {{ number_format($reasonData->percentage, 1) }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-success m-3">
                            <i class="fas fa-check-circle fa-2x mb-2"></i><br>
                            Bu periyotta reddedilen ürün bulunmamaktadır.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Shift Report - Only for Daily Period -->
        @if($period === 'daily')
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-clock"></i>
                        Vardiya Raporu
                    </h3>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-info-circle"></i> 
                    Vardiya raporu sadece günlük periyotta mevcuttur
                </small>
            </div>
            <div class="card-body-modern">
                <!-- Shift Summary -->
                <div class="shift-summary">
                    <div class="summary-item">
                        <i class="fas fa-calendar-day"></i>
                        <span>Tarih: {{ \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') }}</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-clock"></i>
                        <span>Toplam Vardiya: {{ count($shiftReport) }}</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-barcode"></i>
                        <span>Toplam Palet: {{ array_sum(array_column($shiftReport, 'barcode_count')) ?? 0 }}</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-weight-hanging"></i>
                        <span>Toplam Miktar: {{ number_format(array_sum(array_column($shiftReport, 'total_quantity')) ?? 0, 1) }} ton</span>
                    </div>
                </div>
                
                <div class="shift-grid">
                    @forelse($shiftReport as $shiftName => $shiftData)
                        <div class="shift-card {{ $shiftName }}" title="{{ ucfirst($shiftName) }} Vardiyası">
                            <div class="shift-name">
                                @if($shiftName === 'gece')
                                    <i class="fas fa-moon"></i> 00:00 - 08:00
                                @elseif($shiftName === 'gündüz')
                                    <i class="fas fa-sun"></i> 08:00 - 16:00
                                @elseif($shiftName === 'akşam')
                                    <i class="fas fa-cloud-sun"></i> 16:00 - 24:00
                                @else
                                    <i class="fas fa-clock"></i> {{ ucfirst($shiftName) }}
                                @endif
                            </div>
                            <div class="shift-stats">
                                <div class="shift-stat" title="Toplam Palet Sayısı">
                                    <div class="shift-stat-value">{{ number_format($shiftData['barcode_count'] ?? 0) }}</div>
                                    <div class="shift-stat-label">Palet</div>
                                </div>
                                <div class="shift-stat" title="Toplam Miktar (ton)">
                                    <div class="shift-stat-value">{{ number_format($shiftData['total_quantity'] ?? 0, 1) }}</div>
                                    <div class="shift-stat-label">Toplam (ton)</div>
                                </div>
                                <div class="shift-stat" title="Kabul Edilen Miktar (ton)">
                                    <div class="shift-stat-value">{{ number_format($shiftData['accepted_quantity'] ?? 0, 1) }}</div>
                                    <div class="shift-stat-label">Kabul (ton)</div>
                                </div>
                                <div class="shift-stat" title="Reddedilen Miktar (ton)">
                                    <div class="shift-stat-value">{{ number_format($shiftData['rejected_quantity'] ?? 0, 1) }}</div>
                                    <div class="shift-stat-label">Red (ton)</div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="shift-card no-data" style="grid-column: 1 / -1; text-align: center;">
                            <div class="shift-name">
                                <i class="fas fa-info-circle"></i> Veri Bulunamadı
                            </div>
                            <div class="shift-stats">
                                <p style="color: rgba(255, 255, 255, 0.8); margin: 0;">Seçilen tarih için vardiya verisi bulunamadı.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        <!-- Crusher Performance -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-cogs"></i>
                        Kırıcı Performans Analizi
                    </h3>
                </div>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Kırıcı Adı</th>
                                <th>Palet Sayısı</th>
                                <th>Toplam Miktar (ton)</th>
                                <th>Kabul Edilen (ton)</th>
                                <th>Reddedilen (ton)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($crusherPerformance as $crusher)
                            <tr>
                                <td><strong>{{ $crusher->crusher_name }}</strong></td>
                                <td>{{ number_format($crusher->barcode_count) }}</td>
                                <td>{{ number_format($crusher->total_quantity, 1) }}</td>
                                <td><span class="badge badge-success">{{ number_format($crusher->accepted_quantity, 1) }}</span></td>
                                <td><span class="badge badge-danger">{{ number_format($crusher->rejected_quantity, 1) }}</span></td>
                            </tr>
                            @endforeach
                            
                            @if(count($crusherPerformance) === 0)
                            <tr>
                                <td colspan="5" class="text-center">Bu periyotta veri bulunmamaktadır.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Crusher Rejection Rates -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-exclamation-triangle"></i>
                    Kırıcı Red Oranları
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Kırıcı Adı</th>
                                <th>Toplam Miktar (ton)</th>
                                <th>Reddedilen (ton)</th>
                                <th>Red Oranı (%)</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($crusherRejectionRates as $crusher)
                            <tr>
                                <td><strong>{{ $crusher->crusher_name }}</strong></td>
                                <td>{{ number_format($crusher->total_quantity, 1) }}</td>
                                <td>{{ number_format($crusher->rejected_quantity, 1) }}</td>
                                <td><strong>{{ number_format($crusher->rejection_rate, 1) }}%</strong></td>
                                <td>
                                    @if($crusher->rejection_rate <= 5)
                                        <span class="badge badge-success">Düşük</span>
                                    @elseif($crusher->rejection_rate <= 15)
                                        <span class="badge badge-warning">Orta</span>
                                    @else
                                        <span class="badge badge-danger">Yüksek</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @if(count($crusherRejectionRates) === 0)
                            <tr>
                                <td colspan="5" class="text-center">Bu periyotta veri bulunmamaktadır.</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @include('granilya.production.partials_stock_age')

        <!-- Monthly Comparison -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-chart-line"></i>
                        Aylık Karşılaştırma Analizi
                    </h3>
                    <div class="d-flex align-items-center gap-5">
                        <span class="monthly-comparison-badge">
                            <i class="fas fa-calendar-alt"></i> 
                            {{ $monthlyComparison['current_month_name'] }} vs {{ $monthlyComparison['previous_month_name'] }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="card-body-modern">
                <!-- Main Comparison Cards -->
                <div class="monthly-comparison-overview">
                    <div class="row">
                        @php
                            $currTotal = array_sum($monthlyComparison['current']);
                            $prevTotal = array_sum($monthlyComparison['previous']);
                            $diff = $currTotal - $prevTotal;
                            $percentDiff = $prevTotal > 0 ? ($diff / $prevTotal) * 100 : ($currTotal > 0 ? 100 : 0);
                        @endphp
                        
                        <!-- Current Month -->
                        <div class="col-lg-6 mb-3">
                            <div class="month-card current-month">
                                <div class="month-header">
                                    <div class="month-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="month-info">
                                        <h4 class="month-title">Bu Ay</h4>
                                        <p class="month-period">{{ $monthlyComparison['current_month_name'] }}</p>
                                    </div>
                                </div>
                                <div class="month-stats">
                                    <div class="month-stat-item">
                                        <div class="month-stat-value">{{ number_format($currTotal, 1) }}</div>
                                        <div class="month-stat-label">Toplam Tonaj</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Previous Month -->
                        <div class="col-lg-6 mb-3">
                            <div class="month-card previous-month">
                                <div class="month-header">
                                    <div class="month-icon">
                                        <i class="fas fa-history"></i>
                                    </div>
                                    <div class="month-info">
                                        <h4 class="month-title">Geçen Ay</h4>
                                        <p class="month-period">{{ $monthlyComparison['previous_month_name'] }}</p>
                                    </div>
                                </div>
                                <div class="month-stats">
                                    <div class="month-stat-item">
                                        <div class="month-stat-value">{{ number_format($prevTotal, 1) }}</div>
                                        <div class="month-stat-label">Toplam Tonaj</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="chart-container">
                    <canvas id="monthlyComparisonChart"></canvas>
                </div>
            </div>
        </div>

        @include('granilya.production.partials_ai_insights')

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle Custom Date Selector
        const periodSelect = document.getElementById('period');
        const customDateSelector = document.getElementById('customDateSelector');
        
        periodSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateSelector.style.display = 'block';
            } else {
                customDateSelector.style.display = 'none';
                this.form.submit();
            }
        });

        // Custom date range validation
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        if (startDateInput && endDateInput) {
            startDateInput.addEventListener('change', function() {
                endDateInput.min = this.value;
            });
            
            endDateInput.addEventListener('change', function() {
                startDateInput.max = this.value;
            });
        }
        
        // Monthly Comparison Line Chart
        const ctxMonthly = document.getElementById('monthlyComparisonChart').getContext('2d');
        const monthlyData = @json($monthlyComparison);
        
        new Chart(ctxMonthly, {
            type: 'line',
            data: {
                labels: monthlyData.labels,
                datasets: [
                    {
                        label: monthlyData.current_month_name,
                        data: monthlyData.current,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 2,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#667eea',
                        pointHoverBackgroundColor: '#667eea',
                        pointHoverBorderColor: '#ffffff',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: monthlyData.previous_month_name,
                        data: monthlyData.previous,
                        borderColor: '#adb5bd',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#adb5bd',
                        pointHoverBackgroundColor: '#adb5bd',
                        pointHoverBorderColor: '#ffffff',
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        fill: false,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: { family: "'Inter', sans-serif", size: 12, weight: 'bold' }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#343a40',
                        bodyColor: '#495057',
                        borderColor: '#e9ecef',
                        borderWidth: 1,
                        padding: 12,
                        boxPadding: 6,
                        callbacks: {
                            title: function(context) { return 'Gün ' + context[0].label; },
                            label: function(context) { return context.dataset.label + ': ' + context.raw.toFixed(1) + ' Ton'; }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: "'Inter', sans-serif" } },
                        title: { display: true, text: 'Ayın Günleri', font: { weight: 'bold' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4], color: '#f1f3f5' },
                        ticks: {
                            font: { family: "'Inter', sans-serif" },
                            callback: function(value) { return value + ' T'; }
                        },
                        title: { display: true, text: 'Üretim (Ton)', font: { weight: 'bold' } }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        });
    });
</script>
@endsection
