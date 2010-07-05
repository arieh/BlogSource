<?php
class PostErrors{
    const BAD_NAME = 1;
    const BAD_ID = 2;
}

class Post extends AbstractModel{
    protected $_actions = array(
        'list' => 'listAll'
        ,'open' => 'open'
        ,'new' => 'new'
        ,'create' => 'create'
        ,'edit'=> 'open'
        ,'update'=>'update'
    );
    
    protected $_default_action = 'list';
    
    protected $_posts = array();
    
    protected $_count = 0;
    
    protected $_comments = array();
    
    protected $_tags = array();
    
    protected $_id = 0;
    
    protected function listAll(){
        $start = $this->getOption('start');
        if (!$start) $start = 0;
        
        $sql = "SELECT `posts`.*,tags.`name` as `t_name`,tags.`id` as `t_id`
                FROM
                    posts
                Right Join posts_has_tags ON posts.id = posts_has_tags.posts_id
                Inner Join tags ON tags.id = posts_has_tags.tags_id
                ORDER BY `posts`.`created` DESC
                LIMIT $start,10";
        
        $posts = $this->db->queryArray($sql);
        
        if (!$posts) return;

        foreach($posts as $post){
            if (!array_key_exists($post['id'],$this->_posts)){
                $this->_posts[$post['id']] = $post;
                $this->_posts[$post['id']];
                $this->_posts[$post['id']]['comments'] = $this->db->count('comments',array('posts_id'=>$post['id']));
                $this->_posts[$post['id']]['tags'] = array();
            }
            if (!array_key_exists($post['t_id'],$this->_posts[$post['id']]['tags'])){
                $this->_posts[$post['id']]['tags'][$post['t_id']] = array('id'=>$post['t_id'],'name'=>$post['t_name']);
            }
        }
        
        $this->_count = $this->db->count('posts');
    }
    
    protected function open(){
        $name = $this->getOption('name');
        if ($name && !$this->doesPostExists($name)) $this->setError(PostErrors::BAD_NAME);
        elseif (!$name){
            $id = $this->getOption('id');
            if (!$this->doesPostExists($id)) $this->setError(PostErrors::BAD_ID);
        }
        
        if ($this->isError()) return;
        
        $sql = "SELECT
                    posts.id,
                    posts.name,
                    posts.title,
                    posts.content,
                    UNIX_TIMESTAMP(posts.created) as `created`,
                    UNIX_TIMESTAMP(posts.updated) as `updated`,
                    posts.summary,
                    comments.id as `c_id`,
                    comments.title as `c_title`,
                    comments.name as `c_name`,
                    comments.content as `c_content`,
                    comments.email as `c_email`,
                    UNIX_TIMESTAMP(comments.created) as `c_created`,
                    tags.name as `t_name`,
                    tags.id as `t_id`
                FROM
                    posts
                LEFT Join comments ON comments.posts_id = posts.id
                LEFT Join posts_has_tags ON posts.id = posts_has_tags.posts_id
                LEFT Join tags ON posts_has_tags.tags_id = tags.id
                WHERE ";
        
        if ($name) $post = $this->db->queryArray($sql.= "`posts`.`name` = ?",array($name));
        else $post = $this->db->queryArray($sql.= "`posts`.`id` = ?",array($id));
        
        $data = $this->orgenizePostData($post);
        $this->_posts[]=$data['post'];
        $this->_comments = $data['comments'];
        $this->_tags = $data['tags'];
    }
    
    protected function create(){
        $title = htmlentities($this->getOption('title'));
        $name = $this->generateName($title);
        $content = $this->getOption('content');
        $content = preg_replace('/<p>\\W*&nbsp;\\w*<\/p>/iu','',$content);
        $summary = htmlentities($this->getOption('summary'));
        $tags = $this->getOption('tags');
        $sql = "INSERT INTO `posts` (`name`,`title`,`content`,`non-html`,`summary`,`created`) VALUES(?,?,?,?,?,NOW())";
        $this->db->update($sql,array($name,$title,$content,strip_tags($content),$summary));
        $this->id = $this->db->getLastId();
        $this->insertTags($this->id,explode(',',$tags));
    }
    
    protected function update(){
        $id = $this->getOption('id');
        if (!$this->doesPostExists($id)) $this->setError(PostErrors::BAD_ID);
        
        if ($this->isError()) return;
        
        $title = $this->getOption('title');

        $content = $this->getOption('content');
        $content = preg_replace('/<p>\\W*&nbsp;\\w*<\/p>/iu','',$content);
        $summary = $this->getOption('summary');
        $tags = $this->getOption('tags');
        
        $sql = "UPDATE `posts` SET `title`=?,`content`=? WHERE `id`=?";
        $this->db->update($sql,array($title,$content,$id));
        $this->emptyTags($id);
        $this->insertTags($id,explode(',',$tags));
    }
    
    private function doesPostExists($value){
        if (is_numeric($value)) $key = 'id';
        else $key = 'name';
        
        return $this->db->count('posts',array($key=>$value));
    }
    
    private function generateName($title){
        $title = strtolower(str_replace(' ','-',$title));
        $i=0;
        $name = $title;
        while ($this->doesNameExists($name)){
            $name = $title.$i++;
        }
        return $name;
    }
    
    private function doesNameExists($name){
        return $this->db->count('posts',array('name'=>$name));
    }
    
    private function orgenizePostData($data){
        $post = array();
        $post['id'] = $data[0]['id'];
        $post['name'] = $data[0]['name'];
        $post['title']= $data[0]['title'];
        $post['content'] = $data[0]['content'];
        $post['created'] = $data[0]['created'];
        $post['updated'] = $data[0]['updated'];
        $post['summary'] = $data[0]['summary'];
        
        $comments = array();
        $tags = array();
        foreach ($data as $row){
            if ($row['c_id'] && !array_key_exists($row['c_id'],$comments)){
                $comment = array(
                    'id' => $row['c_id']
                    , 'name' => $row['c_name']
                    , 'email' => $row['c_email']
                    , 'title' => $row['c_title']
                    , 'content' => $row['c_content']
                    , 'created' => $row['c_created']
                );
                $comments[$row['c_id']] = $comment;
            }
            
            if (!array_key_exists($row['t_id'],$tags)){
                $tag = array(
                    'id'=>$row['t_id']
                    , 'name'=>$row['t_name']
                );
                $tags[$row['t_id']] = $tag;
            }
        }
        
        return array ('post'=>$post,'comments'=>$comments,'tags'=>$tags);
    }
    
    private function insertTags($id,$tags){
        $pairs = array();
        $sql = "INSERT INTO `posts_has_tags` (`posts_id`,`tags_id`) VALUES ";
        $sep = '';
        foreach ($tags as $tag){
            $tag = ucwords(strtolower(ltrim($tag)));
            $tag_id = $this->getTagId($tag);
            if (!$this->doesPostHaveTag($id,$tag_id)){
                $sql .= $sep . '(?,?)';
                $sep = ',';
                $pairs[]=$id;
                $pairs[]=$tag_id;
            }
        }
        $this->db->update($sql,$pairs);
    }
    
    private function getTagId($tag){
        $sql = "SELECT `id` FROM `tags` WHERE `name`=?";
        $row = $this->db->queryRow($sql,array($tag));
        if ($row['id']) return $row['id'];
        $this->db->update("INSERT INTO `tags`(`name`) VALUES(?)",array($tag));
        return $this->db->getLastId();
    }
    
    private function doesPostHaveTag($post,$tag){
        return $this->db->count('posts_has_tags',array('posts_id'=>$post,'tags_id'=>$tag));
    }
    
    private function emptyTags($id){
        $this->db->update("DELETE FROM `posts_has_tags` WHERE `posts_id`=?",array($id));
    }
}