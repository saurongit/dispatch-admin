<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"><title>Dispatch Admin</title></head>
<body>
<h1>Dispatch Core — админка</h1>

<h2>Очереди операций (здоровье шин)</h2>
<pre>{{ json_encode($operations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>

<h2>Открыть заявку</h2>
<form id="open" method="get" action="{{ route('orders.index') }}">
    <input type="text" name="oid" id="oid" placeholder="order id (uuid)" />
    <button type="button" onclick="go()">Открыть</button>
</form>
<script>
function go(){
    var id = document.getElementById('oid').value.trim();
    if (id) window.location = '/orders/' + encodeURIComponent(id);
}
</script>
</body>
</html>
