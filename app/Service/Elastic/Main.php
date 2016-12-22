<?php

namespace App\Service\Elastic;

use Elasticsearch\ClientBuilder;
use App\Service\Elastic\Constants as Constants;

class Main
{
    public $client;
    public function __construct()
    {
        // Only host is required
        $hosts = [
            'host' => '10.8.18.72'
        ];

        // Instantiate a new ClientBuilder
        $this->client = ClientBuilder::create()
        ->setHosts($hosts)// Set the hosts
        ->build();
    }

    public function createIndex()
    {
        $params = [
            'index' => Constants::INDEX,
            'body' =>['mappings'=>[
                Constants::DOC_TYPE_FORUM => ["properties" =>[
                    "date_posted"=>["type"=>"date","format"=>"yyyy-MM-dd HH:mm:ss" ]
                        ]
                    ]
                ]
            ]
        ];

        // Create the index
        $response = $this->client->indices()->create($params);
        echo "<pre>";
        print_r($response);
        die;
    }
}

