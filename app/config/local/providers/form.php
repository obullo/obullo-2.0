<?php

return array(
    
    'params' => [
    
        /**
        * Form message templates.
        * 
        * Note : Default CSS brought from getbootstrap.com
         */
        'notification' => 
        [
            'message' => '<div class="{class}">{icon}{message}</div>',
            'error'  => [
                'class' => 'alert alert-danger', 
                'icon' => '<span class="glyphicon glyphicon-remove-sign"></span> '
            ],
            'success' => [
                'class' => 'alert alert-success', 
                'icon' => '<span class="glyphicon glyphicon-ok-sign"></span> '
            ],
            'warning' => [
                'class' => 'alert alert-warning', 
                'icon' => '<span class="glyphicon glyphicon-exclamation-sign"></span> '
            ],
            'info' => [
                'class' => 'alert alert-info', 
                'icon' => '<span class="glyphicon glyphicon-info-sign"></span> '
            ],
        ],
        /**
        * Form validation errors.
         */
        'validation' => [

            'error' => [
                'class' => 'has-error has-feedback',
                'label' => '<label class="control-label" for="%s">%s</label>
                <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>'
            ]
        ]
    ],

);