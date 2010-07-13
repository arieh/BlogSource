<?php
class TagsController extends AbstractSubController{
    protected $actions = array(
        'open'=>'open'
    );
    
    protected $css = array('tags');
    
    protected $default_action = 'open';
    
    protected $folder = 'tags';
    
    protected $template = 'default';
    
    private $title = 'Tags';
    private $description = '';
    
    protected function open(){
        $add = ($this->router->getFolder(1)=='open') ? 1 : 0;

        $name = $this->router->getFolder(1+$add);
        $start = $this->router->getFolder(2+$add);
        
        if (!$start) $start = 0;
        
        $tags = new Tag(array('action'=>'open','name'=>$name,'start'=>$start));
        $tags->execute();
        
        if ($tags->isError()){
          $this->goHome();
          return;
        }
        $tag = $tags->getTag();
        $this->view->assign('tag',$tag);
        $this->view->assign('posts',$tags->getPosts());
        $this->view->assign('start',$start);
        $this->view->assign('count',$tags->getCount());
        
        $this->folder .='/open';
        $this->title = $tag['name'] . " :: Tags";
        $this->description = "A list of posts for the tag {$tag['name']}";
    }
    
    public function getTitle(){return $this->title;}
    public function getDescription(){return $this->description;}
}