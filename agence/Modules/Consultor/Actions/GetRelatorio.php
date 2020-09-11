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

    public function exec($usuarios, $de_data, $a_data)
    {
        try {
            $data = $this->consultor->join('cao_os', function($join) use($usuarios){
                $join->on('cao_usuario.co_usuario', '=', 'cao_os.co_usuario')
                ->whereIn('cao_usuario.co_usuario', explode(',', $usuarios));
            })
            ->join('cao_fatura', function($join) use($de_data, $a_data){
                $join->on('cao_os.co_os','=','cao_fatura.co_os')
                ->whereBetween('data_emissao', [$de_data, $a_data]);
            })
            ->join('cao_salario', function($join){
                $join->on('cao_usuario.co_usuario','=','cao_salario.co_usuario');
            })
            ->orderBy('data_emissao', 'asc')
            ->get();
            
            return $this->orderData->exec($data);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'error'
            ], 500);
        }
        
    }
}