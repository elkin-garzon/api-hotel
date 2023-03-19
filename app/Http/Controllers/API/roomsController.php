<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\rooms;
use App\Models\hotels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Utils\Response;
use Exception;

class roomsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::success(rooms::select('hotels.name', 'rooms.id', 'rooms.count', 'rooms.room_type', 'rooms.lodging', 'rooms.hotel_id')->join('hotels', 'hotels.id', '=', 'rooms.hotel_id')->get());
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

        try {
            $validator = Validator::make($request->all(), [
                'hotel_id' => 'required | numeric |exists:hotels,id',
                'count' => 'required | numeric | min:1',
                'room_type' => 'required | string | min:3',
                'lodging' => 'required | string | min:3',
            ]);
    
            if($validator->fails() == 0){

                $room_type = rooms::where([
                    ['room_type', '=',$request->room_type],
                    ['hotel_id', '=',$request->hotel_id]
                ])->get();
        
                $lodging = rooms::where([
                    ['lodging', '=',$request->lodging],
                    ['hotel_id', '=',$request->hotel_id]
                ])->get();

                if($request->method() === 'POST'){
                    if(count($room_type) > 0){
                        throw new Exception('Tipo de habitacion ya se encuentra creado para el hotel');
                    }
        
                    if(count($lodging) > 0){
                        throw new Exception('Acomodación ya se encuentra creado para el hotel');
                    }
                    $count = rooms::where('hotel_id', $request->hotel_id)->sum('count');
                    $countHotel = hotels::find($request->hotel_id)->room_count;
                }else{
                    $room =rooms::find($request->id);
                    $count = rooms::where('hotel_id', $request->hotel_id)->sum('count')-$room->count;;
                    $countHotel = hotels::find($request->hotel_id)->room_count;
                }
                
                if(intval($count)+ intval($request->count) <= intval($countHotel)){
                    return true;
                }else{
                    return response()->json(Response::getErrorsValidate('Hotel no cuenta con la capacidad de ingresar ' . intval($request->count). ' habitaciones, solo cuenta con capacidad para '. intval($countHotel)-intval($count) .' habitaciones más'), 500);
                }
                
            }else{
                return response()->json(Response::getErrorsValidate($validator->errors()), 500);
            }

        } catch (\Throwable $th) {
            return response()->json(Response::getErrorsValidate($th->getMessage()), 500);
        }       
    }
}
