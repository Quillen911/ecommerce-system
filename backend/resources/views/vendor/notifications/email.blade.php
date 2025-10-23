<x-mail::layout>
    @php
        $palette = [
            'success' => ['bg' => '#10b981', 'text' => '#ecfdf5'],
            'error'   => ['bg' => '#ef4444', 'text' => '#fef2f2'],
            'primary' => ['bg' => '#2563eb', 'text' => '#eff6ff'],
        ];
        $colors = $palette[$level] ?? $palette['primary'];
    @endphp

    {{-- Header --}}
    <x-slot:header>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" @style([
            'background' => '#0f172a',
            'padding' => '32px 0',
        ])>
            <tr>
                <td align="center">
                    <a href="{{ config('app.url') }}" style="display:inline-flex;align-items:center;gap:12px;text-decoration:none;color:#f8fafc;font-weight:600;font-size:18px;">
                        <img src="{{ asset('img/mail/logo.png') }}" alt="{{ config('app.name') }} logo" width="48" height="48" style="display:block;border:0;">
                        <span>{{ config('app.name') }}</span>
                    </a>
                </td>
            </tr>
        </table>
    </x-slot:header>

    {{-- Body --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:16px;overflow:hidden;box-shadow:0 20px 40px rgba(15,23,42,0.08);">
        <tr>
            <td style="padding:40px;">
                <h1 style="margin:0 0 24px;font-size:28px;font-weight:700;line-height:1.2;color:#0f172a;">
                    {{ $greeting ?? __($level === 'error' ? 'Whoops!' : 'Merhaba!') }}
                </h1>

                @foreach ($introLines as $line)
                    <p style="margin:0 0 16px;font-size:15px;line-height:1.7;color:#475569;">
                        {{ $line }}
                    </p>
                @endforeach

                @isset($actionText)
                    <div style="margin:32px 0;text-align:center;">
                        <a href="{{ $actionUrl }}" @style([
                            'display' => 'inline-block',
                            'padding' => '14px 28px',
                            'border-radius' => '999px',
                            'font-size' => '15px',
                            'font-weight' => '600',
                            'text-decoration' => 'none',
                            'background' => $colors['bg'],
                            'color' => $colors['text'],
                        ])>
                            {{ $actionText }}
                        </a>
                    </div>
                @endisset

                @foreach ($outroLines as $line)
                    <p style="margin:0 0 16px;font-size:15px;line-height:1.7;color:#475569;">
                        {{ $line }}
                    </p>
                @endforeach

                <p style="margin:32px 0 0;font-size:15px;line-height:1.7;color:#0f172a;font-weight:600;">
                    {{ $salutation ?? __('Saygılarımızla,') }}<br>
                    <span style="font-weight:700;">{{ config('app.name') }}</span>
                </p>
            </td>
        </tr>
    </table>

    @isset($actionText)
        <x-slot:subcopy>
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;margin:24px auto 0;">
                <tr>
                    <td style="padding:16px 24px;border-radius:12px;background:#f8fafc;color:#475569;font-size:13px;line-height:1.6;">
                        @lang(
                            'Eğer ":actionText" butonuna tıklamakta sorun yaşıyorsanız aşağıdaki URL\'yi tarayıcınıza yapıştırın:',
                            ['actionText' => $actionText]
                        )
                        <br>
                        <a href="{{ $actionUrl }}" style="color:#2563eb;word-break:break-all;">{{ $displayableActionUrl }}</a>
                    </td>
                </tr>
            </table>
        </x-slot:subcopy>
    @endisset

    <x-slot:footer>
        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:24px 0;">
            <tr>
                <td align="center" style="font-size:12px;color:#94a3b8;">
                    © {{ date('Y') }} {{ config('app.name') }}. Tüm hakları saklıdır.
                </td>
            </tr>
        </table>
    </x-slot:footer>
</x-mail::layout>
