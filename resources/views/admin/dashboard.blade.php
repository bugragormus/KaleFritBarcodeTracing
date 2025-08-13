@extends('layouts.app')

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

    .date-selector {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .date-selector label {
        color: white;
        font-weight: 600;
        margin-right: 1rem;
        font-size: 1.1rem;
    }

    .date-selector input {
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 12px;
        padding: 0.75rem 1.25rem;
        color: #333;
        font-weight: 500;
        font-size: 1rem;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        min-width: 200px;
    }

    .date-selector input:focus {
        outline: none;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }

    .date-selector input:hover {
        box-shadow: 0 5px 18px rgba(0, 0, 0, 0.12);
    }

    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        border-radius: 10px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        background: linear-gradient(135deg, #218838 0%, #1ea085 100%);
    }

    .btn-success i {
        margin-right: 0.5rem;
    }
    
    .card-modern {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: 1px solid #e9ecef;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .card-header-modern {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem 2.5rem;
        border-bottom: 2px solid #e9ecef;
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
        font-size: 1.4rem;
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
        font-size: 1.3rem;
        background: rgba(102, 126, 234, 0.1);
        padding: 0.8rem;
        border-radius: 12px;
        border: 2px solid rgba(102, 126, 234, 0.2);
    }
    
    .card-body-modern {
        padding: 2rem;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #e9ecef;
        border-radius: 15px;
        padding: 1.5rem;
        text-align: center;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #667eea;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 500;
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

    .warning-card {
        border-left: 4px solid;
        margin-bottom: 1rem;
    }

    .warning-critical {
        border-left-color: #dc3545;
        background: #f8d7da;
    }

    .warning-warning {
        border-left-color: #ffc107;
        background: #fff3cd;
    }

    .warning-info {
        border-left-color: #17a2b8;
        background: #d1ecf1;
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

    /* New styles for AI/ML Insights */
    .insight-card {
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
            padding: 1.5rem;
        }
        
        .page-title-modern {
            font-size: 2rem;
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
            font-size: 1.8rem;
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
        
        .date-selector {
            padding: 1rem;
        }
        
        .date-selector input {
            min-width: 150px;
            padding: 0.5rem 1rem;
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
            border-radius: 15px;
        }
        
        .page-title-modern {
            font-size: 1.5rem;
        }
        
        .page-title-modern i {
            font-size: 1.5rem;
            margin-right: 0.8rem;
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
            font-size: 1.2rem;
        }
        
        .card-title-modern i {
            font-size: 1.1rem;
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

    .model-accuracy {
        font-size: 0.85rem;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="modern-dashboard">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="page-header-modern">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="page-title-modern">
                        <i class="fas fa-chart-bar"></i>
                        Günlük Üretim Raporu
                    </h1>
                    <p class="page-subtitle-modern">Sistem geneli istatistikler ve performans göstergeleri</p>
                </div>
                
                <!-- Date Selector -->
                <div class="date-selector">
                    <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center">
                        <label for="date">📅 Rapor Tarihi (Bugün):</label>
                        <input type="date" id="date" name="date" value="{{ $selectedDate }}" 
                               class="form-control" onchange="this.form.submit()">
                    </form>
                </div>
            </div>
        </div>

        <!-- Daily Production Stats -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-calendar-day"></i>
                    {{ $date->format('d.m.Y') }} Günlük Üretim Özeti
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['total_barcodes']) }}</div>
                        <div class="stat-label">Toplam Barkod</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['total_quantity'], 1) }}</div>
                        <div class="stat-label">Toplam Miktar (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['accepted_quantity'], 1) }}</div>
                        <div class="stat-label">Kabul Edilen (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['testing_quantity'], 1) }}</div>
                        <div class="stat-label">Test Sürecinde (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['delivery_quantity'], 1) }}</div>
                        <div class="stat-label">Teslimat Sürecinde (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['rejected_quantity'], 1) }}</div>
                        <div class="stat-label">Reddedilen (ton)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shift Report -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-clock"></i>
                        Vardiya Raporu
                    </h3>
                    <div class="shift-actions">
                        <button class="btn btn-info btn-sm" onclick="refreshShiftReport()" title="Yenile">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
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
                        <span>Toplam Barkod: {{ array_sum(array_column($shiftReport, 'barcode_count')) ?? 0 }}</span>
                    </div>
                    <div class="summary-item">
                        <i class="fas fa-weight-hanging"></i>
                        <span>Toplam Miktar: {{ number_format(array_sum(array_column($shiftReport, 'total_quantity')) ?? 0, 1) }} ton</span>
                    </div>
                </div>
                
                <div class="shift-grid">
                    @forelse($shiftReport as $shiftName => $shiftData)
                        <div class="shift-card {{ $shiftName }}" title="{{ ucfirst($shiftName) }} Vardiyası - {{ \Carbon\Carbon::parse($selectedDate)->format('d.m.Y') }}">
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
                                <div class="shift-stat" title="Toplam Barkod Sayısı">
                                    <div class="shift-stat-value">{{ number_format($shiftData['barcode_count'] ?? 0) }}</div>
                                    <div class="shift-stat-label">Barkod</div>
                                </div>
                                <div class="shift-stat" title="Toplam Miktar (ton)">
                                    <div class="shift-stat-value">{{ number_format($shiftData['total_quantity'] ?? 0, 1) }}</div>
                                    <div class="shift-stat-label">Toplam (ton)</div>
                                </div>
                                <div class="shift-stat" title="Kabul Edilen Miktar (ton)">
                                    <div class="shift-stat-value">{{ number_format($shiftData['accepted_quantity'] ?? 0, 1) }}</div>
                                    <div class="shift-stat-label">Kabul (ton)</div>
                                </div>
                                <div class="shift-stat" title="Test Sürecinde Miktar (ton)">
                                    <div class="shift-stat-value">{{ number_format($shiftData['testing_quantity'] ?? 0, 1) }}</div>
                                    <div class="shift-stat-label">Test (ton)</div>
                                </div>
                                <div class="shift-stat" title="Teslimat Sürecinde Miktar (ton)">
                                    <div class="shift-stat-value">{{ number_format($shiftData['delivery_quantity'] ?? 0, 1) }}</div>
                                    <div class="shift-stat-label">Teslimat (ton)</div>
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
                                <button class="btn btn-light btn-sm mt-2" onclick="refreshShiftReport()">
                                    <i class="fas fa-sync-alt"></i> Yenile
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Kiln Performance -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-fire"></i>
                        Fırın Performans Analizi
                    </h3>
                    <button class="btn btn-success btn-sm" onclick="exportKilnPerformance(event)">
                        <i class="fas fa-file-excel"></i> CSV İndir
                    </button>
                </div>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Fırın Adı</th>
                                <th>Barkod Sayısı</th>
                                <th>Toplam Miktar (ton)</th>
                                <th>Ortalama Miktar (ton)</th>
                                <th>Kabul Edilen (ton)</th>
                                <th>Test Sürecinde (ton)</th>
                                <th>Teslimat Sürecinde (ton)</th>
                                <th>Reddedilen (ton)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kilnPerformance as $kiln)
                            <tr>
                                <td><strong>{{ $kiln->kiln_name }}</strong></td>
                                <td>{{ number_format($kiln->barcode_count) }}</td>
                                <td>{{ number_format($kiln->total_quantity, 1) }}</td>
                                <td>{{ number_format($kiln->avg_quantity, 1) }}</td>
                                <td><span class="badge badge-success">{{ number_format($kiln->accepted_quantity, 1) }}</span></td>
                                <td><span class="badge badge-info">{{ number_format($kiln->testing_quantity ?? 0, 1) }}</span></td>
                                <td><span class="badge badge-warning">{{ number_format($kiln->delivery_quantity ?? 0, 1) }}</span></td>
                                <td><span class="badge badge-danger">{{ number_format($kiln->rejected_quantity, 1) }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Kiln Rejection Rates -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-exclamation-triangle"></i>
                    Fırın Red Oranları
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Fırın Adı</th>
                                <th>Toplam Barkod</th>
                                <th>Reddedilen (ton)</th>
                                <th>Red Oranı (%)</th>
                                <th>Durum</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kilnRejectionRates as $kiln)
                            <tr>
                                <td><strong>{{ $kiln->kiln_name }}</strong></td>
                                <td>{{ number_format($kiln->total_barcodes) }}</td>
                                <td>{{ number_format($kiln->rejected_quantity, 1) }}</td>
                                <td><strong>{{ $kiln->rejection_rate }}%</strong></td>
                                <td>
                                    @if($kiln->rejection_rate <= 5)
                                        <span class="badge badge-success">Düşük</span>
                                    @elseif($kiln->rejection_rate <= 15)
                                        <span class="badge badge-warning">Orta</span>
                                    @else
                                        <span class="badge badge-danger">Yüksek</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stock Age Warnings -->
        @if(!empty($stockAgeWarnings))
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-clock"></i>
                    Stok Yaşı Uyarıları
                </h3>
            </div>
            <div class="card-body-modern">
                @if(isset($stockAgeWarnings['critical']))
                <h5 class="text-danger mb-3">🚨 Kritik Uyarı (30+ gün)</h5>
                @foreach($stockAgeWarnings['critical'] as $stock)
                <div class="warning-card warning-critical p-3">
                    <strong>{{ $stock->name }}</strong> ({{ $stock->code }}) - 
                    Son üretim: {{ $stock->last_production_date ? \Carbon\Carbon::parse($stock->last_production_date)->format('d.m.Y') : 'Hiç üretilmemiş' }}
                    @if($stock->last_production_date)
                        - <span class="text-danger">{{ $stock->days_old }} gün önce</span>
                    @endif
                </div>
                @endforeach
                @endif

                @if(isset($stockAgeWarnings['warning']))
                <h5 class="text-warning mb-3 mt-4">⚠️ Uyarı (15+ gün)</h5>
                @foreach($stockAgeWarnings['warning'] as $stock)
                <div class="warning-card warning-warning p-3">
                    <strong>{{ $stock->name }}</strong> ({{ $stock->code }}) - 
                    Son üretim: {{ \Carbon\Carbon::parse($stock->last_production_date)->format('d.m.Y') }} 
                    - <span class="text-warning">{{ $stock->days_old }} gün önce</span>
                </div>
                @endforeach
                @endif

                @if(isset($stockAgeWarnings['info']))
                <h5 class="text-info mb-3 mt-4">ℹ️ Bilgi (7+ gün)</h5>
                @foreach($stockAgeWarnings['info'] as $stock)
                <div class="warning-card warning-info p-3">
                    <strong>{{ $stock->name }}</strong> ({{ $stock->code }}) - 
                    Son üretim: {{ \Carbon\Carbon::parse($stock->last_production_date)->format('d.m.Y') }} 
                    - <span class="text-info">{{ $stock->days_old }} gün önce</span>
                </div>
                @endforeach
                @endif
            </div>
        </div>
        @endif

        <!-- Product Kiln Analysis -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-line"></i>
                    Ürün Özelinde Fırın Kapasite Analizi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="table-responsive">
                    <table class="table table-modern">
                        <thead>
                            <tr>
                                <th>Ürün</th>
                                <th>Fırın</th>
                                <th>Barkod Sayısı</th>
                                <th>Toplam Miktar (ton)</th>
                                <th>Kabul Edilen (ton)</th>
                                <th>Test Sürecinde (ton)</th>
                                <th>Teslimat Sürecinde (ton)</th>
                                <th>Reddedilen (ton)</th>
                                <th>Kabul Oranı (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($productKilnAnalysis as $analysis)
                            <tr>
                                <td><strong>{{ $analysis->stock_name }}</strong><br><small>{{ $analysis->stock_code }}</small></td>
                                <td>{{ $analysis->kiln_name }}</td>
                                <td>{{ number_format($analysis->barcode_count) }}</td>
                                <td>{{ number_format($analysis->total_quantity, 1) }}</td>
                                <td><span class="badge badge-success">{{ number_format($analysis->accepted_quantity, 1) }}</span></td>
                                <td><span class="badge badge-info">{{ number_format($analysis->testing_quantity ?? 0, 1) }}</span></td>
                                <td><span class="badge badge-warning">{{ number_format($analysis->delivery_quantity ?? 0, 1) }}</span></td>
                                <td><span class="badge badge-danger">{{ number_format($analysis->rejected_quantity, 1) }}</span></td>
                                <td>
                                    <span class="badge badge-{{ $analysis->acceptance_rate >= 90 ? 'success' : ($analysis->acceptance_rate >= 75 ? 'warning' : 'danger') }}">
                                        {{ $analysis->acceptance_rate }}%
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Weekly Trend Chart -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-chart-area"></i>
                    Haftalık Üretim Trendi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="chart-container">
                    <canvas id="weeklyTrendChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Monthly Comparison -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-balance-scale"></i>
                    Aylık Karşılaştırma
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Bu Ay</h5>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-value">{{ number_format($monthlyComparison['current_month']['total_barcodes']) }}</div>
                                <div class="stat-label">Barkod</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">{{ number_format($monthlyComparison['current_month']['total_quantity']) }}</div>
                                <div class="stat-label">Miktar</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5>Geçen Ay</h5>
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-value">{{ number_format($monthlyComparison['previous_month']['total_barcodes']) }}</div>
                                <div class="stat-label">Barkod</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-value">{{ number_format($monthlyComparison['previous_month']['total_quantity']) }}</div>
                                <div class="stat-label">Miktar</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4">
                    <h6>Değişim Oranları:</h6>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-value {{ $monthlyComparison['change_percentage']['total_barcodes'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $monthlyComparison['change_percentage']['total_barcodes'] >= 0 ? '+' : '' }}{{ $monthlyComparison['change_percentage']['total_barcodes'] }}%
                                </div>
                                <div class="stat-label">Barkod Değişimi</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-value {{ $monthlyComparison['change_percentage']['total_quantity'] >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $monthlyComparison['change_percentage']['total_quantity'] >= 0 ? '+' : '' }}{{ $monthlyComparison['change_percentage']['total_quantity'] }}%
                                </div>
                                <div class="stat-label">Miktar Değişimi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI/ML Insights Section -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-brain"></i>
                    AI/ML İçgörüler & Tahmin Analizi
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    <!-- Production Predictions -->
                    <div class="col-md-6 mb-4">
                        <div class="insight-card">
                            <div class="insight-header">
                                <i class="fas fa-chart-line text-primary"></i>
                                <h5>Üretim Tahmini (Gelecek 7 Gün)</h5>
                            </div>
                            <div class="insight-content">
                                <div class="prediction-item">
                                    <span class="prediction-label">Beklenen Üretim:</span>
                                    <span class="prediction-value">{{ number_format($aiInsights['production_forecast'] ?? 0, 1) }} ton</span>
                                </div>
                                <div class="prediction-item">
                                    <span class="prediction-label">Güven Seviyesi:</span>
                                    <span class="prediction-value {{ ($aiInsights['confidence_level'] ?? 0) >= 80 ? 'text-success' : (($aiInsights['confidence_level'] ?? 0) >= 60 ? 'text-warning' : 'text-danger') }}">
                                        {{ $aiInsights['confidence_level'] ?? 0 }}%
                                    </span>
                                </div>
                                <div class="trend-indicator">
                                    <i class="fas fa-arrow-{{ ($aiInsights['trend_direction'] ?? 'up') === 'up' ? 'up text-success' : 'down text-danger' }}"></i>
                                    <span>Geçen haftaya göre %{{ $aiInsights['trend_percentage'] ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quality Predictions -->
                    <div class="col-md-6 mb-4">
                        <div class="insight-card">
                            <div class="insight-header">
                                <i class="fas fa-shield-alt text-success"></i>
                                <h5>Kalite Risk Değerlendirmesi</h5>
                            </div>
                            <div class="insight-content">
                                <div class="risk-indicator">
                                    <span class="risk-label">Mevcut Risk Seviyesi:</span>
                                    <span class="risk-badge risk-{{ $aiInsights['quality_risk_level'] ?? 'low' }}">
                                        @if(($aiInsights['quality_risk_level'] ?? 'low') === 'low')
                                            Düşük
                                        @elseif(($aiInsights['quality_risk_level'] ?? 'low') === 'medium')
                                            Orta
                                        @else
                                            Yüksek
                                        @endif
                                    </span>
                                </div>
                                <div class="prediction-item">
                                    <span class="prediction-label">Beklenen Red Oranı:</span>
                                    <span class="prediction-value">{{ $aiInsights['expected_rejection_rate'] ?? 0 }}%</span>
                                </div>
                                <div class="recommendation">
                                    <strong>Öneri:</strong> {{ $aiInsights['quality_recommendation'] ?? 'Mevcut kalite kontrol prosedürlerine devam edin.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Anomaly Detection -->
                    <div class="col-md-6 mb-4">
                        <div class="insight-card">
                            <div class="insight-header">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                <h5>Anomali Tespiti</h5>
                            </div>
                            <div class="insight-content">
                                @if(isset($aiInsights['anomalies']) && count($aiInsights['anomalies']) > 0)
                                    @foreach($aiInsights['anomalies'] as $anomaly)
                                    <div class="anomaly-item">
                                        <div class="anomaly-type">{{ $anomaly['type'] }}</div>
                                        <div class="anomaly-description">{{ $anomaly['description'] }}</div>
                                        <div class="anomaly-severity severity-{{ $anomaly['severity'] }}">
                                            @if($anomaly['severity'] === 'low')
                                                Düşük
                                            @elseif($anomaly['severity'] === 'medium')
                                                Orta
                                            @else
                                                Yüksek
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="no-anomalies">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <span>Mevcut verilerde anomali tespit edilmedi</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Optimization Recommendations -->
                    <div class="col-md-6 mb-4">
                        <div class="insight-card">
                            <div class="insight-header">
                                <i class="fas fa-lightbulb text-info"></i>
                                <h5>Optimizasyon Önerileri</h5>
                            </div>
                            <div class="insight-content">
                                @if(isset($aiInsights['recommendations']) && count($aiInsights['recommendations']) > 0)
                                    @foreach($aiInsights['recommendations'] as $recommendation)
                                    <div class="recommendation-item">
                                        <div class="recommendation-category">{{ $recommendation['category'] }}</div>
                                        <div class="recommendation-text">{{ $recommendation['text'] }}</div>
                                        <div class="recommendation-impact">
                                            <span class="impact-label">Beklenen Etki:</span>
                                            <span class="impact-value impact-{{ $recommendation['impact'] }}">
                                                @if($recommendation['impact'] === 'low')
                                                    Düşük
                                                @elseif($recommendation['impact'] === 'medium')
                                                    Orta
                                                @else
                                                    Yüksek
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="no-recommendations">
                                        <i class="fas fa-info-circle text-info"></i>
                                        <span>Şu anda optimizasyon önerisi bulunmuyor</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Machine Learning Model Status -->
                <div class="ml-status-section mt-4">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="model-status-card">
                                <div class="status-header">
                                    <i class="fas fa-cog"></i>
                                    <span>Üretim Modeli</span>
                                </div>
                                <div class="status-indicator status-{{ $aiInsights['model_status']['production'] ?? 'active' }}">
                                    @if(($aiInsights['model_status']['production'] ?? 'active') === 'active')
                                        Aktif
                                    @else
                                        Pasif
                                    @endif
                                </div>
                                <div class="model-accuracy">
                                    Doğruluk: %{{ $aiInsights['model_accuracy']['production'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="model-status-card">
                                <div class="status-header">
                                    <i class="fas fa-chart-bar"></i>
                                    <span>Kalite Modeli</span>
                                </div>
                                <div class="status-indicator status-{{ $aiInsights['model_status']['quality'] ?? 'active' }}">
                                    @if(($aiInsights['model_status']['quality'] ?? 'active') === 'active')
                                        Aktif
                                    @else
                                        Pasif
                                    @endif
                                </div>
                                <div class="model-accuracy">
                                    Doğruluk: %{{ $aiInsights['model_status']['quality'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="model-status-card">
                                <div class="status-header">
                                    <i class="fas fa-robot"></i>
                                    <span>Anomali Modeli</span>
                                </div>
                                <div class="status-indicator status-{{ $aiInsights['model_status']['anomaly'] ?? 'active' }}">
                                    @if(($aiInsights['model_status']['anomaly'] ?? 'active') === 'active')
                                        Aktif
                                    @else
                                        Pasif
                                    @endif
                                </div>
                                <div class="model-accuracy">
                                    Doğruluk: %{{ $aiInsights['model_status']['anomaly'] ?? 0 }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Weekly Trend Chart
    const weeklyTrendCtx = document.getElementById('weeklyTrendChart').getContext('2d');
    const weeklyTrendData = @json($weeklyTrend);
    
    const labels = weeklyTrendData.map(item => item.date);
    const barcodeData = weeklyTrendData.map(item => item.barcode_count);
    const quantityData = weeklyTrendData.map(item => item.total_quantity);
    
    new Chart(weeklyTrendCtx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Barkod Sayısı',
                data: barcodeData,
                borderColor: '#667eea',
                backgroundColor: 'rgba(102, 126, 234, 0.1)',
                tension: 0.4,
                yAxisID: 'y'
            }, {
                label: 'Toplam Miktar',
                data: quantityData,
                borderColor: '#764ba2',
                backgroundColor: 'rgba(118, 75, 162, 0.1)',
                tension: 0.4,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Tarih'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Barkod Sayısı'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Miktar'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Haftalık Üretim Trendi'
                }
            }
        }
    });
});

// Refresh shift report function
function refreshShiftReport() {
    const refreshBtn = event.target;
    const originalHTML = refreshBtn.innerHTML;
    
    // Show loading state
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    refreshBtn.disabled = true;
    
    // Reload the page to refresh data
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

// Excel export function for kiln performance
function exportKilnPerformance() {
    const selectedDate = document.getElementById('date').value;
    
    // Loading göster
    const exportBtn = event.target;
    const originalText = exportBtn.innerHTML;
    exportBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> İndiriliyor...';
    exportBtn.disabled = true;
    
    // CSRF token ve credentials ekle
    fetch(`{{ route('dashboard.export-kiln-performance') }}`, {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({
            date: selectedDate
        })
    })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            
            if (!data.success) {
                throw new Error(data.error || 'Unknown error occurred');
            }
            
            if (!data.data || !Array.isArray(data.data)) {
                throw new Error('Invalid data format received');
            }
            
            // Create CSV content with proper encoding
            let csvContent = "data:text/csv;charset=utf-8,\uFEFF";
            
            // Add headers
            csvContent += "Fırın Adı,Barkod Sayısı,Toplam Miktar (ton),Ortalama Miktar (ton),Kabul Edilen (ton),Test Sürecinde (ton),Teslimat Sürecinde (ton),Reddedilen (ton)\n";
            
            // Add data rows with proper escaping
            data.data.forEach(kiln => {
                const row = [
                    `"${kiln.kiln_name || ''}"`,
                    kiln.barcode_count || 0,
                    parseFloat(kiln.total_quantity || 0).toFixed(1),
                    parseFloat(kiln.avg_quantity || 0).toFixed(1),
                    parseFloat(kiln.accepted_quantity || 0).toFixed(1),
                    parseFloat(kiln.testing_quantity || 0).toFixed(1),
                    parseFloat(kiln.delivery_quantity || 0).toFixed(1),
                    parseFloat(kiln.rejected_quantity || 0).toFixed(1)
                ].join(',');
                csvContent += row + '\n';
            });
            
            // Create download link
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", data.filename);
            link.style.display = 'none';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Success message
            alert('Fırın performans raporu başarıyla indirildi!');
        })
        .catch(error => {
            console.error('Export error:', error);
            alert('Export sırasında bir hata oluştu: ' + error.message);
        })
        .finally(() => {
            // Button'u eski haline getir
            exportBtn.innerHTML = originalText;
            exportBtn.disabled = false;
        });
}
</script>
@endsection
