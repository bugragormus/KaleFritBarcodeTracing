@extends('layouts.granilya')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('granilya.dashboard') }}"><i class="fas fa-home"></i> Ana Sayfa</a></li>
    <li class="breadcrumb-item active">Kırıcı Makinalar</li>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-sm-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title mb-0 font-size-18">Kırıcı Makinalar</h4>
                <div>
                    <button type="button" class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#createModal">
                        <i class="fas fa-plus mr-1"></i> Yeni Kırıcı Makina Ekle
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
                            @foreach($crushers as $crusher)
                                <tr>
                                    <td>{{ $crusher->code ?? '-' }}</td>
                                    <td>{{ $crusher->name }}</td>
                                    <td>
                                        @if($crusher->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-danger">Pasif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('granilya.kirici.edit', $crusher->id) }}" class="btn btn-sm btn-info waves-effect waves-light">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('granilya.kirici.destroy', $crusher->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Silmek istediğinize emin misiniz?');">
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

            $('#editForm').attr('action', '/granilya/kirici/' + id);
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
