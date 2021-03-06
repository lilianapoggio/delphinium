<?php namespace Delphinium\Blackberry\Controllers;

use Illuminate\Routing\Controller;
use Delphinium\Blackberry\Classes\OAuth2Response;
use Delphinium\Blackberry\Models\Developer as LtiConfigurations;
use Delphinium\Blackberry\Models\User;

class OAuthResponse extends Controller
{
    public function saveUserInfo()
    {
        session_start();
        $code = \Input::get('code');
        $lti = \Input::get('lti');
        
        $instanceFromDB = LtiConfigurations::find($lti);
		
	$clientId = $instanceFromDB['DeveloperId'];
        $developerSecret = $instanceFromDB['DeveloperSecret'];
        
        $opts = array('http' => array( 'method'  => 'POST', ));
	$context  = stream_context_create($opts);
        $url = "https://uvu.instructure.com/login/oauth2/token?client_id={$clientId}&client_secret={$developerSecret}&code={$code}";
	$userTokenJSON = file_get_contents($url, false, $context, -1, 40000);
	$userToken = json_decode($userTokenJSON);
        
        $actualToken = $userToken->access_token;
        $encryptedToken = $actualToken;//\Crypt::encrypt($actualToken);
        $_SESSION['userToken'] = $encryptedToken;
        
	//store encrypted token in the database
	$courseId = $_SESSION['courseID'];
	$userId=$_SESSION['userID'];
        
        $user = new User();
        $user->user_id = $userId;
        $user->course_id = $courseId;
//        $user->encrypted_token = $encryptedToken;
        $user->encrypted_token = $encryptedToken;
        $user->save();
        
        $url = 'https://delphinium.uvu.edu/octobercms/new-iris';
        $this->redirect($url);
        
    } 
    
    function redirect($url)
	{ 
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>'; exit;
        
	}
}