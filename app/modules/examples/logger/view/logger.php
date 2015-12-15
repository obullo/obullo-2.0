
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

    <title>Loglama</title>

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
        <nav>
          <ul class="nav nav-pills pull-right">
            <li role="presentation"><a href="/examples">Örnekler</a></li>
            <li role="presentation"><a href="#">Dökümentasyon</a></li>
          </ul>
        </nav>
        <h2 class="text-muted"><a href="/examples/">Loglama</a></h2>
      </div>

      <div class="content description">

          <p>Bir log oluşturmak</p>

          <div class="layer example-gray">$this->logger->debug('Message', array('variables'), $priority = 10);</div>

        <p>File sürücüsü için oluşturduğunuz logu konsoldan görüntüleyebilirsiniz.</p>

        <pre>php task log</pre>

        <p>Log Seviyeleri</p>

        <pre>
$this->logger->debug();
$this->logger->error();
$this->logger->alert();
$this->logger->warning();
$this->logger->info();
$this->logger->emergency();
$this->logger->critical();
$this->logger->notice();</pre>

      </div>

      <!--
      <footer class="footer">

      </footer>
      -->

    </div> <!-- /container -->


  </body>
</html>