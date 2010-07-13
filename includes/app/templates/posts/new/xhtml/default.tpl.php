<?php
if ($this->post instanceof Post){
    $post = array(
        'title'=>$this->post->getOption('title')
        ,'summary'=>$this->post->getOption('summary')
        ,'content'=>$this->post->getOption('content')
        ,'tags'=>$this->post->getOptions('tags')
    );
    $tags = $post->getOptions('tags');
}elseif (isset($this->post) && $this->post){
    $post = $this->post;
    $tags = $this->tags;
}
else $post = false;
?>
<header>
<h1><?php
    if (isset($this->edit) && $this->edit){
        echo "Edit Post";
        $action = 'posts/update/'.$post['id'];
    }
    else{
        echo "New Post";
        $action = 'posts/create';
    }
?></h1>
</header>
<form action='<?php echo $action;?>' method='post' enctype="multipart/form-data">
<fieldset>
    <label for='title'>Title:</label>
    <input type='text' name='post[title]' id='title' <?php
        if ($post) echo "value = '{$post['title']}'";
    ?> />
    
    <label for='summary'>Summary:</label>
    <textarea type='text' name='post[summary]' id='summary' rows='30' cols='60' /><?php
        if ($post) echo "{$post['summary']}";
    ?></textarea>
    
    <label for='p_js'>JS File:</label>
    <input type='file' name='js' id='p_js' />
    
    <label for='p_js'>CSS File:</label>
    <input type='file' name='css' id='p_css' />
    
    <label for='tags'>Tags:</label>
    <input type='text' name='post[tags]' id='tags' <?php
        if ($post) echo "value = '{$tags}'";
    ?> />
    <label for='p_content'>Content:</label>
    <textarea id='p_content' name='post[content]' cols='60' rows='30'><?php
        if ($post) echo $post['content'];
    ?></textarea>
    <input type='submit' value='submit' />
</fieldset>
</form>