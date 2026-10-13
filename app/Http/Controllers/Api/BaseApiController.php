<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponseFormatter;

class BaseApiController extends Controller
{
    use ApiResponseFormatter;
}

