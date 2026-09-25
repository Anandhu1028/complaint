<?php
namespace App\Http\Controllers;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminComplaintController extends Controller
{
    private function guard(Request $request): void
    {
        if (!$request->session()->get('complaint_admin')) abort(403);
    }
    public function login() { return view('admin.login'); }
    public function authenticate(Request $request) {
        $request->validate(['email'=>'required|email','password'=>'required']);
        if ($request->email !== env('COMPLAINT_ADMIN_EMAIL') || !Hash::check($request->password, password_hash(env('COMPLAINT_ADMIN_PASSWORD'), PASSWORD_BCRYPT))) {
            return back()->withErrors(['email'=>'Invalid admin credentials.']);
        }
        $request->session()->regenerate(); $request->session()->put('complaint_admin', true); return redirect()->route('admin.dashboard');
    }
    public function logout(Request $request) { $request->session()->forget('complaint_admin'); $request->session()->invalidate(); $request->session()->regenerateToken(); return redirect()->route('admin.login'); }
    public function dashboard(Request $request) {
        $this->guard($request); $q=$request->string('q')->trim(); $status=$request->string('status')->trim();
        $query=Complaint::query()->latest(); if($q->isNotEmpty()) $query->where(fn($x)=>$x->where('reference_no','like','%'.$q.'%')->orWhere('full_name','like','%'.$q.'%')->orWhere('mobile','like','%'.$q.'%')->orWhere('subject','like','%'.$q.'%')); if($status->isNotEmpty()) $query->where('status',$status);
        $complaints=$query->paginate(12)->withQueryString();
        $stats=['total'=>Complaint::count(),'new'=>Complaint::where('status','new')->count(),'forwarded'=>Complaint::where('status','forwarded')->count(),'resolved'=>Complaint::where('status','resolved')->count()];
        return view('admin.dashboard',compact('complaints','stats'));
    }
    public function show(Request $request, Complaint $complaint) { $this->guard($request); return view('admin.show',compact('complaint')); }
    public function update(Request $request, Complaint $complaint) { $this->guard($request); $request->validate(['status'=>'required|in:new,forwarded,in_progress,resolved,closed']); $complaint->update(['status'=>$request->status]); return back()->with('ok','Status updated.'); }
}
