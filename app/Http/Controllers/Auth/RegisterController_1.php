<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use App\Device;
// use Illuminate\Support\Facades\Auth;

class RegisterController_1 extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'serial'   => ['required', 'string', 'min:4', 'max:20']
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $data){

        $datosFinales = [];
        $errores = [];
        $datos = $_POST;
        $mail = $_POST["email"];
        $user = User::where("email","=",$mail)->get()->first();
        

        foreach ($datos as $posicion => $dato) {
          $datosFinales[$posicion] = trim($dato);
        }
        $device = Device::where("serial_number","=",$datosFinales["serial"])->get()->first();
        if ($datosFinales["name"] == "") {
          $errores["name"] = "Hubo error en el nombre porque esta vacio";
        } else if (ctype_alpha($datosFinales["name"]) == false) {
          $errores["name"] = "Hubo error en el nombre porque tiene caracteres que no son alfabeticos";
        }
    
        // if ($datosFinales["apellido"] == "") {
        //   $errores["apellido"] = "Hubo error en el apellido porque esta vacio";
        // } else if (ctype_alpha($datosFinales["apellido"]) == false) {
        //   $errores["apellido"] = "Hubo error en el apellido porque tiene caracteres que no son alfabeticos";
        // }
    
        if ($datosFinales["password"] == "") {
          $errores["password"] = "Hubo error en la contraseña porque esta vacia";
        }
        else if (strlen($datosFinales["password"]) < 8) {
            $errores["password"] = "La contraseña debe tener al menos 8 caracteres";
        }
        else if (!preg_match("#[0-9]+#", $datosFinales["password"])) {
            $errores["password"] = "La contraseña debe tener al menos un numero!";
        }
        else if (!preg_match("#[A-Z]+#", $datosFinales["password"])) {
            $errores["password"] = "La contraseña debe tener al menos una mayuscula.";
        }
        else if (!preg_match("#[a-z]+#", $datosFinales["password"])) {
            $errores["password"] = "La contraseña debe tener al menos una minuscula";
        }
    
        if ($datosFinales["password_confirmation"] == "") {
          $errores["password_confirmation"] = "Hubo error en la confirmacion de contraseña porque esta vacia";
        }
        else if ( $datosFinales["password_confirmation"] != $datosFinales["password"] ) {
          $errores["password_confirmation"] = "La contraseña no verifica";
        }
    
        if ($datosFinales["email"] == "") {
          $errores["email"] = "El email esta vacio";
        }
        else if ( filter_var($datosFinales["email"], FILTER_VALIDATE_EMAIL) == false) {
          $errores["email"] = "El email no es un email válido";
        } else if ($user != NULL ) {
          $errores["email"] = "El email ya está en uso";
        }

        if($device == null){
            $errores["serial"] = "El dispositivo no fue conectado aún a Internet o no existe.";
        }
    
        if(empty($errores)) {
            $user = new User();
            $user->name =  $datosFinales['name'];
            $user->email = $datosFinales['email'];
            $user->password = Hash::make($datosFinales['password']);
            $user->save();
            $device->user_id = $user->id;
            $device->save();
            Auth::attempt(['email' => $user->email, 'password' => $datosFinales['password']]);
            return redirect('home');
        } else{
            $vac = compact('errores');
            return view('auth\register', $vac);
        }
        
    }
}
