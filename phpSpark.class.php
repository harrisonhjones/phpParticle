<?php

/*
 * @project phpSpark
 * @file    phpSpark.class.php
 * @authors Harrison Jones (harrison@hhj.me)
 *          Devin Pearson   (devin@blackhat.co.za)
 * @date    March 12, 2015
 * @brief   PHP Class for interacting with the Spark Cloud (spark.io)
 */

class phpSpark
{
    private $_email = false;
    private $_password = false;
    private $_accessToken = false;
    private $_debug = false;
    private $_disableSSL = true;
    private $_error = "No Error";
    private $_errorSource = "None";
    private $_result = false;
    private $_debugType = "HTML";
    private $_endpoint = "https://api.spark.io/";
    private $_curlTimeout = 10;

    public function setEndpoint($endpoint)
    {
            $this->_endpoint = $endpoint;
    }

    public function setTimeout($timeout)
    {
        if(is_numeric($timeout))
        {
            $this->_curlTimeout = intval($timeout);
            return true;
        }
        else
        {
            $errorText = "Non numeric timeout";
            $this->_setError($errorText, __FUNCTION__);
            return false;
        }
    }
	
    public function setAuth($email, $password)
    {
        $this->_email = $email;
        $this->_password = $password;
    }
    public function clearAuth()
    {
        $this->setAuth(false,false);
    }
    public function setAccessToken($accessToken)
    {
        $this->_accessToken = $accessToken;
    }
    public function clearAccessToken()
    {
        $this->setAccessToken(false);
    }
    public function setDebugType($debugType = "HTML")
    {
        if(($debugType == "HTML") or ($debugType == "TEXT"))
        {
            $this->_debugType = $debugType;
            return true;
        }
        else
        {
            $this->_setError("Bad debut type (" . $debugType . ")", "setDebugType");
            return false;
        }
    }
    public function setDebug($debug = false)
    {
        if($debug)
        {
            $this->_debug = true;
            return true;
        }
        else
        {
            $this->_debug = false;
            return true;
        }
    }
    public function setDisableSSL($disableSSL = false)
    {
        if($disableSSL)
        {
            $this->_disableSSL = true;
            return true;
        }
        else
        {
            $this->_disableSSL = false;
            return true;
        }
    }
    private function _setError($errorText, $errorSource)
    {
        $this->_error = $errorText;
        $this->_errorSource = $errorSource;
    }


    private function _debug($debugText, $override = false)
    {
        if(($this->_debug == true) || ($override == true))
        {
            if($this->_debugType == "HTML")
            {
                echo $debugText . "<BR/>\n";
                return true;
            }
            else if($this->_debugType == "TEXT")
            {
                echo $debugText . "\n";
                return true;
            }
            else
            {
                $this->_setError("Bad debut type (" . $this->_debugType . ")", "_debug");
                return false;
            }
        }
    }

    private function _debug_r($debugArray, $override = false)
    {
        if(($this->_debug == true) || ($override == true))
        {
            if($this->_debugType == "HTML")
            {
                $this->debug("<pre>");
                print_r($debugArray);
                $this->debug("</pre>");
                return true;
            }
            else if($this->_debugType == "TEXT")
            {
                print_r($debugArray);
                $this->debug();
                return true;
            }
            else
            {
                $this->_setError("Bad debut type (" . $this->_debugType . ")", "_debug");
                return false;
            }
        }
    }

    public function debug($debugText)
    {
        return $this->_debug($debugText, $override = true);
    }

    public function debug_r($debugArray)
    {
        return $this->_debug_r($debugArray, $override = true);
    }
    
    public function doFunction($deviceID, $deviceFunction, $params)
    {
        if($this->_accessToken)
        {
            $url = $this->_endpoint .'v1/devices/' . $deviceID . '/' . $deviceFunction;
            $result =  $this->_curlRequest($url, array('args'=>$params), 'post');

            if($result == false)
            {
                // There was a curl error. 
                $errorText = "Curl Error. Error number = " . $this->_error . ". See http://curl.haxx.se/libcurl/c/libcurl-errors.html for more information";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }

            $retVal = json_decode($result,true);

            if(json_last_error() == 0)
            {
                if(isset($retVal['error']) && $retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $this->_setError($errorText, __FUNCTION__);
                    return false;
                }
                else
                {
                    $this->_result = $retVal;
                    return true;
                }
            }
            else
            {
                $errorText = "Unable to parse JSON. Json error = " . json_last_error() . ". See http://php.net/manual/en/function.json-last-error.php for more information. Raw response from Spark Cloud = '" . $result . "'";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }
        }
        else
        {
            $errorText = "No access token set";
            $this->_setError($errorText, __FUNCTION__);
            return false;
        }
    }
    public function getVariable($deviceID, $variableName)
    {
        if($this->_accessToken)
        {
            $url = $this->_endpoint .'v1/devices/' . $deviceID . '/' . $variableName;
            $result = $this->_curlRequest($url, array(), 'get');
            $retVal = json_decode($result,true);

            if($result == false)
            {
                // There was a curl error. 
                $errorText = "Curl Error. Error number = " . $this->_error . ". See http://curl.haxx.se/libcurl/c/libcurl-errors.html for more information";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }

            $retVal = json_decode($result,true);

            if(json_last_error() == 0)
            {
                if(isset($retVal['error']) && $retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $this->_setError($errorText, __FUNCTION__);
                    return false;
                }
                else
                {
                    $this->_result = $retVal;
                    return true;
                }
            }
            else
            {
                $errorText = "Unable to parse JSON. Json error = " . json_last_error() . ". See http://php.net/manual/en/function.json-last-error.php for more information. Raw response from Spark Cloud = '" . $result . "'";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }
        }
        else
        {
            $errorText = "No access token set";
            $this->_setError($errorText, __FUNCTION__);
            return false;
        }
    }

    public function listDevices()
    {
        if($this->_accessToken)
        {
            $url = $this->_endpoint .'v1/devices/';
            $result = $this->_curlRequest($url, array(), 'get');
            $retVal = json_decode($result,true);

            if($result == false)
            {
                // There was a curl error. 
                $errorText = "Curl Error. Error number = " . $this->_error . ". See http://curl.haxx.se/libcurl/c/libcurl-errors.html for more information";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }

            $retVal = json_decode($result,true);

            if(json_last_error() == 0)
            {
                if(isset($retVal['error']) && $retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $this->_setError($errorText, __FUNCTION__);
                    return false;
                }
                else
                {
                    $this->_result = $retVal;
                    return true;
                }
            }
            else
            {
                $errorText = "Unable to parse JSON. Json error = " . json_last_error() . ". See http://php.net/manual/en/function.json-last-error.php for more information. Raw response from Spark Cloud = '" . $result . "'";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }
        }
        else
        {
            $errorText = "No access token set";
            $this->_setError($errorText, __FUNCTION__);
            return false;
        }
    }

    public function getDeviceInfo($deviceID)
    {
        if($this->_accessToken)
        {
            $url = $this->_endpoint .'v1/devices/' . $deviceID;
            $result = $this->_curlRequest($url, array(), 'get');
            $retVal = json_decode($result,true);

            if($result == false)
            {
                // There was a curl error. 
                $errorText = "Curl Error. Error number = " . $this->_error . ". See http://curl.haxx.se/libcurl/c/libcurl-errors.html for more information";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }

            $retVal = json_decode($result,true);

            if(json_last_error() == 0)
            {
                if(isset($retVal['error']) && $retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $this->_setError($errorText, __FUNCTION__);
                    return false;
                }
                else
                {
                    $this->_result = $retVal;
                    return true;
                }
            }
            else
            {
                $errorText = "Unable to parse JSON. Json error = " . json_last_error() . ". See http://php.net/manual/en/function.json-last-error.php for more information. Raw response from Spark Cloud = '" . $result . "'";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }
        }
        else
        {
            $errorText = "No access token set";
            $this->_setError($errorText, __FUNCTION__);
            return false;
        }
    }

    public function setDeviceName($deviceID,$name)
    {
        if($this->_accessToken)
        {
            $url = $this->_endpoint .'v1/devices/' . $deviceID;
            $result = $this->_curlRequest($url, array("name" => $name), 'put');
            $retVal = json_decode($result,true);

            if($result == false)
            {
                // There was a curl error. 
                $errorText = "Curl Error. Error number = " . $this->_error . ". See http://curl.haxx.se/libcurl/c/libcurl-errors.html for more information";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }

            $retVal = json_decode($result,true);

            if(json_last_error() == 0)
            {
                if(isset($retVal['error']) && $retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $this->_setError($errorText, __FUNCTION__);
                    return false;
                }
                else
                {
                    $this->_result = $retVal;
                    return true;
                }
            }
            else
            {
                $errorText = "Unable to parse JSON. Json error = " . json_last_error() . ". See http://php.net/manual/en/function.json-last-error.php for more information. Raw response from Spark Cloud = '" . $result . "'";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }
        }
        else
        {
            $errorText = "No access token set";
            $this->_setError($errorText, __FUNCTION__);
            return false;
        }
    }
    
    /**
     * Gets a list of your tokens from the spark cloud
     * @return boolean
     */
    public function listTokens()
    {
        if(($this->_email) && ($this->_password))
        {
            $fields = array();
            $url = $this->_endpoint .'v1/access_tokens';
            $result = $this->_curlRequest($url, $fields, 'get', 'basic', $this->_email, $this->_password);
            $retVal = json_decode($result,true);

            if($result == false)
            {
                // There was a curl error. 
                $errorText = "Curl Error. Error number = " . $this->_error . ". See http://curl.haxx.se/libcurl/c/libcurl-errors.html for more information";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }

            $retVal = json_decode($result,true);

            if(json_last_error() == 0)
            {
                if(isset($retVal['error']) && $retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $this->_setError($errorText, __FUNCTION__);
                    return false;
                }
                else
                {
                    $this->_result = $retVal;
                    return true;
                }
            }
            else
            {
                $errorText = "Unable to parse JSON. Json error = " . json_last_error() . ". See http://php.net/manual/en/function.json-last-error.php for more information. Raw response from Spark Cloud = '" . $result . "'";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }
        }
        else
        {
            $errorText = "No auth credentials (email/password) set";
            $this->_setError($errorText, __FUNCTION__);
            return false;
        }
    }
    
    /**
     * Creates a new token on the spark cloud
     * @return boolean
     */
    public function getToken($clientID = "phpSpark")
    {
        if(($this->_email) && ($this->_password))
        {
            // create token
            // ,
            $fields = array('grant_type' => 'password', 'client_id' => 'user', 'client_secret' => 'client_secret_here', 'username' => $this->_email, 'password' => $this->_password);
            $url = $this->_endpoint .'oauth/token';
            $result = $this->_curlRequest($url, $fields, 'post', 'basic-dummy');
            $retVal = json_decode($result,true);
            
            if($result == false)
            {
                // There was a curl error. 
                $errorText = "Curl Error. Error number = " . $this->_error . ". See http://curl.haxx.se/libcurl/c/libcurl-errors.html for more information";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }

            $retVal = json_decode($result,true);

            if(json_last_error() == 0)
            {
                if(isset($retVal['error']) && $retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $this->_setError($errorText, __FUNCTION__);
                    return false;
                }
                else
                {
                    $this->_result = $retVal;
                    return true;
                }
            }
            else
            {
                $errorText = "Unable to parse JSON. Json error = " . json_last_error() . ". See http://php.net/manual/en/function.json-last-error.php for more information. Raw response from Spark Cloud = '" . $result . "'";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }
        }
        else
        {
            $errorText = "No auth credentials (email/password) set";
            $this->_setError($errorText, __FUNCTION__);
            return false;
        }
    }
    
    /**
     * Removes the token from the spark cloud
     * @return boolean
     */
    public function deleteToken($token)
    {
        if(($this->_email) && ($this->_password))
        {
            // delete token
            $url = $this->_endpoint .'v1/access_tokens/'.$token;
            $result = $this->_curlRequest($url, $fields, 'delete', 'basic');
            $retVal = json_decode($result,true);
            
            if($result == false)
            {
                // There was a curl error. 
                $errorText = "Curl Error. Error number = " . $this->_error . ". See http://curl.haxx.se/libcurl/c/libcurl-errors.html for more information";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }

            $retVal = json_decode($result,true);

            if(json_last_error() == 0)
            {
                if(isset($retVal['error']) && $retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $this->_setError($errorText, __FUNCTION__);
                    return false;
                }
                else
                {
                    $this->_result = $retVal;
                    return true;
                }
            }
            else
            {
                $errorText = "Unable to parse JSON. Json error = " . json_last_error() . ". See http://php.net/manual/en/function.json-last-error.php for more information. Raw response from Spark Cloud = '" . $result . "'";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }
        }
        else
        {
            $errorText = "No auth credentials (email/password) set";
            $this->_setError($errorText, __FUNCTION__);
            return false;
        }
    }
    /**
     * Gets a list of webhooks from the spark cloud
     * @return boolean
     */
    public function listWebhooks()
    {
        if($this->_accessToken)
        {
            $fields = array();
            $url = $this->_endpoint .'v1/webhooks';
            $result = $this->_curlRequest($url, $fields, 'get');
            $retVal = json_decode($result,true);

            if($result == false)
            {
                // There was a curl error. 
                $errorText = "Curl Error. Error number = " . $this->_error . ". See http://curl.haxx.se/libcurl/c/libcurl-errors.html for more information";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }

            $retVal = json_decode($result,true);

            if(json_last_error() == 0)
            {
                if(isset($retVal['error']) && $retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $this->_setError($errorText, __FUNCTION__);
                    return false;
                }
                else
                {
                    $this->_result = $retVal;
                    return true;
                }
            }
            else
            {
                $errorText = "Unable to parse JSON. Json error = " . json_last_error() . ". See http://php.net/manual/en/function.json-last-error.php for more information. Raw response from Spark Cloud = '" . $result . "'";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }
        }
        else
        {
            $errorText = "No access token set";
            $this->_setError($errorText, __FUNCTION__);
            return false;
        }
    }
    
    /**
     * Delete webhooks from the spark cloud
     * @return boolean
     */
    public function deleteWebhook($webhookID)
    {
        if($this->_accessToken)
        {
            $fields = array();
            $url = $this->_endpoint ."v1/webhooks/{$webhookID}/";
            $result = $this->_curlRequest($url, $fields, 'delete');
            $retVal = json_decode($result,true);

            if($result == false)
            {
                // There was a curl error. 
                $errorText = "Curl Error. Error number = " . $this->_error . ". See http://curl.haxx.se/libcurl/c/libcurl-errors.html for more information";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }

            $retVal = json_decode($result,true);

            if(json_last_error() == 0)
            {
                if(isset($retVal['error']) && $retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $this->_setError($errorText, __FUNCTION__);
                    return false;
                }
                else
                {
                    $this->_result = $retVal;
                    return true;
                }
            }
            else
            {
                $errorText = "Unable to parse JSON. Json error = " . json_last_error() . ". See http://php.net/manual/en/function.json-last-error.php for more information. Raw response from Spark Cloud = '" . $result . "'";
                $this->_setError($errorText, __FUNCTION__);
                return false;
            }
        }
        else
        {
            $errorText = "No access token set";
            $this->_setError($errorText, __FUNCTION__);
            return false;
        }
    }
    
    public function getError()
    {
        return $this->_error;
    }
    public function getErrorSource()
    {
        return $this->_errorSource;
    }
    public function getResult()
    {
        return $this->_result;
    }

    private function _curlRequest($url, $params = null, $type = 'post', $authType = 'none')
    {
        $this->_debug("Opening a {$type} connection to {$url}");
        $fields_string = null;

        if($authType == 'none')
            $params['access_token'] = $this->_accessToken;

        // is cURL installed yet?
        if (!function_exists('curl_init'))
        {
            die("CURL is not installed/available");
        }

        // OK cool - then let's create a new cURL resource handle
        $ch = curl_init();
        //set the number of POST vars, POST data
        if($type == 'get')
        {
            $url .= ("?" . http_build_query($params));
        }
        else if ($type == 'post') 
        {
            curl_setopt($ch,CURLOPT_POST,count($fields));
            curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($params));
        }
        else if($type == "put")
        {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($params));
        }
        else if ($type == 'delete') 
        {
            $url .= ("?" . http_build_query($params));
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        } 
        else 
        {
            $errorText = "Unsupported method type (" . $type . ")";
            $this->_setError($errorText, __FUNCTION__);
            return false;
        }

        // Now set some options (most are optional)
        // Set URL to download
        curl_setopt($ch, CURLOPT_URL, $url);
        
        if($this->_disableSSL)
        {
            // stop the verification of certificate
            $this->_debug("[WARN] Disabling SSL Verification for CURL");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        
        // Set a referer
        // curl_setopt($ch, CURLOPT_REFERER, "http://www.example.com/curl.htm");

        // User agent
        // curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");

        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->_curlTimeout);
        
        // basic auth
        if ($authType == 'basic') {
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, $this->_email . ":" . $this->_password);
        }
        if ($authType == 'basic-dummy') {
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "spark:spark");
        }
        
        // Download the given URL, and return output
        $this->_debug("Executing Curl Operation");
        $this->_debug("Url:");
        $this->_debug_r($url);
        $this->_debug("Params:");
        $this->_debug_r($params);
        $output = curl_exec($ch);
        
        $this->_debug("Curl Result: '" .  $output . "'");
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->_debug("Curl Response Code: '" .  $httpCode."'");
        // Close the cURL resource, and free system resources

        $curlError = curl_errno($ch);
        curl_close($ch);
        if($curlError != CURLE_OK)
        {
            $this->_setError($curlError, __FUNCTION__);
            return false;
        }
        else
        {
            return $output;
        }
    }
}