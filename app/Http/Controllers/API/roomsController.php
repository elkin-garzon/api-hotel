<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\rooms;
use App\Models\hotels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Utils\Response;

class roomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::success(rooms::get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(self::validateData($request) === true){
            $data = new rooms();
            $data->hotel_id = $request->hotel_id;
            $data->count = $request->count;
            $data->room_type = $request->room_type;
            $data->lodging = $request->lodging;
            if($data->save()){
                return Response::success($data);
            }else{
                return response()->json('error', 500);
            }
        }else{
            return self::validateData($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\rooms  $rooms
     * @return \Illuminate\Http\Response
     */
    public function show(rooms $rooms)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\rooms  $rooms
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if(self::validateData($request) === true){
            $data =rooms::find($id);
            $data->hotel_id = $request->hotel_id;
            $data->count = $request->count;
            $data->room_type = $request->room_type;
            $data->lodging = $request->lodging;
            if($data->save()){
                return Response::success($data);
            }else{
                return response()->json('error', 500);
            }
        }else{
            return self::validateData($request);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\rooms  $rooms
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(rooms::destroy($id)){
            return Response::deleteSuccess();
        }else{
            return response()->json('error', 500);
        }
    }

    
    public function validateData($request)
    {
        $validator = Validator::make($request->all(), [
            'hotel_id' => 'required | numeric |exists:hotels,id',
            'count' => 'required | numeric | min:1',
            'room_type' => 'required | string | min:3',
            'lodging' => 'required | string | min:6',
        ]);

        $count = rooms::where('hotel_id', $request->hotel_id)->sum('count');
        $countHotel = hotels::find($request->hotel_id)->room_count;

        if($validator->fails() == 0){
            if(intval($count)+ intval($request->count) <= intval($countHotel)){
                return true;
            }else{
                return response()->json(Response::getErrorsValidate('Hotel no cuenta con la capacidad de ingresar ' . intval($request->count). ' habitaciones, solo cuenta con capacidad para '. intval($countHotel)-intval($count) .' habitaciones más'), 500);
            }
            
        }else{
            return response()->json(Response::getErrorsValidate($validator->errors()), 500);
        }
    }
}
