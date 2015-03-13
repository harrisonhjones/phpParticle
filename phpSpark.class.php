<?php

/*
 * @project phpSpark
 * @file    phpSpark.class.php
 * @authors Harrison Jones (harrison@hhj.me)
 * @date    March 12, 2015
 * @brief   PHP Class for interacting with the Spark Cloud (spark.io)
 */

class phpSpark
{
    private $_email = false;
    private $_password = false;
    private $_accessToken = false;
    private $_debug = false;
    private $_disableSSL = false;
    private $_error = "No Error";
    private $_errorSource = "None";
    private $_result = false;
    private $_debugType = "HTML";

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
    public function setDisableSSL($_disableSSL = false)
    {
        if($_disableSSL)
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
            $url = 'https://api.spark.io/v1/devices/' . $deviceID . '/' . $deviceFunction ;
            $result =  $this->_curlPOST($url,$params);

            $retVal = json_decode($result,true);

            if($retVal != false)
            {
                if($retVal['error'])
                {
                    $this->_error = $retVal['error'];
                    $this->_errorSource = "doFunction";
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
                $errorSource = "doFunction";
                $this->_setError($errorText, $errorSource);
                return false;
            }
        }
        else
        {
            $errorText = "No access token set";
            $errorSource = "doFunction";
            $this->_setError($errorText, $errorSource);
            return false;
        }
    }
    public function getVariable($deviceID, $variableName)
    {
        if($this->_accessToken)
        {
            $url = 'https://api.spark.io/v1/devices/' . $deviceID . '/' . $variableName ;
            $result = $this->_curlGET($url);
            $retVal = json_decode($result,true);

            if($retVal != false)
            {
                if($retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $errorSource = "getVariable";
                    $this->_setError($errorText, $errorSource);
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
                $errorSource = "getVariable";
                $this->_setError($errorText, $errorSource);
                return false;
            }
        }
        else
        {
            $errorText = "No access token set";
            $errorSource = "getVariable";
            $this->_setError($errorText, $errorSource);
            return false;
        }
    }

    public function listDevices()
    {
        if($this->_accessToken)
        {
            $url = 'https://api.spark.io/v1/devices/';
            $result = $this->_curlGET($url);
            $retVal = json_decode($result,true);

            if($retVal != false)
            {
                if($retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $errorSource = "listDevices";
                    $this->_setError($errorText, $errorSource);
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
                $errorSource = "listDevices";
                $this->_setError($errorText, $errorSource);
                return false;
            }
        }
        else
        {
            $errorText = "No access token set";
            $errorSource = "listDevices";
            $this->_setError($errorText, $errorSource);
            return false;
        }
    }

    public function getDeviceInfo($deviceID)
    {
        if($this->_accessToken)
        {
            $url = 'https://api.spark.io/v1/devices/' . $deviceID;
            $result = $this->_curlGET($url);
            $retVal = json_decode($result,true);

            if($retVal != false)
            {
                if($retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $errorSource = "getDeviceInfo";
                    $this->_setError($errorText, $errorSource);
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
                $errorSource = "getDeviceInfo";
                $this->_setError($errorText, $errorSource);
                return false;
            }
        }
        else
        {
            $errorText = "No access token set";
            $errorSource = "getDeviceInfo";
            $this->_setError($errorText, $errorSource);
            return false;
        }
    }

    public function setDeviceName($deviceID,$name)
    {
        if($this->_accessToken)
        {
            $url = 'https://api.spark.io/v1/devices/' . $deviceID;
            $result = $this->_curlPUT($url,array("name" => $name));
            $retVal = json_decode($result,true);

            if($retVal != false)
            {
                if($retVal['error'])
                {
                    $errorText = $retVal['error'];
                    $errorSource = "setDeviceName";
                    $this->_setError($errorText, $errorSource);
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
                $errorSource = "setDeviceName";
                $this->_setError($errorText, $errorSource);
                return false;
            }
        }
        else
        {
            $errorText = "No access token set";
            $errorSource = "getDeviceInfo";
            $this->_setError($errorText, $errorSource);
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

    private function _curlGET($url)
    {
        $this->_debug("Opening a GET connection to " . $url);
        //open connection
        $ch = curl_init();

        $url = $url  . "?access_token=" . $this->_accessToken;

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        // Disable SSL verification
        if($this->_disableSSL)
        {
            $this->_debug("[WARN] Disabling SSL Verification for CURL");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $this->_debug("Executing Curl Operation<br/>");
        $result = curl_exec($ch);

        $this->_debug("Curl Result: '" .  $result);

        //close connection
        curl_close($ch);
        return $result;

    }
    private function _curlPOST($url,$params)
    {
        $this->_debug("Opening a POST connection to " . $url);
        $fields = array(
            'access_token' => urlencode($this->_accessToken),
            'args' => urlencode($params)
        );

        //url-ify the data for the POST
        $fields_string = "";
        foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
        $fields_string = rtrim($fields_string, '&');

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_POST, count($fields));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        // Disable SSL verification
        if($this->disableSSL)
        {
            $this->_debug("[WARN] Disabling SSL Verification for CURL");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $this->_debug("Executing Curl Operation");
        $result = curl_exec($ch);

        $this->_debug("Curl Result: '" .  $result);

        //close connection
        curl_close($ch);
        return $result;
    }

    private function _curlPUT($url,$params)
    {
        $this->_debug("Opening a PUT connection to " . $url);

        $params['access_token'] = $this->_accessToken;
        $this->_debug_r(http_build_query($params));

        //open connection
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($params));
        // Disable SSL verification
        if($this->disableSSL)
        {
            $this->_debug("[WARN] Disabling SSL Verification for CURL");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //execute post
        $this->_debug("Executing Curl Operation");
        $result = curl_exec($ch);

        $this->_debug("Curl Result: '" .  $result);

        //close connection
        curl_close($ch);
        return $result;
    }
}