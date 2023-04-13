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
        $this->token=$args['token'] ??'';
        $this->confirmado=$args['confirmado'] ??'';
    }
}