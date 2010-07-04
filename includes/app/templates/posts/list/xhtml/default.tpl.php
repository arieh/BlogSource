<h2 id='subs' class='box dark'>
    Javascript, Mootools, PHP, HTML and more
</h2>
<ol class='posts'>
<?php foreach ($this->posts as $post):?>
    <li class='post box'>
        <h2><a href='posts/open/<?php echo $post['name'];?>'><?php echo $post['title']?></a></h2>
        <blockquote><?php echo $post['summary']?></blockquote>
        <small>Comments: <?php echo $post['comments']?></small>
    </li>
<?php endforeach;?>
</ol>
<div id='tag-list' class='box dark'>
<h2>Tags</h2>
<ol>
<?php foreach ($this->tags as $tag):?>
    <li class='tag'><a href='tags/open/<?php echo $tag['name'];?>'><?php echo $tag['name'];?></a>(<?php echo $tag['count'];?>)</li>
<?php endforeach;?>
</ol>
</div>
<?php if ($this->user->isAdmin()):?><p class='clear'><a href='posts/new'>New Post</a></p><?php endif;?>