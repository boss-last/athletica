@extends('layouts.app')

@section('title', 'Créer utilisateur - Admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">➕ Créer un utilisateur</h1>

    <div class="glass-card rounded-3xl p-8 shadow-xl">
        <form action="/admin/users" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Email *</label>
                <input type="email" name="email" required class="w-full px-4 py-3 border rounded-2xl focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Nom d'utilisateur *</label>
                <input type="text" name="username" required class="w-full px-4 py-3 border rounded-2xl focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Nom complet *</label>
                <input type="text" name="full_name" required class="w-full px-4 py-3 border rounded-2xl focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Rôle *</label>
                <select name="role" required class="w-full px-4 py-3 border rounded-2xl focus:ring-2 focus:ring-green-500">
                    <option value="user">Utilisateur</option>
                    <option value="coach">Coach</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <label class="flex items-center">
                <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                <span>Compte actif</span>
            </label>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-green-600 text-white py-3 rounded-2xl font-bold hover:bg-green-700">
                    <i class="fas fa-save mr-2"></i>Créer
                </button>
                <a href="/admin/users" class="px-6 py-3 border border-slate-200 rounded-2xl text-slate-600 hover:bg-slate-50">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
