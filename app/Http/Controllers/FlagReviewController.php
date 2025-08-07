<?php

namespace App\Http\Controllers;


use App\Models\QuestionFlag;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FlagReviewController extends Controller
{
    /**
     * Display question flags dashboard
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $priority = $request->get('priority', 'all');
        $flagType = $request->get('flag_type', 'all');
        
        $query = QuestionFlag::with(['question.quizSession', 'question.examType', 'flaggedBy', 'reviewedBy'])
            ->orderBy('created_at', 'desc');
        
        // Apply filters
        if ($status !== 'all') {
            $query->where('status', $status);
        }
        
        if ($priority !== 'all') {
            $query->where('priority', $priority);
        }
        
        if ($flagType !== 'all') {
            $query->where('flag_type', $flagType);
        }
        
        $flags = $query->paginate(20);
        
        // Get summary statistics
        $summaryStats = [
            'total_flags' => QuestionFlag::count(),
            'pending_flags' => QuestionFlag::where('status', 'pending')->count(),
            'high_priority' => QuestionFlag::where('priority', 'high')->count(),
            'under_review' => QuestionFlag::where('status', 'under_review')->count(),
            'resolved_today' => QuestionFlag::where('status', 'resolved')
                ->whereDate('reviewed_at', today())->count()
        ];
        
        return view('backend.flags.index', compact(
            'flags', 
            'summaryStats',
            'status',
            'priority', 
            'flagType'
        ));
    }

    /**
     * Show flag details
     */
    public function show($flagId)
    {
        $flag = QuestionFlag::with([
            'question.quizSession', 
            'question.examType', 
            'flaggedBy', 
            'reviewedBy'
        ])->findOrFail($flagId);
        
        // Get question analytics if available
        $questionAnalytics = $this->getQuestionAnalytics($flag->question);
        
        return view('backend.flags.show', compact('flag', 'questionAnalytics'));
    }

    /**
     * Flag a question for review
     */
    public function store(Request $request)
    {
        $request->validate([
            'question_id' => 'required|exists:quiz_questions,id',
            'flag_type' => 'required|in:poor_performance,poor_discrimination,ambiguous_wording,incorrect_answer,too_difficult,too_easy,other',
            'reason' => 'nullable|string|max:1000',
            'suggested_improvement' => 'nullable|string|max:1000',
            'priority' => 'required|in:low,medium,high'
        ]);

        // Check if user already flagged this question
        $existingFlag = QuestionFlag::where('question_id', $request->question_id)
            ->where('flagged_by', Auth::id())
            ->first();

        if ($existingFlag) {
            return response()->json([
                'success' => false,
                'message' => 'You have already flagged this question for review.'
            ], 422);
        }

        $flag = QuestionFlag::create([
            'question_id' => $request->question_id,
            'flagged_by' => Auth::id(),
            'flag_type' => $request->flag_type,
            'reason' => $request->reason,
            'suggested_improvement' => $request->suggested_improvement,
            'priority' => $request->priority,
            'status' => 'pending'
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Question flagged for review successfully!',
                'flag_id' => $flag->id
            ]);
        }

        return redirect()->back()
            ->with('message', 'Question flagged for review successfully!')
            ->with('alert-type', 'success');
    }

    /**
     * Update flag status (for reviewers)
     */
    public function updateStatus(Request $request, $flagId)
    {
        $request->validate([
            'status' => 'required|in:pending,under_review,resolved,dismissed',
            'review_notes' => 'nullable|string|max:1000'
        ]);

        $flag = QuestionFlag::findOrFail($flagId);
        
        $flag->update([
            'status' => $request->status,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_notes' => $request->review_notes
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Flag status updated successfully!',
                'new_status' => $flag->status
            ]);
        }

        return redirect()->back()
            ->with('message', 'Flag status updated successfully!')
            ->with('alert-type', 'success');
    }

    /**
     * Bulk update flag statuses
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'flag_ids' => 'required|array',
            'flag_ids.*' => 'exists:question_flags,id',
            'action' => 'required|in:resolve,dismiss,review',
            'review_notes' => 'nullable|string|max:1000'
        ]);

        $status = match($request->action) {
            'resolve' => 'resolved',
            'dismiss' => 'dismissed',
            'review' => 'under_review'
        };

        QuestionFlag::whereIn('id', $request->flag_ids)
            ->update([
                'status' => $status,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
                'review_notes' => $request->review_notes
            ]);

        $count = count($request->flag_ids);
        $message = "Successfully {$request->action}d {$count} flag(s).";

        return redirect()->back()
            ->with('message', $message)
            ->with('alert-type', 'success');
    }

    /**
     * Delete a flag
     */
    public function destroy($flagId)
    {
        $flag = QuestionFlag::findOrFail($flagId);
        
        // Only allow deletion by the person who created the flag or admins
        if ($flag->flagged_by !== Auth::id() && !Auth::user()->hasRole('admin')) {
            return redirect()->back()
                ->with('message', 'You are not authorized to delete this flag.')
                ->with('alert-type', 'error');
        }

        $flag->delete();

        return redirect()->back()
            ->with('message', 'Flag deleted successfully!')
            ->with('alert-type', 'success');
    }

    /**
     * Get analytics for a specific question
     */
    private function getQuestionAnalytics($question)
    {
        // Basic analytics calculation
        $registrations = \App\Models\QuizRegistration::where('quiz_session_id', $question->quiz_session_id)
            ->where('exam_type_id', $question->exam_type_id)
            ->where('is_completed', true)
            ->whereNotNull('answers')
            ->get();

        $totalAnswers = 0;
        $correctAnswers = 0;

        foreach ($registrations as $registration) {
            if (isset($registration->answers[$question->id])) {
                $totalAnswers++;
                if (method_exists($question, 'isCorrectAnswer') && 
                    $question->isCorrectAnswer($registration->answers[$question->id])) {
                    $correctAnswers++;
                }
            }
        }

        $successRate = $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100, 2) : 0;

        return [
            'total_attempts' => $totalAnswers,
            'correct_answers' => $correctAnswers,
            'success_rate' => $successRate,
            'difficulty_level' => $this->getDifficultyLevel($successRate)
        ];
    }

    /**
     * Get difficulty level based on success rate
     */
    private function getDifficultyLevel($successRate)
    {
        if ($successRate >= 80) return 'Easy';
        if ($successRate >= 60) return 'Medium';
        if ($successRate >= 40) return 'Hard';
        return 'Very Hard';
    }

    /**
     * Export flags report
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        
        $flags = QuestionFlag::with(['question.quizSession', 'question.examType', 'flaggedBy', 'reviewedBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($format === 'csv') {
            return $this->exportFlagsToCsv($flags);
        }

        return redirect()->back()
            ->with('message', 'Invalid export format')
            ->with('alert-type', 'error');
    }

    /**
     * Export flags to CSV
     */
    private function exportFlagsToCsv($flags)
    {
        $filename = sprintf('question_flags_%s.csv', now()->format('Y-m-d_H-i-s'));

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($flags) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Flag ID', 'Question ID', 'Question Text', 'Quiz Session', 'Exam Type',
                'Flag Type', 'Priority', 'Status', 'Reason', 'Suggested Improvement',
                'Flagged By', 'Flagged At', 'Reviewed By', 'Reviewed At', 'Review Notes'
            ]);

            // CSV Data
            foreach ($flags as $flag) {
                fputcsv($file, [
                    $flag->id,
                    $flag->question->id,
                    \Illuminate\Support\Str::limit($flag->question->question, 100),
                    $flag->question->quizSession->title,
                    $flag->question->examType->name,
                    $flag->getFlagTypeLabel(),
                    ucfirst($flag->priority),
                    ucfirst($flag->status),
                    $flag->reason ?? 'N/A',
                    $flag->suggested_improvement ?? 'N/A',
                    $flag->flaggedBy->name,
                    $flag->created_at->format('Y-m-d H:i:s'),
                    $flag->reviewedBy->name ?? 'N/A',
                    $flag->reviewed_at ? $flag->reviewed_at->format('Y-m-d H:i:s') : 'N/A',
                    $flag->review_notes ?? 'N/A'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get flag statistics for dashboard widgets
     */
    public function getStats()
    {
        $stats = [
            'total_flags' => QuestionFlag::count(),
            'pending_flags' => QuestionFlag::where('status', 'pending')->count(),
            'high_priority_flags' => QuestionFlag::where('priority', 'high')->count(),
            'flags_this_week' => QuestionFlag::where('created_at', '>=', now()->subWeek())->count(),
            'resolved_this_week' => QuestionFlag::where('status', 'resolved')
                ->where('reviewed_at', '>=', now()->subWeek())->count(),
            'flag_types_breakdown' => QuestionFlag::select('flag_type')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('flag_type')
                ->get()
                ->pluck('count', 'flag_type'),
            'recent_flags' => QuestionFlag::with(['question.quizSession', 'flaggedBy'])
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get()
        ];

        return response()->json($stats);
    }
}
