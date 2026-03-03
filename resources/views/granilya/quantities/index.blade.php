@extends('layouts.granilya')

@section('styles')
    <style>
        body, .main-content, .modern-quantity-management {
            background: #f8f9fa !important;
        }
        .modern-quantity-management {
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
            margin-bottom: 2rem;
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
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
        }
        
        /* DataTable modern styling */
        .table-modern {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table-modern th {
            background-color: #f8f9fa;
            color: #495057;
            font-weight: 600;
            border-bottom: 2px solid #e9ecef;
            padding: 1rem;
        }
        .table-modern td {
            vertical-align: middle;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
        }
        .table-modern tr:last-child td {
            border-bottom: none;
        }
        .table-modern tbody tr {
            transition: background-color 0.2s ease;
        }
        .table-modern tbody tr:hover {
            background-color: #f8f9fa;
        }
        .badge-modern {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-weight: 500;
        }
        .badge-success-modern {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-danger-modern {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .action-btns .btn {
            border-radius: 8px;
            padding: 0.4rem 0.8rem;
        }
    </style>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('granilya.dashboard') }}"><i class="fas fa-home"></i> Ana Sayfa</a></li>
    <li class="breadcrumb-item active">Miktarlar (KG)</li>
@endsection

@section('content')
<div class="modern-quantity-management">
    <div class="container-fluid">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Modern Page Header -->
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern">
                        <i class="fas fa-balance-scale"></i> Paketleme Miktarları (KG)
                    </h1>
                    <p class="page-subtitle-modern">Granilya ambalaj/paketleme miktar tanımlamalarını yönetin</p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('granilya.miktar.create') }}" class="btn-modern btn-success-modern">
                        <i class="fas fa-plus"></i> Yeni Miktar Ekle
                    </a>
                </div>
            </div>
        </div>

        <div class="card-modern">
            <div class="card-body-modern">
                <table id="datatable" class="table table-modern dt-responsive nowrap">
                    <thead>
                        <tr>
                            <th>Miktar</th>
                            <th class="text-right">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($quantities as $quantity)
                            <tr>
                                <td><strong>{{ $quantity->quantity }} KG</strong></td>
                                <td class="text-right action-btns">
                                    <a href="{{ route('granilya.miktar.edit', $quantity->id) }}" class="btn btn-sm btn-primary shadow-sm mr-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('granilya.miktar.destroy', $quantity->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu miktarı silmek istediğinize emin misiniz?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger shadow-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "destroy": true,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json"
            }
        });
    });
</script>
@endsection
