<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CountriesSeeder extends Seeder {

  /**
  * Run the database seeds.
  *
  * @return void
  */
  public function run()
  {
    DB::table('countries')->insert([
      [
        "id"            =>  1,
        "charcode"      =>  "AF",
        "charcode3"     =>  "AFG",
        "numcode"       =>  "004",
        "name"          =>  "Afghanistan",
        "country_code"  =>  "93",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  2,
        "charcode"      =>  "AX",
        "charcode3"     =>  "ALA",
        "numcode"       =>  "248",
        "name"          =>  "Aland Islands",
        "country_code"  =>  "358",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  3,
        "charcode"      =>  "AL",
        "charcode3"     =>  "ALB",
        "numcode"       =>  "008",
        "name"          =>  "Albania",
        "country_code"  =>  "355",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  4,
        "charcode"      =>  "DZ",
        "charcode3"     =>  "DZA",
        "numcode"       =>  "012",
        "name"          =>  "Algeria",
        "country_code"  =>  "213",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  5,
        "charcode"      =>  "AS",
        "charcode3"     =>  "ASM",
        "numcode"       =>  "016",
        "name"          =>  "American Samoa",
        "country_code"  =>  "1 684",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  6,
        "charcode"      =>  "AD",
        "charcode3"     =>  "AND",
        "numcode"       =>  "020",
        "name"          =>  "Andorra",
        "country_code"  =>  "376",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  7,
        "charcode"      =>  "AO",
        "charcode3"     =>  "AGO",
        "numcode"       =>  "024",
        "name"          =>  "Angola",
        "country_code"  =>  "244",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  8,
        "charcode"      =>  "AI",
        "charcode3"     =>  "AIA",
        "numcode"       =>  "660",
        "name"          =>  "Anguilla",
        "country_code"  =>  "1 264",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  9,
        "charcode"      =>  "AQ",
        "charcode3"     =>  "ATA",
        "numcode"       =>  "010",
        "name"          =>  "Antarctica",
        "country_code"  =>  "672",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  10,
        "charcode"      =>  "AG",
        "charcode3"     =>  "ATG",
        "numcode"       =>  "028",
        "name"          =>  "Antigua and Barbuda",
        "country_code"  =>  "1 268",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  11,
        "charcode"      =>  "AR",
        "charcode3"     =>  "ARG",
        "numcode"       =>  "032",
        "name"          =>  "Argentina",
        "country_code"  =>  "54",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  12,
        "charcode"      =>  "AM",
        "charcode3"     =>  "ARM",
        "numcode"       =>  "051",
        "name"          =>  "Armenia",
        "country_code"  =>  "374",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  13,
        "charcode"      =>  "AW",
        "charcode3"     =>  "ABW",
        "numcode"       =>  "533",
        "name"          =>  "Aruba",
        "country_code"  =>  "297",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  14,
        "charcode"      =>  "AU",
        "charcode3"     =>  "AUS",
        "numcode"       =>  "036",
        "name"          =>  "Australia",
        "country_code"  =>  "61",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  15,
        "charcode"      =>  "AT",
        "charcode3"     =>  "AUT",
        "numcode"       =>  "040",
        "name"          =>  "Austria",
        "country_code"  =>  "43",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  16,
        "charcode"      =>  "AZ",
        "charcode3"     =>  "AZE",
        "numcode"       =>  "031",
        "name"          =>  "Azerbaijan",
        "country_code"  =>  "994",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  17,
        "charcode"      =>  "BS",
        "charcode3"     =>  "BHS",
        "numcode"       =>  "044",
        "name"          =>  "Bahamas",
        "country_code"  =>  "1 242",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  18,
        "charcode"      =>  "BH",
        "charcode3"     =>  "BHR",
        "numcode"       =>  "048",
        "name"          =>  "Bahrain",
        "country_code"  =>  "973",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  19,
        "charcode"      =>  "BD",
        "charcode3"     =>  "BGD",
        "numcode"       =>  "050",
        "name"          =>  "Bangladesh",
        "country_code"  =>  "880",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  20,
        "charcode"      =>  "BB",
        "charcode3"     =>  "BRB",
        "numcode"       =>  "052",
        "name"          =>  "Barbados",
        "country_code"  =>  "1 246",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  21,
        "charcode"      =>  "BY",
        "charcode3"     =>  "BLR",
        "numcode"       =>  "112",
        "name"          =>  "Belarus",
        "country_code"  =>  "375",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  22,
        "charcode"      =>  "BE",
        "charcode3"     =>  "BEL",
        "numcode"       =>  "056",
        "name"          =>  "Belgium",
        "country_code"  =>  "32",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  23,
        "charcode"      =>  "BZ",
        "charcode3"     =>  "BLZ",
        "numcode"       =>  "084",
        "name"          =>  "Belize",
        "country_code"  =>  "501",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  24,
        "charcode"      =>  "BJ",
        "charcode3"     =>  "BEN",
        "numcode"       =>  "204",
        "name"          =>  "Benin",
        "country_code"  =>  "229",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  25,
        "charcode"      =>  "BM",
        "charcode3"     =>  "BMU",
        "numcode"       =>  "060",
        "name"          =>  "Bermuda",
        "country_code"  =>  "1 441",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  26,
        "charcode"      =>  "BT",
        "charcode3"     =>  "BTN",
        "numcode"       =>  "064",
        "name"          =>  "Bhutan",
        "country_code"  =>  "975",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  27,
        "charcode"      =>  "BO",
        "charcode3"     =>  "BOL",
        "numcode"       =>  "068",
        "name"          =>  "Bolivia",
        "country_code"  =>  "591",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  28,
        "charcode"      =>  "BA",
        "charcode3"     =>  "BIH",
        "numcode"       =>  "070",
        "name"          =>  "Bosnia and Herzegovina",
        "country_code"  =>  "387",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  29,
        "charcode"      =>  "BW",
        "charcode3"     =>  "BWA",
        "numcode"       =>  "072",
        "name"          =>  "Botswana",
        "country_code"  =>  "267",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  30,
        "charcode"      =>  "BV",
        "charcode3"     =>  "BVT",
        "numcode"       =>  "074",
        "name"          =>  "Bouvet Island",
        "country_code"  =>  "47",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  31,
        "charcode"      =>  "BR",
        "charcode3"     =>  "BRA",
        "numcode"       =>  "076",
        "name"          =>  "Brazil",
        "country_code"  =>  "55",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  32,
        "charcode"      =>  "VG",
        "charcode3"     =>  "VGB",
        "numcode"       =>  "092",
        "name"          =>  "British Virgin Islands",
        "country_code"  =>  "1 284",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  33,
        "charcode"      =>  "IO",
        "charcode3"     =>  "IOT",
        "numcode"       =>  "086",
        "name"          =>  "British Indian Ocean Territory",
        "country_code"  =>  "246",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  34,
        "charcode"      =>  "BN",
        "charcode3"     =>  "BRN",
        "numcode"       =>  "096",
        "name"          =>  "Brunei Darussalam",
        "country_code"  =>  "673",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  35,
        "charcode"      =>  "BG",
        "charcode3"     =>  "BGR",
        "numcode"       =>  "100",
        "name"          =>  "Bulgaria",
        "country_code"  =>  "359",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  36,
        "charcode"      =>  "BF",
        "charcode3"     =>  "BFA",
        "numcode"       =>  "854",
        "name"          =>  "Burkina Faso",
        "country_code"  =>  "226",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  37,
        "charcode"      =>  "BI",
        "charcode3"     =>  "BDI",
        "numcode"       =>  "108",
        "name"          =>  "Burundi",
        "country_code"  =>  "257",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  38,
        "charcode"      =>  "KH",
        "charcode3"     =>  "KHM",
        "numcode"       =>  "116",
        "name"          =>  "Cambodia",
        "country_code"  =>  "855",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  39,
        "charcode"      =>  "CM",
        "charcode3"     =>  "CMR",
        "numcode"       =>  "120",
        "name"          =>  "Cameroon",
        "country_code"  =>  "237",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  40,
        "charcode"      =>  "CA",
        "charcode3"     =>  "CAN",
        "numcode"       =>  "124",
        "name"          =>  "Canada",
        "country_code"  =>  "1",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  41,
        "charcode"      =>  "CV",
        "charcode3"     =>  "CPV",
        "numcode"       =>  "132",
        "name"          =>  "Cape Verde",
        "country_code"  =>  "238",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  42,
        "charcode"      =>  "KY",
        "charcode3"     =>  "CYM",
        "numcode"       =>  "136",
        "name"          =>  "Cayman Islands ",
        "country_code"  =>  "1 345",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  43,
        "charcode"      =>  "CF",
        "charcode3"     =>  "CAF",
        "numcode"       =>  "140",
        "name"          =>  "Central African Republic",
        "country_code"  =>  "236",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  44,
        "charcode"      =>  "TD",
        "charcode3"     =>  "TCD",
        "numcode"       =>  "148",
        "name"          =>  "Chad",
        "country_code"  =>  "235",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  45,
        "charcode"      =>  "CL",
        "charcode3"     =>  "CHL",
        "numcode"       =>  "152",
        "name"          =>  "Chile",
        "country_code"  =>  "56",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  46,
        "charcode"      =>  "CN",
        "charcode3"     =>  "CHN",
        "numcode"       =>  "156",
        "name"          =>  "China",
        "country_code"  =>  "86",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  47,
        "charcode"      =>  "HK",
        "charcode3"     =>  "HKG",
        "numcode"       =>  "344",
        "name"          =>  "Hong Kong, Special Administrative Region of China",
        "country_code"  =>  "852",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  48,
        "charcode"      =>  "MO",
        "charcode3"     =>  "MAC",
        "numcode"       =>  "446",
        "name"          =>  "Macao, Special Administrative Region of China",
        "country_code"  =>  "853",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  49,
        "charcode"      =>  "CX",
        "charcode3"     =>  "CXR",
        "numcode"       =>  "162",
        "name"          =>  "Christmas Island",
        "country_code"  =>  "61",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  50,
        "charcode"      =>  "CC",
        "charcode3"     =>  "CCK",
        "numcode"       =>  "166",
        "name"          =>  "Cocos (Keeling) Islands",
        "country_code"  =>  "61",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  51,
        "charcode"      =>  "CO",
        "charcode3"     =>  "COL",
        "numcode"       =>  "170",
        "name"          =>  "Colombia",
        "country_code"  =>  "57",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  52,
        "charcode"      =>  "KM",
        "charcode3"     =>  "COM",
        "numcode"       =>  "174",
        "name"          =>  "Comoros",
        "country_code"  =>  "269",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  53,
        "charcode"      =>  "CG",
        "charcode3"     =>  "COG",
        "numcode"       =>  "178",
        "name"          =>  "Congo (Brazzaville)",
        "country_code"  =>  "242",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  54,
        "charcode"      =>  "CD",
        "charcode3"     =>  "COD",
        "numcode"       =>  "180",
        "name"          =>  "Congo, Democratic Republic of the",
        "country_code"  =>  "243",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  55,
        "charcode"      =>  "CK",
        "charcode3"     =>  "COK",
        "numcode"       =>  "184",
        "name"          =>  "Cook Islands",
        "country_code"  =>  "682",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  56,
        "charcode"      =>  "CR",
        "charcode3"     =>  "CRI",
        "numcode"       =>  "188",
        "name"          =>  "Costa Rica",
        "country_code"  =>  "506",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  57,
        "charcode"      =>  "CI",
        "charcode3"     =>  "CIV",
        "numcode"       =>  "384",
        "name"          =>  "Côte d'Ivoire",
        "country_code"  =>  "225",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  58,
        "charcode"      =>  "HR",
        "charcode3"     =>  "HRV",
        "numcode"       =>  "191",
        "name"          =>  "Croatia",
        "country_code"  =>  "385",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  59,
        "charcode"      =>  "CU",
        "charcode3"     =>  "CUB",
        "numcode"       =>  "192",
        "name"          =>  "Cuba",
        "country_code"  =>  "53",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  60,
        "charcode"      =>  "CY",
        "charcode3"     =>  "CYP",
        "numcode"       =>  "196",
        "name"          =>  "Cyprus",
        "country_code"  =>  "357",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  61,
        "charcode"      =>  "CZ",
        "charcode3"     =>  "CZE",
        "numcode"       =>  "203",
        "name"          =>  "Czech Republic",
        "country_code"  =>  "420",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  62,
        "charcode"      =>  "DK",
        "charcode3"     =>  "DNK",
        "numcode"       =>  "208",
        "name"          =>  "Denmark",
        "country_code"  =>  "45",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  63,
        "charcode"      =>  "DJ",
        "charcode3"     =>  "DJI",
        "numcode"       =>  "262",
        "name"          =>  "Djibouti",
        "country_code"  =>  "253",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  64,
        "charcode"      =>  "DM",
        "charcode3"     =>  "DMA",
        "numcode"       =>  "212",
        "name"          =>  "Dominica",
        "country_code"  =>  "1 767",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  65,
        "charcode"      =>  "DO",
        "charcode3"     =>  "DOM",
        "numcode"       =>  "214",
        "name"          =>  "Dominican Republic",
        "country_code"  =>  "1 809",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  66,
        "charcode"      =>  "EC",
        "charcode3"     =>  "ECU",
        "numcode"       =>  "218",
        "name"          =>  "Ecuador",
        "country_code"  =>  "593",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  67,
        "charcode"      =>  "EG",
        "charcode3"     =>  "EGY",
        "numcode"       =>  "818",
        "name"          =>  "Egypt",
        "country_code"  =>  "20",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  68,
        "charcode"      =>  "SV",
        "charcode3"     =>  "SLV",
        "numcode"       =>  "222",
        "name"          =>  "El Salvador",
        "country_code"  =>  "503",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  69,
        "charcode"      =>  "GQ",
        "charcode3"     =>  "GNQ",
        "numcode"       =>  "226",
        "name"          =>  "Equatorial Guinea",
        "country_code"  =>  "240",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  70,
        "charcode"      =>  "ER",
        "charcode3"     =>  "ERI",
        "numcode"       =>  "232",
        "name"          =>  "Eritrea",
        "country_code"  =>  "291",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  71,
        "charcode"      =>  "EE",
        "charcode3"     =>  "EST",
        "numcode"       =>  "233",
        "name"          =>  "Estonia",
        "country_code"  =>  "372",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  72,
        "charcode"      =>  "ET",
        "charcode3"     =>  "ETH",
        "numcode"       =>  "231",
        "name"          =>  "Ethiopia",
        "country_code"  =>  "251",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  73,
        "charcode"      =>  "FK",
        "charcode3"     =>  "FLK",
        "numcode"       =>  "238",
        "name"          =>  "Falkland Islands (Malvinas) ",
        "country_code"  =>  "500",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  74,
        "charcode"      =>  "FO",
        "charcode3"     =>  "FRO",
        "numcode"       =>  "234",
        "name"          =>  "Faroe Islands",
        "country_code"  =>  "298",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  75,
        "charcode"      =>  "FJ",
        "charcode3"     =>  "FJI",
        "numcode"       =>  "242",
        "name"          =>  "Fiji",
        "country_code"  =>  "679",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  76,
        "charcode"      =>  "FI",
        "charcode3"     =>  "FIN",
        "numcode"       =>  "246",
        "name"          =>  "Finland",
        "country_code"  =>  "358",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  77,
        "charcode"      =>  "FR",
        "charcode3"     =>  "FRA",
        "numcode"       =>  "250",
        "name"          =>  "France",
        "country_code"  =>  "33",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  78,
        "charcode"      =>  "GF",
        "charcode3"     =>  "GUF",
        "numcode"       =>  "254",
        "name"          =>  "French Guiana",
        "country_code"  =>  "594",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  79,
        "charcode"      =>  "PF",
        "charcode3"     =>  "PYF",
        "numcode"       =>  "258",
        "name"          =>  "French Polynesia",
        "country_code"  =>  "689",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  80,
        "charcode"      =>  "TF",
        "charcode3"     =>  "ATF",
        "numcode"       =>  "260",
        "name"          =>  "French Southern Territories",
        "country_code"  =>  "33",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  81,
        "charcode"      =>  "GA",
        "charcode3"     =>  "GAB",
        "numcode"       =>  "266",
        "name"          =>  "Gabon",
        "country_code"  =>  "241",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  82,
        "charcode"      =>  "GM",
        "charcode3"     =>  "GMB",
        "numcode"       =>  "270",
        "name"          =>  "Gambia",
        "country_code"  =>  "220",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  83,
        "charcode"      =>  "GE",
        "charcode3"     =>  "GEO",
        "numcode"       =>  "268",
        "name"          =>  "Georgia",
        "country_code"  =>  "995",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  84,
        "charcode"      =>  "DE",
        "charcode3"     =>  "DEU",
        "numcode"       =>  "276",
        "name"          =>  "Germany",
        "country_code"  =>  "49",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  85,
        "charcode"      =>  "GH",
        "charcode3"     =>  "GHA",
        "numcode"       =>  "288",
        "name"          =>  "Ghana",
        "country_code"  =>  "233",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  86,
        "charcode"      =>  "GI",
        "charcode3"     =>  "GIB",
        "numcode"       =>  "292",
        "name"          =>  "Gibraltar ",
        "country_code"  =>  "350",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  87,
        "charcode"      =>  "GR",
        "charcode3"     =>  "GRC",
        "numcode"       =>  "300",
        "name"          =>  "Greece",
        "country_code"  =>  "30",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  88,
        "charcode"      =>  "GL",
        "charcode3"     =>  "GRL",
        "numcode"       =>  "304",
        "name"          =>  "Greenland",
        "country_code"  =>  "299",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  89,
        "charcode"      =>  "GD",
        "charcode3"     =>  "GRD",
        "numcode"       =>  "308",
        "name"          =>  "Grenada",
        "country_code"  =>  "1 473",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  90,
        "charcode"      =>  "GP",
        "charcode3"     =>  "GLP",
        "numcode"       =>  "312",
        "name"          =>  "Guadeloupe",
        "country_code"  =>  "590",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  91,
        "charcode"      =>  "GU",
        "charcode3"     =>  "GUM",
        "numcode"       =>  "316",
        "name"          =>  "Guam",
        "country_code"  =>  "1 671",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  92,
        "charcode"      =>  "GT",
        "charcode3"     =>  "GTM",
        "numcode"       =>  "320",
        "name"          =>  "Guatemala",
        "country_code"  =>  "502",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  93,
        "charcode"      =>  "GG",
        "charcode3"     =>  "GGY",
        "numcode"       =>  "831",
        "name"          =>  "Guernsey",
        "country_code"  =>  "44",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  94,
        "charcode"      =>  "GN",
        "charcode3"     =>  "GIN",
        "numcode"       =>  "324",
        "name"          =>  "Guinea",
        "country_code"  =>  "224",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  95,
        "charcode"      =>  "GW",
        "charcode3"     =>  "GNB",
        "numcode"       =>  "624",
        "name"          =>  "Guinea-Bissau",
        "country_code"  =>  "245",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  96,
        "charcode"      =>  "GY",
        "charcode3"     =>  "GUY",
        "numcode"       =>  "328",
        "name"          =>  "Guyana",
        "country_code"  =>  "592",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  97,
        "charcode"      =>  "HT",
        "charcode3"     =>  "HTI",
        "numcode"       =>  "332",
        "name"          =>  "Haiti",
        "country_code"  =>  "509",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  98,
        "charcode"      =>  "HM",
        "charcode3"     =>  "HMD",
        "numcode"       =>  "334",
        "name"          =>  "Heard Island and Mcdonald Islands",
        "country_code"  =>  "672",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  99,
        "charcode"      =>  "VA",
        "charcode3"     =>  "VAT",
        "numcode"       =>  "336",
        "name"          =>  "Holy See (Vatican City State)",
        "country_code"  =>  "39",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  100,
        "charcode"      =>  "HN",
        "charcode3"     =>  "HND",
        "numcode"       =>  "340",
        "name"          =>  "Honduras",
        "country_code"  =>  "504",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  101,
        "charcode"      =>  "HU",
        "charcode3"     =>  "HUN",
        "numcode"       =>  "348",
        "name"          =>  "Hungary",
        "country_code"  =>  "36",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  102,
        "charcode"      =>  "IS",
        "charcode3"     =>  "ISL",
        "numcode"       =>  "352",
        "name"          =>  "Iceland",
        "country_code"  =>  "354",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  103,
        "charcode"      =>  "IN",
        "charcode3"     =>  "IND",
        "numcode"       =>  "356",
        "name"          =>  "India",
        "country_code"  =>  "91",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  104,
        "charcode"      =>  "ID",
        "charcode3"     =>  "IDN",
        "numcode"       =>  "360",
        "name"          =>  "Indonesia",
        "country_code"  =>  "62",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  105,
        "charcode"      =>  "IR",
        "charcode3"     =>  "IRN",
        "numcode"       =>  "364",
        "name"          =>  "Iran, Islamic Republic of",
        "country_code"  =>  "98",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  106,
        "charcode"      =>  "IQ",
        "charcode3"     =>  "IRQ",
        "numcode"       =>  "368",
        "name"          =>  "Iraq",
        "country_code"  =>  "964",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  107,
        "charcode"      =>  "IE",
        "charcode3"     =>  "IRL",
        "numcode"       =>  "372",
        "name"          =>  "Ireland",
        "country_code"  =>  "353",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  108,
        "charcode"      =>  "IM",
        "charcode3"     =>  "IMN",
        "numcode"       =>  "833",
        "name"          =>  "Isle of Man ",
        "country_code"  =>  "44",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  109,
        "charcode"      =>  "IL",
        "charcode3"     =>  "ISR",
        "numcode"       =>  "376",
        "name"          =>  "Israel",
        "country_code"  =>  "972",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  110,
        "charcode"      =>  "IT",
        "charcode3"     =>  "ITA",
        "numcode"       =>  "380",
        "name"          =>  "Italy",
        "country_code"  =>  "39",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  111,
        "charcode"      =>  "JM",
        "charcode3"     =>  "JAM",
        "numcode"       =>  "388",
        "name"          =>  "Jamaica",
        "country_code"  =>  "1 876",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  112,
        "charcode"      =>  "JP",
        "charcode3"     =>  "JPN",
        "numcode"       =>  "392",
        "name"          =>  "Japan",
        "country_code"  =>  "81",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  113,
        "charcode"      =>  "JE",
        "charcode3"     =>  "JEY",
        "numcode"       =>  "832",
        "name"          =>  "Jersey",
        "country_code"  =>  "44",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  114,
        "charcode"      =>  "JO",
        "charcode3"     =>  "JOR",
        "numcode"       =>  "400",
        "name"          =>  "Jordan",
        "country_code"  =>  "962",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  115,
        "charcode"      =>  "KZ",
        "charcode3"     =>  "KAZ",
        "numcode"       =>  "398",
        "name"          =>  "Kazakhstan",
        "country_code"  =>  "7",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  116,
        "charcode"      =>  "KE",
        "charcode3"     =>  "KEN",
        "numcode"       =>  "404",
        "name"          =>  "Kenya",
        "country_code"  =>  "254",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  117,
        "charcode"      =>  "KI",
        "charcode3"     =>  "KIR",
        "numcode"       =>  "296",
        "name"          =>  "Kiribati",
        "country_code"  =>  "686",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  118,
        "charcode"      =>  "KP",
        "charcode3"     =>  "PRK",
        "numcode"       =>  "408",
        "name"          =>  "Korea, Democratic People's Republic of",
        "country_code"  =>  "850",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  119,
        "charcode"      =>  "KR",
        "charcode3"     =>  "KOR",
        "numcode"       =>  "410",
        "name"          =>  "Korea, Republic of",
        "country_code"  =>  "82",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  120,
        "charcode"      =>  "KW",
        "charcode3"     =>  "KWT",
        "numcode"       =>  "414",
        "name"          =>  "Kuwait",
        "country_code"  =>  "965",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  121,
        "charcode"      =>  "KG",
        "charcode3"     =>  "KGZ",
        "numcode"       =>  "417",
        "name"          =>  "Kyrgyzstan",
        "country_code"  =>  "996",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  122,
        "charcode"      =>  "LA",
        "charcode3"     =>  "LAO",
        "numcode"       =>  "418",
        "name"          =>  "Lao PDR",
        "country_code"  =>  "856",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  123,
        "charcode"      =>  "LV",
        "charcode3"     =>  "LVA",
        "numcode"       =>  "428",
        "name"          =>  "Latvia",
        "country_code"  =>  "371",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  124,
        "charcode"      =>  "LB",
        "charcode3"     =>  "LBN",
        "numcode"       =>  "422",
        "name"          =>  "Lebanon",
        "country_code"  =>  "961",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  125,
        "charcode"      =>  "LS",
        "charcode3"     =>  "LSO",
        "numcode"       =>  "426",
        "name"          =>  "Lesotho",
        "country_code"  =>  "266",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  126,
        "charcode"      =>  "LR",
        "charcode3"     =>  "LBR",
        "numcode"       =>  "430",
        "name"          =>  "Liberia",
        "country_code"  =>  "231",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  127,
        "charcode"      =>  "LY",
        "charcode3"     =>  "LBY",
        "numcode"       =>  "434",
        "name"          =>  "Libya",
        "country_code"  =>  "218",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  128,
        "charcode"      =>  "LI",
        "charcode3"     =>  "LIE",
        "numcode"       =>  "438",
        "name"          =>  "Liechtenstein",
        "country_code"  =>  "423",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  129,
        "charcode"      =>  "LT",
        "charcode3"     =>  "LTU",
        "numcode"       =>  "440",
        "name"          =>  "Lithuania",
        "country_code"  =>  "370",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  130,
        "charcode"      =>  "LU",
        "charcode3"     =>  "LUX",
        "numcode"       =>  "442",
        "name"          =>  "Luxembourg",
        "country_code"  =>  "352",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  131,
        "charcode"      =>  "MK",
        "charcode3"     =>  "MKD",
        "numcode"       =>  "807",
        "name"          =>  "Macedonia, Republic of",
        "country_code"  =>  "389",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  132,
        "charcode"      =>  "MG",
        "charcode3"     =>  "MDG",
        "numcode"       =>  "450",
        "name"          =>  "Madagascar",
        "country_code"  =>  "261",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  133,
        "charcode"      =>  "MW",
        "charcode3"     =>  "MWI",
        "numcode"       =>  "454",
        "name"          =>  "Malawi",
        "country_code"  =>  "265",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  134,
        "charcode"      =>  "MY",
        "charcode3"     =>  "MYS",
        "numcode"       =>  "458",
        "name"          =>  "Malaysia",
        "country_code"  =>  "60",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  135,
        "charcode"      =>  "MV",
        "charcode3"     =>  "MDV",
        "numcode"       =>  "462",
        "name"          =>  "Maldives",
        "country_code"  =>  "960",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  136,
        "charcode"      =>  "ML",
        "charcode3"     =>  "MLI",
        "numcode"       =>  "466",
        "name"          =>  "Mali",
        "country_code"  =>  "223",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  137,
        "charcode"      =>  "MT",
        "charcode3"     =>  "MLT",
        "numcode"       =>  "470",
        "name"          =>  "Malta",
        "country_code"  =>  "356",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  138,
        "charcode"      =>  "MH",
        "charcode3"     =>  "MHL",
        "numcode"       =>  "584",
        "name"          =>  "Marshall Islands",
        "country_code"  =>  "692",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  139,
        "charcode"      =>  "MQ",
        "charcode3"     =>  "MTQ",
        "numcode"       =>  "474",
        "name"          =>  "Martinique",
        "country_code"  =>  "596",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  140,
        "charcode"      =>  "MR",
        "charcode3"     =>  "MRT",
        "numcode"       =>  "478",
        "name"          =>  "Mauritania",
        "country_code"  =>  "222",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  141,
        "charcode"      =>  "MU",
        "charcode3"     =>  "MUS",
        "numcode"       =>  "480",
        "name"          =>  "Mauritius",
        "country_code"  =>  "230",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  142,
        "charcode"      =>  "YT",
        "charcode3"     =>  "MYT",
        "numcode"       =>  "175",
        "name"          =>  "Mayotte",
        "country_code"  =>  "262",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  143,
        "charcode"      =>  "MX",
        "charcode3"     =>  "MEX",
        "numcode"       =>  "484",
        "name"          =>  "Mexico",
        "country_code"  =>  "52",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  144,
        "charcode"      =>  "FM",
        "charcode3"     =>  "FSM",
        "numcode"       =>  "583",
        "name"          =>  "Micronesia, Federated States of",
        "country_code"  =>  "691",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  145,
        "charcode"      =>  "MD",
        "charcode3"     =>  "MDA",
        "numcode"       =>  "498",
        "name"          =>  "Moldova",
        "country_code"  =>  "373",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  146,
        "charcode"      =>  "MC",
        "charcode3"     =>  "MCO",
        "numcode"       =>  "492",
        "name"          =>  "Monaco",
        "country_code"  =>  "377",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  147,
        "charcode"      =>  "MN",
        "charcode3"     =>  "MNG",
        "numcode"       =>  "496",
        "name"          =>  "Mongolia",
        "country_code"  =>  "976",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  148,
        "charcode"      =>  "ME",
        "charcode3"     =>  "MNE",
        "numcode"       =>  "499",
        "name"          =>  "Montenegro",
        "country_code"  =>  "382",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  149,
        "charcode"      =>  "MS",
        "charcode3"     =>  "MSR",
        "numcode"       =>  "500",
        "name"          =>  "Montserrat",
        "country_code"  =>  "664",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  150,
        "charcode"      =>  "MA",
        "charcode3"     =>  "MAR",
        "numcode"       =>  "504",
        "name"          =>  "Morocco",
        "country_code"  =>  "212",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  151,
        "charcode"      =>  "MZ",
        "charcode3"     =>  "MOZ",
        "numcode"       =>  "508",
        "name"          =>  "Mozambique",
        "country_code"  =>  "258",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  152,
        "charcode"      =>  "MM",
        "charcode3"     =>  "MMR",
        "numcode"       =>  "104",
        "name"          =>  "Myanmar",
        "country_code"  =>  "95",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  153,
        "charcode"      =>  "NA",
        "charcode3"     =>  "NAM",
        "numcode"       =>  "516",
        "name"          =>  "Namibia",
        "country_code"  =>  "264",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  154,
        "charcode"      =>  "NR",
        "charcode3"     =>  "NRU",
        "numcode"       =>  "520",
        "name"          =>  "Nauru",
        "country_code"  =>  "674",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  155,
        "charcode"      =>  "NP",
        "charcode3"     =>  "NPL",
        "numcode"       =>  "524",
        "name"          =>  "Nepal",
        "country_code"  =>  "977",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  156,
        "charcode"      =>  "NL",
        "charcode3"     =>  "NLD",
        "numcode"       =>  "528",
        "name"          =>  "Netherlands",
        "country_code"  =>  "31",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  157,
        "charcode"      =>  "AN",
        "charcode3"     =>  "ANT",
        "numcode"       =>  "530",
        "name"          =>  "Netherlands Antilles",
        "country_code"  =>  "599",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  158,
        "charcode"      =>  "NC",
        "charcode3"     =>  "NCL",
        "numcode"       =>  "540",
        "name"          =>  "New Caledonia",
        "country_code"  =>  "687",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  159,
        "charcode"      =>  "NZ",
        "charcode3"     =>  "NZL",
        "numcode"       =>  "554",
        "name"          =>  "New Zealand",
        "country_code"  =>  "64",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  160,
        "charcode"      =>  "NI",
        "charcode3"     =>  "NIC",
        "numcode"       =>  "558",
        "name"          =>  "Nicaragua",
        "country_code"  =>  "505",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  161,
        "charcode"      =>  "NE",
        "charcode3"     =>  "NER",
        "numcode"       =>  "562",
        "name"          =>  "Niger",
        "country_code"  =>  "227",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  162,
        "charcode"      =>  "NG",
        "charcode3"     =>  "NGA",
        "numcode"       =>  "566",
        "name"          =>  "Nigeria",
        "country_code"  =>  "234",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  163,
        "charcode"      =>  "NU",
        "charcode3"     =>  "NIU",
        "numcode"       =>  "570",
        "name"          =>  "Niue ",
        "country_code"  =>  "683",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  164,
        "charcode"      =>  "NF",
        "charcode3"     =>  "NFK",
        "numcode"       =>  "574",
        "name"          =>  "Norfolk Island",
        "country_code"  =>  "672",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  165,
        "charcode"      =>  "MP",
        "charcode3"     =>  "MNP",
        "numcode"       =>  "580",
        "name"          =>  "Northern Mariana Islands",
        "country_code"  =>  "1 670",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  166,
        "charcode"      =>  "NO",
        "charcode3"     =>  "NOR",
        "numcode"       =>  "578",
        "name"          =>  "Norway",
        "country_code"  =>  "47",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  167,
        "charcode"      =>  "OM",
        "charcode3"     =>  "OMN",
        "numcode"       =>  "512",
        "name"          =>  "Oman",
        "country_code"  =>  "968",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  168,
        "charcode"      =>  "PK",
        "charcode3"     =>  "PAK",
        "numcode"       =>  "586",
        "name"          =>  "Pakistan",
        "country_code"  =>  "92",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  169,
        "charcode"      =>  "PW",
        "charcode3"     =>  "PLW",
        "numcode"       =>  "585",
        "name"          =>  "Palau",
        "country_code"  =>  "680",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  170,
        "charcode"      =>  "PS",
        "charcode3"     =>  "PSE",
        "numcode"       =>  "275",
        "name"          =>  "Palestine‎",
        "country_code"  =>  "970",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  171,
        "charcode"      =>  "PA",
        "charcode3"     =>  "PAN",
        "numcode"       =>  "591",
        "name"          =>  "Panama",
        "country_code"  =>  "507",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  172,
        "charcode"      =>  "PG",
        "charcode3"     =>  "PNG",
        "numcode"       =>  "598",
        "name"          =>  "Papua New Guinea",
        "country_code"  =>  "675",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  173,
        "charcode"      =>  "PY",
        "charcode3"     =>  "PRY",
        "numcode"       =>  "600",
        "name"          =>  "Paraguay",
        "country_code"  =>  "595",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  174,
        "charcode"      =>  "PE",
        "charcode3"     =>  "PER",
        "numcode"       =>  "604",
        "name"          =>  "Peru",
        "country_code"  =>  "51",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  175,
        "charcode"      =>  "PH",
        "charcode3"     =>  "PHL",
        "numcode"       =>  "608",
        "name"          =>  "Philippines",
        "country_code"  =>  "63",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  176,
        "charcode"      =>  "PN",
        "charcode3"     =>  "PCN",
        "numcode"       =>  "612",
        "name"          =>  "Pitcairn",
        "country_code"  =>  "870",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  177,
        "charcode"      =>  "PL",
        "charcode3"     =>  "POL",
        "numcode"       =>  "616",
        "name"          =>  "Poland",
        "country_code"  =>  "48",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  178,
        "charcode"      =>  "PT",
        "charcode3"     =>  "PRT",
        "numcode"       =>  "620",
        "name"          =>  "Portugal",
        "country_code"  =>  "351",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  179,
        "charcode"      =>  "PR",
        "charcode3"     =>  "PRI",
        "numcode"       =>  "630",
        "name"          =>  "Puerto Rico",
        "country_code"  =>  "1",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  180,
        "charcode"      =>  "QA",
        "charcode3"     =>  "QAT",
        "numcode"       =>  "634",
        "name"          =>  "Qatar",
        "country_code"  =>  "974",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  181,
        "charcode"      =>  "RE",
        "charcode3"     =>  "REU",
        "numcode"       =>  "638",
        "name"          =>  "R?union",
        "country_code"  =>  "262",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  182,
        "charcode"      =>  "RO",
        "charcode3"     =>  "ROU",
        "numcode"       =>  "642",
        "name"          =>  "Romania",
        "country_code"  =>  "40",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  183,
        "charcode"      =>  "RU",
        "charcode3"     =>  "RUS",
        "numcode"       =>  "643",
        "name"          =>  "Russian Federation",
        "country_code"  =>  "7",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  184,
        "charcode"      =>  "RW",
        "charcode3"     =>  "RWA",
        "numcode"       =>  "646",
        "name"          =>  "Rwanda",
        "country_code"  =>  "250",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  185,
        "charcode"      =>  "BL",
        "charcode3"     =>  "BLM",
        "numcode"       =>  "652",
        "name"          =>  "Saint-Barth?lemy",
        "country_code"  =>  "590",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  186,
        "charcode"      =>  "SH",
        "charcode3"     =>  "SHN",
        "numcode"       =>  "654",
        "name"          =>  "Saint Helena",
        "country_code"  =>  "290",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  187,
        "charcode"      =>  "KN",
        "charcode3"     =>  "KNA",
        "numcode"       =>  "659",
        "name"          =>  "Saint Kitts and Nevis",
        "country_code"  =>  "1 869",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  188,
        "charcode"      =>  "LC",
        "charcode3"     =>  "LCA",
        "numcode"       =>  "662",
        "name"          =>  "Saint Lucia",
        "country_code"  =>  "1 758",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  189,
        "charcode"      =>  "MF",
        "charcode3"     =>  "MAF",
        "numcode"       =>  "663",
        "name"          =>  "Saint-Martin (French part)",
        "country_code"  =>  "1 599",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  190,
        "charcode"      =>  "PM",
        "charcode3"     =>  "SPM",
        "numcode"       =>  "666",
        "name"          =>  "Saint Pierre and Miquelon ",
        "country_code"  =>  "508",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  191,
        "charcode"      =>  "VC",
        "charcode3"     =>  "VCT",
        "numcode"       =>  "670",
        "name"          =>  "Saint Vincent and Grenadines",
        "country_code"  =>  "1 784",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  192,
        "charcode"      =>  "WS",
        "charcode3"     =>  "WSM",
        "numcode"       =>  "882",
        "name"          =>  "Samoa",
        "country_code"  =>  "685",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  193,
        "charcode"      =>  "SM",
        "charcode3"     =>  "SMR",
        "numcode"       =>  "674",
        "name"          =>  "San Marino",
        "country_code"  =>  "378",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  194,
        "charcode"      =>  "ST",
        "charcode3"     =>  "STP",
        "numcode"       =>  "678",
        "name"          =>  "Sao Tome and Principe",
        "country_code"  =>  "239",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  195,
        "charcode"      =>  "SA",
        "charcode3"     =>  "SAU",
        "numcode"       =>  "682",
        "name"          =>  "Saudi Arabia",
        "country_code"  =>  "966",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  196,
        "charcode"      =>  "SN",
        "charcode3"     =>  "SEN",
        "numcode"       =>  "686",
        "name"          =>  "Senegal",
        "country_code"  =>  "221",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  197,
        "charcode"      =>  "RS",
        "charcode3"     =>  "SRB",
        "numcode"       =>  "688",
        "name"          =>  "Serbia",
        "country_code"  =>  "381",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  198,
        "charcode"      =>  "SC",
        "charcode3"     =>  "SYC",
        "numcode"       =>  "690",
        "name"          =>  "Seychelles",
        "country_code"  =>  "248",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  199,
        "charcode"      =>  "SL",
        "charcode3"     =>  "SLE",
        "numcode"       =>  "694",
        "name"          =>  "Sierra Leone",
        "country_code"  =>  "232",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  200,
        "charcode"      =>  "SG",
        "charcode3"     =>  "SGP",
        "numcode"       =>  "702",
        "name"          =>  "Singapore",
        "country_code"  =>  "65",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  201,
        "charcode"      =>  "SK",
        "charcode3"     =>  "SVK",
        "numcode"       =>  "703",
        "name"          =>  "Slovakia",
        "country_code"  =>  "421",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  202,
        "charcode"      =>  "SI",
        "charcode3"     =>  "SVN",
        "numcode"       =>  "705",
        "name"          =>  "Slovenia",
        "country_code"  =>  "386",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  203,
        "charcode"      =>  "SB",
        "charcode3"     =>  "SLB",
        "numcode"       =>  "090",
        "name"          =>  "Solomon Islands",
        "country_code"  =>  "677",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  204,
        "charcode"      =>  "SO",
        "charcode3"     =>  "SOM",
        "numcode"       =>  "706",
        "name"          =>  "Somalia",
        "country_code"  =>  "252",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  205,
        "charcode"      =>  "ZA",
        "charcode3"     =>  "ZAF",
        "numcode"       =>  "710",
        "name"          =>  "South Africa",
        "country_code"  =>  "27",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  206,
        "charcode"      =>  "GS",
        "charcode3"     =>  "SGS",
        "numcode"       =>  "239",
        "name"          =>  "South Georgia and the South Sandwich Islands",
        "country_code"  =>  "500",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  207,
        "charcode"      =>  "SS",
        "charcode3"     =>  "SSD",
        "numcode"       =>  "728",
        "name"          =>  "South Sudan",
        "country_code"  =>  "211",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  208,
        "charcode"      =>  "ES",
        "charcode3"     =>  "ESP",
        "numcode"       =>  "724",
        "name"          =>  "Spain",
        "country_code"  =>  "34",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  209,
        "charcode"      =>  "LK",
        "charcode3"     =>  "LKA",
        "numcode"       =>  "144",
        "name"          =>  "Sri Lanka",
        "country_code"  =>  "94",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  210,
        "charcode"      =>  "SD",
        "charcode3"     =>  "SDN",
        "numcode"       =>  "736",
        "name"          =>  "Sudan",
        "country_code"  =>  "249",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  211,
        "charcode"      =>  "SR",
        "charcode3"     =>  "SUR",
        "numcode"       =>  "740",
        "name"          =>  "Suriname",
        "country_code"  =>  "597",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  212,
        "charcode"      =>  "SJ",
        "charcode3"     =>  "SJM",
        "numcode"       =>  "744",
        "name"          =>  "Svalbard and Jan Mayen Islands ",
        "country_code"  =>  "47",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  213,
        "charcode"      =>  "SZ",
        "charcode3"     =>  "SWZ",
        "numcode"       =>  "748",
        "name"          =>  "Swaziland",
        "country_code"  =>  "268",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  214,
        "charcode"      =>  "SE",
        "charcode3"     =>  "SWE",
        "numcode"       =>  "752",
        "name"          =>  "Sweden",
        "country_code"  =>  "46",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  215,
        "charcode"      =>  "CH",
        "charcode3"     =>  "CHE",
        "numcode"       =>  "756",
        "name"          =>  "Switzerland",
        "country_code"  =>  "41",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  216,
        "charcode"      =>  "SY",
        "charcode3"     =>  "SYR",
        "numcode"       =>  "760",
        "name"          =>  "Syrian Arab Republic (Syria)",
        "country_code"  =>  "963",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  217,
        "charcode"      =>  "TW",
        "charcode3"     =>  "TWN",
        "numcode"       =>  "158",
        "name"          =>  "Taiwan, Republic of China",
        "country_code"  =>  "886",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  218,
        "charcode"      =>  "TJ",
        "charcode3"     =>  "TJK",
        "numcode"       =>  "762",
        "name"          =>  "Tajikistan",
        "country_code"  =>  "992",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  219,
        "charcode"      =>  "TZ",
        "charcode3"     =>  "TZA",
        "numcode"       =>  "834",
        "name"          =>  "Tanzania *, United Republic of",
        "country_code"  =>  "255",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  220,
        "charcode"      =>  "TH",
        "charcode3"     =>  "THA",
        "numcode"       =>  "764",
        "name"          =>  "Thailand",
        "country_code"  =>  "66",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  221,
        "charcode"      =>  "TL",
        "charcode3"     =>  "TLS",
        "numcode"       =>  "626",
        "name"          =>  "Timor-Leste",
        "country_code"  =>  "670",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  222,
        "charcode"      =>  "TG",
        "charcode3"     =>  "TGO",
        "numcode"       =>  "768",
        "name"          =>  "Togo",
        "country_code"  =>  "228",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  223,
        "charcode"      =>  "TK",
        "charcode3"     =>  "TKL",
        "numcode"       =>  "772",
        "name"          =>  "Tokelau ",
        "country_code"  =>  "690",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  224,
        "charcode"      =>  "TO",
        "charcode3"     =>  "TON",
        "numcode"       =>  "776",
        "name"          =>  "Tonga",
        "country_code"  =>  "676",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  225,
        "charcode"      =>  "TT",
        "charcode3"     =>  "TTO",
        "numcode"       =>  "780",
        "name"          =>  "Trinidad and Tobago",
        "country_code"  =>  "1 868",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  226,
        "charcode"      =>  "TN",
        "charcode3"     =>  "TUN",
        "numcode"       =>  "788",
        "name"          =>  "Tunisia",
        "country_code"  =>  "216",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  227,
        "charcode"      =>  "TR",
        "charcode3"     =>  "TUR",
        "numcode"       =>  "792",
        "name"          =>  "Turkey",
        "country_code"  =>  "90",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  228,
        "charcode"      =>  "TM",
        "charcode3"     =>  "TKM",
        "numcode"       =>  "795",
        "name"          =>  "Turkmenistan",
        "country_code"  =>  "993",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  229,
        "charcode"      =>  "TC",
        "charcode3"     =>  "TCA",
        "numcode"       =>  "796",
        "name"          =>  "Turks and Caicos Islands ",
        "country_code"  =>  "1 649",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  230,
        "charcode"      =>  "TV",
        "charcode3"     =>  "TUV",
        "numcode"       =>  "798",
        "name"          =>  "Tuvalu",
        "country_code"  =>  "688",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  231,
        "charcode"      =>  "UG",
        "charcode3"     =>  "UGA",
        "numcode"       =>  "800",
        "name"          =>  "Uganda",
        "country_code"  =>  "256",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  232,
        "charcode"      =>  "UA",
        "charcode3"     =>  "UKR",
        "numcode"       =>  "804",
        "name"          =>  "Ukraine",
        "country_code"  =>  "380",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  233,
        "charcode"      =>  "AE",
        "charcode3"     =>  "ARE",
        "numcode"       =>  "784",
        "name"          =>  "United Arab Emirates",
        "country_code"  =>  "971",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  234,
        "charcode"      =>  "GB",
        "charcode3"     =>  "GBR",
        "numcode"       =>  "826",
        "name"          =>  "United Kingdom",
        "country_code"  =>  "44",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  235,
        "charcode"      =>  "US",
        "charcode3"     =>  "USA",
        "numcode"       =>  "840",
        "name"          =>  "United States of America",
        "country_code"  =>  "1",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  236,
        "charcode"      =>  "UM",
        "charcode3"     =>  "UMI",
        "numcode"       =>  "581",
        "name"          =>  "United States Minor Outlying Islands",
        "country_code"  =>  "1",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  237,
        "charcode"      =>  "UY",
        "charcode3"     =>  "URY",
        "numcode"       =>  "858",
        "name"          =>  "Uruguay",
        "country_code"  =>  "598",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  238,
        "charcode"      =>  "UZ",
        "charcode3"     =>  "UZB",
        "numcode"       =>  "860",
        "name"          =>  "Uzbekistan",
        "country_code"  =>  "998",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  239,
        "charcode"      =>  "VU",
        "charcode3"     =>  "VUT",
        "numcode"       =>  "548",
        "name"          =>  "Vanuatu",
        "country_code"  =>  "678",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  240,
        "charcode"      =>  "VE",
        "charcode3"     =>  "VEN",
        "numcode"       =>  "862",
        "name"          =>  "Venezuela (Bolivarian Republic of)",
        "country_code"  =>  "58",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  241,
        "charcode"      =>  "VN",
        "charcode3"     =>  "VNM",
        "numcode"       =>  "704",
        "name"          =>  "Viet Nam",
        "country_code"  =>  "84",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  242,
        "charcode"      =>  "VI",
        "charcode3"     =>  "VIR",
        "numcode"       =>  "850",
        "name"          =>  "Virgin Islands, US",
        "country_code"  =>  "1 340",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  243,
        "charcode"      =>  "WF",
        "charcode3"     =>  "WLF",
        "numcode"       =>  "876",
        "name"          =>  "Wallis and Futuna Islands ",
        "country_code"  =>  "681",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  244,
        "charcode"      =>  "EH",
        "charcode3"     =>  "ESH",
        "numcode"       =>  "732",
        "name"          =>  "Western Sahara",
        "country_code"  =>  "212",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  245,
        "charcode"      =>  "YE",
        "charcode3"     =>  "YEM",
        "numcode"       =>  "887",
        "name"          =>  "Yemen",
        "country_code"  =>  "967",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  246,
        "charcode"      =>  "ZM",
        "charcode3"     =>  "ZMB",
        "numcode"       =>  "894",
        "name"          =>  "Zambia",
        "country_code"  =>  "260",
        "deleted_at"    =>  null
      ],
      [
        "id"            =>  247,
        "charcode"      =>  "ZW",
        "charcode3"     =>  "ZWE",
        "numcode"       =>  "716",
        "name"          =>  "Zimbabwe",
        "country_code"  =>  "263",
        "deleted_at"    =>  null
      ],
    ]);
  }
}
