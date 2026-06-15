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
.header h1 { font-size: 22px; font-weight: 900; color: #fff; letter-spacing: -0.5px; }
        .header p { font-size: 14px; color: rgba(255,255,255,0.75); margin-top: 6px; }
        .body { padding: 32px; }
        .greeting { font-size: 15px; color: #a1a1aa; margin-bottom: 24px; line-height: 1.6; word-break: break-word; overflow-wrap: break-word; }
        .greeting strong { word-break: break-word; overflow-wrap: break-word; }
        .status-box { border-radius: 14px; padding: 20px; text-align: center; margin-bottom: 28px; border: 1px solid; }
        .status-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 10px; }
        .status-value { font-size: 20px; font-weight: 900; letter-spacing: -0.3px; }
        .details { background: #0a0a0a; border: 1px solid rgba(63,63,70,0.4); border-radius: 12px; padding: 20px; margin-bottom: 28px; }
        .detail-row td { padding: 8px 0; border-bottom: 1px solid rgba(63,63,70,0.3); vertical-align: top; }
        .detail-row:last-child td { border-bottom: none; }
        .detail-label { font-size: 11px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.1em; white-space: nowrap; width: 45%; }
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
            <h1>Status Atualizado</h1>
            <p>Sua solicitação recebeu uma atualização</p>
        </div>

        <div class="body">
            <p class="greeting">
                Olá, <strong>{{ ucfirst($ticket->citizen_name) }}</strong>.<br>
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
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr class="detail-row">
                        <td class="detail-label">Protocolo</td>
                        <td class="detail-value">{{ $ticket->protocol }}</td>
                    </tr>
                    <tr class="detail-row">
                        <td class="detail-label">Categoria</td>
                        <td class="detail-value">{{ $ticket->category?->name ?? '—' }}</td>
                    </tr>
                    <tr class="detail-row">
                        <td class="detail-label">Atualizado em</td>
                        <td class="detail-value">{{ now()->format('d/m/Y \à\s H:i') }}</td>
                    </tr>
                </table>
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
