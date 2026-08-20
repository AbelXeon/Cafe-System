@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <div>
            <h1>Dashboard</h1>
            <p>Overview of the cafe.</p>
        </div>
    </div>

    <div style="display:grid; grid-template-columns: repeat(5, 1fr); gap:16px; margin-bottom:28px;">
        <div class="card">
            <p style="color:var(--ink-soft); font-size:13px;">Menu Items</p>
            <h2 style="font-size:26px; margin-top:6px;">{{ $stats['product_count'] }}</h2>
        </div>
        <div class="card">
            <p style="color:var(--ink-soft); font-size:13px;">Categories</p>
            <h2 style="font-size:26px; margin-top:6px;">{{ $stats['category_count'] }}</h2>
        </div>
        <div class="card">
            <p style="color:var(--ink-soft); font-size:13px;">Staff</p>
            <h2 style="font-size:26px; margin-top:6px;">{{ $stats['staff_count'] }}</h2>
        </div>
        <div class="card">
            <p style="color:var(--ink-soft); font-size:13px;">Delivery Riders</p>
            <h2 style="font-size:26px; margin-top:6px;">{{ $stats['delivery_count'] }}</h2>
        </div>
        <div class="card">
            <p style="color:var(--ink-soft); font-size:13px;">Orders</p>
            <h2 style="font-size:26px; margin-top:6px;">{{ $stats['order_count'] }}</h2>
        </div>
    </div>

    <div class="card">
        <h3 style="margin-bottom:16px; font-size:16px;">Recent Orders</h3>
        @if ($recentOrders->isEmpty())
            <p style="color:var(--ink-soft); font-size:14px;">No orders yet.</p>
        @else
            <table>
                <thead>
                    <tr><th>ID</th><th>Type</th><th>Total</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach ($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ ucfirst($order->order_type) }}</td>
                            <td>{{ number_format($order->total_amount, 2) }}</td>
                            <td><span class="badge badge-muted">{{ ucfirst($order->status) }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection