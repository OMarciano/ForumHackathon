<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
    
    private $fb;
    
    public function __construct()
{
    parent::__construct();
    $this->fb = new Facebook\Facebook([
        'app_id' => '396678370717648',
        'app_secret' => 'ede22c396a86bac224a8e683ebbb162f',
        'default_graph_version' => 'v2.7',
        'persistent_data_handler'=>'session'
    ]);
}
    
	public function index()
	{
        
    $helper = $this->fb->getRedirectLoginHelper();
    $permissions = ['email'];
    $loginUrl = $helper->getLoginUrl('http://localhost/fb/index.php/welcome/logado', $permissions);
    echo '<a href="' . htmlspecialchars($loginUrl) . '">Logar com Facebook!</a>';
		
	}
    
    public function logado()
{
 
    $helper = $this->fb->getRedirectLoginHelper();
 
    try {
        $accessToken = $helper->getAccessToken();
    } catch(Facebook\Exceptions\FacebookResponseException $e) {
        echo 'Erro da Graph API: ' . $e->getMessage();
        exit;
    } catch(Facebook\Exceptions\FacebookSDKException $e) {
        echo 'Erro da Facebook SDK: ' . $e->getMessage();
        exit;
    }
 
    if (isset($accessToken)) {
        $this->session->set_userdata('facebook_access_token', (string) $accessToken);
        try {
            $response = $this->fb->get('/me?fields=id,name,email,picture', $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Erro da Graph API: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Erro da Facebook SDK: ' . $e->getMessage();
            exit;
        }
    } elseif ($helper->getError()) {
        echo "Requisição negada para o usuário.";
        exit;
    }else{
        echo "Erro desconhecido.";
        exit;
    }
 
    $user = $response->getGraphUser();
 
    foreach ($user as $key => $value) {
        echo $key.": ".$value."
";
    }
 
}
}
