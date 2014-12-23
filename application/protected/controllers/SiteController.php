<?php

class SiteController extends Controller {

    /**
     * Define access rules here. This is based on CAccessControlFilter. See
     * docs here: 
     * http://www.yiiframework.com/doc/api/1.1/CAccessControlFilter
     * 
     * Role can be aliased using:
     *  *: any user, including both anonymous and authenticated users.
     *  ?: anonymous users.
     *  @: authenticated users.
     * 
     * @return array
     */
    public function accessRules() {
        return array(
            array('allow',
                'actions' => array('error', 'systemCheck'),
            ),
            array('allow',
                'users' => array('@'),
            ),
            /* Last rule should just be deny to deny access by default unless
             * explicitly allowed above. */
            array('deny'),
        );
    }
    
    /**
     * This is the default 'index' action that is invoked when an action is not
     * explicitly requested by users.
     */
    public function actionIndex() {
        
        // Set up our Search Form data model.
        $model = new SearchForm;
        
        // Track whether the user did an advanced search (so we can show the
        // advanced form again with the results.
        $didAdvancedSearch = FALSE;

        //// if it is ajax validation request
        //if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
        //    echo CActiveForm::validate($model);
        //    Yii::app()->end();
        //}

        // If the form has been submitted...
        if (isset($_REQUEST['SearchForm'])) {
            
            // Collect the user input data.
            $model->attributes = Yii::app()->request->getParam('SearchForm');
            //$model->attributes = $_REQUEST['SearchForm'];
            
            // Validate the user input. If successful...
            if ($model->validate()) {
                
                // Set up a variable to hold our list of search results.
                $results = array();
                
                try {
                    
                    // Create an instance of our object for interacting with the
                    // API, specifying the base-url for the API.
                    $extDirApi = new ExtendedDirectoryApi(
                        Yii::app()->params['apiBaseUrl'],
                        Yii::app()->params['apiKey'],
                        Yii::app()->params['apiSecret']
                    );
                    
                    // If anything was submitted in the basic search...
                    if (is_string($model->any) && ($model->any != '')) {

                        // Do a basic search.
                        $results = $extDirApi->doBasicSearch($model->any);
                    }
                    // Otherwise, assume it was an advanced search.
                    else {

                        // Do an advanced search.
                        $results = $extDirApi->doAdvancedSearch($model->first,
                            $model->last, $model->email, $model->title);

                        // Make a note that we did an advanced search.
                        $didAdvancedSearch = TRUE;
                    }
                } catch (\Exception $e) {
                    
                    // Get the error message.
                    $errorMessage = $e->getMessage();
                    
                    // Log the error.
                    Yii::log($errorMessage, CLogger::LEVEL_WARNING);
                    
                    // Show the error message.
                    $model->addError('any', $errorMessage);
                    
                    // Set our results array to null (to avoid showing a "no
                    // results" message, since the call failed.
                    $results = NULL;
                }
            }
        }
        
        // If we got a lot of results, encourage the user to narrow their
        // search.
        if (isset($results) && (count($results) > 50)) {
            $model->addError('any', 'To get better results, you may want to ' .
                'be more specific with your search.');
        }
        
        // Render the page, passing along the form model and any search results.
        $this->render('index', array(
            'model' => $model,
            'results' => (isset($results) ? $results : NULL),
            'advanced' => $didAdvancedSearch,
        ));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        
        // Get the error details.
        $error = Yii::app()->errorHandler->error;
        
        // If there were NOT any error details...
        if (!$error) {
            
            // Put together a generic error.
            $error = array(
                'code' => '',
                'message' => 'Oops! Something went wrong...'
            );
        }
        
        // If it's an AJAX request, return the error message. Otherwise,
        // render the error page with the given error details.
        if (Yii::app()->request->isAjaxRequest) {
            echo $error['message'];
        } else {
            $this->render('error', $error);
        }
    }

    public function actionVcard()
    {
        $email = Yii::app()->request->getParam('email',false);
        if(!$email){
            Yii::app()->user->setFlash('danger','Unable to generate vCard file, email address missing.');
            $this->redirect('/');
        }

        try {

            // Create an instance of our object for interacting with the
            // API, specifying the base-url for the API.
            $extDirApi = new ExtendedDirectoryApi(
                Yii::app()->params['apiBaseUrl'],
                Yii::app()->params['apiKey'],
                Yii::app()->params['apiSecret']
            );

            $results = $extDirApi->doAdvancedSearch(null,null,$email,null,true);
            if($results){
                $vcard = new Sabre\VObject\Component\VCard(array(
                    'FN' => CHtml::encode($results['first'].' '.$results['last']),
                    'TITLE' => CHtml::encode($results['title']),
                    /**
                     * Finish adding attributes here
                     * ran out of time...
                     */

                ));
            }

        } catch(\Exception $e) {

        }
    }

    /**
     * This action checks the application's ability to connect to the,
     * Extended Directory API.
     * @todo For now it always returns 200 OK, but it should attempt connection to API
     * It returns an HTTP code of 200 and content of 'OK' if all
     * is good, else it returns a 500 and a brief error if not good.
     */
    public function actionSystemCheck()
    {
        header('Content-type: text/plain', true, 200);
        echo 'OK ';

    }
}
