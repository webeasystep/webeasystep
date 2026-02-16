<?php

namespace App\Libraries; // Change the namespace according to your application structure

use CodeIgniter\HTTP\CURLRequest;

class EasyCron
{
    protected $token;
    protected $uri = 'https://www.easycron.com/rest/';

    /**
     * Constructor
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

    /**
     * Makes the actual call to the easycron.
     *
     * @param string $method The name of the API method (ex: 'add' or 'edit')
     * @param array $data Array of data to send to API endpoint
     * @return mixed
     */
    public function call(string $method, array $data = [])
    {
        $data['token'] = $this->token;
        $url = $this->uri . $method;

        $client = \Config\Services::curlrequest();
        $response = $client->request('GET', $url, [
            'query' => $data
        ]);

        return $response->getBody();
    }
}
