<?php

// app/Models/Submission.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Models\ResubmissionHistory;

class Submission extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'documents',
        'defense_type',
        'department',
        'cluster',
        'group_no',
        'file_path',
        'status',
        'submitted_by'
    ];

    public function committee()
    {
        return $this->hasOne(Committee::class);
    }

    // if you need owner/user relation:
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function resubmissions()
{
    return $this->hasMany(ResubmissionHistory::class);
}

public function resubmit(Request $request, $id)
{
    $submission = Submission::where('id', $id)
        ->where('user_id', auth()->id()) // ensure ownership
        ->firstOrFail();

    $request->validate([
        'title' => 'required|string|max:255',
        'file' => 'required|mimes:pdf,doc,docx|max:2048',
    ]);

    // Save history of previous submission
    ResubmissionHistory::create([
        'submission_id' => $submission->id,
        'title' => $submission->title,
        'file_path' => $submission->file_path,
    ]);

    // Store new file
    $path = $request->file('file')->store('submissions', 'public');

    // Update submission with latest version
    $submission->update([
        'title' => $request->title,
        'file_path' => $path,
        'status' => 'Pending',   // reset to pending
        'feedback' => null,      // clear feedback
    ]);

    return redirect()->back()->with('success', 'Proposal revised and resubmitted successfully!');
}

}




