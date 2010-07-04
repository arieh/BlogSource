<h1><a href='posts/open/<?php echo $this->post['name'];?>'><?php echo $this->post['title']?></a></h1>
<div id='info'>
    <p><dfn>Created On: </dfn> <?php echo date('r',$this->post['created'])?></p>
    <?php if ($this->post['updated']):?>
        <p><dfn>Updated On: </dfn> <?php echo date('r',$this->post['updated'])?></p>
    <?php endif;?>
    <h3>Tags:</h3>
    <ul class='tags'>
    <?php foreach ($this->tags as $tag):?>
        <li><a href='tags/<?php echo $tag['name']?>'><?php echo $tag['name']?></a></li>
    <?php endforeach;?>
    </ul>
</div>
<div id='content' class='box'>
<?php echo $this->post['content']?>
</div>

<h2>Comments</h2>
<?php if ($this->comments):?>
<ol>
<?php foreach($this->comments as $comment):?>
    <li class='comment box'>
        <div class='header'>
            <?php
                $hash = md5(strtolower(ltrim($comment['email'])));
            ?>
            <img src="http://www.gravatar.com/avatar/<?php echo $hash?>?d=identicon&amp;=70" height='70' width='70' alt='gravatar image' />
            <h3><?php echo $comment['title'];?></h3>
            <h4>by <?php echo $comment['name']?></h4>
            <small>posted on: <?php echo date('r',$comment['created'])?></small>
        </div>
        <div class='content'>
            <?php echo $comment['content']?>
        </div>
        <?php if ($this->user->isAdmin()):?>
            <a href='posts/comment/delete/<?php echo $comment['id'];?>'>delete</a>
        <?php endif;?>
    </li>
<?php endforeach;?>
</ol>
<?php endif;?>
<form id='new-comment' action='posts/comment/new/<?php echo $this->post['id']?>' method='post' class='box'>
<fieldset>
    <legend><span>New Comment</span></legend>
    <label for='c_title'>Title:</label>
    <input type='text' name='comment[title]' id='c_title' />
    
    <label for='c_name'>Name:</label>
    <input type='text' name='comment[name]' id='c_name' />
    
    <label for='c_email'>Email: <small>will not be published</small></label>
    <input type='text' name='comment[email]' id='e_email' />
    
    <label for='c_content'>Content:</label>
    <textarea id='c_content' name='comment[content]' rows='20'  cols=60'></textarea>
    
    <input type='submit' value='Send' />
</fieldset>
</form>