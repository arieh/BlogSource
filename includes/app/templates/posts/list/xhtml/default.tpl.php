<header id='subs' class='box dark'>
    Javascript, Mootools, PHP, HTML and more
</header>

<?php foreach ($this->posts as $post):?>
    <article class='post box'>
        <header>
            <h1><a href='posts/open/<?php echo $post['name'];?>'><?php echo $post['title']?></a></h1>
        </header>
        <figure>
            <figcaption>Summary:</figcaption>
            <blockquote class='summary' cite='posts/open/<?php echo $post['name'];?>'><?php echo $post['summary']?></blockquote>
        </figure>
        <footer>
            <small>Comments: <?php echo $post['comments']?></small>
        </footer>
    </article>
<?php endforeach;?>

<aside id='tag-list' class='box dark'>
    <header>
        <h1>Tags</h1>
    </header>
    <ol>
    <?php foreach ($this->tags as $tag):?>
        <li class='tag'><a href='tags/open/<?php echo $tag['name'];?>'><?php echo $tag['name'];?></a> (<?php echo $tag['count'];?>)</li>
    <?php endforeach;?>
    </ol>
</aside>

<?php if ($this->user->isAdmin()):?><p class='clear'><a href='posts/new'>New Post</a></p><?php endif;?>