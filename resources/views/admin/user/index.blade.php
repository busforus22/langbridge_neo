@extends('admin.layout')

@section('admin-content')

<div style="overflow-x: auto; overflow-y: visible;" class="w-auto">

    {{--  search bar --}}
    <div style="align-items: center; margin-bottom: 20px; padding-right: 2px;">
        <form action="{{ route('admin.users.index') }}" method="GET" style="position: relative; display: inline-block;">
            <span style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); color: #D1D5DB; pointer-events: none;">
                <i class="fas fa-search" style="font-size: 14px;"></i>
            </span>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search User" 
                   style="width: 260px; padding: 8px 40px 8px 20px; border: 1px solid #E5E7EB; border-radius: 9999px; background-color: #ffffff; outline: none; font-size: 14px; box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
        </form>    
    </div>

    {{-- user table --}}
    <div class="bg-white rounded-lg shadow-sm w-auto" >
        <table class="table-auto w-full border-collapse border border-gray-200">
            <thead class="bg-gray-50 report-title">
                <tr>
                    <th class="px-4 py-3 border border-gray-200 text-sm font-semibold text-gray-600">ID</th>
                    <th class="px-4 py-3 border border-gray-200 text-sm font-semibold text-gray-600">Full Name</th>
                    <th class="px-4 py-3 border border-gray-200 text-sm font-semibold text-gray-600">Handle</th>
                    <th class="px-4 py-3 border border-gray-200 text-sm font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-3 border border-gray-200 text-sm font-semibold text-gray-600">Role</th>
                    <th class="px-4 py-3 border border-gray-200 text-sm font-semibold text-gray-600">Age</th>
                    <th class="px-4 py-3 border border-gray-200 text-sm font-semibold text-gray-600">Country</th>
                    <th class="px-4 py-3 border border-gray-200 text-sm font-semibold text-gray-600">Region</th>
                    <th class="px-4 py-3 border border-gray-200 text-sm font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 border border-gray-200 text-sm font-semibold text-gray-600"><i class="fa-solid fa-ellipsis"></i></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="hover:bg-gray-50/50">
                    <td class="px-4 py-2 border border-gray-200 text-sm">{{ $user->id }}</td>
                    <td class="px-4 py-2 border border-gray-200 text-sm">{{ $user->name }}
                        @if($user->is_admin)
                         <i class="fas fa-shield-alt"></i>
                        @endif
                    </td>
                    <td class="px-4 py-2 border border-gray-200 text-sm text-gray-500">
                        <a href="{{ route('profile.show', ['user_id' => encrypt($user->id)]) }}" class="text-cyan-600 hover:underline">
                            {{ $user->profile->handle }}
                        </a>
                    </td>
                    <td class="px-4 py-2 border border-gray-200 text-sm text-gray-500">{{ $user->email }}</td>
                    <td class="px-4 py-2 border border-gray-200 text-sm text-gray-500">{{ $user->target_language }}</td>
                    <td class="px-4 py-2 border border-gray-200 text-sm text-gray-500">{{ $user->age }}</td>
                    <td class="px-4 py-2 border border-gray-200 text-sm text-gray-500">{{ $user->country }}</td>
                    <td class="px-4 py-2 border border-gray-200 text-sm text-gray-500">{{ $user->region }}</td>
                    
                    {{-- status --}}
                    <td class="px-4 py-2 border border-gray-200 text-center">
                        @if($user->trashed())
                            <span class="status-badge status-deleted">Deleted</span>
                        @elseif($user->suspended)
                            <span class="status-badge status-suspended">Suspended</span>
                        @else
                            <span class="status-badge status-active">Active</span>
                        @endif
                    </td>
                    
                    {{-- ・・・ --}}
                    <td class="px-4 py-2 border border-r-0 text-center relative">
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            
                            <button @click.stop="open = !open" type="button" 
                                    class="btn-ellipsis">
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                    
                            {{-- dropdown menu --}}
                            <div x-show="open" @click.away="open = false" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="dropdown-menu-custom absolute right-0 mt-2 w-44 bg-white z-50 py-2 shadow-xl"
                                 >
                                
                                @if($user->trashed())
                                    <form action="{{ route('admin.users.restore', $user->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-green-600 hover:bg-green-50">
                                            <i class="fas fa-undo-alt mr-2"></i>Restore
                                        </button>
                                    </form>
                                @else
                                    @if($user->suspended)
                                        <form action="{{ route('admin.users.unsuspend', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-blue-600 hover:bg-blue-50">
                                                <i class="fas fa-play mr-2"></i>Activate
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.suspend', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-orange-500 hover:bg-orange-50">
                                                <i class="fas fa-pause mr-2"></i>Suspend
                                            </button>
                                        </form>
                                    @endif
                    
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-red-400 hover:bg-red-50">
                                            <i class="fas fa-trash-alt mr-2"></i>Delete
                                        </button>
                                    </form>
                                @endif
                                @if(!$user->is_admin)
                                 <form action="{{ route('admin.users.makeAdmin', $user->id) }}" method="POST">
                                 @csrf
                                <button type="submit" class="dropdown-item text-purple-600 hover:bg-purple-50">
                                 <i class="fas fa-user-shield mr-2"></i>Make Admin
                                 </button>
                                 </form>
                                @else
                                <form action="{{ route('admin.users.removeAdmin', $user->id) }}" method="POST">
                                  @csrf
                                 <button type="submit" class="dropdown-item text-gray-600 hover:bg-gray-50">
                                 <i class="fas fa-user mr-2"></i>Remove Admin
                                 </button>
                                 </form>
                                @endif

                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection