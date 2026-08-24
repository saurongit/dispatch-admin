<?php

namespace App\Http\Controllers;

use App\Services\DispatchCoreApi;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private DispatchCoreApi $core)
    {
    }

    public function index()
    {
        $operations = $this->core->operations();
        return view('orders.index', compact('operations'));
    }

    public function show(string $id)
    {
        $order = $this->core->getOrder($id);
        if ($order === null) {
            abort(404, 'Заявка не найдена в ядре');
        }
        return view('orders.show', compact('order', 'id'));
    }

    public function approve(string $id)
    {
        $this->core->approveReport($id);
        return redirect()->route('orders.show', $id)
            ->with('status', 'Отчёт одобрен, заявка закрыта.');
    }

    public function reject(Request $request, string $id)
    {
        $reason = (string) $request->input('reason', '');
        if ($reason === '') {
            return back()->withErrors(['reason' => 'Укажите причину возврата.']);
        }
        $this->core->rejectReport($id, $reason);
        return redirect()->route('orders.show', $id)
            ->with('status', 'Заявка возвращена мастеру на доработку.');
    }
}
