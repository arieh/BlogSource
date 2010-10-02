<?php
require_once dirname(__FILE__) . '/../classes/BlogTestCase.php';
require_once dirname(__FILE__) . '/../../includes/lib/models/AbstractModel.class.php';
require_once dirname(__FILE__) . '/../../includes/app/models/Post.class.php';

class PostTest extends BlogTestCase{
    protected $sql = 'posts';
    
    public function testFamiliarPosts(){
    	$this->setUpDB();
    	$options = array(
    	   'action' => 'open'
    	   ,'id' => 12
    	);
    	$post = new Post($options);
    	$post->execute();
    	$familiar = $post->getFarmiliars();
    	
    	$this->assertTrue(is_array($familiar));
    	
    	$this->assertEquals(3,count($familiar));
    }
}