<?php

namespace Rangkotodotcom\Pushnotif\Networks;

use Exception;
use InvalidArgumentException;
use Illuminate\Support\Facades\Http;
use League\Config\Exception\InvalidConfigurationException;

class HttpClient
{
    const HTTP_GET = 'GET';
    const HTTP_POST = 'POST';
    const HTTP_PUT = 'PUT';
    const HTTP_DELETE = 'DELETE';

    protected $productionUrl = 'https://pushnotif.';
    protected $stagingUrl = 'https://staging-pushnotif.';
    protected $clientId;
    protected $clientSecret;
    protected $baseUrl = 'http://localhost:7078';
    protected $tokenUrl;

    protected $_accessToken;
    protected $_expiredIn;
    protected $_isConnected;

    public function __construct(string $mode, string $clientId, string $clientSecret, string $mainDomain)
    {
        $mode = $mode ?? config('pushnotif.pushnotif_mode');
        $clientId = $clientId ?? config('pushnotif.pushnotif_client_id');
        $clientSecret = $clientSecret ?? config('pushnotif.pushnotif_client_secret');
        $mainDomain = $mainDomain ?? config('pushnotif.pushnotif_main_domain');

        if ($mode == '' || $clientId == '' || $clientSecret == '' || $mainDomain == '') {
            throw new InvalidConfigurationException("Client ID atau Client Secret belum dikonfigurasi");
        }

        if ($mode == 'production') {
            $this->baseUrl = $this->productionUrl . $mainDomain;
        }

        if ($mode == 'development') {
            $this->baseUrl = $this->stagingUrl . $mainDomain;
        }

        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->tokenUrl = $this->baseUrl . '/auth/login';

        $parsedUrl = parse_url($this->baseUrl);
        if (!isset($parsedUrl['host'])) {
            return false;
        }

        $domain = $parsedUrl['host'];
        $port = $parsedUrl['port'] ?? ($parsedUrl['scheme'] === 'https' ? 443 : 80);

        $connected = @fsockopen($domain, $port, $errno, $errstr, 5);

        if ($connected) {
            fclose($connected);
            $this->_isConnected = true;
        } else {
            $this->_isConnected = false;
        }

        if ($this->_isConnected) {
            $this->getAccessToken();
        } else {
            throw new Exception('Failed connect to Pushnotif Server : ' . $this->baseUrl);
        }
    }

    protected function getAccessToken(bool $isRefresh = false)
    {
        $path = base_path('json');

        if (!file_exists($path)) {
            mkdir($path, 0755);
        }

        if ($isRefresh) {
            try {
                $response = Http::post($this->tokenUrl, [
                    "client_id"         => $this->clientId,
                    "client_secret"     => $this->clientSecret,
                ]);

                if ($response->successful()) {
                    $decResponse = json_decode($response->getBody());
                    $content = $decResponse->content;
                    $client = $content->client;
                    $contentToken = $content->token;

                    $this->_accessToken = $contentToken->access_token;
                    $this->_expiredIn = $contentToken->expires_in;

                    $token = $this->_accessToken;

                    $dataJsonToken = [
                        'expired_time'  => (time() + $this->_expiredIn) - 120,
                        'token'         => $token
                    ];

                    $encJsonPost = json_encode($dataJsonToken);

                    file_put_contents($path . '/pushnotif_token.json', $encJsonPost);
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }

            return;
        }



        if (file_exists($path . '/pushnotif_token.json')) {
            $jsonToken = file_get_contents($path . '/pushnotif_token.json');

            $decJsonToken = json_decode($jsonToken);

            if ($decJsonToken->expired_time > time()) {
                $token = $decJsonToken->token;
                $this->_accessToken = $token;
            }
        } else {
            try {
                $response = Http::post($this->tokenUrl, [
                    "client_id"         => $this->clientId,
                    "client_secret"     => $this->clientSecret,
                ]);

                if ($response->successful()) {
                    $decResponse = json_decode($response->getBody());
                    $content = $decResponse->content;
                    $client = $content->client;
                    $contentToken = $content->token;

                    $this->_accessToken = $contentToken->access_token;
                    $this->_expiredIn = $contentToken->expires_in;


                    $token = $this->_accessToken;

                    $dataJsonToken = [
                        'expired_time'  => (time() + $this->_expiredIn) - 120,
                        'token'         => $token
                    ];

                    $encJsonPost = json_encode($dataJsonToken);

                    file_put_contents($path . '/pushnotif_token.json', $encJsonPost);
                }
            } catch (Exception $e) {
                throw new Exception($e->getMessage());
            }
        }
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return string
     * @throws Exception
     */
    public function sendRequest(string $method, string $endpoint, array $data = [])
    {
        if ($method == self::HTTP_GET) {
            return $this->sendGetRequest($this->baseUrl . $endpoint, $data);
        }

        if ($method == self::HTTP_POST) {
            return $this->sendPostRequest($this->baseUrl . $endpoint, $data);
        }

        if ($method == self::HTTP_PUT) {
            return $this->sendPutRequest($this->baseUrl . $endpoint, $data);
        }

        if ($method == self::HTTP_DELETE) {
            return $this->sendDeleteRequest($this->baseUrl . $endpoint, $data);
        }

        throw new InvalidArgumentException(sprintf("http method %s not supported", $method));
    }

    protected function sendGetRequest(string $fullEndPoint, array $data)
    {
        try {
            $response = Http::withToken($this->_accessToken)->get($fullEndPoint, $data);

            if ($response->successful()) {
                return json_decode($response->body());
            }

            if ($response->status() == 401) {
                $this->getAccessToken(true);
                return $this->sendGetRequest($fullEndPoint, $data);
            }

            return json_decode($response->body());
        } catch (Exception $e) {
            return (object)[
                'status'  => false,
                'message' => $e->getMessage()
            ];
        }
    }

    protected function sendPostRequest(string $fullEndPoint, array $data)
    {
        try {
            $response = Http::withToken($this->_accessToken)->acceptJson()->post($fullEndPoint, $data);

            if ($response->successful()) {
                return json_decode($response->body());
            }

            if ($response->status() == 401) {
                $this->getAccessToken(true);
                return $this->sendPostRequest($fullEndPoint, $data);
            }

            return json_decode($response->body());
        } catch (Exception $e) {
            return (object)[
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
    }

    protected function sendPutRequest(string $fullEndPoint, array $data)
    {
        try {
            $response = Http::withToken($this->_accessToken)->acceptJson()->put($fullEndPoint, $data);

            if ($response->successful()) {
                return json_decode($response->body());
            }

            if ($response->status() == 401) {
                $this->getAccessToken(true);
                return $this->sendPutRequest($fullEndPoint, $data);
            }

            return json_decode($response->body());
        } catch (Exception $e) {
            return (object)[
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
    }

    protected function sendDeleteRequest(string $fullEndPoint, array $data)
    {
        try {
            $response = Http::withToken($this->_accessToken)->acceptJson()->delete($fullEndPoint, $data);

            if ($response->successful()) {
                return (object)[
                    'status'    => true,
                    'message'   => 'Success Delete'
                ];
            }

            if ($response->status() == 401) {
                $this->getAccessToken(true);
                return $this->sendDeleteRequest($fullEndPoint, $data);
            }

            return json_decode($response->body());
        } catch (Exception $e) {
            return (object)[
                'status'    => false,
                'message'   => $e->getMessage()
            ];
        }
    }

    public function checkConnection()
    {
        return $this->_isConnected;
    }
}
