<?php

namespace App\Http\Controllers;
use Throwable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
// use App\Models\Ajustes;
// use App\Lib\LibCore;
use LdapRecord\Connection;
use Illuminate\Support\Facades\Auth;


class AjustesController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Declaración de variables
    |--------------------------------------------------------------------------
    |
    */
    public $LibCore;

    /*
    |--------------------------------------------------------------------------
    | Inicializar variables comunes
    |--------------------------------------------------------------------------
    |
    */
    public function __construct(){
        // $this->LibCore = new LibCore();
    }

    /*
    |--------------------------------------------------------------------------
    | Inicial
    |--------------------------------------------------------------------------
    |
    | Carga solo vista con HTML
    | Todo es controlado por JS ajustes.js
    |
    */
    public function index()
    {


        $connection = new Connection([
             'hosts'    => ['ldap.forumsys.com'],
             'username' => 'cn=read-only-admin,dc=example,dc=com',
             'password' => 'password',
        ]);


        try {
            $connection->connect();

            echo "Successfully connected!";
        } catch (\LdapRecord\Auth\BindException $e) {
            $error = $e->getDetailedError();

            echo $error->getErrorCode();
            echo $error->getErrorMessage();
            echo $error->getDiagnosticMessage();
        }

        $credentials = [
            'uid' => 'tesla',
            'password' => 'password',
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Returns true:
            $user instanceof \LdapRecord\Models\Model;
        }

        // if(!\Schema::hasTable('ajustes')){
        //     return json_encode(array("b_status"=> false, "vc_message" => "No se encontro la tabla ajustes"));
        // }

        // return view('ajustes');
    }


    /*
    |--------------------------------------------------------------------------
    | Agrega o modificar registro
    |--------------------------------------------------------------------------
    | 
    | Modifica el registro solo si manda el parametro '$request->id'
    | @return json
    |
    */
    public function set_ajustes(Request $request)
    {
        if(!\Schema::hasTable('ajustes')){
            return json_encode(array("b_status"=> false, "vc_message" => "No se encontro la tabla Ajustes"));
        }

        $data=[ 'honda' => $request->honda,
                'civic' => $request->civic,
                'motores' => $request->motores,
                'vtec' => $request->vtec,
                'hector' => $request->hector,
                'paquito' => $request->paquito,
                'vCampo7_ajustes' => $request->vCampo7_ajustes,
                'vCampo8_ajustes' => $request->vCampo8_ajustes,
                'vCampo9_ajustes' => $request->vCampo9_ajustes,
                'vCampo10_ajustes' => $request->vCampo10_ajustes,
        ];

        sleep(1);
        // Si ya existe solo se actualiza el registro
        if (isset($request->id)){
            $ajustes = Ajustes::where( ['id' => $request->id])->update($data );
            return json_encode(array("b_status"=> true, "vc_message" => "Actualizado correctamente..."));
        }else{ // Nuevo registro
            $ajustes = Ajustes::create( $data );
            return json_encode(array("b_status"=> true, "vc_message" => "Agregado correctamente..."));
        }

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener un registro por id
    |--------------------------------------------------------------------------
    | 
    | @return json
    |
    */
    public function get_ajustes_by_id(Request $request)
    {
        $data= Ajustes::select('honda'
                                    , 'civic'
                                    , 'motores'
                                    , 'vtec'
                                    , 'hector'
                                    , 'paquito'
                                    , 'vCampo7_ajustes'
                                    , 'vCampo8_ajustes'
                                    , 'vCampo9_ajustes'
                                    , 'vCampo10_ajustes'
        )->where('id', $request->id)->get();
        sleep(1);
        return json_encode(array("b_status"=> true, "data" => $data));
    }

    /*
    |--------------------------------------------------------------------------
    | Datatable registro especial como se requiere en js
    |--------------------------------------------------------------------------
    | 
    | @return json
    |
    */
    public function get_ajustes_by_datatable()
    {
        if(!\Schema::hasTable('ajustes')){
            return json_encode(array("data"=>"" ));
        }

        $data= Ajustes::select("id"
                                    , "honda"
                                    , "civic"
                                    , "motores"
                                    , "vtec"
                                    , "hector"
                                    , "paquito"
                                    , "vCampo7_ajustes"
                                    , "vCampo8_ajustes"
                                    , "vCampo9_ajustes"
                                    , "vCampo10_ajustes"
        );

        $total  = $data->count();

        foreach ($data->where('b_status', 1)->get() as $key => $value) {
            $arr[]= array(    $value->id
                            , $value->honda
                            , $value->civic
                            , $value->motores
                            , $value->vtec
                            , $value->hector
                            , $value->paquito
                            , $value->vCampo7_ajustes
                            , $value->vCampo8_ajustes
                            , $value->vCampo9_ajustes
                            , $value->vCampo10_ajustes
                            , $value->id
            );
        }

        $json_data = array(
            "draw"            => intval( 10 ),   
            "recordsTotal"    => intval( $total ),  
            "recordsFiltered" => intval( $total ),
            "data"            => isset($arr) && is_array($arr)? $arr : ''
        );

        if($total > 0){
            return json_encode($json_data);
        }else{
            return json_encode(array("data"=>"" ));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar registro por id
    |--------------------------------------------------------------------------
    | 
    | @return id
    |
    */
    public function delete_ajustes(Request $request)
    {
        $id=$request->id;
        Ajustes::where('id', $id)->update(['b_status' => 0]);
        return $id;
    }

    /*
    |--------------------------------------------------------------------------
    | Desahacer el registro que se elimino
    |--------------------------------------------------------------------------
    | 
    | @return id
    |
    */
    public function undo_delete_ajustes(Request $request)
    {
        $id=$request->id;
        Ajustes::where('id', $id)->update(['b_status' => 1]);        
        return $id;
    }
}
