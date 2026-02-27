<?php

$file = __DIR__ . '/app/Http/Controllers/DashboardController.php';
$content = file_get_contents($file);

// Fix getMonthlyComparison
$searchMonthly = <<<EOT
    private function getMonthlyComparison(\$date)
    {
        \$currentMonth = \$date->copy()->startOfMonth();
        \$previousMonth = \$date->copy()->subMonth()->startOfMonth();
        
        \$currentMonthData = \$this->getMonthData(\$currentMonth);
        \$previousMonthData = \$this->getMonthData(\$previousMonth);
EOT;

$replaceMonthly = <<<EOT
    private function getMonthlyComparison(\$date)
    {
        \$currentMonthStart = \$date->copy()->startOfMonth();
        \$currentMonthEnd = \$date->copy()->endOfDay();
        
        \$previousMonthStart = \$date->copy()->subMonth()->startOfMonth();
        // Geçen ayın aynı gününe kadar olan veriyi al (Adil karşılaştırma için)
        // Eğer geçen ay o gün yoksa (örn 31 Şubat), ay sonunu alır
        if (\$date->day > \$previousMonthStart->copy()->endOfMonth()->day) {
            \$previousMonthEnd = \$previousMonthStart->copy()->endOfMonth();
        } else {
            \$previousMonthEnd = \$date->copy()->subMonth()->endOfDay();
        }
        
        \$currentMonthData = \$this->getMonthData(\$currentMonthStart, \$currentMonthEnd);
        \$previousMonthData = \$this->getMonthData(\$previousMonthStart, \$previousMonthEnd);
EOT;

$content = str_replace($searchMonthly, $replaceMonthly, $content);

// Modify getMonthData to accept $monthEnd
$searchMonthData = <<<EOT
    private function getMonthData(\$monthStart)
    {
        \$monthEnd = \$monthStart->copy()->endOfMonth();
EOT;

$replaceMonthData = <<<EOT
    private function getMonthData(\$monthStart, \$monthEnd = null)
    {
        if (!\$monthEnd) {
            \$monthEnd = \$monthStart->copy()->endOfMonth();
        }
EOT;

$content = str_replace($searchMonthData, $replaceMonthData, $content);

file_put_contents($file, $content);
echo "Monthly comparison logic fixed!\n";
