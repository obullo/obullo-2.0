<!DOCTYPE html>
<html>
    <head>
        <title>Add Form</title>
        <meta charset="utf-8">

        <link rel="stylesheet" href="/assets/jelly/css/bootstrap.min.css">
        <link rel="stylesheet" href="/assets/jelly/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="/assets/jelly/css/style.css">

    </head>
    <body>
        <div class="container">
            <div class="row">
            
                <?php echo $this->view->load('list_forms', false); ?>

                <div class="page-header"><h4>Add Form</h4></div>
                <?php echo $this->form->getMessage($this->flash); ?>
                <hr>

                <?php echo $formData; ?>
                
            </div>      
        </div>
    </body>
</html>