<?php

namespace App\Http\Controllers;

use App\Models\QuizSession;
use App\Models\QuizRegistration;
use App\Models\ExamType;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SessionReportsController extends Controller
{
    /**
     * Display session reports overview
     */
    public function index()
    {
        $quizSessions = QuizSession::with(['quizRegistrations', 'quizQuestions'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate overall statistics
        $totalSessions = $quizSessions->count();
        $totalRegistrations = $quizSessions->sum(function($session) {
            return $session->quizRegistrations->count();
        });
        $totalCompleted = $quizSessions->sum(function($session) {
            return $session->quizRegistrations->where('is_completed', true)->count();
        });
        $completionRate = $totalRegistrations > 0 ? round(($totalCompleted / $totalRegistrations) * 100, 2) : 0;

        // Get recent activity (last 30 days)
        $recentActivity = $this->getRecentActivity();

        return view('backend.reports.sessions.index', compact(
            'quizSessions', 
            'totalSessions', 
            'totalRegistrations', 
            'totalCompleted', 
            'completionRate',
            'recentActivity'
        ));
    }

    /**
     * Display detailed report for a specific session
     */
    public function show($sessionId)
    {
        $quizSession = QuizSession::with(['quizRegistrations.examType', 'quizQuestions.examType', 'quizSetting'])
            ->findOrFail($sessionId);

        // Basic session statistics
        $sessionStats = $this->getSessionStatistics($quizSession);

        // Registration statistics by exam type
        $examTypeStats = $this->getExamTypeStatistics($quizSession);

        // Performance analytics
        $performanceData = $this->getPerformanceAnalytics($quizSession);

        // Time analytics
        $timeAnalytics = $this->getTimeAnalytics($quizSession);

        // Regional analysis (if applicable)
        $regionalAnalysis = $this->getRegionalAnalysis($quizSession);

        // Question difficulty analysis
        $questionAnalysis = $this->getQuestionAnalysis($quizSession);

        return view('backend.reports.sessions.show', compact(
            'quizSession',
            'sessionStats',
            'examTypeStats',
            'performanceData',
            'timeAnalytics',
            'regionalAnalysis',
            'questionAnalysis'
        ));
    }

    /**
     * Export session report
     */
    public function export($sessionId, Request $request)
    {
        $quizSession = QuizSession::with(['quizRegistrations.examType', 'quizQuestions.examType'])
            ->findOrFail($sessionId);

        $format = $request->get('format', 'csv');

        if ($format === 'csv') {
            return $this->exportToCsv($quizSession);
        } elseif ($format === 'pdf') {
            return $this->exportToPdf($quizSession);
        } elseif ($format === 'excel') {
            return $this->exportToExcel($quizSession);
        }

        return redirect()->back()->with('message', 'Invalid export format')->with('alert-type', 'error');
    }

    /**
     * Get session statistics
     */
    private function getSessionStatistics($quizSession)
    {
        $registrations = $quizSession->quizRegistrations;
        $completed = $registrations->where('is_completed', true);

        return [
            'total_registrations' => $registrations->count(),
            'total_completed' => $completed->count(),
            'completion_rate' => $registrations->count() > 0 ? round(($completed->count() / $registrations->count()) * 100, 2) : 0,
            'average_score' => $completed->count() > 0 ? round($completed->avg('score'), 2) : 0,
            'highest_score' => $completed->count() > 0 ? $completed->max('score') : 0,
            'lowest_score' => $completed->count() > 0 ? $completed->min('score') : 0,
            'time_expired_count' => $completed->where('time_expired', true)->count(),
            'average_time_minutes' => $this->calculateAverageTime($completed),
            'total_questions' => $quizSession->quizQuestions->count(),
            'session_duration_days' => $quizSession->active_from && $quizSession->active_until ? 
                Carbon::parse($quizSession->active_from)->diffInDays(Carbon::parse($quizSession->active_until)) : 0
        ];
    }

    /**
     * Get exam type statistics
     */
    private function getExamTypeStatistics($quizSession)
    {
        $examTypes = ExamType::whereHas('quizRegistrations', function($query) use ($quizSession) {
            $query->where('quiz_session_id', $quizSession->id);
        })->get();

        $stats = [];
        foreach ($examTypes as $examType) {
            $registrations = $quizSession->quizRegistrations->where('exam_type_id', $examType->id);
            $completed = $registrations->where('is_completed', true);

            // Calculate total possible points for this exam type
            $totalPossiblePoints = $quizSession->quizQuestions
                ->where('exam_type_id', $examType->id)
                ->sum('points');

            $stats[] = [
                'exam_type' => $examType,
                'total_registrations' => $registrations->count(),
                'total_completed' => $completed->count(),
                'completion_rate' => $registrations->count() > 0 ? round(($completed->count() / $registrations->count()) * 100, 2) : 0,
                'average_score' => $completed->count() > 0 ? round($completed->avg('score'), 2) : 0,
                'average_percentage' => $completed->count() > 0 && $totalPossiblePoints > 0 ? 
                    round(($completed->avg('score') / $totalPossiblePoints) * 100, 2) : 0,
                'questions_count' => $quizSession->quizQuestions->where('exam_type_id', $examType->id)->count(),
                'time_expired_count' => $completed->where('time_expired', true)->count(),
                'total_possible_points' => $totalPossiblePoints
            ];
        }

        return collect($stats);
    }

    /**
     * Get performance analytics
     */
    private function getPerformanceAnalytics($quizSession)
    {
        $completed = $quizSession->quizRegistrations->where('is_completed', true);

        if ($completed->count() === 0) {
            return [
                'score_distribution' => [],
                'pass_fail_stats' => ['passed' => 0, 'failed' => 0],
                'grade_distribution' => [],
                'top_performers' => collect(),
                'improvement_areas' => []
            ];
        }

        // Calculate score percentages for each registration
        $scoresWithPercentages = $completed->map(function($registration) use ($quizSession) {
            $totalPossible = $quizSession->quizQuestions
                ->where('exam_type_id', $registration->exam_type_id)
                ->sum('points');
            
            $percentage = $totalPossible > 0 ? round(($registration->score / $totalPossible) * 100, 2) : 0;
            
            return [
                'registration' => $registration,
                'percentage' => $percentage
            ];
        });

        // Score distribution (0-20, 21-40, 41-60, 61-80, 81-100)
        $scoreDistribution = [
            '0-20%' => 0,
            '21-40%' => 0,
            '41-60%' => 0,
            '61-80%' => 0,
            '81-100%' => 0
        ];

        $gradeDistribution = [
            'A+' => 0, 'A' => 0, 'B' => 0, 'C' => 0, 'D' => 0, 'F' => 0
        ];

        $passed = 0;
        $failed = 0;

        foreach ($scoresWithPercentages as $item) {
            $percentage = $item['percentage'];
            
            // Score distribution
            if ($percentage <= 20) $scoreDistribution['0-20%']++;
            elseif ($percentage <= 40) $scoreDistribution['21-40%']++;
            elseif ($percentage <= 60) $scoreDistribution['41-60%']++;
            elseif ($percentage <= 80) $scoreDistribution['61-80%']++;
            else $scoreDistribution['81-100%']++;

            // Grade distribution
            if ($percentage >= 90) $gradeDistribution['A+']++;
            elseif ($percentage >= 80) $gradeDistribution['A']++;
            elseif ($percentage >= 70) $gradeDistribution['B']++;
            elseif ($percentage >= 60) $gradeDistribution['C']++;
            elseif ($percentage >= 50) $gradeDistribution['D']++;
            else $gradeDistribution['F']++;

            // Pass/Fail
            if ($percentage >= 60) $passed++;
            else $failed++;
        }

        $topPerformers = $scoresWithPercentages->sortByDesc('percentage')->take(10);

        return [
            'score_distribution' => $scoreDistribution,
            'pass_fail_stats' => ['passed' => $passed, 'failed' => $failed],
            'grade_distribution' => $gradeDistribution,
            'top_performers' => $topPerformers,
            'improvement_areas' => $this->getImprovementAreas($quizSession)
        ];
    }

    /**
     * Get time analytics
     */
    private function getTimeAnalytics($quizSession)
    {
        $completed = $quizSession->quizRegistrations->where('is_completed', true);
        $withTime = $completed->filter(function($reg) {
            return $reg->quiz_started_at && $reg->quiz_taken_at;
        });

        if ($withTime->count() === 0) {
            return [
                'average_time_minutes' => 0,
                'fastest_time_minutes' => 0,
                'slowest_time_minutes' => 0,
                'time_distribution' => [],
                'time_vs_score' => []
            ];
        }

        $times = $withTime->map(function($reg) {
            return $reg->quiz_started_at->diffInMinutes($reg->quiz_taken_at);
        });

        $timeDistribution = [
            '0-10 min' => 0,
            '11-20 min' => 0,
            '21-30 min' => 0,
            '31-45 min' => 0,
            '46-60 min' => 0,
            '60+ min' => 0
        ];

        foreach ($times as $time) {
            if ($time <= 10) $timeDistribution['0-10 min']++;
            elseif ($time <= 20) $timeDistribution['11-20 min']++;
            elseif ($time <= 30) $timeDistribution['21-30 min']++;
            elseif ($time <= 45) $timeDistribution['31-45 min']++;
            elseif ($time <= 60) $timeDistribution['46-60 min']++;
            else $timeDistribution['60+ min']++;
        }

        return [
            'average_time_minutes' => round($times->avg(), 2),
            'fastest_time_minutes' => $times->min(),
            'slowest_time_minutes' => $times->max(),
            'time_distribution' => $timeDistribution,
            'time_vs_score' => $withTime->map(function($reg) use ($quizSession) {
                $totalPossible = $quizSession->quizQuestions
                    ->where('exam_type_id', $reg->exam_type_id)
                    ->sum('points');
                $percentage = $totalPossible > 0 ? round(($reg->score / $totalPossible) * 100, 2) : 0;
                
                return [
                    'time' => $reg->quiz_started_at->diffInMinutes($reg->quiz_taken_at),
                    'score' => $percentage
                ];
            })->toArray()
        ];
    }

    /**
     * Get regional analysis
     */
    private function getRegionalAnalysis($quizSession)
    {
        $registrations = $quizSession->quizRegistrations;
        $regions = $registrations->groupBy('region_name');

        $analysis = [];
        foreach ($regions as $regionName => $regionRegistrations) {
            $completed = $regionRegistrations->where('is_completed', true);
            
            // Calculate average percentage properly
            $avgPercentage = 0;
            if ($completed->count() > 0) {
                $totalPercentage = $completed->sum(function($reg) use ($quizSession) {
                    $totalPossible = $quizSession->quizQuestions
                        ->where('exam_type_id', $reg->exam_type_id)
                        ->sum('points');
                    return $totalPossible > 0 ? ($reg->score / $totalPossible) * 100 : 0;
                });
                $avgPercentage = round($totalPercentage / $completed->count(), 2);
            }
            
            $analysis[] = [
                'region' => $regionName,
                'total_registrations' => $regionRegistrations->count(),
                'total_completed' => $completed->count(),
                'completion_rate' => $regionRegistrations->count() > 0 ? 
                    round(($completed->count() / $regionRegistrations->count()) * 100, 2) : 0,
                'average_score' => $completed->count() > 0 ? round($completed->avg('score'), 2) : 0,
                'average_percentage' => $avgPercentage,
                'highest_score' => $completed->count() > 0 ? $completed->max('score') : 0,
                'provinces' => $quizSession->type === 'regional' ? 
                    $regionRegistrations->pluck('province')->unique()->filter()->count() : 0
            ];
        }

        return collect($analysis)->sortByDesc('average_percentage');
    }

    /**
     * Get question analysis
     */
    private function getQuestionAnalysis($quizSession)
    {
        $questions = $quizSession->quizQuestions;
        $analysis = [];

        foreach ($questions as $question) {
            $registrations = $quizSession->quizRegistrations
                ->where('is_completed', true)
                ->where('exam_type_id', $question->exam_type_id);
            
            $totalAnswers = 0;
            $correctAnswers = 0;

            foreach ($registrations as $registration) {
                if (isset($registration->answers[$question->id])) {
                    $totalAnswers++;
                    if ($question->isCorrectAnswer($registration->answers[$question->id])) {
                        $correctAnswers++;
                    }
                }
            }

            $correctPercentage = $totalAnswers > 0 ? round(($correctAnswers / $totalAnswers) * 100, 2) : 0;

            $analysis[] = [
                'question' => $question,
                'total_answers' => $totalAnswers,
                'correct_answers' => $correctAnswers,
                'correct_percentage' => $correctPercentage,
                'difficulty_level' => $this->getDifficultyLevel($correctPercentage),
                'exam_type' => $question->examType->name
            ];
        }

        return collect($analysis)->sortBy('correct_percentage');
    }

    /**
     * Get improvement areas
     */
    private function getImprovementAreas($quizSession)
    {
        $questionAnalysis = $this->getQuestionAnalysis($quizSession);
        $difficultQuestions = $questionAnalysis->where('correct_percentage', '<', 60);

        return $difficultQuestions->map(function($item) {
            return [
                'area' => Str::limit($item['question']->question, 50),
                'success_rate' => $item['correct_percentage'],
                'exam_type' => $item['exam_type']
            ];
        })->take(5);
    }

    /**
     * Get difficulty level based on correct percentage
     */
    private function getDifficultyLevel($percentage)
    {
        if ($percentage >= 80) return 'Easy';
        if ($percentage >= 60) return 'Medium';
        if ($percentage >= 40) return 'Hard';
        return 'Very Hard';
    }

    /**
     * Calculate average time in minutes
     */
    private function calculateAverageTime($completed)
    {
        $withTime = $completed->filter(function($reg) {
            return $reg->quiz_started_at && $reg->quiz_taken_at;
        });

        if ($withTime->count() === 0) return 0;

        $totalMinutes = $withTime->sum(function($reg) {
            return $reg->quiz_started_at->diffInMinutes($reg->quiz_taken_at);
        });

        return round($totalMinutes / $withTime->count(), 2);
    }

    /**
     * Get recent activity for dashboard
     */
    private function getRecentActivity()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        return QuizRegistration::where('created_at', '>=', $thirtyDaysAgo)
            ->with(['quizSession', 'examType'])
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get()
            ->map(function($registration) {
                return [
                    'date' => $registration->created_at->format('M d'),
                    'session' => $registration->quizSession->title,
                    'exam_type' => $registration->examType->name,
                    'email' => $registration->email,
                    'status' => $registration->is_completed ? 'Completed' : 'Registered',
                    'score' => $registration->score ?? 'N/A'
                ];
            });
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($quizSession)
    {
        $filename = sprintf('session_report_%s_%s.csv', 
            str_replace(' ', '_', $quizSession->title), 
            now()->format('Y-m-d_H-i-s')
        );

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($quizSession) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Email', 'Exam Type', 'Region', 'Province', 'Score', 'Percentage', 
                'Time Taken (min)', 'Registered At', 'Started At', 'Completed At', 
                'Time Expired', 'Status'
            ]);

            // CSV Data
            foreach ($quizSession->quizRegistrations as $registration) {
                $timeTaken = ($registration->quiz_started_at && $registration->quiz_taken_at) ? 
                    $registration->quiz_started_at->diffInMinutes($registration->quiz_taken_at) : 'N/A';

                // Calculate percentage
                $totalPossible = $quizSession->quizQuestions
                    ->where('exam_type_id', $registration->exam_type_id)
                    ->sum('points');
                $percentage = ($registration->score && $totalPossible > 0) ? 
                    round(($registration->score / $totalPossible) * 100, 2) : 'N/A';

                fputcsv($file, [
                    $registration->email,
                    $registration->examType->name,
                    $registration->region_name,
                    $registration->province ?? '',
                    $registration->score ?? 'N/A',
                    $percentage,
                    $timeTaken,
                    $registration->registered_at->format('Y-m-d H:i:s'),
                    $registration->quiz_started_at ? $registration->quiz_started_at->format('Y-m-d H:i:s') : 'N/A',
                    $registration->quiz_taken_at ? $registration->quiz_taken_at->format('Y-m-d H:i:s') : 'N/A',
                    $registration->time_expired ? 'Yes' : 'No',
                    $registration->is_completed ? 'Completed' : 'Registered'
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
