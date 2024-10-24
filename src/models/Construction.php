<?php

namespace floor12\catastro\models;
class   Construction
{
    // Main usage/type of the property
    public ?string $usePrincipal = '';
    // Block number
    public ?string $bloque = null;
    // Stair number
    public ?int $escalera = null;
    // Floor number
    public ?string $planta = null;
    // Door number
    public ?int $puerta = null;
    // Surface of the property in square meters
    public ?int $superficie = null;

}
