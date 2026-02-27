/**
 * Laboratory Report JavaScript Module
 */

(function($) {
    'use strict';

    $(function() {
        if (typeof Chart !== 'undefined' && window.LabReportConfig) {
            initializeCharts();
        }
    });

    /**
     * Chart Initializations
     */
    function initializeCharts() {
        initializeStatusChart();
        initializeDailyChart();
    }

    /**
     * Status Distribution Chart (Doughnut)
     */
    function initializeStatusChart() {
        const ctx = document.getElementById('statusChart');
        if (!ctx) return;

        const config = window.LabReportConfig.statusData;
        new Chart(ctx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: ['Beklemede', 'Ön Onaylı', 'Kontrol Tekrarı', 'Sevk Onaylı', 'Reddedildi'],
                datasets: [{
                    data: [
                        config.waiting,
                        config.accepted,
                        config.control_repeat,
                        config.shipment_approved,
                        config.rejected
                    ],
                    backgroundColor: ['#ffc107', '#28a745', '#17a2b8', '#007bff', '#dc3545'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            font: { size: 12 }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * Daily Activity Chart (Line)
     */
    function initializeDailyChart() {
        const ctx = document.getElementById('dailyChart');
        if (!ctx) return;

        const config = window.LabReportConfig.dailyData;
        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: config.labels,
                datasets: [{
                    label: 'Günlük İşlem Sayısı',
                    data: config.counts,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    }

})(jQuery);
