<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"><title>Заявка {{ $id }}</title></head>
<body>
<h1>Заявка {{ $id }}</h1>

<pre>{{ json_encode($order, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

@if (($order['status'] ?? '') === 'report_pending')
    <hr>
    <form method="post" action="{{ route('orders.approve', $id) }}">
        @csrf
        <button>✅ Одобрить и закрыть</button>
    </form>
    <form method="post" action="{{ route('orders.reject', $id) }}">
        @csrf
        <textarea name="reason" placeholder="Причина возврата мастеру"></textarea><br>
        <button>↩ Вернуть мастеру</button>
    </form>
@endif

@if (session('status'))
    <p><strong>{{ session('status') }}</strong></p>
@endif

<p><a href="{{ route('orders.index') }}">← назад</a></p>
</body>
</html>
