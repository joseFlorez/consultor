<?php

namespace Modules\Consultor\Actions;
use Illuminate\Support\Carbon;

class OrderData {

    public function exec($data)
    {
        $relatorio = [];
        foreach($data as $val){
            $month = Carbon::create($val['data_emissao'])->locale('pt_BR')->monthName;
            if(!isset($relatorio[$val['co_usuario']])){
                $relatorio[$val['co_usuario']] = [
                    'co_usuario'   => $val['co_usuario'],
                    'no_usuario'   => $val['no_usuario'],
                    'brut_salario' => $val['brut_salario']
                ];
                $relatorio[$val['co_usuario']]['months'][$month] = [
                    'valor'    => $this->claclValor($val),
                    'comissao' => $this->calcComissao($val)
                ];
                $relatorio[$val['co_usuario']]['months'][$month]['lucro'] = $this->calcLucro($relatorio, $val, $month);

            } else if(!isset($relatorio[$val['co_usuario']]['months'][$month])) {
                $relatorio[$val['co_usuario']]['months'][$month] = [
                    'valor' => $this->claclValor($val),
                    'comissao' => $this->calcComissao($val)
                ];
                $relatorio[$val['co_usuario']]['months'][$month]['lucro'] = $this->calcLucro($relatorio, $val, $month);

            } else {
                $relatorio[$val['co_usuario']]['months'][$month]['valor'] += $this->claclValor($val);
                $relatorio[$val['co_usuario']]['months'][$month]['comissao'] += $this->calcComissao($val);
                $relatorio[$val['co_usuario']]['months'][$month]['lucro'] = $this->calcLucro($relatorio, $val, $month);
            }
        }
        return array_values($relatorio);
    }

    private function claclValor($val)
    {
        return ($val['valor']-($val['valor'] * ($val['total_imp_inc']/100)));
    }

    private function calcComissao($val)
    {
        return ($val['valor']-($val['valor'] * ($val['total_imp_inc']/100)))*($val['comissao_cn']/100);
    }

    private function calcLucro($relatorio, $val, $month)
    {
        return $relatorio[$val['co_usuario']]['months'][$month]['valor'] - ($relatorio[$val['co_usuario']]['brut_salario']+$relatorio[$val['co_usuario']]['months'][$month]['comissao']);
    }
}