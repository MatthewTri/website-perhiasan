@extends('layout.main')

@section('content')
<div class="bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-14">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-semibold text-gray-900">Privacy Policy</h1>
                <p class="mt-2 text-sm text-gray-500">Last updated: {{ now()->format('F j, Y') }}</p>
            </div>
            <a href="{{ route('checkout.index') }}" class="shrink-0 text-xs uppercase tracking-widest text-gray-500 border-b border-gray-300 hover:text-gray-900 hover:border-gray-900 transition-colors">Back to Checkout</a>
        </div>

        <div class="mt-8 p-5 sm:p-6 bg-[#FAFAFA] border border-gray-200">
            <p class="text-sm text-gray-700 leading-relaxed">
                This Privacy Policy explains how {{ config('app.name') }} ("we", "us", "our") collects, uses, discloses, and protects your information when you visit or make a purchase through our website (the "Site").
                By using the Site, you consent to the practices described in this Privacy Policy.
            </p>
            <p class="mt-3 text-xs text-gray-500 leading-relaxed">
                If you are using PayPal to pay, PayPal processes your payment data under its own privacy policy. We generally do not store full payment card details on our servers.
            </p>
        </div>

        <div class="mt-10 space-y-10">
            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">1. Information We Collect</h2>
                <div class="mt-4 space-y-4 text-sm text-gray-700 leading-relaxed">
                    <div>
                        <p class="font-medium text-gray-900">Information you provide</p>
                        <ul class="mt-2 list-disc pl-5 space-y-2">
                            <li>Contact details (e.g., name, email address, phone number).</li>
                            <li>Shipping information (e.g., address, city, province/state, postal code, country).</li>
                            <li>Order information (e.g., items purchased, quantities, custom notes, special instructions).</li>
                            <li>Customer support communications (e.g., messages, emails, attachments you send us).</li>
                        </ul>
                    </div>

                    <div>
                        <p class="font-medium text-gray-900">Information collected automatically</p>
                        <ul class="mt-2 list-disc pl-5 space-y-2">
                            <li>Device and browser information (e.g., IP address, browser type, operating system).</li>
                            <li>Usage data (e.g., pages viewed, time spent, links clicked).</li>
                            <li>Cookie and similar technology data (see “Cookies & Tracking”).</li>
                        </ul>
                    </div>

                    <div>
                        <p class="font-medium text-gray-900">Payment information</p>
                        <p class="mt-2">
                            When you pay via PayPal, PayPal collects and processes your payment details (such as funding source and transaction identifiers). We may receive limited information such as your PayPal transaction ID, payment status, and payer details that PayPal shares with merchants.
                        </p>
                    </div>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">2. How We Use Your Information</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>We use your information to:</p>
                    <ul class="mt-2 list-disc pl-5 space-y-2">
                        <li>Process orders, payments, shipping, and delivery.</li>
                        <li>Produce and fulfill custom/bespoke orders, including production planning (typical production time estimates may be 2–8 weeks depending on the order).</li>
                        <li>Communicate with you about your order status, updates, and customer service inquiries.</li>
                        <li>Detect and prevent fraud, unauthorized transactions, abuse, and security incidents.</li>
                        <li>Improve the Site, troubleshoot issues, and analyze performance.</li>
                        <li>Comply with legal obligations and enforce our Terms & Conditions.</li>
                    </ul>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">3. Legal Bases (Where Applicable)</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>Depending on your location, we process personal data under one or more legal bases, such as: performance of a contract (fulfilling your order), legitimate interests (fraud prevention, service improvement), consent (marketing communications where required), and compliance with legal obligations.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">4. Cookies & Tracking Technologies</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>We and our service providers may use cookies, pixels, and similar technologies to operate the Site, remember preferences, understand usage, and improve performance.</p>
                    <p>You can control cookies through your browser settings. Disabling cookies may affect Site functionality (such as login sessions or checkout experiences).</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">5. How We Share Your Information</h2>
                <div class="mt-4 space-y-4 text-sm text-gray-700 leading-relaxed">
                    <p>We may share information with:</p>
                    <ul class="mt-2 list-disc pl-5 space-y-2">
                        <li><span class="font-medium">Payment processors</span> (e.g., PayPal) to process payments, manage fraud, and handle disputes.</li>
                        <li><span class="font-medium">Shipping and logistics providers</span> to deliver your purchases, including international shipping.</li>
                        <li><span class="font-medium">Service providers</span> who help operate the Site (hosting, analytics, customer support tools), under contractual obligations to protect your data.</li>
                        <li><span class="font-medium">Legal and compliance</span> when required by law or to protect rights, safety, and security.</li>
                    </ul>
                    <p class="text-xs text-gray-500">
                        We do not sell your personal information in the ordinary commercial sense.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">6. International Data Transfers</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>Depending on your location and where our service providers operate, your information may be transferred to and processed in countries other than your own. Where required, we use appropriate safeguards to protect such transfers.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">7. Data Retention</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>We retain personal information for as long as needed to fulfill the purposes described in this policy, including to comply with legal, accounting, and tax obligations, resolve disputes (including PayPal disputes), and enforce agreements.</p>
                    <p>When information is no longer required, we take reasonable steps to delete, anonymize, or securely store it.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">8. Security</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>We implement reasonable administrative, technical, and physical safeguards designed to protect your information. However, no method of transmission or storage is 100% secure, and we cannot guarantee absolute security.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">9. Your Rights & Choices</h2>
                <div class="mt-4 space-y-4 text-sm text-gray-700 leading-relaxed">
                    <p>Depending on your location, you may have rights regarding your personal data, such as:</p>
                    <ul class="mt-2 list-disc pl-5 space-y-2">
                        <li>Access to the personal data we hold about you.</li>
                        <li>Correction of inaccurate or incomplete information.</li>
                        <li>Deletion of your information (subject to exceptions, e.g., legal obligations).</li>
                        <li>Objection or restriction to certain processing.</li>
                        <li>Data portability (where applicable).</li>
                        <li>Opt-out of marketing communications by using unsubscribe links (if available) or contacting us.</li>
                    </ul>
                    <p>To exercise rights, contact us using the contact methods available on the Site. We may need to verify your identity before fulfilling your request.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">10. Children’s Privacy</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>The Site is not intended for children, and we do not knowingly collect personal information from children. If you believe a child has provided personal information, please contact us so we can take appropriate steps.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">11. Changes to This Privacy Policy</h2>
                <div class="mt-4 space-y-3 text-sm text-gray-700 leading-relaxed">
                    <p>We may update this Privacy Policy from time to time. The updated version will be posted on this page with a revised “Last updated” date.</p>
                </div>
            </section>

            <section>
                <h2 class="text-sm font-bold uppercase tracking-[0.15em] text-gray-900">12. Contact</h2>
                <div class="mt-4 space-y-5 text-sm text-gray-700 leading-relaxed">
                    <p>If you have questions about this Privacy Policy or want to request access, correction, or deletion (where applicable), contact us through the options below.</p>

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
            <a href="{{ route('policies.terms') }}" class="text-xs uppercase tracking-widest text-gray-500 border-b border-gray-300 hover:text-gray-900 hover:border-gray-900 transition-colors">Terms & Conditions</a>
            <a href="{{ route('home') }}" class="text-xs uppercase tracking-widest text-[#E8B49E] border-b border-[#E8B49E] hover:text-gray-900 hover:border-gray-900 transition-colors">Home</a>
        </div>
    </div>
</div>
@endsection
