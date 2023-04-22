<?php
namespace Model;
class Usuario extends ActiveRecord{
    protected static $tabla='usuarios';
    protected static $columnasDB=['id','nombre','email','password','token','confirmado'];

    //Creando el Modelo de Usuarios
    public function __construct($args=[]){
        $this->id=$args['id'] ??null;
        $this->nombre=$args['nombre'] ??'';
        $this->email=$args['email'] ??'';
        $this->password=$args['password'] ??'';
        $this->password2=$args['password2'] ??'';
        $this->token=$args['token'] ??'';
        $this->confirmado=$args['confirmado'] ?? 0;
    }
    //Validacion para cuentas nuevaas
    public function validarNuevaCuenta(){
        //Validar nombre de usuario  u obligatorio
        if(!$this->nombre){
            self::$alertas['error'][]='El Nombre de Usuario es Obligatorio';
        }

        //Validar email de usuario u obligatorio
        if(!$this->email){
            self::$alertas['error'][]='El Email de Usuario es Obligatorio';
        }

        //Validar password de usuario u obligatorio
        if(!$this->password){
            self::$alertas['error'][]='El Password de Usuario es Obligatorio';
        }

        //Validar extension del password
        if(strlen ($this->password)<6){
            self::$alertas['error'][]='El Password por lo menos debe ser de 6 caracterres';
        }
        //Validar sin los dos password son diferentes o iguales
        if($this->password !== $this->password2){
            self::$alertas['error'][]='Los Passwords son diferentes';
        }
        return self::$alertas;
    }

    //Validar un Email
    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][]='El Email es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][]='Email no valido';
        }
        return self::$alertas;
    }

    //Valida el Password
    public function validarPassword(){
        //Validar password de usuario u obligatorio
        if(!$this->password){
            self::$alertas['error'][]='El Password de Usuario es Obligatorio';
        }

        //Validar extension del password
        if(strlen ($this->password)<6){
            self::$alertas['error'][]='El Password por lo menos debe ser de 6 caracterres';
        }
        return self::$alertas;
    }

    //Hashea el password del usuario
    public function hashPassword(){
$this->password=password_hash($this->password, PASSWORD_BCRYPT);
    }

    //Generar un token para el usuario
    public function crearToken(){
        $this->token=uniqid();
    }
}