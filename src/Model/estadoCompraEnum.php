<?php
namespace App\Model;

enum EstadoCompra: string
{
    case EN_REDACCION = 'en_redaccion';
    case EN_PROCESO = 'en_proceso';
    case PUBLICADO = 'publicado';
}

