<?php
class SearchController extends AbstractSubController{
    protected $actions = array(
        'posts'=>'posts'
    );
    
    protected $css = array('search');
    
    protected $default_action = 'posts';
    
    protected $folder = 'search';
    
    protected $template = 'default';
    
    private $title = 'Search Posts';
    private $description = 'Search Results For ';
    
    protected function posts(){
        $value = $this->router->getFolder(1);
        
        if (!$value) $value = $this->router->getParam('value');
        
        $start = $this->router->getFolder(2);
        $model = new Search(array('value'=>$value,'start'=>$start));
        $model->execute();
        
        $this->view->assign('value',htmlentities($value));
        $this->view->assign('posts',$model->getPosts());
        $this->view->assign('count',$model->getCount());
        $this->view->assign('start',$start);
        $this->description .= htmlentities($value);
    }
    
    public function getTitle(){return $this->title;}
    public function getDescription(){return $this->description;}
}