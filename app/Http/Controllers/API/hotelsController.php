<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\hotels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Utils\Response;

class hotelsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Response::success(hotels::orderBy('name')->get());
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
            $data = new hotels();
            $data->name = $request->name;
            $data->address = $request->address;
            $data->city = $request->city;
            $data->nit = $request->nit;
            $data->room_count = $request->room_count;
            if($data->save()){
                return Response::success($data);
            }else{
                return Response::getErrors($data);
            }
        }else{
            return self::validateData($request);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\hotels  $hotels
     * @return \Illuminate\Http\Response
     */
    public function show(hotels $hotels)
    {

        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\hotels  $hotels
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        if(self::validateData($request) === true){
            $data = hotels::find($id);
            $data->name = $request->name;
            $data->address = $request->address;
            $data->city = $request->city;
            $data->nit = $request->nit;
            $data->room_count = $request->room_count;
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
     * @param  \App\Models\hotels  $hotels
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(hotels::destroy($id)){
            return Response::deleteSuccess();
        }else{
            return response()->json('error', 500);
        }
    }


    public function validateData($request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required | string | min:6 |unique:hotels',
            'address' => 'required | string | min:4',
            'city' => 'required | string | min:3',
            'nit' => 'required | string | min:6',
            'room_count' => 'required | numeric | min:1'
        ]);

        if($validator->fails() == 0){
            return true;
        }else{
            return response()->json(Response::getErrorsValidate($validator->errors()), 500);
        }
    }
}
