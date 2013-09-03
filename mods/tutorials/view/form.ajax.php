<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
            <?php echo html\css('welcome.css') ?>
        <title>Odm Ajax Tutorial</title>
        
        <?php echo html\js('jquery/*') ?> 
        <?php echo html\js('form_json/*') ?> 
        <?php echo html\js('underscore/*') ?>
        
        <?php
        // form Json Class Attributes  
        // 
        // no-top-msg:    Hide the top error message.
        // no-ajax:       Use this attribute for native posts.
        // hide-form:     Hide the form area if form submit success.
        ?>
    </head>

    <body>
        <header>
            <?php echo url\anchor('/', html\img('logo.png', ' alt="Obullo" ')) ?>
        </header>
        
        <h1>Odm Ajax Tutorial</h1>
        <section>
            <?php echo form\open('tutorials/form_ajax/post.json', array('method' => 'POST', 'class' => 'hide-form')) ?>
                <table width="100%">
                    <tr>
                        <td style="width:20%;"><?php echo form\label('Email') ?></td>
                        <td>
                        <?php echo form\error('user_email', '<div class="input-error">', '</div>'); ?>
                        <?php echo form\input('user_email', '', " id='email' ");?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo form\label('Password') ?></td>
                        <td>
                        <?php echo form\error('user_password', '<div class="input-error">', '</div>'); ?>
                        <?php echo form\password('user_password', '', " id='password' ") ?>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo form\label('Confirm') ?></td>
                        <td>
                        <?php echo form\error('user_confirm_password', '<div class="input-error">', '</div>') ?>
                        <?php echo form\password('user_confirm_password', '', " id='confirm' ") ?>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><?php echo form\submit('do_post', 'Do Post') ?></td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    </table>
                    
                    <h2>form_Json Helper</h2>
                    <p>* form_Json helper simply use php <b>json_encode($user->errors());</b> function and it send ajax response in json format.</p>
                    <p>* It send <strong>.json</strong> header when we get the request with .json extension in our form post url.</p>

            <?php echo form\close(); ?>
        </section>
        <section>
            <p>&nbsp;</p>
        </section>
        
    </body>
</html>