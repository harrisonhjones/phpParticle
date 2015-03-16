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

    public function setEndpoint($endpoint)
    {
            $this->_endpoint = $endpoint;
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
            $url = $this->_endpoint .'v1/devices/' . $deviceID . '/' . $deviceFunction."?access_token=".$this->_accessToken;
            $result =  $this->_curlRequest($url, $params, 'post');

            $retVal = json_decode($result,true);

            if($retVal != false)
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
            $url = $this->_endpoint .'v1/devices/' . $deviceID . '/' . $variableName."?access_token=".$this->_accessToken;
            $result = $this->_curlRequest($url, array(), 'get');
            $retVal = json_decode($result,true);

            if($retVal != false)
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
            $url = $this->_endpoint .'v1/devices/'."?access_token=".$this->_accessToken;
            $result = $this->_curlRequest($url, array(), 'get');
            $retVal = json_decode($result,true);

            if($retVal != false)
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
            $url = $this->_endpoint .'v1/devices/' . $deviceID."?access_token=".$this->_accessToken;
            $result = $this->_curlRequest($url, array(), 'get');
            $retVal = json_decode($result,true);

            if($retVal != false)
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
            $url = $this->_endpoint .'v1/devices/' . $deviceID."?access_token=".$this->_accessToken;
            $result = $this->_curlRequest($url, array("name" => $name), 'put');
            $retVal = json_decode($result,true);

            if($retVal != false)
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
        $fields = array();
        $url = $this->_endpoint .'v1/access_tokens';
        $result = $this->_curlRequest($url, $fields, 'get', 'basic', $this->_email, $this->_password);
        $retVal = json_decode($result,true);

        if($retVal != false)
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
    
    /**
     * Creates a new token on the spark cloud
     * @return boolean
     */
    public function getToken()
    {
        // create token
        $fields = array('grant_type' => 'password', 'username' => $this->_email, 'password' => $this->_password);
        $url = $this->_endpoint .'oauth/token';
        $result = $this->_curlRequest($url, $fields, 'post', 'basic', 'spark', 'spark');
        $retVal = json_decode($result,true);
        
        if($retVal != false)
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
    
    /**
     * Removes the token from the spark cloud
     * @return boolean
     */
    public function deleteToken($token)
    {
        // delete token
        $fields = array('grant_type' => 'password', 'username' => $this->_email, 'password' => $this->_password);
        $url = $this->_endpoint .'v1/access_tokens/'.$token;
        $result = $this->_curlRequest($url, $fields, 'delete', 'basic', $username, $password);
        $retVal = json_decode($result,true);
        
        if($retVal != false)
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
    
    /**
     * Gets a list of webhooks from the spark cloud
     * @return boolean
     */
    public function listWebhooks()
    {
        if($this->_accessToken)
        {
            $fields = array();
            $url = $this->_endpoint .'v1/webhooks?access_token='. $this->_accessToken;
            $result = $this->_curlRequest($url, $fields, 'get');
            $retVal = json_decode($result,true);

            if($retVal != false)
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
            $url = $this->_endpoint ."v1/webhooks/{$webhookID}/?access_token=". $this->_accessToken;
            $result = $this->_curlRequest($url, $fields, 'delete');
            $retVal = json_decode($result,true);

            if($retVal != false)
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

    private function _curlRequest($url, $fields = null, $type = 'post', $authType = 'none', $username = '', $password = '')
    {
        $this->_debug("Opening a {$type} connection to {$url}");
        $fields_string = null;
        // is cURL installed yet?
        if (!function_exists('curl_init'))
        {
            die('Sorry cURL is not installed!');
        }

        // OK cool - then let's create a new cURL resource handle
        $ch = curl_init();
        if (!empty($fields))
        {
            //url-ify the data for the POST
            foreach($fields as $key=>$value) 
            { 
                if (is_array($value))
                {
                    foreach($value as $value2)
                    {
                        if (!is_null($value2))
			{
			    $fields_string .= $key.'='.$value2.'&';
			}
                    }
                } else
                {
                    if (!is_null($value))
		    {
			$fields_string .= $key.'='.$value.'&';
		    }
		    
                }
                
            }
            rtrim($fields_string,'&');
            $this->_debug_r($fields_string);
            //set the number of POST vars, POST data
            if ($type == 'post') {
                curl_setopt($ch,CURLOPT_POST,1);
                curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
            }
        }
        if ($type == 'delete') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        }
        if ($type == 'put') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
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
        curl_setopt($ch, CURLOPT_REFERER, "http://www.example.com/curl.htm");

        // User agent
        curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");

        // Include header in result? (0 = yes, 1 = no)
        curl_setopt($ch, CURLOPT_HEADER, 0);

        // Should cURL return or print out the data? (true = return, false = print)
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Timeout in seconds
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        // basic auth
        if ($authType == 'basic') {
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
        }
        
        // Download the given URL, and return output
        $this->_debug("Executing Curl Operation");
        $output = curl_exec($ch);
        
        $this->_debug("Curl Result: '" .  $output);
        
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->_debug("Curl Response Code: '" .  $httpCode."'");
        // Close the cURL resource, and free system resources
        curl_close($ch);

        return $output;
    }
}