<?php

namespace Modules\Service\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Service\Models\Service;
use Modules\Service\Models\ServiceNotification;
use Carbon\Carbon;

class SendServiceReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'service:send-reminders 
                            {--force : Send reminders even if already sent recently}
                            {--dry-run : Show what would be sent without actually sending}';

    /**
     * The console command description.
     */
    protected $description = 'Send automatic service reminder emails for upcoming and overdue services';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Checking for services due for reminders...');
        
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');
        
        // Get all active services with upcoming or overdue service dates
        // Only get services with auto_reminder enabled
        $services = Service::with('client')
            ->where('status', 'active')
            ->where('auto_reminder', true)
            ->whereNotNull('next_service_date')
            ->get();
        
        $sentCount = 0;
        $skippedCount = 0;
        $failedCount = 0;
        
        foreach ($services as $service) {
            // Skip if no client email
            if (!$service->client || !$service->client->email) {
                $this->line("  ⏭️  #{$service->id} {$service->machine_name} - No client email");
                $skippedCount++;
                continue;
            }
            
            $reminderDays = $service->reminder_days ?? 15;
            $nextDate = Carbon::parse($service->next_service_date);
            $daysUntil = now()->startOfDay()->diffInDays($nextDate, false);
            
            // Check if service is within reminder window or overdue
            $shouldRemind = false;
            $reminderType = '';
            
            if ($daysUntil < 0) {
                // Overdue
                $shouldRemind = true;
                $reminderType = 'overdue';
            } elseif ($daysUntil <= $reminderDays) {
                // Within reminder window
                $shouldRemind = true;
                $reminderType = 'upcoming';
            }
            
            if (!$shouldRemind) {
                continue; // Not yet time to remind
            }
            
            // Check if reminder was already sent recently (within last 7 days)
            if (!$force) {
                $recentReminder = ServiceNotification::where('service_id', $service->id)
                    ->where('type', 'reminder')
                    ->where('status', 'sent')
                    ->where('sent_at', '>=', now()->subDays(7))
                    ->exists();
                
                if ($recentReminder) {
                    $this->line("  ⏭️  #{$service->id} {$service->machine_name} - Reminder already sent within 7 days");
                    $skippedCount++;
                    continue;
                }
            }
            
            // Prepare reminder info
            $daysText = $daysUntil < 0 
                ? abs($daysUntil) . ' days overdue' 
                : ($daysUntil == 0 ? 'Due today' : "{$daysUntil} days remaining");
            
            $this->line("  📧 #{$service->id} {$service->machine_name} - {$daysText} ({$reminderType})");
            
            if ($dryRun) {
                $this->line("      → Would send to: {$service->client->email}");
                $sentCount++;
                continue;
            }
            
            // Send the reminder
            $result = $this->sendReminder($service, $reminderType, $daysUntil);
            
            if ($result) {
                $this->info("      ✓ Sent to: {$service->client->email}");
                $sentCount++;
            } else {
                $this->error("      ✗ Failed to send");
                $failedCount++;
            }
        }
        
        // Summary
        $this->newLine();
        $this->info("📊 Summary:");
        $this->line("   Sent: {$sentCount}");
        $this->line("   Skipped: {$skippedCount}");
        $this->line("   Failed: {$failedCount}");
        
        if ($dryRun) {
            $this->warn("   (Dry run - no emails were actually sent)");
        }
        
        return 0;
    }

    /**
     * Send reminder email for a service
     */
    protected function sendReminder(Service $service, string $type, int $daysUntil): bool
    {
        try {
            $clientEmail = $service->client->email;
            
            // Determine subject based on type
            if ($type === 'overdue') {
                $subject = "⚠️ OVERDUE: Service Required - {$service->machine_name}";
            } else {
                $subject = "Service Reminder - {$service->machine_name}";
            }
            
            $body = $this->getReminderEmailBody($service, $type, $daysUntil);
            
            // Send email using MailService helper
            $sent = false;
            if (function_exists('send_mail')) {
                $sent = send_mail($clientEmail, $subject, $body);
            }
            
            // Log notification
            ServiceNotification::create([
                'service_id' => $service->id,
                'type' => 'reminder',
                'email_to' => $clientEmail,
                'subject' => $subject,
                'message' => $body,
                'status' => $sent ? 'sent' : 'failed',
                'sent_at' => $sent ? now() : null,
            ]);
            
            // Update last reminder sent
            if ($sent) {
                $service->last_reminder_sent = now();
                $service->saveQuietly();
            }
            
            return $sent;
            
        } catch (\Exception $e) {
            Log::error('Service reminder failed: ' . $e->getMessage(), [
                'service_id' => $service->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get reminder email body
     */
    protected function getReminderEmailBody(Service $service, string $type, int $daysUntil): string
    {
        $nextDate = $service->next_service_date ? $service->next_service_date->format('d M Y') : 'N/A';
        
        if ($daysUntil < 0) {
            $daysText = abs($daysUntil) . " days overdue";
            $statusColor = '#dc2626';
            $headerBg = 'linear-gradient(135deg, #dc2626 0%, #ef4444 100%)';
            $urgencyText = "Your service is <strong style='color:#dc2626;'>overdue</strong>. Please schedule immediately.";
        } elseif ($daysUntil == 0) {
            $daysText = "Due today";
            $statusColor = '#d97706';
            $headerBg = 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)';
            $urgencyText = "Your service is <strong style='color:#d97706;'>due today</strong>.";
        } elseif ($daysUntil <= 3) {
            $daysText = "{$daysUntil} days remaining";
            $statusColor = '#d97706';
            $headerBg = 'linear-gradient(135deg, #d97706 0%, #f59e0b 100%)';
            $urgencyText = "Your service is due in <strong>{$daysUntil} days</strong>. Please schedule soon.";
        } else {
            $daysText = "{$daysUntil} days remaining";
            $statusColor = '#16a34a';
            $headerBg = 'linear-gradient(135deg, #1e3a5f 0%, #3b82f6 100%)';
            $urgencyText = "Your scheduled service is approaching.";
        }

        return "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;'>
            <div style='background: {$headerBg}; padding: 20px; border-radius: 8px 8px 0 0;'>
                <h2 style='color: #fff; margin: 0;'>" . ($type === 'overdue' ? '⚠️ ' : '🔔 ') . "Service Reminder</h2>
            </div>
            
            <div style='background: #f8fafc; padding: 20px; border: 1px solid #e2e8f0;'>
                <p style='color: #1e293b;'>Dear <strong>{$service->client->name}</strong>,</p>
                
                <p style='color: #475569;'>{$urgencyText}</p>
                
                <table style='width: 100%; border-collapse: collapse; margin: 20px 0;'>
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b; width: 40%;'>Equipment</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b; font-weight: 600;'>{$service->machine_name}</td>
                    </tr>
                    " . ($service->serial_number ? "
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Serial No</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b;'>{$service->serial_number}</td>
                    </tr>" : "") . "
                    " . ($service->model_no ? "
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Model No</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b;'>{$service->model_no}</td>
                    </tr>" : "") . "
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Service Due Date</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b; font-weight: 600;'>{$nextDate}</td>
                    </tr>
                    <tr style='background: " . ($type === 'overdue' ? '#fee2e2' : '#fff') . ";'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Status</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: {$statusColor}; font-weight: 600;'>{$daysText}</td>
                    </tr>
                    <tr style='background: #fff;'>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #64748b;'>Service Frequency</td>
                        <td style='padding: 12px; border: 1px solid #e2e8f0; color: #1e293b;'>{$service->frequency_label}</td>
                    </tr>
                </table>
                
                <div style='background: " . ($type === 'overdue' ? '#fee2e2; border-left: 4px solid #dc2626;' : '#dbeafe; border-left: 4px solid #3b82f6;') . " padding: 15px; border-radius: 4px; margin: 20px 0;'>
                    <p style='margin: 0; color: " . ($type === 'overdue' ? '#991b1b' : '#1e40af') . ";'>
                        <strong>Action Required:</strong> Please contact us to schedule your service appointment.
                    </p>
                </div>
                
                <p style='color: #64748b; font-size: 12px; margin-top: 20px;'>
                    If you have already scheduled this service or have any questions, please contact our support team.
                </p>
            </div>
            
            <div style='background: #1e293b; padding: 15px; border-radius: 0 0 8px 8px; text-align: center;'>
                <p style='color: #94a3b8; margin: 0; font-size: 12px;'>This is an automated reminder from {company_name}</p>
            </div>
        </div>";
    }
}
