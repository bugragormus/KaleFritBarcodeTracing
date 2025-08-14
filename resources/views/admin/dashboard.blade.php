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
        border-radius: 15px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.25);
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

    .date-selector {
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        padding: 1rem;
        margin-top: 0.8rem;
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
        
        .date-selector {
            padding: 0.8rem;
        }
        
        .date-selector input {
            min-width: 140px;
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
                <div class="d-flex align-items-center gap-3">
                    <div class="date-selector">
                        <form method="GET" action="{{ route('dashboard') }}" class="d-flex align-items-center">
                            <label for="date">📅 Rapor Tarihi (Bugün):</label>
                            <input type="date" id="date" name="date" value="{{ $selectedDate }}" 
                                   class="form-control" onchange="this.form.submit()">
                        </form>
                        <small class="text-white-50 mt-1 d-block">
                            <i class="fas fa-info-circle"></i> 
                            OEE ve AI/ML içgörüler her zaman güncel tarihe göre çalışır
                        </small>
                    </div>
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

        <!-- Correction Summary -->
        <div class="card-modern">
            <div class="card-header-modern">
                <h3 class="card-title-modern">
                    <i class="fas fa-recycle"></i>
                    Düzeltme Faaliyeti Özeti
                </h3>
            </div>
            <div class="card-body-modern">
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['with_correction_output'] ?? 0, 1) }}</div>
                        <div class="stat-label">Düzeltmeli Üretim (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['without_correction_output'] ?? 0, 1) }}</div>
                        <div class="stat-label">Düzeltmesiz Üretim (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['correction_input_used'] ?? 0, 1) }}</div>
                        <div class="stat-label">Düzeltmede Kullanılan Red (ton)</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($dailyProduction['raw_material_used'] ?? 0, 1) }}</div>
                        <div class="stat-label">Toplam Hammadde Kullanımı (ton)</div>
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
                            <i class="fas fa-sync-alt"></i> Yenile
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
                                    <i class="fas fa-sync-alt"></i> Yeniles
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
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-chart-line"></i>
                        Aylık Karşılaştırma Analizi
                    </h3>
                    <div class="d-flex align-items-center gap-5">
                        <span class="monthly-comparison-badge">
                            <i class="fas fa-calendar-alt"></i> 
                            {{ \Carbon\Carbon::now()->format('F Y') }} vs {{ \Carbon\Carbon::now()->subMonth()->format('F Y') }}
                        </span>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#monthlyComparisonInfoModal" title="Bilgi">
                            <i class="fas fa-info-circle"></i> Bilgi
                        </button>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-info-circle"></i> 
                    Bu ay ile geçen ay arasındaki performans karşılaştırması ve trend analizi
                </small>
            </div>
            <div class="card-body-modern">
                <!-- Main Comparison Cards -->
                <div class="monthly-comparison-overview">
                    <div class="row">
                        <!-- Current Month -->
                        <div class="col-lg-6">
                            <div class="month-card current-month">
                                <div class="month-header">
                                    <div class="month-icon">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="month-info">
                                        <h4 class="month-title">Bu Ay</h4>
                                        <p class="month-period">{{ \Carbon\Carbon::now()->format('F Y') }}</p>
                                    </div>
                                    <div class="month-badge current">
                                        <span>Güncel</span>
                                    </div>
                                </div>
                                <div class="month-stats">
                                    <div class="month-stat-item">
                                        <div class="stat-icon">
                                            <i class="fas fa-barcode"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ number_format($monthlyComparison['current_month']['total_barcodes']) }}</div>
                                            <div class="stat-label">Toplam Barkod</div>
                                        </div>
                                        <div class="stat-trend up">
                                            <i class="fas fa-arrow-up"></i>
                                        </div>
                                    </div>
                                    <div class="month-stat-item">
                                        <div class="stat-icon">
                                            <i class="fas fa-weight-hanging"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ number_format($monthlyComparison['current_month']['total_quantity'], 1) }}</div>
                                            <div class="stat-label">Toplam Miktar (ton)</div>
                                        </div>
                                        <div class="stat-trend up">
                                            <i class="fas fa-arrow-up"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Previous Month -->
                        <div class="col-lg-6">
                            <div class="month-card previous-month">
                                <div class="month-header">
                                    <div class="month-icon">
                                        <i class="fas fa-calendar-minus"></i>
                                    </div>
                                    <div class="month-info">
                                        <h4 class="month-title">Geçen Ay</h4>
                                        <p class="month-period">{{ \Carbon\Carbon::now()->subMonth()->format('F Y') }}</p>
                                    </div>
                                    <div class="month-badge previous">
                                        <span>Önceki</span>
                                    </div>
                                </div>
                                <div class="month-stats">
                                    <div class="month-stat-item">
                                        <div class="stat-icon">
                                            <i class="fas fa-barcode"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ number_format($monthlyComparison['previous_month']['total_barcodes']) }}</div>
                                            <div class="stat-label">Toplam Barkod</div>
                                        </div>
                                        <div class="stat-trend down">
                                            <i class="fas fa-arrow-down"></i>
                                        </div>
                                    </div>
                                    <div class="month-stat-item">
                                        <div class="stat-icon">
                                            <i class="fas fa-weight-hanging"></i>
                                        </div>
                                        <div class="stat-content">
                                            <div class="stat-value">{{ number_format($monthlyComparison['previous_month']['total_quantity'], 1) }}</div>
                                            <div class="stat-label">Toplam Miktar (ton)</div>
                                        </div>
                                        <div class="stat-trend down">
                                            <i class="fas fa-arrow-down"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Change Analysis -->
                <div class="change-analysis-section">
                    <div class="section-header">
                        <h5 class="section-title">
                            <i class="fas fa-chart-bar"></i>
                            Değişim Analizi
                        </h5>
                        <p class="section-subtitle">Bu ay ile geçen ay arasındaki performans değişimi</p>
                    </div>
                    
                    <div class="change-metrics">
                        <div class="row">
                            <!-- Barcode Change -->
                            <div class="col-lg-6">
                                <div class="change-metric-card">
                                    <div class="metric-header">
                                        <div class="metric-icon">
                                            <i class="fas fa-barcode"></i>
                                        </div>
                                        <div class="metric-info">
                                            <h6 class="metric-title">Barkod Değişimi</h6>
                                            <p class="metric-subtitle">Üretim hacmi karşılaştırması</p>
                                        </div>
                                    </div>
                                    <div class="metric-content">
                                        <div class="change-value {{ $monthlyComparison['change_percentage']['total_barcodes'] >= 0 ? 'positive' : 'negative' }}">
                                            <span class="change-sign">{{ $monthlyComparison['change_percentage']['total_barcodes'] >= 0 ? '+' : '' }}</span>
                                            <span class="change-number">{{ $monthlyComparison['change_percentage']['total_barcodes'] }}</span>
                                            <span class="change-unit">%</span>
                                        </div>
                                        <div class="change-description">
                                            @if($monthlyComparison['change_percentage']['total_barcodes'] == 100)
                                                <span class="trend-indicator positive">
                                                    <i class="fas fa-plus-circle"></i>
                                                    Yeni üretim başladı
                                                </span>
                                            @elseif($monthlyComparison['change_percentage']['total_barcodes'] == -100)
                                                <span class="trend-indicator negative">
                                                    <i class="fas fa-minus-circle"></i>
                                                    Üretim durdu
                                                </span>
                                            @elseif($monthlyComparison['change_percentage']['total_barcodes'] >= 0)
                                                <span class="trend-indicator positive">
                                                    <i class="fas fa-arrow-up"></i>
                                                    {{ $monthlyComparison['change_percentage']['total_barcodes'] >= 20 ? 'Güçlü artış' : ($monthlyComparison['change_percentage']['total_barcodes'] >= 10 ? 'Orta artış' : 'Hafif artış') }}
                                                </span>
                                            @else
                                                <span class="trend-indicator negative">
                                                    <i class="fas fa-arrow-down"></i>
                                                    {{ abs($monthlyComparison['change_percentage']['total_barcodes']) >= 20 ? 'Önemli düşüş' : (abs($monthlyComparison['change_percentage']['total_barcodes']) >= 10 ? 'Orta düşüş' : 'Hafif düşüş') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="metric-footer">
                                        <div class="progress-container">
                                            <div class="progress-label">Değişim Oranı</div>
                                            <div class="progress-bar">
                                                <div class="progress-fill {{ $monthlyComparison['change_percentage']['total_barcodes'] >= 0 ? 'positive' : 'negative' }}" 
                                                     style="width: {{ min(abs($monthlyComparison['change_percentage']['total_barcodes']), 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quantity Change -->
                            <div class="col-lg-6">
                                <div class="change-metric-card">
                                    <div class="metric-header">
                                        <div class="metric-icon">
                                            <i class="fas fa-weight-hanging"></i>
                                        </div>
                                        <div class="metric-info">
                                            <h6 class="metric-title">Miktar Değişimi</h6>
                                            <p class="metric-subtitle">Üretim kapasitesi karşılaştırması</p>
                                        </div>
                                    </div>
                                    <div class="metric-content">
                                        <div class="change-value {{ $monthlyComparison['change_percentage']['total_quantity'] >= 0 ? 'positive' : 'negative' }}">
                                            <span class="change-sign">{{ $monthlyComparison['change_percentage']['total_quantity'] >= 0 ? '+' : '' }}</span>
                                            <span class="change-number">{{ $monthlyComparison['change_percentage']['total_quantity'] }}</span>
                                            <span class="change-unit">%</span>
                                        </div>
                                        <div class="change-description">
                                            @if($monthlyComparison['change_percentage']['total_quantity'] == 100)
                                                <span class="trend-indicator positive">
                                                    <i class="fas fa-plus-circle"></i>
                                                    Yeni üretim başladı
                                                </span>
                                            @elseif($monthlyComparison['change_percentage']['total_quantity'] == -100)
                                                <span class="trend-indicator negative">
                                                    <i class="fas fa-minus-circle"></i>
                                                    Üretim durdu
                                                </span>
                                            @elseif($monthlyComparison['change_percentage']['total_quantity'] >= 0)
                                                <span class="trend-indicator positive">
                                                    <i class="fas fa-arrow-up"></i>
                                                    {{ $monthlyComparison['change_percentage']['total_quantity'] >= 20 ? 'Güçlü artış' : ($monthlyComparison['change_percentage']['total_quantity'] >= 10 ? 'Orta artış' : 'Hafif artış') }}
                                                </span>
                                            @else
                                                <span class="trend-indicator negative">
                                                    <i class="fas fa-arrow-down"></i>
                                                    {{ abs($monthlyComparison['change_percentage']['total_quantity']) >= 20 ? 'Önemli düşüş' : (abs($monthlyComparison['change_percentage']['total_quantity']) >= 10 ? 'Orta düşüş' : 'Hafif düşüş') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="metric-footer">
                                        <div class="progress-container">
                                            <div class="progress-label">Değişim Oranı</div>
                                            <div class="progress-bar">
                                                <div class="progress-fill {{ $monthlyComparison['change_percentage']['total_quantity'] >= 0 ? 'positive' : 'negative' }}" 
                                                     style="width: {{ min(abs($monthlyComparison['change_percentage']['total_quantity']), 100) }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <!-- Stock Age Analysis Dashboard -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-clock"></i>
                        Stok Yaşı Analizi Dashboard'u
                    </h3>
                    <div class="d-flex align-items-center gap-2">
                        <span class="stock-age-info-badge">
                            <i class="fas fa-sync-alt"></i> Son Güncelleme: {{ $stockAgeAnalysis['current_date'] }}
                        </span>
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#stockAgeInfoModal">
                            <i class="fas fa-info-circle"></i> Bilgi
                        </button>
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Bu dashboard gözden kaçan stokları yakalamanıza yardımcı olur. Her zaman güncel veriler gösterir.
                </small>
            </div>
            <div class="card-body-modern">
                <!-- Summary Cards -->
                <div class="stock-age-summary">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stock-age-card critical">
                                <div class="stock-age-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="stock-age-value critical">{{ number_format($stockAgeAnalysis['summary']['critical_count']) }}</div>
                                <div class="stock-age-label">Kritik Stok</div>
                                <div class="stock-age-subtitle">30+ gün</div>
                                <div class="stock-age-quantity">{{ number_format($stockAgeAnalysis['summary']['critical_quantity'], 1) }} ton</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stock-age-card warning">
                                <div class="stock-age-icon">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="stock-age-value warning">{{ number_format($stockAgeAnalysis['summary']['warning_count']) }}</div>
                                <div class="stock-age-label">Uyarı Stok</div>
                                <div class="stock-age-subtitle">15-29 gün</div>
                                <div class="stock-age-quantity">{{ number_format($stockAgeAnalysis['summary']['warning_quantity'], 1) }} ton</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stock-age-card attention">
                                <div class="stock-age-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="stock-age-value attention">{{ number_format($stockAgeAnalysis['summary']['attention_count']) }}</div>
                                <div class="stock-age-label">Dikkat Stok</div>
                                <div class="stock-age-subtitle">7-14 gün</div>
                                <div class="stock-age-quantity">{{ number_format($stockAgeAnalysis['summary']['attention_quantity'], 1) }} ton</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stock-age-card normal">
                                <div class="stock-age-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stock-age-value normal">{{ number_format($stockAgeAnalysis['summary']['normal_count']) }}</div>
                                <div class="stock-age-label">Normal Stok</div>
                                <div class="stock-age-subtitle">0-6 gün</div>
                                <div class="stock-age-quantity">{{ number_format($stockAgeAnalysis['summary']['normal_quantity'], 1) }} ton</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Critical Stock Details -->
                @if(count($stockAgeAnalysis['categorized_stock']['critical'] ?? []) > 0)
                <div class="stock-age-detail-section critical-section">
                    <div class="section-header critical-header">
                        <div class="section-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="section-content">
                            <h4 class="section-title">Kritik Stok Uyarısı</h4>
                            <p class="section-subtitle">{{ count($stockAgeAnalysis['categorized_stock']['critical']) }} barkod acil müdahale bekliyor</p>
                        </div>
                        <div class="section-badge critical-badge">
                            <span class="badge-number">{{ count($stockAgeAnalysis['categorized_stock']['critical']) }}</span>
                            <span class="badge-text">Kritik</span>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="table-responsive">
                            <table class="table table-modern stock-age-table critical-stock-table">
                                <thead>
                                    <tr>
                                        <th>Barkod</th>
                                        <th>Ürün</th>
                                        <th>Miktar (KG)</th>
                                        <th>Durum</th>
                                        <th>Şirket</th>
                                        <th>Depo</th>
                                        <th>Fırın</th>
                                        <th>Yaş (Gün)</th>
                                        <th>Son İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(array_slice($stockAgeAnalysis['categorized_stock']['critical'], 0, 10) as $stock)
                                    <tr class="table-danger">
                                        <td><strong class="barcode-id">#{{ $stock->barcode }}</strong></td>
                                        <td>
                                            <div class="product-info">
                                                <strong class="product-name">{{ $stock->stock_name }}</strong>
                                                <small class="product-code">{{ $stock->stock_code }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="quantity-badge">{{ number_format($stock->quantity, 1) }}</span>
                                        </td>
                                        <td>
                                            @switch($stock->status)
                                                @case('waiting')
                                                    <span class="badge stock-age-status-badge waiting">Beklemede</span>
                                                    @break
                                                @case('control_repeat')
                                                    <span class="badge stock-age-status-badge control_repeat">Kontrol Tekrarı</span>
                                                    @break
                                                @case('pre_approved')
                                                    <span class="badge stock-age-status-badge pre_approved">Ön Onaylı</span>
                                                    @break
                                                @case('shipment_approved')
                                                    <span class="badge stock-age-status-badge shipment_approved">Sevk Onaylı</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge stock-age-status-badge rejected">Reddedildi</span>
                                                    @break
                                                @case('customer_transfer')
                                                    <span class="badge stock-age-status-badge customer_transfer">Müşteri Transfer</span>
                                                    @break
                                                @case('delivered')
                                                    <span class="badge stock-age-status-badge delivered">Teslim Edildi</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">{{ $stock->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $stock->company_name ?? '-' }}</td>
                                        <td>{{ $stock->warehouse_name ?? '-' }}</td>
                                        <td>{{ $stock->kiln_name ?? '-' }}</td>
                                        <td>
                                            <span class="badge stock-age-badge critical">{{ $stock->days_old }} gün</span>
                                        </td>
                                        <td>
                                            @if($stock->lab_at)
                                                <small class="process-info lab">Lab: {{ \Carbon\Carbon::parse($stock->lab_at)->format('d.m.Y') }}</small>
                                            @elseif($stock->shipment_at)
                                                <small class="process-info transfer">Depo Transfer: {{ \Carbon\Carbon::parse($stock->shipment_at)->format('d.m.Y') }}</small>
                                            @else
                                                <small class="process-info created">Oluşturulma: {{ \Carbon\Carbon::parse($stock->created_at)->format('d.m.Y') }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(count($stockAgeAnalysis['categorized_stock']['critical']) > 10)
                            <div class="section-footer">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Toplam {{ count($stockAgeAnalysis['categorized_stock']['critical']) }} kritik barkod bulundu. Sadece ilk 10 tanesi gösteriliyor.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Warning Stock Details -->
                @if(count($stockAgeAnalysis['categorized_stock']['warning'] ?? []) > 0)
                <div class="stock-age-detail-section warning-section">
                    <div class="section-header warning-header">
                        <div class="section-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="section-content">
                            <h4 class="section-title">Uyarı Stok</h4>
                            <p class="section-subtitle">{{ count($stockAgeAnalysis['categorized_stock']['warning']) }} barkod dikkat gerektiriyor</p>
                        </div>
                        <div class="section-badge warning-badge">
                            <span class="badge-number">{{ count($stockAgeAnalysis['categorized_stock']['warning']) }}</span>
                            <span class="badge-text">Uyarı</span>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="table-responsive">
                            <table class="table table-modern stock-age-table warning-stock-table">
                                <thead>
                                    <tr>
                                        <th>Barkod</th>
                                        <th>Ürün</th>
                                        <th>Miktar (KG)</th>
                                        <th>Durum</th>
                                        <th>Yaş (Gün)</th>
                                        <th>Son İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(array_slice($stockAgeAnalysis['categorized_stock']['warning'], 0, 5) as $stock)
                                    <tr class="table-warning">
                                        <td><strong class="barcode-id">#{{ $stock->barcode }}</strong></td>
                                        <td>
                                            <div class="product-info">
                                                <strong class="product-name">{{ $stock->stock_name }}</strong>
                                                <small class="product-code">{{ $stock->stock_code }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="quantity-badge">{{ number_format($stock->quantity, 1) }}</span>
                                        </td>
                                        <td>
                                            @switch($stock->status)
                                                @case('waiting')
                                                    <span class="badge stock-age-status-badge waiting">Beklemede</span>
                                                    @break
                                                @case('control_repeat')
                                                    <span class="badge stock-age-status-badge control_repeat">Kontrol Tekrarı</span>
                                                    @break
                                                @case('pre_approved')
                                                    <span class="badge stock-age-status-badge pre_approved">Ön Onaylı</span>
                                                    @break
                                                @case('shipment_approved')
                                                    <span class="badge stock-age-status-badge shipment_approved">Sevk Onaylı</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge stock-age-status-badge rejected">Reddedildi</span>
                                                    @break
                                                @case('customer_transfer')
                                                    <span class="badge stock-age-status-badge customer_transfer">Müşteri Transfer</span>
                                                    @break
                                                @case('delivered')
                                                    <span class="badge stock-age-status-badge delivered">Teslim Edildi</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-secondary">{{ $stock->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <span class="badge stock-age-badge warning">{{ $stock->days_old }} gün</span>
                                        </td>
                                        <td>
                                            @if($stock->lab_at)
                                                <small class="process-info lab">Lab: {{ \Carbon\Carbon::parse($stock->lab_at)->format('d.m.Y') }}</small>
                                            @elseif($stock->shipment_at)
                                                <small class="process-info transfer">Depo Transfer: {{ \Carbon\Carbon::parse($stock->shipment_at)->format('d.m.Y') }}</small>
                                            @else
                                                <small class="process-info created">Oluşturulma: {{ \Carbon\Carbon::parse($stock->created_at)->format('d.m.Y') }}</small>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if(count($stockAgeAnalysis['categorized_stock']['warning']) > 5)
                            <div class="section-footer">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Toplam {{ count($stockAgeAnalysis['categorized_stock']['warning']) }} uyarı barkod bulundu. Sadece ilk 5 tanesi gösteriliyor.
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Product Analysis -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="stock-age-product-analysis">
                            <div class="card-header-modern">
                                <h5 class="card-title-modern">
                                    <i class="fas fa-chart-pie"></i>
                                    Durum Bazında Stok Yaşı Analizi
                                </h5>
                            </div>
                            <div class="card-body-modern">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Durum</th>
                                                <th>Barkod Sayısı</th>
                                                <th>Toplam Miktar</th>
                                                <th>Ortalama Yaş</th>
                                                <th>En Eski</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($stockAgeAnalysis['status_analysis'] as $status => $data)
                                            <tr>
                                                <td>
                                                    @switch($status)
                                                        @case('waiting')
                                                            <span class="badge stock-age-status-badge waiting">Beklemede</span>
                                                            @break
                                                        @case('control_repeat')
                                                            <span class="badge stock-age-status-badge control_repeat">Kontrol Tekrarı</span>
                                                            @break
                                                        @case('pre_approved')
                                                            <span class="badge stock-age-status-badge pre_approved">Ön Onaylı</span>
                                                            @break
                                                        @case('shipment_approved')
                                                            <span class="badge stock-age-status-badge shipment_approved">Sevk Onaylı</span>
                                                            @break
                                                        @case('rejected')
                                                            <span class="badge stock-age-status-badge rejected">Reddedildi</span>
                                                            @break
                                                        @case('customer_transfer')
                                                            <span class="badge stock-age-status-badge customer_transfer">Müşteri Transfer</span>
                                                            @break
                                                        @case('delivered')
                                                            <span class="badge stock-age-status-badge delivered">Teslim Edildi</span>
                                                            @break
                                                        @default
                                                            <span class="badge badge-secondary">{{ $status }}</span>
                                                    @endswitch
                                                </td>
                                                <td>{{ number_format($data['count']) }}</td>
                                                <td>{{ number_format($data['quantity'], 1) }} KG</td>
                                                <td>{{ $data['avg_age'] }} gün</td>
                                                <td>{{ $data['oldest_age'] }} gün</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stock-age-product-analysis">
                            <div class="card-header-modern">
                                <h5 class="card-title-modern">
                                    <i class="fas fa-boxes"></i>
                                    En Kritik Ürünler (Top 10)
                                </h5>
                            </div>
                            <div class="card-body-modern">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Ürün</th>
                                                <th>Barkod</th>
                                                <th>Miktar</th>
                                                <th>Ortalama Yaş</th>
                                                <th>Kritik</th>
                                                <th>Uyarı</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(array_slice($stockAgeAnalysis['product_analysis'], 0, 10) as $product)
                                            <tr>
                                                <td>
                                                    <div class="product-info">
                                                        <strong class="product-name">{{ $product['stock_name'] }}</strong>
                                                        <small class="product-code">{{ $product['stock_code'] }}</small>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($product['count']) }}</td>
                                                <td>{{ number_format($product['quantity'], 1) }} KG</td>
                                                <td>{{ $product['avg_age'] }} gün</td>
                                                <td>
                                                    @if($product['critical_count'] > 0)
                                                        <span class="badge badge-danger">{{ $product['critical_count'] }}</span>
                                                    @else
                                                        <span class="badge badge-light">0</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($product['warning_count'] > 0)
                                                        <span class="badge badge-warning">{{ $product['warning_count'] }}</span>
                                                    @else
                                                        <span class="badge badge-light">0</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Recommendations -->
                <div class="action-recommendations">
                    <div class="recommendations-header">
                        <div class="header-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div class="header-content">
                            <h4 class="header-title">Önerilen Aksiyonlar</h4>
                            <p class="header-subtitle">Stok yaşı analizine göre öncelikli eylem planları</p>
                        </div>
                        <div class="header-stats">
                            <div class="stat-item">
                                <span class="stat-number">{{ $stockAgeAnalysis['summary']['critical_count'] + $stockAgeAnalysis['summary']['warning_count'] }}</span>
                                <span class="stat-label">Aksiyon Gerekli</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="recommendations-content">
                        @if($stockAgeAnalysis['summary']['critical_count'] > 0 || $stockAgeAnalysis['summary']['warning_count'] > 0)
                        <div class="row">
                            @if($stockAgeAnalysis['summary']['critical_count'] > 0)
                            <div class="col-lg-6 col-md-12">
                                <div class="recommendation-card critical-card">
                                    <div class="card-header-section">
                                        <div class="priority-badge critical-priority">
                                            <i class="fas fa-exclamation-triangle"></i>
                                            <span>Yüksek Öncelik</span>
                                        </div>
                                        <div class="urgency-indicator">
                                            <div class="pulse-dot"></div>
                                            <span>Acil</span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body-section">
                                        <h5 class="card-title">
                                            <i class="fas fa-fire"></i>
                                            Kritik Stok İçin Acil Aksiyon
                                        </h5>
                                        <div class="stats-summary">
                                            <div class="stat-box">
                                                <span class="stat-icon">📊</span>
                                                <span class="stat-value">{{ $stockAgeAnalysis['summary']['critical_count'] }}</span>
                                                <span class="stat-label">Kritik Barkod</span>
                                            </div>
                                            <div class="stat-box">
                                                <span class="stat-icon">⚖️</span>
                                                <span class="stat-value">{{ number_format($stockAgeAnalysis['summary']['critical_quantity'], 1) }}</span>
                                                <span class="stat-label">Ton</span>
                                            </div>
                                            <div class="stat-box">
                                                <span class="stat-icon">⏰</span>
                                                <span class="stat-value">30+</span>
                                                <span class="stat-label">Gün</span>
                                            </div>
                                        </div>
                                        
                                        <div class="action-list">
                                            <h6 class="action-title">Önerilen Eylemler:</h6>
                                            <ul class="action-items">
                                                <li class="action-item">
                                                    <div class="action-icon">
                                                        <i class="fas fa-flask"></i>
                                                    </div>
                                                    <div class="action-content">
                                                        <strong>Laboratuvar Süreçlerini Hızlandırın</strong>
                                                        <p>Kritik stoklar için öncelikli test sırası oluşturun</p>
                                                    </div>
                                                </li>
                                                <li class="action-item">
                                                    <div class="action-icon">
                                                        <i class="fas fa-phone"></i>
                                                    </div>
                                                    <div class="action-content">
                                                        <strong>Müşteri İletişimi</strong>
                                                        <p>Aciliyet durumunu belirterek teslimat planını netleştirin</p>
                                                    </div>
                                                </li>
                                                <li class="action-item">
                                                    <div class="action-icon">
                                                        <i class="fas fa-sync-alt"></i>
                                                    </div>
                                                    <div class="action-content">
                                                        <strong>Stok Rotasyonu</strong>
                                                        <p>Eski stokları öncelikli olarak işleme alın</p>
                                                    </div>
                                                </li>
                                                <li class="action-item">
                                                    <div class="action-icon">
                                                        <i class="fas fa-chart-line"></i>
                                                    </div>
                                                    <div class="action-content">
                                                        <strong>Performans Analizi</strong>
                                                        <p>Kritik stok oluşum nedenlerini analiz edin</p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer-section">
                                        <div class="progress-indicator">
                                            <div class="progress-bar">
                                                <div class="progress-fill critical-fill" style="width: 100%"></div>
                                            </div>
                                            <span class="progress-text">Acil Aksiyon Gerekli</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            @if($stockAgeAnalysis['summary']['warning_count'] > 0)
                            <div class="col-lg-6 col-md-12">
                                <div class="recommendation-card warning-card">
                                    <div class="card-header-section">
                                        <div class="priority-badge warning-priority">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span>Orta Öncelik</span>
                                        </div>
                                        <div class="urgency-indicator">
                                            <div class="pulse-dot warning-pulse"></div>
                                            <span>Dikkat</span>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body-section">
                                        <h5 class="card-title">
                                            <i class="fas fa-eye"></i>
                                            Uyarı Stok İçin Önleyici Aksiyon
                                        </h5>
                                        <div class="stats-summary">
                                            <div class="stat-box">
                                                <span class="stat-icon">📊</span>
                                                <span class="stat-value">{{ $stockAgeAnalysis['summary']['warning_count'] }}</span>
                                                <span class="stat-label">Uyarı Barkod</span>
                                            </div>
                                            <div class="stat-box">
                                                <span class="stat-icon">⚖️</span>
                                                <span class="stat-value">{{ number_format($stockAgeAnalysis['summary']['warning_quantity'], 1) }}</span>
                                                <span class="stat-label">Ton</span>
                                            </div>
                                            <div class="stat-box">
                                                <span class="stat-icon">⏰</span>
                                                <span class="stat-value">15-29</span>
                                                <span class="stat-label">Gün</span>
                                            </div>
                                        </div>
                                        
                                        <div class="action-list">
                                            <h6 class="action-title">Önerilen Eylemler:</h6>
                                            <ul class="action-items">
                                                <li class="action-item">
                                                    <div class="action-icon">
                                                        <i class="fas fa-list-ol"></i>
                                                    </div>
                                                    <div class="action-content">
                                                        <strong>Öncelik Belirleme</strong>
                                                        <p>Uyarı stokları için işlem sırası oluşturun</p>
                                                    </div>
                                                </li>
                                                <li class="action-item">
                                                    <div class="action-icon">
                                                        <i class="fas fa-expand-arrows-alt"></i>
                                                    </div>
                                                    <div class="action-content">
                                                        <strong>Kapasite Artırımı</strong>
                                                        <p>Laboratuvar ve işlem kapasitesini optimize edin</p>
                                                    </div>
                                                </li>
                                                <li class="action-item">
                                                    <div class="action-icon">
                                                        <i class="fas fa-comments"></i>
                                                    </div>
                                                    <div class="action-content">
                                                        <strong>Erken İletişim</strong>
                                                        <p>Müşteri ile proaktif iletişim kurun</p>
                                                    </div>
                                                </li>
                                                <li class="action-item">
                                                    <div class="action-icon">
                                                        <i class="fas fa-search"></i>
                                                    </div>
                                                    <div class="action-content">
                                                        <strong>Stok Planlaması</strong>
                                                        <p>Mevcut stok planlamasını gözden geçirin</p>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div class="card-footer-section">
                                        <div class="progress-indicator">
                                            <div class="progress-bar">
                                                <div class="progress-fill warning-fill" style="width: 75%"></div>
                                            </div>
                                            <span class="progress-text">Önleyici Aksiyon Gerekli</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                        
                        @if($stockAgeAnalysis['summary']['critical_count'] == 0 && $stockAgeAnalysis['summary']['warning_count'] == 0)
                        <div class="success-recommendation">
                            <div class="success-card">
                                <div class="success-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="success-content">
                                    <h4 class="success-title">🎉 Mükemmel Stok Yönetimi!</h4>
                                    <p class="success-description">
                                        Tüm stoklarınız güncel ve iyi yönetiliyor. Bu performansı sürdürün!
                                    </p>
                                    <div class="success-tips">
                                        <div class="tip-item">
                                            <i class="fas fa-chart-line"></i>
                                            <span>Performans metriklerini takip edin</span>
                                        </div>
                                        <div class="tip-item">
                                            <i class="fas fa-users"></i>
                                            <span>Ekibinizi bu başarı için ödüllendirin</span>
                                        </div>
                                        <div class="tip-item">
                                            <i class="fas fa-book"></i>
                                            <span>En iyi uygulamaları dokümante edin</span>
                                        </div>
                                        <div class="tip-item">
                                            <i class="fas fa-target"></i>
                                            <span>Yeni hedefler belirleyin</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Production Efficiency Analysis -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-tachometer-alt"></i>
                        Üretim Verimliliği Analizi (OEE) - Güncel Veriler
                    </h3>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#oeeInfoModal">
                        <i class="fas fa-info-circle"></i> Bilgi
                    </button>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-clock"></i> 
                    Bu bölüm her zaman güncel tarihe göre hesaplanır, tarih filtresinden etkilenmez
                </small>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    <div class="col-md-3">
                        <div class="efficiency-card">
                            <div class="efficiency-value {{ $aiInsights['production_efficiency']['level'] ?? 'average' }}">
                                %{{ $aiInsights['production_efficiency']['oee_score'] ?? 0 }}
                            </div>
                            <div class="efficiency-label">Genel Verimlilik</div>
                            <div class="efficiency-level">{{ ucfirst($aiInsights['production_efficiency']['level'] ?? 'average') }}</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="efficiency-card">
                            <div class="efficiency-value availability">
                                %{{ $aiInsights['production_efficiency']['availability'] ?? 0 }}
                            </div>
                            <div class="efficiency-label">Erişilebilirlik</div>
                            <div class="efficiency-desc">Makine çalışma süresi</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="efficiency-card">
                            <div class="efficiency-value performance">
                                %{{ $aiInsights['production_efficiency']['performance'] ?? 0 }}
                            </div>
                            <div class="efficiency-label">Performans</div>
                            <div class="efficiency-desc">Üretim hızı</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="efficiency-card">
                            <div class="efficiency-value quality">
                                %{{ $aiInsights['production_efficiency']['quality_rate'] ?? 0 }}
                            </div>
                            <div class="efficiency-label">Kalite</div>
                            <div class="efficiency-desc">Kabul oranı</div>
                        </div>
                    </div>
                </div>
                
                <div class="efficiency-details mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h6><i class="fas fa-info-circle"></i> Verimlilik Detayları</h6>
                            <ul class="efficiency-stats">
                                <li><strong>Toplam Barkod:</strong> {{ number_format($aiInsights['production_efficiency']['total_barcodes'] ?? 0) }}</li>
                                <li><strong>Aktif Barkod:</strong> {{ number_format($aiInsights['production_efficiency']['active_barcodes'] ?? 0) }}</li>
                                <li><strong>Reddedilen:</strong> {{ number_format($aiInsights['production_efficiency']['rejected_barcodes'] ?? 0) }}</li>
                                <li><strong>Birleştirilen:</strong> {{ number_format($aiInsights['production_efficiency']['merged_barcodes'] ?? 0) }}</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6><i class="fas fa-chart-line"></i> Performans Metrikleri</h6>
                            <ul class="efficiency-stats">
                                <li><strong>Ortalama Miktar:</strong> {{ number_format($aiInsights['production_efficiency']['avg_quantity'] ?? 0, 1) }} KG</li>
                                <li><strong>Verimlilik Seviyesi:</strong> 
                                    <span class="badge badge-{{ $aiInsights['production_efficiency']['level'] ?? 'average' }}">
                                        {{ ucfirst($aiInsights['production_efficiency']['level'] ?? 'average') }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- AI/ML Insights Section -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-brain"></i>
                        AI/ML İçgörüler & Tahmin Analizi - Güncel Veriler
                    </h3>
                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#aimlInfoModal">
                        <i class="fas fa-info-circle"></i> Bilgi
                    </button>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-clock"></i> 
                    Bu bölüm her zaman güncel tarihe göre hesaplanır, tarih filtresinden etkilenmez
                </small>
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
                                        <i class="fas fa-check-circle text-success"></i> Aktif
                                    @else
                                        <i class="fas fa-times-circle text-danger"></i> Pasif
                                    @endif
                                </div>
                                <div class="model-accuracy">
                                    <div class="accuracy-bar">
                                        <div class="accuracy-fill" style="width: {{ $aiInsights['model_status']['accuracy']['production'] ?? 0 }}%"></div>
                                    </div>
                                    <span class="accuracy-text">Doğruluk: %{{ $aiInsights['model_status']['accuracy']['production'] ?? 0 }}</span>
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
                                        <i class="fas fa-check-circle text-success"></i> Aktif
                                    @else
                                        <i class="fas fa-times-circle text-danger"></i> Pasif
                                    @endif
                                </div>
                                <div class="model-accuracy">
                                    <div class="accuracy-bar">
                                        <div class="accuracy-fill" style="width: {{ $aiInsights['model_status']['accuracy']['quality'] ?? 0 }}%"></div>
                                    </div>
                                    <span class="accuracy-text">Doğruluk: %{{ $aiInsights['model_status']['accuracy']['quality'] ?? 0 }}</span>
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
                                        <i class="fas fa-check-circle text-success"></i> Aktif
                                    @else
                                        <i class="fas fa-times-circle text-danger"></i> Pasif
                                    @endif
                                </div>
                                <div class="model-accuracy">
                                    <div class="accuracy-bar">
                                        <div class="accuracy-fill" style="width: {{ $aiInsights['model_status']['accuracy']['anomaly'] ?? 0 }}%"></div>
                                    </div>
                                    <span class="accuracy-text">Doğruluk: %{{ $aiInsights['model_status']['accuracy']['anomaly'] ?? 0 }}</span>
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

        <!-- Monthly Comparison Info Modal -->
        <div class="modal fade info-modal" id="monthlyComparisonInfoModal" tabindex="-1" role="dialog" aria-labelledby="monthlyComparisonInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="monthlyComparisonInfoModalLabel">
                            <i class="fas fa-chart-line"></i>
                            Aylık Karşılaştırma Analizi Bilgileri
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="metric-explanation">
                            <div class="metric-title">📊 Aylık Karşılaştırma Nedir?</div>
                            <div class="metric-desc">
                                Aylık karşılaştırma analizi, bu ay ile geçen ay arasındaki üretim performansını karşılaştırarak trend analizi yapar. Bu analiz sayesinde üretim kapasitesi, verimlilik ve büyüme oranlarını takip edebilirsiniz.
                            </div>
                        </div>

                        <div class="metric-explanation">
                            <div class="metric-title">🔍 Hangi Metrikler Karşılaştırılır?</div>
                            <div class="metric-desc">
                                <strong>Barkod Sayısı:</strong> Üretim hacmi ve iş yükü karşılaştırması<br>
                                <strong>Toplam Miktar:</strong> Üretim kapasitesi ve tonaj karşılaştırması<br>
                                <strong>Değişim Oranı:</strong> Yüzdelik artış veya azalış miktarı<br>
                                <strong>Trend Analizi:</strong> Performans yönü ve büyüklüğü
                            </div>
                        </div>

                        <div class="metric-explanation">
                            <div class="metric-title">📈 Değişim Oranları Nasıl Hesaplanır?</div>
                            <div class="metric-desc">
                                <strong>Formül:</strong> ((Bu Ay - Geçen Ay) / Geçen Ay) × 100<br>
                                <strong>Pozitif Değer:</strong> Artış gösterir (örn: +15% = %15 artış)<br>
                                <strong>Negatif Değer:</strong> Azalış gösterir (örn: -8% = %8 azalış)<br>
                                <strong>%100:</strong> Önceki ay 0, bu ay veri varsa (yeni üretim)<br>
                                <strong>-%100:</strong> Önceki ay veri, bu ay 0 ise (üretim durdu)
                            </div>
                        </div>

                        <div class="example-box">
                            <div class="example-title">📝 Örnek Senaryolar</div>
                            <div class="metric-desc">
                                <strong>Senaryo 1 - Artış:</strong><br>
                                Geçen ay: 1000 barkod, Bu ay: 1200 barkod<br>
                                Değişim: ((1200-1000)/1000) × 100 = +20% (Güçlü artış)<br><br>
                                
                                <strong>Senaryo 2 - Azalış:</strong><br>
                                Geçen ay: 1000 barkod, Bu ay: 900 barkod<br>
                                Değişim: ((900-1000)/1000) × 100 = -10% (Orta düşüş)<br><br>
                                
                                <strong>Senaryo 3 - Yeni Üretim:</strong><br>
                                Geçen ay: 0 barkod, Bu ay: 500 barkod<br>
                                Değişim: +100% (Yeni üretim başladı)
                            </div>
                        </div>

                        <div class="metric-explanation">
                            <div class="metric-title">🎯 Trend Kategorileri</div>
                            <div class="metric-desc">
                                <strong>Güçlü Artış:</strong> %20 ve üzeri artış (yeşil)<br>
                                <strong>Orta Artış:</strong> %10-%19 arası artış (yeşil)<br>
                                <strong>Hafif Artış:</strong> %1-%9 arası artış (yeşil)<br>
                                <strong>Hafif Düşüş:</strong> %1-%9 arası azalış (kırmızı)<br>
                                <strong>Orta Düşüş:</strong> %10-%19 arası azalış (kırmızı)<br>
                                <strong>Önemli Düşüş:</strong> %20 ve üzeri azalış (kırmızı)
                            </div>
                        </div>

                        <div class="metric-explanation">
                            <div class="metric-title">💡 Neden Önemli?</div>
                            <div class="metric-desc">
                                <strong>Performans Takibi:</strong> Aylık hedeflerin karşılanıp karşılanmadığını gösterir<br>
                                <strong>Trend Analizi:</strong> Uzun vadeli büyüme veya düşüş eğilimlerini belirler<br>
                                <strong>Kapasite Planlaması:</strong> Gelecek aylar için üretim planlaması yapılmasına yardımcı olur<br>
                                <strong>Problem Tespiti:</strong> Ani düşüşlerde hızlı müdahale imkanı sağlar<br>
                                <strong>Başarı Ölçümü:</strong> İyileştirme çalışmalarının etkisini ölçer
                            </div>
                        </div>

                        <div class="metric-explanation">
                            <div class="metric-title">🚨 Dikkat Edilmesi Gerekenler</div>
                            <div class="metric-desc">
                                <strong>Sezonsal Etkiler:</strong> Bazı aylar doğal olarak düşük üretim gösterebilir<br>
                                <strong>Bakım Planları:</strong> Planlı bakım süreleri üretimi etkileyebilir<br>
                                <strong>Pazar Koşulları:</strong> Talep değişiklikleri üretim miktarını etkiler<br>
                                <strong>Personel Durumu:</strong> Tatil, hastalık gibi faktörler üretimi etkileyebilir<br>
                                <strong>Hammadde Tedariki:</strong> Tedarik zinciri sorunları üretimi durdurabilir
                            </div>
                        </div>

                        <div class="metric-explanation">
                            <div class="metric-title">🔄 Güncelleme Sıklığı</div>
                            <div class="metric-desc">
                                <strong>Gerçek Zamanlı:</strong> Dashboard her sayfa yenilendiğinde güncel veriler gösterilir<br>
                                <strong>Veri Kaynağı:</strong> Doğrudan veritabanından alınan güncel üretim verileri<br>
                                <strong>Zaman Dilimi:</strong> Avrupa/İstanbul saat dilimine göre hesaplanır<br>
                                <strong>Filtreleme:</strong> Tarih filtresinden etkilenmez, her zaman güncel aylık veriler
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- OEE Bilgi Modal -->
        <div class="modal fade info-modal" id="oeeInfoModal" tabindex="-1" role="dialog" aria-labelledby="oeeInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="oeeInfoModalLabel">
                    <i class="fas fa-tachometer-alt"></i> OEE (Üretim Verimliliği) Nedir?
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="metric-explanation">
                    <div class="metric-title">🎯 OEE Nedir?</div>
                    <div class="metric-desc">
                        OEE (Overall Equipment Effectiveness), üretim süreçlerinin verimliliğini ölçen dünya standartlarında bir metrik. 
                        Makinelerinizin ne kadar verimli çalıştığını gösterir.
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">📊 OEE Hesaplama</div>
                    <div class="metric-desc">
                        OEE, 3 ana faktörün çarpımı ile hesaplanır:
                    </div>
                    <div class="metric-formula">
                        OEE = Erişilebilirlik × Performans × Kalite
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">⏰ 1. Erişilebilirlik (Availability)</div>
                    <div class="metric-desc">
                        Makinelerinizin ne kadar süre çalıştığını gösterir. 
                        Planlı duruşlar, arızalar ve bakım süreleri bu metriği etkiler.
                    </div>
                    <div class="metric-formula">
                        Erişilebilirlik = (Aktif Barkod / Toplam Barkod) × 100
                    </div>
                    <div class="metric-desc">
                        <strong>Aktif Barkod:</strong> Üretim sürecinde olan barkodlar (Beklemede, Kontrol, Onaylı, Sevk, Transfer)
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">🚀 2. Performans (Performance)</div>
                    <div class="metric-desc">
                        Makinelerinizin standart hızda çalışıp çalışmadığını gösterir. 
                        Yavaş çalışma, duruşlar ve verimsizlik bu metriği etkiler.
                    </div>
                    <div class="metric-formula">
                        Performans = (Ortalama Miktar / Standart Miktar) × 100
                    </div>
                    <div class="metric-desc">
                        <strong>Standart Miktar:</strong> 1000 KG/barkod (ideal üretim miktarı)
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">✅ 3. Kalite (Quality)</div>
                    <div class="metric-desc">
                        Üretilen ürünlerin ne kadarının kabul edildiğini gösterir. 
                        Reddedilen ürünler ve birleştirme işlemleri bu metriği etkiler.
                    </div>
                    <div class="metric-formula">
                        Kalite = ((Toplam - Reddedilen - Birleştirilen) / Toplam) × 100
                    </div>
                </div>

                <div class="example-box">
                    <div class="example-title">📝 Hesaplama Örneği</div>
                    <div class="metric-desc">
                        <strong>Senaryo:</strong> 1000 barkod üretildi, 850'si aktif, 100'ü reddedildi, 50'si birleştirildi
                    </div>
                    <div class="metric-formula">
                        Erişilebilirlik = (850/1000) × 100 = %85<br>
                        Performans = (950/1000) × 100 = %95<br>
                        Kalite = ((1000-100-50)/1000) × 100 = %85<br><br>
                        OEE = (85 × 95 × 85) / 10000 = %68.6
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">🏆 Verimlilik Seviyeleri</div>
                    <div class="metric-desc">
                        OEE skoruna göre verimlilik seviyeleri:
                    </div>
                    <div class="metric-desc">
                        <span class="level-indicator excellent">90%+ Excellent</span> - Dünya standartlarında üretim<br>
                        <span class="level-indicator good">80-89% Good</span> - İyi performans, küçük iyileştirmeler gerekli<br>
                        <span class="level-indicator average">70-79% Average</span> - Ortalama, orta seviye iyileştirmeler gerekli<br>
                        <span class="level-indicator poor">60-69% Poor</span> - Düşük performans, ciddi iyileştirmeler gerekli<br>
                        <span class="level-indicator critical"><60% Critical</span> - Kritik durum, acil müdahale gerekli
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">💡 İyileştirme Önerileri</div>
                    <div class="metric-desc">
                        <strong>Erişilebilirlik Düşükse:</strong> Makine bakım planlaması, vardiya optimizasyonu<br>
                        <strong>Performans Düşükse:</strong> Üretim hızı optimizasyonu, personel eğitimi<br>
                        <strong>Kalite Düşükse:</strong> Hammadde kalite kontrolü, üretim parametreleri
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AI/ML Bilgi Modal -->
<div class="modal fade info-modal" id="aimlInfoModal" tabindex="-1" role="dialog" aria-labelledby="aimlInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="aimlInfoModalLabel">
                    <i class="fas fa-brain"></i> AI/ML İçgörüler & Tahmin Analizi Nedir?
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="metric-explanation">
                    <div class="metric-title">🤖 AI/ML Nedir?</div>
                    <div class="metric-desc">
                        AI (Yapay Zeka) ve ML (Makine Öğrenmesi), üretim verilerinizi analiz ederek 
                        gelecekteki üretim ve kalite durumlarını tahmin eden akıllı sistemlerdir.
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">📈 1. Üretim Modeli</div>
                    <div class="metric-desc">
                        <strong>Ne Yapar:</strong> Geçmiş üretim verilerinizi analiz ederek gelecekteki üretim miktarlarını tahmin eder.<br>
                        <strong>Nasıl Çalışır:</strong> Son 7 günlük gerçek üretimi, önceki 7 günlük tahminle karşılaştırır.<br>
                        <strong>Doğruluk:</strong> Tahmin güvenilirliği %70+ olmalıdır.
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">🔍 2. Kalite Modeli</div>
                    <div class="metric-desc">
                        <strong>Ne Yapar:</strong> Geçmiş kalite verilerinizi analiz ederek gelecekteki red oranlarını tahmin eder.<br>
                        <strong>Nasıl Çalışır:</strong> Son 14 günlük gerçek red oranını, önceki 14 günlük tahminle karşılaştırır.<br>
                        <strong>Doğruluk:</strong> Tahmin güvenilirliği %70+ olmalıdır.
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">⚠️ 3. Anomali Tespit Modeli</div>
                    <div class="metric-desc">
                        <strong>Ne Yapar:</strong> Üretim verilerinizde normal olmayan durumları tespit eder.<br>
                        <strong>Nasıl Çalışır:</strong> Son 30 günlük verileri analiz ederek standart sapma hesaplar.<br>
                        <strong>Doğruluk:</strong> %5-15 arası anomali oranı normal kabul edilir.
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">📊 Model Durumu</div>
                    <div class="metric-desc">
                        <strong>Aktif (🟢):</strong> Model çalışıyor ve doğru tahminler yapıyor<br>
                        <strong>Pasif (🔴):</strong> Model durmuş veya yanlış tahminler yapıyor
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">🎯 Doğruluk Oranı</div>
                    <div class="metric-desc">
                        <strong>%90+:</strong> Mükemmel tahmin - Güvenle kullanabilirsiniz<br>
                        <strong>%80-89%:</strong> İyi tahmin - Çoğu durumda güvenilir<br>
                        <strong>%70-79%:</strong> Ortalama tahmin - Dikkatli kullanın<br>
                        <strong>%70-:</strong> Düşük tahmin - Modeli yeniden eğitmek gerekebilir
                    </div>
                </div>

                <div class="example-box">
                    <div class="example-title">📝 Nasıl Çalışır?</div>
                    <div class="metric-desc">
                        <strong>1. Veri Toplama:</strong> Sistem her gün üretim verilerinizi toplar<br>
                        <strong>2. Analiz:</strong> Geçmiş verileri analiz ederek pattern'ları bulur<br>
                        <strong>3. Tahmin:</strong> Bulunan pattern'lara göre geleceği tahmin eder<br>
                        <strong>4. Karşılaştırma:</strong> Tahminleri gerçek verilerle karşılaştırır<br>
                        <strong>5. Öğrenme:</strong> Hatalardan öğrenerek daha iyi tahminler yapar
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">💡 Neden Önemli?</div>
                    <div class="metric-desc">
                        <strong>Planlama:</strong> Gelecekteki üretim miktarlarını önceden bilirsiniz<br>
                        <strong>Kalite:</strong> Kalite sorunlarını önceden tespit edebilirsiniz<br>
                        <strong>Maliyet:</strong> Gereksiz harcamaları önleyebilirsiniz<br>
                        <strong>Verimlilik:</strong> Üretim süreçlerinizi optimize edebilirsiniz
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">🚀 Gelecek Özellikler</div>
                    <div class="metric-desc">
                        <strong>Yakın Vadede:</strong> Daha gelişmiş tahmin algoritmaları, otomatik uyarılar<br>
                        <strong>Orta Vadede:</strong> Görüntü işleme ile kalite kontrolü, IoT sensör entegrasyonu<br>
                        <strong>Uzun Vadede:</strong> Tam otomatik üretim optimizasyonu, öngörücü bakım
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stok Yaşı Bilgi Modal -->
<div class="modal fade info-modal" id="stockAgeInfoModal" tabindex="-1" role="dialog" aria-labelledby="stockAgeInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="stockAgeInfoModalLabel">
                    <i class="fas fa-clock"></i> Stok Yaşı Analizi Dashboard'u Nedir?
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="metric-explanation">
                    <div class="metric-title">🎯 Stok Yaşı Analizi Nedir?</div>
                    <div class="metric-desc">
                        Stok yaşı analizi, üretilen ürünlerin (barkodların) ne kadar süredir sistemde beklediğini 
                        analiz eden bir sistemdir. Bu sayede gözden kaçan stokları yakalayabilir ve 
                        stok yönetimini optimize edebilirsiniz.
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">⏰ Yaş Kategorileri</div>
                    <div class="metric-desc">
                        <strong>🔴 Kritik (30+ gün):</strong> Acil müdahale gerektiren stoklar<br>
                        <strong>🟡 Uyarı (15-29 gün):</strong> Dikkat edilmesi gereken stoklar<br>
                        <strong>🔵 Dikkat (7-14 gün):</strong> Yakından takip edilmesi gereken stoklar<br>
                        <strong>🟢 Normal (0-6 gün):</strong> Güncel ve iyi yönetilen stoklar
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">📊 Dashboard Özellikleri</div>
                    <div class="metric-desc">
                        <strong>Özet Kartları:</strong> Her yaş kategorisindeki barkod sayısı ve miktarı<br>
                        <strong>Kritik Stok Detayları:</strong> 30+ günlük stokların detaylı listesi<br>
                        <strong>Uyarı Stok Detayları:</strong> 15-29 günlük stokların listesi<br>
                        <strong>Durum Analizi:</strong> Her durumdaki stokların yaş ortalaması<br>
                        <strong>Ürün Analizi:</strong> En kritik ürünlerin listesi<br>
                        <strong>Aksiyon Önerileri:</strong> Stok yaşına göre yapılması gerekenler
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">🔍 Nasıl Hesaplanır?</div>
                    <div class="metric-desc">
                        <strong>Stok Yaşı:</strong> Bugün - Barkod oluşturulma tarihi<br>
                        <strong>Lab Süresi:</strong> Bugün - Laboratuvar işlem tarihi<br>
                        <strong>Sevk Süresi:</strong> Bugün - Sevk tarihi<br>
                        <strong>Güncelleme Süresi:</strong> Bugün - Son güncelleme tarihi
                    </div>
                </div>

                <div class="example-box">
                    <div class="example-title">📝 Örnek Senaryo</div>
                    <div class="metric-desc">
                        <strong>Barkod:</strong> ABC123<br>
                        <strong>Oluşturulma:</strong> 15.01.2024<br>
                        <strong>Bugün:</strong> 20.02.2024<br>
                        <strong>Yaş:</strong> 36 gün (Kritik kategori)<br>
                        <strong>Durum:</strong> Beklemede<br>
                        <strong>Miktar:</strong> 1500 KG<br><br>
                        <strong>Sonuç:</strong> Bu barkod kritik kategoride ve acil müdahale gerektiriyor.
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">💡 Neden Önemli?</div>
                    <div class="metric-desc">
                        <strong>Stok Rotasyonu:</strong> Eski stokların öncelikli işlenmesini sağlar<br>
                        <strong>Müşteri Memnuniyeti:</strong> Gecikmeleri önler ve teslimat sürelerini kısaltır<br>
                        <strong>Maliyet Kontrolü:</strong> Stok tutma maliyetlerini azaltır<br>
                        <strong>Kalite Güvencesi:</strong> Ürün kalitesinin korunmasını sağlar<br>
                        <strong>Depo Optimizasyonu:</strong> Depo alanının verimli kullanılmasını sağlar
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">🚨 Kritik Durumlar</div>
                    <div class="metric-desc">
                        <strong>30+ Günlük Stok:</strong> Acil müdahale gerekli, müşteri ile iletişim kurulmalı<br>
                        <strong>15+ Günlük Stok:</strong> Öncelik belirlenmeli, laboratuvar kapasitesi artırılmalı<br>
                        <strong>Beklemede Durumu:</strong> Laboratuvar süreçleri hızlandırılmalı<br>
                        <strong>Reddedilen Ürünler:</strong> Hızlıca işlenmeli veya bertaraf edilmeli
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">📈 Performans Metrikleri</div>
                    <div class="metric-desc">
                        <strong>Ortalama Stok Yaşı:</strong> Tüm stokların ortalama yaşı<br>
                        <strong>Kritik Stok Oranı:</strong> Kritik kategorideki stokların yüzdesi<br>
                        <strong>Stok Devir Hızı:</strong> Stokların ne kadar hızlı işlendiği<br>
                        <strong>Durum Bazında Yaş:</strong> Her durumdaki stokların yaş analizi
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">🎯 İyileştirme Stratejileri</div>
                    <div class="metric-desc">
                        <strong>Laboratuvar Kapasitesi:</strong> Test süreçlerini hızlandırın<br>
                        <strong>Müşteri İletişimi:</strong> Teslimat planlarını netleştirin<br>
                        <strong>Stok Planlaması:</strong> Üretim ve teslimat planlarını senkronize edin<br>
                        <strong>Otomasyon:</strong> Manuel süreçleri otomatikleştirin<br>
                        <strong>Personel Eğitimi:</strong> Stok yönetimi konusunda eğitim verin
                    </div>
                </div>

                <div class="metric-explanation">
                    <div class="metric-title">🔄 Güncelleme Sıklığı</div>
                    <div class="metric-desc">
                        <strong>Gerçek Zamanlı:</strong> Dashboard her sayfa yenilendiğinde güncellenir<br>
                        <strong>Veri Kaynağı:</strong> Doğrudan veritabanından alınan güncel veriler<br>
                        <strong>Zaman Dilimi:</strong> Avrupa/İstanbul saat dilimine göre hesaplanır<br>
                        <strong>Filtreleme:</strong> Tarih filtresinden etkilenmez, her zaman güncel
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Add smooth animations for the monthly comparison cards
document.addEventListener('DOMContentLoaded', function() {
    // Animate progress bars
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach(bar => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, 500);
    });
    
    // Add hover effects for month cards
    const monthCards = document.querySelectorAll('.month-card');
    monthCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Add click effects for change metric cards
    const changeCards = document.querySelectorAll('.change-metric-card');
    changeCards.forEach(card => {
        card.addEventListener('click', function() {
            this.style.transform = 'scale(0.98)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 150);
        });
    });
});
</script>

@endsection
