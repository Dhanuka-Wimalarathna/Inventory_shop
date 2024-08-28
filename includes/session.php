<?php
session_start();

class Session {

 public $msg;
 private $user_is_logged_in = false;

 function __construct(){
   $this->flash_msg();  // Load any flash messages
   $this->userLoginSetup();  // Check if user is logged in
 }

  // Check if the user is logged in
  public function isUserLoggedIn(){
    return $this->user_is_logged_in;
  }

  // Log in the user by setting the session user_id
  public function login($user_id){
    $_SESSION['user_id'] = $user_id;
  }

  // Set up login status based on session data
  private function userLoginSetup() {
    if(isset($_SESSION['user_id'])) {
      $this->user_is_logged_in = true;
    } else {
      $this->user_is_logged_in = false;
    }
  }

  // Log out the user by unsetting the session user_id
  public function logout(){
    unset($_SESSION['user_id']);
  }

  // Store or retrieve session messages
  public function msg($type ='', $msg =''){
    if(!empty($msg)){
       if(strlen(trim($type)) == 1){
         $type = str_replace(array('d', 'i', 'w', 's'), array('danger', 'info', 'warning', 'success'), $type );
       }
       $_SESSION['msg'][$type] = $msg;
    } else {
      return $this->msg;
    }
  }

  // Load and clear flash messages from the session
  private function flash_msg(){
    if(isset($_SESSION['msg'])) {
      $this->msg = $_SESSION['msg'];
      unset($_SESSION['msg']);
    }
  }
}

// Initialize session and messages
$session = new Session();
$msg = $session->msg();

?>
