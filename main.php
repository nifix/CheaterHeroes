<?php

class CheaterHeroes
{

    private $data = "";
    private $file = "";
    
    /* Configuration BEGINS */ 
    private $decodeFile = "./data/chDump.txt";
    private $encodeFile = "./data/chModified.txt";
    /* Configuration ENDS */
    
    private $splitter = "Fe12NAfA3R6z4k0z";
    private $salty = "af0ik392jrmt0nsfdghy0";
    
    /*  CheaterHeroes Constructor 
     *  
     *  string $file : path to the file you want to modify. 
     *  bool $mode : true to decode the savefile to chDump.txt, false to encode chDump.txt after modification
     */
    
    public function __construct( $file, $mode )
    {
        if (!is_dir("data")) mkdir("data");
        if ( !$file )  throw new Exception( "You have to supply a path to the savefile." );
        else if ( $mode )
        {
            $this->setFile( $file );
            $this->chDecode( );
        } else if ( !$mode ) {
            $this->setFile( $file );
            $this->chEncode( );
        }
    }
 
    /*  Supposed to decode the savefile.
     *  and exports it.
     */
    
    private function chDecode( )
    {
        $data = $this->returnFile( 3 );
        $data = explode( $this->splitter, $data );
        $splitd = str_split( $data[0] );
        
        $dec = array( );
        $i = 0;
        while ( $i < count( $splitd ) ) {
            $dec[$i / 2] = $splitd[$i];
            $i += 2;
        }
        
        $dec = implode( "", $dec );
        $dec = base64_decode( $dec );
        $this->exportToFile( $dec, 1 );       
    }
    
    private function chEncode( )
    {
        $data = $this->returnFile( 2 );
        $data = base64_encode( $data );
        $datahash = $data;
        $splitd = str_split( $data );
        
        $dec = array( );
        $i = 0;
        while ( $i < count( $splitd ) )
        {
            $dec[] = $splitd[$i];
            $dict = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
            $rndl = substr( $dict,rand( 0, strlen($dict) - 1), 1 );
            $dec[] = $rndl;
            $i++;
        }
        $dec[] = $this->addHash( $datahash );
        $dec = implode( "", $dec );
        $this->exportToFile( $dec, 2 );       
    }
    
    private function addHash( $data )
    {
        $str = $data . $this->salty;
        $rtn = $this->splitter . hash( "md5", $str );
        return $rtn;
    }
    
    /*  Function to open and return the content of a file
     *  string $file : sets the global var $data to the content of the file
     */

    private function returnFile( $mode )
    {
        switch ( $mode ) {
            case 1:
                $file = $this->encodeFile;
                break;
            case 2:
                $file = $this->decodeFile;
                break;
            case 3:
                $file = $this->getFile( );
                break;
        }
        
        if ( file_exists( $file ) )
        {
            $fh = fopen( $file, "r+" );
            $content = stream_get_contents( $fh );
            fclose( $fh );
            return $content;
        } else {
            throw new Exception( "File doesn't exist or is not readable." );
        }        
    }
 
    /*  Function to export a data to the file, depending on the mode (decode/encode)
     *  string $data : data to export
     *  int $mode : mode (1 to decode, 2 to encode)
     */
    private function exportToFile( $data, $mode )
    {
        switch ( $mode ) {
            case 1:
                $file = $this->decodeFile;
                break;
            case 2:
                $file = $this->encodeFile;
                break;
        }
        
        try
        {
            $fh = fopen( $file, "w+");
            fwrite( $fh, $data );
            fclose( $fh );
            echo "Exported Successfully to " . $file . ", you should be able to import it.";
        } catch ( Exception $e )
        {
            echo "Exception : " . $e->getMessage() . "\n";
        }
    }
    /*  Random getters and setters */
    
    private function setData( $data ) { $this->data = $data; }
    private function getData( ) { return $this->data; }
    
    private function setFile( $file ) { $this->file = $file; }
    private function getFile( ) { return $this->file; }
}

$chdecode = new CheaterHeroes("./data/clickerHeroSave.txt", false);

?>