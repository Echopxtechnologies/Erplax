<?php

namespace Database\Seeders;

use App\Models\Admin\CronJob;
use Illuminate\Database\Seeder;

class CronJobSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cronJobs = [
            [
                'name' => 'Test Cron',
                'method' => 'TestCron/sendRemainder',
                'schedule' => 'daily',
                'description' => 'Test cron job to verify the system is working',
                'status' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Invoice Payment Reminders',
                'method' => 'InvoiceCron/sendPaymentReminders',
                'schedule' => 'daily',
                'description' => 'Send payment reminder emails for overdue invoices',
                'status' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Generate Recurring Invoices',
                'method' => 'InvoiceCron/generateRecurringInvoices',
                'schedule' => 'daily',
                'description' => 'Automatically generate invoices from recurring templates',
                'status' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Mark Overdue Invoices',
                'method' => 'InvoiceCron/markOverdueInvoices',
                'schedule' => 'daily',
                'description' => 'Update invoice status to overdue when past due date',
                'status' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($cronJobs as $job) {
            CronJob::updateOrCreate(
                ['name' => $job['name']],
                $job
            );
        }

        $this->command->info('Default cron jobs seeded successfully!');
    }
}