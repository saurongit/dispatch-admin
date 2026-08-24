<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DispatchCoreApi
{
    public function __construct(
        private string $baseUrl,
        private string $adminKey
    ) {
    }

    private function http()
    {
        return Http::withToken($this->adminKey, 'Bearer')
            ->baseUrl(rtrim($this->baseUrl, '/'))
            ->timeout(10);
    }

    /** Очереди операций (здоровье шина). */
    public function operations(): array
    {
        $res = $this->http()->get('/v1/operations/queues');
        return $res->failed() ? [] : (array) $res->json();
    }

    public function getOrder(string $id): ?array
    {
        $res = $this->http()->get("/v1/orders/{$id}");
        if ($res->failed()) {
            return null;
        }
        return (array) $res->json();
    }

    public function approveReport(string $id): bool
    {
        return $this->http()
            ->post("/v1/orders/{$id}/report:approve", ['actor_id' => 'admin'])
            ->successful();
    }

    public function rejectReport(string $id, string $reason): bool
    {
        return $this->http()
            ->post("/v1/orders/{$id}/report:reject", ['reason' => $reason, 'actor_id' => 'admin'])
            ->successful();
    }
}
