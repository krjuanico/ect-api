<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // Change these to match your actual schema
    private const PAYROLL_TABLE = 'payroll';
    private const PAYROLL_NAME_COLUMN = 'name';
    private const PAYROLL_BUDGET_COLUMN = 'budget';
    private const PAYROLL_ACTIVE_COLUMN = 'is_active';

    private const LIST_TABLE = 'clean_list';
    private const LIST_PAYROLL_FK = 'payroll_id';
    private const LIST_STATUS_COLUMN = 'status';
    private const LIST_AMOUNT_COLUMN = 'amount';

    private const STATUSES = [
        'to_be_served',
        'validated',
        'claimed',
        'for_correction',
        'for_replacement',
        'disqualified',
    ];

    public function payrolls(): JsonResponse
    {
        $payrolls = DB::table(self::PAYROLL_TABLE)
            ->select([
                'id',
                self::PAYROLL_NAME_COLUMN . ' as name',
                self::PAYROLL_ACTIVE_COLUMN . ' as is_active',
            ])
            ->orderByDesc(self::PAYROLL_ACTIVE_COLUMN)
            ->orderByDesc('id')
            ->get();

        return response()->json($payrolls);
    }

    public function summary(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'payroll_id' => ['required', 'integer', 'exists:' . self::PAYROLL_TABLE . ',id'],
        ]);

        $payrollId = (int) $validated['payroll_id'];

        $allocated = (float) DB::table(self::PAYROLL_TABLE)
            ->where('id', $payrollId)
            ->value(self::PAYROLL_BUDGET_COLUMN);

        // One query: count and total amount per status
        $rows = DB::table(self::LIST_TABLE)
            ->where(self::LIST_PAYROLL_FK, $payrollId)
            ->selectRaw(
                self::LIST_STATUS_COLUMN . ' as status, COUNT(*) as total, COALESCE(SUM(' . self::LIST_AMOUNT_COLUMN . '), 0) as amount'
            )
            ->groupBy(self::LIST_STATUS_COLUMN)
            ->get()
            ->keyBy('status');

        $counts = [];
        foreach (self::STATUSES as $status) {
            $counts[$status] = (int) ($rows[$status]->total ?? 0);
        }

        $disbursed = (float) ($rows['claimed']->amount ?? 0);

        return response()->json([
            'payroll_id' => $payrollId,
            'counts' => $counts,
            'allocated' => $allocated,
            'disbursed' => $disbursed,
            'balance' => $allocated - $disbursed,
        ]);
    }
}