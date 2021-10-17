<?php

declare(strict_types=1);

namespace App\Service;

use App\Container\ServiceContainer;
use App\Exception\NotFoundException;
use ReflectionException;

class DistanceService
{
    public function __construct(
        private ServiceContainer $container,
    ) {
    }

    public function renderPhp(string $path): bool|string
    {
        ob_start();
        include($path);
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    /**
     * @throws ReflectionException
     * @throws NotFoundException
     */
    public function getCoordinates(array $addresses): string
    {
        array_splice($addresses, 10);
        $httpClient = $this->container->get('HttpClient');
        $coordinates = [];
        $headers = self::headers();

        foreach ($addresses as $address) {
            if (strlen($address) < 10 || strlen($address) > 200) {
                return 'Address - ' . $address . ' - not satisfy min10 max200';
            }
            $response = $httpClient->get(getenv('GEOLOCATION_API_ADDRESS_URL') . urlencode($address), $headers);
            $responseArray = json_decode($response, true);

            if (!empty($responseArray['error'])) {
                return $responseArray['error'];
            } elseif (empty($responseArray['adrese'])) {
                return 'There is no address - ' . $address;
            }
            $coordinates[] = $response;
        }

        return self::getIds($coordinates);
    }

    /**
     * @throws ReflectionException
     * @throws NotFoundException
     */
    private function getIds(array $coordinates): string
    {
        $httpClient = $this->container->get('HttpClient');
        $headers = self::headers();
        $ids = [];

        foreach ($coordinates as $coordinate) {
            $coordinateArray = json_decode($coordinate, true);

            if (empty($coordinateArray['adrese'])) {
                return 'Empty address passed to Mappost';
            }
            elseif (empty($coordinateArray['adrese'][0]['name'])) {
                return 'Empty address name passed to Mappost';
            }
            elseif (empty($coordinateArray['adrese'][0]['x']) || empty($coordinateArray['adrese'][0]['y'])) {
                return 'Empty coordinates passed to Mappost';
            }
            $body = [
                'Name' => $coordinateArray['adrese'][0]['name'],
                'GroupID' => 8116,
                'GeoJSONFeature' => [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Point',
                        'coordinates' => [$coordinateArray['adrese'][0]['x'], $coordinateArray['adrese'][0]['y']]
                    ]
                ]
            ];

            $response = $httpClient->post(getenv('MAPPOST_API_CREATE_URL'), $headers, json_encode($body));
            $responseArray = json_decode($response, true);

            if ($responseArray['Success'] === false) {
                return print_r($responseArray['Errors'], true);
            }
            $ids[] = ['ClientObjectID' => $responseArray['ID'], 'Quantity' => 1];
        }

        return self::getDistance($ids);
    }

    /**
     * @throws ReflectionException
     * @throws NotFoundException
     */
    private function getDistance(array $ids): string
    {
        $httpClient = $this->container->get('HttpClient');
        $headers = self::headers();
        $distances = [];

        $body = [
            'TaskType' => 'delivery',
            'TaskName' => 'Logistic task '.time(),
            'Orders' => $ids,
            'Warehouses' => ['WarehouseObjectID' => ''],
            'ParkingPlaces' => ['ParkingPlaceObjectID' => ''],
            'Cars' => ['CarObjectID' => '']
        ];

        $response = $httpClient->post(getenv('MAPPOST_API_SOLVE_URL'), $headers, json_encode($body));
        $responseArray = json_decode($response, true);

        if ($responseArray['Success'] === false) {
            return print_r($responseArray['Errors'], true);
        }
        $distances[] = $responseArray['Solution']['Distance'];


        return (string)(array_sum($distances)/1000);
    }

    private function headers(): array
    {
        $this->container->get('DotEnvService')->load();

        return [
            'Authorization: Basic ' . base64_encode(getenv('HTTP_BASIC_AUTH_USERNAME') . ':' . getenv('HTTP_BASIC_AUTH_PASSWORD')),
            'Accept: application/json',
            'Content-Type: application/json'
        ];
    }
}
