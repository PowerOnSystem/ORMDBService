<?php

/*
 * Copyright (C) PowerOn Sistemas
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace PowerOn\Utility;

/**
 * Lang Controla los lenguajes incluidos en el framework
 * @author Lucas Sosa
 * @version 0.1
 */
class Lang {
    /**
     * Coleccion de archivos de idioma de la aplicaicon
     * @var array
     */
    private static $_collection = [];
    /**
     * Configuración de la clase lenguaje
     * @var array
     */
    private static $_config = [
        'strict_mode' => FALSE
    ];
    
    const STRICT_MODE = 1;
    
    /**
     * Configura el manejo de la clase Lang
     */
    public static function configure() {
        $args = func_get_args();
        if (in_array(self::STRICT_MODE, $args)) {
            self::$_config['strict_mode'] = TRUE;
        }
    }

    /**
     * Carga un idioma solicitado
     * @param string $name Nombre del archivo requerido
     * @param string $request_lang [Opcional] Idioma específico
     * @return array Devuele un array con el idioma solicitado
     * @throws \Exception
     */
    public static function load($name, $request_lang = NULL, $path = NULL) {
        $lang = $request_lang ? $request_lang : (Config::exist('Global.lang') ? Config::get('Global.lang') : 'es');

        if ( !Hash::check(self::$_collection, $name . '.' . $lang) ) {
            $lang_file = ($path ? $path : dirname(dirname(dirname(dirname(dirname(__FILE__))))) . DIRECTORY_SEPARATOR . 'langs')
                    . DIRECTORY_SEPARATOR . $name . '.' . $lang . '.php';

            if ( !is_file($lang_file) ) {
                throw new \Exception(sprintf('No se encontr&oacute; el archivo (%s) de lenguaje.', $lang_file));
            }
            
            $lang_array = include $lang_file;
            if ( !is_array($lang_array) ) {
                throw new \DomainException(sprintf('El archivo (%s) debe retornar en un array', $lang_file));
            }

            self::$_collection = Hash::insert(self::$_collection, $name . '.' . $lang, $lang_array);
        }

        return self::$_collection[$name][$lang];
    }
    
    /**
     * Devuelve una cadena en un idioma específico
     * @param string $name Nombre de la cadena a obtener
     * @param string $request_lang [Opcional] El idioma específico a obtener
     * @return string La cadena en el idimoa requerido
     */
    public static function get($name, $request_lang = NULL) {
        $lang_name = $request_lang ? $request_lang : (Config::exist('Global.lang') ? Config::get('Global.lang') : 'es');
        $lang_split = explode('.', $name);
        $file_string = next($lang_split);
        $file_name = reset($lang_split);

        self::load($file_name, $request_lang);
        return Hash::get(self::$_collection, $file_name . '.' . $lang_name . '.' . $file_string);
    }

}
