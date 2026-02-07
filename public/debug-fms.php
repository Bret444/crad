<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head><title>Debug FMS Integration</title></head>
<body>
<h2>FMS API Integration Debug</h2>

<?php
echo "<h3>Step 1: Test API Connection</h3>";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, env('FMS_API_URL'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'X-API-Key: ' . env('FMS_API_KEY'),
    'X-API-Username: ' . env('FMS_API_USERNAME')
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "<p><strong>HTTP Status:</strong> $httpCode</p>";
echo "<p><strong>API URL:</strong> " . env('FMS_API_URL') . "</p>";

if ($httpCode === 200) {
    echo "<h3>Raw Response:</h3>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    
    $data = json_decode($response, true);
    echo "<p style='color:green'><strong>✓ API Working!</strong></p>";
    echo "<p><strong>Total Records:</strong> " . (is_array($data) ? count($data) : 'Not an array') . "</p>";
    echo "<p><strong>Data Type:</strong> " . gettype($data) . "</p>";
    
    if (is_array($data) && count($data) > 0) {
        echo "<h3>Step 2: Sample Data (First 3 Records)</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>First Name</th><th>Middle Name</th><th>Last Name</th><th>Email</th><th>Role</th><th>Department</th><th>Status</th></tr>";
        
        foreach (array_slice($data, 0, 3) as $item) {
            echo "<tr>";
            echo "<td>" . ($item['id'] ?? 'N/A') . "</td>";
            echo "<td>" . ($item['first_name'] ?? 'N/A') . "</td>";
            echo "<td>" . ($item['middle_name'] ?? 'N/A') . "</td>";
            echo "<td>" . ($item['last_name'] ?? 'N/A') . "</td>";
            echo "<td>" . ($item['email'] ?? 'N/A') . "</td>";
            echo "<td>" . ($item['role'] ?? 'N/A') . "</td>";
            echo "<td>" . ($item['department'] ?? 'N/A') . "</td>";
            echo "<td>" . ($item['status'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        echo "<h3>Step 3: Check Controller Method</h3>";
        $controller = new \App\Http\Controllers\CoordinatorController();
        echo "<p style='color:green'><strong>✓ Controller exists!</strong></p>";
        
        echo "<h3>Step 4: Full First Record</h3>";
        echo "<pre>" . print_r($data[0] ?? 'No data', true) . "</pre>";
    } else {
        echo "<p style='color:red'>Data is not an array or empty</p>";
        echo "<pre>" . print_r($data, true) . "</pre>";
    }
} else {
    echo "<p style='color:red'><strong>✗ API Failed!</strong></p>";
    echo "<p>Response: " . htmlspecialchars($response) . "</p>";
}
?>
</body>
</html>
