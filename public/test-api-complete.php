<?php
header('Content-Type: application/json');

// Test 1: Direct API Call
echo "=== TEST 1: Direct External API Call ===\n";
$apiUrl = 'http://fms3.jampzdev.com/api/subject-load-tracker/list';

$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'ignore_errors' => true
    ]
]);

$response = @file_get_contents($apiUrl, false, $context);

if ($response === false) {
    $error = error_get_last();
    echo json_encode([
        'test1_status' => 'FAILED',
        'error' => $error['message'] ?? 'Unknown error',
        'message' => 'Cannot reach external API'
    ], JSON_PRETTY_PRINT);
    echo "\n\n";
} else {
    $data = json_decode($response, true);
    echo json_encode([
        'test1_status' => 'SUCCESS',
        'record_count' => is_array($data) ? count($data) : 0,
        'sample_data' => is_array($data) && count($data) > 0 ? $data[0] : null
    ], JSON_PRETTY_PRINT);
    echo "\n\n";
    
    // Test 2: Data Mapping
    echo "=== TEST 2: Data Mapping ===\n";
    if (is_array($data) && count($data) > 0) {
        $mapped = array_map(function($item) {
            return [
                'name' => $item['instructor_name'] ?? $item['name'] ?? null,
                'department' => $item['department'] ?? null,
                'contact' => $item['contact_number'] ?? $item['contact'] ?? null,
                'sections' => $item['sections'] ?? [],
                'subject' => $item['subject_code'] ?? $item['subject'] ?? null
            ];
        }, $data);
        
        echo json_encode([
            'test2_status' => 'SUCCESS',
            'mapped_count' => count($mapped),
            'sample_mapped' => $mapped[0]
        ], JSON_PRETTY_PRINT);
        echo "\n\n";
        
        // Test 3: Field Validation
        echo "=== TEST 3: Field Validation ===\n";
        $validRecords = 0;
        $invalidRecords = 0;
        $missingFields = [];
        
        foreach ($mapped as $record) {
            $valid = true;
            if (empty($record['name'])) {
                $missingFields[] = 'name';
                $valid = false;
            }
            if (empty($record['department'])) {
                $missingFields[] = 'department';
                $valid = false;
            }
            
            if ($valid) {
                $validRecords++;
            } else {
                $invalidRecords++;
            }
        }
        
        echo json_encode([
            'test3_status' => 'SUCCESS',
            'valid_records' => $validRecords,
            'invalid_records' => $invalidRecords,
            'missing_fields' => array_unique($missingFields)
        ], JSON_PRETTY_PRINT);
        echo "\n\n";
    }
}

// Test 4: Laravel Proxy Route Test
echo "=== TEST 4: Laravel Proxy Route ===\n";
$proxyUrl = 'http://localhost/crad3/public/api/subject-load-proxy';

$proxyResponse = @file_get_contents($proxyUrl, false, $context);

if ($proxyResponse === false) {
    echo json_encode([
        'test4_status' => 'FAILED',
        'message' => 'Laravel proxy route not accessible. Make sure you are logged in as coordinator.'
    ], JSON_PRETTY_PRINT);
} else {
    $proxyData = json_decode($proxyResponse, true);
    echo json_encode([
        'test4_status' => 'SUCCESS',
        'proxy_working' => true,
        'record_count' => is_array($proxyData) ? count($proxyData) : 0
    ], JSON_PRETTY_PRINT);
}

echo "\n\n=== INTEGRATION TEST COMPLETE ===\n";
