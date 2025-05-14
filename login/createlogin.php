<?php
require_once(__DIR__.'/../config.php');
require_once($CFG->libdir.'/moodlelib.php');

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$token = optional_param('token', '', PARAM_RAW);

if (!$token) {
    print_error('Missing token');
}
//
//$key = 'your-secret-key'; // Must match what was used to encode

try {
    global $DB;
    $decoded = base64_decode($token);
    $data = json_decode($decoded, true);
    $user = $DB->get_record('user',array('username'=> $data['username'],'id' => $data['id'],'email'=>$data['email']));
    // Find user by username or email
    if (!$user) {
        print_error('User not found');
    }

    // Log in the user
    complete_user_login($user);

    // Redirect to dashboard
    redirect(new moodle_url('/my'));
} catch (Exception $e) {
    print_error('Invalid token: ' . $e->getMessage());
}