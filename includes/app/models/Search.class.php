<?php
class SearchErrors{
    const NO_PHRASE=1;
    const SHORT_PHRASE=2;
    const BAD_PHRASE =3;
}

class Search extends AbstractModel{
    
    const POSTS_PER_PAGE = 10;
    
    protected $_actions = array(
        'posts'=>'posts'
    );
    
    protected $_default_action = 'posts';
    
    protected $_posts = array();
    
    protected $_count = 0;
    
    protected function posts(){
        $value = strtolower($this->getOption('value'));
        $start = (int)$this->getOption('start');
        
        if (!$value) $this->setError(SearchErrors::NO_PHRASE);
        if (strlen($value)<2) $this->setError(SearchErrors::SHORT_PHRASE);
        if (!preg_match('/[A-Za-z]+/',$value)) $this->setError(SearchErrors::BAD_PHRASE);
        
        if ($this->isError()) return;
        
        $this->searchMatch($value,$start);
    }
    
    private function searchMatch($value,$start){
        $sql = "SELECT COUNT(`id`) as `c` FROM `posts_search` where (match(summary,nohtml) against (?))";
        $row = $this->db->queryRow($sql,array($value));
        $this->_count = (int)$row['c'];
        
        if (!$this->_count){
            $this->searchNoMatch($value,$start);
            return;
        }
        
        $sql = "SELECT
                    p.id,
                    p.name,
                    p.title,
                    UNIX_TIMESTAMP(p.created) as `created`,
                    UNIX_TIMESTAMP(p.updated) as `updated`,
                    p.summary,
                    match(ps.summary,ps.nohtml) against (?)  as rating
                from posts as p
                inner join posts_search as ps on ps.id=p.id
                where (match(ps.summary,ps.nohtml) against (?))
                ORDER BY rating DESC
                LIMIT {$start},".self::POSTS_PER_PAGE;
        $this->_posts = $this->db->queryArray($sql,array($value,$value));
        
        
    }
    
    private function searchNoMatch($value,$start){
        $sql = "SELECT
                    p.id,
                    p.name,
                    p.title,
                    UNIX_TIMESTAMP(p.created) as `created`,
                    UNIX_TIMESTAMP(p.updated) as `updated`,
                    p.summary,
                    1 as rating
                from posts as p
                inner join posts_search as ps on ps.id=p.id
                WHERE LCASE(ps.summary) LIKE ? OR LCASE(ps.nohtml) LIKE ?
                ORDER BY p.created DESC
                LIMIT {$start},".self::POSTS_PER_PAGE;
        
        $this->_posts = $this->db->queryArray($sql,array("%{$value}%","%{$value}%"));
        
        $sql = "SELECT COUNT(`id`) as c FROM posts_search WHERE summary LIKE ? OR nohtml LIKE ?";
        $row = $this->db->queryRow($sql,array("%{$value}%","%{$value}%"));
        $this->_count = (int)$row['c'];
    }
}