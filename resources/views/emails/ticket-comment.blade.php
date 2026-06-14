<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nova Atualização — {{ $ticket->protocol }}</title>
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
        .comment-box { background: #0a0a0a; border-left: 3px solid #17A2B8; border-radius: 0 12px 12px 0; padding: 16px 20px; margin-bottom: 28px; }
        .comment-label { font-size: 10px; font-weight: 700; color: #17A2B8; text-transform: uppercase; letter-spacing: 0.15em; margin-bottom: 8px; }
        .comment-text { font-size: 14px; color: #d4d4d8; line-height: 1.7; word-break: break-word; overflow-wrap: break-word; }
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
            </div>
            <h1>Nova Atualização</h1>
            <p>A equipe Guardião adicionou um comentário</p>
        </div>

        <div class="body">
            <p class="greeting">
                Olá, <strong>{{ $ticket->citizen_name }}</strong>.<br>
                A equipe responsável pela sua solicitação adicionou uma atualização pública.
            </p>

            <div class="comment-box">
                <p class="comment-label">Comentário da equipe</p>
                <p class="comment-text">{{ $comment }}</p>
            </div>

            <div class="details">
                <div class="detail-row">
                    <span class="detail-label">Protocolo</span>
                    <span class="detail-value">{{ $ticket->protocol }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status atual</span>
                    <span class="detail-value">{{ $ticket->status->label() }}</span>
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
