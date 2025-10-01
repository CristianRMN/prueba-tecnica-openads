<?php
namespace App\Model;

enum PagoProovedor: string
{
    case PENDIENTE = 'pendiente';
    case PAGADO = 'pagado';
}


