<?php
    /**
    * Modelo: UsuarioPDO
    * @author Óscar Llamas Parra - oscar.llapar@educa.jcyl.es - https://github.com/OscarLlaPar
    * @since 21/12/2021 
    * @version 1.0 
    * Última modificación: 11/01/2022
    */
    class UsuarioPDO implements UsuarioDB{
        /**
         * Comprobación de la existencia previa de un usuario y de si su contraseña
         * es correcta en la base de datos.
         * 
         * @param String $codigoUsuario Código del usuario a comprobar.
         * @param String $password Contraseña del usuario a comprobar.
         * @return Object|boolean Devuelve el objeto únicamente con la FechaHoraUltimaConexion
         * si el usuario existe y la contraseña es correcta, y false en caso contrario.
         */
        public static function validarUsuario($codigoUsuario, $password) {
            /*
             * Query de selección del usuario según su código y contraseña, de modo
             * que valida tanto la existencia del usuario como que la contraseña
             * introducida sea correcta.
             */
            $sSelect = <<<QUERY
                SELECT * FROM T01_Usuario
                WHERE T01_CodUsuario='{$codigoUsuario}' AND
                T01_Password=SHA2("{$codigoUsuario}{$password}", 256);
            QUERY;

            $oResultado = DBPDO::ejecutarConsulta($sSelect);
            $oDatos = $oResultado->fetchObject();
            
            $oUsuario = new Usuario($oDatos->T01_CodUsuario, $oDatos->T01_Password, $oDatos->T01_DescUsuario, $oDatos->T01_NumConexiones, time(), $oDatos->T01_FechaHoraUltimaConexion, $oDatos->T01_Perfil);
            
            if($oUsuario){
                self::registrarUltimaConexion($codigoUsuario);
                return $oUsuario;
            }
            /*
             * Si no existe, devuelve false.
             */
            else{
                return false;
            }
        }

        /**
         * Inserción, registro de un usuario en la base de datos.
         * 
         * @param String $codigoUsuario Código del usuario que se va a registrar.
         * @param String $password Contraseña del usuario que se va a registrar.
         * @param String $descUsuario Descripción (nombre y apellidos) del usuario que se va a registrar.
         * @return PDOStatement Resultado del insert.
         */
        public static function altaUsuario($codigoUsuario, $password, $descUsuario){
            /*
             * Query de inserción del usuario, dados su código de usuario, contraseña
             * y descripción.
             */
            $sInsert = <<<QUERY
                INSERT INTO T01_Usuario(T01_CodUsuario, T01_Password, T01_DescUsuario, T01_FechaHoraUltimaConexion) VALUES
                ("{$codigoUsuario}", SHA2("{$codigoUsuario}{$password}", 256), "{$descUsuario}", UNIX_TIMESTAMP());
            QUERY;

            return DBPDO::ejecutarConsulta($sInsert);
        }

        /**
         * 
         */
        public static function modificarUsuario(){

        }

        /**
         * 
         */
        public static function borrarUsuario(){

        }

        /**
         * Dado un código de usuario, modifica la fecha-hora de última conexión, añade 
         * una conexión más, y devuelve el objeto.
         * @param String $codigoUsuario Código del usuario al que registrar una nueva conexión.
         * @return PDOStatement Resultado del update.
         */
        public static function registrarUltimaConexion($codigoUsuario){
            $sUpdate = <<<QUERY
                UPDATE T01_Usuario SET T01_NumConexiones=T01_NumConexiones+1,
                T01_FechaHoraUltimaConexion = unix_timestamp()
                WHERE T01_CodUsuario='{$codigoUsuario}';
            QUERY;

            return DBPDO::ejecutarConsulta($sUpdate);
        }

        /*
         * 
         */
        public static function buscaUsuariosporDesc(){

        }

        /**
         * Dado un código de usuario, comprueba que no exista ya en la base de datos.
         * @param String $codigoUsuario Código del usuario al que registrar una nueva conexión.
         * @return PDOStatement Resultado del update.
         */
        public static function validarCodNoExiste($codigoUsuario){
            $sSelect = <<<QUERY
                SELECT T01_CodUsuario FROM T01_Usuario
                WHERE T01_CodUsuario='{$codigoUsuario}';
            QUERY;

            return DBPDO::ejecutarConsulta($sSelect); 
        }

        /*
         * 
         */
        public static function creaOpinion(){

        }

        /*
         * 
         */
        public static function modificaOpinion(){

        }

        /*
         * 
         */
        public static function borraOpinion(){

        }
    }