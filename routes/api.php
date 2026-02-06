<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Group;
use App\Http\Controllers\FacultyApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Get group by number and department
Route::get('/groups/by-number/{groupNumber}', function ($groupNumber, Request $request) {
    $department = $request->query('department');
    
    try {
        $group = Group::where('group_number', $groupNumber)
                     ->where('department', $department)
                     ->first();
        
        if ($group) {
            return response()->json([
                'success' => true,
                'group' => $group
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Group not found'
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error fetching group data'
        ], 500);
    }
});

// Faculty API Integration
Route::get('/faculties/sync', [FacultyApiController::class, 'syncFaculties']);
Route::get('/faculties/list', [FacultyApiController::class, 'listFaculties']);
Route::get('/faculties/test-external', [FacultyApiController::class, 'testExternal']);

// Student API Integration
Route::get('/students/fetch', [\App\Http\Controllers\StudentApiController::class, 'fetchStudents']);