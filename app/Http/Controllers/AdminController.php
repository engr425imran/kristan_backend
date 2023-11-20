<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\About;
use App\Faq;
use App\TermOfService;
use App\Setting;
use App\Feedback;
use Auth;
use Hash;
use DB;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email',
            'password'      => 'required',
        ]);

        $credentials = [
            'email' => $request->email,
            'password'=> $request->password,
            // 'is_admin' => 1,
        ];

        if(Auth::attempt($credentials)){
            return redirect('admin/dashboard');
        }else{
            return redirect()->back()->with(['error_msg'=> 'Invalid Credentials']);
        }

    }

    public function dashboard()
    {
        $users = New User;
        return view('admin.dashboard', compact('users'));
    }

    public function managers()
    {
        $managers = User::where(['user_type'=> 1])->paginate(10);
        return view('admin.users', compact('managers'));
    }

    public function valets()
    {
        $valets = User::where(['user_type'=> 2])->paginate(10);
        return view('admin.valet', compact('valets'));
    }

    public function customers()
    {
        $users = User::where(['user_type'=> 0])->paginate(10);
        return view('admin.customer', compact('users'));
    }

    public function about()
    {
        $about =  About::first();
        return view('admin.about', compact('about'));
    }

    public function save_about(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
        ]);

        $about = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        $data = About::first();
        if(empty($data)){
            About::create($about);
            $message = "Data Saved Successfully!";
        }else{
            About::where(['id'=> $data->id])->update($about);
            $message = "Data Updated Successfully!";
        }

        return redirect()->back()->with(['success'=> $message]);
    }

    public function faq()
    {
        $faq = Faq::first();
        return view('admin.faqs',compact('faq'));
    }

    public function save_faq(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
        ]);

        $about = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        $data = Faq::first();
        if(empty($data)){
            Faq::create($about);
            $message = "Data Saved Successfully!";
        }else{
            Faq::where(['id'=> $data->id])->update($about);
            $message = "Data Updated Successfully!";
        }

        return redirect()->back()->with(['success'=> $message]);
    }

    public function terms()
    {
        $term = TermOfService::first();
        return view('admin.terms_services', compact('term'));
    }

    public function save_term(Request $request)
    {
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
        ]);

        $about = [
            'title' => $request->title,
            'description' => $request->description,
        ];

        $data = TermOfService::first();
        if(empty($data)){
            TermOfService::create($about);
            $message = "Data Saved Successfully!";
        }else{
            TermOfService::where(['id'=> $data->id])->update($about);
            $message = "Data Updated Successfully!";
        }

        return redirect()->back()->with(['success'=> $message]);
    }

    public function setting()
    {
        $setting = Setting::first();
        return view('admin.site_setting', compact('setting'));
    }

    public function save_setting(Request $request)
    {
        $this->validate($request,[
            'site_title' => 'required',
            'email' => 'required|email',
            'contact_number' => 'required',
        ]);

        $setting = [
            'title' => $request->site_title,
            'email' => $request->email,
            'number' => $request->contact_number,
        ];

        $data = Setting::first();
        if(empty($data)){
            Setting::create($setting);
            $message = "Data Saved Successfully!";
        }else{
            Setting::where(['id'=> $data->id])->update($setting);
            $message = "Data Updated Successfully!";
        }

        return redirect()->back()->with(['success'=> $message]);

    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function update_profile(Request $request)
    {
        $this->validate($request,[
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required','unique:users,email,'.auth()->user()->id],
            'phone' => 'required',
        ]);

        $profile = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone,
        ];

        if(!empty($request->password)){
            $profile['password'] = Hash::make($request->password);
        }

        User::where(['id'=> auth()->user()->id])->update($profile);
        return redirect()->back()->with(['success'=> 'Profile Updated Successfully!']);

    }

    public function delete($user_id)
    {
        User::where(['id'=> $user_id])->delete();
        return redirect()->back()->with(['success'=> 'User Deleted Successfully!']);
    }

    public function getReport(Request $request)
    {
        $end_date = date("Y-m-d", strtotime($request->end_date));
        $start_date = date("Y-m-d", strtotime($request->start_date));

        if($request->reportType == 'user'){
            $query = User::where(['is_admin'=>0]);
            if($start_date != $end_date){
                $query = $query->whereBetween(DB::raw('DATE(created_at)'), array($start_date, $end_date));
            }else{
                $query = $query->whereDate('created_at', $start_date);
            }
            $users = $query->get();
            if(count($users) > 0)
            return response()->json(['userReport'=> $users, 'success'=> true]);
            else
            return response()->json(['userReport'=> $users, 'success'=> false]);
        }elseif($request->reportType == 'revenue'){
            $revenuReport = Feedback::with('vehicle_request');
            if($start_date != $end_date){
                $revenuReport = $revenuReport->whereBetween(DB::raw('DATE(created_at)'), array($start_date, $end_date));
            }else{
                $revenuReport = $revenuReport->whereDate('created_at', $start_date);
            }
            $revenuReport = $revenuReport->get();
            if(count($revenuReport) > 0)
            return response()->json(['revenuReport'=> $revenuReport, 'success'=> true]);
            else
            return response()->json(['revenuReport'=> $revenuReport, 'success'=> false]);
        }elseif($request->reportType == 'rating'){
            $ratingReport = Feedback::with('vehicle_request');
            if($start_date != $end_date){
                $ratingReport = $ratingReport->whereBetween(DB::raw('DATE(created_at)'), array($start_date, $end_date));
            }else{
                $ratingReport = $ratingReport->whereDate('created_at', $start_date);
            }
            $ratingReport = $ratingReport->get();
            if(count($ratingReport) > 0)
            return response()->json(['ratingReport'=> $ratingReport, 'success'=> true]);
            else
            return response()->json(['ratingReport'=> $ratingReport, 'success'=> false]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
