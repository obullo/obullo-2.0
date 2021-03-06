<?php

namespace Examples\Captcha;

use RuntimeException;
use Obullo\Http\Controller;

class Captcha extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isPost()) {

            $this->validator->setRules('email', 'Email', 'required|trim|email|max(100)');
            $this->validator->setRules('captcha_answer', 'Captcha', 'required|captcha');

            if ($this->validator->isValid()) {
                $this->form->success('Form Validation Success.');
            }
        }
        $this->view->load('captcha');
    }
}