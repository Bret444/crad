<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class FacultyApiController extends Controller
{
    private $apiUrl;
    private $apiUsername;
    private $apiKey;

    public function __construct()
    {
        $this->apiUrl = env('FMS_API_URL', 'http://fms3.jampzdev.com/api/subject-load-tracker/list');
        $this->apiUsername = env('FMS_API_USERNAME', 'Emmanuel');
        $this->apiKey = env('FMS_API_KEY', 'SubjectLoadApi');
    }

    public function testExternal()
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-API-KEY' => $this->apiKey,
                    'X-USERNAME' => $this->apiUsername
                ])
                ->get($this->apiUrl);
            
            return response()->json([
                'status' => $response->status(),
                'success' => $response->successful(),
                'data' => $response->json(),
                'message' => $response->successful() ? 'FMS API accessible' : 'FMS API returned error'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function syncFaculties(Request $request)
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'X-API-KEY' => $this->apiKey,
                    'X-USERNAME' => $this->apiUsername
                ])
                ->get($this->apiUrl);
            
            if (!$response->successful()) {
                return response()->json([
                    'error' => 'FMS API error',
                    'status' => $response->status(),
                    'message' => 'Failed to fetch from FMS API',
                    'response' => $response->json()
                ], $response->status());
            }

            $apiData = $response->json();
            $fmsFaculties = $apiData['data'] ?? [];
            
            if (empty($fmsFaculties)) {
                return response()->json(['success' => false, 'message' => 'No data found in FMS API'], 404);
            }
            
            $synced = 0;
            $professors = [];

            foreach ($fmsFaculties as $item) {
                $profName = $item['professor'] ?? null;
                if (!$profName || isset($professors[$profName])) continue;
                
                $professors[$profName] = true;
                $professorId = 'FMS-' . str_pad($synced + 1, 4, '0', STR_PAD_LEFT);
                
                DB::table('faculties')->updateOrInsert(
                    ['name' => $profName],
                    [
                        'professor_id' => $professorId,
                        'first_name' => explode(' ', $profName)[0] ?? null,
                        'last_name' => explode(' ', $profName)[count(explode(' ', $profName)) - 1] ?? null,
                        'email' => strtolower(str_replace(' ', '', $profName)) . '@fms.edu',
                        'password' => bcrypt('default'),
                        'role' => 'professor',
                        'status' => 'active',
                        'employment_type' => 'Full-Time',
                        'department' => 'BSIT',
                        'updated_at' => now()
                    ]
                );
                $synced++;
            }

            return response()->json(['success' => true, 'synced' => $synced, 'message' => "$synced faculties synced from FMS API"]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listFaculties(Request $request)
    {
        try {
            $department = $request->query('department');
            
            $query = DB::table('faculties')->where('status', 'active');
            
            if ($department) {
                $query->where('department', $department);
            }
            
            $faculties = $query->orderBy('name')->get();
            
            return response()->json(['success' => true, 'count' => $faculties->count(), 'faculties' => $faculties]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
