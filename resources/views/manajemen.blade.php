<x-sidebar>
    <div class="p-8 max-w-7xl mx-auto bg-slate-50 min-h-screen" x-data="{ 
        openModal: false, 
        selectedUser: { id: '', name: '', email: '', role: '', status: '' } 
    }">

        {{-- Header Halaman --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl lg:text-3xl font-extrabold text-slate-900 tracking-tight">
                    Manajemen Akun
                </h1>
                <p class="text-sm text-slate-600 mt-1">
                    Kelola akses pengguna, tingkat role, dan status persetujuan login sistem secara terpusat.
                </p>
            </div>
            
            <div class="flex items-center gap-3">
                <span class="text-xs font-medium bg-white text-slate-700 px-3 py-1.5 rounded-lg border border-slate-300 shadow-sm">
                    <i class="fa-regular fa-clock mr-1"></i> Update Terakhir: Baru saja
                </span>
            </div>
        </div>

        {{-- Kartu Statistik (Kontras Diperbaiki) --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">
            {{-- Total Pengguna --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Pengguna</p>
                        <h2 class="text-3xl font-extrabold text-slate-900 mt-2">{{ $totalUser }}</h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                </div>
            </div>

            {{-- Pending --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all border-l-4 border-l-amber-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-amber-700">Pending</p>
                        <h2 class="text-3xl font-extrabold text-amber-600 mt-2">{{ $pending }}</h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            {{-- Approved --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all border-l-4 border-l-emerald-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-emerald-700">Approved</p>
                        <h2 class="text-3xl font-extrabold text-emerald-600 mt-2">{{ $approved }}</h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>

            {{-- Rejected --}}
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-md transition-all border-l-4 border-l-rose-600">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-rose-700">Rejected</p>
                        <h2 class="text-3xl font-extrabold text-rose-600 mt-2">{{ $rejected }}</h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center text-rose-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Tabel Data --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white text-xs uppercase tracking-wider font-bold">
                            <th class="p-4 pl-6">Nama Pengguna</th>
                            <th class="p-4">Email</th>
                            <th class="p-4">Role</th>
                            <th class="p-4">Status</th>
                            <th class="p-4 text-center pr-6">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 text-sm">
                        @forelse($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            {{-- Nama --}}
                            <td class="p-4 pl-6">
                                <div class="font-bold text-slate-900">{{ $user->name }}</div>
                                <div class="text-xs text-slate-500 font-mono mt-0.5">ID: #{{ $user->id }}</div>
                            </td>

                            {{-- Email --}}
                            <td class="p-4 text-slate-700 font-medium">
                                {{ $user->email }}
                            </td>

                            {{-- Role --}}
                            <td class="p-4">
                                @if($user->role == "admin")
                                    <span class="inline-flex items-center gap-1.5 bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-xs font-bold border border-indigo-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-600"></span> Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-slate-200 text-slate-800 px-3 py-1 rounded-full text-xs font-bold border border-slate-300">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-600"></span> User
                                    </span>
                                @endif
                            </td>

                            {{-- Status --}}
                            <td class="p-4">
                                @if($user->status == "approved")
                                    <span class="inline-flex items-center gap-1.5 bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-xs font-bold border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Approved
                                    </span>
                                @elseif($user->status == "pending")
                                    <span class="inline-flex items-center gap-1.5 bg-amber-100 text-amber-800 px-3 py-1 rounded-full text-xs font-bold border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600 animate-pulse"></span> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 bg-rose-100 text-rose-800 px-3 py-1 rounded-full text-xs font-bold border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-600"></span> Rejected
                                    </span>
                                @endif
                            </td>

                            {{-- Tombol Kelola --}}
                            <td class="p-4 text-center pr-6">
                                <button 
                                    @click="openModal = true; selectedUser = { id: '{{ $user->id }}', name: '{{ $user->name }}', email: '{{ $user->email }}', role: '{{ $user->role }}', status: '{{ $user->status }}' }"
                                    class="inline-flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-xs font-bold shadow-md hover:shadow-lg border border-indigo-500 transition-all transform active:scale-95">
                                    <svg class="w-3.5 h-3.5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Kelola
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-slate-500 font-medium">
                                Belum ada data pengguna yang tersedia.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(method_exists($users, 'links'))
                <div class="p-4 border-t border-slate-200 bg-white">
                    {{ $users->links() }}
                </div>
            @endif
        </div>

        {{-- MODAL KELOLA USER --}}
        <div 
            x-show="openModal" 
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
            x-cloak
            x-transition.opacity>
            
            <div 
                @click.away="openModal = false"
                class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-6 overflow-hidden border border-slate-200"
                x-transition>
                
                <div class="flex justify-between items-center pb-4 border-b border-slate-200">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Edit Akun Pengguna</h3>
                        <p class="text-xs text-slate-600 mt-0.5" x-text="selectedUser.name + ' (' + selectedUser.email + ')'"></p>
                    </div>
                    <button @click="openModal = false" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-700 hover:bg-slate-200 font-bold transition">
                        &times;
                    </button>
                </div>

                <form :action="'/admin/users/' + selectedUser.id" method="POST" class="space-y-4 pt-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Role Pengguna</label>
                        <select name="role" x-model="selectedUser.role" class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-slate-800 text-slate-900">
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">Status Persetujuan</label>
                        <select name="status" x-model="selectedUser.status" class="w-full bg-white border border-slate-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-slate-800 text-slate-900">
                            <option value="approved">Approved</option>
                            <option value="pending">Pending</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200">
                        <button type="button" @click="openModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-slate-800 hover:bg-slate-900 shadow-sm transition">
                            Simpan
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</x-sidebar>