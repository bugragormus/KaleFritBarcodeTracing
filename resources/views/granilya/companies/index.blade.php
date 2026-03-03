@extends('layouts.granilya')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('granilya.dashboard') }}"><i class="fas fa-home"></i> Ana Sayfa</a></li>
    <li class="breadcrumb-item active">Firmalar</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title mb-0 font-size-18">Firmalar</h4>
                <div>
                    <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#createModal">
                        <i class="fas fa-plus mr-1"></i> Yeni Firma Ekle
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                        <thead>
                            <tr>
                                <th>Kod</th>
                                <th>Adı</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <td>{{ $company->code ?? '-' }}</td>
                                    <td>{{ $company->name }}</td>
                                    <td>
                                        @if($company->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Pasif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info waves-effect waves-light edit-btn" 
                                            data-id="{{ $company->id }}" 
                                            data-name="{{ $company->name }}" 
                                            data-code="{{ $company->code }}" 
                                            data-active="{{ $company->is_active }}"
                                            data-toggle="modal" data-target="#editModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('granilya.firma.destroy', $company->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bu firmayı silmek istediğinize emin misiniz?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger waves-effect waves-light">
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
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Yeni Firma Ekle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('granilya.firma.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kod</label>
                        <input type="text" name="code" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Adı <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="customCheck1" name="is_active" value="1" checked>
                            <label class="custom-control-label" for="customCheck1">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Firma Düzenle</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Kod</label>
                        <input type="text" name="code" id="edit_code" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Adı <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="editCheck1" name="is_active" value="1">
                            <label class="custom-control-label" for="editCheck1">Aktif</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">İptal</button>
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
            </form>
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

        // Edit Modal Data Binding
        $('.edit-btn').on('click', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var code = $(this).data('code');
            var active = $(this).data('active');

            $('#editForm').attr('action', '/granilya/firma/' + id);
            $('#edit_name').val(name);
            $('#edit_code').val(code);
            
            if(active == 1) {
                $('#editCheck1').prop('checked', true);
            } else {
                $('#editCheck1').prop('checked', false);
            }
        });
    });
</script>
@endsection
