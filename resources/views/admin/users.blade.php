@extends('layouts.app')

@section('title', 'Gestion Utilisateurs - Admin')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-slate-900">👥 Gestion des Utilisateurs</h1>
        <div class="flex gap-3">
            <a href="/admin/users/create" class="bg-green-600 text-white px-5 py-2.5 rounded-2xl font-semibold hover:bg-green-700">
                <i class="fas fa-plus mr-2"></i>Nouvel utilisateur
            </a>
            <a href="/admin/dashboard" class="text-green-600 hover:underline px-5 py-2.5">
                <i class="fas fa-arrow-left mr-1"></i> Retour Admin
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-2xl">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-2xl">
        {{ session('error') }}
    </div>
    @endif

    <div class="glass-card rounded-3xl shadow-xl overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b text-left text-slate-500">
                    <th class="p-4">Utilisateur</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Rôle</th>
                    <th class="p-4">Statut</th>
                    <th class="p-4">Date</th>
                    <th class="p-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-b hover:bg-slate-50">
                    <td class="p-4">
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->full_name) }}&size=32&background=22c55e&color=fff" class="w-8 h-8 rounded-full">
                            <span class="font-medium">{{ $user->full_name }}</span>
                        </div>
                    </td>
                    <td class="p-4 text-sm text-slate-500">{{ $user->email }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded-full text-xs {{ $user->role === 'admin' ? 'bg-yellow-100 text-yellow-700' : ($user->role === 'coach' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                            {{ $user->role }}
                        </span>
                    </td>
                    <td class="p-4">
                        <span class="px-2 py-1 rounded-full text-xs {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $user->is_active ? 'Actif' : 'Inactif' }}
                        </span>
                    </td>
                    <td class="p-4 text-sm text-slate-400">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="p-4">
                        <div class="flex gap-3">
                            <a href="/admin/users/{{ $user->id }}/edit" class="text-blue-600 hover:text-blue-800 text-sm">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="/admin/users/{{ $user->id }}/toggle" method="POST" class="inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-sm {{ $user->is_active ? 'text-orange-600' : 'text-green-600' }}">
                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                </button>
                            </form>
                            <form action="/admin/users/{{ $user->id }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm" onclick="return confirm('Supprimer cet utilisateur ?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
