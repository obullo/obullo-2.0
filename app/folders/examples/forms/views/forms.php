
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    
    <!-- <link rel="icon" href="favicon.ico"> -->

    <title>Formlar</title>

    <!-- Bootstrap core CSS -->
    <link href="/assets/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/assets/css/welcome.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="container">

      <div class="header clearfix">
        <?php echo $this->view->get('examples::form_navigation') ?>
        <h2 class="text-muted"><a href="/examples/">Formlar</a></h2>
      </div>

      <div class="content">
        <ul>
          <li><a href="/examples/forms/form">Form</a></li>
          <li><a href="/examples/forms/ajax">Ajax Form</a></li>
          <li><a href="/examples/forms/element">Form Element</a></li>
          <li><a href="/examples/forms/csrf">Csrf Form</a></li>
          <li><a href="/examples/captcha">Captcha Form</a></li>
          <li><a href="/examples/captcha/ajax">Captcha Ajax Form</a></li>
          <li><a href="/examples/recaptcha">ReCaptcha Form</a></li>
          <li><a href="/examples/recaptcha/ajax">ReCaptcha Ajax Form</a></li>
        </ul>
      </div>

      <!--
      <footer class="footer">

      </footer>
      -->

    </div> <!-- /container -->

  </body>
</html>