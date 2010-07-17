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
    
    protected $rss_actions = array('open','list');
    
    protected $rss_default_action = 'list';
    
    protected $css = array();
    
    protected $default_action = 'list';
    
    protected $folder = 'posts';
    
    protected $template = 'default';
    
    private $title = 'Posts';
    private $description = 'A list of all posts';
    
    
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
            //$this->view->assign('tinymce',true);
            $this->css[]='new';
            
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
                FB::log($post);
                $this->newPost($post);
            }else{
                header('Location:'.$this->view->base_path.'posts/open/'.$post->getName());
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
        $this->view->assign('action','posts-open');
        
        $this->folder .='/open';
        $this->title = $post['title'];
        $this->description = $post['summary'];
        
        $this->css[] = 'posts';
        $this->css[]='highlighter';
        $this->js[]='highlighter';
        $this->js[]='validator';
        $this->js[]='posts';
        
        if ($post['js']) $this->js[]=str_replace('.js','',$post['js']);
        if ($post['css']) $this->css[]=str_replace('.css','',$post['css']);
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
        
        $this->css[]='new';
        $this->view->assign('post',$post->getPost());
        $this->view->assign('tags',$tags);
        $this->view->assign('edit',true);
       // $this->view->assign('tinymce',true);
        
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
        
        header('Location:'.$this->view->base_path.'posts/open/'.$id);
    }
    
    protected function comment(){
        $id = $this->router->getFolder(3);
        
        if (!$id) $this->goHome();
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
                }
                header('Location:'.$this->view->base_path.'posts/open/'.$id);
            break;
            
            case 'delete':
                if (!$this->user->isAdmin()){
                    $this->listAll();
                    return;
                }
                
                $id = $this->router->getFolder(3);
                $comment = new Comment(array('action'=>'delete','id'=>$id));
                $comment->execute();
                $this->goHome();
            break;
            default:
                $this->goHome();
        }
    }
    
    public function getTitle(){return $this->title;}
    public function getDescription(){return $this->description;}
}