<?php

$file = '/Applications/XAMPP/xamppfiles/htdocs/kalefrit/app/Http/Controllers/LaboratoryController.php';
$content = file_get_contents($file);

$search = <<<EOT
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
EOT;

$replace = <<<EOT
            \$acceptedStatuses = [
                \App\Models\Barcode::STATUS_PRE_APPROVED,
                \App\Models\Barcode::STATUS_SHIPMENT_APPROVED,
                \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER,
                \App\Models\Barcode::STATUS_DELIVERED
            ];

            \$totalBarcodes = \$stock->barcodes->count();
            \$acceptedBarcodes = \$stock->barcodes->whereIn('status', \$acceptedStatuses)->count();
            \$rejectedBarcodes = \$stock->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->count();
            \$controlRepeatBarcodes = \$stock->barcodes->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT)->count();
            
            \$totalKg = \$stock->barcodes->sum('quantity.quantity');
            \$acceptedKg = \$stock->barcodes->whereIn('status', \$acceptedStatuses)->sum('quantity.quantity');
EOT;

$content = str_replace($search, $replace, $content);

$searchKiln = <<<EOT
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
EOT;

$replaceKiln = <<<EOT
            \$acceptedStatuses = [
                \App\Models\Barcode::STATUS_PRE_APPROVED,
                \App\Models\Barcode::STATUS_SHIPMENT_APPROVED,
                \App\Models\Barcode::STATUS_CUSTOMER_TRANSFER,
                \App\Models\Barcode::STATUS_DELIVERED
            ];

            \$totalBarcodes = \$kiln->barcodes->count();
            \$acceptedBarcodes = \$kiln->barcodes->whereIn('status', \$acceptedStatuses)->count();
            \$rejectedBarcodes = \$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_REJECTED)->count();
            \$controlRepeatBarcodes = \$kiln->barcodes->where('status', \App\Models\Barcode::STATUS_CONTROL_REPEAT)->count();
            
            \$totalKg = \$kiln->barcodes->sum('quantity.quantity');
            \$acceptedKg = \$kiln->barcodes->whereIn('status', \$acceptedStatuses)->sum('quantity.quantity');
EOT;

$content = str_replace($searchKiln, $replaceKiln, $content);

file_put_contents($file, $content);
echo "Statuses refactored!\n";
