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
            ->whereIn('cao_usuario.co_usuario',['carlos.arruda','renato.pereira']);
        })
        ->join('cao_fatura', function($join){
            $join->on('cao_os.co_os','=','cao_fatura.co_os')
            ->whereMonth('data_emissao','6');
        })
        ->get();
        $relatorio = [];
        foreach($data as $val){
            if(!isset($relatorio[$val['co_usuario']])){
                print $val['valor'] . ' - ' . $val['total_imp_inc']  . '<br>';
                $relatorio[$val['co_usuario']] = [
                    'co_usuario' => $val['co_usuario'],
                    'valor' => ($val['valor']-($val['valor'] * ($val['total_imp_inc']/100)))
                ];
            } else {
                print $val['valor'] . ' - ' . $val['total_imp_inc'] . ' - '. ($val['valor']-($val['valor'] * ($val['total_imp_inc']/100)))  .'<br>';
                $relatorio[$val['co_usuario']]['valor'] += ($val['valor']-($val['valor'] * ($val['total_imp_inc']/100)));
                //print $relatorio[$val['co_usuario']]['valor'] . '<br>';
            }
        }
        dd($relatorio);
    }
}