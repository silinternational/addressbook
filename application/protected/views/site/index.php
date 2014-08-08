<?php
/* @var $advanced bool */
/* @var $model SearchForm */
/* @var $results array */
/* @var $this SiteController */

// Show any search values in the page title.
$activeSearchValues = $model->getActiveSearchValuesAsString();
$this->pageTitle = Yii::app()->name .
                   ((strlen($activeSearchValues) > 0) ?
                    ': ' . $activeSearchValues :
                    '');

?>
<div id="search-forms-container">
    <?php
    echo CHtml::errorSummary($model,
        '<div class="alert alert-error">', // . '<button type="button" class="close" data-dismiss="alert">&times;</button> ',
        '</div>');
    ?>
    <div class="top-links">
        <a href="<?php echo Yii::app()->createUrl('/auth/logout'); ?>"
           class="pull-left small-link">
            logout
        </a>
        <a href="javascript:void(0)" id="form-toggler" class="pull-right small-link"
           onclick="cstm.toggleAdvanced()">advanced</a>
    </div>

    <h2 class="search-forms-heading">Find someone</h2>

    <?php
    /** @var CActiveForm */
    $form = $this->beginWidget('CActiveForm', array('id' => 'search-form',
                                                    'action' => '/',
                                                    'method' => 'get'));
        ?>
        <div id="basic-search" class="clearfix">
            <?php
            echo $form->textField($model, 'any', array(
                'autofocus' => 'autofocus',
                'class' => 'input-block-level',
                'id' => 'basic-search-field',
                'onkeyup' => 'cstm.basicInputTrigger(this, event);',
                'oninput' => 'cstm.basicInputTrigger(this, event);'
            ));
            ?>
            <button class="btn btn-primary pull-right" 
                    type="submit">Search</button>
            <small class="muted">by name, email, or title</small>
        </div>

        <div id="advanced-search" class="clearfix">
            <div class="adv-field-container"> <?php
                echo $form->labelEx($model,'first');
                echo $form->textField($model, 'first');
            ?> </div>
            <div class="adv-field-container"> <?php
                echo $form->labelEx($model,'last');
                echo $form->textField($model, 'last');
            ?> </div>
            <div class="adv-field-container"> <?php
                echo $form->labelEx($model,'email');
                echo $form->textField($model, 'email');
            ?> </div>
            <div class="adv-field-container"> <?php
                echo $form->labelEx($model,'title');
                echo $form->textField($model, 'title');
            ?> </div>
            <button class="btn btn-primary pull-right" type="submit">Search</button>
        </div>
    
<!--        <!--<div class="muted" style="margin-top: 0.83ex"> --><?php
//           //echo $form->checkBox($model, 'functional', array(
//               'class' => 'pull-left',
//               'style' => 'margin-right: 0.83ex'
//           //));
//           echo //$form->labelEx($model, 'functional');
//        ?><!-- </div>-->
        <?php
    $this->endWidget();
    ?>
</div>

<?php

// If our results variable is not null...
if (!is_null($results)) {
    
    // Show the results, etc.
    ?>
    <div id="small-screen-sort-links">
        <b>Sort:</b> &nbsp;
        <a href="javascript:void(0)" id="sort-first">Given Name</a>&nbsp;|
        <a href="javascript:void(0)" id="sort-last">Family Name</a>&nbsp;|
        <a href="javascript:void(0)" id="sort-email">Email</a>&nbsp;|
        <a href="javascript:void(0)" id="sort-phone">Phone(s)</a>&nbsp;|
        <a href="javascript:void(0)" id="sort-title">Title</a>&nbsp;|
        <a href="javascript:void(0)" id="sort-spouse">Spouse</a>&nbsp;|
        <a href="javascript:void(0)" id="sort-entity">Entity</a>&nbsp;|
        <a href="javascript:void(0)" id="sort-manager">Manager</a>
    </div>
    <div id="results-container-outer">
        <div id="results-container">
            <?php

            // If there were no results, say so.
            if (count($results) < 1) {
                ?><div id="no-results"> No results </div><?php
            }
            // Otherwise...
            else {

                // Start the results table.
                ?>
                <table class="table table-hover" id="search-results">
                    <thead>
                        <tr>
                            <th title="Click to sort by first name">Given Name </th>
                            <th title="Click to sort by last name">Family Name </th>
                            <th title="Click to sort by email address">Email </th>
                            <th title="Click to sort by phone number">Phone(s) </th>
                            <th title="Click to sort by job title">Title </th>
                            <th title="Click to sort by spouse's name">Spouse </th>
                            <th title="Click to sort by entity">Entity </th>
                            <th title="Click to sort by manager">Manager </th>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody id="results-tbody">
                        <?php

                        // Show each of the results.
                        foreach ($results as $result) {

                            /**
                             * Combine appropriate result attributes into a string for
                             * zeroclipboard to use for Copy button
                             */
                            $fullDetails = '';
                            foreach(Yii::app()->params['copyAttributes'] as $attr){
                                /**
                                 * This is ugly, but basically it is making sure the attribute
                                 * is set, it is not an empty string, and that it is either
                                 * not an array, or if it is an array that it has more than
                                 * zero items in it.
                                 * This makes sure we don't include empty or missing values
                                 */
                                if(isset($result[$attr]) && $result[$attr] != '' &&
                                    (!is_array($result[$attr]) ||
                                        (is_array($result[$attr]) && count($result[$attr]) > 0)
                                    )
                                ){
                                    $fullDetails .= ucfirst($attr).': ';
                                    if($attr == 'phone' && is_array($result[$attr])){
                                        foreach($result[$attr] as $idx => $number){
                                            $fullDetails .= ucfirst($number['type']).': '.CHtml::encode($number['number']).', ';
                                        }
                                    } else {
                                        $fullDetails .= CHtml::encode($result[$attr]);
                                    }
                                    $fullDetails .= PHP_EOL;
                                }
                            }

                            ?>
                            <tr>
                                <td><?php echo CHtml::encode($result['first']); ?></td>
                                <td><?php echo CHtml::encode($result['last']); ?></td>
                                <td><?php
                                    echo sprintf('<a href="mailto:%s">%s</a>',
                                        CHtml::encode($result['email']),
                                        str_replace('@', '<wbr>@', CHtml::encode($result['email']))
                                    );
                                ?></td>
                                <td><?php
                                    $i = 0;
                                    $len = count($result['phone']);
                                    //echo "<pre>".print_r($result['phone'],true)."</pre>";
                                    foreach($result['phone'] as $idx => $number){
                                        //echo print_r($number,true);
                                        switch($number['type']){
                                            case 'mobile':
                                                $icon = 'icon-signal';
                                                break;
                                            case 'home':
                                                $icon = 'icon-home';
                                                break;
                                            case 'work':
                                                $icon = 'icon-briefcase';
                                                break;
                                            default:
                                                $icon = 'icon-user';
                                                break;
                                        }
                                        echo "<i class='$icon'></i> ";
                                        echo sprintf('<a href="tel:%s" class="hastooltip" title="%s Phone">%s</a>',
                                            CHtml::encode($number['number']),
                                            ucfirst($number['type']),
                                            CHtml::encode($number['number'])
                                        );
                                        // Add break tag if there are more numbers to show
                                        if($i++ < $len){
                                            echo "<br />";
                                        }
                                    }
                                ?></td>
                                <td><?php
                                    if ($result['title']) {
                                        ?><i class="small-screen-inline">Title: </i><?php 
                                        echo CHtml::encode($result['title']);
                                    }
                                ?></td>
                                <td><?php
                                    if ($result['spouse']) {
                                        ?><i class="small-screen-inline">Spouse: </i><?php 
                                        
                                        // Split the spouse name on spaces.
                                        $spsNameParts = explode(' ', $result['spouse']);
                                        
                                        // Show the spouse's name as a link to
                                        // search for them.
                                        echo '<a href="/?SearchForm[first]=' .
                                             rawurlencode(array_shift($spsNameParts)) .
                                             '&SearchForm[last]=' .
                                             rawurlencode(array_pop($spsNameParts)) .
                                             '">' .
                                             CHtml::encode($result['spouse']) .
                                             '</a>';
                                    }
                                ?></td>
                                <td><?php
                                    if ($result['entity']) {
                                        ?><i class="small-screen-inline">Entity: </i><?php 
                                        echo CHtml::encode($result['entity']);
                                    }
                                ?></td>
                                <td><?php
                                    if ($result['manager']) {
                                        ?><i class="small-screen-inline">Manager: </i><?php 
                                        
                                        // Split the manager name on spaces.
                                        $mgrNameParts = explode(' ', $result['manager']);
                                        
                                        // Show the manager's name as a link to
                                        // search for them.
                                        echo '<a href="/?SearchForm[first]=' .
                                             rawurlencode(array_shift($mgrNameParts)) .
                                             '&SearchForm[last]=' .
                                             rawurlencode(array_pop($mgrNameParts)) .
                                             '">' .
                                             CHtml::encode($result['manager']) .
                                             '</a>';
                                    }
                                ?></td>
                                <td>
                                    <button class="copy-button btn btn-default btn-mini"
                                            data-clipboard-text="<?php echo $fullDetails; ?>"
                                            title="Copy Contact Details">Copy</button>
                                </td>
                            </tr>
                            <?php
                        }

                        // End the table.
                        ?>
                    </tbody>
                </table>
                <script type="text/javascript">
                // Enable sorting the search-results table using JavaScript links
                // (shown for small screens).
                $(document).ready(function() {
                  $("#search-results").tablesorter({ 
                    cssAsc: 'headerSortUp',
                    cssDesc: 'headerSortDown',
                    cssHeader: 'headerSort'
                  });
                  $("#sort-first").click(function() {
                      var sorting = [[0,0],[1,0]];
                      $("table").trigger("sorton",[sorting]);
                  });
                  $("#sort-last").click(function() {
                      var sorting = [[1,0],[0,0]];
                      $("table").trigger("sorton",[sorting]);
                  });
                  $("#sort-email").click(function() {
                      var sorting = [[2,0]];
                      $("table").trigger("sorton",[sorting]);
                  });
                  $("#sort-phone").click(function() {
                      var sorting = [[3,0]];
                      $("table").trigger("sorton",[sorting]);
                  });
                  $("#sort-title").click(function() {
                      var sorting = [[4,0]];
                      $("table").trigger("sorton",[sorting]);
                  });
                  $("#sort-spouse").click(function() {
                      var sorting = [[5,0]];
                      $("table").trigger("sorton",[sorting]);
                  });
                  $("#sort-entity").click(function() {
                      var sorting = [[6,0]];
                      $("table").trigger("sorton",[sorting]);
                  });
                  $("#sort-manager").click(function() {
                      var sorting = [[7,0]];
                      $("table").trigger("sorton",[sorting]);
                  });

                  // Go ahead and sort by first name then last name automatically.
                  var sorting = [[0,0], [1,0]];
                  $("table").trigger("sorton",[sorting]);
                });
                </script>
                <?php
            }

            ?>
        </div>
    </div>
    <?php
}

?>
<script type="text/javascript">

$(function() {

    // Setup our custom JavaScript object.
    cstm.setup();

    <?php

    // If told to show the advanced form initially...
    if (isset($advanced) && $advanced) {

        // Then switch to the advanced form (but don't mess with the fields'
        // values).
        ?>cstm.toggleAdvanced(null, false);<?php
    }

    ?>
});

</script>
