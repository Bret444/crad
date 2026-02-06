<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudentApiController extends Controller
{
    public function fetchStudents(Request $request)
    {
        try {
            $apiUrl = env('STUDENT_API_URL', 'https://sisreg.jampzdev.com/api/getpersonalinfo.php');
            $apiKey = env('STUDENT_API_KEY', 'RegistrarAPIKeyPass');
            
            $response = Http::timeout(30)->get($apiUrl, ['key' => $apiKey]);
            
            if (!$response->successful()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to fetch student data',
                    'status' => $response->status()
                ], $response->status());
            }

            $students = $response->json();
            
            if (!is_array($students)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid data format'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'count' => count($students),
                'students' => $students
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
