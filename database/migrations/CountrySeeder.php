<?php

namespace Modules\StudentSponsor\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $countries = [
            ['country_id' => 1, 'iso2' => 'AF', 'short_name' => 'Afghanistan', 'long_name' => 'Islamic Republic of Afghanistan', 'iso3' => 'AFG', 'numcode' => '004', 'un_member' => 'yes', 'calling_code' => '93', 'cctld' => '.af'],
            ['country_id' => 2, 'iso2' => 'AX', 'short_name' => 'Aland Islands', 'long_name' => 'Åland Islands', 'iso3' => 'ALA', 'numcode' => '248', 'un_member' => 'no', 'calling_code' => '358', 'cctld' => '.ax'],
            ['country_id' => 3, 'iso2' => 'AL', 'short_name' => 'Albania', 'long_name' => 'Republic of Albania', 'iso3' => 'ALB', 'numcode' => '008', 'un_member' => 'yes', 'calling_code' => '355', 'cctld' => '.al'],
            ['country_id' => 4, 'iso2' => 'DZ', 'short_name' => 'Algeria', 'long_name' => 'People\'s Democratic Republic of Algeria', 'iso3' => 'DZA', 'numcode' => '012', 'un_member' => 'yes', 'calling_code' => '213', 'cctld' => '.dz'],
            ['country_id' => 5, 'iso2' => 'AS', 'short_name' => 'American Samoa', 'long_name' => 'American Samoa', 'iso3' => 'ASM', 'numcode' => '016', 'un_member' => 'no', 'calling_code' => '1+684', 'cctld' => '.as'],
            ['country_id' => 6, 'iso2' => 'AD', 'short_name' => 'Andorra', 'long_name' => 'Principality of Andorra', 'iso3' => 'AND', 'numcode' => '020', 'un_member' => 'yes', 'calling_code' => '376', 'cctld' => '.ad'],
            ['country_id' => 7, 'iso2' => 'AO', 'short_name' => 'Angola', 'long_name' => 'Republic of Angola', 'iso3' => 'AGO', 'numcode' => '024', 'un_member' => 'yes', 'calling_code' => '244', 'cctld' => '.ao'],
            ['country_id' => 8, 'iso2' => 'AI', 'short_name' => 'Anguilla', 'long_name' => 'Anguilla', 'iso3' => 'AIA', 'numcode' => '660', 'un_member' => 'no', 'calling_code' => '1+264', 'cctld' => '.ai'],
            ['country_id' => 9, 'iso2' => 'AQ', 'short_name' => 'Antarctica', 'long_name' => 'Antarctica', 'iso3' => 'ATA', 'numcode' => '010', 'un_member' => 'no', 'calling_code' => '672', 'cctld' => '.aq'],
            ['country_id' => 10, 'iso2' => 'AG', 'short_name' => 'Antigua and Barbuda', 'long_name' => 'Antigua and Barbuda', 'iso3' => 'ATG', 'numcode' => '028', 'un_member' => 'yes', 'calling_code' => '1+268', 'cctld' => '.ag'],
            ['country_id' => 11, 'iso2' => 'AR', 'short_name' => 'Argentina', 'long_name' => 'Argentine Republic', 'iso3' => 'ARG', 'numcode' => '032', 'un_member' => 'yes', 'calling_code' => '54', 'cctld' => '.ar'],
            ['country_id' => 12, 'iso2' => 'AM', 'short_name' => 'Armenia', 'long_name' => 'Republic of Armenia', 'iso3' => 'ARM', 'numcode' => '051', 'un_member' => 'yes', 'calling_code' => '374', 'cctld' => '.am'],
            ['country_id' => 13, 'iso2' => 'AW', 'short_name' => 'Aruba', 'long_name' => 'Aruba', 'iso3' => 'ABW', 'numcode' => '533', 'un_member' => 'no', 'calling_code' => '297', 'cctld' => '.aw'],
            ['country_id' => 14, 'iso2' => 'AU', 'short_name' => 'Australia', 'long_name' => 'Commonwealth of Australia', 'iso3' => 'AUS', 'numcode' => '036', 'un_member' => 'yes', 'calling_code' => '61', 'cctld' => '.au'],
            ['country_id' => 15, 'iso2' => 'AT', 'short_name' => 'Austria', 'long_name' => 'Republic of Austria', 'iso3' => 'AUT', 'numcode' => '040', 'un_member' => 'yes', 'calling_code' => '43', 'cctld' => '.at'],
            ['country_id' => 16, 'iso2' => 'AZ', 'short_name' => 'Azerbaijan', 'long_name' => 'Republic of Azerbaijan', 'iso3' => 'AZE', 'numcode' => '031', 'un_member' => 'yes', 'calling_code' => '994', 'cctld' => '.az'],
            ['country_id' => 17, 'iso2' => 'BS', 'short_name' => 'Bahamas', 'long_name' => 'Commonwealth of The Bahamas', 'iso3' => 'BHS', 'numcode' => '044', 'un_member' => 'yes', 'calling_code' => '1+242', 'cctld' => '.bs'],
            ['country_id' => 18, 'iso2' => 'BH', 'short_name' => 'Bahrain', 'long_name' => 'Kingdom of Bahrain', 'iso3' => 'BHR', 'numcode' => '048', 'un_member' => 'yes', 'calling_code' => '973', 'cctld' => '.bh'],
            ['country_id' => 19, 'iso2' => 'BD', 'short_name' => 'Bangladesh', 'long_name' => 'People\'s Republic of Bangladesh', 'iso3' => 'BGD', 'numcode' => '050', 'un_member' => 'yes', 'calling_code' => '880', 'cctld' => '.bd'],
            ['country_id' => 20, 'iso2' => 'BB', 'short_name' => 'Barbados', 'long_name' => 'Barbados', 'iso3' => 'BRB', 'numcode' => '052', 'un_member' => 'yes', 'calling_code' => '1+246', 'cctld' => '.bb'],
            ['country_id' => 21, 'iso2' => 'BY', 'short_name' => 'Belarus', 'long_name' => 'Republic of Belarus', 'iso3' => 'BLR', 'numcode' => '112', 'un_member' => 'yes', 'calling_code' => '375', 'cctld' => '.by'],
            ['country_id' => 22, 'iso2' => 'BE', 'short_name' => 'Belgium', 'long_name' => 'Kingdom of Belgium', 'iso3' => 'BEL', 'numcode' => '056', 'un_member' => 'yes', 'calling_code' => '32', 'cctld' => '.be'],
            ['country_id' => 23, 'iso2' => 'BZ', 'short_name' => 'Belize', 'long_name' => 'Belize', 'iso3' => 'BLZ', 'numcode' => '084', 'un_member' => 'yes', 'calling_code' => '501', 'cctld' => '.bz'],
            ['country_id' => 24, 'iso2' => 'BJ', 'short_name' => 'Benin', 'long_name' => 'Republic of Benin', 'iso3' => 'BEN', 'numcode' => '204', 'un_member' => 'yes', 'calling_code' => '229', 'cctld' => '.bj'],
            ['country_id' => 25, 'iso2' => 'BM', 'short_name' => 'Bermuda', 'long_name' => 'Bermuda Islands', 'iso3' => 'BMU', 'numcode' => '060', 'un_member' => 'no', 'calling_code' => '1+441', 'cctld' => '.bm'],
            ['country_id' => 26, 'iso2' => 'BT', 'short_name' => 'Bhutan', 'long_name' => 'Kingdom of Bhutan', 'iso3' => 'BTN', 'numcode' => '064', 'un_member' => 'yes', 'calling_code' => '975', 'cctld' => '.bt'],
            ['country_id' => 27, 'iso2' => 'BO', 'short_name' => 'Bolivia', 'long_name' => 'Plurinational State of Bolivia', 'iso3' => 'BOL', 'numcode' => '068', 'un_member' => 'yes', 'calling_code' => '591', 'cctld' => '.bo'],
            ['country_id' => 28, 'iso2' => 'BQ', 'short_name' => 'Bonaire, Sint Eustatius and Saba', 'long_name' => 'Bonaire, Sint Eustatius and Saba', 'iso3' => 'BES', 'numcode' => '535', 'un_member' => 'no', 'calling_code' => '599', 'cctld' => '.bq'],
            ['country_id' => 29, 'iso2' => 'BA', 'short_name' => 'Bosnia and Herzegovina', 'long_name' => 'Bosnia and Herzegovina', 'iso3' => 'BIH', 'numcode' => '070', 'un_member' => 'yes', 'calling_code' => '387', 'cctld' => '.ba'],
            ['country_id' => 30, 'iso2' => 'BW', 'short_name' => 'Botswana', 'long_name' => 'Republic of Botswana', 'iso3' => 'BWA', 'numcode' => '072', 'un_member' => 'yes', 'calling_code' => '267', 'cctld' => '.bw'],
            ['country_id' => 31, 'iso2' => 'BV', 'short_name' => 'Bouvet Island', 'long_name' => 'Bouvet Island', 'iso3' => 'BVT', 'numcode' => '074', 'un_member' => 'no', 'calling_code' => 'NONE', 'cctld' => '.bv'],
            ['country_id' => 32, 'iso2' => 'BR', 'short_name' => 'Brazil', 'long_name' => 'Federative Republic of Brazil', 'iso3' => 'BRA', 'numcode' => '076', 'un_member' => 'yes', 'calling_code' => '55', 'cctld' => '.br'],
            ['country_id' => 33, 'iso2' => 'IO', 'short_name' => 'British Indian Ocean Territory', 'long_name' => 'British Indian Ocean Territory', 'iso3' => 'IOT', 'numcode' => '086', 'un_member' => 'no', 'calling_code' => '246', 'cctld' => '.io'],
            ['country_id' => 34, 'iso2' => 'BN', 'short_name' => 'Brunei', 'long_name' => 'Brunei Darussalam', 'iso3' => 'BRN', 'numcode' => '096', 'un_member' => 'yes', 'calling_code' => '673', 'cctld' => '.bn'],
            ['country_id' => 35, 'iso2' => 'BG', 'short_name' => 'Bulgaria', 'long_name' => 'Republic of Bulgaria', 'iso3' => 'BGR', 'numcode' => '100', 'un_member' => 'yes', 'calling_code' => '359', 'cctld' => '.bg'],
            ['country_id' => 36, 'iso2' => 'BF', 'short_name' => 'Burkina Faso', 'long_name' => 'Burkina Faso', 'iso3' => 'BFA', 'numcode' => '854', 'un_member' => 'yes', 'calling_code' => '226', 'cctld' => '.bf'],
            ['country_id' => 37, 'iso2' => 'BI', 'short_name' => 'Burundi', 'long_name' => 'Republic of Burundi', 'iso3' => 'BDI', 'numcode' => '108', 'un_member' => 'yes', 'calling_code' => '257', 'cctld' => '.bi'],
            ['country_id' => 38, 'iso2' => 'KH', 'short_name' => 'Cambodia', 'long_name' => 'Kingdom of Cambodia', 'iso3' => 'KHM', 'numcode' => '116', 'un_member' => 'yes', 'calling_code' => '855', 'cctld' => '.kh'],
            ['country_id' => 39, 'iso2' => 'CM', 'short_name' => 'Cameroon', 'long_name' => 'Republic of Cameroon', 'iso3' => 'CMR', 'numcode' => '120', 'un_member' => 'yes', 'calling_code' => '237', 'cctld' => '.cm'],
            ['country_id' => 40, 'iso2' => 'CA', 'short_name' => 'Canada', 'long_name' => 'Canada', 'iso3' => 'CAN', 'numcode' => '124', 'un_member' => 'yes', 'calling_code' => '1', 'cctld' => '.ca'],
            ['country_id' => 41, 'iso2' => 'CV', 'short_name' => 'Cape Verde', 'long_name' => 'Republic of Cape Verde', 'iso3' => 'CPV', 'numcode' => '132', 'un_member' => 'yes', 'calling_code' => '238', 'cctld' => '.cv'],
            ['country_id' => 42, 'iso2' => 'KY', 'short_name' => 'Cayman Islands', 'long_name' => 'The Cayman Islands', 'iso3' => 'CYM', 'numcode' => '136', 'un_member' => 'no', 'calling_code' => '1+345', 'cctld' => '.ky'],
            ['country_id' => 43, 'iso2' => 'CF', 'short_name' => 'Central African Republic', 'long_name' => 'Central African Republic', 'iso3' => 'CAF', 'numcode' => '140', 'un_member' => 'yes', 'calling_code' => '236', 'cctld' => '.cf'],
            ['country_id' => 44, 'iso2' => 'TD', 'short_name' => 'Chad', 'long_name' => 'Republic of Chad', 'iso3' => 'TCD', 'numcode' => '148', 'un_member' => 'yes', 'calling_code' => '235', 'cctld' => '.td'],
            ['country_id' => 45, 'iso2' => 'CL', 'short_name' => 'Chile', 'long_name' => 'Republic of Chile', 'iso3' => 'CHL', 'numcode' => '152', 'un_member' => 'yes', 'calling_code' => '56', 'cctld' => '.cl'],
            ['country_id' => 46, 'iso2' => 'CN', 'short_name' => 'China', 'long_name' => 'People\'s Republic of China', 'iso3' => 'CHN', 'numcode' => '156', 'un_member' => 'yes', 'calling_code' => '86', 'cctld' => '.cn'],
            ['country_id' => 47, 'iso2' => 'CX', 'short_name' => 'Christmas Island', 'long_name' => 'Christmas Island', 'iso3' => 'CXR', 'numcode' => '162', 'un_member' => 'no', 'calling_code' => '61', 'cctld' => '.cx'],
            ['country_id' => 48, 'iso2' => 'CC', 'short_name' => 'Cocos (Keeling) Islands', 'long_name' => 'Cocos (Keeling) Islands', 'iso3' => 'CCK', 'numcode' => '166', 'un_member' => 'no', 'calling_code' => '61', 'cctld' => '.cc'],
            ['country_id' => 49, 'iso2' => 'CO', 'short_name' => 'Colombia', 'long_name' => 'Republic of Colombia', 'iso3' => 'COL', 'numcode' => '170', 'un_member' => 'yes', 'calling_code' => '57', 'cctld' => '.co'],
            ['country_id' => 50, 'iso2' => 'KM', 'short_name' => 'Comoros', 'long_name' => 'Union of the Comoros', 'iso3' => 'COM', 'numcode' => '174', 'un_member' => 'yes', 'calling_code' => '269', 'cctld' => '.km'],
            ['country_id' => 100, 'iso2' => 'IN', 'short_name' => 'India', 'long_name' => 'Republic of India', 'iso3' => 'IND', 'numcode' => '356', 'un_member' => 'yes', 'calling_code' => '91', 'cctld' => '.in'],
            ['country_id' => 210, 'iso2' => 'LK', 'short_name' => 'Sri Lanka', 'long_name' => 'Democratic Socialist Republic of Sri Lanka', 'iso3' => 'LKA', 'numcode' => '144', 'un_member' => 'yes', 'calling_code' => '94', 'cctld' => '.lk'],
            ['country_id' => 235, 'iso2' => 'GB', 'short_name' => 'United Kingdom', 'long_name' => 'United Kingdom of Great Britain and Northern Ireland', 'iso3' => 'GBR', 'numcode' => '826', 'un_member' => 'yes', 'calling_code' => '44', 'cctld' => '.uk'],
            ['country_id' => 236, 'iso2' => 'US', 'short_name' => 'United States', 'long_name' => 'United States of America', 'iso3' => 'USA', 'numcode' => '840', 'un_member' => 'yes', 'calling_code' => '1', 'cctld' => '.us'],
        ];

        foreach ($countries as $country) {
            DB::table('tblcountries')->updateOrInsert(
                ['country_id' => $country['country_id']],
                $country
            );
        }
    }
}
