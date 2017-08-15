<?php
class log{
    // Categoria:
    // INFO
    // WARNING
    // ERROR
    // FATAL
    private static $categoria='';
    private static $evento='';
    private static $detalle='';
    private static $usuario='Sistema';

    public static function Add($cat, $e){
        try{
            self::Init();
            // entrada al log.
            if(isset($_SESSION['username']))
                $usuario=$_SESSION['username'];
            self::$categoria= $cat;
            self::$evento= $e;
            self::Write();
            // Valida tamaño del log.
            self::SizeCheck();
        }
        catch(Exception $e){}
    }

    public static function AddD($cat, $e, $d){
        try{
            self::Init();
            // entrada al log.
            if(isset($_SESSION['username']))
                $usuario=$_SESSION['username'];
            self::$categoria= $cat;
            self::$evento= $e;
            self::$detalle= $d;
            self::Write();
            // Valida tamaño del log.
            self::SizeCheck();
        }
        catch(Exception $e){}
    }

    public static function Read(){
        try{}
        catch(Exception $e){}
    }

    private static function Write(){
         if( $xml = file_get_contents( '../../log/xLog.xml') ) {
            $doc = new DomDocument( '1.0' );
            $doc->formatOutput = true;
            $doc->loadXML( $xml, LIBXML_NOBLANKS );                
            // etiqueta root.
            $root = $doc->getElementsByTagName('EventLogger')->item(0);
            // etiqueta elog.
            $log = $doc->createElement('eLog');
            $log->setAttribute('Categoria', self::$categoria);
            $log->setAttribute('Fecha', date("Y-m-d"));
            $log->setAttribute('Hora', date("H:i:s"));       
            $log->setAttribute('Usuario', self::$usuario);
            $log->setAttribute('Evento', self::$evento);
            if(self::$detalle!='')
                $log->setAttribute('Detalle', self::$detalle);
            //
            $root= $root->appendChild( $log );
            $doc->save('../../log/xLog.xml');
            self::$detalle='';
        }
    }

    private static function Init(){
        try{    
            // Directorios.
            if (!file_exists('../../log/')) {
                mkdir('../../log/', 0777, true);
            }  
            if (!file_exists('../../log/Historico')) {               
                mkdir('../../log/Historico', 0777, true);
            }        
            // Archivo log.
            if (!file_exists('../../log/xLog.xml')) {
                // Si no existe el archivo, lo crea.
                // require_once('Globals.php');                
                $doc = new DOMDocument('1.0', 'utf-8');
                $doc->formatOutput = true;
                $root = $doc->createElement('EventLogger');
                $root->setAttribute('Desarrollado', 'Operaciones DCTI');
                $root->setAttribute('Aplicacion', 'Bitacora DCTI');
                $root->setAttribute('Fecha_Inicio', date("Y-m-d H:i:s") );                                
                $root = $doc->appendChild($root);
                $doc->save('../../log/xLog.xml');
            }                        
        }
        catch(Exception $e){
        }
    }

    private static function SizeCheck(){
        try{       
            if (file_exists('../../log/xLog.xml')) {
                if(filesize('../../log/xLog.xml')/1024>2000) // 2 MB - 2.000 KB
                {
                    // Si el log es de mas de 10 MB, lo cierra y crea nuevo.
                    // Ultima entrada.
                    self::$categoria='INFO';
                    self::$evento='Cierre del log';
                    self::$detalle='';
                    self::$usuario='Sistema';
                    self::Write();
                    // Renombra.
                    rename("../../log/xLog.xml", "../../log/Historico/xLog_". date("Ymd")  .".xml");
                    // Nuevo archivo.
                    self::Init();
                }
            }
        }
        catch(Exception $e){
        }
    }
}
?>