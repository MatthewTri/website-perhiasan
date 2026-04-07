@extends('layout.main')

@section('content')
<div class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Refund Policy</h1>
                <p class="mt-2 text-sm text-gray-500">Last updated: {{ now()->format('F j, Y') }}</p>
            </div>
            <a href="{{ route('checkout.index') }}" class="shrink-0 text-xs uppercase tracking-widest text-gray-500 border-b border-gray-300 hover:text-gray-900 hover:border-gray-900 transition-colors">Back to Checkout</a>
        </div>

        <div class="mt-8 p-5 sm:p-6 bg-[#FAFAFA] border border-gray-200">
            <p class="text-sm text-gray-700 leading-relaxed">
                This Refund Policy explains when we offer repairs, remakes, replacements, or refunds. It is designed for a fine jewelry pre-order experience, where product integrity, authenticity, and customization matter.
            </p>
            <p class="mt-3 text-sm text-gray-700 leading-relaxed">
                <span class="font-medium text-gray-900">Important:</span> <span class="font-medium">All items on this website are Pre-Order / Custom-Made</span>. Because items are produced after you order, they are <span class="font-medium">final sale</span> and are not eligible for returns, exchanges, or refunds for change-of-mind, except where required by applicable law or for verified defects/damage.
            </p>
        </div>

        <div class="mt-10 space-y-10">
            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">1. Scope</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>This policy applies to purchases made on {{ config('app.name') }}. It should be read together with our <a href="{{ route('policies.terms') }}" class="text-gray-900 border-b border-gray-300 hover:border-gray-900 transition-colors">Terms & Conditions</a>.</p>
                    <p>If a local consumer protection law gives you additional rights, those rights remain unchanged.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">2. Final Sale: All Items Are Pre-Order / Custom-Made</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>Our catalog items are produced on a pre-order / made-to-order basis. This includes (without limitation): custom sizing, engraving, special stone requests, metal changes, design modifications, and any item produced or sourced specifically for you.</p>
                    <p>We do not accept returns, exchanges, or refunds for change-of-mind, wrong size provided, or preference changes after production has started, except where required by applicable law.</p>
                    <p>Estimated production timelines are typically <span class="font-medium">2–8 weeks</span>. Delays due to material availability, craftsmanship, quality checks, or customs/carrier issues do not convert a purchase into a refundable purchase.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">3. Defects, Damage in Transit, or Incorrect Items</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>If your item arrives damaged, defective, or incorrect, contact us promptly with photos/videos of:</p>
                    <ul class="mt-2 list-disc pl-5 space-y-2">
                        <li>Outer packaging and shipping label,</li>
                        <li>Inner packaging, and</li>
                        <li>The item and the issue.</li>
                    </ul>
                    <p>After verification, we may offer (as appropriate) repair, remake, replacement, or refund. The solution depends on feasibility, stock/material availability, and the nature of the issue.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">4. Verification & Inspection</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>To protect customers and product integrity, we may request additional information and conduct verification/inspection. For high-value items, we may record serial numbers, micro-engravings, and packaging identifiers to prevent fraud.</p>
                    <p>If we determine the issue is due to misuse, wear-and-tear, alterations by third parties, or incorrect information provided (such as incorrect size confirmed by the customer), we may not be able to offer a refund.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">5. Non-Returnable Items</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>The following are generally not eligible for returns or refunds (except where required by law):</p>
                    <ul class="mt-2 list-disc pl-5 space-y-2">
                        <li>Pre-Order / Custom-Made items (including custom sizing and engraving) for change-of-mind or preference-based reasons.</li>
                        <li>Items marked as final sale, clearance, or non-returnable.</li>
                        <li>Gift cards (if applicable).</li>
                        <li>Items returned without original certificates/packaging when those were part of the delivery.</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">6. Cancellations</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>We may be able to cancel an order <span class="font-medium">before</span> it enters processing/production. Once production begins, cancellations are generally not possible because the item is being made specifically for you.</p>
                    <p>If a cancellation is approved before production, refunds (if any) are handled according to the original payment method (see “Refund Method”).</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">7. Shipping for Repairs/Returns (If Applicable)</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>If we ask you to ship an item back for verification/repair/remake, shipping responsibility depends on the situation. Where permitted by law, shipping costs are generally your responsibility unless the issue is due to our error or a verified defect/damage in transit.</p>
                    <p>We recommend insured, trackable shipping with signature confirmation for high-value items. You are responsible for the item until it is received by us.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">8. International Orders & Customs</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>International shipments may be subject to import duties, taxes, VAT/GST, and brokerage fees. Unless explicitly stated otherwise, these charges are your responsibility.</p>
                    <p>Customs delays, carrier delays, or refusal to pay duties/taxes do not qualify as grounds for a refund. If an international shipment is returned as refused/undeliverable, additional shipping and handling fees may apply and original shipping fees are typically non-refundable.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">9. Refund Method & Processing Time</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>Approved refunds are issued to the original payment method where possible. Processing times depend on inspection completion and your payment provider’s settlement timelines.</p>
                    <p>If you paid via PayPal, refunds are processed through PayPal. PayPal may take additional time to reflect the refund in your account and may handle currency conversion according to its policies.</p>
                    <p>If you paid via <span class="font-medium">Bank Transfer (BCA)</span>, refunds (if approved) are typically processed by our admin/seller via transfer to your BCA bank account after verification of recipient details.</p>
                    <p>Shipping fees are generally non-refundable unless required by law or due to our error.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">10. Disputes, Chargebacks & PayPal Claims</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>If you open a dispute/chargeback/PayPal claim, we may pause production (for bespoke items) and/or shipment while the case is reviewed. You agree to cooperate and provide information promptly.</p>
                    <p>Using a chargeback as a substitute for our return process may delay resolution and may affect our ability to provide time-sensitive solutions (e.g., carrier claims).</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">11. Policy Updates</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>We may update this Refund Policy from time to time. The latest version will be posted on this page with the updated date. The policy applicable to your purchase is generally the one in effect at the time you placed your order.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">12. Contact</h2>
                <div class="mt-4 space-y-5 text-sm text-gray-700 leading-relaxed">
                    <p>If you need help with a return request, a damaged item report, or a refund status inquiry, contact us through the options below.</p>

                    <div class="space-y-4">
                        <a href="https://wa.me/6287766999564" target="_blank" rel="noopener noreferrer"
                           class="group flex items-start gap-4 p-5 bg-[#FAFAFA] hover:bg-gray-100 transition-all duration-300 border border-gray-200 hover:border-gray-300">
                            <div class="flex-shrink-0 w-11 h-11 bg-white rounded-full flex items-center justify-center text-green-600 group-hover:text-green-700 transition-colors shadow-sm">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-bold uppercase tracking-[0.15em] text-gray-900 mb-1">WhatsApp</p>
                                <p class="text-sm text-gray-600 font-mono">+62 877 6699 9564</p>
                                <p class="text-xs text-gray-500 mt-2 font-light">Click to chat with us instantly</p>
                            </div>
                            <div class="flex-shrink-0 text-gray-300 group-hover:text-gray-600 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </a>
                    </div>

                    <p class="text-xs text-gray-400 font-light leading-relaxed">
                        Our team typically responds within 24 hours during business hours. For urgent inquiries, please reach us via WhatsApp.
                    </p>
                </div>
            </section>
        </div>

        <div class="mt-12 pt-6 border-t border-gray-200 flex items-center justify-between gap-4">
            <a href="{{ route('policies.terms') }}" class="text-xs uppercase tracking-widest text-gray-500 border-b border-gray-300 hover:text-gray-900 hover:border-gray-900 transition-colors">Terms & Conditions</a>
            <a href="{{ route('policies.privacy') }}" class="text-xs uppercase tracking-widest text-gray-500 border-b border-gray-300 hover:text-gray-900 hover:border-gray-900 transition-colors">Privacy Policy</a>
            <a href="{{ route('policies.shipping') }}" class="text-xs uppercase tracking-widest text-gray-500 border-b border-gray-300 hover:text-gray-900 hover:border-gray-900 transition-colors">Shipping Policy</a>
        </div>
    </div>
</div>
@endsection
