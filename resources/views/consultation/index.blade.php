@extends('layout.main')

@section('content')
<div class="min-h-screen bg-stone-50 py-16 md:py-24">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="text-center mb-16 animate-fade-in-up">
            <h6 class="text-xs font-bold tracking-[0.2em] text-stone-500 uppercase mb-3">
                The Cecile Experience
            </h6>
            <h1 class="text-3xl md:text-5xl font-serif text-stone-900 tracking-tight mb-6">
                Book an Appointment
            </h1>
            <p class="text-stone-600 font-light max-w-2xl mx-auto leading-relaxed text-sm md:text-base">
                Whether you wish to explore our latest collection or create a bespoke masterpiece, 
                our consultants are dedicated to guiding you through a personalized journey.
            </p>
        </div>

        {{-- Contact Info Strip --}}
        <div class="flex flex-col md:flex-row justify-center items-center gap-4 md:gap-8 text-xs text-stone-500 font-medium tracking-wide uppercase mb-16">
            <a href="tel:+6139663119" class="flex items-center hover:text-[#E8B49E] transition-colors group">
                <svg class="w-4 h-4 mr-2 text-stone-400 group-hover:text-[#E8B49E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                +61 3 9663 6119
            </a>
            <span class="hidden md:inline text-stone-300">|</span>
            <a href="mailto:info@cecileprivate.com" class="flex items-center hover:text-[#E8B49E] transition-colors group">
                <svg class="w-4 h-4 mr-2 text-stone-400 group-hover:text-[#E8B49E]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                info@cecileprivate.com
            </a>
        </div>

        {{-- Service Cards --}}
        <div class="grid md:grid-cols-2 gap-8 mb-12">
            
            {{-- Card 1: Collection Discovery --}}
            <div class="group bg-white p-8 md:p-10 border border-stone-200 rounded-sm hover:border-stone-400 hover:shadow-[0_10px_40px_-15px_rgba(0,0,0,0.05)] transition-all duration-300 flex flex-col items-center text-center">
                <div class="w-14 h-14 bg-stone-50 rounded-full flex items-center justify-center mb-6 text-stone-800 group-hover:bg-stone-900 group-hover:text-white transition-colors duration-300">
                    {{-- Icon Diamond --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                
                <h3 class="text-xl font-serif text-stone-900 mb-3">General Consultation</h3>
                <p class="text-stone-500 text-sm font-light leading-relaxed mb-8 min-h-[48px]">
                    Have questions about our <strong>Pre-Order collections</strong>, sizing, or materials? Book this concise session for quick assistance and general inquiries.
                </p>
                
                <div class="w-full mt-auto space-y-6">
                    <div class="flex justify-center items-center space-x-6 text-xs font-bold text-stone-400 uppercase tracking-widest border-t border-stone-100 pt-6">
                        <span class="flex items-center"><svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 30 Mins</span>
                        <span class="flex items-center"><svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg> In-Store</span>
                    </div>

                    <a href="{{ route('consultation.create', ['type' => 'short']) }}" 
                       class="block w-full bg-stone-900 text-white py-4 px-6 text-xs font-bold uppercase tracking-[0.2em] hover:bg-stone-800 transition-all duration-300 rounded-sm">
                        Reserve Slot
                    </a>
                </div>
            </div>

            {{-- Card 2: Bespoke Design --}}
            <div class="group bg-white p-8 md:p-10 border border-stone-200 rounded-sm hover:border-stone-400 hover:shadow-[0_10px_40px_-15px_rgba(0,0,0,0.05)] transition-all duration-300 flex flex-col items-center text-center">
                <div class="w-14 h-14 bg-stone-50 rounded-full flex items-center justify-center mb-6 text-stone-800 group-hover:bg-stone-900 group-hover:text-white transition-colors duration-300">
                    {{-- Icon Pencil/Design --}}
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </div>
                
                <h3 class="text-xl font-serif text-stone-900 mb-3">Bespoke Design Journey</h3>
                <p class="text-stone-500 text-sm font-light leading-relaxed mb-8 min-h-[48px]">
                    An in-depth collaboration for <strong>Custom Requests</strong>. Work with our artisans to design a unique masterpiece from scratch.
                </p>
                
                <div class="w-full mt-auto space-y-6">
                    <div class="flex justify-center items-center space-x-6 text-xs font-bold text-stone-400 uppercase tracking-widest border-t border-stone-100 pt-6">
                        <span class="flex items-center"><svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> 1 Hour</span>
                        <span class="flex items-center"><svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg> Private Suite</span>
                    </div>

                    <a href="{{ route('consultation.create', ['type' => 'design']) }}" 
                       class="block w-full bg-stone-900 text-white py-4 px-6 text-xs font-bold uppercase tracking-[0.2em] hover:bg-stone-800 transition-all duration-300 rounded-sm">
                        Start Journey
                    </a>
                </div>
            </div>

        </div>

        {{-- Address / Additional Info --}}
        <div class="text-center border-t border-stone-200 pt-10">
            <p class="text-xs text-stone-400 font-light tracking-wide uppercase mb-2">Location</p>
            <p class="text-stone-600 font-serif">Suite 904, 125 Swanston St, Melbourne VIC 3000</p>
        </div>

    </div>
</div>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        icon: 'success',
        title: 'Appointment Confirmed!',
        html: '<p class="text-sm text-gray-600">{{ session("success") }}</p>',
        confirmButtonText: 'OK',
        confirmButtonColor: '#292524',
        customClass: {
            popup: 'rounded-sm',
            title: 'text-stone-900',
            confirmButton: 'uppercase tracking-widest text-xs'
        }
    });
});
</script>
@endif

<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
</style>
@endsection