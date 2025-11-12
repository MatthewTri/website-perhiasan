<div class="content">
    <div class="field">
        <div class="field-label">👤 Customer Name: {{ $customRequest->user_name }}</div>
    </div>

    <div class="field">
        <div class="field-label">📧 Email Address: {{ $customRequest->user_email }}</div>
    </div>
    <div class="field">
        <div class="field-label">🌍 Country: {{ $customRequest->country }}</div>
    </div>

    <div class="field">
        <div class="field-label">👤 Account Status:</div>
        <div class="field-value">
            @if($customRequest->user_id)
                ✅ Registered User (ID: {{ $customRequest->user_id }})
            @else
                🔄 Guest User
            @endif
        </div>
    </div>

    <div class="field">
        <div class="field-label">📅 Submitted: {{ $customRequest->created_at->format('F j, Y \a\t g:i A') }}</div>
    </div>

    @if($customRequest->notes)
    <div class="field">
        <div class="field-label">💎 Jewelry Description:</div>
        <div class="notes">{{ $customRequest->notes }}</div>
    </div>
    @endif

    <div style="text-align: center; margin-top: 30px;">
        <a href="{{ url('/admin/consultation/' . $customRequest->id) }}" 
           class="button">
           View Full Details & Respond
        </a>
    </div>
</div>

<div class="footer">
    <p>This is an automated notification from your jewelry website.</p>
    <p>Request ID: #{{ $customRequest->id }}</p>
</div>