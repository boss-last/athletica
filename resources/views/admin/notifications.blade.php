@extends('layouts.app')

@section('title', 'Notifications groupées - Admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">🔔 Envoyer une notification groupée</h1>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl mb-4">{{ session('success') }}</div>
    @endif

    <div class="glass-card rounded-3xl p-8 shadow-xl">
        <form action="/admin/notifications/send" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Titre *</label>
                <input type="text" name="title" required class="w-full px-4 py-3 border rounded-2xl">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Message *</label>
                <textarea name="message" rows="4" required class="w-full px-4 py-3 border rounded-2xl"></textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Destinataires *</label>
                <select name="target" required class="w-full px-4 py-3 border rounded-2xl">
                    <option value="all">Tous les utilisateurs</option>
                    <option value="active">Utilisateurs actifs</option>
                    <option value="premium">Utilisateurs Premium</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-2xl font-bold hover:bg-green-700">
                <i class="fas fa-paper-plane mr-2"></i>Envoyer
            </button>
        </form>
    </div>
</div>
@endsection
