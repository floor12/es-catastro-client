<?php

namespace floor12\catastro;

use floor12\catastro\models\Construction;
use floor12\catastro\models\Inmueble;
use floor12\catastro\models\Localicacion;
use GuzzleHttp\Client;

class ClientCatastro
{
    const BASE_URL = 'https://ovc.catastro.meh.es/OVCServWeb/OVCWcfCallejero/COVCCallejero.svc/json/Consulta_DNPRC';

    private Client $client;
    /**
     * @var \floor12\catastro\models\Inmueble
     */
    private Inmueble $inmueble;

    public function __construct(protected string $reference = '', protected string $googleApiKey = '')
    {
        // accept self-signed certificates
        $this->client = new Client([
            'verify' => false,
        ]);
        $this->inmueble = $this->getData();

    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function getData(): Inmueble
    {
        if (empty($this->reference)) {
            throw new \InvalidArgumentException('Reference is required');
        }
        $response = $this->client->get(self::BASE_URL, [
            'query' => [
                'RefCat' => $this->reference,
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        if (empty($data['consulta_dnprcResult'])) {
            throw new \Exception('No data found');
        }
        return $this->parseData($data['consulta_dnprcResult']);
    }

    public function saveStreetViewPhoto($path): void
    {
        if (empty($this->googleApiKey)) {
            throw new \InvalidArgumentException('Google API key is required');
        }
        $url = $this->inmueble->localicacion->getGoogleRequest();
        $url = 'https://maps.googleapis.com/maps/api/streetview?size=1200x1200&fov=100&pitch=3&location=' . urlencode($url) . '&key=' . $this->googleApiKey;
        $image = file_get_contents($url);
        file_put_contents($path, $image);
    }

    public function getGoogleMapLink(): string
    {
        $address = $this->inmueble->localicacion->getGoogleRequest();
        return 'https://www.google.com/maps/place/' . urlencode($address);
    }

    public function getGoogleStreetViewLink(): string
    {
        $address = $this->inmueble->localicacion->getGoogleRequest();
        return 'https://www.google.com/maps/@?api=1&map_action=pano&viewpoint=' . urlencode($address);
    }

    /**
     * @param array $data
     * @return \floor12\catastro\models\Inmueble
     */
    private function parseData(array $data): Inmueble
    {
        $inmueble = new Inmueble();
        $inmueble->referencia = $data['bico']['bi']['idbi']['rc'];
        $inmueble->usoPrincipal = $data['bico']['bi']['debi']['luso'];
        $inmueble->superficie = $data['bico']['bi']['debi']['sfc'];
        $inmueble->participacionDeInmueble = (float)str_replace(',', '.', $data['bico']['bi']['debi']['cpt']);
        $inmueble->ano = $data['bico']['bi']['debi']['ant'];
        if (isset($data['bico']['lcons'])) {
            foreach ($data['bico']['lcons'] as $construction) {
                $inmueble->construcciones[] = $this->parseConstruction($construction);
            }
        }
        $localicacion = new Localicacion();
        $localicacion->todo = $data['bico']['bi']['ldt'];
        $localicacion->provincia = $data['bico']['bi']['dt']['np'];
        $localicacion->municipio = $data['bico']['bi']['dt']['nm'];
        $localicacion->codigoPostal = $data['bico']['bi']['dt']['locs']['lous']['lourb']['dp'] ?? null;
        $localicacion->viaType = $data['bico']['bi']['dt']['locs']['lous']['lourb']['dir']['tv'] ?? null;
        $localicacion->viaNombre = $data['bico']['bi']['dt']['locs']['lous']['lourb']['dir']['nv'] ?? null;
        $localicacion->titulo = $data['bico']['bi']['dt']['locs']['lous']['lourb']['dir']['td'] ?? null;
        $localicacion->numero = $data['bico']['bi']['dt']['locs']['lous']['lourb']['dir']['pnp'] ?? null;
        $localicacion->bloque = $data['bico']['bi']['dt']['locs']['lous']['lourb']['loint']['bq'] ?? null;
        $localicacion->escalera = $data['bico']['bi']['dt']['locs']['lous']['lourb']['loint']['es'] ?? null;
        $localicacion->planta = $data['bico']['bi']['dt']['locs']['lous']['lourb']['loint']['pt'] ?? null;
        $localicacion->puerta = $data['bico']['bi']['dt']['locs']['lous']['lourb']['loint']['pu'] ?? null;

        $inmueble->localicacion = $localicacion;
        return $inmueble;
    }

    private function parseConstruction($construction): Construction
    {
        $c = new Construction();
        $c->usePrincipal = $construction['lcd'] ?? null;
        $c->bloque = $construction['dt']['lourb']['loint']['bq'] ?? null;
        $c->escalera = $construction['dt']['lourb']['loint']['es'] ?? null;
        $c->planta = $construction['dt']['lourb']['loint']['pt'] ?? null;
        $c->puerta = $construction['dt']['lourb']['loint']['pu'] ?? null;
        $c->superficie = $construction['dfcons']['stl'] ?? null;
        return $c;

    }

    public function getInmueble(): Inmueble
    {
        return $this->inmueble;
    }
}

