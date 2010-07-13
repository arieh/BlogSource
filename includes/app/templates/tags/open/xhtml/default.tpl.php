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

<?php if ($this->count>10):?>
<aside id='paging'>
<?php if ($this->start>=10){
    $use_prev = true;
    $prev = $this->start-10;
    if ($prev <0) $prev = 0;
}else{ $use_prev = false;}
$j=0;

if ($this->start<=$this->count-10){
    $use_next = true;
    $next = $this->start + 10;
}else $use_next = false;
?>
    <ol>
    <?php if ($use_prev):?><li><a class='prev' href="posts/list/<?php echo $prev?>">Previous Page</a></li><?php endif;?>
    <?php for ($i=0,$c=$this->count;$i<$c;$i+=10):
       $j++;
       ?>
       <li>
       <?php if ($this->start == $i): echo $j?>
       <?php else:?>
           <a href="posts/list/<?php echo $i?>"><?php echo $j?></a></li>
       <?php endif;?>
    <?php endfor;?>
    <?php if ($use_next):?><li><a class='next' href="posts/list/<?php echo $next?>">Next Page</a></li><?php endif;?>
    </ol>
</aside>
<?php endif;?>