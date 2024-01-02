<?php

namespace App\Services;

use App\Models\Shop\JeduCustomer;
use Illuminate\Support\Facades\Http;
use Webkul\Customer\Contracts\Customer;
use Webkul\Sales\Models\Order;
use Webkul\User\Models\Admin;

class MoodleService
{

    public static function getCustomLoginURL(Customer $customer)
    {
        $token = config('moodle.moodle_auth_token');
        $functionname = 'auth_userkey_request_login_url';
        $root = config('moodle.moodle_address');

        if (!$root) {
            \Log::error("MOODLE ADDRESS EMPTY");
            return null;
        }

        if (!$token) {
            \Log::error("AUTH TOKEN EMPTY");
            return null;
        }

        if ($customer->incomplete) {
            \Log::error("AUTH TOKEN EMPTY");
            return null;
        }

        $data = [
            'user' => [
                'username' => $customer->national_code
            ]
        ];

        $url = $root.'/webservice/rest/server.php'.'?wstoken='.$token.'&wsfunction='.$functionname
            .'&moodlewsrestformat=json';

        try {
            $response = Http::asForm()->post($url, $data)
                ->throw();
            $body = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
            $output = [];
            if ($body) {
                if (array_key_exists('loginurl', $body)) {
                    parse_str(parse_url($body['loginurl'], PHP_URL_QUERY), $output);
                    $customer->moodle_login_key = $output['key'];
                    $customer->save();
                    return $customer;
                }

                \Log::info("getLoginURL user response:", $body);
            }

        } catch (\Exception $exception) {
            report($exception);
        }
        return null;
    }

    public static function getAdminLoginURL(Admin $user)
    {
        $token = config('moodle.moodle_auth_token');
        $functionname = 'auth_userkey_request_login_url';
        $root = config('moodle.moodle_address');

        if (!$root) {
            \Log::error("MOODLE ADDRESS EMPTY");
            return null;
        }

        if (!$token) {
            \Log::error("AUTH TOKEN EMPTY");
            return null;
        }

        $data = [
            'user' => [
                'username' => $user->username
            ]
        ];

        $url = $root.'/webservice/rest/server.php'.'?wstoken='.$token.'&wsfunction='.$functionname
            .'&moodlewsrestformat=json';

        try {
            $response = Http::asForm()->post($url, $data)
                ->throw();
            $body = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
            $output = [];

            if ($body) {
                if (array_key_exists('loginurl', $body)) {
                    //parse_str(parse_url($body['loginurl'], PHP_URL_QUERY), $output);
                    return $body['loginurl'];
                }
                \Log::info("getLoginURL user response:", $body);
            }

        } catch (\Exception $exception) {
            report($exception);
        }
        return null;
    }

    public static function createUser(JeduCustomer $customer)
    {
        $token = config('moodle.moodle_core_token');
        $functionname = 'core_user_create_users';
        $root = config('moodle.moodle_address');

        if (!$root) {
            \Log::error("MOODLE ADDRESS EMPTY");
            return false;
        }

        if (!$token) {
            \Log::error("AUTH TOKEN EMPTY");
            return false;
        }

        if ($customer->incomplete) {
            \Log::error("USER IS INCOMPLETE");
            return false;
        }
        $user = self::getUser($customer->national_code);
        if ($user) {
            \Log::info("USER Already Exist");
            return $user;
        }
        //$user1 = new stdClass();
        $data['users'][] = [
            'firstname' => $customer->first_name,
            'lastname'  => $customer->last_name,
            'username'  => $customer->national_code,
            'password'  => self::generateRandomString(8),
            'email'     => $customer->email ?: $customer->national_code."@jedu.ir",
        ];

        $url = $root.'/webservice/rest/server.php'.'?wstoken='.$token.'&wsfunction='.$functionname
            .'&moodlewsrestformat=json';
        $body = null;

        try {
            $response = Http::asForm()
                ->post($url, $data)
                ->throw();

            $body = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
            if ($body) {
                if (array_key_exists('exception', $body)) {
                    \Log::error("createUser user failed:", $body);
                    return false;
                }
                \Log::info("createUser user response:", $body);
            }

        } catch (\Exception $exception) {
            report($exception);
        }
        return $body;
    }

    /**
     * @param  Array  $customers
     *
     * @return mixed|void|null
     */
    public static function createUsers($customers)
    {
        $token = config('moodle.moodle_core_token');
        $functionname = 'core_user_create_users';
        $root = config('moodle.moodle_address');

        if (!$root) {
            \Log::error("MOODLE ADDRESS EMPTY");
            return;
        }

        if (!$token) {
            \Log::error("AUTH TOKEN EMPTY");
            return;
        }

        $data['users'] = [];
        $url = $root.'/webservice/rest/server.php'.'?wstoken='.$token.'&wsfunction='.$functionname
            .'&moodlewsrestformat=json';
        foreach ($customers as $customer) {

            if (!$customer['is_moodle_user']) {
                continue;
            }

            $data['users'][] = [
                'firstname' => $customer['first_name'],
                'lastname'  => $customer['last_name'],
                'username'  => $customer['national_code'],
                'password'  => self::generateRandomString(8),
                'email'     => $customer['email'] ?: $customer['national_code']."@jedu.ir",
            ];
        }

        $body = null;

        try {
            $response = Http::asForm()
                ->post($url, $data)
                ->throw();

            $body = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
            if ($body) {
                if (array_key_exists('exception', $body)) {
                    \Log::error("createUser user failed:", $body);
                    return null;
                }
                \Log::info("createUser user response:", $body);
            }

        } catch (\Exception $exception) {
            report($exception);
        }
        return $body;
    }

    public static function updateUser(Customer $customer)
    {
        $token = config('moodle.moodle_core_token');
        $functionname = 'core_user_update_users';
        $root = config('moodle.moodle_address');

        if (!$root) {
            \Log::error("MOODLE ADDRESS EMPTY");
            return;
        }

        if (!$token) {
            \Log::error("AUTH TOKEN EMPTY");
            return;
        }

        if ($customer->incomplete) {
            \Log::error("AUTH TOKEN EMPTY");
            return;
        }

        $user = self::getUser($customer->national_code);
        if (!$user) {
            \Log::error("USER NOT FOUND");
            return;
        }
        $user_id = $user[0]['id'];
        //$user1 = new stdClass();
        $data['users'][] = [
            'id'        => $user_id,
            'firstname' => $customer->first_name,
            'lastname'  => $customer->last_name,
            'username'  => $customer->national_code,
            'email'     => $customer->email ?: $customer->email."@jedu.ir",
        ];

        $url = $root.'/webservice/rest/server.php'.'?wstoken='.$token.'&wsfunction='.$functionname
            .'&moodlewsrestformat=json';
        $body = null;
        try {
            $response = Http::asForm()
                ->post($url, $data)
                ->throw();
            $body = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
            if ($body) {
                \Log::info("updateUser user response:", $body);
            }

        } catch (\Exception $exception) {
            report($exception);
        }
        return $body;
    }

    public static function updateUserEnrolment(Order $order)
    {
        if (!$order->items->first()?->product?->moodle_id) {
            \Log::info("Order item is not moodle type.");
            return null;
        }
        if ($order->status !== "completed"
            && $order->status !== "closed"
            && $order->status !== "canceled"
        ) {
            \Log::info("Order Status is {$order->status}, no update needed.");
            return null;
        }
        if ($order->status === "completed") {
            $resposne = self::enrolUser($order);
            if ($resposne && is_array($resposne)) {
                \Log::info("enroling user response:", $resposne);
            }
            return "";
        }
        $resposne = self::unEnrolUser($order);
        if ($resposne && is_array($resposne)) {
            \Log::info("enroling user response:", $resposne);
        }
        return "";
    }

    public static function enrolUser(Order $order)
    {
        $token = config('moodle.moodle_core_token');
        $functionname = 'enrol_manual_enrol_users';
        $root = config('moodle.moodle_address');

        if (!$root) {
            \Log::error("MOODLE ADDRESS EMPTY");
            return;
        }

        if (!$token) {
            \Log::error("AUTH TOKEN EMPTY");
            return;
        }
        if ($order->status !== "completed"
            && $order->status !== "closed"
            && $order->status !== "canceled"
        ) {
            return "unnecssary update";
        }
        $customer = $order->customer;
        $user = self::checkUser($customer);

        $user_id = $user[0]['id'];
        //$user1 = new stdClass();
        $data['enrolments'][] = [
            'roleid'   => config('moodle.moodle_student_role_id'),
            'userid'   => $user_id,
            'courseid' => $order->items->first()->product->moodle_id,
        ];

        $url = $root.'/webservice/rest/server.php'.'?wstoken='.$token.'&wsfunction='.$functionname
            .'&moodlewsrestformat=json';

        try {
            $response = Http::asForm()
                ->post($url, $data)
                ->throw();
            return json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);

        } catch (\Exception $exception) {
            report($exception);
        }
        return null;
    }

    public static function unEnrolUser(Order $order)
    {
        $token = config('moodle.moodle_core_token');
        $functionname = 'enrol_manual_unenrol_users';
        $root = config('moodle.moodle_address');

        if (!$root) {
            \Log::error("MOODLE ADDRESS EMPTY");
            return;
        }

        if (!$token) {
            \Log::error("AUTH TOKEN EMPTY");
            return;
        }
        if ($order->status !== "completed"
            && $order->status !== "closed"
            && $order->status !== "canceled"
        ) {
            return "unnecssary update";
        }
        $customer = $order->customer;

        $user = self::getUser($customer->national_code);
        if (!$user) {
            \Log::error("USER NOT FOUND");
            return;
        }
        $user_id = $user[0]['id'];
        //$user1 = new stdClass();
        $data['enrolments'][] = [
            'roleid'   => config('moodle.moodle_student_role_id'),
            'userid'   => $user_id,
            'courseid' => 118,
        ];

        $url = $root.'/webservice/rest/server.php'.'?wstoken='.$token.'&wsfunction='.$functionname
            .'&moodlewsrestformat=json';

        try {
            $response = Http::asForm()
                ->post($url, $data)
                ->throw();
            return json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);

        } catch (\Exception $exception) {
            report($exception);
        }
        return null;
    }

    public static function enrolUserInCourse($customer)
    {
        $token = config('moodle.moodle_core_token');
        $functionname = 'enrol_manual_enrol_users';
        $root = config('moodle.moodle_address');

        if (!$root) {
            \Log::error("MOODLE ADDRESS EMPTY");
            return false;
        }

        if (!$token) {
            \Log::error("AUTH TOKEN EMPTY");
            return false;
        }

        $user = self::checkUser($customer);
        if (!$user) {
            return false;
        }

        $user_id = $user[0]['id'];

        $data['enrolments'] = [];
        foreach ($customer->moodle_enrolments as $enrolments) {
            $data['enrolments'][] = [
                'roleid'   => config('moodle.moodle_student_role_id'),
                'userid'   => $user_id,
                'courseid' => $enrolments->moodle_course_id,
            ];
        }

        \Log::info("enrolments data", $data);

        $url = $root.'/webservice/rest/server.php'.'?wstoken='.$token.'&wsfunction='.$functionname
            .'&moodlewsrestformat=json';

        try {
            $response = Http::asForm()
                ->post($url, $data)
                ->throw();

            $body = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
            if (!$body) {
                return true;
            }
            if (array_key_exists('exception', $body)) {
                \Log::error("enrolUser failed:", $body);
                return false;
            }

            return $body;

        } catch (\Exception $exception) {
            report($exception);
        }
        return false;
    }

    public static function getUser($username)
    {
        $token = config('moodle.moodle_core_token');
        $functionname = 'core_user_get_users_by_field';
        $root = config('moodle.moodle_address');

        if (!$root) {
            \Log::error("MOODLE ADDRESS EMPTY");
            return;
        }

        if (!$token) {
            \Log::error("AUTH TOKEN EMPTY");
            return;
        }

        //$user1 = new stdClass();
        $data = [
            'field'  => 'username',
            'values' => [
                $username
            ]
        ];

        $url = $root.'/webservice/rest/server.php'.'?wstoken='.$token.'&wsfunction='.$functionname
            .'&moodlewsrestformat=json';

        try {
            $response = Http::asForm()
                ->post($url, $data)
                ->throw();

            return json_decode($response->body(), true);
        } catch (\Exception $exception) {

            report($exception);
        }
        return null;
    }

    public static function getUserCourses(JeduCustomer $customer)
    {
        $token = config('moodle.moodle_core_token');
        $functionname = 'joomdle_my_courses';
        $root = config('moodle.moodle_address');

        if (!$root) {
            \Log::error("MOODLE ADDRESS EMPTY");
            return false;
        }

        if (!$token) {
            \Log::error("AUTH TOKEN EMPTY");
            return false;
        }

        if ($customer->incomplete) {
            \Log::error("USER IS INCOMPLETE");
            return false;
        }

        //$user1 = new stdClass();
        $data = [
            'order_by_cat' => 1,
            'username'     => $customer->national_code,
        ];

        $url = $root.'/webservice/rest/server.php'.'?wstoken='.$token.'&wsfunction='.$functionname
            .'&moodlewsrestformat=json';
        $body = null;

        try {
            $response = Http::asForm()
                ->post($url, $data)
                ->throw();

            $body = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
            if ($body) {
                if (array_key_exists('exception', $body)) {
                    \Log::error("get user courses failed:", $body);
                    return false;
                }
                \Log::info("createUser user response:", $body);
            }

        } catch (\Exception $exception) {
            report($exception);
        }
        return $body;
    }

    protected static function generateRandomString($length = 25)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $randomString .= "1aA!";
        return $randomString;
    }

    protected static function checkUser($customer)
    {

        $user = self::getUser($customer->national_code);
        if ($user) {
            return $user;
        }

        \Log::error("USER NOT FOUND, Creating new one");
        $user = self::createUser($customer);
        if ($user) {
            return $user;
        }

        \Log::error("USER CREATION FAILED");
        return null;

    }
}
