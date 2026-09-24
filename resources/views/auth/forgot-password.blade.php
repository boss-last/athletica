@extends('layouts.auth')
@section('title', 'Mot de passe oublié - Athletica')

@section('content')
<div class="glass-card rounded-3xl p-8 shadow-xl">
    <h2 class="text-2xl font-bold text-center text-slate-900 mb-6">🔒 Mot de passe oublié</h2>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6">
        {{ session('error') }}
    </div>
    @endif

    <form action="/forgot-password" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500"
                   placeholder="votre@email.com">
        </div>
        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-2xl font-semibold hover:bg-green-700 transition">
            <i class="fas fa-paper-plane mr-2"></i>Envoyer le lien
        </button>
    </form>

    <p class="text-center mt-6 text-slate-500">
        <a href="/login" class="text-green-600 font-semibold hover:underline">← Retour à la connexion</a>
    </p>
</div>
@endsection
