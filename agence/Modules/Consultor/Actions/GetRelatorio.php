<?php

namespace Modules\Consultor\Actions;

use Modules\Consultor\Entities\Consultor;
use Modules\Consultor\Actions\OrderData;

class GetRelatorio {

    private $consultor;
    private $orderData;

    public function __construct(
        Consultor $consultor,
        OrderData  $orderData
    )
    {
        $this->consultor = $consultor;
        $this->orderData = $orderData;
    }

    public function exec()
    {
        $data = $this->consultor->join('cao_os', function($join){
            $join->on('cao_usuario.co_usuario', '=', 'cao_os.co_usuario')
            ->whereIn('cao_usuario.co_usuario',['carlos.arruda','renato.pereira']);
        })
        ->join('cao_fatura', function($join){
            $join->on('cao_os.co_os','=','cao_fatura.co_os')
            ->whereBetween('data_emissao', ['2007-01-01', '2007-06-31']);
        })
        ->join('cao_salario', function($join){
            $join->on('cao_usuario.co_usuario','=','cao_salario.co_usuario');
        })
        ->orderBy('data_emissao', 'asc')
        ->get();
        
        return $this->orderData->exec($data);
    }
}