<?php
	include('holidays.php');

	$dots = '';
	$first = true;
	$holidays = getRedDays();
	foreach($holidays as $date=>$description){
		echo '<div><p>'.$description.'</p></div>'."\n";
		if ($first){
			$first = false;
			$dots .= '<span class="active">&bull;</span>';
		} else {
			$dots .= '<span>&bull;</span>';					
		}
	}
	echo '<script>';
	echo 'var marginLeft = 0;'."\n";
  	echo 'var maxMargin  = '.((count($holidays)-1)*480).';'."\n";
	echo 'x$(".dots").html(\'inner\',\''.$dots.'\');'."\n";
	echo "x$('#holidays').swipe(
    function(e, data){
    	 
            switch (data.type){
            
            case ('direction'):
				if (data.direction == 'left'){  
					marginLeft = marginLeft-480; 
					if(Math.abs(marginLeft)>maxMargin){ marginLeft=maxMargin*-1; } 
					x$('#holidays').setStyle('marginLeft',marginLeft+'px') 
					
					x$('.dots span').each(function(element, index, xui) {
						x$(this).attr('class','');
						if (parseInt(index) == parseInt(Math.abs(marginLeft/480))){
							x$(this).attr('class','active');
						}
					});
					
				}
				if (data.direction == 'right'){ 
					marginLeft = marginLeft+480; 
					if(marginLeft>0){ marginLeft=0; } 
					x$('#holidays').setStyle('marginLeft',marginLeft+'px')
					x$('.dots span').each(function(element, index, xui) {
						x$(this).attr('class','');
						if (parseInt(index) == parseInt(Math.abs(marginLeft/480))){
							x$(this).attr('class','active');
						}
					});
					 
				}
            break;
			case ('doubletap'):
            case ('longtap'):
            case ('simpletap'):
				marginLeft = marginLeft-480; 
				if(Math.abs(marginLeft)>maxMargin){ marginLeft=maxMargin*-1; } 
				x$('#holidays').setStyle('marginLeft',marginLeft+'px');
				x$('.dots span').each(function(element, index, xui) {
					x$(this).attr('class','');
					if (parseInt(index) == parseInt(Math.abs(marginLeft/480))){
						x$(this).attr('class','active');
					}
				});
            break;
            }	
            
            
    }, {
    	
        swipeCapture: true,
        longTapCapture: true,
        doubleTapCapture: true,
        simpleTapCapture: false
         
    }
    );";
	
	
	echo '</script>';
   
	
?>
