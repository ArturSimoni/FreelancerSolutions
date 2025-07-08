{{-- ... (Botões de ação do projeto) ... --}}

@if ($projeto->status === 'concluido')
    <hr class="my-4">
    <h4>Avaliação do Serviço</h4>

    @php
        $user = Auth::user();
        $canEvaluate = false;
        $evaluationDone = false;
        $avaliadorId = $user->id;
        $avaliadoId = null;
        $avaliacaoExistente = null;

        if ($user->id === $projeto->cliente_id) { // Cliente avalia Freelancer
            $freelancerDoProjeto = $projeto->freelancerAceito->freelancer ?? null;
            if ($freelancerDoProjeto) {
                $avaliadoId = $freelancerDoProjeto->id;
                $avaliacaoExistente = $projeto->avaliacoes->where('avaliador_id', $avaliadorId)
                                                          ->where('avaliado_id', $avaliadoId)
                                                          ->where('tipo_avaliacao', 'cliente_para_freelancer')
                                                          ->first();
                $canEvaluate = !$avaliacaoExistente;
            }
        } elseif ($freelancerAceito && $freelancerAceito->freelancer_id === $user->id) { // Freelancer avalia Cliente
            $avaliadoId = $projeto->cliente->id;
            $avaliacaoExistente = $projeto->avaliacoes->where('avaliador_id', $avaliadorId)
                                                      ->where('avaliado_id', $avaliadoId)
                                                      ->where('tipo_avaliacao', 'freelancer_para_cliente')
                                                      ->first();
            $canEvaluate = !$avaliacaoExistente;
        }
    @endphp

    @if ($canEvaluate)
        <a href="{{ route('avaliacoes.create', $projeto) }}" class="btn btn-primary mt-2">
            {{ __('Avaliar Serviço') }}
        </a>
    @elseif ($avaliacaoExistente)
        <div class="alert alert-info mt-2">
            Você já avaliou este serviço.
            Nota: {{ $avaliacaoExistente->nota }}/5.
            Comentário: {{ $avaliacaoExistente->comentario ?? 'Nenhum' }}
        </div>
    @else
        <p class="text-muted mt-2">Nenhum avaliador ou avaliado elegível para este projeto.</p>
    @endif

    {{-- Exibir avaliações recebidas pelo projeto --}}
    @if ($projeto->avaliacoes->isNotEmpty())
        <h5 class="mt-4">Avaliações Recebidas:</h5>
        <ul class="list-group">
            @foreach ($projeto->avaliacoes as $avaliacao)
                <li class="list-group-item">
                    De: **{{ $avaliacao->avaliador->name }}** para: **{{ $avaliacao->avaliado->name }}** ({{ $avaliacao->tipo_avaliacao == 'cliente_para_freelancer' ? 'Cliente para Freelancer' : 'Freelancer para Cliente' }})
                    <br>
                    Nota: **{{ $avaliacao->nota }}/5**
                    <br>
                    Comentário: "{{ $avaliacao->comentario ?? 'Nenhum comentário.' }}"
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-muted">Nenhuma avaliação para este projeto ainda.</p>
    @endif
@endif
