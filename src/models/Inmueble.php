<?php

namespace floor12\catastro\models;
class Inmueble
{
    public array $referencia = [];
    public ?Localicacion $localicacion = null;
    public string $clase = '';
    public string $usoPrincipal = '';
    public string $superficie = '';
    public string $ano = '';
    public ?float $participacionDeInmueble = null;
    /** @var \Construction[] */
    public array $construcciones = [];


}