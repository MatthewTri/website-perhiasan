@extends('layout.main')

@section('content')
<div class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Terms & Conditions</h1>
                <p class="mt-2 text-sm text-gray-500">Last updated: {{ now()->format('F j, Y') }}</p>
            </div>
            <a href="{{ route('checkout.index') }}" class="shrink-0 text-xs uppercase tracking-widest text-gray-500 border-b border-gray-300 hover:text-gray-900 hover:border-gray-900 transition-colors">Back to Checkout</a>
        </div>

        <div class="mt-8 p-5 sm:p-6 bg-[#FAFAFA] border border-gray-200">
            <p class="text-sm text-gray-700 leading-relaxed">
                These Terms & Conditions ("Terms") govern your access to and use of {{ config('app.name') }} (the "Site") and your purchase of products and services from us ("we", "us", "our").
                By using the Site and/or placing an order, you agree to these Terms. If you do not agree, please do not use the Site.
            </p>
            <p class="mt-3 text-xs text-gray-500 leading-relaxed">
                For convenience, we summarize key points below. This summary is not a substitute for the full Terms.
            </p>
            <ul class="mt-4 text-sm text-gray-700 list-disc pl-5 space-y-2">
                <li><span class="font-medium">Custom/bespoke production</span> typically takes <span class="font-medium">2–8 weeks</span> (estimate).</li>
                <li><span class="font-medium">No-refund policy</span> applies to <span class="font-medium">bespoke/custom-made</span> items, except where required by law.</li>
                <li><span class="font-medium">International shipping</span> may be subject to duties/taxes and customs delays.</li>
                <li><span class="font-medium">PayPal</span> payments are processed under PayPal’s terms; disputes/chargebacks can affect fulfillment.</li>
            </ul>
        </div>

        <div class="mt-10 space-y-10">
            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">1. Definitions</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p><span class="font-medium">"Ready-to-Ship Item"</span> means an item that is already produced and available for dispatch.</p>
                    <p><span class="font-medium">"Custom" / "Bespoke" / "Made-to-Order"</span> means an item produced specifically for you (including custom sizing, engraving, special materials, unique design requests, or any item marked as pre-order/made-to-order).</p>
                    <p><span class="font-medium">"Order"</span> means your purchase request submitted through the Site.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">2. Eligibility & Account</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>You represent that you are legally able to enter into a binding contract and that any information you provide is accurate and complete.</p>
                    <p>You are responsible for maintaining the confidentiality of your account and credentials and for all activity under your account.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">3. Product Information</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>We strive to display product descriptions, pricing, and photos accurately. However, due to device display differences and the nature of handcrafted products, colors, textures, and finishes may vary slightly.</p>
                    <p>Measurements, weights, and specifications are approximate unless explicitly stated otherwise.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">4. Orders & Acceptance</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>Submitting an Order constitutes an offer to purchase. We may accept or decline an Order at our discretion (for example, due to stock availability, pricing errors, verification issues, or suspected fraud).</p>
                    <p>We may contact you for additional information to verify your Order, payment, or shipping details.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">5. Pricing, Currency & Taxes</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>Prices are displayed in available currencies on the Site. For PayPal transactions, your charge may be processed in <span class="font-medium">USD</span> and your bank/PayPal may apply exchange rates and fees.</p>
                    <p>Depending on your location, taxes, customs duties, import VAT/GST, brokerage fees, and other charges may apply and are typically collected by the carrier or local authorities. Unless otherwise stated, these charges are <span class="font-medium">your responsibility</span>.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">6. Custom / Bespoke Items (Production Time: 2–8 Weeks)</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>Custom/bespoke items require production. Estimated production time is typically <span class="font-medium">2–8 weeks</span> from Order confirmation (or from final approval of design/specifications, whichever is later). This is an estimate and may vary due to materials availability, artisan workload, quality checks, and holidays.</p>
                    <p>For custom orders, you are responsible for confirming all details (such as size, spelling for engraving, materials, and shipping address). Once production begins, changes may be limited or may result in additional costs and/or timeline extensions.</p>
                    <p>If you require delivery by a specific date, contact us <span class="font-medium">before</span> placing your Order. We cannot guarantee delivery dates for custom items.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">7. Strict No-Refund Policy for Bespoke / Custom-Made Items</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>Due to the personalized nature of bespoke/custom-made items, all sales of such items are <span class="font-medium">final</span>. We do <span class="font-medium">not</span> offer returns, refunds, or exchanges for bespoke/custom items, <span class="font-medium">except where required by applicable law</span>.</p>
                    <p>This includes (without limitation): custom sizing, engraving, special material requests, made-to-order pieces, design modifications, and items produced or sourced specifically for you.</p>
                    <p>If your bespoke item arrives with a verified manufacturing defect or damage in transit, please contact us promptly so we can assess a repair, remake, or replacement where appropriate (subject to verification and availability).</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">8. Returns (Non-Bespoke Items)</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>For Ready-to-Ship items (non-bespoke), we may accept return requests within <span class="font-medium">14 days</span> of delivery, subject to eligibility criteria and verification.</p>
                    <p>To be eligible, items must be unworn, unused, in original condition, and returned with any original packaging and included materials. We may refuse returns that show signs of wear, alteration, damage, or tampering.</p>
                    <p>Shipping fees are generally non-refundable. You are responsible for return shipping costs unless the return is due to our error or a verified defect.</p>
                    <p>To request a return, contact us with your Order details and the reason for return before sending any items back.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">9. Shipping & Delivery (Including International Shipping)</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>Delivery timeframes are estimates and not guaranteed. Risk of loss typically passes to you upon delivery to the address provided (subject to applicable law and carrier terms).</p>
                    <p><span class="font-medium">International shipping:</span> Your shipment may be inspected by customs and may incur additional charges (duties/taxes/fees). Customs processing may cause delays beyond our control. You are responsible for complying with local import regulations and for providing any required information/documentation.</p>
                    <p>Please ensure your shipping address is accurate and complete. We are not responsible for delays or losses resulting from incorrect or incomplete addresses.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">10. Delivery Issues, Lost/Damaged Packages & Refusals</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>Please inspect your package upon delivery. If your order arrives damaged, incomplete, or incorrect, contact us promptly with photos of the packaging and items so we can investigate and support a claim with the carrier where applicable.</p>
                    <p>If a package is marked as delivered by the carrier but you cannot locate it, you agree to cooperate with any carrier investigation. Outcomes depend on carrier confirmation and evidence available.</p>
                    <p><span class="font-medium">Refused/undeliverable shipments:</span> If a shipment is refused, returned to sender, or undeliverable due to address issues, non-payment of duties/taxes, or failure to provide required import documentation, you may be responsible for additional shipping/handling fees and any losses incurred.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">11. Cancellations & Changes</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>We may be able to cancel or adjust an order <span class="font-medium">before</span> it enters processing/production. Once a custom/bespoke item enters production, cancellation and changes are typically not possible.</p>
                    <p>For Ready-to-Ship items, cancellation requests are handled on a best-effort basis prior to dispatch. If your order has shipped, cancellation is not possible and you may need to follow the return process (if eligible).</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">12. PayPal Payment Terms</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>If you choose PayPal, your payment is processed by PayPal and is subject to PayPal’s user agreement, privacy policy, and applicable policies. We do not control PayPal’s systems or decisions.</p>
                    <p><span class="font-medium">Authorization and capture:</span> We may authorize and then capture your payment. Orders may not be considered fully confirmed until payment is successfully captured and verified.</p>
                    <p><span class="font-medium">Currency:</span> PayPal may process charges in USD and apply currency conversion. Your bank/PayPal may charge fees. You are responsible for these fees.</p>
                    <p><span class="font-medium">Shipping & address verification:</span> For fraud prevention and PayPal seller protection, we may ship only to the address shown as confirmed/eligible within the PayPal transaction where applicable.</p>
                    <p><span class="font-medium">Disputes and chargebacks:</span> If you open a PayPal dispute/claim/chargeback, we may pause production (for custom items) and/or shipment until the issue is resolved. You agree to cooperate and provide information promptly if requested.</p>
                    <p><span class="font-medium">PayPal policies:</span> Your rights and obligations under PayPal (including any purchase protection) are governed by PayPal’s policies, which may change from time to time.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">13. Fraud Prevention & Order Security</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>We reserve the right to request additional verification, cancel orders, and/or refuse service in cases of suspected fraud, abuse, or policy violations.</p>
                    <p>We may void or cancel transactions that appear unauthorized or high-risk, including orders with mismatched billing/shipping information.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">14. Intellectual Property</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>All Site content, including text, images, designs, logos, and product presentations, are owned by us or our licensors and are protected by applicable intellectual property laws.</p>
                    <p>You may not reproduce, distribute, or exploit any part of the Site without our prior written consent.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">15. Limitation of Liability</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>To the maximum extent permitted by law, we are not liable for indirect, incidental, special, consequential, or punitive damages, or any loss of profits, revenue, data, or goodwill.</p>
                    <p>Where liability cannot be excluded, our liability is limited to the amount paid for the relevant Order (unless applicable law requires otherwise).</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">16. Changes to These Terms</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>We may update these Terms from time to time. The updated version will be posted on this page with a revised “Last updated” date. Continued use of the Site after changes constitutes acceptance of the updated Terms.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">17. Contact</h2>
                <div class="mt-4 space-y-5 text-sm text-gray-700 leading-relaxed">
                    <p>If you have questions about these Terms, returns, shipping, or your order, contact us through the options below.</p>

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

                    <p class="text-xs text-gray-500">Site: <span class="text-gray-700">{{ url('/') }}</span></p>
                </div>
            </section>
        </div>

        <div class="mt-12 pt-6 border-t border-gray-200 flex items-center justify-between gap-4">
            <a href="{{ route('policies.privacy') }}" class="text-xs uppercase tracking-widest text-gray-500 border-b border-gray-300 hover:text-gray-900 hover:border-gray-900 transition-colors">Privacy Policy</a>
            <a href="{{ route('home') }}" class="text-xs uppercase tracking-widest text-[#E8B49E] border-b border-[#E8B49E] hover:text-gray-900 hover:border-gray-900 transition-colors">Home</a>
        </div>
    </div>
</div>
@endsection
