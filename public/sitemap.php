<?php
ob_start();
header ("Content-Type:text/xml");
require_once 'autoloader.php';
require_once 'paths.php';
ob_start();
session_start();
session_regenerate_id();

$dbconf = json_decode(file_get_contents('../config/db.json'));

PancakeTF_PDOAccess::connect('mysql',$dbconf->host,$dbconf->dbname,$dbconf->username,$dbconf->password);
$db = new PancakeTF_PDOAccess;
$tags = $db->queryArray("SELECT * FROM `tags`");
foreach ($tags as &$tag){
    $post = $db->queryRow(
            'SELECT
                UNIX_TIMESTAMP(posts.created) as `created`
             FROM `posts`
             Inner Join `posts_has_tags` as pht ON pht.posts_id = posts.id
             WHERE pht.tags_id = ?
             ORDER BY created DESC
             LIMIT 0,1'
            , array($tag['id'])
    );
    $tag['last-created'] = $post['created'];
}

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";

?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    <url>
        <loc>http://blog.arieh.co.il/</loc>
        <lastmod><?php echo date('c')?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.00</priority>
    </url>
<?php foreach ($tags as $tag):?>
    <url>
        <loc><?php echo $paths[0] . "tags/open/" . $tag['name'] ?></loc>
        <lastmod><?php echo date('c',$tag['last-created']);?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.50</priority>
    </url>
<?php endforeach?>
</urlset>