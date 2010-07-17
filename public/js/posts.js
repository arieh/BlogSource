document.addEvent('domready',function(){
	new Form.Validator.Inline($('new-comment'));
	var img = $$('#new-comment img')[0];
	$('c_email').addEvent('blur',function(){
		var hash = hex_md5(this.value);
		img.set('src',"http://www.gravatar.com/avatar/"+hash+"?d=identicon&s=70");
	});
});