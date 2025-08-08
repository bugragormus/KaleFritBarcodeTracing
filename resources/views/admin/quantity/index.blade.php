@extends('layouts.app')

@section('meta')
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
@endsection

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
        
        .btn-danger-modern {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
            color: white;
        }
        
        .table-modern {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .table-modern thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 1rem;
            font-weight: 600;
            text-align: center;
        }
        
        .table-modern tbody td {
            padding: 1rem;
            border: none;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }
        
        .table-modern tbody td:nth-child(1) {
            text-align: center !important;
        }
        
        .table-modern tbody td:nth-child(2) {
            text-align: center !important;
        }
        
        .table-modern tbody tr:hover {
            background: #f8f9fa;
        }
        
        .table-modern .quantity-id {
            font-weight: 600;
            color: #495057;
        }
        
        .table-modern .quantity-value {
            font-weight: 600;
            color: #28a745;
            font-size: 1.1rem;
        }
        
        .table-modern .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .table-modern .btn-xs-modern {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: 8px;
        }
        
        @media (max-width: 768px) {
            .page-title-modern {
                font-size: 2rem;
            }
            
            .table-modern .action-buttons {
                flex-direction: column;
            }
            
            .table-modern .btn-xs-modern {
                width: 100%;
                margin-bottom: 0.25rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="modern-quantity-management">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-calculator"></i> Adet Yönetimi
                        </h1>
                        <p class="page-subtitle-modern">Sistemdeki tüm adet girişlerini görüntüleyin, düzenleyin ve yönetin</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('quantity.create') }}" class="btn-modern btn-success-modern">
                            <i class="fas fa-plus"></i> Yeni Adet Girişi
                        </a>
                    </div>
                </div>
            </div>

            <!-- Modern Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-list"></i> Adet Listesi
                    </h3>
                    <p class="card-subtitle-modern">
                        Aşağıdaki listede sisteme kayıtlı tüm adet girişlerini görebilir, düzenleyebilir ve silebilirsiniz
                    </p>
                </div>
                
                <div class="card-body-modern">
                    <table id="datatable" class="table table-modern">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Adet</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quantities as $quantity)
                            <tr>
                                <td>
                                    <div class="quantity-id">{{ $quantity->id }}</div>
                                </td>
                                <td>
                                    <div class="quantity-value">{{ $quantity->quantity }}</div>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('quantity.edit', ['adet' => $quantity->id]) }}" class="btn-modern btn-success-modern btn-xs-modern">
                                            <i class="fas fa-edit"></i> Düzenle
                                        </a>
                                        <button class="btn-modern btn-danger-modern btn-xs-modern" data-id="{{ $quantity->id }}" data-action="{{ route('quantity.destroy', $quantity->id) }}" onclick='deleteConfirmation("{{$quantity->id}}")'>
                                            <i class="fas fa-trash"></i> Sil
                                        </button>
                                    </div>
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
    <script type="text/javascript">
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content') }
        });
        function deleteConfirmation(id) {

            swal({
                title: "Silmek istediğinize emin misiniz?",
                text: "Silme işlemi geri alınamaz!",
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: 'btn btn-danger btn-lg',
                confirmButtonText: "Sil",
                cancelButtonClass: 'btn btn-primary btn-lg m-l-10',
                cancelButtonText: "Vazgeç",
                buttonsStyling: false
            }).then(function (e) {
                if (e.value === true) {
                    var data = {
                        "_token": $('input[name="_token"]').val(),
                        "id": id
                    }

                    $.ajax({
                        type: 'DELETE',
                        url: "{{url('/adet')}}/" + id,
                        data: data,
                        success: function (results) {
                            if (results) {
                                swal("Başarılı!", results.message, "success");
                                location.reload();
                            } else {
                                swal("Hata!", "Lütfen tekrar deneyin!", "error");
                            }
                        }
                    });

                } else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        }
    </script>

    <script>
        $('#datatable').DataTable( {
            dom: 'Bfrtip',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ],
            pageLength: 25,
            // Cache'i devre dışı bırak
            bStateSave: false,
            // AJAX kullanmadan normal tablo
            processing: false,
            serverSide: false
        } );
    </script>
@endsection
