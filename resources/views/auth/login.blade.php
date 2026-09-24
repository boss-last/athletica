@extends('layouts.auth')

@section('title', 'Connexion - Athletica')

@section('content')
<div class="glass-card rounded-3xl p-8 shadow-xl">
    <h2 class="text-2xl font-bold text-center text-slate-900 mb-6">Connexion</h2>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6">
        @foreach($errors->all() as $error)
            <p><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form action="/login" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                   placeholder="votre@email.com" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Nom d'utilisateur</label>
            <input type="text" name="username" value="{{ old('username') }}"
                   class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500 focus:border-green-500"
                   placeholder="johndoe" required>
        </div>
        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-2xl font-semibold hover:bg-green-700 transition">
            <i class="fas fa-sign-in-alt mr-2"></i>Se connecter
        </button>
    </form>

    <div class="text-center mt-6 space-y-3">
        <p class="text-slate-500">
            Pas encore de compte ?
            <a href="/register" class="text-green-600 font-semibold hover:underline">S'inscrire</a>
        </p>
        <p>
            <a href="/forgot-password" class="text-slate-400 text-sm hover:text-green-600 transition">
                <i class="fas fa-key mr-1"></i>Mot de passe oublié ?
            </a>
        </p>
    </div>
</div>
@endsection
