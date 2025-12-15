<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimezoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timezones = [
            // UTC-12:00 to UTC-09:00
            ['id' => 1, 'name' => 'Pacific/Midway', 'label' => '(UTC-11:00) Midway Island, Samoa', 'offset' => '-11:00', 'offset_minutes' => -660, 'country_code' => 'UM', 'is_active' => true],
            ['id' => 2, 'name' => 'Pacific/Honolulu', 'label' => '(UTC-10:00) Hawaii', 'offset' => '-10:00', 'offset_minutes' => -600, 'country_code' => 'US', 'is_active' => true],
            ['id' => 3, 'name' => 'America/Anchorage', 'label' => '(UTC-09:00) Alaska', 'offset' => '-09:00', 'offset_minutes' => -540, 'country_code' => 'US', 'is_active' => true],
            
            // UTC-08:00 Pacific
            ['id' => 4, 'name' => 'America/Los_Angeles', 'label' => '(UTC-08:00) Pacific Time (US & Canada)', 'offset' => '-08:00', 'offset_minutes' => -480, 'country_code' => 'US', 'is_active' => true],
            ['id' => 5, 'name' => 'America/Tijuana', 'label' => '(UTC-08:00) Tijuana, Baja California', 'offset' => '-08:00', 'offset_minutes' => -480, 'country_code' => 'MX', 'is_active' => true],
            ['id' => 6, 'name' => 'America/Vancouver', 'label' => '(UTC-08:00) Vancouver', 'offset' => '-08:00', 'offset_minutes' => -480, 'country_code' => 'CA', 'is_active' => true],
            
            // UTC-07:00 Mountain
            ['id' => 7, 'name' => 'America/Denver', 'label' => '(UTC-07:00) Mountain Time (US & Canada)', 'offset' => '-07:00', 'offset_minutes' => -420, 'country_code' => 'US', 'is_active' => true],
            ['id' => 8, 'name' => 'America/Phoenix', 'label' => '(UTC-07:00) Arizona', 'offset' => '-07:00', 'offset_minutes' => -420, 'country_code' => 'US', 'is_active' => true],
            ['id' => 9, 'name' => 'America/Edmonton', 'label' => '(UTC-07:00) Edmonton', 'offset' => '-07:00', 'offset_minutes' => -420, 'country_code' => 'CA', 'is_active' => true],
            
            // UTC-06:00 Central
            ['id' => 10, 'name' => 'America/Chicago', 'label' => '(UTC-06:00) Central Time (US & Canada)', 'offset' => '-06:00', 'offset_minutes' => -360, 'country_code' => 'US', 'is_active' => true],
            ['id' => 11, 'name' => 'America/Mexico_City', 'label' => '(UTC-06:00) Mexico City', 'offset' => '-06:00', 'offset_minutes' => -360, 'country_code' => 'MX', 'is_active' => true],
            ['id' => 12, 'name' => 'America/Guatemala', 'label' => '(UTC-06:00) Central America', 'offset' => '-06:00', 'offset_minutes' => -360, 'country_code' => 'GT', 'is_active' => true],
            ['id' => 13, 'name' => 'America/Winnipeg', 'label' => '(UTC-06:00) Winnipeg', 'offset' => '-06:00', 'offset_minutes' => -360, 'country_code' => 'CA', 'is_active' => true],
            
            // UTC-05:00 Eastern
            ['id' => 14, 'name' => 'America/New_York', 'label' => '(UTC-05:00) Eastern Time (US & Canada)', 'offset' => '-05:00', 'offset_minutes' => -300, 'country_code' => 'US', 'is_active' => true],
            ['id' => 15, 'name' => 'America/Toronto', 'label' => '(UTC-05:00) Toronto', 'offset' => '-05:00', 'offset_minutes' => -300, 'country_code' => 'CA', 'is_active' => true],
            ['id' => 16, 'name' => 'America/Bogota', 'label' => '(UTC-05:00) Bogota, Lima, Quito', 'offset' => '-05:00', 'offset_minutes' => -300, 'country_code' => 'CO', 'is_active' => true],
            ['id' => 17, 'name' => 'America/Panama', 'label' => '(UTC-05:00) Panama', 'offset' => '-05:00', 'offset_minutes' => -300, 'country_code' => 'PA', 'is_active' => true],
            ['id' => 18, 'name' => 'America/Havana', 'label' => '(UTC-05:00) Havana', 'offset' => '-05:00', 'offset_minutes' => -300, 'country_code' => 'CU', 'is_active' => true],
            
            // UTC-04:00 Atlantic
            ['id' => 19, 'name' => 'America/Halifax', 'label' => '(UTC-04:00) Atlantic Time (Canada)', 'offset' => '-04:00', 'offset_minutes' => -240, 'country_code' => 'CA', 'is_active' => true],
            ['id' => 20, 'name' => 'America/Caracas', 'label' => '(UTC-04:00) Caracas', 'offset' => '-04:00', 'offset_minutes' => -240, 'country_code' => 'VE', 'is_active' => true],
            ['id' => 21, 'name' => 'America/Santiago', 'label' => '(UTC-04:00) Santiago', 'offset' => '-04:00', 'offset_minutes' => -240, 'country_code' => 'CL', 'is_active' => true],
            ['id' => 22, 'name' => 'America/La_Paz', 'label' => '(UTC-04:00) La Paz', 'offset' => '-04:00', 'offset_minutes' => -240, 'country_code' => 'BO', 'is_active' => true],
            
            // UTC-03:00 South America
            ['id' => 23, 'name' => 'America/Sao_Paulo', 'label' => '(UTC-03:00) Brasilia, Sao Paulo', 'offset' => '-03:00', 'offset_minutes' => -180, 'country_code' => 'BR', 'is_active' => true],
            ['id' => 24, 'name' => 'America/Argentina/Buenos_Aires', 'label' => '(UTC-03:00) Buenos Aires', 'offset' => '-03:00', 'offset_minutes' => -180, 'country_code' => 'AR', 'is_active' => true],
            ['id' => 25, 'name' => 'America/Montevideo', 'label' => '(UTC-03:00) Montevideo', 'offset' => '-03:00', 'offset_minutes' => -180, 'country_code' => 'UY', 'is_active' => true],
            ['id' => 26, 'name' => 'Atlantic/Stanley', 'label' => '(UTC-03:00) Falkland Islands', 'offset' => '-03:00', 'offset_minutes' => -180, 'country_code' => 'FK', 'is_active' => true],
            
            // UTC-02:00 to UTC-01:00
            ['id' => 27, 'name' => 'Atlantic/South_Georgia', 'label' => '(UTC-02:00) Mid-Atlantic', 'offset' => '-02:00', 'offset_minutes' => -120, 'country_code' => 'GS', 'is_active' => true],
            ['id' => 28, 'name' => 'Atlantic/Azores', 'label' => '(UTC-01:00) Azores', 'offset' => '-01:00', 'offset_minutes' => -60, 'country_code' => 'PT', 'is_active' => true],
            ['id' => 29, 'name' => 'Atlantic/Cape_Verde', 'label' => '(UTC-01:00) Cape Verde Islands', 'offset' => '-01:00', 'offset_minutes' => -60, 'country_code' => 'CV', 'is_active' => true],
            
            // UTC+00:00 GMT/UTC
            ['id' => 30, 'name' => 'UTC', 'label' => '(UTC+00:00) Coordinated Universal Time', 'offset' => '+00:00', 'offset_minutes' => 0, 'country_code' => null, 'is_active' => true],
            ['id' => 31, 'name' => 'Europe/London', 'label' => '(UTC+00:00) London, Edinburgh, Dublin', 'offset' => '+00:00', 'offset_minutes' => 0, 'country_code' => 'GB', 'is_active' => true],
            ['id' => 32, 'name' => 'Europe/Lisbon', 'label' => '(UTC+00:00) Lisbon', 'offset' => '+00:00', 'offset_minutes' => 0, 'country_code' => 'PT', 'is_active' => true],
            ['id' => 33, 'name' => 'Africa/Casablanca', 'label' => '(UTC+00:00) Casablanca', 'offset' => '+00:00', 'offset_minutes' => 0, 'country_code' => 'MA', 'is_active' => true],
            ['id' => 34, 'name' => 'Africa/Accra', 'label' => '(UTC+00:00) Accra, Monrovia', 'offset' => '+00:00', 'offset_minutes' => 0, 'country_code' => 'GH', 'is_active' => true],
            
            // UTC+01:00 Central Europe
            ['id' => 35, 'name' => 'Europe/Paris', 'label' => '(UTC+01:00) Paris, Brussels, Madrid', 'offset' => '+01:00', 'offset_minutes' => 60, 'country_code' => 'FR', 'is_active' => true],
            ['id' => 36, 'name' => 'Europe/Berlin', 'label' => '(UTC+01:00) Berlin, Amsterdam, Rome', 'offset' => '+01:00', 'offset_minutes' => 60, 'country_code' => 'DE', 'is_active' => true],
            ['id' => 37, 'name' => 'Europe/Warsaw', 'label' => '(UTC+01:00) Warsaw, Prague, Vienna', 'offset' => '+01:00', 'offset_minutes' => 60, 'country_code' => 'PL', 'is_active' => true],
            ['id' => 38, 'name' => 'Africa/Lagos', 'label' => '(UTC+01:00) Lagos, West Central Africa', 'offset' => '+01:00', 'offset_minutes' => 60, 'country_code' => 'NG', 'is_active' => true],
            ['id' => 39, 'name' => 'Africa/Algiers', 'label' => '(UTC+01:00) Algiers', 'offset' => '+01:00', 'offset_minutes' => 60, 'country_code' => 'DZ', 'is_active' => true],
            
            // UTC+02:00 Eastern Europe
            ['id' => 40, 'name' => 'Europe/Athens', 'label' => '(UTC+02:00) Athens, Bucharest', 'offset' => '+02:00', 'offset_minutes' => 120, 'country_code' => 'GR', 'is_active' => true],
            ['id' => 41, 'name' => 'Europe/Helsinki', 'label' => '(UTC+02:00) Helsinki, Kyiv, Riga', 'offset' => '+02:00', 'offset_minutes' => 120, 'country_code' => 'FI', 'is_active' => true],
            ['id' => 42, 'name' => 'Europe/Istanbul', 'label' => '(UTC+03:00) Istanbul', 'offset' => '+03:00', 'offset_minutes' => 180, 'country_code' => 'TR', 'is_active' => true],
            ['id' => 43, 'name' => 'Africa/Cairo', 'label' => '(UTC+02:00) Cairo', 'offset' => '+02:00', 'offset_minutes' => 120, 'country_code' => 'EG', 'is_active' => true],
            ['id' => 44, 'name' => 'Africa/Johannesburg', 'label' => '(UTC+02:00) Johannesburg, Harare', 'offset' => '+02:00', 'offset_minutes' => 120, 'country_code' => 'ZA', 'is_active' => true],
            ['id' => 45, 'name' => 'Asia/Jerusalem', 'label' => '(UTC+02:00) Jerusalem', 'offset' => '+02:00', 'offset_minutes' => 120, 'country_code' => 'IL', 'is_active' => true],
            ['id' => 46, 'name' => 'Asia/Beirut', 'label' => '(UTC+02:00) Beirut', 'offset' => '+02:00', 'offset_minutes' => 120, 'country_code' => 'LB', 'is_active' => true],
            
            // UTC+03:00 Middle East / East Africa
            ['id' => 47, 'name' => 'Europe/Moscow', 'label' => '(UTC+03:00) Moscow, St. Petersburg', 'offset' => '+03:00', 'offset_minutes' => 180, 'country_code' => 'RU', 'is_active' => true],
            ['id' => 48, 'name' => 'Asia/Baghdad', 'label' => '(UTC+03:00) Baghdad', 'offset' => '+03:00', 'offset_minutes' => 180, 'country_code' => 'IQ', 'is_active' => true],
            ['id' => 49, 'name' => 'Asia/Kuwait', 'label' => '(UTC+03:00) Kuwait, Riyadh', 'offset' => '+03:00', 'offset_minutes' => 180, 'country_code' => 'KW', 'is_active' => true],
            ['id' => 50, 'name' => 'Africa/Nairobi', 'label' => '(UTC+03:00) Nairobi', 'offset' => '+03:00', 'offset_minutes' => 180, 'country_code' => 'KE', 'is_active' => true],
            ['id' => 51, 'name' => 'Asia/Qatar', 'label' => '(UTC+03:00) Qatar', 'offset' => '+03:00', 'offset_minutes' => 180, 'country_code' => 'QA', 'is_active' => true],
            
            // UTC+03:30
            ['id' => 52, 'name' => 'Asia/Tehran', 'label' => '(UTC+03:30) Tehran', 'offset' => '+03:30', 'offset_minutes' => 210, 'country_code' => 'IR', 'is_active' => true],
            
            // UTC+04:00 Gulf
            ['id' => 53, 'name' => 'Asia/Dubai', 'label' => '(UTC+04:00) Dubai, Abu Dhabi, Muscat', 'offset' => '+04:00', 'offset_minutes' => 240, 'country_code' => 'AE', 'is_active' => true],
            ['id' => 54, 'name' => 'Asia/Baku', 'label' => '(UTC+04:00) Baku', 'offset' => '+04:00', 'offset_minutes' => 240, 'country_code' => 'AZ', 'is_active' => true],
            ['id' => 55, 'name' => 'Asia/Tbilisi', 'label' => '(UTC+04:00) Tbilisi', 'offset' => '+04:00', 'offset_minutes' => 240, 'country_code' => 'GE', 'is_active' => true],
            ['id' => 56, 'name' => 'Asia/Yerevan', 'label' => '(UTC+04:00) Yerevan', 'offset' => '+04:00', 'offset_minutes' => 240, 'country_code' => 'AM', 'is_active' => true],
            ['id' => 57, 'name' => 'Indian/Mauritius', 'label' => '(UTC+04:00) Mauritius', 'offset' => '+04:00', 'offset_minutes' => 240, 'country_code' => 'MU', 'is_active' => true],
            
            // UTC+04:30
            ['id' => 58, 'name' => 'Asia/Kabul', 'label' => '(UTC+04:30) Kabul', 'offset' => '+04:30', 'offset_minutes' => 270, 'country_code' => 'AF', 'is_active' => true],
            
            // UTC+05:00 West Asia
            ['id' => 59, 'name' => 'Asia/Karachi', 'label' => '(UTC+05:00) Karachi, Islamabad', 'offset' => '+05:00', 'offset_minutes' => 300, 'country_code' => 'PK', 'is_active' => true],
            ['id' => 60, 'name' => 'Asia/Tashkent', 'label' => '(UTC+05:00) Tashkent', 'offset' => '+05:00', 'offset_minutes' => 300, 'country_code' => 'UZ', 'is_active' => true],
            ['id' => 61, 'name' => 'Asia/Yekaterinburg', 'label' => '(UTC+05:00) Yekaterinburg', 'offset' => '+05:00', 'offset_minutes' => 300, 'country_code' => 'RU', 'is_active' => true],
            
            // UTC+05:30 India
            ['id' => 62, 'name' => 'Asia/Kolkata', 'label' => '(UTC+05:30) Chennai, Kolkata, Mumbai, New Delhi', 'offset' => '+05:30', 'offset_minutes' => 330, 'country_code' => 'IN', 'is_active' => true],
            ['id' => 63, 'name' => 'Asia/Colombo', 'label' => '(UTC+05:30) Sri Jayawardenepura', 'offset' => '+05:30', 'offset_minutes' => 330, 'country_code' => 'LK', 'is_active' => true],
            
            // UTC+05:45
            ['id' => 64, 'name' => 'Asia/Kathmandu', 'label' => '(UTC+05:45) Kathmandu', 'offset' => '+05:45', 'offset_minutes' => 345, 'country_code' => 'NP', 'is_active' => true],
            
            // UTC+06:00 Central Asia
            ['id' => 65, 'name' => 'Asia/Dhaka', 'label' => '(UTC+06:00) Dhaka', 'offset' => '+06:00', 'offset_minutes' => 360, 'country_code' => 'BD', 'is_active' => true],
            ['id' => 66, 'name' => 'Asia/Almaty', 'label' => '(UTC+06:00) Almaty, Astana', 'offset' => '+06:00', 'offset_minutes' => 360, 'country_code' => 'KZ', 'is_active' => true],
            ['id' => 67, 'name' => 'Asia/Omsk', 'label' => '(UTC+06:00) Omsk', 'offset' => '+06:00', 'offset_minutes' => 360, 'country_code' => 'RU', 'is_active' => true],
            
            // UTC+06:30
            ['id' => 68, 'name' => 'Asia/Yangon', 'label' => '(UTC+06:30) Yangon (Rangoon)', 'offset' => '+06:30', 'offset_minutes' => 390, 'country_code' => 'MM', 'is_active' => true],
            
            // UTC+07:00 Southeast Asia
            ['id' => 69, 'name' => 'Asia/Bangkok', 'label' => '(UTC+07:00) Bangkok, Hanoi, Jakarta', 'offset' => '+07:00', 'offset_minutes' => 420, 'country_code' => 'TH', 'is_active' => true],
            ['id' => 70, 'name' => 'Asia/Ho_Chi_Minh', 'label' => '(UTC+07:00) Ho Chi Minh City', 'offset' => '+07:00', 'offset_minutes' => 420, 'country_code' => 'VN', 'is_active' => true],
            ['id' => 71, 'name' => 'Asia/Jakarta', 'label' => '(UTC+07:00) Jakarta', 'offset' => '+07:00', 'offset_minutes' => 420, 'country_code' => 'ID', 'is_active' => true],
            ['id' => 72, 'name' => 'Asia/Krasnoyarsk', 'label' => '(UTC+07:00) Krasnoyarsk', 'offset' => '+07:00', 'offset_minutes' => 420, 'country_code' => 'RU', 'is_active' => true],
            
            // UTC+08:00 East Asia
            ['id' => 73, 'name' => 'Asia/Singapore', 'label' => '(UTC+08:00) Singapore', 'offset' => '+08:00', 'offset_minutes' => 480, 'country_code' => 'SG', 'is_active' => true],
            ['id' => 74, 'name' => 'Asia/Hong_Kong', 'label' => '(UTC+08:00) Hong Kong', 'offset' => '+08:00', 'offset_minutes' => 480, 'country_code' => 'HK', 'is_active' => true],
            ['id' => 75, 'name' => 'Asia/Shanghai', 'label' => '(UTC+08:00) Beijing, Shanghai, Chongqing', 'offset' => '+08:00', 'offset_minutes' => 480, 'country_code' => 'CN', 'is_active' => true],
            ['id' => 76, 'name' => 'Asia/Taipei', 'label' => '(UTC+08:00) Taipei', 'offset' => '+08:00', 'offset_minutes' => 480, 'country_code' => 'TW', 'is_active' => true],
            ['id' => 77, 'name' => 'Asia/Kuala_Lumpur', 'label' => '(UTC+08:00) Kuala Lumpur', 'offset' => '+08:00', 'offset_minutes' => 480, 'country_code' => 'MY', 'is_active' => true],
            ['id' => 78, 'name' => 'Asia/Manila', 'label' => '(UTC+08:00) Manila', 'offset' => '+08:00', 'offset_minutes' => 480, 'country_code' => 'PH', 'is_active' => true],
            ['id' => 79, 'name' => 'Australia/Perth', 'label' => '(UTC+08:00) Perth', 'offset' => '+08:00', 'offset_minutes' => 480, 'country_code' => 'AU', 'is_active' => true],
            ['id' => 80, 'name' => 'Asia/Irkutsk', 'label' => '(UTC+08:00) Irkutsk', 'offset' => '+08:00', 'offset_minutes' => 480, 'country_code' => 'RU', 'is_active' => true],
            
            // UTC+09:00 Japan/Korea
            ['id' => 81, 'name' => 'Asia/Tokyo', 'label' => '(UTC+09:00) Tokyo, Osaka, Sapporo', 'offset' => '+09:00', 'offset_minutes' => 540, 'country_code' => 'JP', 'is_active' => true],
            ['id' => 82, 'name' => 'Asia/Seoul', 'label' => '(UTC+09:00) Seoul', 'offset' => '+09:00', 'offset_minutes' => 540, 'country_code' => 'KR', 'is_active' => true],
            ['id' => 83, 'name' => 'Asia/Yakutsk', 'label' => '(UTC+09:00) Yakutsk', 'offset' => '+09:00', 'offset_minutes' => 540, 'country_code' => 'RU', 'is_active' => true],
            
            // UTC+09:30
            ['id' => 84, 'name' => 'Australia/Darwin', 'label' => '(UTC+09:30) Darwin', 'offset' => '+09:30', 'offset_minutes' => 570, 'country_code' => 'AU', 'is_active' => true],
            ['id' => 85, 'name' => 'Australia/Adelaide', 'label' => '(UTC+09:30) Adelaide', 'offset' => '+09:30', 'offset_minutes' => 570, 'country_code' => 'AU', 'is_active' => true],
            
            // UTC+10:00 East Australia
            ['id' => 86, 'name' => 'Australia/Sydney', 'label' => '(UTC+10:00) Sydney, Melbourne', 'offset' => '+10:00', 'offset_minutes' => 600, 'country_code' => 'AU', 'is_active' => true],
            ['id' => 87, 'name' => 'Australia/Brisbane', 'label' => '(UTC+10:00) Brisbane', 'offset' => '+10:00', 'offset_minutes' => 600, 'country_code' => 'AU', 'is_active' => true],
            ['id' => 88, 'name' => 'Australia/Hobart', 'label' => '(UTC+10:00) Hobart', 'offset' => '+10:00', 'offset_minutes' => 600, 'country_code' => 'AU', 'is_active' => true],
            ['id' => 89, 'name' => 'Pacific/Guam', 'label' => '(UTC+10:00) Guam, Port Moresby', 'offset' => '+10:00', 'offset_minutes' => 600, 'country_code' => 'GU', 'is_active' => true],
            ['id' => 90, 'name' => 'Asia/Vladivostok', 'label' => '(UTC+10:00) Vladivostok', 'offset' => '+10:00', 'offset_minutes' => 600, 'country_code' => 'RU', 'is_active' => true],
            
            // UTC+11:00
            ['id' => 91, 'name' => 'Pacific/Noumea', 'label' => '(UTC+11:00) New Caledonia', 'offset' => '+11:00', 'offset_minutes' => 660, 'country_code' => 'NC', 'is_active' => true],
            ['id' => 92, 'name' => 'Asia/Magadan', 'label' => '(UTC+11:00) Magadan', 'offset' => '+11:00', 'offset_minutes' => 660, 'country_code' => 'RU', 'is_active' => true],
            ['id' => 93, 'name' => 'Pacific/Solomon', 'label' => '(UTC+11:00) Solomon Islands', 'offset' => '+11:00', 'offset_minutes' => 660, 'country_code' => 'SB', 'is_active' => true],
            
            // UTC+12:00 Pacific
            ['id' => 94, 'name' => 'Pacific/Auckland', 'label' => '(UTC+12:00) Auckland, Wellington', 'offset' => '+12:00', 'offset_minutes' => 720, 'country_code' => 'NZ', 'is_active' => true],
            ['id' => 95, 'name' => 'Pacific/Fiji', 'label' => '(UTC+12:00) Fiji', 'offset' => '+12:00', 'offset_minutes' => 720, 'country_code' => 'FJ', 'is_active' => true],
            ['id' => 96, 'name' => 'Asia/Kamchatka', 'label' => '(UTC+12:00) Kamchatka', 'offset' => '+12:00', 'offset_minutes' => 720, 'country_code' => 'RU', 'is_active' => true],
            
            // UTC+13:00
            ['id' => 97, 'name' => 'Pacific/Tongatapu', 'label' => '(UTC+13:00) Nuku\'alofa', 'offset' => '+13:00', 'offset_minutes' => 780, 'country_code' => 'TO', 'is_active' => true],
            ['id' => 98, 'name' => 'Pacific/Apia', 'label' => '(UTC+13:00) Samoa', 'offset' => '+13:00', 'offset_minutes' => 780, 'country_code' => 'WS', 'is_active' => true],
        ];

        DB::table('timezones')->insert($timezones);
    }
}