<?php

class AutoLoadAllClassesInsideIncludes{

    /**
    * The class loader is used to load to all class files inside includes.
    * @return type void
    */
    public static function loader(){
        $dir = scandir(__DIR__.'/_includes');
        foreach ($dir as $files){
            if ($files == "." || $files == ".."){
                continue;
            }
            else {
                if (explode('.' , $files , 2)[1] == "class.php"){
                    include_once "_includes/$files";
                } else if (explode('.' , $files , 2)[1] == "Class.php"){
                    include_once "_includes/$files";
                } else {
                    throw new Exception("File name is awkward.");
                    continue;
                }
            }
            
        }
    }
}

AutoLoadAllClassesInsideIncludes::loader();