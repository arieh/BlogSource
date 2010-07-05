<header>
    <h1><a href='posts/open/<?php echo $this->post['name'];?>'><?php echo $this->post['title']?></a></h1>
</header>
<aside id='info'>
    <p><dfn>Created On: </dfn><time datetime='<?php echo date('Y-m-d',$this->post['created'])?>'><?php echo date('D, M d Y',$this->post['created'])?></time></p>
    <?php if ($this->post['updated']):?>
        <p><dfn>Updated On: </dfn><time datetime='<?php echo date('Y-m-d',$this->post['updated'])?>'><?php echo date('D, M d Y',$this->post['updated'])?></time></p>
    <?php endif;?>
    <h3>Tags:</h3>
    <ul class='tags'>
    <?php foreach ($this->tags as $tag):?>
        <li><a href='tags/<?php echo $tag['name']?>'><?php echo $tag['name']?></a></li>
    <?php endforeach;?>
    </ul>
</aside>
<article id='content' class='box'>
<?php echo $this->post['content']?>
</article>
<?php if ($this->user->isAdmin()):?>
<p>
    <a href='posts/edit/<?php echo $this->post['id'];?>'>Edit</a>
</p>
<?php endif;?>
<section id='comments'>
    <header>
        <h1>Comments</h1>
    </header>

    <?php if ($this->comments):?>
        <?php foreach($this->comments as $comment):?>
        <article class='comment box' id='cmt<?php echo $comment['id']?>'>
            <header>
                <?php
                    $hash = md5(strtolower(trim($comment['email'])));
                ?>
                <img src="http://www.gravatar.com/avatar/<?php echo $hash?>?d=identicon&amp;=70" height='70' width='70' alt='gravatar image' />
                <h1><?php echo $comment['title'];?></h1>
                <h2>by <?php echo $comment['name']?></h2>
                <small><dfn>Created On: </dfn><time datetime='<?php echo date('Y-m-d',$comment['created'])?>'><?php echo date('D, M d Y',$this->post['created'])?></time></small>
            </header>
            <div class='content'>
                <?php echo $comment['content']?>
            </div>
            <?php if ($this->user->isAdmin()):?>
                <a href='posts/comment/delete/<?php echo $comment['id'];?>'>delete</a>
            <?php endif;?>
        </article>
        <?php endforeach;?>
    <?php endif;?>
    <form id='new-comment' action='posts/comment/new/<?php echo $this->post['id']?>' method='post' class='box'>
    <fieldset>
        <legend><span>New Comment</span></legend>
        <label for='c_title'>Title:</label>
        <input type='text' name='comment[title]' id='c_title' class='required' />
        
        <label for='c_name'>Name:</label>
        <input type='text' name='comment[name]' id='c_name' class='required' />
        
        <label for='c_email'>Email: <small>will not be published</small></label>
        <input type='text' name='comment[email]' id='c_email' class='required validate-email' />
        
        <label for='c_content'>Content:</label>
        <textarea id='c_content' name='comment[content]' rows='20'  cols='60'></textarea>
        
        <input type='submit' value='Send' />
    </fieldset>
    </form>
</section>