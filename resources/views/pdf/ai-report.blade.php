<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ __('app.ai_report') }} - {{ $student->last_name }}</title>
    <style>
        @page {
            margin: 2cm;
        }

        body {
            font-family: 'Times New Roman' ,'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }

        .header h1 {
            font-size: 20pt;
            font-weight: bold;
            margin: 0 0 10px 0;
            color: #000;
        }

        .info-section {
            margin-bottom: 25px;
        }

        .info-row {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            line-height: 1;
        }

        .info-label {
            font-weight: bold;
            width: 100px;
            flex-shrink: 0;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
            text-transform: uppercase;
        }

        .suggestions-list {
            margin-left: 0;
            padding-left: 0;
            list-style-position: outside;
        }

        .suggestions-list li {
            margin-bottom: 8px;
            line-height: 1.5;
        }

        .summary-text {
            text-align: justify;
            line-height: 1.6;
        }

        .grade {
            font-size: 14pt;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ __('app.ai_report') }}</h1>
    </div>

    <div class="info-section">
        <div class="info-row">
            <span class="info-label">{{ __('app.student') }}:</span>
            <span>{{ $student->first_name }} {{ $student->last_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('app.email') }}:</span>
            <span>{{ $student->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">{{ __('app.assignment') }}:</span>
            <span>{{ $assignment->name }}</span>
        </div>
    </div>

    @if(!empty($report['suggestions']) && count($report['suggestions']) > 0)
    <div class="section">
        <div class="section-title">{{ __('app.suggestions_for_student') }}:</div>
        <ol class="suggestions-list">
            @foreach($report['suggestions'] as $suggestion)
                <li>{{ $suggestion }}</li>
            @endforeach
        </ol>
    </div>
    @endif

    <div class="section">
        <div class="section-title">{{ __('app.overall_summary') }}:</div>
        <div class="summary-text">
            {{ $report['overall_summary'] ?? __('app.no_summary') }}
        </div>
    </div>

    <div class="grade">
        {{ __('app.recommended_grade') }}: {{ $report['recommended_grade'] ?? __('app.N/A') }}
    </div>

    <div class="footer">
        {{ __('app.generated_on') }}: {{ now()->format('Y-m-d H:i:s') }}
    </div>
</body>
</html>
