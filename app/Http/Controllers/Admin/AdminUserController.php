<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    public function index(Request $request){
        
        $search = $request->input('search');
        $query = User::withTrashed();

        if($search = $request->input('search')){
            $query->where(function($userQuery) use ($search){
                $userQuery->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhereHas('profile', function($profileQuery) use ($search){
                            $profileQuery->where('handle', 'like', "%{$search}%");
                          });
            });
        }
        $users = $query->paginate(10);
        return view('admin.user.index', compact('users'));
    }

  
   // restore by admin
   public function restore($id){
       $user = User::withTrashed()->findOrFail($id);
       $user->restore();

       return back()->with('success', 'restored');
   }

   public function suspend($id) {
    User::findOrFail($id)->update(['suspended' => 1]);
    return back()->with('success', 'suspended');
}

public function unsuspend($id) {
    User::findOrFail($id)->update(['suspended' => 0]);
    return back()->with('success', 'active');
}

public function destroy($id) {
    User::findOrFail($id)->delete();
    return back()->with('success', 'deleted');
}

public function makeAdmin($id)
{
    $user = User::findOrFail($id);
    $user->is_admin = 1;
    $user->save();

    return back();
}

public function removeAdmin($id)
{
    $user = User::findOrFail($id);
    $user->is_admin = 0;
    $user->save();

    return back();
}


}
