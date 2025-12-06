<?php

namespace Modules\Todo\Console\Commands;

use Illuminate\Console\Command;
use Modules\Todo\Models\Todo;

class CheckOverdueTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'todo:check-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for overdue tasks and send notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue tasks...');

        $overdueTodos = Todo::overdue()
            ->where('overdue_notified', false)
            ->get();

        if ($overdueTodos->isEmpty()) {
            $this->info('No overdue tasks found.');
            return 0;
        }

        $count = 0;
        foreach ($overdueTodos as $todo) {
            try {
                $todo->sendOverdueNotification();
                $count++;
                $this->line("  - Notified for task: {$todo->title}");
            } catch (\Exception $e) {
                $this->error("  - Failed for task {$todo->id}: {$e->getMessage()}");
            }
        }

        $this->info("Done! Sent {$count} overdue notifications.");
        return 0;
    }
}
