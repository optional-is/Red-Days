<?php
function dateDiff($dtStart,$dtEnd){
	$diff = 0;

	$i = 0;
	while ($i < abs(date('Y',$dtStart)-date('Y',$dtEnd))){
		$i++;
		$diff += (date('z',mktime(0,0,0,12,31,date('Y',$dtStart)-($i*(abs(date('Y',$dtStart)-date('Y',$dtEnd))/(date('Y',$dtStart)-date('Y',$dtEnd))))))+1)*(-1*(abs(date('Y',$dtStart)-date('Y',$dtEnd))/(date('Y',$dtStart)-date('Y',$dtEnd))))+1;
	}

	if (date('Y',$dtStart) > date('Y',$dtEnd)){
		$diff -= date('z',$dtStart);
		$diff += date('z',$dtEnd);
	} else {
		$diff += date('z',$dtEnd);
		$diff -= date('z',$dtStart);
	}

	return $diff;
}

function getNextRedDay(){
	$holidays = getRedDays();
	return key($holidays);
	
}

function getRedDays(){
	
	$red_days = array();
	
	// May Day
	$target_day = strtotime(date('Y').'-05-01');
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-05-01');
	}
	$red_days[$target_day] = 'May Day';			
	
	// June 17th
	$target_day = strtotime(date('Y').'-06-17');
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-06-17');
	}
	$red_days[$target_day]='National Day';			

	// Dec 25th
	$target_day = strtotime(date('Y').'-12-25');
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-12-25');
	}
	$red_days[$target_day]='Christmas';			
	
	// Dec 31st
	$target_day = strtotime(date('Y').'-12-31');
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-12-31');
	}
	$red_days[$target_day]='New Year\'s Eve';			


	// Jan 1st
	$target_day = strtotime(date('Y').'-01-01');
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-01-01');
	}
	$red_days[$target_day] ='New Year\'s Day';			
	
	
	// Sumardagurinn fyrsti
	$t_day = date('N',strtotime(date('Y').'-04-19'));
	// thursday == 4
	if($t_day == 4){ $t_day = 19; } elseif($t_day<4) { $t_day = 19+(4-$t_day); } else { $t_day = 24+($t_day-4); }
	
	$target_day = strtotime(date('Y').'-04-'.str_pad($t_day,2,'0',STR_PAD_LEFT));
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-04-'.str_pad($t_day,2,'0',STR_PAD_LEFT));
	}
	$red_days[$target_day] ='First day of Summer';		
	
	// Sjómannadagurinn
	$t_day = date('N',strtotime(date('Y').'-06-01'));
	$t_day = 8-$t_day;
	
	$target_day = strtotime(date('Y').'-06-'.str_pad($t_day,2,'0',STR_PAD_LEFT));
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-06-'.str_pad($t_day,2,'0',STR_PAD_LEFT));
	}
	$red_days[$target_day] ='Sjómannadagurinn';		

	
	// Verslunarmanna
	$t_day = date('N',strtotime(date('Y').'-08-01'));
	if($t_day > 1){ $t_day = 9-$t_day; }
	
	$target_day = strtotime(date('Y').'-08-'.str_pad($t_day,2,'0',STR_PAD_LEFT));	
	if(time() > $target_day){
		$t_day = date('N',strtotime((date('Y')+1).'-08-01'));
		if($t_day > 1){ $t_day = 9-$t_day; }
		$target_day = strtotime((date('Y')+1).'-08-'.str_pad($t_day,2,'0',STR_PAD_LEFT));
	}
	
	$red_days[$target_day] ='Verslunarmanna ';		
	
	// Easter Related Holidays!
	$easter_day = getEaster();
	$target_day = $easter_day;
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
	}
	$red_days[$target_day] ='Easter';		

	// Easter Related Holidays!
	$target_day = $easter_day;
	$target_day = strtotime('-2 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('-2 days',$target_day);
	}
	$red_days[$target_day] ='Good Friday';		

	$target_day = $easter_day;
	$target_day = strtotime('-3 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('-3 days',$target_day);
	}
	$red_days[$target_day] ='Maundy Thursday';		
	
	$target_day = $easter_day;
	$target_day = strtotime('-7 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('-7 days',$target_day);
	}
	$red_days[$target_day] ='Palm Sunday';		

	$target_day = $easter_day;
	$target_day = strtotime('+1 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('+1 days',$target_day);
	}
	$red_days[$target_day] ='Easter Monday';		
	
	$target_day = $easter_day;
	$target_day = strtotime('+40 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('+40 days',$target_day);
	}
	$red_days[$target_day] ='Ascension';		
	
	$target_day = $easter_day;
	$target_day = strtotime('+49 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('+49 days',$target_day);
	}
	$red_days[$target_day] ='Whitsun';		
	
	$target_day = $easter_day;
	$target_day = strtotime('+50 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('+50 days',$target_day);
	}
	$red_days[$target_day] ='Whit Monday';		
	
	
	ksort($red_days);
	return $red_days;

}

function getEaster($Y=''){
	if ($Y == ''){ $Y = date('Y'); }
	
	$a = $Y%19;
	$b = floor($Y/100);
	$c = $Y%100;
	$d = floor($b/4);
	$e = $b%4;
	$f = floor(($b+8) /25);
	$g = floor(($b-$f+1)/3);
	$h = (19*$a + $b - $d - $g + 15)%30;
	$i = floor($c/4);
	$k = $c%4;
	$L = (32+2*$e + 2*$i - $h - $k)%7;
	$m = floor(($a+11*$h+22*$L)/451);
	$month = floor(($h+$L - 7*$m + 114) / 31);
	$day = (($h + $L - 7*$m + 114)%31)+1;
	
	return strtotime($Y.'-'.str_pad($month,2,'0',STR_PAD_LEFT).'-'.str_pad($day,2,'0',STR_PAD_LEFT));

}

?>