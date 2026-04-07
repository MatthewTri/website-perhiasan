@extends('layout.main')

@section('content')
<div class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Shipping Policy</h1>
                <p class="mt-2 text-sm text-gray-500">Last updated: {{ now()->format('F j, Y') }}</p>
            </div>
        </div>

        <div class="mt-8 p-5 sm:p-6 bg-[#FAFAFA] border border-gray-200">
            <p class="text-sm text-gray-700 leading-relaxed">
                This Shipping Policy explains how orders from <span class="font-medium text-gray-900">Cecile Private Collection</span> are produced, prepared, shipped, and delivered.
                Our pieces are bespoke and crafted with care, and secure delivery is part of that experience.
            </p>
            <p class="mt-3 text-sm text-gray-700 leading-relaxed">
                <span class="font-medium text-gray-900">Important:</span>
                All items are <span class="font-medium">Bespoke / Custom-Made</span>. Production time applies before shipping.
            </p>
        </div>

        <div class="mt-10 space-y-10">
            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">1. Production Time (Bespoke Crafting)</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>
                        Each piece is made-to-order. Please allow <span class="font-medium">2 to 8 weeks</span> of crafting time before your order is shipped.
                        This timeline is an estimate and may vary depending on design complexity, materials availability, quality checks, and peak seasons.
                    </p>
                    <p>
                        If you have a deadline (gift date, event date, travel schedule), contact us <span class="font-medium">before</span> placing your order so we can advise on feasibility.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">2. Domestic Shipping (Indonesia)</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>
                        For domestic deliveries within Indonesia, we exclusively ship using <span class="font-medium">JNE</span> and <span class="font-medium">Paxel</span>.
                        These carriers are selected for secure handling and reliable service.
                    </p>
                    <p>
                        Shipping fees and available service levels (regular/express) may vary based on your delivery address.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">3. International Shipping</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>
                        For international orders, we exclusively ship using <span class="font-medium">RPX</span> as our trusted global courier partner.
                        International shipping times vary by destination and customs processing.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">4. Security & Insurance (Signature Required)</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>
                        All shipments are <span class="font-medium">fully insured during transit</span>.
                        For security reasons, a <span class="font-medium">direct signature is strictly required upon delivery</span>.
                    </p>
                    <p>
                        We do <span class="font-medium">not</span> allow packages to be left unattended (for example: left at the door, lobby, mailbox area, or with neighbors without your direct acceptance).
                        Please ensure someone is available to sign at the delivery address.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">5. Tracking & Delivery Updates</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>
                        Once your order is shipped, we will provide tracking details when available.
                        Delivery status updates are managed by the courier, and scanning events may not always appear in real time.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">6. Address Accuracy</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>
                        Please double-check your shipping address, phone number, and recipient name at checkout.
                        We are not responsible for delays or delivery issues caused by incomplete or incorrect address information.
                    </p>
                    <p>
                        If you need to change your address, contact us as soon as possible.
                        Changes may not be possible once a shipment has been handed to the courier.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">7. Customs, Duties & Import Fees (International)</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>
                        For international orders, the buyer is <span class="font-medium">solely responsible</span> for any import taxes, customs duties, VAT/GST, handling charges, or other fees levied by the destination country.
                        These charges are not included in our product or shipping prices unless explicitly stated.
                    </p>
                    <p>
                        Customs processing times and requirements vary by country and are outside our control.
                        Refusal to pay duties/taxes or provide required information may result in delays, return-to-sender, or disposal by customs, and may not be refundable.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">8. Delays, Lost or Damaged Shipments</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>
                        Delivery timeframes are estimates and may be affected by factors such as weather, peak seasons, customs inspections, or courier operational constraints.
                        Insurance coverage and resolution steps depend on courier investigations and available evidence.
                    </p>
                    <p>
                        If your package arrives damaged or appears tampered with, please document it immediately (photos/video of the outer packaging, shipping label, inner packaging, and item) and contact us promptly.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">9. Contact</h2>
                <div class="mt-4 space-y-5 text-sm text-gray-700 leading-relaxed">
                    <p>
                        If you have shipping questions (delivery scheduling, signature arrangements, tracking assistance), please reach us through the contact options below.
                    </p>

                    <div class="space-y-4">
                        <a href="https://wa.me/6287766999564" target="_blank" rel="noopener noreferrer"
                           class="group flex items-start gap-4 p-5 bg-[#FAFAFA] hover:bg-gray-100 transition-all duration-300 border border-gray-200 hover:border-gray-300">
                            <div class="flex-shrink-0 w-11 h-11 bg-white rounded-full flex items-center justify-center text-green-600 group-hover:text-green-700 transition-colors shadow-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M20.52 3.48A11.94 11.94 0 0 0 12 0C5.38 0 .03 5.35 0 11.97c0 2.11.55 4.17 1.6 6.01L0 24l6.18-1.62a11.94 11.94 0 0 0 5.82 1.49h.01c6.62 0 11.97-5.35 12-11.97a11.9 11.9 0 0 0-3.49-8.42ZM12 22.06h-.01a9.96 9.96 0 0 1-5.08-1.39l-.36-.21-3.67.96.98-3.58-.24-.37A9.94 9.94 0 0 1 2 11.97C2.03 6.46 6.5 2 12 2c2.66 0 5.16 1.04 7.04 2.93A9.89 9.89 0 0 1 22 11.97c-.03 5.51-4.5 10.09-10 10.09Zm5.8-7.49c-.32-.16-1.9-.94-2.2-1.05-.3-.11-.52-.16-.73.16-.21.32-.84 1.05-1.03 1.27-.19.21-.38.24-.7.08-.32-.16-1.36-.5-2.59-1.6-.96-.86-1.6-1.92-1.79-2.24-.19-.32-.02-.49.14-.65.14-.14.32-.38.49-.57.16-.19.21-.32.32-.54.11-.21.05-.4-.03-.56-.08-.16-.73-1.76-1-2.41-.26-.63-.52-.55-.73-.56h-.62c-.21 0-.56.08-.86.4-.3.32-1.13 1.1-1.13 2.68 0 1.57 1.16 3.09 1.32 3.3.16.21 2.28 3.48 5.52 4.88.77.33 1.37.53 1.84.68.77.24 1.47.21 2.02.13.62-.09 1.9-.78 2.17-1.54.27-.76.27-1.41.19-1.54-.08-.13-.3-.21-.62-.37Z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold uppercase tracking-[0.15em] text-gray-900 mb-1">WhatsApp</p>
                                <p class="text-sm text-gray-600 font-mono">+62 877 6699 9564</p>
                                <p class="text-xs text-gray-500 mt-2 font-light">Click to chat with us instantly</p>
                            </div>
                            <div class="flex-shrink-0 text-gray-300 group-hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>

                        <a href="mailto:cecileprivatecollection@gmail.com"
                           class="group flex items-start gap-4 p-5 bg-[#FAFAFA] hover:bg-gray-100 transition-all duration-300 border border-gray-200 hover:border-gray-300">
                            <div class="flex-shrink-0 w-11 h-11 bg-white rounded-full flex items-center justify-center text-gray-700 group-hover:text-gray-900 transition-colors shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold uppercase tracking-[0.15em] text-gray-900 mb-1">Email</p>
                                <p class="text-sm text-gray-600 break-all font-mono">cecileprivatecollection@gmail.com</p>
                                <p class="text-xs text-gray-500 mt-2 font-light">Send us an email inquiry</p>
                            </div>
                            <div class="flex-shrink-0 text-gray-300 group-hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </div>
                        </a>
                    </div>

                    <p class="text-xs text-gray-400 font-light leading-relaxed">
                        Our team typically responds within 24 hours during business hours.
                    </p>
                </div>
            </section>
        </div>

        <div class="mt-12 pt-6 border-t border-gray-200 flex items-center justify-between gap-4">
            <a href="{{ route('policies.terms') }}" class="text-xs uppercase tracking-widest text-gray-500 border-b border-gray-300 hover:text-gray-900 hover:border-gray-900 transition-colors">Terms & Conditions</a>
            <a href="{{ route('policies.privacy') }}" class="text-xs uppercase tracking-widest text-gray-500 border-b border-gray-300 hover:text-gray-900 hover:border-gray-900 transition-colors">Privacy Policy</a>
            <a href="{{ route('policies.refund') }}" class="text-xs uppercase tracking-widest text-gray-500 border-b border-gray-300 hover:text-gray-900 hover:border-gray-900 transition-colors">Refund Policy</a>
        </div>
    </div>
</div>
@endsection
