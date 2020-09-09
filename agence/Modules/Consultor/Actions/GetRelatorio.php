<?php

namespace Modules\Consultor\Actions;

use Modules\Consultor\Entities\Consultor;

class GetRelatorio {

    private $consultor;

    public function __construct(Consultor $consultor)
    {
        $this->consultor = $consultor;
    }

    public function exec()
    {
        $data = $this->consultor->join('cao_os', function($join){
            $join->on('cao_usuario.co_usuario', '=', 'cao_os.co_usuario')
            ->where('cao_usuario.co_usuario','=','carlos.arruda');
        })
        ->join('cao_fatura', function($join){
            $join->on('cao_os.co_os','=','cao_fatura.co_os')
            ->whereMonth('data_emissao','2');
        })
        ->get();
        $relatorio = [];
        foreach($data as $val){
            $relatorio[$val['co_usuario']] = [
                'valor' => $val['valor']
            ];
        }
        dd($relatorio);
    }
}