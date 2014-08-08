<?php

/**
 * SearchForm class.
 * SearchForm is the data structure for keeping search form data. It is used by
 * the 'index' action of 'SiteController'.
 */
class SearchForm extends CFormModel
{
    /**
     * Search string when using the Basic search.
     * @var string
     */
    public $any;
    
    /**
     * Advanced search: first name (aka - given name).
     * @var string
     */
    public $first;
    
    /**
     * Advanced search: last name (aka - family name).
     * @var string
     */
    public $last;
    
    /**
     * Advanced search: email address.
     * @var string
     */
    public $email;
    
    /**
     * Advanced search: phone number (e.g. - "+1 800-555-1234").
     * @var string
     */
    public $title;
    
    /**
     * Whether to include functional accounts (e.g. - "hr_director@...") in the
     * results.
     * @var boolean
     */
    public $functional;
    
    /**
     * Declares the validation rules.
     */
    public function rules() {
        return array(
            
            // Sanitize input on all fields.
            array('any, first, last, email, title',
                  'filter', 
                  'filter' => array($this, 'sanitizeInput')),
            
            // Make sure at least one of the fields had something in it.
            //
            // NOTE: Since a single call checks all of the fields, we only need
            //       to have it call the custom function once, so I'm only
            //       including one field in this list. It shouldn't matter
            //       which field we specify here.
            //
            array('any',
                  'atLeastOneFieldRequired'), 
            
            // Make sure the checkbox value is a boolean.
            array('functional', 'boolean', 'message' => 'The checkbox for ' .
                  'including role based accounts must be either checked ' .
                  '(true) or unchecked (false).'),
        );
    }

    public function atLeastOneFieldRequired($attribute_name, $params) {
        
        // If we weren't given anything to search for...
        if ( ($this->any == '') && ($this->first == '') &&
             ($this->last == '') && ($this->email == '') &&
             ($this->title == '') ) {
            
            // Fail this validation check.
            $this->addError($attribute_name, 'Please type part of a name, ' .
                'email address, or title to search for someone.');
            return FALSE;
        }
        // Otherwise, pass the validation check.
        else return TRUE;
        //if (empty($this->username)
        //        && empty($this->email)
        //) {
        //    $this->addError($attribute_name, Yii::t('user', 'At least 1 of the field must be filled up properly'));
        //
        //    return false;
        //}

        return true;
    }

    public function sanitizeInput($input) {
        
        // Remove leading/trailing whitespace.
        $input = trim($input);
        
        // If the input is now an empty string, simply return as-is.
        if ($input === '') return '';
        
        // Otherwise, remove all illegal characters and return the result. In
        // this case, we're using a regex to remove all characters that are NOT
        // one of our allowed characters (shown below).
        //
        // ALLOWED CHARACTERS:
        //     \w    Word characters: letters, digits, underscore. This is used
        //               rather than a-zA-Z in case there are international
        //               characters.
        //     .     Period.
        //           Space.
        //     @     At-symbol (in email addresses, for example).
        //     '     Apostraphe.
        //     *     Asterisk (lets the user do glob-like searches).
        //     -     Hyphen (some names may be hyphenated).
        //
        return preg_replace('/[^\w.@ \\\'\*\-]/', '', $input);
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'any' => 'Search',
            'first' => 'Given name',
            'last' => 'Family name',
            'email' => 'Email',
            'title' => 'Title',
            'functional' => 'Include role based accounts (e.g., hr_director)',
        );
    }
    
    /**
     * Assemble the search values actually being searched for into a string
     * (intended for use in the page title).
     * 
     * EXAMPLES:
     * If doing a basic search for "Bob", the result will be "Bob".
     * If doing an advanced search for first="John" and last="Smith", the result
     * will be "John Smith".
     * 
     * @return string
     */
    public function getActiveSearchValuesAsString() {
        
        // If the 'any' search field has something in it.
        if ($this->any) {
            
            // Simply return it's value (since other fields are ignored when
            // the 'any' field has a value.
            return $this->any;
        }
        // Otherwise...
        else {
            
            // Set up an array to hold the values that we'll assemble into a
            // single string.
            $temp = array();
            
            // Add any string values that we have to that array.
            if ($this->first) {
                $temp[] = $this->first;
            }
            if ($this->last) {
                $temp[] = $this->last;
            }
            if ($this->email) {
                $temp[] = $this->email;
            }
            if ($this->title) {
                $temp[] = $this->title;
            }
            
            // Assemble the values we collected into a single string and return
            // that.
            return implode(' ', $temp);
        }
    }
}
