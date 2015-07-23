<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="/css/bootstrap.min.css" rel="stylesheet" media="screen" />
    <link href="/css/custom-styles.css" rel="stylesheet" media="screen" />
    <!--[if !IE]><!-->
      <link href="/css/small-screens.css" rel="stylesheet" media="screen" />
    <!--<![endif]-->
    <link href="/css/bootstrap-responsive.min.css" rel="stylesheet">

    <link rel="icon"
          type="image/png"
          href="<?php echo Yii::app()->baseUrl; ?>/img/favicon.png">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
    <script type="text/javascript" src="/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="/js/jquery.tablesorter.min.js"></script>    
    <script type="text/javascript" src="/js/custom.js?2014-01-17"></script>
    <script type="text/javascript" src="/js/ZeroClipboard.min.js"></script>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
    
<div class="container-fluid" id="page">

<?php echo $content; ?>

    <div class="footer">
        Delivered by GTIS, USA Studio <br />
        SIL International Inc.
    </div>
</div><!-- /container -->
<script type="text/javascript">
    ZeroClipboard.config( { swfPath: "/js/ZeroClipboard.swf" } );

    var client = new ZeroClipboard( $('.copy-button') );
    client.on( 'ready', function(event) {
        console.log( 'movie is loaded' );

        // Make Copy button visible if flash loads
        $('.copy-button').css('display','inline').css('visibility', 'visible');

        client.on( 'aftercopy', function(event) {
            console.log('Copied text to clipboard: ' + event.data['text/plain']);
        } );
    } );

    client.on( 'error', function(event) {
        // console.log( 'ZeroClipboard error of type "' + event.name + '": ' + event.message );
        ZeroClipboard.destroy();
    } );
</script>
<?php
    $this->renderPartial('//partials/google-analytics', array('user' => Yii::app()->user));
?>
</body>
</html>
