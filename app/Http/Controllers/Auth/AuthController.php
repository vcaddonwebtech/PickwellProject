<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\JointComplaint;
use App\Models\ComplaintType;
use App\Models\EmployeeAttendence;
use App\Models\Attendtl;
use App\Models\Party;
use App\Models\PartywiseMachine;
use App\Models\Status;
use App\Models\User;
use App\Models\UserwiseMachine;
use App\Models\Months;
use App\Models\Area;
use App\Models\LeadStage;
use App\Models\Product;
use App\Models\Role;
use App\Models\Holiday;
use App\Models\CustomerFeedback;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Api\CustomerFeedbackRequest;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function login(Request $request)
    {
        // $loginUserData = $request->validate([
        //     'phone_no' => 'required',
        //     'password' => 'required|min:8'
        // ]);
        //dd($request);
        $user = User::where('phone_no', $request->phone_no)->where('is_active', 1)->with('roles')->first();

        if ($user) {
            // Delete old token
            $user->tokens()->delete(); // uses Laravel Sanctum
            $otp = random_int(100000, 999999);
            $phonenum1 = 9879260346;
            $phonenum = $user->phone_no;
            //$phonenum = 9879260346;
            if(($user->id != '162' || $user->id != '163')) {
                $user->update(['otp' => $otp]);
                $otpapiurl = "http://mobizz.hginfosys.co.in/sendsms.jsp?user=PICKWELL&password=b4672740cfXX&senderid=PIKWEL&mobiles=+91".$phonenum."&sms=".$otp." is the OTP to verify your mobile number with PICKWELL EXIM. OTP is valid for the next 5 mins. Do not share with anyone PICKWELL EXIM.&tempid=1707176543676263550";
                //$otpapiurl = "http://mobizz.hginfosys.co.in/sendsms.jsp?user=SAISTAR&password=9c88265930XX&senderid=SSIMPL&mobiles=+91".$phonenum."&sms=".$otp." is the OTP to verify your mobile number with SAISTAR IMPEX PRIVATE LIMITED. OTP is valid for the next 5 mins. Do not share with anyone SAISTAR GROUP.&tempid=1707175110503248018";
                //http://mobizz.hginfosys.co.in/sendsms.jsp?user=PICKWELL&password=b4672740cfXX&senderid=PIKWEL&mobiles=+918758233066&sms=123456
                //$otpapiurl1 = "http://mobizz.hginfosys.co.in/sendsms.jsp?user=SAISTAR&password=9c88265930XX&senderid=SSIMPL&mobiles=+91".$phonenum1."&sms=".$otp." is the OTP to verify your mobile number with SAISTAR IMPEX PRIVATE LIMITED. OTP is valid for the next 5 mins. Do not share with anyone SAISTAR GROUP.&tempid=1707175110503248018";
                //$response1 = Http::get($otpapiurl1);
                //Send OTP to user mobile
                $response = Http::get($otpapiurl);

                if ($response->successful()) {
                    // Create a new token
                    $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
                    return response()->json([
                        'success' => true,
                        'message' => 'OTP sent Successfully',
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'id' => $user->id,
                        'user_otp' => $otp
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong, OTP is not sent!',
                    ], 401);
                }
            }        
        }
        
        // If user is empty then try to login as party
        if (empty($user)) {
            //$user = Party::where('phone_no', $request->phone_no)->where('is_active', 1)->first();
            $parties = Party::where('phone_no', $request->phone_no)->where('is_active', 1)->first();
            if ($parties) {
            // Delete old token
            $parties->tokens()->delete(); // uses Laravel Sanctum
            $potp = random_int(100000, 999999);
            $phonenum1 = 9879260346;
            $phonenum = $parties->phone_no;
            //$phonenum = 9879260346;
            // Add Otp to party table    
            $parties->update(['otp' => $potp]);
            $otpapiurl = "http://mobizz.hginfosys.co.in/sendsms.jsp?user=PICKWELL&password=b4672740cfXX&senderid=PIKWEL&mobiles=+91".$phonenum."&sms=".$potp." is the OTP to verify your mobile number with PICKWELL EXIM. OTP is valid for the next 5 mins. Do not share with anyone PICKWELL EXIM.&tempid=1707176543676263550";
            //$otpapiurl = "http://mobizz.hginfosys.co.in/sendsms.jsp?user=SAISTAR&password=9c88265930XX&senderid=SSIMPL&mobiles=+91".$phonenum."&sms=".$potp." is the OTP to verify your mobile number with SAISTAR IMPEX PRIVATE LIMITED. OTP is valid for the next 5 mins. Do not share with anyone SAISTAR GROUP.&tempid=1707175110503248018";
            //$otpapiurl1 = "http://mobizz.hginfosys.co.in/sendsms.jsp?user=SAISTAR&password=9c88265930XX&senderid=SSIMPL&mobiles=+91".$phonenum1."&sms=".$potp." is the OTP to verify your mobile number with SAISTAR IMPEX PRIVATE LIMITED. OTP is valid for the next 5 mins. Do not share with anyone SAISTAR GROUP.&tempid=1707175110503248018";
            //$response1 = Http::get($otpapiurl1);
            //Send OTP to user mobile
            $response = Http::get($otpapiurl);

                if ($response->successful()) {
                    // Create a new token
                    $token = $parties->createToken($parties->name . '-AuthToken')->plainTextToken;
                    return response()->json([
                        'success' => true,
                        'message' => 'OTP sent Successfully',
                        'access_token' => $token,
                        'token_type' => 'Bearer',
                        'id' => $parties->id,
                        'user_otp' => $potp
                    ], 200);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong, OTP is not sent!',
                    ], 401);
                }    
        }
            // $roles[] =
            //     [
            //         "id" => 3,
            //         "name" =>  "customer",
            //         "guard_name" => "web",
            //     ];
            // $user['roles'] = $roles;
        }
       //dd($user->phone_no);

        
        if (!$user && !$parties) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Credentials',
            ], 401);
        }

        if ($user->is_active == 0 || $parties->is_active == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid',
            ], 401);
        }

       

        // Send and save OTP 
        //if ($user && Hash::check($loginUserData['password'], $user->password)) {
        

        // $user->profile_url = url('/user_dp/' . $user->profile);
        // $user->aadhar_url = url('/user_dp/' . $user->aadhar_card);
        // $user->pan_url = url('/user_dp/' . $user->pan_card);
        //  // Delete all previous tokens to prevent multiple device login
        // $user->tokens()->delete(); // uses Laravel Sanctum

        // // Create a new token
        // $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
        // return response()->json([
        //     'success' => true,
        //     'message' => 'Login Successful',
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        //     'user' => $user
        // ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            "message" => "logged out",
        ], 200);
    }

    public function changePassword(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = User::find(Auth::id());
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current Password does not match',
            ]);
        }
        // $user->password = Hash::make($request->password);
        $user->update(['password' => $request->password]);
        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);
        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function verifyOtp(Request $request): JsonResponse
    {
       //$user = User::find(Auth::id());
       $user = User::where('phone_no', $request->phone_no)->where('is_active', 1)->with('roles')->first();
        if($user && $request->otp == $user->otp) {
            $user->profile_url = url('/user_dp/' . $user->profile);
            $user->aadhar_url = url('/user_dp/' . $user->aadhar_card);
            $user->pan_url = url('/user_dp/' . $user->pan_card);
             // Delete all previous tokens to prevent multiple device login
            $user->tokens()->delete(); // uses Laravel Sanctum
            // Create a new token
            $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'OTP Matched, Login Successful',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ], 200);
        } else if(!$user) {
            $user = Party::where('phone_no', $request->phone_no)->where('is_active', 1)->first();
            $roles[] =
                [
                    "id" => 3,
                    "name" =>  "customer",
                    "guard_name" => "web",
                ];
            $user['roles'] = $roles;
            if($user && $request->otp == $user->otp) {
                $user->profile_url = '';
                $user->aadhar_url = '';
                $user->pan_url = '';
                // Delete all previous tokens to prevent multiple device login
                $user->tokens()->delete(); // uses Laravel Sanctum
                // Create a new token
                $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;
                return response()->json([
                    'success' => true,
                    'message' => 'OTP Matched, Login Successful',
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user
                ], 200);
            }
        } else {
            return response()->json([
                        'success' => false,
                        'message' => 'OTP is not matched!',
                    ], 401);
        }  
    }

    public function engineerComplaints(Request $request)
    {
        //$user = User::where("phone_no", auth()->user()->phone_no)->first();
        $useridn = Auth::user()->id;
        // Build the query
        //$main_machine_type = $request->main_machine_type;
        //$complaintsQuery = Complaint::where("engineer_id", $user->id)
        $complaintsQuery = Complaint::where(function ($query) use ($useridn) {
                $query->whereHas('jointcomplaintengs', function ($q) use ($useridn) {
                    $q->where('joint_eng_id', $useridn);
                })->orWhere('engineer_id', $useridn);
                
            })
            ->with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType', 'engineer', 'jointcomplaintengs.jntengDetail', 'feedback')
            ->orderBy('id', 'desc');

        // Apply additional filters if provided
        if (isset($request->date) && $request->date != null) {
            $complaintsQuery->where('date', $request->date);
        }
        if (isset($request->status_id) && $request->status_id == 0) {
            //$complaintsQuery->where('status_id', $request->status_id);
        }
        if (isset($request->status_id) && $request->status_id != 0) {
            $complaintsQuery->where('status_id', $request->status_id);
        }
        if (isset($request->party_id) && $request->party_id != null) {
            $complaintsQuery->where('party_id', $request->party_id);
        }
        if (isset($request->complaint_no) && $request->complaint_no != null) {
            $complaintsQuery->where('complaint_no', $request->complaint_no);
        }

        // Apply pagination
        $paginatedComplaints = $complaintsQuery->paginate(10);

        $data = [];
        foreach ($paginatedComplaints->items() as $item) {
            if (!empty($item->image)) {
                $item['image_url'] = url('/complaint/images/' . $item->image);
            } else {
                $item['image_url'] = '';
            }

            if (!empty($item->video)) {
                $item['video_url'] = url('/complaint/videos/' . $item->video);
            } else {
                $item['video_url'] = '';
            }

            if (!empty($item->audio)) {
                $item['audio_url'] = url('/complaint/audios/' . $item->audio);
            } else {
                $item['audio_url'] = '';
            }

            if (!empty($item->engineer_image)) {
                $item['engineer_image_url'] = url('/complaint/images/' . $item->engineer_image);
            } else {
                $item['engineer_image_url'] = '';
            }

            if (!empty($item->engineer_video)) {
                $item['engineer_video_url'] = url('/complaint/videos/' . $item->engineer_video);
            } else {
                $item['engineer_video_url'] = '';
            }

            if (!empty($item->engineer_audio)) {
                $item['engineer_audio_url'] = url('/complaint/audios/' . $item->engineer_audio);
            } else {
                $item['engineer_audio_url'] = '';
            }

            array_push($data, $item);
        }


        // Customize the response
        $response = [
            'success' => true,
            'message' => 'Complaints fetched successfully',
            'data' => [
                'total' => $paginatedComplaints->total(),
                'per_page' => $paginatedComplaints->perPage(),
                'current_page' => $paginatedComplaints->currentPage(),
                'last_page' => $paginatedComplaints->lastPage(),
                'from' => $paginatedComplaints->firstItem(),
                'to' => $paginatedComplaints->lastItem(),
                'data' => $data
            ]
        ];

        return response()->json($response, 200);
    }

    public function managerComplaints(Request $request)
    {
        //$user = User::where("phone_no", auth()->user()->phone_no)->first();
        // Build the query
        $main_machine_type = $request->main_machine_type;
        // $complaintsQuery = Complaint::where(['user_id' => auth()->user()->id, 'main_machine_type' => $main_machine_type])
        //     ->with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType', 'engineer', 'jointcomplaintengs.jntengDetail', 'feedback')
        //     ->orderBy('id', 'desc');

        $complaintsQuery = Complaint::where('main_machine_type', $main_machine_type)
            ->whereIn('service_type_id', [2, 3])
            ->with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType', 'engineer', 'jointcomplaintengs.jntengDetail', 'feedback')
            ->orderBy('id', 'desc');

        // Apply additional filters if provided
        if (isset($request->date) && $request->date != null) {
            $complaintsQuery->where('date', $request->date);
        }
        if (isset($request->status_id) && $request->status_id == 0) {
            //$complaintsQuery->where('status_id', $request->status_id);
        }
        if (isset($request->status_id) && $request->status_id != 0) {
            $complaintsQuery->where('status_id', $request->status_id);
        }
        if (isset($request->party_id) && $request->party_id != null) {
            $complaintsQuery->where('party_id', $request->party_id);
        }
        if (isset($request->complaint_no) && $request->complaint_no != null) {
            $complaintsQuery->where('complaint_no', $request->complaint_no);
        }
        if (isset($request->engineer_id) && $request->engineer_id != null) {
            $complaintsQuery->where('engineer_id', $request->engineer_id);
        }

        // Apply pagination
        $paginatedComplaints = $complaintsQuery->latest()->paginate(10);

        $data = [];
        foreach ($paginatedComplaints->items() as $item) {
            if (!empty($item->image)) {
                $item['image_url'] = url('/complaint/images/' . $item->image);
            } else {
                $item['image_url'] = '';
            }

            if (!empty($item->video)) {
                $item['video_url'] = url('/complaint/videos/' . $item->video);
            } else {
                $item['video_url'] = '';
            }

            if (!empty($item->audio)) {
                $item['audio_url'] = url('/complaint/audios/' . $item->audio);
            } else {
                $item['audio_url'] = '';
            }

            if (!empty($item->engineer_image)) {
                $item['engineer_image_url'] = url('/complaint/images/' . $item->engineer_image);
            } else {
                $item['engineer_image_url'] = '';
            }

            if (!empty($item->engineer_video)) {
                $item['engineer_video_url'] = url('/complaint/videos/' . $item->engineer_video);
            } else {
                $item['engineer_video_url'] = '';
            }

            if (!empty($item->engineer_audio)) {
                $item['engineer_audio_url'] = url('/complaint/audios/' . $item->engineer_audio);
            } else {
                $item['engineer_audio_url'] = '';
            }

            array_push($data, $item);
        }


        // Customize the response
        $response = [
            'success' => true,
            'message' => 'Complaints fetched successfully',
            'data' => [
                'total' => $paginatedComplaints->total(),
                'per_page' => $paginatedComplaints->perPage(),
                'current_page' => $paginatedComplaints->currentPage(),
                'last_page' => $paginatedComplaints->lastPage(),
                'from' => $paginatedComplaints->firstItem(),
                'to' => $paginatedComplaints->lastItem(),
                'data' => $data
            ]
        ];

        return response()->json($response, 200);
    }

    public function managerNotAssignedComplaints(Request $request)
    {
        //$user = User::where("phone_no", auth()->user()->phone_no)->first();
        // Build the query
        $main_machine_type = $request->main_machine_type;
        // $complaintsQuery = Complaint::where(['user_id' => auth()->user()->id, 'main_machine_type' => $main_machine_type])
        //     ->with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType', 'engineer', 'jointcomplaintengs.jntengDetail', 'feedback')
        //     ->orderBy('id', 'desc');

        $complaintsQuery = Complaint::where('main_machine_type', $main_machine_type)
            ->where('is_assigned', 0)
            ->whereIn('service_type_id', [2, 3])
            ->with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType', 'engineer', 'jointcomplaintengs.jntengDetail', 'feedback')
            ->orderBy('id', 'desc');

        // Apply additional filters if provided
        if (isset($request->date) && $request->date != null) {
            $complaintsQuery->where('date', $request->date);
        }
        if (isset($request->status_id) && $request->status_id == 0) {
            //$complaintsQuery->where('status_id', $request->status_id);
        }
        if (isset($request->status_id) && $request->status_id != 0) {
            $complaintsQuery->where('status_id', $request->status_id);
        }
        if (isset($request->party_id) && $request->party_id != null) {
            $complaintsQuery->where('party_id', $request->party_id);
        }
        if (isset($request->complaint_no) && $request->complaint_no != null) {
            $complaintsQuery->where('complaint_no', $request->complaint_no);
        }
        if (isset($request->engineer_id) && $request->engineer_id != null) {
            $complaintsQuery->where('engineer_id', $request->engineer_id);
        }

        // Apply pagination
        $paginatedComplaints = $complaintsQuery->latest()->paginate(10);

        $data = [];
        foreach ($paginatedComplaints->items() as $item) {
            if (!empty($item->image)) {
                $item['image_url'] = url('/complaint/images/' . $item->image);
            } else {
                $item['image_url'] = '';
            }

            if (!empty($item->video)) {
                $item['video_url'] = url('/complaint/videos/' . $item->video);
            } else {
                $item['video_url'] = '';
            }

            if (!empty($item->audio)) {
                $item['audio_url'] = url('/complaint/audios/' . $item->audio);
            } else {
                $item['audio_url'] = '';
            }

            if (!empty($item->engineer_image)) {
                $item['engineer_image_url'] = url('/complaint/images/' . $item->engineer_image);
            } else {
                $item['engineer_image_url'] = '';
            }

            if (!empty($item->engineer_video)) {
                $item['engineer_video_url'] = url('/complaint/videos/' . $item->engineer_video);
            } else {
                $item['engineer_video_url'] = '';
            }

            if (!empty($item->engineer_audio)) {
                $item['engineer_audio_url'] = url('/complaint/audios/' . $item->engineer_audio);
            } else {
                $item['engineer_audio_url'] = '';
            }

            array_push($data, $item);
        }


        // Customize the response
        $response = [
            'success' => true,
            'message' => 'Complaints fetched successfully',
            'data' => [
                'total' => $paginatedComplaints->total(),
                'per_page' => $paginatedComplaints->perPage(),
                'current_page' => $paginatedComplaints->currentPage(),
                'last_page' => $paginatedComplaints->lastPage(),
                'from' => $paginatedComplaints->firstItem(),
                'to' => $paginatedComplaints->lastItem(),
                'data' => $data
            ]
        ];

        return response()->json($response, 200);
    }

    public function installationComplaints(Request $request)
    {
        //$user = User::where("phone_no", auth()->user()->phone_no)->first();
        // Build the query
        $main_machine_type = $request->main_machine_type;
        // $complaintsQuery = Complaint::where(['user_id' => auth()->user()->id, 'main_machine_type' => $main_machine_type])
        //     ->with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType', 'engineer', 'jointcomplaintengs.jntengDetail', 'feedback')
        //     ->orderBy('id', 'desc');

        $complaintsQuery = Complaint::where(['main_machine_type' => $main_machine_type, 'service_type_id' => 4])
            ->with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType', 'engineer', 'jointcomplaintengs.jntengDetail', 'feedback')
            ->orderBy('id', 'desc');

        // Apply additional filters if provided
        if (isset($request->date) && $request->date != null) {
            $complaintsQuery->where('date', $request->date);
        }
        if (isset($request->status_id) && $request->status_id == 0) {
            //$complaintsQuery->where('status_id', $request->status_id);
        }
        if (isset($request->status_id) && $request->status_id != 0) {
            $complaintsQuery->where('status_id', $request->status_id);
        }
        if (isset($request->party_id) && $request->party_id != null) {
            $complaintsQuery->where('party_id', $request->party_id);
        }
        if (isset($request->complaint_no) && $request->complaint_no != null) {
            $complaintsQuery->where('complaint_no', $request->complaint_no);
        }
        if (isset($request->engineer_id) && $request->engineer_id != null) {
            $complaintsQuery->where('engineer_id', $request->engineer_id);
        }

        // Apply pagination
        $paginatedComplaints = $complaintsQuery->latest()->paginate(10);

        $data = [];
        foreach ($paginatedComplaints->items() as $item) {
            if (!empty($item->image)) {
                $item['image_url'] = url('/complaint/images/' . $item->image);
            } else {
                $item['image_url'] = '';
            }

            if (!empty($item->video)) {
                $item['video_url'] = url('/complaint/videos/' . $item->video);
            } else {
                $item['video_url'] = '';
            }

            if (!empty($item->audio)) {
                $item['audio_url'] = url('/complaint/audios/' . $item->audio);
            } else {
                $item['audio_url'] = '';
            }

            if (!empty($item->engineer_image)) {
                $item['engineer_image_url'] = url('/complaint/images/' . $item->engineer_image);
            } else {
                $item['engineer_image_url'] = '';
            }

            if (!empty($item->engineer_video)) {
                $item['engineer_video_url'] = url('/complaint/videos/' . $item->engineer_video);
            } else {
                $item['engineer_video_url'] = '';
            }

            if (!empty($item->engineer_audio)) {
                $item['engineer_audio_url'] = url('/complaint/audios/' . $item->engineer_audio);
            } else {
                $item['engineer_audio_url'] = '';
            }

            array_push($data, $item);
        }


        // Customize the response
        $response = [
            'success' => true,
            'message' => 'Complaints fetched successfully',
            'data' => [
                'total' => $paginatedComplaints->total(),
                'per_page' => $paginatedComplaints->perPage(),
                'current_page' => $paginatedComplaints->currentPage(),
                'last_page' => $paginatedComplaints->lastPage(),
                'from' => $paginatedComplaints->firstItem(),
                'to' => $paginatedComplaints->lastItem(),
                'data' => $data
            ]
        ];

        return response()->json($response, 200);
    }

    public function getComplaints(Request $request)
    {
        if (!$request->user()->hasRole('Admin')) {
            return response()->json([
                "success" => false,
                "message" => "Unauthorized",
                "data" => [],
            ]);
        } else {
            $main_machine_type = $request->main_machine_type;
            $complaintsQuery = Complaint::whereIn('service_type_id', [2, 3])->with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType', 'engineer', 'feedback');
            if (isset($request->date)) {
                $complaintsQuery->where('date', $request->date);
            } 
            if (isset($request->status_id) && $request->status_id == 0) {
                // if ($request->status_id == 3) {
                //     $complaintsQuery->where('engineer_out_date', date('Y-m-d'));
                // }
                //$complaintsQuery->where('status_id', $request->status_id);
            } 
            if (isset($request->status_id) && $request->status_id != 0) {
                // if ($request->status_id == 3) {
                //     $complaintsQuery->where('engineer_out_date', date('Y-m-d'));
                // }
                $complaintsQuery->where('status_id', $request->status_id);
            }
            if (isset($request->main_machine_type)) {
                $complaintsQuery->where('main_machine_type', $main_machine_type);
            }
            if (isset($request->party_id)) {
                $complaintsQuery->where('party_id', $request->party_id);
            }
            if (isset($request->engineer_id)) {
                $complaintsQuery->where('engineer_id', $request->engineer_id);
            }
            if (isset($request->is_assigned)) {
                if ($request->is_assigned != 10) {
                    $complaintsQuery->where('is_assigned', $request->is_assigned);
                }
            }
            if (isset($request->complaint_no)) {
                $complaintsQuery->where('complaint_no', $request->complaint_no);
            }

            // Apply pagination directly on the query builder
            $paginatedComplaints = $complaintsQuery->latest()->paginate(10);
            //dd($complaintsQuery->toSql());


            $data = [];
            foreach ($paginatedComplaints->items() as $item) {
                if (!empty($item->image)) {
                    $item['image_url'] = url('/complaint/images/' . $item->image);
                } else {
                    $item['image_url'] = '';
                }

                if (!empty($item->video)) {
                    $item['video_url'] = url('/complaint/videos/' . $item->video);
                } else {
                    $item['video_url'] = '';
                }

                if (!empty($item->audio)) {
                    $item['audio_url'] = url('/complaint/audios/' . $item->audio);
                } else {
                    $item['audio_url'] = '';
                }

                if (!empty($item->engineer_image)) {
                    $item['engineer_image_url'] = url('/complaint/images/' . $item->engineer_image);
                } else {
                    $item['engineer_image_url'] = '';
                }

                if (!empty($item->engineer_video)) {
                    $item['engineer_video_url'] = url('/complaint/videos/' . $item->engineer_video);
                } else {
                    $item['engineer_video_url'] = '';
                }

                if (!empty($item->engineer_audio)) {
                    $item['engineer_audio_url'] = url('/complaint/audios/' . $item->engineer_audio);
                } else {
                    $item['engineer_audio_url'] = '';
                }

                array_push($data, $item);
            }

            return response()->json([
                "success" => true,
                "message" => "Complaints fetched successfully",
                'data' => [
                    'total' => $paginatedComplaints->total(),
                    'per_page' => $paginatedComplaints->perPage(),
                    'current_page' => $paginatedComplaints->currentPage(),
                    'last_page' => $paginatedComplaints->lastPage(),
                    'from' => $paginatedComplaints->firstItem(),
                    'to' => $paginatedComplaints->lastItem(),
                    'data' => $data
                ]
            ], 200);
        }
    }


    public function engineers(Request $request)
    {
        // if (!$request->user()->hasRole('Admin')) {
        //     return response()->json([
        //         "success" => false,
        //         "message" => "Unauthorized",
        //         "data" => [],
        //     ]);
        // } else {
        $main_machine_type = $request->main_machine_type;
            $engineer = User::with('roles')
                ->role('engineer')
                ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
                $query->where('main_machine_type', $main_machine_type);
                })
                ->leftJoin('complaints', function ($join) {
                    $join->on('users.id', '=', 'complaints.engineer_id')
                        ->where(function ($query) {
                            $query->where('complaints.status_id', '!=', 3)
                                ->orWhereNull('complaints.status_id'); // Handle cases with no complaints
                        });
                })
                ->select('users.id', 'users.name', DB::raw('COUNT(complaints.id) as pending_complaints'))
                ->where('is_active', 1)
                ->groupBy('users.id', 'users.name')
                ->orderBy('pending_complaints', 'asc')
                ->get();

            return response()->json([
                "success" => true,
                "message" => "Engineers fetched successfully",
                "data" => $engineer,
            ], 200);
        // }
    }

    public function assignEngineer(Request $request)
    {
        
        //$jointenggengs = str_replace(['["', '"]'], '', $request->jointengg);
        //dd($jointenggengs);
        $complaint = Complaint::where("id", $request->id)->first();
        $party_idnn = $complaint->party_id;
        $complaint->update([
            "engineer_id" => $request->engineer_id,
            "is_assigned" => 1,
            "jointengg" => $request->jointengg,
            "engineer_assign_date" => date('Y-m-d'),
            "engineer_assign_time" => date("h:i:s"),
        ]);

        //if more then one enginees - Join engs entry
            if(isset($request->jointengg) && !empty($request->jointengg)) {
                //$jointenggengs1 = trim($request->jointengg, '"');    
                //$jointenggengs2 = trim($jointenggengs1, '[');
                //$jointenggengs = trim($jointenggengs2, ']');
                //dd($jointenggengs);
               //$jointenggengs = str_replace(['[', ']'], '', $request->jointengg);
               $jointenggengs1 = trim($request->jointengg, '"');    
                $jointenggengs2 = trim($jointenggengs1, '[');
                $jointenggengs = trim($jointenggengs2, ']');
                $jointenggArr = explode(',', $jointenggengs);
                //$jointenggArr = explode(',', $jointenggengs);
                    $Jtoken = [];
                    foreach($jointenggArr as $engv) 
                    {  
                        // Skip main assigned engineer
                        if (empty($engv) || $engv == $request->engineer_id) {
                            continue;
                        }
                        if(isset($engv))
                        {    
                            $data['joint_eng_id'] = (int) $engv;
                            $data['complaint_id'] = $request->id;
                            $complaint1[$engv] = JointComplaint::create($data);
                        }
                        // Fetch engineer
                        $user = User::find($engv);
                        // Collect valid device tokens
                        if (!empty($user?->device_token)) {
                            $Jtoken[] = $user->device_token;
                        }
                        $title = 'New Complaint';
                        $body = 'A new complaint has been assigned to you. Complaint No: ' . $request->id;
                        //$type = 'engineer';
                        $type = $complaint->main_machine_type;
                        $this->notificationService->sendAlarmNotification($title, $body, $Jtoken, $type, $request->id);  
                    }
            } 

        // Engineer Push notification
        $engineerDetail = User::find($complaint->engineer_id);

        if (isset($complaint) && !empty($complaint)) {
            // $admin = User::find(1);
            // Do not remove admin id 1 from DB

            if (!empty($engineerDetail->device_token)) {
                // Send push notification
                $title = 'New complaint';
                $body = 'A new complaint has been assigned to you. Complaint No: ' . $complaint->id;
                $token = $engineerDetail->device_token;
                $type = $complaint->main_machine_type;

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    //$this->notificationService->sendNotification($title, $body, $token);
                    $this->notificationService->sendAlarmNotification($title, $body, $token, $type, $complaint->id);
                }
            }
        }

        // Party Push Notification
        if (isset($party_idnn)) {
            $party = Party::find($party_idnn);
            if (!empty($party->device_token)) {
                // Send push notification
                $title = 'Complaint Assigned';
                $body = 'Your complaint has been assigned to ' . $engineerDetail->name . ' Complaint No: ' . $request->id;
                $token = $party->device_token;

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    $this->notificationService->sendNotification($title, $body, $token);
                }
            }
        }

        return response()->json([
            "success" => true,
            "message" => "Complaint assigned successfully",
            "data" => $complaint,
        ], 200);
    }

    public function complaint(Request $request)
    {
        $datas['complaint'] = Complaint::where("id", $request->id)->with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType')->first();
        $data['past_complaints'] = Complaint::where('date', '<', $datas['complaint']->date)->where('sales_entry_id', $datas['complaint']->sales_entry_id)->orderBy('date', 'desc')->where('id', '!=', $datas['complaint']->id)->with('party', 'serviceType', 'salesEntry', 'status', 'product', 'complaintType', 'engineerDetail')->latest()->get();
        return response()->json([
            "success" => true,
            "message" => "Complaint fetched successfully",
            "data" => $data,
        ], 200);
    }

    public function EngineerInComplaint(Request $request)
    {
        try {
            $complaint = Complaint::where("id", $request->id)->first();
            $useridn = Auth::user()->id;
            // $engcomplaint = Complaint::where(function ($query) use ($useridn) {
            //         $query->whereHas('jointcomplaintengs', function ($q) use ($useridn) {
            //             $q->where('joint_eng_id', $useridn);
            //         })->orWhere('engineer_id', $useridn);
            //     });    
        
            // Moved image audio and video to out section
            // if (isset($request->engineer_image)) {
            //     $file = $request->engineer_image;
            //     $filename = time() . '.' . $file->getClientOriginalExtension();
            //     $file->move(public_path('complaint/images'), $filename);
            //     $complaint['engineer_image'] = $filename;
            // }
            // if (isset($request->engineer_video)) {
            //     $file = $request->engineer_video;
            //     $filename = time() . '.' . $file->getClientOriginalExtension();
            //     $file->move(public_path('complaint/videos'), $filename);
            //     $complaint['engineer_video'] = $filename;
            // }
            // if (isset($request->engineer_audio)) {
            //     $file = $request->engineer_audio;
            //     $filename = time() . '.' . $file->getClientOriginalExtension();
            //     $file->move(public_path('complaint/audios'), $filename);
            //     $complaint['engineer_audio'] = $filename;
            // }
            if($complaint->engineer_id == $useridn){    
                $complaint->update([
                    "status_id" => 2,
                    "engineer_in_time" => $request->engineer_in_time,
                    "engineer_in_date" => $request->engineer_in_date,
                    "engineer_in_address" => $request->engineer_in_address,
                ]);
                //$jntcomplaint = Complaint::where("id", $request->id)->first();
                $jntcomplaint = JointComplaint::where(['joint_eng_id' => $useridn, 'complaint_id' => $request->id])->first();
                $jntcomplaint->update([
                    "joint_eng_status" => 2,
                    "joint_eng_in_time" => $request->engineer_in_time,
                    "joint_eng_in_date" => $request->engineer_in_date,
                    "joint_eng_in_address" => $request->engineer_in_address,
                ]);
                
            } else {
                $jntcomplaint = JointComplaint::where(['joint_eng_id' => $useridn, 'complaint_id' => $request->id])->first();
                $jntcomplaint->update([
                    "joint_eng_status" => 2,
                    "joint_eng_in_time" => $request->engineer_in_time,
                    "joint_eng_in_date" => $request->engineer_in_date,
                    "joint_eng_in_address" => $request->engineer_in_address,
                ]);
                $complaint['status_id'] = $jntcomplaint->joint_eng_status;
                $complaint['engineer_in_time'] = $jntcomplaint->joint_eng_in_time;
                $complaint['engineer_in_date'] = $jntcomplaint->joint_eng_in_date;
                $complaint['engineer_in_address'] = $jntcomplaint->joint_eng_in_address;
            }

            $engineerDetail = User::find($complaint->engineer_id);

            //if (!empty($engineerDetail)) {
                // Admin push notification
                if (isset($complaint) && $complaint->engineer_id != "") {
                    //$admintl = User::find('178');
                    // do not remove admin id from DB
                    $admintl = User::whereHas('roles', fn ($q) =>
                                            $q->where('name', 'Service Team Leader')
                                        )
                                        ->whereHas('machineTypes', fn ($q) =>
                                            $q->where('main_machine_type', $complaint->main_machine_type)
                                        )
                                        ->where('is_leader', 1)
                                        ->where('is_active', 1)
                                        ->orderBy('id', 'desc') // or created_at
                                        ->first();
                    if (!empty($admintl->device_token)) {
                        // Send push notification
                        $title = 'Complaint In Process';
                        $body = $engineerDetail->name . ' checked in Complaint no ' . $complaint->complaint_no;
                        $token = $admintl->device_token;

                        if ($title != "" && $body != "" && $token != "") {
                            // Get the response from the helper function
                            // sendNotification($title, $body, $token);
                            $this->notificationService->sendNotification($title, $body, $token);
                        }
                    }
                }

                // Party push notification
                if (isset($complaint) && $complaint->party_id != "") {
                    $party = Party::find($complaint->party_id);
                    if (!empty($party->device_token)) {

                        // Send push notification
                        $title = 'Complain In process';
                        $body = $engineerDetail->name . ' check in complain no ' . $complaint->complaint_no;
                        $token = $party->device_token;

                        if ($title != "" && $body != "" && $token != "") {
                            // Get the response from the helper function
                            // sendNotification($title, $body, $token);
                            $this->notificationService->sendNotification($title, $body, $token);
                        }
                    }
                }
            //}
            return response()->json([
                "success" => true,
                "message" => "Complaint updated successfully",
                "data" => $complaint,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => "Complaint not updated",
                "data" => $th,
            ], 200);
        }
    }

    public function EngineerOutComplaint(Request $request)
    {
        $complaint = Complaint::where("id", $request->id)->first();
        $main_machine_type = $request->main_machine_type;
        $useridn = Auth::user()->id;
            if (isset($request->engineer_image)) {
                $file = $request->engineer_image;
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('complaint/images'), $filename);
                $complaint['engineer_image'] = $filename;
            }
            if (isset($request->engineer_video)) {
                $file = $request->engineer_video;
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('complaint/videos'), $filename);
                $complaint['engineer_video'] = $filename;
            }
            if (isset($request->engineer_audio)) {
                $file = $request->engineer_audio;
                $filename = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('complaint/audios'), $filename);
                $complaint['engineer_audio'] = $filename;
            }
            
        if($complaint->engineer_id == $useridn){  
            if (isset($request->fac_per_dsignature)) {
                $filefpds = $request->fac_per_dsignature;
                $filename = time() . '.' . $filefpds->getClientOriginalExtension();
                $filefpds->move(public_path('complaint/images'), $filename);
                $complaint['fac_per_dsignature'] = $filename;
            }
                // Unresolved and Re assign
                if(isset($request->status_id) && $request->status_id == 4) {
                    $complaint->update([
                        "engineer_id" => 0,    
                        "status_id" => $request->status_id,
                        "engineer_complaint_id" => $request->engineer_complaint_id,
                        "engineer_out_time" => $request->engineer_out_time,
                        "engineer_out_date" => $request->engineer_out_date,
                        "engineer_out_address" => $request->engineer_out_address,
                        "engineer_out_remarks" => $request->engineer_out_remarks,
                        "engineer_time_duration" => $request->engineer_time_duration,
                        "is_assigned" => 0,
                        "factory_person" => $request->factory_person,
                        "fac_per_mobile" => $request->fac_per_mobile,
                        "fac_per_designation" => $request->fac_per_designation,
                        "engineer_video" => $complaint['engineer_video'],
                        "engineer_audio" => $complaint['engineer_audio'],
                        "engineer_image" => $complaint['engineer_image'],
                        "fac_per_dsignature" => $complaint['fac_per_dsignature'],
                        "is_reassign" => 1,
                        "not_resolved_by" => $complaint->engineer_id,
                    ]);
                                if (isset($complaint) && !empty($complaint)) {
                                    $admin = User::whereHas('roles', fn ($q) =>
                                            $q->where('name', 'Service Team Leader')
                                        )
                                        ->whereHas('machineTypes', fn ($q) =>
                                            $q->where('main_machine_type', $main_machine_type)
                                        )
                                        ->where('is_leader', 1)
                                        ->where('is_active', 1)
                                        ->orderBy('id', 'desc') // or created_at
                                        ->first();
                                    if (!empty($admin->device_token)) {

                                        $title = 'Reasign Complaint';
                                        $body = 'Reassign Complaint No: ' . $request->id;
                                        $token = $admin->device_token;
                                        //$type = 'engineer';
                                        $type = $main_machine_type;

                                        if ($title != "" && $body != "" && $token != "") {
                                            // Get the response from the helper function
                                            // sendNotification($title, $body, $token);
                                            //$this->notificationService->sendNotification($title, $body, $token);
                                            $this->notificationService->sendAlarmNotification($title, $body, $token, $type, $complaint->id);
                                        }
                                    }
                                }
                } else {
                    $complaint->update([
                        "status_id" => $request->status_id,
                        "engineer_complaint_id" => $request->engineer_complaint_id,
                        "engineer_out_time" => $request->engineer_out_time,
                        "engineer_out_date" => $request->engineer_out_date,
                        "engineer_out_address" => $request->engineer_out_address,
                        "engineer_out_remarks" => $request->engineer_out_remarks,
                        "engineer_time_duration" => $request->engineer_time_duration,
                        "factory_person" => $request->factory_person,
                        "fac_per_mobile" => $request->fac_per_mobile,
                        "fac_per_designation" => $request->fac_per_designation,
                        "engineer_video" => $complaint['engineer_video'],
                        "engineer_audio" => $complaint['engineer_audio'],
                        "engineer_image" => $complaint['engineer_image'],
                        "fac_per_dsignature" => $complaint['fac_per_dsignature'],
                    ]);
                    //$jntcomplaint = Complaint::where("id", $request->id)->first();
                    $jntcomplaint = JointComplaint::where(['joint_eng_id' => $useridn, 'complaint_id' => $request->id])->first();
                    $jntcomplaint->update([
                        "joint_eng_status" => $request->status_id,
                        "joint_eng_out_time" => $request->engineer_out_time,
                        "joint_eng_out_date" => $request->engineer_out_date,
                        "joint_eng_out_address" => $request->engineer_out_address,
                        "joint_eng_remarks" => $request->engineer_out_remarks
                    ]);
                }    
            } else {
                $jntcomplaint = JointComplaint::where(['joint_eng_id' => $useridn, 'complaint_id' => $request->id])->first();
                $jntcomplaint->update([
                    "joint_eng_status" => $request->status_id,
                    "joint_eng_out_time" => $request->engineer_out_time,
                    "joint_eng_out_date" => $request->engineer_out_date,
                    "joint_eng_out_address" => $request->engineer_out_address,
                    "joint_eng_remarks" => $request->engineer_out_remarks
                ]);
                $complaint['status_id'] = (int) $request->joint_eng_status;
                $complaint['engineer_out_time'] = $jntcomplaint->joint_eng_out_time;
                $complaint['engineer_out_date'] = $jntcomplaint->joint_eng_out_date;
                $complaint['engineer_out_address'] = $jntcomplaint->joint_eng_out_address;
                $complaint['engineer_out_remarks'] = $jntcomplaint->joint_eng_remarks;
            }

        $engineerDetail = User::find($complaint->engineer_id);

        // Admin push notification
        if (isset($complaint) && $complaint->engineer_id != "" && $request->status_id != 4) {
            //$admin = User::find('178');
            // do not remove admin id from DB
            $admin = User::whereHas('roles', fn ($q) =>
                                            $q->where('name', 'Service Team Leader')
                                        )
                                        ->whereHas('machineTypes', fn ($q) =>
                                            $q->where('main_machine_type', $main_machine_type)
                                        )
                                        ->where('is_leader', 1)
                                        ->where('is_active', 1)
                                        ->orderBy('id', 'desc') // or created_at
                                        ->first();

            if (!empty($admin->device_token)) {
                // Send push notification
                if ($request->status_id == 2) {
                    $title = 'Complaint In process';
                    $body = $engineerDetail->name . ' Complaint no ' . $complaint->complaint_no . ' still in process';
                }
                if ($request->status_id == 3) {
                    $title = 'Complaint is closed';
                    $body = $engineerDetail->name . ' has closed Complaint no ' . $complaint->complaint_no;
                }
                $token = $admin->device_token;

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    $this->notificationService->sendNotification($title, $body, $token);
                }
            }
        }

        // Party push notification
        if (isset($complaint) && $complaint->party_id != "" && $request->status_id != 4) {
            $party = Party::find($complaint->party_id);

            if (!empty($party->device_token)) {
                // Send push notification
                if ($request->status_id == 2) {
                    $title = 'Complaint In process';
                    $body = 'Complaint no ' . $complaint->complaint_no . ' is in process';
                }
                if ($request->status_id == 3) {
                    $title = 'Complaint closed';
                    $body = 'Complaint has been closed, complain no ' . $complaint->complaint_no;
                }
                $token = $party->device_token;

                if ($title != "" && $body != "" && $token != "") {
                    // Get the response from the helper function
                    // sendNotification($title, $body, $token);
                    $this->notificationService->sendNotification($title, $body, $token);
                }
            }
        }

        return response()->json([
            "success" => true,
            "message" => "Complaint fetched successfully",
            "data" => $complaint,
        ], 200);
    }

    public function complaintTypes(Request $request)
    {
        if($request->main_machine_type == 1){
            $productData = Product::where('id', $request->product_type_id)->first();
            $complaintTypes = ComplaintType::where(['main_machine_type' => $request->main_machine_type, 'machine_type' => $productData->product_type_id])->get();
        } else {
            $complaintTypes = ComplaintType::where('main_machine_type', $request->main_machine_type)->get();
        }
        return response()->json([
            "success" => true,
            "message" => "Complaint Types fetched successfully",
            "data" => $complaintTypes,
        ], 200);
    }

    public function statuses(Request $request)
    {
        $statuses = Status::get();
        return response()->json([
            "success" => true,
            "message" => "Statuses fetched successfully",
            "data" => $statuses,
        ], 200);
    }

    public function parties(Request $request)
    {
        $main_machine_type = $request->main_machine_type;
            $parties = Party::with('firms')->whereHas('partywiseMachines', function ($query) use ($main_machine_type) {
            $query->where('main_machine_type', $main_machine_type);
                })
            ->where('is_active', 1)->get();
        return response()->json([
            "success" => true,
            "message" => "Parties fetched successfully",
            "data" => $parties,
        ], 200);
    }

    public function employeeClockIn(Request $request)
    {
        $employee = Auth::user();
        $attendance = new EmployeeAttendence;
        $attendance->employee_id = $employee->id;
        $attendance->check_in = $request->check_in;
        $attendance->date = $request->date;
        $attendance->check_in_address = $request->check_in_address;
        $attendance->save();
        return response()->json([
            "success" => true,
            "message" => "Employee clocked in successfully",
            "data" => $attendance,
        ], 200);
    }

    public function employeeClockOut(Request $request, $id)
    {
        $employee = User::where("id", auth()->user()->id)->first();
        $attendance = EmployeeAttendence::where("id", $id)->first();
        if ($attendance->employee_id != $employee->id) {
            return response()->json([
                "success" => false,
                "message" => "Employee not found",
            ], 200);
        }
        $status = "absent";
        if ($employee->duty_start < $request->check_out) {
            $status = "late";
        } elseif ($employee->duty_start >= $request->check_out) {
            $status = "present";
        } elseif ($attendance->check_out < $employee->duty_end) {
            $status = "half_day";
        } else {
            $status = "absent";
        }
        $total_hours = Carbon::parse($attendance->check_in)->diffInHours(Carbon::parse($attendance->check_out));
        $attendance->total_hours = $total_hours;
        $attendance->employee_id = $employee->id;
        $attendance->check_out = $request->check_out;
        $attendance->check_out_address = $request->check_out_address;
        $attendance->status = $status;
        $attendance->note = $request->note;
        $attendance->save();
        return response()->json([
            "success" => true,
            "message" => "Employee clocked out successfully",
            "data" => $attendance,
        ], 200);
    }

    public function months()
    {
        $months = Months::where('tag', 1)->get();

        return response()->json([
            "success" => true,
            "message" => "All months list",
            "data" => $months,
        ], 200);
    }

    public function areas()
    {
        $areas = Area::get();
        return response()->json([
            "success" => true,
            "message" => "Areas fetched successfully",
            "data" => $areas,
        ], 200);
    }

    public function leadStage()
    {
        $leadStage = LeadStage::get();
        return response()->json([
            "success" => true,
            "message" => "Lead stage fetched successfully",
            "data" => $leadStage,
        ], 200);
    }

    public function products(Request $request)
    {
        $main_machine_type = $request->main_machine_type;
        //$products = Product::get();
        $products = Product::where('product_group_id', $main_machine_type)->get();
        return response()->json([
            "success" => true,
            "message" => "Products fetched successfully",
            "data" => $products,
        ], 200);
    }

    public function productsTypeSalse()
    {
        $products = Product::where('product_type_id', 1)->get();
        return response()->json([
            "success" => true,
            "message" => "Products type salse list fetched successfully",
            "data" => $products,
        ], 200);
    }

    public function salesPerson(Request $request)
    {
        //$is_salse_emb = isset($request->is_salse_emb) && $request->is_salse_emb == 1 ? 1 : 0;
        //$is_salse_cir = isset($request->is_salse_cir) && $request->is_salse_cir == 1 ? 1 : 0;
        $main_machine_type = isset($request->main_machine_type) ? $request->main_machine_type : 1;
        //dd($main_machine_type);
        //$role = Role::where('name', 'Sales Person')->first();
        $salesPersons = User::with('roles')->role('Sales Person')
        ->whereHas('machineTypes', function ($query) use ($main_machine_type) {
        $query->where('main_machine_type', $main_machine_type);
        })
        ->where('is_active', 1)
        ->groupBy('users.id', 'users.name')
        ->get();

           // $salesPersons = User::whereHas('roles', function ($q) use ($role) {
           //     $q->where('id', $role->id);
           // })->get();
        if (count($salesPersons) == 0) {
            return response()->json([
                "success" => false,
                "message" => "Sales Person not found",
                "data" => [],
            ]);
        } else {
            return response()->json([
                "success" => true,
                "message" => "Sales Persons fetched successfully",
                "data" => $salesPersons,
            ], 200);
        }
    }

    public function deviceToken(Request $request)
    {
        if ($request->role == 'customer') {
            $party = Party::findOrFail($request->id)->update(['device_token' => $request->device_token]);
            if ($party) {
                return response()->json([
                    "success" => true,
                    "message" => "Party updated successfully",
                    "data" => $party,
                ], 200);
            }
        } else {
            $user = User::findOrFail($request->id)->update(['device_token' => $request->device_token]);
            if ($user) {
                return response()->json([
                    "success" => true,
                    "message" => "User updated successfully",
                    "data" => $user,
                ], 200);
            }
        }
        return response()->json([
            "success" => false,
            "message" => "Something went wrong",
            "data" => [],
        ]);
    }

    public function checkHoliday(Request $request)
    {
        $holiday = Holiday::where('date', $request->date)->first();
        if (!empty($holiday)) {
            return response()->json([
                "success" => true,
                "message" => "Holiday get successfully",
                "data" => $holiday,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Working Day",
                "data" => [],
            ]);
        }
    }

    public function holidayList(Request $request)
    {
        $holidaylist = Holiday::get();
        if (!empty($holidaylist)) {
            return response()->json([
                "success" => true,
                "message" => "Holiday list get successfully",
                "data" => $holidaylist,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Working Day",
                "data" => [],
            ]);
        }
    }

    public function customerFeedback(CustomerFeedbackRequest $request) {
        try {
            $customer = CustomerFeedback::create($request->all());

            return response()->json([
                "success" => true,
                "message" => "Feedback stored successfully.",
                "data" => $customer,
            ], 200);
        } catch (\Throwable $th) {
            // Handle error and return failure response
            return response()->json([
                'success' => false,
                'message' => 'Failed to store feedback. Please try again later.',
                'error' => $th->getMessage(),
            ], 500); // HTTP status 500 for internal server error
        }
    }

    public function isEngIn(Request $request)
    {
        $attendtl = Attendtl::where("engineer_id", $request->id)->where(["in_date" => date('Y-m-d'), 'ap' => 'P'])->first();
        if (!empty($attendtl)) {
            return response()->json([
                "success" => true,
                "message" => "Attendance fetched successfully",
                "data" => $attendtl,
            ], 200);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Engineer is not in yet",
                "data" => (object)[],
            ]);
        }
    }
}
