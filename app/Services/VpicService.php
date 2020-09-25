<?php

namespace App\Http\Services;

use Arr;
use GuzzleHttp\Client;

class VpicService
{
    /**
     * @var string
     */
    private $carsByMakerLink = 'https://vpic.nhtsa.dot.gov/api/vehicles/GetModelsForMakeId/:makerId?format=:format';

    /**
     * @var string
     */
    private $makersLink = 'https://vpic.nhtsa.dot.gov/api/vehicles/getallmakes?format=json';

    /**
     * @var string
     */
    private $decodeVinLink = 'https://vpic.nhtsa.dot.gov/api/vehicles/decodevin/:vin?format=json';

    /**
     * @var string
     */
    private $defaultFormat = 'json';


    public function decodeVinCode(string $vinCode)
    {
        return $this->sendSimpleGetRequestToAddress(str_replace(':vin', $vinCode, $this->decodeVinLink));
    }

    public function sendSimpleGetRequestToAddress($link)
    {
        try {
            $client = new Client([
                'base_uri' => $link,
            ]);

            $response = $client->request('GET');

            if ($response->getStatusCode() == 200) {
                return json_decode($response->getBody()->getContents());
            }

            return null;
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
