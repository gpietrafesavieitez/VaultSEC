<?php header("Content-Type: text/html; charset=utf-8");

class User{

    private $uid = null;
    private $displayname = null;
    private $password = null;
    private $type = null;
    private $creation = null;

    public function getUid(){
        return $this->uid;
    }

    public function setUid($uid){
        $this->uid = $uid;
        return $this;
    }

    public function getDisplayname(){
        return $this->displayname;
    }

    public function setDisplayname($displayname){
        $this->displayname = $displayname;
        return $this;
    }

    public function getPassword(){
        return $this->password;
    }

    public function setPassword($password){
        $this->password = $password;
        return $this;
    }

    public function getType(){
        return $this->type;
    }

    public function setType($type){
        $this->type = $type;
        return $this;
    }

    public function getCreation(){
        return $this->creation;
    }

    public function setCreation($creation){
        $this->creation = $creation;
        return $this;
    }
    
}