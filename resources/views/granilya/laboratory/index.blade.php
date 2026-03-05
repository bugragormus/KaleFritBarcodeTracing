@extends('layouts.granilya')

@section('styles')
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <style>
        .modern-lab-card {
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid #e9ecef;
            overflow: hidden;
            margin-bottom: 2rem;
            background: #fff;
        }
        .stat-card {
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: transform 0.3s;
            color: white;
            margin-bottom: 1rem;
        }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-waiting { background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); }
        .stat-pre-approved { background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); }
        .stat-approved { background: linear-gradient(135deg, #28a745 0%, #218838 100%); }
        
        .test-badge {
            cursor: pointer;
            padding: 0.5rem 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.8rem;
            display: inline-block;
            transition: all 0.2s;
            border: 1px solid transparent;
        }
        .test-badge:hover { filter: brightness(0.9); transform: scale(1.05); }
        .test-bekliyor { background: #f8f9fa; color: #6c757d; border-color: #dee2e6; }
        .test-onay { background: #d4edda; color: #155724; border-color: #c3e6cb; }
        .test-red { background: #f8d7da; color: #721c24; border-color: #f5c6cb; }
        
        .bulk-actions-panel {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1rem;
            display: none;
            border: 1px dashed #667eea;
        }
    </style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="font-weight-bold"><i class="fas fa-flask mr-2 text-primary"></i> Granilya Laboratuvar Paneli</h2>
            <p class="text-muted">Palet kalite kontrol ve test onay işlemleri</p>
        </div>
        <div class="col-md-6 text-right">
             <span class="badge badge-light p-2 shadow-sm">
                <i class="far fa-clock mr-1"></i> {{ now()->format('d.m.Y H:i') }}
            </span>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card stat-waiting shadow-sm">
                <div class="h4 mb-1">{{ $productions->where('status', \App\Models\GranilyaProduction::STATUS_WAITING)->count() }}</div>
                <div class="small font-weight-bold">Test Bekleyen</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card stat-pre-approved shadow-sm">
                <div class="h4 mb-1">{{ $productions->where('status', \App\Models\GranilyaProduction::STATUS_PRE_APPROVED)->count() }}</div>
                <div class="small font-weight-bold">Ön Onaylı (Arge Bekleyen)</div>
            </div>
        </div>
        <div class="col-md-4">
             <div class="stat-card stat-approved shadow-sm" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                <div class="h4 mb-1">{{ $productions->count() }}</div>
                <div class="small font-weight-bold">Toplam İşlemdeki Palet</div>
            </div>
        </div>
    </div>

    <!-- Toplu İşlem Paneli -->
    <div id="bulkActionsPanel" class="bulk-actions-panel animated fadeIn">
        <div class="row align-items-center">
            <div class="col-md-5">
                <strong class="text-primary"><i class="fas fa-check-double mr-2"></i> <span id="selectedCount">0</span> Palet Seçildi</strong>
            </div>
            <div class="col-md-7 text-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-success" onclick="openBulkModal('sieve')"><i class="fas fa-microscope mr-1"></i> Elek Onayla</button>
                    <button type="button" class="btn btn-sm btn-info" onclick="openBulkModal('surface')"><i class="fas fa-eye mr-1"></i> Yüzey Onayla</button>
                    <button type="button" class="btn btn-sm btn-primary" onclick="openBulkModal('arge')"><i class="fas fa-flask mr-1"></i> Arge Onayla</button>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="clearSelection()">Vazgeç</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main List -->
    <div class="modern-lab-card">
        <div class="p-4">
            <div class="table-responsive">
                <table id="labTable" class="table table-hover w-100">
                    <thead>
                        <tr>
                            <th width="30"><input type="checkbox" id="selectAll"></th>
                            <th>Palet No</th>
                            <th>Frit / Şarj</th>
                            <th>Miktar</th>
                            <th class="text-center">Elek Testi</th>
                            <th class="text-center">Yüzey Testi</th>
                            <th class="text-center">Arge Testi</th>
                            <th>Genel Durum</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productions as $p)
                        <tr data-id="{{ $p->id }}">
                            <td><input type="checkbox" class="pallet-checkbox" value="{{ $p->id }}"></td>
                            <td><strong>{{ $p->pallet_number }}</strong></td>
                            <td>
                                {{ $p->stock->code }}<br>
                                <small class="text-muted">Şarj: {{ $p->load_number }}</small>
                            </td>
                            <td>{{ $p->used_quantity }} KG</td>
                            <td class="text-center">
                                <span class="test-badge test-{{ strtolower($p->sieve_test_result) }}" 
                                      onclick="openTestModal({{ $p->id }}, 'sieve', '{{ $p->sieve_test_result }}')">
                                    {{ $p->sieve_test_result }}
                                    @if($p->sieve_reject_reason) <br><small>({{ $p->sieve_reject_reason }})</small> @endif
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="test-badge test-{{ strtolower($p->surface_test_result) }}"
                                      onclick="openTestModal({{ $p->id }}, 'surface', '{{ $p->surface_test_result }}')">
                                    {{ $p->surface_test_result }}
                                    @if($p->surface_reject_reason) <br><small>({{ $p->surface_reject_reason }})</small> @endif
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="test-badge test-{{ strtolower($p->arge_test_result) }}"
                                      onclick="openTestModal({{ $p->id }}, 'arge', '{{ $p->arge_test_result }}')">
                                    {{ $p->arge_test_result }}
                                </span>
                            </td>
                            <td>{!! $p->status_badge !!}</td>
                            <td>
                                <a href="{{ route('granilya.production.show', $p->pallet_number) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-search"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Test Update Modal -->
<div class="modal fade" id="testModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title font-weight-bold" id="testModalTitle">Test İşlemi</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="testForm">
                @csrf
                <input type="hidden" name="pallet_id" id="modalPalletId">
                <input type="hidden" name="test_type" id="modalTestType">
                <div class="modal-body p-4">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold">Test Sonucu <span class="text-danger">*</span></label>
                        <div class="d-flex gap-3 mt-2">
                            <div class="custom-control custom-radio mr-4">
                                <input type="radio" id="resOnay" name="result" value="Onay" class="custom-control-input" required>
                                <label class="custom-control-label text-success font-weight-bold" for="resOnay">ONAYLA</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input type="radio" id="resRed" name="result" value="Red" class="custom-control-input" required>
                                <label class="custom-control-label text-danger font-weight-bold" for="resRed">REDDET</label>
                            </div>
                        </div>
                    </div>

                    <div id="rejectReasonSection" style="display: none;">
                        <div class="form-group">
                            <label class="font-weight-bold">Red Sebebi <span class="text-danger">*</span></label>
                            <select name="reject_reason" id="rejectReasonSelect" class="form-control">
                                <!-- Options populated by JS -->
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kapat</button>
                    <button type="submit" class="btn btn-primary px-4 shadow">Kaydet</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Approval Modal -->
<div class="modal fade" id="bulkModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header bg-success text-white" style="border-radius: 15px 15px 0 0;">
                <h5 class="modal-title font-weight-bold">Toplu Onay İşlemi</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4 text-center">
                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                <h5 class="mb-3">Seçili <span id="bulkModalCount" class="font-weight-bold">0</span> palet için <span id="bulkModalTestName" class="font-weight-bold"></span> testi onaylanacaktır.</h5>
                <p class="text-muted">Bu işlem seçilen tüm paletlerin ilgili testini "Onay" durumuna getirecektir. Devam etmek istiyor musunuz?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Vazgeç</button>
                <button type="button" class="btn btn-success px-4 shadow" id="btnBulkConfirm">Evet, Onayla</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        let selectedPallets = [];
        let rejectReasons = {
            'sieve': ['Dirilik', 'Tozama'],
            'surface': ['Renk', 'Parlaklık'],
            'arge': []
        };

        $(document).ready(function() {
            $('#labTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Turkish.json"
                },
                "order": [[1, "asc"]],
                "pageLength": 25
            });

            // Checkbox logic
            $('#selectAll').on('change', function() {
                $('.pallet-checkbox').prop('checked', $(this).prop('checked')).trigger('change');
            });

            $(document).on('change', '.pallet-checkbox', function() {
                updateBulkPanel();
            });

            // Reject reason visibility logic
            $('input[name="result"]').on('change', function() {
                if ($(this).val() === 'Red') {
                    $('#rejectReasonSection').slideDown();
                } else {
                    $('#rejectReasonSection').slideUp();
                }
            });

            // Single Save
            $('#testForm').on('submit', function(e) {
                e.preventDefault();
                let id = $('#modalPalletId').val();
                let data = $(this).serialize();

                $.post('{{ url("/granilya/laboratuvar/test") }}/' + id, data)
                .done(function(res) {
                    $('#testModal').modal('hide');
                    Swal.fire('Başarılı', res.message, 'success').then(() => {
                        location.reload();
                    });
                })
                .fail(function(err) {
                    Swal.fire('Hata', 'Bir sorun oluştu!', 'error');
                });
            });

            // Bulk Save
            $('#btnBulkConfirm').on('click', function() {
                let testType = currentBulkTest;
                $.post('{{ route("granilya.laboratory.bulk") }}', {
                    _token: '{{ csrf_token() }}',
                    pallets: selectedPallets,
                    test_type: testType
                })
                .done(function(res) {
                    $('#bulkModal').modal('hide');
                    Swal.fire('Başarılı', res.message, 'success').then(() => {
                        location.reload();
                    });
                })
                .fail(function(err) {
                    Swal.fire('Hata', 'İşlem sırasında hata oluştu!', 'error');
                });
            });
        });

        function updateBulkPanel() {
            selectedPallets = [];
            $('.pallet-checkbox:checked').each(function() {
                selectedPallets.push($(this).val());
            });

            if (selectedPallets.length > 0) {
                $('#selectedCount').text(selectedPallets.length);
                $('#bulkActionsPanel').fadeIn();
            } else {
                $('#bulkActionsPanel').fadeOut();
            }
        }

        function clearSelection() {
            $('.pallet-checkbox, #selectAll').prop('checked', false);
            updateBulkPanel();
        }

        function openTestModal(id, type, currentResult) {
            $('#testForm')[0].reset();
            $('#modalPalletId').val(id);
            $('#modalTestType').val(type);
            
            let label = type === 'sieve' ? 'Elek Testi' : (type === 'surface' ? 'Yüzey Testi' : 'Arge Testi');
            $('#testModalTitle').text(label + ' İşlemi');

            // Populate reasons
            let reasons = rejectReasons[type];
            let select = $('#rejectReasonSelect');
            select.empty();
            reasons.forEach(r => select.append(`<option value="${r}">${r}</option>`));

            if (reasons.length === 0) {
                $('#rejectReasonSection').hide();
            }

            if (currentResult === 'Onay') $('#resOnay').prop('checked', true);
            if (currentResult === 'Red') {
                 $('#resRed').prop('checked', true);
                 $('#rejectReasonSection').show();
            }

            $('#testModal').modal('show');
        }

        let currentBulkTest = '';
        function openBulkModal(type) {
            currentBulkTest = type;
            let label = type === 'sieve' ? 'Elek' : (type === 'surface' ? 'Yüzey' : 'Arge');
            $('#bulkModalTestName').text(label);
            $('#bulkModalCount').text(selectedPallets.length);
            $('#bulkModal').modal('show');
        }
    </script>
@endsection
