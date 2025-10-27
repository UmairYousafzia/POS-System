@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

    <!-- POS KPIs -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-3 mb-3">
        <div class="col">
            <div class="card radius-10 bg-gradient-cosmic">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-auto">
                            <p class="mb-0 text-white">Today's Sales (PKR)</p>
                            <h4 class="my-1 text-white">{{ number_format($todaySales ?? 0, 2) }}</h4>
                            <p class="mb-0 font-13 text-white"><a class="text-white" href="{{ route('pos.sales.index') }}">View Sales</a></p>
                        </div>
                        <div id="chart1"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 bg-gradient-ibiza">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-auto">
                            <p class="mb-0 text-white">This Week Sales (PKR)</p>
                            <h4 class="my-1 text-white">{{ number_format($weekSales ?? 0, 2) }}</h4>
                            <p class="mb-0 font-13 text-white"><a class="text-white" href="{{ route('pos.sales.index') }}?from={{ now()->startOfWeek()->format('Y-m-d') }}">This Week</a></p>
                        </div>
                        <div id="chart2"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 bg-gradient-ohhappiness">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-auto">
                            <p class="mb-0 text-white">This Month Sales (PKR)</p>
                            <h4 class="my-1 text-white">{{ number_format($monthSales ?? 0, 2) }}</h4>
                            <p class="mb-0 font-13 text-white"><a class="text-white" href="{{ route('pos.sales.index') }}?from={{ now()->startOfMonth()->format('Y-m-d') }}">This Month</a></p>
                        </div>
                        <div id="chart3"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card radius-10 bg-gradient-kyoto">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-auto">
                            <p class="mb-0 text-dark">Total Customers</p>
                            <h4 class="my-1 text-dark">{{ number_format($customersCount ?? 0) }}</h4>
                            <p class="mb-0 font-13 text-dark"><a href="{{ route('pos.parties.index') }}">Manage</a></p>
                        </div>
                        <div id="chart4"></div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->

    <!-- Finance KPIs -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between"><span>Receivables (Due on Sales)</span><strong>{{ number_format($receivables ?? 0, 2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>Payables (Due on Purchases)</span><strong>{{ number_format($payables ?? 0, 2) }}</strong></div>
                    <div class="d-flex justify-content-between"><span>This Month Expenses</span><strong>{{ number_format($monthExpenses ?? 0, 2) }}</strong></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sales & Purchases -->
    <div class="row g-3">
        <div class="col-xl-6">
            <div class="card radius-10">
                <div class="card-header d-flex align-items-center justify-content-between"><h6 class="mb-0">Recent Sales</h6><a href="{{ route('pos.sales.index') }}">View all</a></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead><tr><th>Date</th><th>Invoice</th><th>Customer</th><th class="text-end">Total</th><th class="text-end">Due</th></tr></thead>
                            <tbody>
                            @forelse($recentSales as $s)
                                <tr>
                                    <td>{{ \Illuminate\Support\Carbon::parse($s->date)->format('Y-m-d') }}</td>
                                    <td><a href="{{ route('pos.sales.show',$s) }}">{{ $s->invoice_no }}</a></td>
                                    <td>{{ optional($s->party)->name ?: 'Walk-in' }}</td>
                                    <td class="text-end">{{ number_format($s->total,2) }}</td>
                                    <td class="text-end">{{ number_format($s->due,2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">No sales yet</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card radius-10">
                <div class="card-header d-flex align-items-center justify-content-between"><h6 class="mb-0">Recent Purchases</h6><a href="{{ route('pos.purchases.index') }}">View all</a></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead><tr><th>Date</th><th>Invoice</th><th>Supplier</th><th class="text-end">Total</th><th class="text-end">Due</th></tr></thead>
                            <tbody>
                            @forelse($recentPurchases as $p)
                                <tr>
                                    <td>{{ \Illuminate\Support\Carbon::parse($p->date)->format('Y-m-d') }}</td>
                                    <td>{{ $p->invoice_no }}</td>
                                    <td>{{ optional($p->party)->name }}</td>
                                    <td class="text-end">{{ number_format($p->total,2) }}</td>
                                    <td class="text-end">{{ number_format($p->due,2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted">No purchases yet</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(false)
    @endif

@endsection
