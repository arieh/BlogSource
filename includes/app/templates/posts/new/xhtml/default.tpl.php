<?php
if ($this->post instanceof Post){
    $post = array(
        'title'=>$this->post->getOption('title')
        ,'summary'=>$this->post->getOption('summary')
        ,'content'=>$this->post->getOption('content')
        ,'tags'=>$this->post->getOptions('tags')
    );
}elseif (isset($this->post)) $post = $this->post;
else $post = false;
?>
<h1><?php
    if (isset($this->edit) && $this->edit) echo "Edit Post";
    else echo "New Post";
?></h1>

<form action='posts/create' method='post'>
<fieldset>
    <label for='title'>Title:</label>
    <input type='text' name='post[title]' id='title' <?php
        if ($post) echo "value = '{$post['title']}'";
    ?> />
    
    <label for='summary'>Summary:</label>
    <input type='text' name='post[summary]' id='summary' <?php
        if ($post) echo "value = '{$post['summary']}'";
    ?> />
    
    <label for='tags'>Tags:</label>
    <input type='text' name='post[tags]' id='tags' <?php
        if ($post) echo "value = '{$post['tags']}'";
    ?> />
    <label for='p_content'>Content:</label>
    <textarea id='p_content' name='post[content]' cols='60' rows='30'><?php
        if ($post) echo "value = '{$post['content']}'";
    ?></textarea>
    <input type='submit' value='submit' />
</fieldset>
</form>