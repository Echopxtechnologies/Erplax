<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;

class OptionsSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            // Company Settings
            [
                'key' => 'company_name',
                'value' => 'My Company',
                'group' => 'company',
                'type' => 'text',
                'label' => 'Company Name',
                'description' => 'Your company or business name',
                'autoload' => true,
            ],
            [
                'key' => 'company_email',
                'value' => 'info@example.com',
                'group' => 'company',
                'type' => 'text',
                'label' => 'Company Email',
                'description' => 'Main contact email address',
                'autoload' => true,
            ],
            [
                'key' => 'company_phone',
                'value' => '',
                'group' => 'company',
                'type' => 'text',
                'label' => 'Company Phone',
                'description' => 'Main contact phone number',
                'autoload' => true,
            ],
            [
                'key' => 'company_address',
                'value' => '',
                'group' => 'company',
                'type' => 'textarea',
                'label' => 'Company Address',
                'description' => 'Full business address',
                'autoload' => true,
            ],
            [
                'key' => 'company_logo',
                'value' => '',
                'group' => 'company',
                'type' => 'file',
                'label' => 'Company Logo',
                'description' => 'Logo displayed in header (recommended: 200x50px)',
                'autoload' => true,
            ],
            [
                'key' => 'company_favicon',
                'value' => '',
                'group' => 'company',
                'type' => 'file',
                'label' => 'Favicon',
                'description' => 'Browser tab icon (recommended: 32x32px)',
                'autoload' => true,
            ],
            [
                'key' => 'company_website',
                'value' => '',
                'group' => 'company',
                'type' => 'text',
                'label' => 'Website URL',
                'description' => 'Your company website',
                'autoload' => false,
            ],
            [
                'key' => 'company_gst',
                'value' => '',
                'group' => 'company',
                'type' => 'text',
                'label' => 'GST Number',
                'description' => 'GST/Tax identification number',
                'autoload' => false,
            ],

            // Mail Settings
            [
                'key' => 'mail_mailer',
                'value' => 'smtp',
                'group' => 'mail',
                'type' => 'text',
                'label' => 'Mail Driver',
                'description' => 'smtp, sendmail, mailgun, ses, postmark',
                'autoload' => true,
            ],
            [
                'key' => 'mail_host',
                'value' => 'smtp.gmail.com',
                'group' => 'mail',
                'type' => 'text',
                'label' => 'SMTP Host',
                'description' => 'SMTP server address',
                'autoload' => true,
            ],
            [
                'key' => 'mail_port',
                'value' => '587',
                'group' => 'mail',
                'type' => 'number',
                'label' => 'SMTP Port',
                'description' => 'Usually 587 for TLS or 465 for SSL',
                'autoload' => true,
            ],
            [
                'key' => 'mail_username',
                'value' => '',
                'group' => 'mail',
                'type' => 'text',
                'label' => 'SMTP Username',
                'description' => 'Your email username',
                'autoload' => true,
            ],
            [
                'key' => 'mail_password',
                'value' => '',
                'group' => 'mail',
                'type' => 'password',
                'label' => 'SMTP Password',
                'description' => 'Your email password or app password',
                'autoload' => true,
            ],
            [
                'key' => 'mail_encryption',
                'value' => 'tls',
                'group' => 'mail',
                'type' => 'text',
                'label' => 'Encryption',
                'description' => 'tls or ssl',
                'autoload' => true,
            ],
            [
                'key' => 'mail_from_address',
                'value' => 'noreply@example.com',
                'group' => 'mail',
                'type' => 'text',
                'label' => 'From Address',
                'description' => 'Default sender email address',
                'autoload' => true,
            ],
            [
                'key' => 'mail_from_name',
                'value' => 'My Company',
                'group' => 'mail',
                'type' => 'text',
                'label' => 'From Name',
                'description' => 'Default sender name',
                'autoload' => true,
            ],

            // General Settings
            [
                'key' => 'site_timezone',
                'value' => 'Asia/Kolkata',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Timezone',
                'description' => 'System timezone',
                'autoload' => true,
            ],
            [
                'key' => 'date_format',
                'value' => 'd/m/Y',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Date Format',
                'description' => 'PHP date format (d/m/Y, Y-m-d, etc.)',
                'autoload' => true,
            ],
            [
                'key' => 'time_format',
                'value' => 'h:i A',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Time Format',
                'description' => 'PHP time format (H:i, h:i A)',
                'autoload' => true,
            ],
            [
                'key' => 'currency_symbol',
                'value' => '₹',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Currency Symbol',
                'description' => 'Default currency symbol',
                'autoload' => true,
            ],
            [
                'key' => 'currency_code',
                'value' => 'INR',
                'group' => 'general',
                'type' => 'text',
                'label' => 'Currency Code',
                'description' => 'ISO currency code (INR, USD, EUR)',
                'autoload' => true,
            ],
            [
                'key' => 'pagination_limit',
                'value' => '10',
                'group' => 'general',
                'type' => 'number',
                'label' => 'Pagination Limit',
                'description' => 'Default items per page',
                'autoload' => true,
            ],
        ];

        foreach ($options as $option) {
            Option::updateOrCreate(
                ['key' => $option['key']],
                $option
            );
        }
    }
}