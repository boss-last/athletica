@extends('layouts.auth')
@section('title', 'Nouveau mot de passe - Athletica')

@section('content')
<div class="glass-card rounded-3xl p-8 shadow-xl">
    <h2 class="text-2xl font-bold text-center text-slate-900 mb-6">🔑 Nouveau mot de passe</h2>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6">
        @foreach($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form action="/reset-password" method="POST" class="space-y-5">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Nouveau mot de passe</label>
            <input type="password" name="password" required
                   class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500"
                   placeholder="Min. 8 caractères">
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" required
                   class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500"
                   placeholder="Répétez le mot de passe">
        </div>
        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-2xl font-semibold hover:bg-green-700 transition">
            <i class="fas fa-save mr-2"></i>Réinitialiser
        </button>
    </form>
</div>
@endsection
