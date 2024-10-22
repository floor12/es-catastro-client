<?php

namespace floor12\catastro\models;
class Inmueble
{
    // Reference number of the property
    public array $referencia = [];
    // Location of the property
    public ?Localicacion $localicacion = null;
    // Type of the property
    public string $usoPrincipal = '';
    // Surface of the property in square meters
    public string $superficie = '';
    // Year of construction
    public ?string $ano = '';
    // Percent of the main property
    public ?float $participacionDeInmueble = null;
    /** @var \Construction[] */
    // List of constructions in the property
    public array $construcciones = [];


}
