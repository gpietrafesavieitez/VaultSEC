<?php header("Content-Type: text/html; charset=utf-8");

class File{

    private $isDir = false;
    private $checksum = null;
    private $path = null;
    private $name = null;
    private $mimetype = null;
    private $size = 0;
    private $modified = null;
    
    public function __construct($path){
        if(file_exists($path)){
            $this->path = $path;
            if(is_dir($this->path)){
                $this->isDir = true;
            }else if(is_file($this->path)){
                $this->isDir = false;
                $this->checksum = hash_file("md5", $this->path);
            }
            $this->name = basename($this->path);
            $this->mimetype = mime_content_type($this->path);
            $this->size = sizeFormat(filesize($this->path));
            $this->modified = time_elapsed_string("@" . filemtime($this->path));
        }
    }

    public function getChecksum(){
        return $this->checksum;
    }

    public function setChecksum($checksum){
        $this->checksum = $checksum;
        return $this;
    }

    public function getPath($full = false){
        if($full){
            return $this->path;
        }
        return substr($this->path, strlen(HOME));
    }

    public function setPath($path){
        $this->path = $path;
        return $this;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
        return $this;
    }

    public function getMimetype(){
        return $this->mimetype;
    }

    public function setMimetype($mimetype){
        $this->mimetype = $mimetype;
        return $this;
    }

    public function getSize(){
        return $this->size;
    }

    public function setSize($size){
        $this->size = $size;
        return $this;
    }

    public function getModified(){
        return $this->modified;
    }

    public function setModified($modified){
        $this->modified = $modified;
        return $this;
    }

    public function getIsDir(){
        return $this->isDir;
    }

    public function setIsDir($isDir){
        $this->isDir = $isDir;
        return $this;
    }

}

?>