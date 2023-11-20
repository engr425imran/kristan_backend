<?php

namespace App\Http\Controllers\Zoom;

use App\Http\Controllers\Controller;
use App\Traits\ZoomJWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MeetingController extends Controller
{
    use ZoomJWT;

    const MEETING_TYPE_INSTANT = 1;
    const MEETING_TYPE_SCHEDULE = 2;
    const MEETING_TYPE_RECURRING = 3;
    const MEETING_TYPE_FIXED_RECURRING_FIXED = 8;

    public function list(Request $request) { 
        // $key = env('ZOOM_API_KEY', '');
        // $secret = env('ZOOM_API_SECRET', '');
        // $payload = [
        //     'iss' => $key,
        //     'exp' => strtotime('+1 minute'),
        // ];
        // return \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
     }
    public function create(Request $request) {
        $validator = Validator::make($request->all(), [
        'topic' => 'required|string',
        'start_time' => 'required|date',
        'agenda' => 'string|nullable',
    ]);
    
    if ($validator->fails()) {
        return [
            'success' => false,
            'data' => $validator->errors(),
        ];
    }
    $data = $validator->validated();

    $path = 'users/me/meetings';
    $response = $this->zoomPost($path, [
        'topic' => $data['topic'],
        'type' => self::MEETING_TYPE_SCHEDULE,
        'start_time' => $this->toZoomTimeFormat($data['start_time']),
        'duration' => 30,
        'agenda' => $data['agenda'],
        'settings' => [
            'host_video' => true,
            'participant_video' => true,
            'waiting_room' => true,
        ]
    ]);


    return [
        'success' => $response->status() === 201,
        'data' => json_decode($response->body(), true),
    ];
    }
    public function get(Request $request, string $id) { /**/ }
    public function update(Request $request, string $id) { /**/ }
    public function delete(Request $request, string $id) { /**/ }
}
