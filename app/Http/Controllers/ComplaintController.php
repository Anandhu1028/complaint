<?php

namespace App\Http\Controllers;

use App\Mail\ComplaintSubmitted;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class ComplaintController extends Controller
{
    public function home() { return view('complaints.home'); }

    public function store(Request $request)
    {
        $key = 'complaint-submit:'.$request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['form' => 'Too many submissions. Please try again later.'])->withInput();
        }
        RateLimiter::hit($key, 600);

        $data = $request->validate([
            'full_name' => ['required','string','max:150'],
            'mobile' => ['required','string','max:30','regex:/^[0-9+()\-\s]{8,30}$/'],
            'email' => ['nullable','email','max:255'],
            'address' => ['required','string','max:2000'],
            'state' => ['required','string','max:100'],
            'district' => ['required','string','max:100'],
            'subject' => ['required','string','max:255'],
            'details' => ['required','string','max:10000'],
            'attachment' => ['nullable','file','mimes:jpg,jpeg,png,pdf,doc,docx','max:5120'],
        ]);

        $data['reference_no'] = 'CMP-'.now()->format('Ym').'-'.strtoupper(Str::random(6));
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('complaints','local');
        }
        $complaint = Complaint::create($data);

        try {
            Mail::to(config('complaint.recipient'))->send(new ComplaintSubmitted($complaint));
            $complaint->update(['status'=>'forwarded','email_sent_at'=>now(),'email_error'=>null]);
        } catch (\Throwable $e) {
            report($e);
            $complaint->update(['email_error'=>Str::limit($e->getMessage(), 2000)]);
        }

        return redirect()->route('complaints.success', $complaint->reference_no);
    }

    public function success(string $reference) {
        $complaint = Complaint::where('reference_no',$reference)->firstOrFail();
        return view('complaints.success', compact('complaint'));
    }

    public function track(Request $request) {
        $complaint = null;
        if ($request->filled('reference')) {
            $complaint = Complaint::where('reference_no', strtoupper(trim($request->reference)))->first();
        }
        return view('complaints.track', compact('complaint'));
    }
}
