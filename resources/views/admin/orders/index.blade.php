@extends('layout.main')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Order Management</h1>
                <p class="text-gray-600 mt-2">Manage and process all customer orders</p>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500">Total Orders</div>
                <div class="text-2xl font-bold text-gray-900">{{ $orders->count() }}</div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
    <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-7 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $allOrders->count() }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Pending Review</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $allOrders->where('status', 'PENDING_REVIEW')->count() }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Invoice Sent</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $allOrders->where('status', 'INVOICE_SENT')->count() }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Paid</p>
                    <p class="text-2xl font-bold text-green-600">{{ $allOrders->where('status', 'PAID')->count() }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Shipped</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $allOrders->where('status', 'SHIPPED')->count() }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $allOrders->where('status', 'COMPLETED')->count() }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Cancelled</p>
                    <p class="text-2xl font-bold text-red-600">{{ $allOrders->where('status', 'CANCELLED')->count() }}</p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a href="{{ route('admin.orders.index') }}" 
            class="border-b-2 {{ !request('status') ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 font-medium text-sm">
                All Orders
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'PENDING_REVIEW']) }}" 
            class="border-b-2 {{ request('status') === 'PENDING_REVIEW' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 font-medium text-sm">
                Pending Review
                @if($allOrders->where('status', 'PENDING_REVIEW')->count() > 0)
                <span class="ml-2 bg-yellow-100 text-yellow-800 py-0.5 px-2 rounded-full text-xs">{{ $allOrders->where('status', 'PENDING_REVIEW')->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'INVOICE_SENT']) }}" 
            class="border-b-2 {{ request('status') === 'INVOICE_SENT' ? 'border-yellow-500 text-yellow-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 font-medium text-sm">
                Invoice Sent
                @if($allOrders->where('status', 'INVOICE_SENT')->count() > 0)
                <span class="ml-2 bg-yellow-100 text-yellow-800 py-0.5 px-2 rounded-full text-xs">{{ $allOrders->where('status', 'INVOICE_SENT')->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'WAITING_VERIFICATION']) }}" 
            class="border-b-2 {{ request('status') === 'WAITING_VERIFICATION' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 font-medium text-sm">
                Waiting Verification
                @if($allOrders->where('status', 'WAITING_VERIFICATION')->count() > 0)
                <span class="ml-2 bg-orange-100 text-orange-800 py-0.5 px-2 rounded-full text-xs">{{ $allOrders->where('status', 'WAITING_VERIFICATION')->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'PAID']) }}" 
            class="border-b-2 {{ request('status') === 'PAID' ? 'border-green-500 text-green-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 font-medium text-sm">
                Paid
                @if($allOrders->where('status', 'PAID')->count() > 0)
                <span class="ml-2 bg-green-100 text-green-800 py-0.5 px-2 rounded-full text-xs">{{ $allOrders->where('status', 'PAID')->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'SHIPPED']) }}" 
            class="border-b-2 {{ request('status') === 'SHIPPED' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 font-medium text-sm">
                Shipped
                @if($allOrders->where('status', 'SHIPPED')->count() > 0)
                <span class="ml-2 bg-blue-100 text-blue-800 py-0.5 px-2 rounded-full text-xs">{{ $allOrders->where('status', 'SHIPPED')->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'COMPLETED']) }}" 
            class="border-b-2 {{ request('status') === 'COMPLETED' ? 'border-purple-500 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 font-medium text-sm">
                Completed
                @if($allOrders->where('status', 'COMPLETED')->count() > 0)
                <span class="ml-2 bg-purple-100 text-purple-800 py-0.5 px-2 rounded-full text-xs">{{ $allOrders->where('status', 'COMPLETED')->count() }}</span>
                @endif
            </a>
            <a href="{{ route('admin.orders.index', ['status' => 'CANCELLED']) }}" 
            class="border-b-2 {{ request('status') === 'CANCELLED' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 font-medium text-sm">
                Cancelled
                @if($allOrders->where('status', 'CANCELLED')->count() > 0)
                <span class="ml-2 bg-red-100 text-red-800 py-0.5 px-2 rounded-full text-xs">{{ $allOrders->where('status', 'CANCELLED')->count() }}</span>
                @endif
            </a>
        </nav>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
        @if($orders->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Order Number
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Customer Info
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Phone Number
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Total Amount
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($orders as $order)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-100 to-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-bold text-blue-600">#{{ $order->id }}</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $order->order_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $order->shipping_name }}</div>
                            <div class="text-sm text-gray-500">{{ $order->contact_email }}</div>
                            @if($order->user)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                Member
                            </span>
                            @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 mt-1">
                                Guest
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->shipping_phone }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $order->currency }} {{ number_format($order->total_amount, 2, ',', '.') }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $order->orderItems->sum('quantity') }} {{ Str::plural('item', $order->orderItems->sum('quantity')) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $statusColors = [
                                'PENDING_REVIEW' => 'bg-yellow-100 text-yellow-800',
                                'INVOICE_SENT' => 'bg-blue-100 text-blue-800',
                                'PAID' => 'bg-green-100 text-green-800',
                                'SHIPPED' => 'bg-indigo-100 text-indigo-800',
                                'COMPLETED' => 'bg-purple-100 text-purple-800',
                                'CANCELLED' => 'bg-red-100 text-red-800',
                            ];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ str_replace('_', ' ', $order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $order->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('g:i A') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.orders.show', $order) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No orders yet</h3>
            <p class="mt-2 text-sm text-gray-500">No customers have placed orders yet.</p>
        </div>
        @endif
    </div>

    <!-- Footer Info -->
    @if($orders->count() > 0)
    <div class="mt-6 text-sm text-gray-600">
        Showing {{ $orders->count() }} {{ Str::plural('order', $orders->count()) }}
    </div>
    @endif
</div>
@endsection