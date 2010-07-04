<?php
class TagErrors{

}

class Tag extends AbstractModel{
    protected $_actions = array(
        'list' => 'listAll'
        ,'open' => 'open'
    );
    
    protected $_tags = array();
    
    protected $_posts = array();
    
    protected $_default_action = 'list';
    
    protected function listAll(){
        $tags = $this->db->queryArray('select * from tags');
        foreach($tags as &$tag) $tag['count'] = $this->db->count('posts_has_tags',array('tags_id'=>$tag['id']));
        $this->_tags =$tags;
    }
    
    protected function open(){
        $name = $this->getOption('name');
        $start = (int)$this->getOption('start');
        $start = $start ? 0 : $start;
        
        if (!$this->doesTagExists($name)) $this->setError(TagErrors::BAD_NAME);
        
        if ($this->isError()) return;
        
        $sql = "SELECT
                    tags.id,
                    tags.name,
                    posts.id as p_id,
                    posts.name as p_name,
                    posts.title as p_title,
                    posts.summary as p_summary
                FROM
                    tags
                Inner Join posts_has_tags ON tags.id = posts_has_tags.tags_id
                Inner Join posts ON posts_has_tags.posts_id = posts.id
                WHERE LCASE(tags.name) = ?
                ORDER BY posts.created DESC
                LIMIT $start,10";
        
        $data = $this->db->queryArray($sql,array(strtolower($name)));
        $this->_tags[] = array(
            'id'=>$data[0]['id']
            ,'name'=>$data[0]['name']
            ,'count'=>$this->db->count('posts_has_tags',array('tags_id'=>$data[0]['id']))
        );
        
        $posts = array();
        
        foreach ($data as $row){
            $posts[]= array(
                'id'=>$row['p_id']
                ,'name'=>$row['p_name']
                ,'title'=>$row['p_title']
                ,'summary'=>$row['p_summary']
            );
        }
        $this->_posts = $posts;
    }
    
    private function doesTagExists($tag){
        return $this->db->count('tags',array('name'=>$tag),true);
    }
}