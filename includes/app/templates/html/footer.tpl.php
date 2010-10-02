<a id='promote-js' href='https://developer.mozilla.org/en/JavaScript' title='JavaScript Reference, JavaScript Guide, JavaScript API, JS API, JS Guide, JS Reference, Learn JS, JS Documentation'><img src='http://static.jsconf.us/promotejshs.png' height='150' width='180' alt='JavaScript Reference, JavaScript Guide, JavaScript API, JS API, JS Guide, JS Reference, Learn JS, JS Documentation'/></a>
<footer>
    
</footer>
<div id="sky">
    <div id="clouds_s"></div>
    <div id="clouds_l"></div>
</div>
<?php if ($this->action == 'posts-open'):?>
<!-- AddThis Button BEGIN -->
<script type="text/javascript">var addthis_config = {"data_track_clickback":true};</script>
<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=ariehblog"></script>
<!-- AddThis Button END -->
<?php endif;?>

<?php if (!$this->nojs):?>
    <script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/mootools/1.2.4/mootools-yui-compressed.js'></script>
    <?php
    if ($this->online):
        $src = $this->cdn . "b=" . $this->sub_path . "js&amp;f=";
        $sep='';
        foreach($this->js as $name){
            $src .=$sep.$name.'.js';
            $sep=',';
        }?>
        <script type='text/javascript' src='<?php echo $src."?tk=".$this->js_token;?>'></script>
    <?php else:?>
        <?php foreach ($this->js as $name):?>
            <script type='text/javascript' src='<?php echo $this->base_path . "js/$name.js";?>'></script>
        <?php endforeach;?>
    <?php endif;?>
<?php endif;?>
<script type='text/javascript'>
//<![CDATA[
   <?php echo "var base_path = '{$this->base_path}';\n"?>
//]]>
</script>
</body>
</html>