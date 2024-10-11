<?php

namespace floor12\catastro\models;

class Localicacion
{

    public string $todo = '';
    public string $provincia = '';
    public string $municipio = '';
    public ?string $viaType = null;
    public ?string $viaNombre = null;
    public ?string $numero = null;
    public ?string $planta = null;
    public ?string $puerta = null;
    public ?string $codigoPostal = null;
    public ?string $bloque = null;
    public ?string $escalera = null;
    public ?string $titulo = null;

    public function getGoogleRequest(): string
    {
        return trim($this->titulo . ' ' . $this->viaType . ' ' . $this->viaNombre . ' ' . $this->numero . ' ' . $this->municipio . ' ' . $this->provincia);
    }
}