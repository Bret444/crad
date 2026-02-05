<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasColumn('groups', 'cluster')) {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('cluster')->after('department');
        });
        echo "Cluster column added successfully!\n";
    } else {
        echo "Cluster column already exists!\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}