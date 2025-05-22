<?php

namespace App;

enum PropertyType: string
{
    case APARTMENT = 'apartment';
    case HOUSE = 'house';
    case DORM = 'dorm';
    case CONDO = 'condo';
}
