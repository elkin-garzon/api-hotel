<?php 
    namespace App\Utils;

    class Response{

        /*RESPUESTA CUANDO HAY ERRORES*/
		public static function getErrors($data){
            return response()->json( [
                'status' => false,
                'error'  => $data,
            ], 500);
		}

        /*RESPUESTA CUANDO HAY ERRORES EN VALIDACIONES*/
		public static function getErrorsValidate($data){
            return response()->json( [
                'status' => false,
                'error'  => [$data]
            ], 500);
		}


		/*RESPUESTA EXITOSA*/
		public static function success($data = ''){
            return response()->json( [
                'status' => true,
                'data'  => $data
            ], 200);
		}

		/*ELIMINAR*/
		public static function deleteSuccess(){
            return response()->json( [
                'status' => true,
            ], 200);
		}

	}