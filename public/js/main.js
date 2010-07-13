
document.addEvent('domready',function(){
var small = $('clouds_s')
	, large = $('clouds_l')
	, contact = $('contact')
	, small_counter = 0
	, large_counter = 0
	, toggle = false
	, toggle_contact = false
	, stopped = false
	, handle;

function exactPeriodical(callback,timeout){
	var start = new Date().getTime()
		, count = 0
		, handle;
		
	function instance(){
		var now = new Date().getTime()
			, diff = now-start - timeout*count++;
		if (stopped) return;
		callback();
		if (timeout-diff>0){
			handle = window.setTimeout(instance,timeout-diff);
		}else{
			handle = window.setTimeout(instance,0);
		}
	}	
	handle = window.setTimeout(instance,timeout);
	return handle;
}

function moveSmall(){
	small_counter += 1;
	small.setStyle('background-position', small_counter + 'px bottom');
	if (small_counter>499) small_counter = 0;
}


function moveLarge(){
	large_counter += 2;
	large.setStyle('background-position', large_counter + 'px 0');
	if (large_counter>498) large_counter = 0;
}

function move(){
	moveLarge();
	if (toggle){
		moveSmall();
		toggle = false;
	}else toggle = true;
}

handle = exactPeriodical(move,100);

$('wind').addEvent('click',function(){
	if (stopped){
		exactPeriodical(move,100);
		stopped = false;
	}else{
		stopped = true;
	}
});
});