<?php
namespace App\Helpers;
use Exception;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as phpmailerException;
use App\Models\Document;
use App\Models\FAQCategory;
use App\Models\FAQ;
use App\Models\FAQSubCategory;
use App\Models\FreeTrial;
use App\Models\Plan;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Util;
//use Slim\Http\Request as Request;
//use Slim\Http\Response as Response;
class Helpers
{
    function validateAdminSession()
    {
        if (isset($_SESSION['app']) && $_SESSION['app'] == "bazichic" && isset($_SESSION['first_name']) && isset($_SESSION['last_name']) && isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    function validateSession()
    {
        if (isset($_SESSION['app']) && $_SESSION['app'] == "bazichic" && isset($_SESSION['first_name']) && isset($_SESSION['last_name']) && isset($_SESSION['role_id'])) {
            return true;
        } else {
            return false;
        }
    }

    function checkConfigurations(Request $request, Response $response)
    {
        //require_once("dbmodels/document.crud.php");
        //$docCRUD = new DocumentCRUD(getConnection());
        //$testimonials = $docCRUD->getAllTestimonials();

        /*
            if($this->validateAdminSession()){
        }else{
        if($this->isMaintenanceModeOn()){
        
        } 
        }*/
        //$response->redirect($app->urlFor('coming-soon'), 303);
        return $response->withHeader('location', '/coming-soon');
    }

    function isMaintenanceModeOn($con)
    {
        if (SiteSetting::isMaintenanceModeOn()) {
            return true;
        } else {
            return false;
        }
    }

    public function canMembersAuthor($db)
    {
        $id = 1;
        $stmt = $db->prepare("SELECT allow_member_authoring FROM site_settings WHERE id=:id");
        $stmt->execute(array(":id" => $id));
        $result = $stmt->fetchColumn();
        return $result;
    }

//    function sendEmail($to, $subject, $body)
//    {
//        $name = "Bazichic Chinese Metaphysics Consultancy";
//        // $from = "customer_support@bazichic.com";
//        $from = 'medoroyalrma@gmail.com';
//        $headers = array(
//            "From: $from",
//            "Reply-To: $from",
//            "X-Mailer: PHP/" . PHP_VERSION
//        );
//        $headers = implode("\r\n", $headers);
//        //mail($to,$subject,$body,$headers);
//        $mail = new PHPMailer(true);
//        try {
//            // Server settings
//            $mail->isSMTP();                                  // Set mailer to use SMTP
//            $mail->Host       = $_ENV['SMTP_HOST'];           // Specify SMTP server
//            $mail->SMTPAuth   = true;                         // Enable SMTP authentication
//            $mail->Username   = $_ENV['SMTP_USERNAME'];     // SMTP username
//            $mail->Password   = $_ENV['SMTP_PASSWORD'];        // SMTP password
//            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
//            $mail->Port       = $_ENV['SMTP_PORT'];
//            // Recipients
//            $mail->setFrom($to, 'Bazichic');
//            $mail->addAddress($to);
//
//            // Content
//            $mail->isHTML(true);                              // Set email format to HTML
//            $mail->Subject = $subject;
//            $mail->Body    = $body;
//            // Send email
//            $mail->send();
//
//            return ['status' => 'success', 'message' => 'Email sent successfully!'];
//        } catch (phpmailerException $e) {
//            return ['status' => 'error', 'message' => $mail->ErrorInfo];
//        }
//    }
    function sendEmail($to, $subject, $body): array
    {
        $name = "Bazichic Chinese Metaphysics Consultancy";
        $from = $_ENV['MAIL_FROM'];  // Use your actual domain email

        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];  // Usually 'localhost' on cPanel
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USERNAME'];  // Your full email address
            $mail->Password   = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];  // Usually 587 for TLS

            // Recipients
            $mail->setFrom($from, $name);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return ['status' => 'success', 'message' => 'Email sent successfully!'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $mail->ErrorInfo];
        }
    }
    function sendContact($to, $subject, $body): array
    {
        $name = "Bazichic Chinese Metaphysics Consultancy";
        $from = $_ENV['MAIL_FROM'];  // Use your actual domain email

        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];  // Usually 'localhost' on cPanel
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USERNAME'];  // Your full email address
            $mail->Password   = $_ENV['SMTP_PASSWORD'];
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = $_ENV['SMTP_PORT'];  // Usually 587 for TLS

            // Recipients
            $mail->setFrom($from, $name);
            $mail->addAddress($_ENV['ADMIN_MAIL']);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return ['status' => 'success', 'message' => 'Email sent successfully!'];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $mail->ErrorInfo];
        }
    }
    function getDocDetails($user_id, $con)
    {
        $row = Document::getID($user_id);
        if ($row != null) {
            $tmp = array();
            $tmp["id"] = $row["id"];
            $tmp["title"] = $row["title"];
            // $tmp["price"] = $row["price"];
            $tmp["category_id"] = $row["category_id"];
            $tmp["documentType"] = $row["documentType"];
            $tmp["qcode"] = $row["qcode"];
            $tmp["cover"] = $row["cover"];
            //$tmp["date_created"] = $utilCRUD->getFormattedDate($row["date_created"]);
            return $tmp;
        }
        return NULL;
    }

    function getTrialDetails($user_id)
    {
        $row = FreeTrial::where('user_id',$user_id);
        if ($row != null) {
            $tmp = array();
            $tmp["id"] = $row->id;
            $tmp["user_id"] = $row->user_id;
            $tmp["plan_id"] = $row->plan_id;
            $tmp["plan_name"] = Plan::getNameByID($row->plan_id);
            $tmp["user_name"] = User::getNameByID($row->user_id);

            $tmp["date_created"] = Util::getFormalDate($row->date_created);
            $tmp["date_expiring"] = Util::getFormalDate($row->date_expiring);
            return $tmp;
        }
        return NULL;
    }


    function getFAQSubCategoryDetails($id)
    {

        $row = FAQSubCategory::find($id);
        if ($row != null) {
            $tmp = array();
            $tmp["id"] = $row->id;
            $tmp["title"] = $row->title;
            $tmp["category_id"] = $row->category_id;
            $tmp["category"] = FAQCategory::getNameByID($row->category_id);
            $tmp["qcode"] = $row->qcode;
            //$tmp["numFaqs"] = 11;
            $tmp["numFaqs"] = FAQ::getNumFAQsInSubCategory($row->category_id);
            //$tmp["date_created"] = $utilCRUD->getFormattedDate($row["date_created"]);
            return $tmp;
        }
        return NULL;
    }


    function getFAQDetails($user_id)
    {
        $row = FAQ::getID($user_id);
        if ($row != null) {
            $tmp = array();
            $tmp["id"] = $row["id"];
            $tmp["title"] = $row["title"];
            $tmp["description"] = $row["description"];
            $tmp["url"] = $row["url"];
            $tmp["category_id"] = $row["category_id"];
            $tmp["category"] = FAQCategory::getNameByID($row["category_id"]);
            $tmp["qcode"] = $row["qcode"];
            $tmp["subcategory_name"] = "";
            if ($row["subcategory_id"] > 0) {
                $tmp["subcategory_name"] = FAQSubCategory::find($row["subcategory_id"])->first()->title;
            }
            $tmp["date_created"] = "";
            $tmp["date_updated"] = "";

            try {
                 if(!empty($row["date_created"])){
                       $tmp["date_created"] = Util::getFormattedDate($row["date_created"]);
                     }
                 if(!empty($row["date_updated"])){
                   $tmp["date_updated"] =  Util::getFormattedDate($row["date_updated"]);
                 }
            } catch (Exception $e) {
            }
            return $tmp;
        }
        return NULL;
    }

function getRequestingAgent($request,$con)
{
	$headers = $request->getHeaders();
	$output = array();
	$output["error"] = true;
	$output["message"] = "Invalid Api key";
	$authArr = $request->getHeader("Authorization");
	$api_key = $authArr[0];
	$output["api_key"] = $api_key;
	if (isset($api_key) && !empty($api_key)) {
		if (!User::isValidApiKey($api_key)) {
			$output["error"] = true;
			$output["message"] = "Access Denied. Invalid Authorization key.";
		} else {
			$output["user_info"] = User::getUserByAPIKey($api_key);
			$output["error"] = false;
			$output["message"] = "Access Granted.";
		}
	} else {
		$output["error"] = true;
		$output["message"] = "Access Denied. No Authorization found with request.";
	}
	return $output;
}
    function getCountries()
    {
        return [
            'Afghanistan',
            'Åland Islands',
            'Albania',
            'Algeria',
            'American Samoa',
            'Andorra',
            'Angola',
            'Anguilla',
            'Antarctica',
            'Antigua and Barbuda',
            'Argentina',
            'Armenia',
            'Aruba',
            'Australia',
            'Austria',
            'Azerbaijan',
            'Bahamas',
            'Bahrain',
            'Bangladesh',
            'Barbados',
            'Belarus',
            'Belgium',
            'Belize',
            'Benin',
            'Bermuda',
            'Bhutan',
            'Bolivia',
            'Bosnia and Herzegovina',
            'Botswana',
            'Bouvet Island',
            'Brazil',
            'British Indian Ocean Territory',
            'Brunei Darussalam',
            'Bulgaria',
            'Burkina Faso',
            'Burundi',
            'Cambodia',
            'Cameroon',
            'Canada',
            'Cape Verde',
            'Cayman Islands',
            'Central African Republic',
            'Chad',
            'Chile',
            'China',
            'Christmas Island',
            'Cocos (Keeling) Islands',
            'Colombia',
            'Comoros',
            'Congo',
            'Congo, The Democratic Republic of The',
            'Cook Islands',
            'Costa Rica',
            'Cote D\'ivoire',
            'Croatia',
            'Cuba',
            'Cyprus',
            'Czech Republic',
            'Denmark',
            'Djibouti',
            'Dominica',
            'Dominican Republic',
            'Ecuador',
            'Egypt',
            'El Salvador',
            'Equatorial Guinea',
            'Eritrea',
            'Estonia',
            'Ethiopia',
            'Falkland Islands (Malvinas)',
            'Faroe Islands',
            'Fiji',
            'Finland',
            'France',
            'French Guiana',
            'French Polynesia',
            'French Southern Territories',
            'Gabon',
            'Gambia',
            'Georgia',
            'Germany',
            'Ghana',
            'Gibraltar',
            'Greece',
            'Greenland',
            'Grenada',
            'Guadeloupe',
            'Guam',
            'Guatemala',
            'Guernsey',
            'Guinea',
            'Guinea-bissau',
            'Guyana',
            'Haiti',
            'Heard Island and Mcdonald Islands',
            'Holy See (Vatican City State)',
            'Honduras',
            'Hong Kong',
            'Hungary',
            'Iceland',
            'India',
            'Indonesia',
            'Iran, Islamic Republic of',
            'Iraq',
            'Ireland',
            'Isle of Man',
            'Israel',
            'Italy',
            'Jamaica',
            'Japan',
            'Jersey',
            'Jordan',
            'Kazakhstan',
            'Kenya',
            'Kiribati',
            'Korea, Democratic People\'s Republic of',
            'Korea, Republic of',
            'Kuwait',
            'Kyrgyzstan',
            'Lao People\'s Democratic Republic',
            'Latvia',
            'Lebanon',
            'Lesotho',
            'Liberia',
            'Libyan Arab Jamahiriya',
            'Liechtenstein',
            'Lithuania',
            'Luxembourg',
            'Macao',
            'Macedonia, The Former Yugoslav Republic of',
            'Madagascar',
            'Malawi',
            'Malaysia',
            'Maldives',
            'Mali',
            'Malta',
            'Marshall Islands',
            'Martinique',
            'Mauritania',
            'Mauritius',
            'Mayotte',
            'Mexico',
            'Micronesia, Federated States of',
            'Moldova, Republic of',
            'Monaco',
            'Mongolia',
            'Montenegro',
            'Montserrat',
            'Morocco',
            'Mozambique',
            'Myanmar',
            'Namibia',
            'Nauru',
            'Nepal',
            'Netherlands',
            'Netherlands Antilles',
            'New Caledonia',
            'New Zealand',
            'Nicaragua',
            'Niger',
            'Nigeria',
            'Niue',
            'Norfolk Island',
            'Northern Mariana Islands',
            'Norway',
            'Oman',
            'Pakistan',
            'Palau',
            'Palestinian Territory, Occupied',
            'Panama',
            'Papua New Guinea',
            'Paraguay',
            'Peru',
            'Philippines',
            'Pitcairn',
            'Poland',
            'Portugal',
            'Puerto Rico',
            'Qatar',
            'Reunion',
            'Romania',
            'Russian Federation',
            'Rwanda',
            'Saint Helena',
            'Saint Kitts and Nevis',
            'Saint Lucia',
            'Saint Pierre and Miquelon',
            'Saint Vincent and The Grenadines',
            'Samoa',
            'San Marino',
            'Sao Tome and Principe',
            'Saudi Arabia',
            'Senegal',
            'Serbia',
            'Seychelles',
            'Sierra Leone',
            'Singapore',
            'Slovakia',
            'Slovenia',
            'Solomon Islands',
            'Somalia',
            'South Africa',
            'South Georgia and The South Sandwich Islands',
            'Spain',
            'Sri Lanka',
            'Sudan',
            'Suriname',
            'Svalbard and Jan Mayen',
            'Swaziland',
            'Sweden',
            'Switzerland',
            'Syrian Arab Republic',
            'Taiwan, Province of China',
            'Tajikistan',
            'Tanzania, United Republic of',
            'Thailand',
            'Timor-leste',
            'Togo',
            'Tokelau',
            'Tonga',
            'Trinidad and Tobago',
            'Tunisia',
            'Turkey',
            'Turkmenistan',
            'Turks and Caicos Islands',
            'Tuvalu',
            'Uganda',
            'Ukraine',
            'United Arab Emirates',
            'United Kingdom',
            'United States',
            'United States Minor Outlying Islands',
            'Uruguay',
            'Uzbekistan',
            'Vanuatu',
            'Venezuela',
            'Viet Nam',
            'Virgin Islands, British',
            'Virgin Islands, U.S.',
            'Wallis and Futuna',
            'Western Sahara',
            'Yemen',
            'Zambia',
            'Zimbabwe'
        ];
    }

}