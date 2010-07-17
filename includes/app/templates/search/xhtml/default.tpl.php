<header>
    <h1>Search Results For: <?php echo $this->value;?></h1>
</header>
<ol class='posts'>
<?php foreach ($this->posts as $post):?>
    <li>
    <article class='post box'>
        <header>
            <h1><a href="posts/<?php echo $post['name'];?>"><?php echo $post['title'];?></a></h1>
        </header>
        <blockquote cite="posts/<?php echo $post['name'];?>">
            <p>
                <?php echo $post['summary'];?>
            </p>
        </blockquote>
        <aside>
            <p><dfn>Created On: </dfn><time datetime='<?php echo date('Y-m-d',$post['created'])?>'><?php echo date('D, M d Y',$post['created'])?></time></p>
            <?php if ($post['updated']):?>
                <p><dfn>Updated On: </dfn><time datetime='<?php echo date('Y-m-d',$post['updated'])?>'><?php echo date('D, M d Y',$post['updated'])?></time></p>
            <?php endif;?>
        </aside>
    </article>
    </li>
<?php endforeach;?>
</ol>