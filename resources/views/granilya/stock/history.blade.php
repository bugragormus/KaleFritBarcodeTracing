@extends('layouts.granilya')

@section('styles')
<style>
    .modern-history {
        background: #f8f9fa;
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
    
    .timeline {
        position: relative;
        padding: 20px 0;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        left: 20px; /* Adjusted from 50px */
        height: 100%;
        width: 4px;
        background: #e9ecef;
        border-radius: 2px;
    }
    
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
        padding-left: 60px; /* Adjusted from 100px */
    }
    
    .timeline-icon {
        position: absolute;
        left: 0; /* Adjusted from 32px to align with before line */
        top: 0;
        width: 44px;
        height: 44px;
        background: white;
        border: 4px solid #667eea;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        color: #667eea;
        box-shadow: 0 3px 10px rgba(102, 126, 234, 0.2);
    }
    
    .timeline-content {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border: 1px solid #e9ecef;
        transition: all 0.3s ease;
    }

    .timeline-content:hover {
        transform: translateX(5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }
    
    .timeline-date {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    .timeline-user {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .changes-table {
        margin-top: 1rem;
        font-size: 0.85rem;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #dee2e6;
    }

    .changes-table table {
        margin-bottom: 0;
    }

    .changes-table thead th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #dee2e6;
    }

    .changes-table tbody tr:nth-of-type(odd) {
        background-color: rgba(0,0,0,.02);
    }
    
    .change-arrow {
        color: #667eea;
        margin: 0 10px;
    }

    @media (max-width: 768px) {
        .timeline::before {
            left: 15px;
        }
        .timeline-item {
            padding-left: 45px;
        }
        .timeline-icon {
            width: 34px;
            height: 34px;
            border-width: 3px;
        }
    }
</style>
@endsection

@section('content')
<div class="modern-history">
    <div class="container-fluid">
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-history"></i> Palet Hareketleri
                    </h1>
                    <p class="page-subtitle-modern">{{ $pallet->pallet_number }} numaralı paletin geçmişi</p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('granilya.stock.index') }}" class="btn-modern btn-secondary-modern">
                        <i class="fas fa-arrow-left"></i> Geri Dön
                    </a>
                </div>
            </div>
        </div>

        <div class="timeline">
            @forelse($histories as $history)
                <div class="timeline-item">
                    <div class="timeline-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            <i class="far fa-clock"></i> {{ $history->created_at->format('d.m.Y H:i:s') }}
                        </div>
                        <div class="timeline-user">
                            <i class="far fa-user"></i> {{ $history->user->name }}
                        </div>
                        <div class="timeline-desc">
                            <strong>İşlem:</strong> {{ $history->description }}
                        </div>
                        
                        @if($history->changes)
                            <div class="changes-table">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Alan</th>
                                            <th>Eski Değer</th>
                                            <th>Yeni Değer</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($history->changes as $field => $change)
                                            <tr>
                                                <td><strong>{{ $field }}</strong></td>
                                                <td class="text-danger">{{ $change['from'] ?? '-' }}</td>
                                                <td class="text-success">{{ $change['to'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-info">Bu palet için henüz bir hareket kaydı bulunmuyor.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
