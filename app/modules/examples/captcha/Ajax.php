<?php

namespace Examples\Captcha;

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
        if (! $this->c->has('captcha')) {
            throw new RuntimeException("Captcha service is not defined in your components.");
        }
        if ($this->request->isAjax()) {

            $this->validator->bind($this->captcha);
            $this->validator->setRules('name', 'Name', 'required');
            $this->validator->setRules('email', 'Email', 'required|email');
            $this->validator->setRules('message', 'Your Message', 'required|max(800)');
            $this->validator->setRules('hear', 'Last', 'required');
            $this->validator->setRules('communicate', 'Communicate', 'required|max(5)');

            if ($this->validator->isValid()) {          

                $this->form->success('Form validation success.');

            } else {
                
                $this->form->error('Form validation failed.');
                $this->form->setErrors($this->validator);
            }

            return $this->response->json($this->form->outputArray());
        }

        $this->view->load('ajax');
    }

}