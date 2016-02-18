<?php

namespace Examples\Recaptcha;

use Obullo\Http\Controller;

class Ajax extends Controller
{
    /**
     * Index
     * 
     * @return void
     */
    public function index()
    {
        if ($this->request->isAjax()) {

            $this->validator->setRules('name', 'Name', 'required');
            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('message', 'Your Message', 'required|max(800)');
            $this->validator->setRules('recaptcha', 'Captcha', 'recaptcha');
            $this->validator->setRules('hear', 'Last', 'required');
            $this->validator->setRules('communicate', 'Communicate', 'required|max(5)');

            if ($this->validator->isValid()) {          

                $this->form->success('Form validation success.');

            } else {
                
                $this->form->error('Form validation failed.');
            }

            return $this->response->json($this->form->outputArray());
        }

        $this->view->load('ajax');
    }

}