<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Atualizado — {{ $ticket->protocol }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background-color: #0a0a0a; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; color: #f4f4f5; }
        .wrapper { max-width: 560px; margin: 32px auto; padding: 0 16px; }
        .card { background: #161616; border: 1px solid rgba(63,63,70,0.5); border-radius: 20px; overflow: hidden; }
        .header { background: linear-gradient(135deg, #186073, #17A2B8); padding: 36px 32px; text-align: center; }
        .header-icon { width: 56px; height: 56px; background: rgba(255,255,255,0.15); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 16px; }
        .header h1 { font-size: 22px; font-weight: 900; color: #fff; letter-spacing: -0.5px; }
        .header p { font-size: 14px; color: rgba(255,255,255,0.75); margin-top: 6px; }
        .body { padding: 32px; }
        .greeting { font-size: 15px; color: #a1a1aa; margin-bottom: 24px; line-height: 1.6; word-break: break-word; overflow-wrap: break-word; }
        .greeting strong { word-break: break-word; overflow-wrap: break-word; }
        .status-box { border-radius: 14px; padding: 20px; text-align: center; margin-bottom: 28px; border: 1px solid; }
        .status-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 10px; }
        .status-value { font-size: 20px; font-weight: 900; letter-spacing: -0.3px; }
        .details { background: #0a0a0a; border: 1px solid rgba(63,63,70,0.4); border-radius: 12px; padding: 20px; margin-bottom: 28px; }
        .detail-row { display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; padding: 8px 0; border-bottom: 1px solid rgba(63,63,70,0.3); }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-size: 11px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.1em; white-space: nowrap; flex-shrink: 0; }
        .detail-value { font-size: 13px; font-weight: 600; color: #e4e4e7; text-align: right; word-break: break-word; overflow-wrap: break-word; }
        .cta { text-align: center; margin-bottom: 28px; }
        .cta a { display: inline-block; background: linear-gradient(135deg, #186073, #17A2B8); color: #fff; text-decoration: none; padding: 14px 32px; border-radius: 12px; font-size: 13px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.12em; }
        .footer { padding: 20px 32px; border-top: 1px solid rgba(63,63,70,0.4); text-align: center; }
        .footer p { font-size: 11px; color: #52525b; line-height: 1.7; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="card">
        <div class="header">
            <div class="header-icon">
                <svg width="28" height="28" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <h1>Status Atualizado</h1>
            <p>Sua solicitação recebeu uma atualização</p>
        </div>

        <div class="body">
            <p class="greeting">
                Olá, <strong>{{ $ticket->citizen_name }}</strong>.<br>
                O status da sua solicitação foi atualizado. Confira abaixo.
            </p>

            @php
                $statusStyles = match($newStatus->value) {
                    'resolvido'        => ['bg' => 'rgba(34,197,94,0.1)',  'border' => 'rgba(34,197,94,0.4)',  'label' => '#4ade80', 'value' => '#86efac'],
                    'em_andamento'     => ['bg' => 'rgba(59,130,246,0.1)', 'border' => 'rgba(59,130,246,0.4)', 'label' => '#60a5fa', 'value' => '#93c5fd'],
                    'rejeitado'        => ['bg' => 'rgba(239,68,68,0.1)',  'border' => 'rgba(239,68,68,0.4)',  'label' => '#f87171', 'value' => '#fca5a5'],
                    'cancelado'        => ['bg' => 'rgba(113,113,122,0.1)','border' => 'rgba(113,113,122,0.4)','label' => '#a1a1aa', 'value' => '#d4d4d8'],
                    'duplicado'        => ['bg' => 'rgba(168,85,247,0.1)', 'border' => 'rgba(168,85,247,0.4)', 'label' => '#c084fc', 'value' => '#d8b4fe'],
                    default            => ['bg' => 'rgba(234,179,8,0.1)',  'border' => 'rgba(234,179,8,0.4)',  'label' => '#facc15', 'value' => '#fde047'],
                };
            @endphp

            <div class="status-box" style="background: {{ $statusStyles['bg'] }}; border-color: {{ $statusStyles['border'] }};">
                <p class="status-label" style="color: {{ $statusStyles['label'] }};">Novo Status</p>
                <p class="status-value" style="color: {{ $statusStyles['value'] }};">{{ $newStatus->label() }}</p>
            </div>

            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Protocolo</span>
                    <span class="detail-value">{{ $ticket->protocol }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Categoria</span>
                    <span class="detail-value">{{ $ticket->category?->name ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Atualizado em</span>
                    <span class="detail-value">{{ now()->format('d/m/Y \à\s H:i') }}</span>
                </div>
            </div>

            <div class="cta">
                <a href="{{ url('/protocolo?protocol=' . $ticket->protocol) }}">
                    Acompanhar Solicitação
                </a>
            </div>
        </div>

        <div class="footer">
            <p>
                Este e-mail foi gerado automaticamente pelo sistema Guardião.<br>
                Não responda a esta mensagem — em caso de dúvidas, acesse o portal e consulte pelo protocolo.<br><br>
                &copy; {{ date('Y') }} Guardião — Sistema de Gestão de Ocorrências
            </p>
        </div>
    </div>
</div>
</body>
</html>
