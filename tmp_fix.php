<?php

$file = '/Applications/XAMPP/xamppfiles/htdocs/kalefrit/app/Http/Controllers/LaboratoryController.php';
$content = file_get_contents($file);

// STOCK QUALITY - BARCODE CALCULATION BLOCK
$stockSearch = <<<EOT
            \$totalBarcodes = \$stock->barcodes->count();
            \$acceptedBarcodes = \$stock->barcodes->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)->count();
            \$rejectedBarcodes = \$stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->count();
            \$controlRepeatBarcodes = \$stock->barcodes->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT)->count();
            
            \$totalKg = \$stock->barcodes->sum('quantity.quantity');
            \$acceptedKg = \$stock->barcodes->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)->sum('quantity.quantity');
            \$rejectedKg = \$stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->sum('quantity.quantity');
            
            // Red sebepleri analizi
            \$rejectionReasons = [];
            foreach (\$stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED) as \$barcode) {
                foreach (\$barcode->rejectionReasons as \$reason) {
                    if (!isset(\$rejectionReasons[\$reason->name])) {
                        \$rejectionReasons[\$reason->name] = ['count' => 0, 'kg' => 0];
                    }
                    \$rejectionReasons[\$reason->name]['count']++;
                    \$rejectionReasons[\$reason->name]['kg'] += \$barcode->quantity->quantity ?? 0;
                }
            }
            
            // Kalite oranları
            \$acceptanceRate = \$totalBarcodes > 0 ? (\$acceptedBarcodes / \$totalBarcodes) * 100 : 0;
            \$rejectionRate = \$totalBarcodes > 0 ? (\$rejectedBarcodes / \$totalBarcodes) * 100 : 0;
EOT;

$stockReplace = <<<EOT
            \$nonAcceptedStatuses = [
                \App\Models\Barcode::STATUS_WAITING,
                \App\Models\Barcode::STATUS_CONTROL_REPEAT,
                \App\Models\Barcode::STATUS_REJECTED
            ];

            \$totalBarcodes = \$stock->barcodes->count();
            \$acceptedBarcodes = \$stock->barcodes->whereNotIn('status', \$nonAcceptedStatuses)->count();
            \$rejectedBarcodes = \$stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->count();
            \$controlRepeatBarcodes = \$stock->barcodes->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT)->count();
            
            \$totalKg = \$stock->barcodes->sum('quantity.quantity');
            \$acceptedKg = \$stock->barcodes->whereNotIn('status', \$nonAcceptedStatuses)->sum('quantity.quantity');
            \$rejectedKg = \$stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->sum('quantity.quantity');
            \$controlRepeatKg = \$stock->barcodes->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT)->sum('quantity.quantity');
            
            // Red sebepleri analizi (virgülle ayırarak tek kalem yap)
            \$rejectionReasons = [];
            foreach (\$stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED) as \$barcode) {
                \$reasonNames = collect(\$barcode->rejectionReasons)->pluck('name')->implode(', ');
                if (empty(\$reasonNames)) {
                    \$reasonNames = 'Belirtilmemiş';
                }
                
                if (!isset(\$rejectionReasons[\$reasonNames])) {
                    \$rejectionReasons[\$reasonNames] = ['count' => 0, 'kg' => 0];
                }
                \$rejectionReasons[\$reasonNames]['count']++;
                \$rejectionReasons[\$reasonNames]['kg'] += \$barcode->quantity->quantity ?? 0;
            }
            
            // Kalite oranları (KG üzerinden)
            \$acceptanceRate = \$totalKg > 0 ? (\$acceptedKg / \$totalKg) * 100 : 0;
            \$rejectionRate = \$totalKg > 0 ? (\$rejectedKg / \$totalKg) * 100 : 0;
EOT;

$content = str_replace($stockSearch, $stockReplace, $content);

// Ensure control_repeat_kg is mapped
$content = str_replace(
    "'rejected_kg' => \$rejectedKg,",
    "'rejected_kg' => \$rejectedKg,\n                'control_repeat_kg' => \$controlRepeatKg,",
    $content
);

// KILN PERFORMANCE - BARCODE CALCULATION BLOCK
$kilnSearch = <<<EOT
            \$totalBarcodes = \$kiln->barcodes->count();
            \$acceptedBarcodes = \$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)->count();
            \$rejectedBarcodes = \$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->count();
            \$controlRepeatBarcodes = \$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT)->count();
            
            \$totalKg = \$kiln->barcodes->sum('quantity.quantity');
            \$acceptedKg = \$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_PRE_APPROVED)->sum('quantity.quantity');
            \$rejectedKg = \$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->sum('quantity.quantity');
            
            // Red sebepleri analizi
            \$rejectionReasons = [];
            foreach (\$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED) as \$barcode) {
                foreach (\$barcode->rejectionReasons as \$reason) {
                    if (!isset(\$rejectionReasons[\$reason->name])) {
                        \$rejectionReasons[\$reason->name] = ['count' => 0, 'kg' => 0];
                    }
                    \$rejectionReasons[\$reason->name]['count']++;
                    \$rejectionReasons[\$reason->name]['kg'] += \$barcode->quantity->quantity ?? 0;
                }
            }
            
            // Performans oranları
            \$acceptanceRate = \$totalBarcodes > 0 ? (\$acceptedBarcodes / \$totalBarcodes) * 100 : 0;
            \$rejectionRate = \$totalBarcodes > 0 ? (\$rejectedBarcodes / \$totalBarcodes) * 100 : 0;
            \$efficiencyRate = \$totalBarcodes > 0 ? ((\$acceptedBarcodes + \$controlRepeatBarcodes) / \$totalBarcodes) * 100 : 0;
EOT;

$kilnReplace = <<<EOT
            \$nonAcceptedStatuses = [
                \App\Models\Barcode::STATUS_WAITING,
                \App\Models\Barcode::STATUS_CONTROL_REPEAT,
                \App\Models\Barcode::STATUS_REJECTED
            ];

            \$totalBarcodes = \$kiln->barcodes->count();
            \$acceptedBarcodes = \$kiln->barcodes->whereNotIn('status', \$nonAcceptedStatuses)->count();
            \$rejectedBarcodes = \$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->count();
            \$controlRepeatBarcodes = \$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT)->count();
            
            \$totalKg = \$kiln->barcodes->sum('quantity.quantity');
            \$acceptedKg = \$kiln->barcodes->whereNotIn('status', \$nonAcceptedStatuses)->sum('quantity.quantity');
            \$rejectedKg = \$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->sum('quantity.quantity');
            \$controlRepeatKg = \$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT)->sum('quantity.quantity');
            
            // Red sebepleri analizi (virgülle ayırarak tek kalem yap)
            \$rejectionReasons = [];
            foreach (\$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED) as \$barcode) {
                \$reasonNames = collect(\$barcode->rejectionReasons)->pluck('name')->implode(', ');
                if (empty(\$reasonNames)) {
                    \$reasonNames = 'Belirtilmemiş';
                }
                
                if (!isset(\$rejectionReasons[\$reasonNames])) {
                    \$rejectionReasons[\$reasonNames] = ['count' => 0, 'kg' => 0];
                }
                \$rejectionReasons[\$reasonNames]['count']++;
                \$rejectionReasons[\$reasonNames]['kg'] += \$barcode->quantity->quantity ?? 0;
            }
            
            // Performans oranları (KG üzerinden hesaplanacak)
            \$acceptanceRate = \$totalKg > 0 ? (\$acceptedKg / \$totalKg) * 100 : 0;
            \$rejectionRate = \$totalKg > 0 ? (\$rejectedKg / \$totalKg) * 100 : 0;
            \$efficiencyRate = \$totalKg > 0 ? ((\$acceptedKg + \$controlRepeatKg) / \$totalKg) * 100 : 0;
EOT;

$content = str_replace($kilnSearch, $kilnReplace, $content);

// overallStats adjustments for Stock
$overallStockSearch = <<<EOT
            'overall_acceptance_rate' => \$stockQualityData->sum('total_barcodes') > 0 ? 
                (\$stockQualityData->sum('accepted_barcodes') / \$stockQualityData->sum('total_barcodes')) * 100 : 0,
            'overall_rejection_rate' => \$stockQualityData->sum('total_barcodes') > 0 ? 
                (\$stockQualityData->sum('rejected_barcodes') / \$stockQualityData->sum('total_barcodes')) * 100 : 0
EOT;

$overallStockReplace = <<<EOT
            'overall_acceptance_rate' => \$stockQualityData->sum('total_kg') > 0 ? 
                (\$stockQualityData->sum('accepted_kg') / \$stockQualityData->sum('total_kg')) * 100 : 0,
            'overall_rejection_rate' => \$stockQualityData->sum('total_kg') > 0 ? 
                (\$stockQualityData->sum('rejected_kg') / \$stockQualityData->sum('total_kg')) * 100 : 0
EOT;

$content = str_replace($overallStockSearch, $overallStockReplace, $content);

// overallStats adjustments for Kiln
$overallKilnSearch = <<<EOT
            'overall_acceptance_rate' => \$kilnPerformanceData->sum('total_barcodes') > 0 ? 
                (\$kilnPerformanceData->sum('accepted_barcodes') / \$kilnPerformanceData->sum('total_barcodes')) * 100 : 0,
            'overall_rejection_rate' => \$kilnPerformanceData->sum('total_barcodes') > 0 ? 
                (\$kilnPerformanceData->sum('rejected_barcodes') / \$kilnPerformanceData->sum('total_barcodes')) * 100 : 0,
            'overall_efficiency_rate' => \$kilnPerformanceData->sum('total_barcodes') > 0 ? 
                ((\$kilnPerformanceData->sum('accepted_barcodes') + \$kilnPerformanceData->sum('control_repeat_barcodes')) / \$kilnPerformanceData->sum('total_barcodes')) * 100 : 0
EOT;

$overallKilnReplace = <<<EOT
            'overall_acceptance_rate' => \$kilnPerformanceData->sum('total_kg') > 0 ? 
                (\$kilnPerformanceData->sum('accepted_kg') / \$kilnPerformanceData->sum('total_kg')) * 100 : 0,
            'overall_rejection_rate' => \$kilnPerformanceData->sum('total_kg') > 0 ? 
                (\$kilnPerformanceData->sum('rejected_kg') / \$kilnPerformanceData->sum('total_kg')) * 100 : 0,
            'overall_efficiency_rate' => \$kilnPerformanceData->sum('total_kg') > 0 ? 
                ((\$kilnPerformanceData->sum('accepted_kg') + \$kilnPerformanceData->sum('control_repeat_kg')) / \$kilnPerformanceData->sum('total_kg')) * 100 : 0
EOT;

$content = str_replace($overallKilnSearch, $overallKilnReplace, $content);

file_put_contents($file, $content);
echo "LaboratoryController successfully updated.\n";
