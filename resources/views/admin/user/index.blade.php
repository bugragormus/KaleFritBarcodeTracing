@extends('layouts.app')
@section('styles')
    <style>
        body, .main-content, .modern-user-management {
            background: #f8f9fa !important;
        }
        .modern-user-management {
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
        
        .stats-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: #ffffff;
            border-radius: 15px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border: 1px solid #e9ecef;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .stat-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
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
        
        .btn-primary-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-success-modern {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-info-modern {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
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
            text-align: left !important;
        }
        
        .table-modern tbody td:nth-child(2) {
            text-align: left !important;
        }
        
        .table-modern tbody td:nth-child(3) {
            text-align: center !important;
        }
        
        .table-modern tbody td:nth-child(4) {
            text-align: center !important;
        }
        
        .table-modern tbody tr:hover {
            background: #f8f9fa;
        }
        
        .table-modern .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 0.75rem;
        }
        
        .table-modern .user-info {
            display: flex;
            align-items: flex-start;
            text-align: left;
            justify-content: flex-start;
        }
        
        .table-modern .user-name {
            font-weight: 600;
            color: #495057;
        }
        
        .table-modern .user-email {
            color: #6c757d;
            font-size: 0.875rem;
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
        
        .table-modern .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .table-modern .status-active {
            background: #d4edda;
            color: #155724;
        }
        
        .table-modern .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }
        
        @media (max-width: 768px) {
            .stats-cards {
                grid-template-columns: 1fr;
            }
            
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
    <div class="modern-user-management">
        <div class="container-fluid">
            <!-- Modern Page Header -->
            <div class="page-header-modern">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="page-title-modern">
                            <i class="fas fa-users"></i> Kullanıcı Yönetimi
                        </h1>
                        <p class="page-subtitle-modern">Sistem kullanıcılarını yönetin ve yetkilerini düzenleyin</p>
                    </div>
                    <div class="col-md-4 text-right">
                        <a href="{{ route('user.create') }}" class="btn-modern btn-success-modern">
                            <i class="fas fa-plus"></i> Yeni Kullanıcı
                        </a>
                    </div>
                </div>
            </div>



            <!-- Modern Card -->
            <div class="card-modern">
                <div class="card-header-modern">
                    <h3 class="card-title-modern">
                        <i class="fas fa-list"></i> Kullanıcı Listesi
                    </h3>
                    <p class="card-subtitle-modern">
                        Sisteme kayıtlı tüm kullanıcıları görebilir, düzenleyebilir ve silebilirsiniz
                    </p>
                </div>
                
                <div class="card-body-modern">
                    <table id="datatable" class="table table-modern">
                        <thead>
                            <tr>
                                <th>Kullanıcı</th>
                                <th>İletişim Bilgileri</th>
                                <th>Sicil Numarası</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>

                        <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="user-info">
                                    <div>
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-email">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div><i class="fas fa-envelope text-muted"></i> {{ $user->email }}</div>
                                    @if($user->phone)
                                        <div><i class="fas fa-phone text-muted"></i> {{ $user->phone }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($user->registration_number)
                                    <span class="badge badge-secondary">{{ $user->registration_number }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="{{ route('user.edit', ['user' => $user->id]) }}" class="btn-modern btn-success-modern btn-xs-modern">
                                        <i class="fas fa-edit"></i> Düzenle
                                    </a>
                                    <a href="{{ route('user.permission-edit', ['user' => $user->id]) }}" class="btn-modern btn-info-modern btn-xs-modern">
                                        <i class="fas fa-shield-alt"></i> Yetkiler
                                    </a>
                                    @if(Auth::user()->id !== $user->id)
                                        <button class="btn-modern btn-danger-modern btn-xs-modern" onclick='deleteConfirmation("{{$user->id}}")'>
                                            <i class="fas fa-trash"></i> Sil
                                        </button>
                                    @endif
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
                        url: "{{url('/kullanici')}}/" + id,
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


        $('#datatable').dataTable( {
            language: {
                search: "Kullanıcı Arama: ",
                searchPlaceholder: "Orhan ÖZKAN"
            }
        } );
    </script>
@endsection
