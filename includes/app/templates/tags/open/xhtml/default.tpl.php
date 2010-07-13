<header>
<h1><a href='tags/open/<?php echo $this->tag['name']?>'><?php echo $this->tag['name']?></a></h1>
</header>
<?php foreach ($this->posts as $post):?>
    <article class='post box'>
        <header>
            <h1><a href='posts/open/<?php echo $post['name'];?>'><?php echo $post['title']?></a></h1>
        </header>
            <blockquote class='summary' cite='posts/open/<?php echo $post['name'];?>'>
                <p><?php echo $post['summary']?></p>
            </blockquote>
    </article>
<?php endforeach;?>
