@extends('layouts.app')

@section('title', 'Créer badge - Admin')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">🏅 Nouveau Badge</h1>

    <div class="glass-card rounded-3xl p-8 shadow-xl">
        <form action="/admin/badges" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-2">Nom *</label>
                <input type="text" name="name" required class="w-full px-4 py-3 border rounded-2xl focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium mb-2">Description</label>
                <textarea name="description" rows="2" class="w-full px-4 py-3 border rounded-2xl"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-2">Catégorie</label>
                    <input type="text" name="category" class="w-full px-4 py-3 border rounded-2xl">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-2">Points *</label>
                    <input type="number" name="points" value="10" required class="w-full px-4 py-3 border rounded-2xl">
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-green-600 text-white py-3 rounded-2xl font-bold hover:bg-green-700">
                    <i class="fas fa-save mr-2"></i>Créer
                </button>
                <a href="/admin/badges" class="px-6 py-3 border rounded-2xl text-slate-600">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection
