<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\View\Model\ViewModel;
use Zend\View\Helper;
use Zend\Mail;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Debug\Debug;
use Zend\Session\Container;

class Db {

  protected $adapter;
  public $myBasePath = 'http://www.wekuran.com/';

  public $configArray = array(
      "driver" => "Pdo_Mysql",
      "database" => "test_database",
      "username" => "root",
      "password" => "",
      "hostname" => "localhost");

  
  /**
   * Choose db config based on URL param "agent"
   * @return Array db info
   */
    public function chooseMyDb($db) {
        $dName = "test_database";
        if($db != ""){
            $dName = $db;
        }

        
        $configArray = array(
            "driver" => "Pdo_Mysql",
            "database" => $dName,
            "username" => "username",
            "password" => "1234",
            "hostname" => "localhost");
        
        return $configArray;
    } 
    
  /**
   * Random password generator (8 digit)
   * @return Integer 8 digit password
   */
  public static function getRandomPassNumber() {
    $pin = mt_rand(0, 99999999);
    return $pin;
  }

  /**
   * Get client+browser info
   * @return Mixed
   */
  public static function getBrowser() {
    return (
            $_SERVER['HTTP_USER_AGENT'] . ' <----> ' .
            $_SERVER['REMOTE_ADDR'] . ' <-----> ' .
            $_SERVER['HTTP_REFERER'] . ' <-----> '
            );
  }

  /**
   * Random transaction number generator
   * @param Integer $t length of number
   * @return Integer long digit number
   */
  public static function getRandomTransNumber($t = 14) {
    $p = mt_rand(1111111111, 9999999999);
    $pin = substr($p . rand(0, 9999), 0 - $t);
    return $pin;
  }


  /**
   * File upload and save
   *
   * @return String filename
   *
   */
  public function mvUploadFile() {
    
    if($_FILES['attachment']['tmp_name'] != ''){
      $filename = str_replace(" ", "", $_FILES['attachment']['name']);
      $picLoc = getcwd() . '/public/files/profilepic/';

      $type = strtolower($_FILES['attachment']['type']);
      if ($_FILES['attachment']['size'] <= 512000 && preg_match('/jpg|jpeg|gif|png/', $type)) { //512000 = 500 KB, 102400 = 100 KB

          $filename = str_replace(array('[', ']',' '), '', $filename);
          if (file_exists($picLoc.$filename)) {
            $filename = date('dmYHisa').$filename;
          }

          move_uploaded_file($_FILES['attachment']['tmp_name'], $picLoc.$filename);
          return $filename;

      } 
      else {
        return '';

      }
    }
    else{

      return '';

    }

  }
  
  
  /**
   * Captcha generate - custom theory
   * @return Object Captcha
   */
  public function myCustomCaptcha(){
    // captcha session
    $captcha_session = new Container('captcha');
    $num1 = mt_rand(0, 50);
    $num2 = mt_rand(51, 99);
    $result = $num1 + $num2;
    $captcha_session->mycaptcha = $result;
    $row = '<div style="width: 78px; height: 25px; background: #D4D0C8; text-align: center; padding-top: 6px; font-weight: bold;">' . $num1 . ' + ' .$num2 . '</div>';
    return $row;
  }
  


}
