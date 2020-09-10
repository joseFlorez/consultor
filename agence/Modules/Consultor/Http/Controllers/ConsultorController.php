<?php

namespace Modules\Consultor\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Consultor\Actions\GetConsultores;
use Modules\Consultor\Actions\GetRelatorio;

class ConsultorController extends Controller
{
    private $getConsultores;
    private $getRelatorio;

    public function __construct(
        GetConsultores $getConsultores,
        GetRelatorio $getRelatorio
    )
    {
        $this->getConsultores = $getConsultores;
        $this->getRelatorio   = $getRelatorio;
    }

    public function listConsultor()
    {
        return $this->getConsultores->exec();
    }

    public function relatorio(Request $request)
    {
        return $this->getRelatorio->exec($request->data, $request->de, $request->a);
    }
}
