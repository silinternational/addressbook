<?php
/**
 * Make sure Google Analytics is enabled and configured before rendering
 */
if(isset(Yii::app()->params['google_analytics']['enabled']) &&
    Yii::app()->params['google_analytics']['enabled'] &&
    isset(Yii::app()->params['google_analytics']['tracking_id'])){

    $addtl = array();

    if(!$user->isGuest){
        /**
         * If UUID is available from IdP, track as userId
         */
        if(isset($user->uuid)){
            $addtl['userId'] = $user->uuid;
        }

        /**
         * Use dimension1 to track user role
         */
        //$addtl['dimension1'] = $user->role;

    } else {
        /**
         * If user is a guest, track as a visitor
         */
        $addtl['dimension1'] = 'Visitor';
    }

    /**
     * Encode additional information as json for adding to tracking code
     */
    $extra = json_encode($addtl);
?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

        ga('create', '<?php echo Yii::app()->params['google_analytics']['tracking_id']; ?>', 'auto');
        ga('send', 'pageview', <?php echo $extra; ?>);

    </script>
<?php
}
