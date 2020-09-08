<?php

namespace Modules\Consultor\Actions;

use Modules\Consultor\Entities\Consultor;

class GetConsultores {

    private $consultor;

    public function __construct(Consultor $consultor)
    {
        $this->consultor = $consultor;
    }

    public function exec()
    {
        return $this->consultor->join('permissao_sistema', function ($join) {
            $join->on('cao_usuario.co_usuario', '=', 'permissao_sistema.co_usuario')
                 ->where('permissao_sistema.co_sistema', '=', 1)
                 ->whereIn('permissao_sistema.co_tipo_usuario', [0, 1, 2])
                 ->where('permissao_sistema.in_ativo', '=', 'S');
        })
        ->get();
    }
}