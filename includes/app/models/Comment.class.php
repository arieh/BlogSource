<?php
class CommentErrors{
    const BAD_POST = 1;
    const NO_TITLE = 2;
    const NO_NAME = 3;
    const BAD_EMAIL = 4;
    const ROBOT = 100;
}

class Comment extends AbstractModel{
    protected $_actions = array(
        'create' => 'create'
        ,'delete' => 'delete'
    );
    
    protected function create(){
        if ($this->getOption('extra')){
            $this->setError(CommentErrors::ROBOT);
            return;
        }
        
        $post = $this->getOption('id');
        $title = trim($this->getOption('title'));
        $name = trim($this->getOption('name'));
        $email = trim($this->getOption('email'));
        $content = $this->getOption('content');
        
        
        if (!$this->doesPostExists($post)) $this->setError(CommentErrors::BAD_POST);
        if (strlen($title)==0) $this->setError(CommentErrors::NO_TITLE);
        if (strlen($name)==0) $this->setError(CommentErrors::NO_NAME);
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) $this->setError(CommentErrors::BAD_EMAIL);
        
        if ($this->isError()) return;
        
        $title = strip_tags($title);
        $name = strip_tags($name);
        $content = strip_tags($content,'<b><i><u>');
        
        $pattern = '/(\\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/iu';
        
        $content = preg_replace($pattern,'<a href="$1" rel="nofollow" target="_blank">$1</a>',$content);
        $content = "<p>".str_replace("\n","</p><p>",$content)."</p>";
        $sql = "INSERT INTO `comments`(`title`,`name`,`email`,`content`,`posts_id`,`created`) VALUES(?,?,?,?,?,NOW())";
        $this->db->update($sql,array($title,$name,$email,$content,$post));
        $this->_id = $this->db->getLastId();
    }
    
    protected function delete(){
        $id = $this->getOption('id');
        
        $this->db->update("DELETE FROM comments WHERE id=?",array($id));
    }
    
    private function doesPostExists($post){
        return $this->db->count('posts',array('id'=>$post));
    }
}