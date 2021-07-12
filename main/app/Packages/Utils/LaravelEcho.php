<?php
namespace App\Packages\Utils;

use App\Packages\Utils\ClassTrait;

class LaravelEcho
{
    use ClassTrait;

    private $app = '';

    private $channels = '';

    private $host = '';

    private $key = '';

    private $port = '';

    private $status = '';

    private $users = '';

    public function __construct()
    {
        $this->key = env('WS_KEY');
        $this->host = env('WS_HOST');
        $this->port = env('WS_PORT');
        $this->app = env('WS_APP');
    }

    private function app($value)
    {
        $this->app = $value;
        return $this;
    }

    private function events($data = [])
    {

        $uri = $this->makeUri('/events');
        $uri = $uri . '?auth_key=' . $this->key;
        $params = ['json' => $data];

        try {
            $client = new \GuzzleHttp\Client(['timeout' => 1.0]);

            $promise = $client->postAsync($uri, $params)->then(function ($response) {

                return json_decode($response->getBody(), true);
            });
            return $promise->wait();
        } catch (\Throwable $th) {
            return false;
        }

    }

    private function get($uri)
    {
        $headers = ['Authorization' => $this->key];
        $client = new \GuzzleHttp\Client(['timeout' => 3.0, 'headers' => $headers]);
        $response = $client->get($uri);
        $result = json_decode($response->getBody(), true);
        return $result;

    }

    private function makeUri($param)
    {
        return $this->host . ':' . $this->port . '/apps/' . $this->app . $param;
    }

    private function status()
    {
        $uri = $this->makeUri('/status');
        return $this->get($uri);
    }

    private function users($channels)
    {
        $channels = $channels ? '/channels/presence-' . $channels : '/channels';
        $this->users = '/users';
        $uri = $this->makeUri($channels . '/users');

        return $this->get($uri);
    }
}
