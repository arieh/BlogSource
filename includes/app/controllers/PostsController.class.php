<?php
class PostsController extends AbstractSubController{
    protected $actions = array(
        'list' => 'listAll'
        ,'new' => 'newPost'
        ,'create'=>'create'
        ,'open' => 'open'
        ,'edit' => 'edit'
        ,'update' => 'update'
        ,'comment' => 'comment'
    );
    
    protected $css = array();
    
    protected $default_action = 'list';
    
    protected $folder = 'posts';
    
    protected $template = 'default';
    
    private $title = 'Posts';
    private $description = 'A list of all posts';
    
    public function __construct(Router $router, Savant3 $savant, $env = 'xhtml'){
        parent::__construct($router,$savant,$env);
        if ($env=='rss') $this->action = 'list';
    }
    
    
    protected function listAll(){
        if ($start = $this->router->getFolder(2)) $options = array('start'=>(int)$start);
        else $options = array();
        $post = new Post($options);
        $post->execute();

        $tags = new Tag();
        $tags->execute();
        
        $this->view->assign('posts',$post->getPosts());
        $this->view->assign('count',$post->getCount());
        $this->view->assign('tags',$tags->getTags());
        $this->view->assign('start',$post->getOption('start'));
        $this->view->assign('main',true);
        
        $this->folder .='/list';
    }
    
    protected function newPost($post=null){
        if ($this->user->isAdmin()){
            $this->folder .= '/new';
            $this->view->assign('tinymce',true);
            if ($post) $this->view->assign('post',$post);
            else $this->view->assign('post',false);
        }else $this->goHome();
    }
    
    protected function create(){
        if ($this->user->isAdmin()){
            $options = $this->router->post;
            $options['action']='create';
            $post = new Post($options);
            
            $post->execute();
            
            if ($post->isError()){
                $this->newPost($post);
            }else{
                $this->open($post->getId());
            }
        }else $this->folder .='/list';
    }
    
    protected function open($id=false){
        $options = array('action'=>'open','id'=>$id);
        
        if (!$id){
            $value = $this->router->getFolder(2);
            if (!$value) return $this->listAll();
            if (is_numeric($value)) $options['id']=$value;
            else $options['name']=$value;
        }
        
        $post = new Post($options);
        $post->execute();
        
        if ($post->isError()){
            return;
        }
        
        $comments = $post->getComments();
        $tags = $post->getTags();
        $post = $post->getPost();
        
        $this->view->assign('post',$post);
        $this->view->assign('tags',$tags);
        $this->view->assign('comments',$comments);
        
        $this->folder .='/open';
        $this->title = $post['title'];
        $this->descriptin = $post['summary'];
        
        $this->css[] = 'posts';
        $this->css[]='highlighter';
        $this->js[]='highlighter';
    }
    
    protected function edit(){
        if (!$this->user->isAdmin()){
          $this->listAll();
          return;
        }
        
        $id = $this->router->getFolder(2);
        $post = new Post(array('action'=>'edit','id'=>$id));
        $post->execute();
        
        if ($post->isError()){
            $this->listAll();
            return;
        }

        $t_tags = $post->getTags();
        $tags = '';
        $sep = '';
        foreach ($t_tags as $tag){
          $tags.=$sep.$tag['name'];
          $sep=',';
        }
        
        $this->view->assign('post',$post->getPost());
        $this->view->assign('tags',$tags);
        $this->view->assign('edit',true);
        $this->view->assign('tinymce',true);
        
        $this->folder .= '/new';
        $this->title = "Edit Post";
        $this->description = "Edit Post";
    }
    
    protected function update(){
        if (!$this->user->isAdmin()){
            $this->listAll();
            return;
        }
        $options = $this->router->post;
        $id = $options['id'] = $this->router->getFolder(2);
        $options['action'] = 'update';
        
        $post = new Post($options);
        $post->execute();
        
        if ($post->isError()){
            $this->view->assign('edit',true);
            $this->newPost($post);
            return;
        }
        
        $this->open($id);
    }
    
    protected function comment(){
        $id = $this->router->getFolder(3);
        
        if (!$id) $this->listAll();
        switch ($this->router->getFolder(2)){
            case 'new':
                $opt = $this->router->comment;
                $opt['id']=$id;
                $opt['action'] = 'create';
                
                $comment = new Comment($opt);
                $comment->execute();
                
                if ($comment->isError()){
                    if ($comment->isError(CommentErrors::BAD_POST)){
                        $this->listAll();
                    }
                    return;
                }
                $this->open($id);
            break;
            
            case 'delete':
                if (!$this->user->isAdmin()){
                    $this->listAll();
                    return;
                }
                
                $id = $this->router->getFolder(3);
                $comment = new Comment(array('action'=>'delete','id'=>$id));
                $comment->execute();
                
            default:
                $this->listAll();
        }
    }
    
    public function getTitle(){return $this->title;}
    public function getDescription(){return $this->description;}
}