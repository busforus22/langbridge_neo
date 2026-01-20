
    @extends('layouts.app')
   
    @section('sidebar')
    <aside class="col-auto bg-gray-100 p-4 rounded-xl shadow-md space-y-6">
        <nav class="w-64 border-r border-gray-200 font-sans">
            <div class="border rounded">
                <div class="flex items-center px-3 py-2 text-gray-900 font-medium report-title">
                    <span class="mr-3 text-purple-300">
                        <i class="fas fa-database"></i> </span>
                    <span class="text-sm">Data Overview</span>
                    
                </div>
                <div class="border">
                    <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 rounded-md transition-colors">
                        <span class="mr-3 opacity-50"><i class="fa-solid fa-users"></i></span> User
                    </a>
                </div>
            </div>
        
            <div class="border rounded">
                <div class="flex items-center px-3 py-2 text-gray-900 font-medium report-title">
                    <span class="mr-3 text-purple-300">
                        <i class="far fa-file-alt"></i> </span>
                    <span class="text-sm">Reports</span>
                    
                </div>
                
                <div class="border">
                    <a href="{{ route('admin.reports.users') }}" 
                       class="flex items-center ml-4 px-3 py-2 text-sm rounded-md transition-all {{ request()->routeIs('admin.reports.user') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="mr-3 opacity-50">
                            <i class="fas fa-hand-paper"></i> </span>
                        User Report
                    </a>
                    <div>
                    <a href="{{ route('admin.reports.messages') }}" 
                       class="flex items-center ml-4 mt-1 px-3 py-2 text-sm rounded-md transition-all {{ request()->routeIs('admin.reports.message') ? 'bg-indigo-50 text-indigo-700 shadow-sm' : 'text-gray-600 hover:bg-gray-50' }}">
                        <span class="mr-3 opacity-50 text-xs">
                            <i class="fas fa-file-alt"></i>
                        </span>
                        Chat Message
                    </a>
                </div>
                    
                </div>
            </div>
        </nav>
        
    </aside> 
    

   @endsection

   @section('content')
<div class="p-6 overflow-x-auto">
    <div class="min-w-max">
    @yield('admin-content')
</div>
</div>
@endsection

