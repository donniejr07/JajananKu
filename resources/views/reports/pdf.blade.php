<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #1b1b1d; font-size: 13px; margin: 0; padding: 24px; }
        h1 { font-size: 22px; margin: 0 0 4px; }
        h2 { font-size: 16px; margin: 0 0 16px; color: #45464d; font-weight: normal; }
        .summary { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .summary td { border: 1px solid #c6c6cd; padding: 12px; width: 33%; }
        .summary .label { font-size: 11px; color: #45464d; text-transform: uppercase; }
        .summary .value { font-size: 18px; font-weight: bold; }
        .income { color: #006c49; }
        .expense { color: #ba1a1a; }
        table.detail { width: 100%; border-collapse: collapse; }
        table.detail th { background: #f0edef; text-align: left; padding: 8px 12px; border: 1px solid #c6c6cd; font-size: 11px; color: #45464d; text-transform: uppercase; }
        table.detail td { padding: 8px 12px; border: 1px solid #e4e2e4; }
        .right { text-align: right; }
        .footer { margin-top: 32px; font-size: 10px; color: #76777d; }
    </style>
</head>
<body>
    <h1>JajananKu — Laporan Keuangan</h1>
    <h2>{{ $user->name }} • {{ $from->format('d M Y') }} s/d {{ $to->format('d M Y') }}</h2>

    <table class="summary">
        <tr>
            <td>
                <div class="label">Total Income</div>
                <div class="value income">+Rp {{ number_format($totals['income'], 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Total Expense</div>
                <div class="value expense">-Rp {{ number_format($totals['expense'], 0, ',', '.') }}</div>
            </td>
            <td>
                <div class="label">Net</div>
                <div class="value {{ $totals['net'] >= 0 ? 'income' : 'expense' }}">
                    {{ $totals['net'] >= 0 ? '+' : '-' }}Rp {{ number_format(abs($totals['net']), 0, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>

    <h2>Breakdown per Kategori (Expense)</h2>
    <table class="detail">
        <thead>
            <tr>
                <th>Kategori</th>
                <th class="right">Transaksi</th>
                <th class="right">Total</th>
                <th class="right">%</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categorySummary as $row)
                <tr>
                    <td>{{ $row['name'] }}</td>
                    <td class="right">{{ $row['count'] }}</td>
                    <td class="right">Rp {{ number_format($row['total'], 0, ',', '.') }}</td>
                    <td class="right">{{ $row['percentage'] }}%</td>
                </tr>
            @empty
                <tr><td colspan="4">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Dibuat {{ now()->format('d M Y H:i') }} oleh JajananKu</div>
</body>
</html>
