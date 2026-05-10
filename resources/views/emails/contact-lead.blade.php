<h2>New Contact Form Submission</h2>
<p><strong>Name:</strong> {{ $lead->name }}</p>
<p><strong>Email:</strong> {{ $lead->email }}</p>
<p><strong>Phone:</strong> {{ $lead->phone ?: '-' }}</p>
<p><strong>Subject:</strong> {{ $lead->subject ?: '-' }}</p>
<p><strong>Source:</strong> {{ $lead->source }}</p>
<p><strong>IP:</strong> {{ $lead->ip_address ?: '-' }}</p>
<hr>
<p><strong>Message:</strong></p>
<p>{{ $lead->message }}</p>
