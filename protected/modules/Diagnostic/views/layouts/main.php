<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <?php Yii::app()->clientScript->registerCoreScript('jquery');?>
    <title>YiiXpress Diagnostic Tool</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="<?php echo Yii::app()->theme->baseUrl;?>/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <style type="text/css">
      body {
        padding-top: 20px;
        padding-bottom: 40px;
      }

      /* Custom container */
      .container-narrow {
        margin: 0 auto;
        max-width: 700px;
      }
      .container-narrow > hr {
        margin: 30px 0;
      }

      /* Main marketing message and sign up button */
      .jumbotron {
        margin: 60px 0;
        text-align: center;
      }
      .jumbotron h1 {
        font-size: 72px;
        line-height: 1;
      }
      .jumbotron .btn {
        font-size: 21px;
        padding: 14px 24px;
      }

      /* Supporting marketing content */
      .marketing {
        margin: 60px 0;
      }
      .marketing p + h4 {
        margin-top: 28px;
      }
    </style>
  </head>

  <body>

    <div class="container-narrow">
        
      <div class="masthead">
        <ul class="nav nav-pills pull-right">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="#">Tools</a></li>
        </ul>
        <h3 class="muted">YiiXpress</h3>
      </div>

      <hr>

      <div class="jumbotron">
        <h1>Diagnostic Tool</h1>
        <p class="lead">This tool helps you to identify common issues while deploying an YiiXpress system. Run it when reload database, deploy to new domain, change the code base folder,...</p>
        <?php
            if(!isset(Yii::app()->session['toolIsStarted']) || Yii::app()->session['toolIsStarted'] != 1) {
        ?>
        <form action="/diagnostics.php" method="POST">
        <input type="hidden" name="toolstarted" value="1"/>
        <button class="btn btn-large btn-success" id="startDiagnose" type="submit">Start Diagnose</button>
        </form>
        <?php
            }
        ?>
      </div>

      <hr>

      <div class="row-fluid marketing">
        <?php echo $content;?>
      </div>

      <hr>

      <div class="footer">
        <p>&copy; YiiXpress <?php echo date('Y');?></p>
      </div>

    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <script src="<?php echo Yii::app()->theme->baseUrl;?>/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
