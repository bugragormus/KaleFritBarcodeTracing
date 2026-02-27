<?php

$file = __DIR__ . '/app/Http/Controllers/DashboardController.php';
$content = file_get_contents($file);

// 1. Fix getKilnPerformance
$searchKiln = <<<EOT
    private function getKilnPerformance(\$date, \$period = 'daily', \$periodInfo = null)
    {
        \$startDate = \$date->copy()->startOfDay();
        \$endDate = \$date->copy()->endOfDay();
EOT;
$replaceKiln = <<<EOT
    private function getKilnPerformance(\$date, \$period = 'daily', \$periodInfo = null)
    {
        if (\$periodInfo) {
            \$startDate = \$periodInfo['start_date'];
            \$endDate = \$periodInfo['end_date'];
        } else {
            \$startDate = \$date->copy()->startOfDay();
            \$endDate = \$date->copy()->endOfDay();
        }
EOT;
$content = str_replace($searchKiln, $replaceKiln, $content);

// 2. Fix getKilnRejectionRates
$searchKilnRej = <<<EOT
    private function getKilnRejectionRates(\$date, \$period = 'daily', \$periodInfo = null)
    {
        \$startDate = \$date->copy()->startOfDay();
        \$endDate = \$date->copy()->endOfDay();
EOT;
$content = str_replace($searchKilnRej, $replaceKiln, $content);

// 3. Fix getProductKilnAnalysis
$searchProdKiln = <<<EOT
    private function getProductKilnAnalysis(\$date, \$period = 'daily', \$periodInfo = null)
    {
        \$startDate = \$date->copy()->startOfDay();
        \$endDate = \$date->copy()->endOfDay();
EOT;
$content = str_replace($searchProdKiln, $replaceKiln, $content);

// 4. Fix getRejectionReasonsAnalysis
$searchRejReason = <<<EOT
    private function getRejectionReasonsAnalysis(\$date, \$period = 'daily', \$periodInfo = null)
    {
        \$startDate = \$date->copy()->startOfDay();
        \$endDate = \$date->copy()->endOfDay();
EOT;
$content = str_replace($searchRejReason, $replaceKiln, $content);

// 5. Update Statuses in getKilnPerformance
$searchKilnStatus = <<<EOT
            Barcode::STATUS_SHIPMENT_APPROVED, // Kabul edildi: sevk onaylı
            Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT, // Test süreci: beklemede, ön onaylı, kontrol tekrarı
            Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Teslimat süreci: müşteri transfer, teslim edildi
            Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED, // Red: reddedildi ve birleştirildi
            Barcode::STATUS_SHIPMENT_APPROVED, // Kabul edildi: sevk onaylı (count için)
            Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT, // Test süreci count için
            Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Teslimat süreci count için
            Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED, // Red count için: reddedildi ve birleştirildi
EOT;
$replaceKilnStatus = <<<EOT
            Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED, Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Kabul edildi
            Barcode::STATUS_WAITING, Barcode::STATUS_CONTROL_REPEAT, // Test süreci: beklemede, kontrol tekrarı
            Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Teslimat süreci: müşteri transfer, teslim edildi
            Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED, // Red: reddedildi ve birleştirildi
            Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED, Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Kabul edildi (count için)
            Barcode::STATUS_WAITING, Barcode::STATUS_CONTROL_REPEAT, // Test süreci count için
            Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Teslimat süreci count için
            Barcode::STATUS_REJECTED, Barcode::STATUS_MERGED, // Red count için
EOT;
$content = preg_replace('/Barcode::STATUS_SHIPMENT_APPROVED,[ \t]*\/\/[ \t]*Kabul edildi.*?\n[ \t]*Barcode::STATUS_REJECTED,[ \t]*Barcode::STATUS_MERGED,[ \t]*\/\/[ \t]*Red count için.*?$/m', $replaceKilnStatus, $content, 1);

// We need a safer replace for getKilnPerformance query statuses
$content = str_replace(
    "Barcode::STATUS_SHIPMENT_APPROVED, // Kabul edildi: sevk onaylı", 
    "Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED, Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Kabul edildi", 
$content);

$content = str_replace(
    "Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT,", 
    "Barcode::STATUS_WAITING, Barcode::STATUS_CONTROL_REPEAT,", 
$content);

$content = str_replace(
    "Barcode::STATUS_SHIPMENT_APPROVED, // Kabul edildi: sevk onaylı (count için)", 
    "Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED, Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Kabul edildi (count için)", 
$content);

$content = str_replace(
    "Barcode::STATUS_SHIPMENT_APPROVED, // Kabul edildi: sevk onaylı (acceptance rate için)", 
    "Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED, Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED, // Kabul edildi (acceptance rate için)", 
$content);


// 6. Update Shift Report
$searchShiftReport = <<<EOT
    private function getShiftReport(\$date)
    {
        \$shifts = [
            'gece' => ['start' => '00:00', 'end' => '08:00'],
            'gündüz' => ['start' => '08:00', 'end' => '16:00'],
            'akşam' => ['start' => '16:00', 'end' => '24:00']
        ];
        
        \$shiftData = [];
        
        foreach (\$shifts as \$shiftName => \$shiftTime) {
            \$startTime = \$date->copy()->setTimeFromTimeString(\$shiftTime['start']);
            
            // Vardiya bitiş zamanını hesapla
            if (\$shiftTime['end'] === '24:00') {
                \$endTime = \$date->copy()->addDay()->setTimeFromTimeString('00:00');
            } else {
                \$endTime = \$date->copy()->setTimeFromTimeString(\$shiftTime['end']);
            }
EOT;

$replaceShiftReport = <<<EOT
    private function getShiftReport(\$date, \$periodInfo = null)
    {
        if (\$periodInfo) {
            \$startDate = clone \$periodInfo['start_date'];
            \$endDate = clone \$periodInfo['end_date'];
        } else {
            \$startDate = \$date->copy()->startOfDay();
            \$endDate = \$date->copy()->endOfDay();
        }

        \$shifts = [
            'gece' => ['start' => '00:00:00', 'end' => '08:00:00'],
            'gündüz' => ['start' => '08:00:01', 'end' => '16:00:00'],
            'akşam' => ['start' => '16:00:01', 'end' => '23:59:59']
        ];
        
        \$shiftData = [];
        
        foreach (\$shifts as \$shiftName => \$shiftTime) {
            \$shiftStart = \$shiftTime['start'];
            \$shiftEnd = \$shiftTime['end'];
            
            // Debug için log ekle
            \Log::info("Vardiya: {\$shiftName}", [
                'start_date' => \$startDate->format('Y-m-d'),
                'end_date' => \$endDate->format('Y-m-d')
            ]);
EOT;

$content = str_replace($searchShiftReport, $replaceShiftReport, $content);
$content = str_replace(
    "['start_time' => \$startTime->format('Y-m-d H:i:s'), 'end_time' => \$endTime->format('Y-m-d H:i:s')]", 
    "['time_start' => \$shiftStart, 'time_end' => \$shiftEnd]", 
$content);

// Shift specific queries update:
$content = preg_replace(
    '/\$startTime, \$endTime/m', 
    '\$startDate, \$endDate, \$shiftStart, \$shiftEnd', 
$content);

$content = preg_replace(
    '/WHERE barcodes.created_at BETWEEN \? AND \?/m', 
    'WHERE barcodes.created_at BETWEEN ? AND ? AND TIME(barcodes.created_at) BETWEEN ? AND ?', 
$content);

$content = preg_replace(
    '/Barcode::whereBetween\(\'created_at\', \[\$startDate, \$endDate, \$shiftStart, \$shiftEnd\]\)/m', 
    'Barcode::whereBetween(\'created_at\', [$startDate, $endDate])->whereTime(\'created_at\', \'>=\', $shiftStart)->whereTime(\'created_at\', \'<=\', $shiftEnd)', 
$content);

// 6b. Fix dashboard index call
$content = str_replace(
    "\$shiftReport = \$period === 'daily' ? \$this->getShiftReport(\$date) : [];", 
    "\$shiftReport = \$this->getShiftReport(\$date, \$periodInfo);", 
$content);
$content = str_replace(
    "'shiftReport' => \$period === 'daily' ? \$this->getShiftReport(\$date) : [],", 
    "'shiftReport' => \$this->getShiftReport(\$date, \$periodInfo),", 
$content);


// 7. Update getProductionData
$content = str_replace(
    "\$acceptedQuantity = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.status = ?
            AND barcodes.deleted_at IS NULL
        ', [\$startDate, \$endDate, Barcode::STATUS_SHIPMENT_APPROVED])[0]->total_quantity ?? 0;", 
    "\$acceptedQuantity = DB::select('
            SELECT COALESCE(SUM(quantities.quantity), 0) as total_quantity
            FROM barcodes
            LEFT JOIN quantities ON quantities.id = barcodes.quantity_id
            WHERE barcodes.created_at BETWEEN ? AND ?
            AND barcodes.status IN (?, ?, ?, ?)
            AND barcodes.deleted_at IS NULL
        ', [\$startDate, \$endDate, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED, Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])[0]->total_quantity ?? 0;", 
$content);

$content = str_replace(
    "AND barcodes.status IN (?, ?, ?)
            AND barcodes.deleted_at IS NULL
        ', [\$startDate, \$endDate, Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT])", 
    "AND barcodes.status IN (?, ?)
            AND barcodes.deleted_at IS NULL
        ', [\$startDate, \$endDate, Barcode::STATUS_WAITING, Barcode::STATUS_CONTROL_REPEAT])", 
$content);

$content = str_replace(
    "AND barcodes.status = ?
                AND barcodes.deleted_at IS NULL
            ', [\$startDate, \$endDate, \$shiftStart, \$shiftEnd, Barcode::STATUS_SHIPMENT_APPROVED])", 
    "AND barcodes.status IN (?, ?, ?, ?)
                AND barcodes.deleted_at IS NULL
            ', [\$startDate, \$endDate, \$shiftStart, \$shiftEnd, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED, Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])", 
$content);

$content = str_replace(
    "AND barcodes.status IN (?, ?, ?)
                AND barcodes.deleted_at IS NULL
            ', [\$startDate, \$endDate, \$shiftStart, \$shiftEnd, Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT])", 
    "AND barcodes.status IN (?, ?)
                AND barcodes.deleted_at IS NULL
            ', [\$startDate, \$endDate, \$shiftStart, \$shiftEnd, Barcode::STATUS_WAITING, Barcode::STATUS_CONTROL_REPEAT])", 
$content);

$content = str_replace(
    "->where('status', Barcode::STATUS_SHIPMENT_APPROVED)",
    "->whereIn('status', [Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_SHIPMENT_APPROVED, Barcode::STATUS_CUSTOMER_TRANSFER, Barcode::STATUS_DELIVERED])",
$content);

$content = str_replace(
    "->whereIn('status', [Barcode::STATUS_WAITING, Barcode::STATUS_PRE_APPROVED, Barcode::STATUS_CONTROL_REPEAT])",
    "->whereIn('status', [Barcode::STATUS_WAITING, Barcode::STATUS_CONTROL_REPEAT])",
$content);

// 8. Fix Rejection Reasons `lab_at`
$content = str_replace(
    "->whereBetween('lab_at', [\$startDate, \$endDate])",
    "->whereBetween('created_at', [\$startDate, \$endDate])",
$content);


// 9. Fix Stock Age Analysis
// Instead of modifying the raw query extensively, we'll alter how the result is returned to aggregate it.
$searchStockAge = <<<EOT
        // Ürün bazında analiz
        \$productAnalysis = [];
        foreach (\$stockAgeData as \$stock) {
            \$productKey = \$stock->stock_name . ' (' . \$stock->stock_code . ')';
            if (!isset(\$productAnalysis[\$productKey])) {
EOT;

$replaceStockAge = <<<EOT
        // Benzersiz Barkod Grubu Analizi (Stok bazında özetleme, aynı barkodu tekrar saymayı önlemek için)
        \$uniqueStockAgeData = collect(\$stockAgeData)->unique('id')->values();

        // Ürün bazında analiz
        \$productAnalysis = [];
        foreach (\$uniqueStockAgeData as \$stock) {
            \$productKey = \$stock->stock_name . ' (' . \$stock->stock_code . ')';
            if (!isset(\$productAnalysis[\$productKey])) {
EOT;

$content = str_replace($searchStockAge, $replaceStockAge, $content);
$content = str_replace(
    "foreach (\$stockAgeData as \$stock) {",
    "foreach (\$uniqueStockAgeData as \$stock) {",
$content);
// But wait, the first loop "foreach ($stockAgeData as $stock) {" is for age categories. I'll replace all.
$content = str_replace(
    "        foreach (\$stockAgeData as \$stock) {\n            \$summary['total_barcodes']++;",
    "        \$uniqueStockAgeData = collect(\$stockAgeData)->unique('id')->values();\n\n        foreach (\$uniqueStockAgeData as \$stock) {\n            \$summary['total_barcodes']++;",
$content);
$content = str_replace(
    "\$oldestBarcodes = array_slice(\$stockAgeData, 0, 20);",
    "\$oldestBarcodes = \$uniqueStockAgeData->take(20)->values()->all();",
$content);
$content = str_replace(
    "foreach (\$stockAgeData as \$stock) {\n            \$status = \$stock->status;",
    "foreach (\$uniqueStockAgeData as \$stock) {\n            \$status = \$stock->status;",
$content);
$content = str_replace(
    "foreach (\$stockAgeData as \$stock) {\n            \$productKey = \$stock->stock_name",
    "foreach (\$uniqueStockAgeData as \$stock) {\n            \$productKey = \$stock->stock_name",
$content);

// 10. Pass $periodInfo to AI insights and OEE
$content = str_replace(
    "\$aiInsights = \$this->generateAIInsights(Carbon::today('Europe/Istanbul'));",
    "\$aiInsights = \$this->generateAIInsights(\$date, \$periodInfo);",
$content);

$content = preg_replace(
    '/private function generateAIInsights\(\$date\)/',
    'private function generateAIInsights($date, $periodInfo = null)',
$content);

$content = preg_replace(
    '/private function calculateProductionEfficiency\(\$date\)/',
    'private function calculateProductionEfficiency($date, $periodInfo = null)',
$content);

// Update OEE dates inside calculateProductionEfficiency
$searchOEE = <<<EOT
    private function calculateProductionEfficiency(\$date, \$periodInfo = null)
    {
        // Get last 30 days of production data
        \$startDate = \$date->copy()->subDays(30)->startOfDay();
        \$endDate = \$date->copy()->endOfDay();
EOT;
$replaceOEE = <<<EOT
    private function calculateProductionEfficiency(\$date, \$periodInfo = null)
    {
        if (\$periodInfo) {
            \$startDate = \$periodInfo['start_date'];
            \$endDate = \$periodInfo['end_date'];
        } else {
            \$startDate = \$date->copy()->subDays(30)->startOfDay();
            \$endDate = \$date->copy()->endOfDay();
        }
EOT;
$content = str_replace($searchOEE, $replaceOEE, $content);

file_put_contents($file, $content);
echo "DashboardController refactored!\n";

