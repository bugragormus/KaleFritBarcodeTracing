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
                                <li><strong>Toplam Palet:</strong> {{ number_format($aiInsights['production_efficiency']['total_barcodes'] ?? 0) }}</li>
                                <li><strong>Aktif Palet:</strong> {{ number_format($aiInsights['production_efficiency']['active_barcodes'] ?? 0) }}</li>
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

        <!-- Trend Analysis Section -->
        <div class="card-modern">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-brain"></i>
                        Algoritmik Trend Analizleri - Güncel Veriler
                    </h3>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#trendInfoModal">
                            <i class="fas fa-info-circle"></i> Bilgi
                        </button>
                    </div>
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
                        <div class="ai-insights-card">
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
                        <div class="ai-insights-card">
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
                        <div class="ai-insights-card">
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
                        <div class="ai-insights-card">
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
        
        <!-- Dashboard Export Summary Card -->
        <div class="card-modern export-summary-card">
            <div class="card-header-modern">
                <div class="d-flex justify-content-between align-items-center">
                    <h3 class="card-title-modern">
                        <i class="fas fa-download"></i>
                        Rapor İndirme Merkezi
                    </h3>
                    <div class="d-flex gap-2">
                        @if($period === 'custom')
                            <a href="{{ route('dashboard.export', ['date' => $selectedDate, 'period' => $period, 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                               class="btn btn-warning btn-m" 
                               title="Detaylı Excel Raporu İndir">
                                <i class="fas fa-file-excel"></i>
                                <span class="d-none d-md-inline ms-2">{{ $periodInfo['name'] }} Üretim Raporu</span>
                            </a>
                        @else
                            <a href="{{ route('dashboard.export', ['date' => $selectedDate, 'period' => $period]) }}" 
                               class="btn btn-warning btn-m" 
                               title="Detaylı Excel Raporu İndir">
                                <i class="fas fa-file-excel"></i>
                                <span class="d-none d-md-inline ms-2">{{ $periodInfo['name'] }} Üretim Raporu</span>
                            </a>
                        @endif
                    </div>
                </div>
                <small class="text-muted mt-2 d-block">
                    <i class="fas fa-info-circle"></i> 
                    Dashboard'daki tüm verileri kapsayan detaylı Excel raporu indirin - {{ $periodInfo['name'] }} periyot
                </small>
            </div>
            <div class="card-body-modern">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-list"></i> Rapor İçeriği</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success"></i> Günlük Üretim Özeti</li>
                            <li><i class="fas fa-check text-success"></i> Vardiya Raporu</li>
                            <li><i class="fas fa-check text-success"></i> Kırıcı Performansı</li>
                            <li><i class="fas fa-check text-success"></i> Red Sebepleri Analizi</li>
                            <li><i class="fas fa-check text-success"></i> Stok Yaşı Analizi</li>
                            <li><i class="fas fa-check text-success"></i> Aylık Karşılaştırma</li>
                            <li><i class="fas fa-check text-success"></i> OEE Analizi</li>
                            <li><i class="fas fa-check text-success"></i> Algoritmik Trend Analizleri</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-info-circle"></i> Rapor Özellikleri</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-star text-warning"></i> 8 ayrı sayfa (sheet)</li>
                            <li><i class="fas fa-star text-warning"></i> 5 farklı periyot seçeneği</li>
                            <li><i class="fas fa-star text-warning"></i> Profesyonel formatlama</li>
                            <li><i class="fas fa-star text-warning"></i> Otomatik sütun genişliği</li>
                            <li><i class="fas fa-star text-warning"></i> Renkli başlıklar</li>
                            <li><i class="fas fa-star text-warning"></i> Türkçe karakter desteği</li>
                            <li><i class="fas fa-star text-warning"></i> Anında indirme</li>
                        </ul>
                    </div>
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-lightbulb"></i>
                    <strong>İpucu:</strong> Bu rapor, dashboard'daki tüm verileri Excel formatında içerir. 
                    5 farklı periyot seçeneği ile raporlar oluşturabilirsiniz. 
                    Yönetici raporları, sunumlar veya veri analizi için kullanabilirsiniz.
                </div>
            </div>
        </div>
