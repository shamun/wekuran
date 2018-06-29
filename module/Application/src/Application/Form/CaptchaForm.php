<?php //

namespace Application\Form;

use Zend\Form\Form,
    Zend\Form\Element\Captcha,
    Zend\Captcha\Image as CaptchaImage;

class CaptchaForm extends Form
{
    public function __construct($urlcaptcha = null)
    {
        parent::__construct('FrmCaptcha');
        $this->setAttribute('method', 'post');

        $dirdata = getcwd() . '/public/files';

        //pass captcha image options
        $captchaImage = new CaptchaImage(  array(
                'font' => $dirdata . '/verdana.ttf',
                'expiration' => 10,
                'width' => 100,
                'height' => 75,
                'wordLen' => 3,
                'dotNoiseLevel' => 5,
                'lineNoiseLevel' => 5)
        );
        $captchaImage->setImgDir($dirdata.'/captcha');
        $captchaImage->setImgUrl($urlcaptcha);

        //add captcha element...
        $this->add(array(
            'type' => 'Zend\Form\Element\Captcha',
            'name' => 'captcha',
            'attributes' => array(
                'class' => 'textbox2',
                'style' => 'margin-left:170px;'
            ),
            'options' => array(
                'label' => 'Please enter this code ',
                'captcha' => $captchaImage,
            ),
        ));
        
        
        /*$this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Test Captcha Now'
            ),
        ));*/
    }

}

?>
