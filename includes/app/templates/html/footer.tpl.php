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
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/mootools/1.2.4/mootools-yui-compressed.js'></script>
<?php
if ($this->online):
$src = $this->cdn . "b=" . $this->sub_path . "js&amp;f=";
$sep='';
foreach($this->js as $name){
    $src .=$sep.$name.'.js';
    $sep=',';
}?>
<script type='text/javascript' src='<?php echo $src;?>'></script>
<?php else:?>
    <?php foreach ($this->js as $name):?>
        <script type='text/javascript' src='<?php echo $this->base_path . "js/$name.js";?>'></script>
    <?php endforeach;?>
<?php endif;?>
<?php if (isset($this->tinymce) && $this->tinymce): ?>
   <script type='text/javascript' src='js/ckeditor/ckeditor.js'></script>
   <script type='text/javascript'>
 //<![CDATA[
   CKEDITOR.replace( 'p_content' );
 //]]>
   </script>
<?php endif;?>

<script type='text/javascript'>
//<![CDATA[
   <?php echo "var base_path = '{$this->base_path}';\n"?>
//]]>
</script>
</body>
</html>