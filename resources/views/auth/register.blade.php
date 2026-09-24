@extends('layouts.auth')

@section('title', 'Inscription - Athletica')

@section('content')
<div class="glass-card rounded-3xl p-8 shadow-xl">
    <h2 class="text-2xl font-bold text-center text-slate-900 mb-6">Créer un compte</h2>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-2xl mb-6">
        @foreach($errors->all() as $error)
            <p><i class="fas fa-exclamation-circle mr-2"></i>{{ $error }}</p>
        @endforeach
    </div>
    @endif

    <form action="/register" method="POST" class="space-y-5">
        @csrf
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Nom complet</label>
            <input type="text" name="full_name" value="{{ old('full_name') }}"
                   class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500"
                   placeholder="John Doe" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Nom d'utilisateur</label>
            <input type="text" name="username" value="{{ old('username') }}"
                   class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500"
                   placeholder="johndoe" required>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}"
                   class="w-full px-4 py-3 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-green-500"
                   placeholder="votre@email.com" required>
        </div>
        <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-2xl font-semibold hover:bg-green-700 transition">
            <i class="fas fa-user-plus mr-2"></i>S'inscrire
        </button>
    </form>
    <p class="text-center mt-6 text-slate-500">
        Déjà un compte ?
        <a href="/login" class="text-green-600 font-semibold hover:underline">Se connecter</a>
    </p>
</div>
@endsection
