<?php

use App\Services\Admin\MailService;

if (!function_exists('send_mail')) {
    /**
     * Send email helper function
     *
     * @param string|array $to Recipient(s)
     * @param string $subject Subject
     * @param string $body Body (HTML)
     * @param array $options Options (cc, bcc, attachments, from_email, from_name, reply_to)
     * @return bool
     */
    function send_mail(string|array $to, string $subject, string $body, array $options = []): bool
    {
        return MailService::send($to, $subject, $body, $options);
    }
}

if (!function_exists('send_mail_view')) {
    /**
     * Send email using blade view
     */
    function send_mail_view(string|array $to, string $subject, string $view, array $data = [], array $options = []): bool
    {
        return MailService::sendView($to, $subject, $view, $data, $options);
    }
}

if (!function_exists('send_test_mail')) {
    /**
     * Send test email
     */
    function send_test_mail(string $to): array
    {
        return MailService::sendTest($to);
    }
}