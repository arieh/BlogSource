<?php
if (!isset($this->post) || !$this->post){
   header("HTTP/1.0 404 Not Found");
   die();
}
header("Content-Type:text/xml");
header("Encoding:utf-8");
echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
?>
<rss version="2.0">
<channel>
    <title>Arieh.co.il - <?php echo $this->post['title'];?> Comments Feed</title>
    <link>http://blog.arieh.co.il/posts/open/<?php echo $this->post['name']?></link>
    <description>A list comments for the post: <?php echo $this->post['title']?></description>
    <lastBuildDate><?php echo date('r',$this->posts[0]['created'])?></lastBuildDate>
    <language>en-us</language>
    <?php foreach ($this->commentss as $comment):?>
    <item>
        <title><?php echo $comment['title']?></title>
        <link><?php echo $this->base_path . '/posts/open/'.$this->post['name']."#cmt".$comment['id']?></link>
        <guid><?php echo $this->base_path . '/posts/open/'.$this->post['name']."#cmt".$comment['id']?></guid>
        <pubDate><?php echo date('r',$comment['created'])?></pubDate>
        <description>[CDATA[<?php echo $comment['content'];?>]]</description>
    </item>
    <?php endforeach;?>
</channel>
</rss>