<?php

$file = __DIR__ . '/app/Http/Controllers/DashboardController.php';
$content = file_get_contents($file);

// Replace duplicate getKilnPerformance methods with their correct names
$content = preg_replace_callback(
    '/\/\*\*\s*\*\s*Fırın başına red oranları\s*\*\/\s*private function getKilnPerformance/s',
    function($matches) {
        return "/**\n     * Fırın başına red oranları\n     */\n    private function getKilnRejectionRates";
    },
    $content
);

$content = preg_replace_callback(
    '/\/\*\*\s*\*\s*Ürün özelinde fırın kapasite analizi\s*\*\/\s*private function getKilnPerformance/s',
    function($matches) {
        return "/**\n     * Ürün özelinde fırın kapasite analizi\n     */\n    private function getProductKilnAnalysis";
    },
    $content
);

$content = preg_replace_callback(
    '/\/\*\*\s*\*\s*Red sebepleri analizi\s*\*\/\s*private function getKilnPerformance/s',
    function($matches) {
        return "/**\n     * Red sebepleri analizi\n     */\n    private function getRejectionReasonsAnalysis";
    },
    $content
);

file_put_contents($file, $content);
echo "Method names fixed!\n";
