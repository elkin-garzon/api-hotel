<?php 
    namespace App\Utils;

    class Response{

        /*RESPUESTA CUANDO HAY ERRORES*/
		public static function getErrors($data){
			return [
                'status' => false,
                'error'  => $data,
            ];
		}

        /*RESPUESTA CUANDO HAY ERRORES EN VALIDACIONES*/
		public static function getErrorsValidate($data){
			return [
                'status' => false,
                'error'  => [$data]
            ];
		}


		/*RESPUESTA EXITOSA*/
		public static function success($data = ''){
			return [
                'status' => true,
                'data'  => $data
                
            ];
		}

		/*ELIMINAR*/
		public static function deleteSuccess(){
			return [
                'status' => true
            ];
		}

	}