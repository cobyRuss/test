@extends('layouts.app')

@section('title', 'Notifications | HappyStem')

@section('content')
    <section class="page-heading">
        <h2>Notifications</h2>
        <p>Updates on your orders, payments, and messages from HappyStem.</p>
    </section>

    <section style="padding: 20px 0 80px;">
        <div class="container" style="max-width: 760px;">
            <div style="background:#fff;border-radius:12px;padding:25px;box-shadow:0 8px 25px rgba(0,0,0,0.08);">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:15px;">
                    <h3 style="color:var(--secondary);">Notifications</h3>
                    <a href="{{ route('account') }}" style="color:var(--secondary);font-weight:600;text-decoration:none;">&larr; Back to Account</a>
                </div>

                @if ($notifications->isEmpty())
                    <p style="color:var(--dark);">You don't have any notifications yet.</p>
                @else
                    @foreach ($notifications as $notification)
                        <a href="{{ $notification->link ?: route('account') }}" style="display:block;text-decoration:none;color:inherit;padding:14px 0;border-bottom:1px solid #f0f0f0;">
                            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;">
                                <div style="flex:1;">
                                    <strong style="color:var(--dark);">{{ $notification->title }}</strong>
                                    @if ($notification->body)
                                        <p style="font-size:0.88rem;color:var(--secondary);margin-top:3px;">{{ $notification->body }}</p>
                                    @endif
                                    <p style="font-size:0.78rem;color:#8a8a8a;margin-top:3px;">
                                        {{ $notification->created_at ? $notification->created_at->format('F j, Y g:i A') : '' }}
                                    </p>
                                </div>
                                @unless ($notification->is_read)
                                    <span style="width:9px;height:9px;border-radius:50%;background:var(--accent);flex-shrink:0;margin-top:6px;"></span>
                                @endunless
                            </div>
                        </a>
                    @endforeach
                @endif

                @if ($notifications->hasPages())
                    <div style="margin-top:20px;">
                        {{ $notifications->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
