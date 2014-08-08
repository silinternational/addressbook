<?php

use \CalcApiSig\HmacSigner;

/**
 * The interface through which this GUI for the Extended Directory interacts
 * with the Extended Directory API.
 */
class ExtendedDirectoryApi extends CComponent
{
    /**
     * The first part of the URL to the API. The url-encoded key-value pairs
     * will be suffixed to this when making the actual call. EXAMPLE:
     * 'http://localhost/search/'
     * 
     * @var string
     */
    protected $apiBaseUrl;
    
    /**
     * The (optional) key needed to access the API. Should only be specified if
     * it's required.
     * 
     * @var string
     */
    protected $apiKey;
    
    /**
     * The (optional) secret value to be used for generating a signature to
     * include in requests to this API. Should only be specified if a
     * signature is required.
     * 
     * @var string
     */
    protected $apiSecret;
    
    /**
     * 
     * @param string|null $apiBaseUrl (Optional:) The base URL to use for calls
     *     to the API.
     * @param string|null $apiKey (Optional:) The API key (if required by the
     *     API).
     * @param string|null $apiSecret (Optional:) The shared secret used (along
     *     with the $apiKey) to generate time-sensitive signatures for calls to
     *     the API (if required). Only used if the API key is also provided.
     */
	public function __construct(
        $apiBaseUrl = null,
        $apiKey = null,
        $apiSecret = null
    ) {
        if ($apiBaseUrl !== null) {
            $this->apiBaseUrl = $apiBaseUrl;
        }
        
        if ($apiKey !== null) {
            $this->apiKey = $apiKey;
        }
        
        if ($apiSecret !== null) {
            $this->apiSecret = $apiSecret;
        }
	}
    
    /**
     * Call the actual API with the given keys/values to search for.
     * 
     * @param array $values An associative array of the values being searched
     *     for, indexed by the field to be searched.
     *     BASIC EXAMPLE: array('any' => 'Joe')
     *     ADVANCED EXAMPLE: array('first' => 'John', 'last' => 'Smith')
     * @param boolean $includeFunctional Whether to include functional accounts
     *     in the list of results. Defaults to FALSE.
     * @return array
     * @throws CException
     */
    protected function callApi($values, $includeFunctional = FALSE) {
        
        // TEMP
        //Yii::log(__FUNCTION__ . ': count($values) = ' . count($values));
        
        // Make sure we have a base URL for the API.
        if ((!is_string($this->apiBaseUrl)) OR (count($this->apiBaseUrl) < 1)) {
            throw new CException('Cannot call the API without an apiBaseUrl.');
        }
        
        // Make sure we were given values to search for.
        if ((!is_array($values)) OR (count($values) < 1)) {
            throw new CException('Cannot call the API without any search ' .
                                 'values.');
        }
        
        // Initialize the array of extra query string parameters we'll need to
        // include in the call, including whether or not to also search the
        // functional accounts.
        $extraQueryParams = array();
        if($includeFunctional){
            $extraQueryParams = array('functional' => $includeFunctional);
        }
        
        // If we have a key for the API...
        if ($this->apiKey !== null) {
            
            // Include it.
            $extraQueryParams['api_key'] = $this->apiKey;
        
            // If we also have a secret for the API...
            if ($this->apiSecret !== null) {

                // Use it (and the key) to calculate a signature for this call.
                $extraQueryParams['api_sig'] = HmacSigner::CalcApiSig(
                    $this->apiKey,
                    $this->apiSecret
                );
            }
        }
        
        try {
            
            // Make the actual call.
            /* @var \Guzzle\Http\Message\Response */
            $response = \Guzzle\Http\StaticClient::get($this->apiBaseUrl, array(
                'query' => $values + $extraQueryParams,
                'timeout' => 10,
                'verify' => false,//__DIR__ . '/../data/ca-bundle.crt',
            ));
        }
        // Try to return a more understandable error for timeouts.
        catch (Guzzle\Http\Exception\CurlException $e) {
            
            $errorNo = $e->getErrorNo();
            if ($errorNo == 28) {
                throw new \Exception(
                    'The server seems to be taking too long to respond. ' .
                    'Please try again or come back later.',
                    $e->getErrorNo(),
                    $e
                );
            } elseif ($errorNo == 60) {
                throw new \Exception(
                    'The SSL certificate for the server seems to have a ' .
                    'problem with it (invalid certificate chain). Please try ' .
                    'again later.',
                    $e->getErrorNo(),
                    $e
                );
            } else {
                throw $e;
            }
        }
        
        // Convert the response JSON to an array.
        $responseJson = $response->json();
        
        // If the call was NOT successful...
        if (strcasecmp($responseJson['success'], 'true') != 0) {
            
            // Throw an exception.
            throw new \Exception('Unexpected response from API: ' .
                $responseJson['message'], $responseJson['code']);
        }
        
        // Return the array of results.
        return $responseJson['data'];
    }

    /**
     * Perform an advanced search, specifying specific values to be matched
     * against some/all of the allowed fields. All records that match ALL of the
     * given search parameters will be returned.
     * 
     * @param string $first The first/given name to search for.
     * @param string $last The last/family name to searched for.
     * @param string $email The email address to search for.
     * @param string $title The job title to search for.
     * @param boolean $includeFunctional Whether to include functional accounts
     *     in the list of results. Defaults to FALSE.
     * @return array An array containing any results.
     * @throws CException
     */
    public function doAdvancedSearch($first = '', $last = '', $email = '',
                                     $title = '', $includeFunctional = FALSE) {
        
        // TEMP
        //Yii::log(__FUNCTION__ . ': first=' . $first . ',last=' . $last .
        //         ',email=' . $email . ',phone=' . $phone);
        
        // Convert any non-strings to empty strings.
        if (!is_string($first)) $first = '';
        if (!is_string($last))  $last = '';
        if (!is_string($email)) $email = '';
        if (!is_string($title)) $title = '';
        
        // Make sure at least one non-empty string was provided.
        if ( ($first == '') && ($last == '') && ($email == '') &&
             ($title == '') ) {
            throw new CException('Nothing given to search for.');
        }
        
        // Assemble all of the non-empty strings into an array.
        $values = array();
        if ($first != '') $values['first'] = $first;
        if ($last != '')  $values['last']  = $last;
        if ($email != '') $values['email'] = $email;
        if ($title != '') $values['title'] = $title;
        
        // Get (and return) the actual results from the API.
        return $this->callApi($values, $includeFunctional);
    }
    
    /**
     * Perform a basic search for the given query string, returning all records
     * that match the query string in ANY of the allowed fields.
     * 
     * @param string $query The query string being searched for.
     * @param boolean $includeFunctional Whether to include functional accounts
     *     in the list of results. Defaults to FALSE.
     * @throws CException
     * @return array An array containing any results.
     */
    public function doBasicSearch($query = '', $includeFunctional = FALSE) {
        
        // TEMP
        //Yii::log(__FUNCTION__ . ': ' . $query);
        
        // Make sure we were given a non-empty string.
        if ((!is_string($query)) OR ($query == '')) {
            throw new CException('Nothing given to search for.');
        }
        
        // Convert the query into an array.
        $values = array();
        $values['any'] = $query;
        
        // Get (and return) the actual results from the API.
        return $this->callApi($values, $includeFunctional);
    }
}