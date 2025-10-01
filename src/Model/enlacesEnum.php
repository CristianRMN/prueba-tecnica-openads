<?php
namespace App\Model;

enum TipoEnlace: string
{
    case DOFOLLOW = 'dofollow';
    case NOFOLLOW = 'nofollow';
    case SPONSORED = 'sponsored';
}