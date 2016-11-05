<?php
/**
 * PHP 7.0 class for interacting with a Cachet installation (Very simple, i know) :-)
 *
 * @author Michal Skogemann - 2016
 */

class cachet {
    public function __construct($cachet_host, $cachet_token, $debugmode = FALSE) {
        $this->cachet_host = $cachet_host;
        $this->cachet_token = $cachet_token;
        $this->debugmode = $debugmode;
    }

    ## Components
    public function components($id = NULL, $method = 'GET', $data = NULL) {
        if ($id) {
            return $this->doCurl('components/' . $id, $method, $data);
        } else {
            return $this->doCurl('components', $method, $data);
        }
    }

    ## Incidents
    public function incidents($id = NULL, $method = 'GET', $data = NULL) {
        if ($id) {
            return $this->doCurl('incidents/' . $id, $method, $data);
        } else {
            return $this->doCurl('incidents', $method, $data);
        }
    }

    ## Subscribers
    public function subscribers($id = NULL, $method = 'GET', $data = NULL) {
        if ($id) {
            return $this->doCurl('subscribers/' . $id, $method, $data);
        } else {
            return $this->doCurl('subscribers', $method, $data);
        }
    }
    
    ## Actions
    public function actions($id = NULL, $method = 'GET', $data = NULL) {
        if ($id) {
            return $this->doCurl('actions/' . $id, $method, $data);
        } else {
            return $this->doCurl('actions', $method, $data);
        }
    }
    
    ## Metrics
    public function metrics($id = NULL, $method = 'GET', $data = NULL) {
        if ($id) {
            return $this->doCurl('metrics/' . $id, $method, $data);
        } else {
            return $this->doCurl('metrics', $method, $data);
        }
    }
    
    ## Misc
    public function ping() {
        return $this->doCurl('ping');
    }

    public function version() {
        return $this->doCurl('version');
    }

    ## Internal methods
    private function doCurl($endpoint, $request_type = 'GET', $data = null, $params = null) {
        try {
            $url = 'http://'.$this->cachet_host . '/api/v1/' . $endpoint;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-Cachet-Token: " . $this->cachet_token));
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            $data = json_encode($data);

            switch ($request_type) {
                case 'POST':
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                case 'GET';
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
                    break;
                case 'DELETE':
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                    break;
                case "PUT":
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Length: ' . strlen($data)));
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
            }

            $curl_response = curl_exec($curl);
            curl_close($curl);
            $decoded = $curl_response;
            $this->debug(json_last_error(), 'JSON_LAST_ERROR');
            $this->debug(json_last_error_msg(), 'JSON_LAST_ERROR_MSG');
            return $decoded;
        } catch (Exception $ex) {
            $this->debug($ex, 'EXCEPTION');
        }
    }

    public function debug($param, $title) {
        if ($this->debugmode) {
            echo '<pre>';
            echo '<h3>' . $title . '</h3>';
            print_r($param);
            echo '</pre>';
        }
    }
}
