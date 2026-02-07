<?php
header('Content-Type: text/html; charset=utf-8');

$apiUrl = 'https://fms3.jampzdev.com/api/subject-load-tracker/list';
$apiKey = 'SubjectLoadApi';
$apiUsername = 'Emmanuel';

echo "<h2>Testing FMS API</h2>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-Key: ' . $apiKey,
    'X-API-Username: ' . $apiUsername
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<p><strong>HTTP Status:</strong> $httpCode</p>";

if ($httpCode === 200) {
    $data = json_decode($response, true);
    echo "<p><strong>Total Records:</strong> " . count($data) . "</p>";
    
    // Show ALL data structure
    echo "<h3>Full API Response:</h3>";
    echo "<pre>" . print_r($data, true) . "</pre>";
    
    // Show first record structure
    if (isset($data[0])) {
        echo "<h3>First Record:</h3>";
        echo "<pre>" . print_r($data[0], true) . "</pre>";
    }
    
    // Filter coordinators
    $coordinators = array_filter($data, function($item) {
        return isset($item['role']) && strtolower($item['role']) === 'coordinator';
    });
    
    echo "<h3>Coordinators Found: " . count($coordinators) . "</h3>";
    
    if (count($coordinators) > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Name</th><th>Department</th><th>Role</th><th>Employment Type</th></tr>";
        foreach ($coordinators as $coord) {
            $fullName = trim(($coord['first_name'] ?? '') . ' ' . ($coord['middle_name'] ?? '') . ' ' . ($coord['name'] ?? ''));
            echo "<tr>";
            echo "<td>$fullName</td>";
            echo "<td>" . ($coord['department'] ?? 'N/A') . "</td>";
            echo "<td>" . ($coord['role'] ?? 'N/A') . "</td>";
            echo "<td>" . ($coord['employment_type'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
} else {
    echo "<p style='color:red'>Failed to fetch data. HTTP Code: $httpCode</p>";
    echo "<pre>$response</pre>";
}
?>
