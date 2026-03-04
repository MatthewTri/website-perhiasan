@extends('layout.main')

@section('content')
<div class="bg-stone-50/50 min-h-screen py-10">
    <div class="container mx-auto px-4 max-w-7xl">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6">
            <div>
                <h6 class="text-xs font-bold tracking-[0.2em] text-stone-500 uppercase mb-2">Commerce Dashboard</h6>
                <h1 class="text-3xl md:text-4xl font-serif text-stone-900 tracking-tight">Order Management</h1>
            </div>
            
            <div class="bg-white px-6 py-3 border border-stone-200 shadow-[0_2px_10px_rgb(0,0,0,0.03)] rounded-sm flex items-center gap-4">
                <span class="text-xs font-bold text-stone-400 uppercase tracking-widest">Total Orders</span>
                <span class="text-2xl font-serif text-stone-900">{{ $allOrders->count() }}</span>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border-l-2 border-emerald-500 text-emerald-800 text-sm tracking-wide shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Statistics Cards (Elegant Grid) --}}
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 mb-10">
            <div class="bg-white p-5 rounded-sm border border-stone-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-md transition-shadow group">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1 group-hover:text-stone-600 transition-colors">Pending</p>
                <p class="text-2xl font-serif text-amber-600">{{ $allOrders->where('status', 'PENDING_REVIEW')->count() }}</p>
            </div>

            <div class="bg-white p-5 rounded-sm border border-stone-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-md transition-shadow group">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1 group-hover:text-stone-600 transition-colors">Invoiced</p>
                <p class="text-2xl font-serif text-blue-600">{{ $allOrders->where('status', 'INVOICE_SENT')->count() }}</p>
            </div>

            <div class="bg-white p-5 rounded-sm border border-stone-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-md transition-shadow group">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1 group-hover:text-stone-600 transition-colors">Verifying</p>
                <p class="text-2xl font-serif text-orange-500">{{ $allOrders->where('status', 'WAITING_VERIFICATION')->count() }}</p>
            </div>

            <div class="bg-white p-5 rounded-sm border border-stone-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-md transition-shadow group">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1 group-hover:text-stone-600 transition-colors">Paid</p>
                <p class="text-2xl font-serif text-emerald-600">{{ $allOrders->where('status', 'PAID')->count() }}</p>
            </div>

            <div class="bg-white p-5 rounded-sm border border-stone-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-md transition-shadow group">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1 group-hover:text-stone-600 transition-colors">Production</p>
                <p class="text-2xl font-serif text-teal-600">{{ $allOrders->where('status', 'PRODUCTION')->count() }}</p>
            </div>

            <div class="bg-white p-5 rounded-sm border border-stone-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-md transition-shadow group">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1 group-hover:text-stone-600 transition-colors">Shipped</p>
                <p class="text-2xl font-serif text-indigo-600">{{ $allOrders->where('status', 'SHIPPED')->count() }}</p>
            </div>

            <div class="bg-white p-5 rounded-sm border border-stone-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-md transition-shadow group">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1 group-hover:text-stone-600 transition-colors">Completed</p>
                <p class="text-2xl font-serif text-stone-800">{{ $allOrders->where('status', 'COMPLETED')->count() }}</p>
            </div>

            <div class="bg-white p-5 rounded-sm border border-stone-100 shadow-[0_2px_10px_rgba(0,0,0,0.02)] hover:shadow-md transition-shadow group">
                <p class="text-[10px] font-bold text-stone-400 uppercase tracking-widest mb-1 group-hover:text-stone-600 transition-colors">Void</p>
                <p class="text-2xl font-serif text-rose-500">{{ $allOrders->whereIn('status', ['CANCELLED', 'REFUNDED'])->count() }}</p>
            </div>
        </div>

        <div class="mb-8 border-b border-stone-200 overflow-x-auto custom-scrollbar">
            <nav class="flex space-x-6 min-w-max pb-px">
                @php
                    $tabs = [
                        ['label' => 'All Orders', 'status' => null, 'color' => 'stone'],
                        ['label' => 'Pending Review', 'status' => 'PENDING_REVIEW', 'color' => 'amber'],
                        ['label' => 'Invoice Sent', 'status' => 'INVOICE_SENT', 'color' => 'blue'],
                        ['label' => 'Waiting Verification', 'status' => 'WAITING_VERIFICATION', 'color' => 'orange'],
                        ['label' => 'Paid', 'status' => 'PAID', 'color' => 'emerald'],
                        ['label' => 'Production', 'status' => 'PRODUCTION', 'color' => 'teal'],
                        ['label' => 'Shipped', 'status' => 'SHIPPED', 'color' => 'indigo'],
                        ['label' => 'Completed', 'status' => 'COMPLETED', 'color' => 'stone'],
                        ['label' => 'Cancelled', 'status' => 'CANCELLED', 'color' => 'rose'],
                        ['label' => 'Refunded', 'status' => 'REFUNDED', 'color' => 'rose'],
                    ];
                @endphp

                @foreach($tabs as $tab)
                    @php
                        $isActive = request('status') === $tab['status'];
                        $count = $tab['status'] ? $allOrders->where('status', $tab['status'])->count() : $allOrders->count();
                        $baseColor = $tab['color'];
                    @endphp
                    <a href="{{ route('admin.orders.index', ['status' => $tab['status']]) }}" 
                       class="group relative py-4 px-1 text-sm font-medium transition-colors duration-200 
                       {{ $isActive ? 'text-stone-900' : 'text-stone-400 hover:text-stone-600' }}">
                        
                        <span class="relative z-10">{{ $tab['label'] }}</span>
                        @if($count > 0 && $tab['status'])
                            <span class="ml-1.5 text-[10px] font-bold px-1.5 py-0.5 rounded-full bg-stone-100 text-stone-500 group-hover:bg-stone-200 transition-colors">{{ $count }}</span>
                        @endif

                        @if($isActive)
                            <span class="absolute bottom-0 left-0 w-full h-0.5 bg-stone-900"></span>
                        @endif
                    </a>
                @endforeach
            </nav>
        </div>

        @if($orders->count() > 0)
        
            {{-- DESKTOP TABLE VIEW (Visible on md+) --}}
            <div class="hidden md:block bg-white rounded-sm shadow-[0_4px_20px_rgb(0,0,0,0.03)] border border-stone-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-stone-50 border-b border-stone-100">
                            <tr>
                                <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Order ID</th>
                                <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Customer</th>
                                <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Total</th>
                                <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Status</th>
                                <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em]">Date</th>
                                <th class="px-6 py-5 text-xs font-bold text-stone-400 uppercase tracking-[0.15em] text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-50">
                            @foreach($orders as $order)
                            <tr class="group hover:bg-stone-50/50 transition-colors duration-200">
                                <td class="px-6 py-5">
                                    <span class="font-mono text-sm font-medium text-stone-900">#{{ $order->order_number }}</span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-stone-800">{{ $order->shipment->first_name ?? 'Guest' }}</span>
                                        <span class="text-xs text-stone-900 font-light mt-0.5">{{ $order->contact_email }}</span>
                                        @if($order->user)
                                            <span class="inline-flex mt-1 w-max items-center px-1.5 py-0.5 rounded-[2px] text-[10px] uppercase tracking-wider font-bold bg-stone-100 text-stone-500">Member</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-serif font-medium text-stone-900">{{ $order->currency }} {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                        <span class="text-xs text-stone-400 font-light">{{ $order->orderItems->sum('quantity') }} items</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    @php
                                        $statusStyles = [
                                            'PENDING_REVIEW' => 'bg-amber-50 text-amber-700 border-amber-100',
                                            'INVOICE_SENT' => 'bg-blue-50 text-blue-700 border-blue-100',
                                            'WAITING_VERIFICATION' => 'bg-orange-50 text-orange-700 border-orange-100',
                                            'PAID' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                            'PRODUCTION' => 'bg-teal-50 text-teal-700 border-teal-100',
                                            'SHIPPED' => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                            'COMPLETED' => 'bg-stone-100 text-stone-700 border-stone-200',
                                            'CANCELLED' => 'bg-rose-50 text-rose-700 border-rose-100',
                                            'REFUNDED' => 'bg-rose-50 text-rose-700 border-rose-100',
                                        ];
                                        $style = $statusStyles[$order->status] ?? 'bg-gray-50 text-gray-700 border-gray-100';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-sm text-[10px] font-bold uppercase tracking-wider border {{ $style }}">
                                        {{ str_replace('_', ' ', $order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm text-stone-600">{{ $order->created_at->format('M d, Y') }}</span>
                                        <span class="text-xs text-stone-400">{{ $order->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}" 
                                       class="inline-flex items-center justify-center px-4 py-2 border border-stone-200 text-xs font-bold text-stone-600 uppercase tracking-widest bg-white hover:bg-stone-50 hover:text-stone-900 hover:border-stone-300 transition-all rounded-sm">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                <div class="px-6 py-6 border-t border-stone-100 bg-stone-50/30">
                    {{ $orders->links('vendor.pagination.custom') }}
                </div>
            </div>

            {{-- MOBILE CARD VIEW (Visible on < md) --}}
            <div class="block md:hidden space-y-4">
                @foreach($orders as $order)
                <div class="bg-white border border-stone-100 shadow-[0_2px_10px_rgb(0,0,0,0.03)] rounded-lg overflow-hidden p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <span class="text-xs font-mono text-stone-400 block mb-1">#{{ $order->order_number }}</span>
                            <h3 class="text-sm font-bold text-stone-900">{{ $order->shipment->first_name ?? 'Guest' }}</h3>
                            <p class="text-xs text-stone-700 mt-0.5">{{ $order->contact_email }}</p>
                        </div>
                         @php
                            $statusStyles = [
                                'PENDING_REVIEW' => 'text-amber-600 bg-amber-50',
                                'INVOICE_SENT' => 'text-blue-600 bg-blue-50',
                                'WAITING_VERIFICATION' => 'text-orange-600 bg-orange-50',
                                'PAID' => 'text-emerald-600 bg-emerald-50',
                                'PRODUCTION' => 'text-teal-600 bg-teal-50',
                                'SHIPPED' => 'text-indigo-600 bg-indigo-50',
                                'COMPLETED' => 'text-stone-600 bg-stone-100',
                                'CANCELLED' => 'text-rose-600 bg-rose-50',
                                'REFUNDED' => 'text-rose-600 bg-rose-50',
                            ];
                            $style = $statusStyles[$order->status] ?? 'text-gray-600 bg-gray-50';
                        @endphp
                        <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $style }}">
                            {{ str_replace('_', ' ', $order->status) }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between py-3 border-t border-b border-stone-50 mb-4">
                        <div class="text-left">
                            <span class="text-[10px] text-stone-400 uppercase tracking-widest block">Date</span>
                            <span class="text-xs font-medium text-stone-700">{{ $order->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] text-stone-400 uppercase tracking-widest block">Total</span>
                            <span class="text-sm font-serif font-bold text-stone-900">{{ $order->currency }} {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('admin.orders.show', $order) }}" class="flex items-center justify-center w-full py-3 bg-stone-900 text-white text-xs font-bold uppercase tracking-widest rounded-sm hover:bg-stone-800 transition-colors">
                        View Details
                    </a>
                </div>
                @endforeach
                
                {{-- Mobile Pagination --}}
                <div class="mt-6">
                    {{ $orders->links('vendor.pagination.custom') }}
                </div>
            </div>

        @else
            <div class="flex flex-col items-center justify-center py-20 border border-dashed border-stone-200 rounded-sm bg-stone-50/30">
                <div class="w-16 h-16 bg-stone-100 rounded-full flex items-center justify-center text-stone-300 mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h3 class="text-lg font-serif text-stone-900 mb-1">No orders found</h3>
                <p class="text-sm text-stone-500">There are no orders with this status currently.</p>
            </div>
        @endif
    </div>
</div>

<style>
    /* Custom Scrollbar for horizontal scrolling areas */
    .custom-scrollbar::-webkit-scrollbar { height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #E7E5E4; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background-color: #D6D3D1; }
</style>
@endsection