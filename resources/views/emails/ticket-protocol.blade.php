<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Protocolo {{ $ticket->protocol }} — Guardião</title>
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
        .protocol-box { background: #0a0a0a; border: 1px solid rgba(23,162,184,0.3); border-radius: 14px; padding: 20px; text-align: center; margin-bottom: 28px; }
        .protocol-label { font-size: 10px; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px; }
        .protocol-number { font-size: 28px; font-weight: 900; color: #17A2B8; letter-spacing: 0.1em; }
        .protocol-hint { font-size: 11px; color: #52525b; margin-top: 6px; }
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
        <!-- Header -->
        <div class="header">
            <div class="header-icon">
                <svg width="28" height="28" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1>Relato Registrado</h1>
            <p>Sua solicitação foi recebida com sucesso</p>
        </div>

        <!-- Body -->
        <div class="body">
            <p class="greeting">
                Olá, <strong>{{ ucfirst($ticket->citizen_name) }}</strong>.<br>
                Seu relato foi registrado no sistema Guardião. Guarde o número de protocolo abaixo para acompanhar o andamento.
            </p>

            <!-- Protocolo em destaque -->
            <div class="protocol-box">
                <p class="protocol-label">Número do Protocolo</p>
                <p class="protocol-number">{{ $ticket->protocol }}</p>
                <p class="protocol-hint">Use este número para consultar o status da sua solicitação</p>
            </div>

            <!-- Detalhes do chamado -->
            <div class="details">
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr class="detail-row">
                        <td class="detail-label">Categoria</td>
                        <td class="detail-value">{{ $ticket->category?->name ?? '—' }}</td>
                    </tr>
                    <tr class="detail-row">
                        <td class="detail-label">Endereço</td>
                        <td class="detail-value">{{ \Illuminate\Support\Str::limit($ticket->address, 50) }}</td>
                    </tr>
                    <tr class="detail-row">
                        <td class="detail-label">Status Inicial</td>
                        <td class="detail-value">{{ $ticket->status->label() }}</td>
                    </tr>
                    <tr class="detail-row">
                        <td class="detail-label">Data de Abertura</td>
                        <td class="detail-value">{{ $ticket->created_at->format('d/m/Y \à\s H:i') }}</td>
                    </tr>
                    @if($ticket->due_date)
                    <tr class="detail-row">
                        <td class="detail-label">Prazo SLA</td>
                        <td class="detail-value">{{ $ticket->due_date->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <!-- CTA -->
            <div class="cta">
                <a href="{{ url('/protocolo?protocol=' . $ticket->protocol) }}">
                    Acompanhar Solicitação
                </a>
            </div>
        </div>

        <!-- Footer -->
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
