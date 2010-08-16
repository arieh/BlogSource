<?php

if (!$this->isKeySet('posts')){
   header("HTTP/1.0 404 Not Found");
   return;
}
header("Content-Type:text/xml");
header("Encoding:utf-8");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
?>
<rss version="2.0">
<channel>
    <title>Arieh.co.il Posts Feed</title>
    <link>http://blog.arieh.co.il/</link>
    <description>A list of my current posts</description>
    <lastBuildDate><?php echo date('r',$this->posts[0]['created'])?></lastBuildDate>
    <language>en-us</language>
    <?php foreach ($this->posts as $post): ?>
    <item>
        <title><?php echo $post['title']?></title>
        <link><?php echo $this->base_path . 'posts/open/'.$post['name']?></link>
        <guid><?php echo $this->base_path . 'posts/open/'.$post['name']?></guid>
        <pubDate><?php echo date('r',$post['created'])?></pubDate>
        <description><?php echo $post['summary']?></description>
    </item>
    <?php endforeach;?>
</channel>
</rss>