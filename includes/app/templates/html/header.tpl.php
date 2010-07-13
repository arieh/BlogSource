<!DOCTYPE html>
<html lang="en-us" xmlns="http://www.w3.org/1999/xhtml"><head>
<title><?php echo implode(' :: ',$this->titles);?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <meta name="google-site-verification" content="BGnm02T2G_Ld6zdRRLjglg3V4BvswH4_p3eLLQMoDz8" />
	<meta name="description" content='<?php echo $this->description;?>' />
    <?php
     if ($this->online):
        $src = $this->cdn . "b=css&amp;f=";
        $sep='';
        foreach($this->css as $name){
            $src .=$sep.$name.'.css';
            $sep=',';
        }
    ?>
    <link rel='stylesheet' type='text/css' href='<?php echo $src;?>' />
    <?php else:?>
       <?php foreach($this->css as $name):?>
           <link rel='stylesheet' type='text/css' href='<?php echo $this->base_path . "css/$name.css";?>' />
       <?php endforeach;?>
    <?php endif;?>
    
    <!--[if lt IE 7]>
        <style type="text/css">
        img, div { behavior: url(<?php echo $this->base_path?>css/iepngfix.htc) }
        </style>
    <![endif]-->
    <base href="<?php echo $this->base_path;?>" />
    <link rel="alternate" type="application/rss+xml" href="/rss/" title="Posts RSS Feed">
    <?php if (isset($this->tag) && $this->tag):?>
        <link rel="alternate" type="application/rss+xml" href="/rss/tags/<?php echo $this->tag['name']?>" title="Tag:<?php echo $this->tag['name']?> RSS Feed">
    <?php endif;?>
    <!--[if IE]>
    <link rel='stylesheet' type='text/css' href='<?php echo $this->base_path;?>css/ie.css' />
    <script type='text/javascript' src="js/html5.js"></script>
    <![endif]-->
    <?php if (isset($this->post) && $this->post):?>
        <link rel="alternate" type="application/rss+xml" href="/rss/posts/open/<?php echo $this->post['name']?>" title="Post Comments RSS Feed">
    <?php endif;?>
    <style type="text/css">
    @font-face {
        font-family: 'Lobster1.3Regular';
        src: url('fonts/Lobster_1.3-webfont.eot');
        src: local('☺'), url('fonts/Lobster_1.3-webfont.woff') format('woff'), url('fonts/Lobster_1.3-webfont.ttf') format('truetype'), url('fonts/Lobster_1.3-webfont.svg#webfontcOtP3oQb') format('svg');
        font-weight: normal;
        font-style: normal;
    }
    
    body > header h1{
        font-family: 'Lobster1.3Regular';
    }
    </style>
</head>
<body>

<nav id="access_box">
<a tabindex="1" class="access" href="#main">Skip Navigation</a>
</nav>
<header>
    <h1>
        <a href='<?php echo $this->base_path;?>'>Arieh<span>.co.il</span></a>
    </h1>
    <a id='wind' href='javascript:;'>Toggle Wind</a>
</header>
