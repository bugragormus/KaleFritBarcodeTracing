
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
                            <p class="section-subtitle">{{ count($stockAgeAnalysis['categorized_stock']['critical']) }} palet acil müdahale bekliyor</p>
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
                                        <th>Palet</th>
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
                                            @php
                                                $statusLabel = \App\Models\Barcode::STATUSES[$stock->status] ?? $stock->status;
                                                $statusClassMap = [
                                                    \App\Models\Barcode::STATUS_WAITING => 'waiting',
                                                    \App\Models\Barcode::STATUS_CONTROL_REPEAT => 'control_repeat',
                                                    \App\Models\Barcode::STATUS_PRE_APPROVED => 'pre_approved',
                                                    \App\Models\Barcode::STATUS_SHIPMENT_APPROVED => 'shipment_approved',
                                                    \App\Models\Barcode::STATUS_REJECTED => 'rejected',
                                                    \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER => 'customer_transfer',
                                                    \App\Models\Barcode::STATUS_DELIVERED => 'delivered',
                                                ];
                                                $statusClass = $statusClassMap[$stock->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge stock-age-status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
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
                                    Toplam {{ count($stockAgeAnalysis['categorized_stock']['critical']) }} kritik palet bulundu. Sadece ilk 10 tanesi gösteriliyor.
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
                            <p class="section-subtitle">{{ count($stockAgeAnalysis['categorized_stock']['warning']) }} palet dikkat gerektiriyor</p>
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
                                        <th>Palet</th>
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
                                            @php
                                                $statusLabel = \App\Models\Barcode::STATUSES[$stock->status] ?? $stock->status;
                                                $statusClassMap = [
                                                    \App\Models\Barcode::STATUS_WAITING => 'waiting',
                                                    \App\Models\Barcode::STATUS_CONTROL_REPEAT => 'control_repeat',
                                                    \App\Models\Barcode::STATUS_PRE_APPROVED => 'pre_approved',
                                                    \App\Models\Barcode::STATUS_SHIPMENT_APPROVED => 'shipment_approved',
                                                    \App\Models\Barcode::STATUS_REJECTED => 'rejected',
                                                    \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER => 'customer_transfer',
                                                    \App\Models\Barcode::STATUS_DELIVERED => 'delivered',
                                                ];
                                                $statusClass = $statusClassMap[$stock->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge stock-age-status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
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
                                    Toplam {{ count($stockAgeAnalysis['categorized_stock']['warning']) }} uyarı palet bulundu. Sadece ilk 5 tanesi gösteriliyor.
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
                                                <th>Palet Sayısı</th>
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
                                                <th>Palet</th>
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
                                                <span class="stat-label">Kritik Palet</span>
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
                                                <span class="stat-label">Uyarı Palet</span>
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

