<?php

namespace Modules\Consultor\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Consultor\Actions\GetConsultores;

class ConsultorController extends Controller
{
    private $getConsultores;

    public function __construct(
        GetConsultores $getConsultores
    )
    {
        $this->getConsultores = $getConsultores;
    }

    public function listConsultor()
    {
        return $this->getConsultores->exec();
    }
}
