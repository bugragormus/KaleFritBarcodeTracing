@extends('layouts.granilya')

@section('styles')
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .modern-lab-list {
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
        .card-modern {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid #e9ecef;
            padding: 2rem;
        }
    </style>
@endsection

@section('content')
<div class="modern-lab-list">
    <div class="container-fluid">
        <div class="page-header-modern">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="page-title-modern text-white">
                        <i class="fas fa-list mr-2"></i> Barkod Listesi
                    </h1>
                    <p class="mb-0 text-white">Tüm Granilya paletlerini ve test durumlarını buradan listeleyebilirsiniz.</p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('granilya.laboratory.dashboard') }}" class="btn btn-light font-weight-bold">
                        <i class="fas fa-arrow-left mr-2"></i> Dashboard'a Dön
                    </a>
                </div>
            </div>
        </div>

        <div class="card-modern">
            <div class="table-responsive">
                <table id="palletTable" class="table table-hover w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Palet No</th>
                            <th>Stok Adı</th>
                            <th>Şarj No</th>
                            <th>Miktar</th>
                            <th>Elek</th>
                            <th>Yüzey</th>
                            <th>Arge</th>
                            <th>Durum</th>
                            <th class="text-right">İşlem</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@include('granilya.laboratory.modals')
@endsection

@section('scripts')
<script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#palletTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('granilya.laboratory.barcode-list') }}",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'pallet_number', name: 'pallet_number' },
                { data: 'stock.name', name: 'stock.name', defaultContent: '-' },
                { data: 'load_number', name: 'load_number', defaultContent: '-' },
                { data: 'quantity.quantity', name: 'quantity.quantity', defaultContent: '-' },
                { data: 'test_sieve', name: 'test_sieve', orderable: false, searchable: false },
                { data: 'test_surface', name: 'test_surface', orderable: false, searchable: false },
                { data: 'test_arge', name: 'test_arge', orderable: false, searchable: false },
                { data: 'status_badge', name: 'status_badge', orderable: false, searchable: false },
                { 
                    data: 'id', 
                    name: 'action', 
                    orderable: false, 
                    searchable: false,
                    className: 'text-right',
                    render: function(data) {
                        return '<button class="btn btn-sm btn-outline-primary" onclick="openPalletDetail('+data+')"><i class="fas fa-eye"></i> İncele</button>';
                    }
                }
            ],
            language: {
                url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json"
            }
        });
    });
</script>
@endsection
