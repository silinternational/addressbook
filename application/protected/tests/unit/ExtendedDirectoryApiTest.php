<?php

class ExtendedDirectoryApiTest extends CTestCase
{
    public function testNoQuery()
    {
        // Arrange
        $extDirApi = new ExtendedDirectoryApi();

        // Act / assert.
        $this->setExpectedException(
            'CException',
            'Nothing given to search for.'
        );
        $extDirApi->doBasicSearch();
    }
    
    public function testNoBaseUrl()
    {
        // Arrange
        $extDirApi = new ExtendedDirectoryApi();

        // Act / assert.
        $this->setExpectedException(
            'CException',
            'Cannot call the API without an apiBaseUrl.'
        );
        $extDirApi->doBasicSearch('value');
    }
    
    public function testDoBasicSearch()
    {
        // Arrange
        $apiBaseUrl = 'http://localhost/';
        $extDirApi = new ExtendedDirectoryApi($apiBaseUrl);

        // Make sure an exception is thrown by Guzzle which includes the API
        // base URL followed by a question mark (which it should always).
        $this->setExpectedException(
            'Guzzle\Http\Exception\ClientErrorResponseException',
            $apiBaseUrl . '?'
        );
        
        // Act.
        $extDirApi->doBasicSearch('value');
    }
    
    public function testDoBasicSearchWithApiKey()
    {
        // Arrange
        $apiBaseUrl = 'http://localhost/';
        $apiKey = 'abc';
        $extDirApi = new ExtendedDirectoryApi($apiBaseUrl, $apiKey);

        // Make sure an exception is thrown by Guzzle which includes the name of
        // the query string variable that would hold the API key.
        $this->setExpectedException(
            'Guzzle\Http\Exception\ClientErrorResponseException',
            'api_key'
        );
        
        // Act.
        $extDirApi->doBasicSearch('value');
    }
    
    public function testDoBasicSearchWithApiKeyAndSecret()
    {
        // Arrange
        $apiBaseUrl = 'http://localhost/';
        $apiKey = 'abc';
        $apiSecret = 'def';
        $extDirApi = new ExtendedDirectoryApi($apiBaseUrl, $apiKey, $apiSecret);

        // Make sure an exception is thrown by Guzzle which includes the name of
        // the query string variable that would hold the signature.
        $this->setExpectedException(
            'Guzzle\Http\Exception\ClientErrorResponseException',
            'api_sig'
        );
        
        // Act.
        $extDirApi->doBasicSearch('value');
    }
}
