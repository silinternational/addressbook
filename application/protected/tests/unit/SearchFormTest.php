<?php

class SearchFormTest extends CTestCase {

    public function testSanitizeInput() {

        // Create a new SearchForm.
        $sf = new SearchForm();
        
        
        
        /********* Make sure the allowed characters are NOT removed: *********/
        
        // Letters
        $input = 'abcdefghijklmnopqrstuvwxyz';
        $this->assertEquals($input, $sf->sanitizeInput($input), 
            'Lowercase letter was incorrectly removed');
        $input = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $this->assertEquals($input, $sf->sanitizeInput($input), 
            'Uppercase letter was incorrectly removed');
        
        
        
        // TODO: Set up assertions for international characters.
        
        
        
        
        // Numbers
        $input = '0123456789';
        $this->assertEquals($input, $sf->sanitizeInput($input), 
            'Number was incorrectly removed');
        
        // Period
        $input = '.';
        $this->assertEquals($input, $sf->sanitizeInput($input), 
            'Period (.) was incorrectly removed');
        
        // At symbol
        $input = '@';
        $this->assertEquals($input, $sf->sanitizeInput($input), 
            'At symbol (@) was incorrectly removed');
        
        // Underscore
        $input = '_';
        $this->assertEquals($input, $sf->sanitizeInput($input), 
            'Underscore (_) was incorrectly removed');
        
        // INTERNAL Space
        $input = 'a b';
        $this->assertEquals($input, $sf->sanitizeInput($input), 
            'Internal space ( ) was incorrectly removed');
        
        // Apostraphe
        $input = "'";
        $this->assertEquals($input, $sf->sanitizeInput($input), 
            "Apostraphe (') was incorrectly removed");
        
        // Asterisk
        $input = '*';
        $this->assertEquals($input, $sf->sanitizeInput($input), 
            'Asterisk (*) was incorrectly removed');
        
        // Hyphen
        $input = '-';
        $this->assertEquals($input, $sf->sanitizeInput($input), 
            'Hyphen (-) was incorrectly removed');
        
        
        /************* Make sure illegal characters ARE removed: *************/
        
        // Double-quote
        $input = '"';
        $this->assertNotEquals($input, $sf->sanitizeInput($input), 
            'Failed to remove double-quote (")');
        
        // Parentheses
        $input = '(';
        $this->assertNotEquals($input, $sf->sanitizeInput($input), 
            'Failed to remove an opening-parenthesis "' . $input . '"');
        $input = ')';
        $this->assertNotEquals($input, $sf->sanitizeInput($input), 
            'Failed to remove a closing-parenthesis "' . $input . '"');
        
        
        
        // TODO: Add more illegal character tests.
        
        
        
        
    }
}

?>
