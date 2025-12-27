<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Option;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Your existing module views
        $this->loadViewsFrom(base_path('Modules/Core/Views'), 'core');

        // Apply mail settings from DB options (so it won't use mailpit)
        $this->applyMailConfigFromOptions();

        // menu issue 
        \Blade::directive('adminMenu', function ($expression) {
        return "<?php echo admin_menu($expression); ?>";
    });

    }

    protected function applyMailConfigFromOptions(): void
    {
        try {
            $mail = Option::mailConfig();

            // If host is empty, don't override anything
            if (empty($mail['host'])) {
                return;
            }

            $mailer = $mail['mailer'] ?: 'smtp';

            config([
                'mail.default' => $mailer,

                // SMTP mailer settings
                'mail.mailers.smtp.host' => $mail['host'],
                'mail.mailers.smtp.port' => (int) ($mail['port'] ?? 587),
                'mail.mailers.smtp.username' => $mail['username'] ?? null,
                'mail.mailers.smtp.password' => $mail['password'] ?? null,
                'mail.mailers.smtp.encryption' => $mail['encryption'] ?? 'tls',

                // Optional: set timeout to avoid hanging
                'mail.mailers.smtp.timeout' => 30,

                // From address/name
                'mail.from.address' => $mail['from_address'] ?: ($mail['username'] ?? null),
                'mail.from.name' => $mail['from_name'] ?: config('app.name'),
            ]);
        } catch (\Throwable $e) {
            // Do nothing. Avoid breaking the app if options table not ready during install.
        }
    }
}
