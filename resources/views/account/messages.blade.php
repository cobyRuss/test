@extends('layouts.app')

@section('title', 'My Messages | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>My Messages</h2>
        <p>Your conversations with HappyStem and replies from our team.</p>
    </section>

    <section style="padding: 20px 0 80px;">
        <div class="container" style="max-width: 760px;">
            <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                    <h3 style="color:var(--secondary);">Messages</h3>
                    <a href="{{ route('account') }}" style="color:var(--secondary);font-weight:600;text-decoration:none;">&larr; Back to Account</a>
                </div>

                @if ($messages->isEmpty())
                    <p style="color:var(--dark);">You haven't sent any messages yet.</p>
                    <a href="{{ route('home') }}#contact" class="btn" style="margin-top:15px;">Contact Us</a>
                @else
                    @foreach ($messages as $message)
                        <div style="padding:14px 0;border-bottom:1px solid #f0f0f0;">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <strong style="color:var(--dark);">You &rarr; HappyStem</strong>
                                <span style="font-size:0.78rem;color:#8a8a8a;">{{ $message->created_at }}</span>
                            </div>
                            <p style="font-size:0.9rem;color:var(--secondary);margin-top:5px;">{{ $message->message }}</p>

                            @if ($message->admin_reply)
                                <div style="margin-top:10px;padding:12px;background:#f9f3f4;border-radius:10px;border-left:3px solid var(--primary);">
                                    <div style="display:flex;justify-content:space-between;align-items:center;">
                                        <strong style="color:var(--accent);">HappyStem</strong>
                                        @if ($message->replied_at)
                                            <span style="font-size:0.78rem;color:#8a8a8a;">{{ \Carbon\Carbon::parse($message->replied_at)->format('F j, Y g:i A') }}</span>
                                        @endif
                                    </div>
                                    <p style="font-size:0.9rem;color:var(--dark);margin-top:5px;">{!! $message->renderedReply() !!}</p>
                                </div>
                            @else
                                <p style="font-size:0.82rem;color:#8a8a8a;margin-top:8px;font-style:italic;">Awaiting reply from our team...</p>
                            @endif
                        </div>
                    @endforeach
                @endif

                @if ($messages->hasPages())
                    <div style="margin-top:20px;">
                        {{ $messages->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
