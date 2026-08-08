@extends('layouts.app')

@section('title', 'Our Services | HappyStem')

@section('content')
    <section class="page-heading" style="background: linear-gradient(rgba(232, 180, 188, 0.75), rgba(138, 155, 110, 0.75)), url('{{ asset('images/aa.jpg') }}') center/cover; color:#fff; padding:70px 20px;">
        <div style="display:inline-block;background:rgba(0,0,0,0.35);padding:30px 40px;border-radius:14px;box-shadow:0 4px 18px rgba(0,0,0,0.25);">
            <h2 style="color:#fff;text-shadow:1px 2px 5px rgba(0,0,0,0.6);">Our Services</h2>
            <p style="text-shadow:1px 1px 4px rgba(0,0,0,0.6);">Floral arrangements and styling for every occasion across Abra.</p>
        </div>
    </section>

    <section class="services" style="background:var(--light);padding:60px 0;">
        <div class="container">
            <p class="services-intro">
                Each service is tailored to your needs. Click "View Photos" to see our past work, or reach out
                through the contact form to get a quote.
            </p>

            <div class="services-grid">
                @foreach ($services as $key => $service)
                    <div class="service-card">
                        <div class="service-header">
                            <div class="service-icon"><i class="fas {{ $service['icon'] }}"></i></div>
                            <h3>{{ $service['title'] }}</h3>
                        </div>
                        <div class="service-content">
                            <p style="margin-bottom:15px;font-size:0.92rem;">{{ $service['description'] }}</p>
                            <ul class="service-list">
                                @foreach ($service['items'] as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            </ul>
                            <button class="photos-btn" data-service="{{ $key }}">
                                <i class="fas fa-images"></i> View Photos
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section style="padding:70px 0;background:#fff;text-align:center;">
        <div class="container" style="max-width:640px;">
            <h2 class="section-title">Ready to plan your flowers?</h2>
            <p style="margin-bottom:25px;color:var(--dark);">
                Tell us about your event and we'll craft the perfect floral design for you.
            </p>
            <button class="btn" id="openContactPopup"><i class="fas fa-envelope"></i> Send an Inquiry</button>
        </div>
    </section>
@endsection
