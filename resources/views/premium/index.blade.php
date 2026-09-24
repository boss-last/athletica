@extends('layouts.app')

@section('title', 'Premium - Athletica')

@section('content')
<div class="space-y-6">
    <div class="text-center">
        <h1 class="text-3xl font-bold text-slate-900">💎 Passe à Athletica Premium</h1>
        <p class="text-slate-500 mt-2">Débloque toutes les fonctionnalités et booste tes performances !</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($plans as $key => $plan)
        <div class="glass-card rounded-3xl p-6 text-center {{ $currentPlan === $key ? 'border-2 border-green-500' : '' }}">
            @if($currentPlan === $key)
            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Actuel</span>
            @endif
            <h3 class="text-xl font-bold mt-3">{{ $plan['name'] }}</h3>
            <p class="text-4xl font-bold my-4">
                @if($plan['price'] == 0)
                    Gratuit
                @else
                    {{ $plan['price'] }}€<span class="text-sm text-slate-400">/mois</span>
                @endif
            </p>
            <ul class="text-sm text-left space-y-2 mb-6">
                @foreach($plan['features'] as $feature)
                <li>✅ {{ $feature }}</li>
                @endforeach
            </ul>
            @if($currentPlan !== $key && $key !== 'free')
            <a href="/premium/subscribe/{{ $key }}"
               class="block w-full bg-green-600 text-white py-3 rounded-2xl font-bold hover:bg-green-700 transition">
                Choisir {{ $plan['name'] }}
            </a>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endsection
