<?php
// This is the magic file! We want to use this to parse JSON or ICS files into a format that can be used by the front-end

function getRedDaysICS(){
	// $URL
	// Good source for holidays
	// http://www.mozilla.org/projects/calendar/holidays.html
	
	$url = 'http://www.mozilla.org/projects/calendar/caldata/IcelandHolidays.ics';
	
	$ics = file_get_contents ( $url );
	
	$ics_lines = explode("\n",$ics);
	$red_days = array();
	
	$ics_obj = array();
	
	// This is very naive, it is not taking into account RRULE!
	foreach($ics_lines as $l){
		if (substr(trim($l),0,7) == 'SUMMARY') { $ics_obj['name'] = substr(trim($l),8); }
		if (substr(trim($l),0,7) == 'DTSTART') { 
			$dtstart = explode(':',trim($l));
			$dtstart = $dtstart[1];
			$ics_obj['date'] = strtotime(substr($dtstart,0,4).'-'.substr($dtstart,4,2).'-'.substr($dtstart,6,2));
		}
		if (trim($l) == 'END:VEVENT'){ 
			if ($ics_obj['name'] != '' && $ics_obj['date'] != ''){
				$target_day = $ics_obj['date'];
				if ($target_day > time()){
					$days = dateDiff(time(),$target_day);			
					$red_days[$target_day]='<strong>'.$days.' '.pluralize('day',$days).'</strong>'.$ics_obj['name'].'<br/>'.date('l',$target_day).', '.date('F jS',$target_day);					
				}				
			}
			$ics_obj = array(); 
		}
	}

	// $limit the number of result so we don't go too far into the future
	$limit = 20;
	ksort($red_days);
	$red_days = array_slice($red_days,0,$limit, true);
	
	return $red_days;
}

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

function pluralize($singular,$count){
	if ($count == 1){ return $singular; }
	switch($singular){
		case 'day': return 'days'; break;
	}
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
	$days = dateDiff(time(),$target_day);
	$red_days[$target_day] = '<strong>'.$days.' '.pluralize('day',$days).'</strong> May Day<br/>'.date('l',$target_day).', May 1st';			
	
	// June 17th
	$target_day = strtotime(date('Y').'-06-17');
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-06-17');
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day]='<strong>'.$days.' '.pluralize('day',$days).'</strong>National Day<br/>'.date('l',$target_day).', June 17th';			

	// Dec 25th
	$target_day = strtotime(date('Y').'-12-25');
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-12-25');
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day]='<strong>'.$days.' '.pluralize('day',$days).'</strong> Christmas<br/>'.date('l',$target_day).', December 25th';			
	
	// Dec 31st
	$target_day = strtotime(date('Y').'-12-31');
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-12-31');
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day]='<strong>'.$days.' '.pluralize('day',$days).'</strong>  New Year\'s Eve<br/>'.date('l',$target_day).', December 31st';			


	// Jan 1st
	$target_day = strtotime(date('Y').'-01-01');
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-01-01');
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day] ='<strong>'.$days.' '.pluralize('day',$days).'</strong> New Year\'s Day<br/>'.date('l',$target_day).', January 1st';			
	
	
	// Sumardagurinn fyrsti
	$t_day = date('N',strtotime(date('Y').'-04-19'));
	// thursday == 4
	if($t_day == 4){ $t_day = 19; } elseif($t_day<4) { $t_day = 19+(4-$t_day); } else { $t_day = 24+($t_day-4); }
	
	$target_day = strtotime(date('Y').'-04-'.str_pad($t_day,2,'0',STR_PAD_LEFT));
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-04-'.str_pad($t_day,2,'0',STR_PAD_LEFT));
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day] ='<strong>'.$days.' '.pluralize('day',$days).'</strong>First day of Summer<br/>'.date('l',$target_day).', April '.$t_day.date('S',$target_day);		
	
	// Sjómannadagurinn
	$t_day = date('N',strtotime(date('Y').'-06-01'));
	$t_day = 8-$t_day;
	
	$target_day = strtotime(date('Y').'-06-'.str_pad($t_day,2,'0',STR_PAD_LEFT));
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-06-'.str_pad($t_day,2,'0',STR_PAD_LEFT));
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day] ='<strong>'.$days.' '.pluralize('day',$days).'</strong> Sjómannadagurinn<br/>'.date('l',$target_day).', June '.$t_day.date('S',$target_day);		

	
	// Verslunarmanna
	$t_day = date('N',strtotime(date('Y').'-08-01'));
	if($t_day > 1){ $t_day = 9-$t_day; }
	
	$target_day = strtotime(date('Y').'-08-'.str_pad($t_day,2,'0',STR_PAD_LEFT));
	if(time() > $target_day){
		$target_day = strtotime((date('Y')+1).'-08-'.str_pad($t_day,2,'0',STR_PAD_LEFT));
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day] ='<strong>'.$days.' '.pluralize('day',$days).'</strong> Verslunarmanna<br/>'.date('l',$target_day).', August '.$t_day.date('S',$target_day);		
	
	// Easter Related Holidays!
	$easter_day = getEaster();
	$target_day = $easter_day;
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day] ='<strong>'.$days.' '.pluralize('day',$days).'</strong> Easter<br/>'.date('l',$target_day).', '.date('F jS',$target_day);		

	// Easter Related Holidays!
	$target_day = $easter_day;
	$target_day = strtotime('-2 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('-2 days',$target_day);
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day] ='<strong>'.$days.' '.pluralize('day',$days).'</strong> Good Friday<br/>'.date('l',$target_day).', '.date('F jS',$target_day);		

	$target_day = $easter_day;
	$target_day = strtotime('-3 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('-3 days',$target_day);
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day] ='<strong>'.$days.' '.pluralize('day',$days).'</strong> Maundy Thursday<br/>'.date('l',$target_day).', '.date('F jS',$target_day);		
	
	$target_day = $easter_day;
	$target_day = strtotime('-7 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('-7 days',$target_day);
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day] ='<strong>'.$days.' '.pluralize('day',$days).'</strong> Palm Sunday<br/>'.date('l',$target_day).', '.date('F jS',$target_day);		

	$target_day = $easter_day;
	$target_day = strtotime('+1 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('+1 days',$target_day);
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day] ='<strong>'.$days.' '.pluralize('day',$days).'</strong> Easter Monday<br/>'.date('l',$target_day).', '.date('F jS',$target_day);		
	
	$target_day = $easter_day;
	$target_day = strtotime('+40 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('+40 days',$target_day);
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day] ='<strong>'.$days.' '.pluralize('day',$days).'</strong> Ascension<br/>'.date('l',$target_day).', '.date('F jS',$target_day);		
	
	$target_day = $easter_day;
	$target_day = strtotime('+49 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('+49 days',$target_day);
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day] ='<strong>'.$days.' '.pluralize('day',$days).'</strong> Whitsun<br/>'.date('l',$target_day).', '.date('F jS',$target_day);		
	
	$target_day = $easter_day;
	$target_day = strtotime('+50 days',$target_day);
	if(time() > $target_day){
		$target_day = getEaster((date('Y')+1));
		$target_day = strtotime('+50 days',$target_day);
	}
	$days = dateDiff(time(),$target_day);			
	$red_days[$target_day] ='<strong>'.$days.' '.pluralize('day',$days).'</strong> Whit Monday<br/>'.date('l',$target_day).', '.date('F jS',$target_day);		
	
	
	ksort($red_days);
	return $red_days;
	/*
	




   // Christmas
   if (($month < 12) || (($month == 12) && ($day < 24))){
   	$days = dateDiff(12,24,$month,$day);			
   	return '<strong>'.$days.' '.pluralize('day',$days).'</strong> until Christmas break, December 24-26th';				
   }
   */
	
/*	
http://en.wikipedia.org/wiki/Public_holidays_in_Iceland
*/		

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