<?php

namespace floor12\catastro\models;

class Localicacion
{

    // Full address
    public string $todo = '';
    // Province
    public string $provincia = '';
    // Municipality
    public string $municipio = '';
    // Type of the street
    public ?string $viaType = null;
    // Name of the street
    public ?string $viaNombre = null;
    // House number
    public ?string $numero = null;
    // Floor number
    public ?string $planta = null;
    // Door number
    public ?string $puerta = null;
    // Postal code
    public ?string $codigoPostal = null;
    // Block number
    public ?string $bloque = null;
    // Stair number
    public ?string $escalera = null;
    // Title of living place (if exists)
    public ?string $titulo = null;

    public function getGoogleRequest(): string
    {
        return trim($this->titulo . ' ' . $this->viaType . ' ' . $this->viaNombre . ' ' . $this->numero . ' ' . $this->municipio . ' ' . $this->provincia);
    }
}