<?php
$apiUrl = 'http://fms3.jampzdev.com/api/subject-load-tracker/list';
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

echo "=== RAW API RESPONSE ===\n";
echo json_encode($data, JSON_PRETTY_PRINT);
echo "\n\n";

echo "=== DATA TYPE ===\n";
echo "Type: " . gettype($data) . "\n";
if (is_array($data)) {
    echo "Count: " . count($data) . "\n";
    echo "Keys: " . json_encode(array_keys($data)) . "\n";
}
