<?php

namespace App\Services\Admin;

use App\Models\Option;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;

class MailService
{
    /**
     * Available template variables
     */
    protected static function getVariables(array $extra = []): array
    {
        return array_merge([
            '{company_name}' => Option::companyName(),
            '{company_email}' => Option::companyEmail() ?? '',
            '{company_phone}' => Option::companyPhone() ?? '',
            '{company_address}' => Option::companyAddress() ?? '',
            '{date}' => now()->format(Option::get('date_format', 'd/m/Y')),
            '{time}' => now()->format(Option::get('time_format', 'h:i A')),
            '{date_time}' => now()->format(Option::get('date_format', 'd/m/Y') . ' ' . Option::get('time_format', 'h:i A')),
            '{year}' => now()->format('Y'),
        ], $extra);
    }

    /**
     * Replace variables in template
     */
    public static function replaceVariables(string $template, array $extra = []): string
    {
        $variables = static::getVariables($extra);
        return str_replace(array_keys($variables), array_values($variables), $template);
    }

    /**
     * Get email footer with variables replaced
     */
    public static function getFooter(): string
    {
        $footer = Option::get('mail_footer', '');
        return $footer ? static::replaceVariables($footer) : '';
    }

    /**
     * Configure mail settings from database options
     */
    public static function configure(): void
    {
        $config = Option::mailConfig();

        Config::set('mail.default', $config['mailer']);
        Config::set('mail.mailers.smtp.host', $config['host']);
        Config::set('mail.mailers.smtp.port', $config['port']);
        Config::set('mail.mailers.smtp.username', $config['username']);
        Config::set('mail.mailers.smtp.password', $config['password']);
        Config::set('mail.mailers.smtp.encryption', $config['encryption'] === 'null' ? null : $config['encryption']);
        Config::set('mail.from.address', $config['from_address']);
        Config::set('mail.from.name', $config['from_name']);
    }

    /**
     * Send email with all options
     */
    public static function send(
        string|array $to,
        string $subject,
        string $body = '',
        array $options = []
    ): bool {
        try {
            static::configure();

            $config = Option::mailConfig();
            $to = is_array($to) ? $to : [$to];

            // Replace variables in subject and body
            $extraVars = ['{recipient_email}' => is_array($to) ? $to[0] : $to];
            $subject = static::replaceVariables($subject, $extraVars);
            $body = static::replaceVariables($body, $extraVars);

            // Append footer if enabled
            $appendFooter = $options['append_footer'] ?? true;
            if ($appendFooter) {
                $footer = static::getFooter();
                if ($footer) {
                    $body .= $footer;
                }
            }

            Mail::send([], [], function (Message $message) use ($to, $subject, $body, $options, $config) {
                $message->to($to);
                $message->subject($subject);

                $fromEmail = $options['from_email'] ?? $config['from_address'];
                $fromName = $options['from_name'] ?? $config['from_name'];
                if ($fromEmail) {
                    $message->from($fromEmail, $fromName);
                }

                if (!empty($options['reply_to'])) {
                    $message->replyTo($options['reply_to']);
                }

                if (!empty($options['cc'])) {
                    $cc = is_array($options['cc']) ? $options['cc'] : [$options['cc']];
                    $message->cc($cc);
                }

                if (!empty($options['bcc'])) {
                    $bcc = is_array($options['bcc']) ? $options['bcc'] : [$options['bcc']];
                    $message->bcc($bcc);
                }

                $isHtml = $options['is_html'] ?? true;
                if ($isHtml) {
                    $message->html($body);
                } else {
                    $message->text($body);
                }

                if (!empty($options['attachments'])) {
                    foreach ((array) $options['attachments'] as $attachment) {
                        if (is_string($attachment)) {
                            if (file_exists($attachment)) {
                                $message->attach($attachment);
                            } elseif (file_exists(storage_path('app/' . $attachment))) {
                                $message->attach(storage_path('app/' . $attachment));
                            } elseif (file_exists(public_path($attachment))) {
                                $message->attach(public_path($attachment));
                            }
                        } elseif (is_object($attachment) && method_exists($attachment, 'getRealPath')) {
                            $message->attach($attachment->getRealPath(), [
                                'as' => $attachment->getClientOriginalName(),
                                'mime' => $attachment->getMimeType(),
                            ]);
                        } elseif (is_array($attachment)) {
                            $message->attach($attachment['path'], [
                                'as' => $attachment['name'] ?? basename($attachment['path']),
                                'mime' => $attachment['mime'] ?? null,
                            ]);
                        }
                    }
                }
            });

            Log::info('[MailService] Email sent', [
                'to' => $to,
                'subject' => $subject,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('[MailService] Failed to send email', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Send email using a blade view
     */
    public static function sendView(
        string|array $to,
        string $subject,
        string $view,
        array $data = [],
        array $options = []
    ): bool {
        try {
            $body = view($view, $data)->render();
            return static::send($to, $subject, $body, $options);
        } catch (\Exception $e) {
            Log::error('[MailService] Failed to render view', [
                'view' => $view,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send test email using saved template
     */
    public static function sendTest(string $to): array
    {
        // Get saved template or use defaults
        $subject = Option::get('mail_test_subject', 'Test Email - {company_name}');
        $body = Option::get('mail_test_body', static::getDefaultTestBody());

        $success = static::send($to, $subject, $body);

        return [
            'success' => $success,
            'message' => $success 
                ? "Test email sent successfully to {$to}" 
                : "Failed to send test email to {$to}. Check your mail settings and logs.",
        ];
    }

    /**
     * Default test email body
     */
    protected static function getDefaultTestBody(): string
    {
        return '<div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
            <h2 style="color: #333;">Test Email</h2>
            <p>Hello,</p>
            <p>This is a test email from <strong>{company_name}</strong>.</p>
            <p>If you received this email, your mail settings are configured correctly!</p>
            <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
            <p style="color: #666; font-size: 12px;">Sent at: {date_time}</p>
        </div>';
    }

    /**
     * Check if mail is configured
     */
    public static function isConfigured(): bool
    {
        $config = Option::mailConfig();
        return !empty($config['host']) && !empty($config['from_address']);
    }

    /**
     * Get configuration status
     */
    public static function getStatus(): array
    {
        $config = Option::mailConfig();
        
        return [
            'configured' => static::isConfigured(),
            'mailer' => $config['mailer'],
            'host' => $config['host'] ?: '(not set)',
            'port' => $config['port'],
            'username' => $config['username'] ? '(set)' : '(not set)',
            'password' => $config['password'] ? '(set)' : '(not set)',
            'encryption' => $config['encryption'] ?: 'none',
            'from_address' => $config['from_address'] ?: '(not set)',
            'from_name' => $config['from_name'] ?: '(not set)',
        ];
    }
}