<?php

namespace App\Traits;

trait ControlLineProgress
{
    /**
     * Get overall progress percentage
     */
    public function getProgressPercentage(): int
    {
        $totalTasks = $this->tasks()->count();
        
        if ($totalTasks === 0) {
            return 0;
        }

        $completedTasks = $this->tasks()->whereHas('completions')->count();
        
        return round(($completedTasks / $totalTasks) * 100);
    }

    /**
     * Get start check progress
     */
    public function getStartCheckProgress(): array
    {
        $totalTasks = $this->tasks()->count();
        $startCompletions = $this->tasks()
            ->whereHas('completions', function($query) {
                $query->where('check_type', 'start');
            })->count();

        return [
            'completed' => $startCompletions,
            'total' => $totalTasks,
            'percentage' => $totalTasks > 0 ? round(($startCompletions / $totalTasks) * 100) : 0,
            'is_complete' => $startCompletions === $totalTasks && $totalTasks > 0,
        ];
    }

    /**
     * Get exit check progress
     */
    public function getExitCheckProgress(): array
    {
        $totalTasks = $this->tasks()->count();
        $exitCompletions = $this->tasks()
            ->whereHas('completions', function($query) {
                $query->where('check_type', 'exit');
            })->count();

        return [
            'completed' => $exitCompletions,
            'total' => $totalTasks,
            'percentage' => $totalTasks > 0 ? round(($exitCompletions / $totalTasks) * 100) : 0,
            'is_complete' => $exitCompletions === $totalTasks && $totalTasks > 0,
        ];
    }

    /**
     * Check if start check is completed
     */
    public function isStartCheckCompleted(): bool
    {
        return $this->getStartCheckProgress()['is_complete'];
    }

    /**
     * Check if exit check is completed
     */
    public function isExitCheckCompleted(): bool
    {
        return $this->getExitCheckProgress()['is_complete'];
    }

    /**
     * Check if control is fully completed
     */
    public function isFullyCompleted(): bool
    {
        return $this->status === 'completed' || 
               ($this->isStartCheckCompleted() && $this->isExitCheckCompleted());
    }

    /**
     * Get tasks with issues
     */
    public function getTasksWithIssues()
    {
        return $this->tasks()
            ->whereHas('completions', function($query) {
                $query->whereIn('status', ['issue', 'missing', 'damaged']);
            })
            ->with('completions')
            ->get();
    }

    /**
     * Get completion summary
     */
    public function getCompletionSummary(): array
    {
        $tasks = $this->tasks()->with('completions')->get();
        
        $summary = [
            'total_tasks' => $tasks->count(),
            'completed_tasks' => 0,
            'pending_tasks' => 0,
            'tasks_with_issues' => 0,
            'start_check' => $this->getStartCheckProgress(),
            'exit_check' => $this->getExitCheckProgress(),
            'status_breakdown' => [
                'ok' => 0,
                'issue' => 0,
                'missing' => 0,
                'damaged' => 0,
            ],
        ];

        foreach ($tasks as $task) {
            if ($task->completions->count() > 0) {
                $summary['completed_tasks']++;
                
                // Count issues
                if ($task->hasIssues()) {
                    $summary['tasks_with_issues']++;
                }

                // Count status breakdown
                foreach ($task->completions as $completion) {
                    if (isset($summary['status_breakdown'][$completion->status])) {
                        $summary['status_breakdown'][$completion->status]++;
                    }
                }
            } else {
                $summary['pending_tasks']++;
            }
        }

        return $summary;
    }

    /**
     * Get next required action
     */
    public function getNextAction(): string
    {
        if ($this->status === 'completed') {
            return 'Control completed';
        }

        $startProgress = $this->getStartCheckProgress();
        $exitProgress = $this->getExitCheckProgress();

        if (!$startProgress['is_complete']) {
            return 'Complete start check (' . $startProgress['completed'] . '/' . $startProgress['total'] . ' tasks)';
        }

        if (!$exitProgress['is_complete']) {
            return 'Complete exit check (' . $exitProgress['completed'] . '/' . $exitProgress['total'] . ' tasks)';
        }

        return 'All checks completed - Ready to close';
    }

    /**
     * Can perform start check
     */
    public function canPerformStartCheck(): bool
    {
        return $this->status === 'active' && !$this->isStartCheckCompleted();
    }

    /**
     * Can perform exit check
     */
    public function canPerformExitCheck(): bool
    {
        return $this->status === 'active' && !$this->isExitCheckCompleted();
    }

    /**
     * Should show start check button
     */
    public function shouldShowStartCheckButton(): bool
    {
        return $this->canPerformStartCheck();
    }

    /**
     * Should show exit check button
     */
    public function shouldShowExitCheckButton(): bool
    {
        return $this->canPerformExitCheck();
    }

    /**
     * Get damage reports count
     */
    public function getDamageReportsCount(): int
    {
        return $this->damageReports()->count();
    }

    /**
     * Get pending damage reports count
     */
    public function getPendingDamageReportsCount(): int
    {
        return $this->damageReports()->whereIn('status', ['reported', 'in_repair'])->count();
    }

    /**
     * Has any damage reports
     */
    public function hasDamageReports(): bool
    {
        return $this->getDamageReportsCount() > 0;
    }

    /**
     * Has pending damage reports
     */
    public function hasPendingDamageReports(): bool
    {
        return $this->getPendingDamageReportsCount() > 0;
    }
}