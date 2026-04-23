@extends('layout.main')

@section('content')

{{-- HERO SECTION --}}
<section class="relative w-full h-[70vh] md:h-[85vh] overflow-hidden bg-gradient-to-br from-[#F5E6E0] to-[#E3CFCF]/30">
    <div class="absolute inset-0">
        <img src="{{ asset('asset/aboutus/DD_05829.jpg') }}"
             alt="Cecile Private Collection Atelier"
             class="w-full h-full object-cover object-center opacity-80">
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-black/30 to-transparent"></div>

    <div class="relative h-full flex items-end pb-16 md:pb-24 px-6 md:px-16 lg:px-24">
        <div class="max-w-3xl animate-fade-in">
            <p class="text-[#E3CFCF] text-xs md:text-sm tracking-[0.4em] uppercase font-light mb-4">Est. 2019 · Surabaya, Indonesia</p>
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-light text-white tracking-[0.15em] uppercase leading-tight mb-6">
                Our <br><em class="italic not-italic font-extralight">Story</em>
            </h1>
            <div class="w-16 h-px bg-[#E3CFCF] mb-6"></div>
            <p class="text-white/85 font-light text-sm md:text-base leading-relaxed tracking-wide max-w-xl">
                Where jewellery becomes a language of memory, identity, and enduring beauty.
            </p>
        </div>
    </div>
</section>

{{-- FOUNDER INTRO SECTION --}}
<section class="max-w-screen-2xl mx-auto px-6 lg:px-16 py-20 md:py-32">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

        {{-- Text --}}
        <div class="order-2 lg:order-1">
            <p class="text-[#E3CFCF] text-xs tracking-[0.4em] uppercase font-medium mb-5">The Founder</p>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-light text-gray-900 tracking-[0.1em] uppercase leading-tight mb-8">
                Cecilia <br>Hermanto
            </h2>
            <div class="w-12 h-px bg-[#E3CFCF] mb-8"></div>
            <div class="space-y-5 text-gray-600 font-light text-sm md:text-base leading-relaxed">
                <p>
                    Cecile Private Collection was founded by Cecilia Hermanto, whose journey in jewellery began through formal studies in both design and business. She first pursued Jewellery Design at <span class="text-gray-900 font-normal">Nanyang Academy of Fine Arts, Singapore</span>, where she developed her understanding of form, proportion, and the relationship between jewellery and personal expression.
                </p>
                <p>
                    She later continued her studies in <span class="text-gray-900 font-normal">International Jewellery Business at Birmingham City University, United Kingdom</span>, which broadened her perspective on the industry — from materials and production to consumer behaviour and the evolving role of jewellery in modern life.
                </p>
                <p>
                    To deepen her expertise further, she also continued her studies at <span class="text-gray-900 font-normal">GIA in coloured stones</span>, refining her knowledge in gemstone evaluation with a focus on beauty, optical performance, durability, and long-term value.
                </p>
            </div>
        </div>

        {{-- Image --}}
        <div class="order-1 lg:order-2 relative">
            <div class="relative overflow-hidden">
                <img src="{{ asset('asset/aboutus/DD_06014.jpg') }}"
                     alt="Cecilia Hermanto - Founder"
                     class="w-full h-[500px] md:h-[620px] object-cover object-center">
                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
            </div>
            {{-- Floating credential badge --}}
            <div class="absolute -bottom-6 -left-4 md:-left-8 bg-white shadow-xl px-6 py-5 max-w-[200px]">
                <p class="text-[9px] text-[#E3CFCF] tracking-[0.3em] uppercase font-medium mb-1">Certified by</p>
                <p class="text-xs font-bold text-gray-900 tracking-wide uppercase leading-snug">GIA · NAFA · Birmingham City University</p>
            </div>
        </div>
    </div>
</section>

{{-- BRAND STORY SECTION --}}
<section class="bg-gradient-to-b from-white to-[#F5E6E0]/30 py-20 md:py-32">
    <div class="max-w-screen-2xl mx-auto px-6 lg:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            {{-- Image Grid --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="overflow-hidden aspect-[3/4]">
                    <img src="{{ asset('asset/aboutus/DD_05860.JPG') }}"
                         alt="Jewellery Design Process"
                         class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                </div>
                <div class="overflow-hidden aspect-[3/4] mt-8">
                    <img src="{{ asset('asset/aboutus/DD_05909.JPG') }}"
                         alt="Gemstone Expertise"
                         class="w-full h-full object-cover transition-transform duration-700 hover:scale-105">
                </div>
            </div>

            {{-- Text --}}
            <div>
                <p class="text-[#E3CFCF] text-xs tracking-[0.4em] uppercase font-medium mb-5">Our Philosophy</p>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-light text-gray-900 tracking-[0.1em] uppercase leading-tight mb-8">
                    More Than <br>Beautiful
                </h2>
                <div class="w-12 h-px bg-[#E3CFCF] mb-8"></div>
                <div class="space-y-5 text-gray-600 font-light text-sm md:text-base leading-relaxed">
                    <p>
                        Established in 2019, Cecile Private Collection was born from Cecilia's love for jewellery and her belief that every piece should be more than beautiful.
                    </p>
                    <p>
                        For her, jewellery is deeply personal — something that can hold emotion, preserve memories, and reflect the identity of its wearer. This belief became the foundation of the brand, where each design is approached not only as an object of adornment, but as <span class="text-gray-900 font-normal italic">a meaningful piece with its own story to tell.</span>
                    </p>
                    <p>
                        With a strong design background and a thoughtful understanding of gemstones, Cecilia built the brand around the idea that jewellery should feel personal, intentional, and lasting.
                    </p>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-3 gap-6 mt-12 pt-12 border-t border-[#E3CFCF]/50">
                    <div class="text-center">
                        <p class="text-3xl md:text-4xl font-light text-gray-900 tracking-wider">2019</p>
                        <p class="text-[10px] text-gray-500 tracking-[0.25em] uppercase mt-1">Established</p>
                    </div>
                    <div class="text-center border-x border-[#E3CFCF]/50">
                        <p class="text-3xl md:text-4xl font-light text-gray-900 tracking-wider">3+</p>
                        <p class="text-[10px] text-gray-500 tracking-[0.25em] uppercase mt-1">Certifications</p>
                    </div>
                    <div class="text-center">
                        <p class="text-3xl md:text-4xl font-light text-gray-900 tracking-wider">100%</p>
                        <p class="text-[10px] text-gray-500 tracking-[0.25em] uppercase mt-1">Bespoke</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- BESPOKE PROCESS SECTION --}}
<section class="max-w-screen-2xl mx-auto px-6 lg:px-16 py-20 md:py-32">
    <div class="text-center mb-16 md:mb-20">
        <p class="text-[#E3CFCF] text-xs tracking-[0.4em] uppercase font-medium mb-4">How We Work</p>
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-light text-gray-900 tracking-[0.2em] uppercase mb-6">
            The Bespoke Experience
        </h2>
        <div class="w-16 h-px bg-[#E3CFCF] mx-auto mb-6"></div>
        <p class="text-sm md:text-base text-gray-600 font-light leading-relaxed max-w-2xl mx-auto">
            At Cecile Private Collection, the approach begins with the wearer. Rather than focusing on ready-made collections, the brand offers bespoke jewellery services — creating custom pieces designed around each client's story, preferences, and purpose.
        </p>
    </div>

    {{-- Process Steps --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-0 mb-20">
        <div class="relative group">
            <div class="overflow-hidden h-[350px] md:h-[450px]">
                <img src="{{ asset('asset/aboutus/DD_05974.jpg') }}"
                     alt="Consultation"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
                <p class="text-[#E3CFCF] text-[10px] tracking-[0.4em] uppercase mb-2">Step 01</p>
                <h3 class="text-white text-lg md:text-xl font-light tracking-[0.15em] uppercase mb-2">The Conversation</h3>
                <p class="text-white/75 text-xs md:text-sm font-light leading-relaxed">Every design begins with understanding the meaning and story behind it.</p>
            </div>
        </div>

        <div class="relative group">
            <div class="overflow-hidden h-[350px] md:h-[450px]">
                <img src="{{ asset('asset/aboutus/DD_05871.JPG') }}"
                     alt="Design Process"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
                <p class="text-[#E3CFCF] text-[10px] tracking-[0.4em] uppercase mb-2">Step 02</p>
                <h3 class="text-white text-lg md:text-xl font-light tracking-[0.15em] uppercase mb-2">The Design</h3>
                <p class="text-white/75 text-xs md:text-sm font-light leading-relaxed">Cecilia translates your vision into a thoughtful, personalised design.</p>
            </div>
        </div>

        <div class="relative group">
            <div class="overflow-hidden h-[350px] md:h-[450px]">
                <img src="{{ asset('asset/aboutus/DD_05960.jpg') }}"
                     alt="Craftsmanship"
                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-8">
                <p class="text-[#E3CFCF] text-[10px] tracking-[0.4em] uppercase mb-2">Step 03</p>
                <h3 class="text-white text-lg md:text-xl font-light tracking-[0.15em] uppercase mb-2">The Creation</h3>
                <p class="text-white/75 text-xs md:text-sm font-light leading-relaxed">Skilled Indonesian artisans bring every piece to life with careful attention to detail.</p>
            </div>
        </div>
    </div>

    {{-- Bespoke Offerings --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12 pt-12 border-t border-[#E3CFCF]/30">
        <div class="flex flex-col items-center text-center space-y-4 group">
            <div class="w-16 h-16 flex items-center justify-center bg-[#F5E6E0] group-hover:bg-[#E3CFCF] transition-colors duration-300">
                <svg class="w-7 h-7 text-[#C9A89A] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold tracking-[0.2em] uppercase text-gray-900">Engagement Rings</h3>
            <p class="text-xs text-gray-500 font-light leading-relaxed max-w-xs">Designed to mark life's most significant commitment, crafted to last forever.</p>
        </div>
        <div class="flex flex-col items-center text-center space-y-4 group">
            <div class="w-16 h-16 flex items-center justify-center bg-[#F5E6E0] group-hover:bg-[#E3CFCF] transition-colors duration-300">
                <svg class="w-7 h-7 text-[#C9A89A] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold tracking-[0.2em] uppercase text-gray-900">Wedding Bands</h3>
            <p class="text-xs text-gray-500 font-light leading-relaxed max-w-xs">Symbolic rings that carry the weight of promises and the beauty of partnership.</p>
        </div>
        <div class="flex flex-col items-center text-center space-y-4 group">
            <div class="w-16 h-16 flex items-center justify-center bg-[#F5E6E0] group-hover:bg-[#E3CFCF] transition-colors duration-300">
                <svg class="w-7 h-7 text-[#C9A89A] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                </svg>
            </div>
            <h3 class="text-sm font-bold tracking-[0.2em] uppercase text-gray-900">Personal Pieces</h3>
            <p class="text-xs text-gray-500 font-light leading-relaxed max-w-xs">Jewellery that reflects your identity, story, and the moments you hold dear.</p>
        </div>
    </div>
</section>

{{-- CRAFTSMANSHIP SECTION --}}
<section class="bg-[#F5E6E0]/30 py-20 md:py-32">
    <div class="max-w-screen-2xl mx-auto px-6 lg:px-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            {{-- Text --}}
            <div>
                <p class="text-[#E3CFCF] text-xs tracking-[0.4em] uppercase font-medium mb-5">Our Artisans</p>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-light text-gray-900 tracking-[0.1em] uppercase leading-tight mb-8">
                    Indonesian <br>Craftsmanship
                </h2>
                <div class="w-12 h-px bg-[#E3CFCF] mb-8"></div>
                <div class="space-y-5 text-gray-600 font-light text-sm md:text-base leading-relaxed">
                    <p>
                        Every piece is brought to life by skilled Indonesian artisans, whose craftsmanship plays an essential role in the identity of Cecile Private Collection. Each jewellery piece is carefully handcrafted with close attention to detail, ensuring both quality and character in every finish.
                    </p>
                    <p>
                        By working with local artisans, the brand not only maintains a high standard of craftsmanship, but also <span class="text-gray-900 font-normal italic">supports the value of Indonesian handmade work.</span>
                    </p>
                    <p>
                        The result is jewellery that is not only refined in design, but also rich in story, intention, and artistry.
                    </p>
                </div>

                <div class="mt-10">
                    <a href="{{ route('consultation.index') }}"
                       class="inline-flex items-center justify-center px-10 py-4 bg-gray-900 text-white hover:bg-[#E3CFCF] transition-all duration-300 border-2 border-gray-900 hover:border-[#E3CFCF]">
                        <span class="text-xs font-bold tracking-[0.25em] uppercase">Book a Consultation</span>
                    </a>
                </div>
            </div>

            {{-- Image Mosaic --}}
            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-3">
                    <div class="overflow-hidden">
                        <img src="{{ asset('asset/aboutus/DD_05963.jpg') }}"
                             alt="Gemstone Inspection"
                             class="w-full h-48 md:h-56 object-cover transition-transform duration-700 hover:scale-105">
                    </div>
                    <div class="overflow-hidden">
                        <img src="{{ asset('asset/aboutus/DD_06063.jpg') }}"
                             alt="Jewellery Detail"
                             class="w-full h-36 md:h-44 object-cover transition-transform duration-700 hover:scale-105">
                    </div>
                </div>
                <div class="space-y-3 mt-6">
                    <div class="overflow-hidden">
                        <img src="{{ asset('asset/aboutus/DD_05981.jpg') }}"
                             alt="Studio Collection"
                             class="w-full h-36 md:h-44 object-cover transition-transform duration-700 hover:scale-105">
                    </div>
                    <div class="overflow-hidden">
                        <img src="{{ asset('asset/aboutus/DD_05993.jpg') }}"
                             alt="Display Collection"
                             class="w-full h-48 md:h-56 object-cover object-top transition-transform duration-700 hover:scale-105">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CREDENTIALS / EDUCATION SECTION --}}
<section class="max-w-screen-2xl mx-auto px-6 lg:px-16 py-20 md:py-32">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

        {{-- Image --}}
        <div class="relative overflow-hidden">
            <img src="{{ asset('asset/aboutus/DD_05934.jpg') }}"
                 alt="Cecilia with Certificates"
                 class="w-full h-[500px] md:h-[620px] object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
        </div>

        {{-- Text --}}
        <div>
            <p class="text-[#E3CFCF] text-xs tracking-[0.4em] uppercase font-medium mb-5">Expertise & Education</p>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-light text-gray-900 tracking-[0.1em] uppercase leading-tight mb-8">
                Knowledge <br>You Can <br>Trust
            </h2>
            <div class="w-12 h-px bg-[#E3CFCF] mb-8"></div>

            <div class="space-y-6">
                {{-- Credential 1 --}}
                <div class="flex gap-5 group">
                    <div class="flex-shrink-0 w-10 h-10 bg-[#F5E6E0] group-hover:bg-[#E3CFCF] transition-colors flex items-center justify-center mt-0.5">
                        <svg class="w-5 h-5 text-[#C9A89A] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 tracking-[0.1em] uppercase mb-1">Nanyang Academy of Fine Arts</h4>
                        <p class="text-xs text-[#C9A89A] tracking-wider mb-1.5">Singapore · Jewellery Design</p>
                        <p class="text-xs text-gray-500 font-light leading-relaxed">Foundation in form, proportion, and the artistry of jewellery as personal expression.</p>
                    </div>
                </div>

                <div class="w-full h-px bg-[#F5E6E0]"></div>

                {{-- Credential 2 --}}
                <div class="flex gap-5 group">
                    <div class="flex-shrink-0 w-10 h-10 bg-[#F5E6E0] group-hover:bg-[#E3CFCF] transition-colors flex items-center justify-center mt-0.5">
                        <svg class="w-5 h-5 text-[#C9A89A] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 tracking-[0.1em] uppercase mb-1">Birmingham City University</h4>
                        <p class="text-xs text-[#C9A89A] tracking-wider mb-1.5">United Kingdom · International Jewellery Business</p>
                        <p class="text-xs text-gray-500 font-light leading-relaxed">Deep understanding of the global jewellery industry, materials, production, and market dynamics.</p>
                    </div>
                </div>

                <div class="w-full h-px bg-[#F5E6E0]"></div>

                {{-- Credential 3 --}}
                <div class="flex gap-5 group">
                    <div class="flex-shrink-0 w-10 h-10 bg-[#F5E6E0] group-hover:bg-[#E3CFCF] transition-colors flex items-center justify-center mt-0.5">
                        <svg class="w-5 h-5 text-[#C9A89A] group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-900 tracking-[0.1em] uppercase mb-1">GIA — Gemological Institute of America</h4>
                        <p class="text-xs text-[#C9A89A] tracking-wider mb-1.5">Coloured Stones Grading</p>
                        <p class="text-xs text-gray-500 font-light leading-relaxed">Advanced expertise in gemstone evaluation — beauty, optical performance, durability, and lasting value.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FULL WIDTH QUOTE SECTION --}}
<section class="relative overflow-hidden py-24 md:py-36 bg-gradient-to-br from-[#E3CFCF] to-[#e3c5c5]">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-72 h-72 rounded-full bg-white blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 rounded-full bg-white blur-3xl translate-x-1/3 translate-y-1/3"></div>
    </div>
    <div class="relative max-w-4xl mx-auto px-6 text-center">
        <div class="text-white/30 text-8xl md:text-9xl font-serif leading-none mb-4 select-none">"</div>
        <blockquote class="text-xl md:text-2xl lg:text-3xl text-white font-light leading-relaxed tracking-wide italic -mt-8 mb-8">
            Jewellery is deeply personal — something that can hold emotion, preserve memories, and reflect the identity of its wearer.
        </blockquote>
        <div class="w-16 h-px bg-white/50 mx-auto mb-6"></div>
        <p class="text-white/80 text-sm tracking-[0.3em] uppercase font-light">Cecilia Hermanto · Founder</p>
    </div>
</section>

{{-- CTA SECTION --}}
<section class="max-w-screen-2xl mx-auto px-6 lg:px-16 py-20 md:py-28">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
        <div>
            <p class="text-[#E3CFCF] text-xs tracking-[0.4em] uppercase font-medium mb-5">Begin Your Journey</p>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-light text-gray-900 tracking-[0.1em] uppercase leading-tight mb-6">
                Let's Create <br>Something <br>Yours
            </h2>
            <div class="w-12 h-px bg-[#E3CFCF] mb-8"></div>
            <p class="text-gray-600 font-light text-sm md:text-base leading-relaxed mb-10 max-w-md">
                From engagement rings and wedding bands to personal jewellery pieces — every design begins with a conversation. We'd love to hear your story.
            </p>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('consultation.index') }}"
                   class="inline-flex items-center justify-center px-10 py-4 bg-gray-900 text-white hover:bg-[#E3CFCF] transition-all duration-300 border-2 border-gray-900 hover:border-[#E3CFCF]">
                    <span class="text-xs font-bold tracking-[0.25em] uppercase">Book a Consultation</span>
                </a>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center justify-center px-10 py-4 bg-transparent text-gray-900 border-2 border-gray-300 hover:border-[#E3CFCF] hover:text-[#C9A89A] transition-all duration-300">
                    <span class="text-xs font-bold tracking-[0.25em] uppercase">View Collection</span>
                </a>
            </div>
        </div>

        {{-- Final Image --}}
        <div class="relative overflow-hidden">
            <img src="{{ asset('asset/aboutus/DD_05824.jpg') }}"
                 alt="Cecile Private Collection Studio"
                 class="w-full h-[420px] md:h-[520px] object-cover object-center">
            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
        </div>
    </div>
</section>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(24px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fade-in 1.1s ease-out both;
    }

    /* Smooth scroll reveal on scroll (CSS-only approach) */
    @media (prefers-reduced-motion: no-preference) {
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease, transform 0.8s ease;
        }
        .reveal.visible {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    // Scroll reveal
    const reveals = document.querySelectorAll('.reveal');
    if (reveals.length) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        reveals.forEach(el => observer.observe(el));
    }
</script>

@endsection