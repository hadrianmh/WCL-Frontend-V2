<?php

class HttpRequest {
    private $baseurl, $host, $port, $endpoint;
    private $token;

    public function __construct($credential = null) {
        $this->host     = "host.docker.internal";
        $this->port     = "8082";
        $this->endpoint = "/api/v1";
        $this->token    = $credential;

        $this->baseurl  = rtrim($this->host .':'. $this->port . $this->endpoint);
    }

    private function execute($url, $method, $data = null, $headers = []) {
        $ch = curl_init($this->baseurl . $url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data) {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Content-Length: ' . strlen($jsonData);
        }

        $headers[] = $this->token == null ? '' : 'Authorization: ' . $this->token;

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return ['status' => 500, 'response' => $error_msg];
        }

        curl_close($ch);

        $jsonDecode = json_decode($response);

        return ['status' => $httpCode, 'response' => $jsonDecode];
    }

    public function get($url, $headers = []) {
        return $this->execute($url, 'GET', null, $headers);
    }

    public function post($url, $data, $headers = []) {
        return $this->execute($url, 'POST', $data, $headers);
    }

    public function put($url, $data, $headers = []) {
        return $this->execute($url, 'PUT', $data, $headers);
    }

    public function delete($url, $headers = []) {
        return $this->execute($url, 'DELETE', null, $headers);
    }
}
?>