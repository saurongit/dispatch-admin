# Dispatch Admin — Laravel админка

Админ-консоль оператора/админа для `dispatch-core`. Часть полиглот-показа:
**Python-ядро + Symfony-микросервис (edge) + Laravel-админка**.

## Что делает

Оператор открывает заявку, видит её статус, и **одобряет / возвращает отчёт исполнителя**
(`report:approve` / `report:reject` ядра) прямо из веб-интерфейса. Всё общение — через
HTTP API ядра с admin-key. Никакой бизнес-логики в Laravel: она только презентует домен ядра.

## Сборка (на стройплощадке, где есть PHP/Composer)

```bash
composer create-project laravel/laravel dispatch-admin
cd dispatch-admin
# скопировать файлы из этого каталога поверх свежего проекта:
#   routes/web.php, app/Services/DispatchCoreApi.php,
#   app/Http/Controllers/OrderController.php,
#   resources/views/orders/*.blade.php, .env (из .env.example)
cp .env.example .env && php artisan key:generate
php artisan serve
```

Настрой `.env`:
```
CORE_API_URL=http://localhost:8000
CORE_ADMIN_API_KEY=change_me_admin_key
```

## Шаги интеграции (обязательно)

Модуль — это набор файлов поверх свежего Laravel. После копирования сделай два шага:

1. **Зарегистрируй сервис-провайдер** (`app/Providers/DispatchCoreServiceProvider.php`
   уже лежит в модуле). Добавь его в `bootstrap/providers.php`:

   ```php
   return [
       App\Providers\AppServiceProvider::class,
       App\Providers\DispatchCoreServiceProvider::class,
   ];
   ```

2. **Выведи action-роуты из-под CSRF** — admin-бэкенд вызывает ядро по API, а формы
   шлют POST без токена. В `bootstrap/app.php` в блоке `withMiddleware`:

   ```php
   ->withMiddleware(function (Middleware $middleware): void {
       $middleware->validateCsrfTokens(except: [
           'orders/*/approve',
           'orders/*/reject',
       ]);
   })
   ```

Без шага 1 Laravel не резолвит `DispatchCoreApi` (скалярные аргументы конструктора),
без шага 2 POST-экшены вернут `419 CSRF`. Оба шага проверены живым запуском
(`php artisan serve` + реальное ядро `dispatch-core`).

## Чем демонстрирует инженерный уровень

- **Laravel уместен там, где он силён**: CRUD/админка, Blade, маршруты, формы — быстро и чисто.
- **Ports & adapters**: Laravel — ещё один адаптер к домену ядра; домен не дублируется.
- **Тонкий клиент к контракту ядра** (`DispatchCoreApi`) — единственное место, знающее про HTTP ядра.
- Ясное разделение: Python несёт бизнес-логику, Symfony — высоконагруженный edge, Laravel — люди.
