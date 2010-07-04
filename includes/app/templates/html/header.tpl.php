<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xml:lang="en" xmlns="http://www.w3.org/1999/xhtml"><head>
<title><?php echo implode(' :: ',$this->titles);?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="description" content='<?php echo $this->description;?>' />
    <link rel="icon" href="<?php echo $this->base_path;?>images/favicon.png" />
    <link rel="stylesheet" href="http://yui.yahooapis.com/2.8.0r4/build/reset/reset-min.css" type='text/css' />
    <?php
     if ($this->online):
        $src = $this->base_path . "min/b=" . $this->sub_path . "css&amp;f=";
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
    <!--[if IE]>
    <link rel='stylesheet' type='text/css' href='<?php echo $this->base_path;?>css/ie.css' />
    <![endif]-->
    <!--[if lt IE 7]>
        <style type="text/css">
        img, div { behavior: url(<?php echo $this->base_path?>css/iepngfix.htc) }
        </style>
    <![endif]-->
    <base href="<?php echo $this->base_path;?>" />
    <style type="text/css">
    @font-face {
        font-family: 'Lobster1.3Regular';
        src: url('fonts/Lobster_1.3-webfont.eot');
        src: local('☺'), url('fonts/Lobster_1.3-webfont.woff') format('woff'), url('fonts/Lobster_1.3-webfont.ttf') format('truetype'), url('fonts/Lobster_1.3-webfont.svg#webfontcOtP3oQb') format('svg');
        font-weight: normal;
        font-style: normal;
    }
    
    #logo, #logo h1{
        font-family: 'Lobster1.3Regular';
    }
    </style>
</head>
<body>
<!--[if IE 6]>

<div id='ie6'>

<![endif]-->
<!--[if IE 7]>

<div id ='ie7'>

<![endif]-->
<!--[if IE 8]>

<div id='ie8'>

<![endif]-->

<div id="access_box">
<a tabindex="1" class="access" href="#main">Skip Navigation</a>
<a tabindex="2" href="http://arieh-laptop/blog/public/sitemap.php"
class="access" rel="sitemap">Site-Map</a>
</div>
<div id = 'header'>
    <div id='logo'>
        <?php if (isset($this->main) && $this->main) echo "<h1>"?>
        <a href='<?php echo $this->base_path;?>'>Arieh<span>.co.il</span></a>
        <?php if (isset($this->main) && $this->main) echo "</h1>"?>
    </div>
</div>