<h1><a href='tags/open/<?php echo $this->tag['name']?>'><?php echo $this->tag['name']?></a></h1>
<ol>
<?php foreach ($this->posts as $post):?>
    <li class='post box'>
        <h2><a href='posts/open/<?php echo $post['name'];?>'><?php echo $post['title']?></a></h2>
        <blockquote cite='posts/open/<?php echo $post['name'];?>'>
            <?php echo $post['summary']?>
        </blockquote>
    </li>
<?php endforeach;?>
</ol>