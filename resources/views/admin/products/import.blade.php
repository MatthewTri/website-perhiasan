@extends('layout.checkout')

@section('content')
<div class="min-h-screen bg-white pb-20">
    <div class="max-w-screen-2xl mx-auto px-4 lg:px-6 pt-10 pb-12">
        <div class="flex flex-col items-center justify-center text-center">
            <span class="text-[10px] font-bold tracking-[0.2em] text-gray-400 uppercase mb-2">Inventory Management</span>
            <h1 class="text-3xl md:text-4xl font-serif text-gray-900 tracking-widest uppercase">
                Bulk Product Import
            </h1>
            <p class="mt-4 max-w-2xl text-sm text-gray-500 tracking-wide leading-7">
                Upload a CSV or XLSX file to create new products or update matching items by slug or name.
            </p>
            <div class="h-px w-16 bg-gray-900 mt-6"></div>
        </div>
    </div>

    <div class="max-w-screen-2xl mx-auto px-4 lg:px-6">
        @if ($errors->any())
            <div class="mb-8 bg-red-50 border-l-2 border-red-500 p-4">
                <p class="text-xs font-bold text-red-500 uppercase tracking-widest">Please correct the following errors:</p>
                <ul class="mt-2 text-xs text-red-600 list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="mb-8 bg-gray-50 border-l-4 border-gray-900 text-gray-900 px-4 py-3 text-xs tracking-wide uppercase" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @php
            $summary = session('importSummary', []);
        @endphp

        @if(!empty($summary))
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-10">
                <div class="border border-gray-100 bg-white p-4">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Total</p>
                    <p class="mt-2 text-2xl font-serif text-gray-900">{{ $summary['total'] ?? 0 }}</p>
                </div>
                <div class="border border-gray-100 bg-white p-4">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Created</p>
                    <p class="mt-2 text-2xl font-serif text-gray-900">{{ $summary['created'] ?? 0 }}</p>
                </div>
                <div class="border border-gray-100 bg-white p-4">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Updated</p>
                    <p class="mt-2 text-2xl font-serif text-gray-900">{{ $summary['updated'] ?? 0 }}</p>
                </div>
                <div class="border border-gray-100 bg-white p-4">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Skipped</p>
                    <p class="mt-2 text-2xl font-serif text-gray-900">{{ $summary['skipped'] ?? 0 }}</p>
                </div>
                <div class="border border-gray-100 bg-white p-4">
                    <p class="text-[10px] uppercase tracking-[0.2em] text-gray-400">Errors</p>
                    <p class="mt-2 text-2xl font-serif text-gray-900">{{ count($summary['errors'] ?? []) }}</p>
                </div>
            </div>

            @if(!empty($summary['errors']))
                <div class="mb-10 border border-gray-100 bg-gray-50 p-5">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 mb-4">Rows that need attention</p>
                    <ul class="space-y-2 text-sm text-gray-700">
                        @foreach($summary['errors'] as $error)
                            <li class="border-l-2 border-gray-300 pl-3">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 xl:gap-16">
            <div class="lg:col-span-7 xl:col-span-8">
                <div class="border border-gray-100 bg-white p-5 md:p-8 shadow-sm">
                    <form id="bulkImportForm" action="{{ route('admin.products.import.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div id="dropZone" class="group relative border-2 border-dashed border-gray-200 bg-gray-50/60 hover:bg-gray-50 hover:border-gray-400 transition-all p-8 md:p-12 text-center">
                            <input type="file" name="file" id="fileInput" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".csv,.xlsx,.txt">

                            <div class="pointer-events-none">
                                <div class="mx-auto w-14 h-14 rounded-full border border-gray-300 flex items-center justify-center text-gray-400 group-hover:text-gray-900 group-hover:border-gray-900 transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.907A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <h2 class="mt-6 text-xl md:text-2xl font-serif text-gray-900 tracking-wider uppercase">Drop file here</h2>
                                <p class="mt-3 text-sm text-gray-500">or click to choose a CSV / XLSX file from your computer.</p>
                                <p id="selectedFileName" class="mt-6 text-xs uppercase tracking-[0.2em] text-gray-400">No file selected</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col sm:flex-row gap-3">
                            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 bg-gray-900 text-white uppercase text-xs tracking-widest border border-gray-900 hover:bg-white hover:text-gray-900 transition-colors">
                                Import Products
                            </button>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white text-gray-900 uppercase text-xs tracking-widest border border-gray-900 hover:bg-gray-900 hover:text-white transition-colors">
                                Back to Products
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <div class="lg:col-span-5 xl:col-span-4 space-y-6">
                <div class="border border-gray-100 bg-white p-5 md:p-7">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 mb-4">Expected Columns</p>
                    <div class="space-y-3 text-sm text-gray-700">
                        <p><span class="font-semibold text-gray-900">name</span> or <span class="font-semibold text-gray-900">Nama</span></p>
                        <p><span class="font-semibold text-gray-900">slug</span> optional, used to match the same product on re-import</p>
                        <p><span class="font-semibold text-gray-900">description</span>, <span class="font-semibold text-gray-900">price_idr</span>, <span class="font-semibold text-gray-900">price_usd</span></p>
                        <p><span class="font-semibold text-gray-900">parent_category</span> and <span class="font-semibold text-gray-900">category</span></p>
                        <p><span class="font-semibold text-gray-900">images</span>, <span class="font-semibold text-gray-900">colors</span>, <span class="font-semibold text-gray-900">sizes</span>, <span class="font-semibold text-gray-900">specifications</span></p>
                    </div>
                </div>

                <div class="border border-gray-100 bg-gray-50 p-5 md:p-7">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-gray-500 mb-4">Format Notes</p>
                    <ul class="space-y-3 text-sm text-gray-700 leading-7">
                        <li>Use <span class="font-semibold text-gray-900">|</span> to separate multiple images, colors, or sizes.</li>
                        <li>Use <span class="font-semibold text-gray-900">default</span> to restore the built-in color or ring size presets.</li>
                        <li>Use <span class="font-semibold text-gray-900">none</span> to clear an existing relation.</li>
                        <li>Specifications use <span class="font-semibold text-gray-900">Section: Item 1|Item 2; Another Section: Item 1</span>.</li>
                        <li>When the same product is imported again, it updates by slug first, then by name.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const selectedFileName = document.getElementById('selectedFileName');

        if (!dropZone || !fileInput || !selectedFileName) {
            return;
        }

        const updateLabel = (file) => {
            selectedFileName.textContent = file ? file.name : 'No file selected';
        };

        fileInput.addEventListener('change', () => {
            updateLabel(fileInput.files && fileInput.files[0] ? fileInput.files[0] : null);
        });

        ['dragenter', 'dragover'].forEach((eventName) => {
            dropZone.addEventListener(eventName, (event) => {
                event.preventDefault();
                event.stopPropagation();
                dropZone.classList.add('border-gray-900', 'bg-white');
            });
        });

        ['dragleave', 'drop'].forEach((eventName) => {
            dropZone.addEventListener(eventName, (event) => {
                event.preventDefault();
                event.stopPropagation();
                dropZone.classList.remove('border-gray-900', 'bg-white');
            });
        });

        dropZone.addEventListener('drop', (event) => {
            const files = event.dataTransfer && event.dataTransfer.files ? event.dataTransfer.files : null;

            if (files && files.length > 0) {
                fileInput.files = files;
                updateLabel(files[0]);
            }
        });
    })();
</script>
@endsection