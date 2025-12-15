<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        
        $currencies = [
            // Major World Currencies
            ['id' => 1, 'name' => 'Indian Rupee', 'code' => 'INR', 'symbol' => '₹', 'symbol_native' => '₹', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 1.000000, 'is_default' => true, 'is_active' => true],
            ['id' => 2, 'name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'symbol_native' => '$', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.012000, 'is_default' => false, 'is_active' => true],
            ['id' => 3, 'name' => 'Euro', 'code' => 'EUR', 'symbol' => '€', 'symbol_native' => '€', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => '.', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.011000, 'is_default' => false, 'is_active' => true],
            ['id' => 4, 'name' => 'British Pound', 'code' => 'GBP', 'symbol' => '£', 'symbol_native' => '£', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.009500, 'is_default' => false, 'is_active' => true],
            ['id' => 5, 'name' => 'Japanese Yen', 'code' => 'JPY', 'symbol' => '¥', 'symbol_native' => '￥', 'decimal_digits' => 0, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 1.800000, 'is_default' => false, 'is_active' => true],
            ['id' => 6, 'name' => 'Chinese Yuan', 'code' => 'CNY', 'symbol' => '¥', 'symbol_native' => '¥', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.087000, 'is_default' => false, 'is_active' => true],
            ['id' => 7, 'name' => 'Australian Dollar', 'code' => 'AUD', 'symbol' => 'A$', 'symbol_native' => '$', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.018500, 'is_default' => false, 'is_active' => true],
            ['id' => 8, 'name' => 'Canadian Dollar', 'code' => 'CAD', 'symbol' => 'C$', 'symbol_native' => '$', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.016300, 'is_default' => false, 'is_active' => true],
            ['id' => 9, 'name' => 'Swiss Franc', 'code' => 'CHF', 'symbol' => 'CHF', 'symbol_native' => 'Fr.', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => '\'', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 0.010700, 'is_default' => false, 'is_active' => true],
            ['id' => 10, 'name' => 'Hong Kong Dollar', 'code' => 'HKD', 'symbol' => 'HK$', 'symbol_native' => '$', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.094000, 'is_default' => false, 'is_active' => true],
            
            // Asian Currencies
            ['id' => 11, 'name' => 'Singapore Dollar', 'code' => 'SGD', 'symbol' => 'S$', 'symbol_native' => '$', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.016100, 'is_default' => false, 'is_active' => true],
            ['id' => 12, 'name' => 'South Korean Won', 'code' => 'KRW', 'symbol' => '₩', 'symbol_native' => '₩', 'decimal_digits' => 0, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 15.800000, 'is_default' => false, 'is_active' => true],
            ['id' => 13, 'name' => 'Thai Baht', 'code' => 'THB', 'symbol' => '฿', 'symbol_native' => '฿', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.420000, 'is_default' => false, 'is_active' => true],
            ['id' => 14, 'name' => 'Malaysian Ringgit', 'code' => 'MYR', 'symbol' => 'RM', 'symbol_native' => 'RM', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.056000, 'is_default' => false, 'is_active' => true],
            ['id' => 15, 'name' => 'Indonesian Rupiah', 'code' => 'IDR', 'symbol' => 'Rp', 'symbol_native' => 'Rp', 'decimal_digits' => 0, 'decimal_separator' => ',', 'thousand_separator' => '.', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 189.000000, 'is_default' => false, 'is_active' => true],
            ['id' => 16, 'name' => 'Philippine Peso', 'code' => 'PHP', 'symbol' => '₱', 'symbol_native' => '₱', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.670000, 'is_default' => false, 'is_active' => true],
            ['id' => 17, 'name' => 'Vietnamese Dong', 'code' => 'VND', 'symbol' => '₫', 'symbol_native' => '₫', 'decimal_digits' => 0, 'decimal_separator' => ',', 'thousand_separator' => '.', 'symbol_position' => 'after', 'space_between' => true, 'exchange_rate' => 295.000000, 'is_default' => false, 'is_active' => true],
            ['id' => 18, 'name' => 'Pakistani Rupee', 'code' => 'PKR', 'symbol' => '₨', 'symbol_native' => '₨', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 3.350000, 'is_default' => false, 'is_active' => true],
            ['id' => 19, 'name' => 'Bangladeshi Taka', 'code' => 'BDT', 'symbol' => '৳', 'symbol_native' => '৳', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 1.320000, 'is_default' => false, 'is_active' => true],
            ['id' => 20, 'name' => 'Sri Lankan Rupee', 'code' => 'LKR', 'symbol' => 'Rs', 'symbol_native' => 'රු', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 3.900000, 'is_default' => false, 'is_active' => true],
            ['id' => 21, 'name' => 'Nepalese Rupee', 'code' => 'NPR', 'symbol' => 'रू', 'symbol_native' => 'रू', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 1.600000, 'is_default' => false, 'is_active' => true],
            
            // Middle East Currencies
            ['id' => 22, 'name' => 'UAE Dirham', 'code' => 'AED', 'symbol' => 'د.إ', 'symbol_native' => 'د.إ', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 0.044000, 'is_default' => false, 'is_active' => true],
            ['id' => 23, 'name' => 'Saudi Riyal', 'code' => 'SAR', 'symbol' => '﷼', 'symbol_native' => 'ر.س', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 0.045000, 'is_default' => false, 'is_active' => true],
            ['id' => 24, 'name' => 'Qatari Riyal', 'code' => 'QAR', 'symbol' => 'ر.ق', 'symbol_native' => 'ر.ق', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 0.044000, 'is_default' => false, 'is_active' => true],
            ['id' => 25, 'name' => 'Kuwaiti Dinar', 'code' => 'KWD', 'symbol' => 'د.ك', 'symbol_native' => 'د.ك', 'decimal_digits' => 3, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 0.003700, 'is_default' => false, 'is_active' => true],
            ['id' => 26, 'name' => 'Bahraini Dinar', 'code' => 'BHD', 'symbol' => '.د.ب', 'symbol_native' => 'د.ب', 'decimal_digits' => 3, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 0.004500, 'is_default' => false, 'is_active' => true],
            ['id' => 27, 'name' => 'Omani Rial', 'code' => 'OMR', 'symbol' => 'ر.ع.', 'symbol_native' => 'ر.ع.', 'decimal_digits' => 3, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 0.004600, 'is_default' => false, 'is_active' => true],
            ['id' => 28, 'name' => 'Israeli Shekel', 'code' => 'ILS', 'symbol' => '₪', 'symbol_native' => '₪', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 0.044000, 'is_default' => false, 'is_active' => true],
            ['id' => 29, 'name' => 'Turkish Lira', 'code' => 'TRY', 'symbol' => '₺', 'symbol_native' => '₺', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => '.', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.350000, 'is_default' => false, 'is_active' => true],
            
            // European Currencies
            ['id' => 30, 'name' => 'Russian Ruble', 'code' => 'RUB', 'symbol' => '₽', 'symbol_native' => '₽', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => ' ', 'symbol_position' => 'after', 'space_between' => true, 'exchange_rate' => 1.100000, 'is_default' => false, 'is_active' => true],
            ['id' => 31, 'name' => 'Polish Zloty', 'code' => 'PLN', 'symbol' => 'zł', 'symbol_native' => 'zł', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => ' ', 'symbol_position' => 'after', 'space_between' => true, 'exchange_rate' => 0.048000, 'is_default' => false, 'is_active' => true],
            ['id' => 32, 'name' => 'Swedish Krona', 'code' => 'SEK', 'symbol' => 'kr', 'symbol_native' => 'kr', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => ' ', 'symbol_position' => 'after', 'space_between' => true, 'exchange_rate' => 0.125000, 'is_default' => false, 'is_active' => true],
            ['id' => 33, 'name' => 'Norwegian Krone', 'code' => 'NOK', 'symbol' => 'kr', 'symbol_native' => 'kr', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => ' ', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 0.128000, 'is_default' => false, 'is_active' => true],
            ['id' => 34, 'name' => 'Danish Krone', 'code' => 'DKK', 'symbol' => 'kr', 'symbol_native' => 'kr', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => '.', 'symbol_position' => 'after', 'space_between' => true, 'exchange_rate' => 0.083000, 'is_default' => false, 'is_active' => true],
            ['id' => 35, 'name' => 'Czech Koruna', 'code' => 'CZK', 'symbol' => 'Kč', 'symbol_native' => 'Kč', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => ' ', 'symbol_position' => 'after', 'space_between' => true, 'exchange_rate' => 0.275000, 'is_default' => false, 'is_active' => true],
            ['id' => 36, 'name' => 'Hungarian Forint', 'code' => 'HUF', 'symbol' => 'Ft', 'symbol_native' => 'Ft', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => ' ', 'symbol_position' => 'after', 'space_between' => true, 'exchange_rate' => 4.300000, 'is_default' => false, 'is_active' => true],
            ['id' => 37, 'name' => 'Romanian Leu', 'code' => 'RON', 'symbol' => 'lei', 'symbol_native' => 'lei', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => '.', 'symbol_position' => 'after', 'space_between' => true, 'exchange_rate' => 0.055000, 'is_default' => false, 'is_active' => true],
            ['id' => 38, 'name' => 'Ukrainian Hryvnia', 'code' => 'UAH', 'symbol' => '₴', 'symbol_native' => '₴', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => ' ', 'symbol_position' => 'after', 'space_between' => false, 'exchange_rate' => 0.440000, 'is_default' => false, 'is_active' => true],
            
            // Americas
            ['id' => 39, 'name' => 'Mexican Peso', 'code' => 'MXN', 'symbol' => '$', 'symbol_native' => '$', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.205000, 'is_default' => false, 'is_active' => true],
            ['id' => 40, 'name' => 'Brazilian Real', 'code' => 'BRL', 'symbol' => 'R$', 'symbol_native' => 'R$', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => '.', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 0.059000, 'is_default' => false, 'is_active' => true],
            ['id' => 41, 'name' => 'Argentine Peso', 'code' => 'ARS', 'symbol' => '$', 'symbol_native' => '$', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => '.', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 10.300000, 'is_default' => false, 'is_active' => true],
            ['id' => 42, 'name' => 'Chilean Peso', 'code' => 'CLP', 'symbol' => '$', 'symbol_native' => '$', 'decimal_digits' => 0, 'decimal_separator' => ',', 'thousand_separator' => '.', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 11.200000, 'is_default' => false, 'is_active' => true],
            ['id' => 43, 'name' => 'Colombian Peso', 'code' => 'COP', 'symbol' => '$', 'symbol_native' => '$', 'decimal_digits' => 2, 'decimal_separator' => ',', 'thousand_separator' => '.', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 47.500000, 'is_default' => false, 'is_active' => true],
            ['id' => 44, 'name' => 'Peruvian Sol', 'code' => 'PEN', 'symbol' => 'S/', 'symbol_native' => 'S/', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 0.045000, 'is_default' => false, 'is_active' => true],
            
            // Africa & Others
            ['id' => 45, 'name' => 'South African Rand', 'code' => 'ZAR', 'symbol' => 'R', 'symbol_native' => 'R', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.220000, 'is_default' => false, 'is_active' => true],
            ['id' => 46, 'name' => 'Egyptian Pound', 'code' => 'EGP', 'symbol' => 'E£', 'symbol_native' => 'ج.م', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => true, 'exchange_rate' => 0.370000, 'is_default' => false, 'is_active' => true],
            ['id' => 47, 'name' => 'Nigerian Naira', 'code' => 'NGN', 'symbol' => '₦', 'symbol_native' => '₦', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 9.500000, 'is_default' => false, 'is_active' => true],
            ['id' => 48, 'name' => 'Kenyan Shilling', 'code' => 'KES', 'symbol' => 'KSh', 'symbol_native' => 'KSh', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 1.850000, 'is_default' => false, 'is_active' => true],
            ['id' => 49, 'name' => 'New Zealand Dollar', 'code' => 'NZD', 'symbol' => 'NZ$', 'symbol_native' => '$', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.020000, 'is_default' => false, 'is_active' => true],
            ['id' => 50, 'name' => 'Taiwan Dollar', 'code' => 'TWD', 'symbol' => 'NT$', 'symbol_native' => 'NT$', 'decimal_digits' => 2, 'decimal_separator' => '.', 'thousand_separator' => ',', 'symbol_position' => 'before', 'space_between' => false, 'exchange_rate' => 0.380000, 'is_default' => false, 'is_active' => true],
        ];

        foreach ($currencies as &$currency) {
            $currency['created_at'] = $now;
            $currency['updated_at'] = $now;
        }

        DB::table('currencies')->insert($currencies);
    }
}