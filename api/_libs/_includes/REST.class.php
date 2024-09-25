<?php

/*
* Copyright(C) 2024 by Hemanth VSR
*/

class RestAPI{
    public function __construct(){
        $this->inputs_();
    }

    private function get_status_detail($code){
        $status = array(
            100 => 'Continue',
            101 => 'Switching Protocols',
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            300 => 'Multiple Choices',
            301 => 'Moved Permanently',
            302 => 'Found',
            303 => 'See Other',
            304 => 'Not Modified',
            305 => 'Use Proxy',
            306 => '(Unused)',
            307 => 'Temporary Redirect',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported'
        );
        if (isset($status[$code])){
            return $status[$code];
        }
        else {
            return $code;
        }
        
    }

    private function inputs_(){
        parse_str(file_get_contents("php://input"),$this->_request);
        switch($this->get_request_method()){
            case 'GET': 
                // just keep it as simple
            case 'POST':
                $this->response = $this->clean_inputs($_POST);
                break;
            case 'PUT':
                $this->response = $this->clean_inputs($_GET);
                break;
            case 'DELETE':
                $this->response = $this->clean_inputs($data);
                break;
            default :
                $this->response = $this->response($this->get_request_method());
                break;
        }
    }

    private function get_request_method(){
        return $_SERVER['REQUEST_METHOD'];
    }

    public function clean_inputs($data){
        $clean_input = array();
            if(is_array($data)){
                foreach($data as $k => $v){
                    $clean_input[$k] = $this->cleanInputs($v);
                }
            }else{
                $data = trim(stripslashes($data));
                $data = strip_tags($data);
                $clean_input = trim($data);
            }
        return $clean_input;
    }

    public function response_($response, $status_code){
        header('Content-Type: application/json');
        try{
            http_response_code($status_code);
            echo json_encode(array("BF" => $response, "Status Name" => $this->get_status_detail($status_code)));
        } catch(Exception $e){
            echo json_encode(array("Message" => "Something gone wrong!"));
        }
        
    }

}