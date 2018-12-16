<?php

   //class which creates a schedule in html
   //holds a collection of Event objects (defined below)
   class ScheduleMaker
   {
      //member variables
      
      var $width; //total width of schedule
      var $height; //total height of schedule
      var $left; //left offset of schedule
      var $top; //top offset of schedule
      var $beginTime = "9:30";
      var $endTime = "17:30";
      var $events = array();
      var $padding; //side padding of schedule 
	  var $earliestTime; //earliest event start time

      //constructor
      //usage: $sm = new ScheduleMaker(100,100,100,100,5);
      function ScheduleMaker($width=0, $height=0, $left=0, $top=0, $padding=5)
      {
         $this->width = $width;
         $this->height = $height;
         $this->left = $left;
         $this->top = $top;
         $this->padding = $padding;
      }

      //set the begin and end time of the entire schedule
      //input format:  "hour:min" in military time
      function setBeginEndTime($begin, $end)
      {
         $this->beginTime = $begin;
         $this->endTime = $end;
		 $this->earliestTime = $begin;
      }

      //adds a new Event to the schedule
      //look at the definiteion of Event below
      function addEvent($event)
      {
         array_push($this->events, $event);
      }

      //prints to HTML
      function printHTML()
      {
         $w = $this->width;
         $h = $this->height;
         $l = $this->left;
         $t = $this->top+60;
		 $z = 0;
		 $eventIndex = 0;
		 $flag = false;

         //cumulative width and height of event cells
         $totalWidth = $w - $this->padding*6;
         $totalHeight = $h - $this->padding*2;

         //$totalTime = TimeToNumeric($this->endTime) - TimeToNumeric($this->earliestTime);

         //width of a single column of events
         $colwidth = $totalWidth * 0.1;
         
         //start and end horizontal locations of each column
         $starts[1] = $l + $this->padding;
         $ends[1] = $starts[1] + $colwidth;
         $starts[2] = $ends[1] + $this->padding;
         $ends[2] = $starts[2] + $colwidth;
         $starts[3] = $ends[2] + $this->padding;
         $ends[3] = $starts[3] + $colwidth;
         $starts[4] = $ends[3] + $this->padding;
         $ends[4] = $starts[4] + $colwidth;
         $starts[5] = $ends[4] + $this->padding;
         $ends[5] = $starts[5] + $colwidth;
		 $starts[6] = $ends[5] + $this->padding;
         $ends[6] = $starts[6] + $colwidth;
         $starts[7] = $ends[6] + $this->padding;
         $ends[7] = $starts[7] + $colwidth;

         echo "<div class='background' style='position:absolute left:${l}px; top:${t}px; height:${h}px width:${w}px'>\n";
         
         echo "<div class='weeklabel' align=center style='position:absolute; left:$starts[1]px; top:${t}px; width:${colwidth}px'>Monday</div>\n";
         echo "<div class='weeklabel' align=center style='position:absolute; left:$starts[2]px; top:${t}px; width:${colwidth}px'>Tuesday</div>\n";
         echo "<div class='weeklabel' align=center style='position:absolute; left:$starts[3]px; top:${t}px; width:${colwidth}px'>Wednesday</div>\n";
         echo "<div class='weeklabel' align=center style='position:absolute; left:$starts[4]px; top:${t}px; width:${colwidth}px'>Thursday</div>\n";
         echo "<div class='weeklabel' align=center style='position:absolute; left:$starts[5]px; top:${t}px; width:${colwidth}px'>Friday</div>\n";
         echo "<div class='weeklabel' align=center style='position:absolute; left:$starts[6]px; top:${t}px; width:${colwidth}px'>Saturday</div>\n";
         echo "<div class='weeklabel' align=center style='position:absolute; left:$starts[7]px; top:${t}px; width:${colwidth}px'>Sunday</div>\n";
		 
		 foreach( $this->events as $event )
         { 
			 if((date("H",strtotime($this->earliestTime)) == 0) && $flag == false)
			 {
				 $this->earliestTime = ($event->startTime);
				 $this->beginTime = $this->earliestTime;
				 $flag = true;
			 }
			 
			 else if(date("H",strtotime($event->startTime)) < date("H",strtotime($this->earliestTime)))
			 {
				 $this->earliestTime = ($event->startTime);
				 $this->beginTime = $this->earliestTime;
				 $earliestEventIndex = $eventIndex;
				 $eventIndex = 0;
			 }
			 
			 $eventIndex++;
		 }
		 
		 $totalTime = TimeToNumeric($this->endTime) - TimeToNumeric($this->earliestTime);
         foreach( $this->events as $event )
         {
            for($i = 1; $i <= 7; $i++)
            {
               $startTime = TimeToNumeric($event->startTime);
               $endTime = TimeToNumeric($event->endTime);
               $interval = TimeToNumeric($endTime) - TimeToNumeric($startTime);
               $top = ($t+ $this->padding + (TimeToNumeric($startTime) - TimeToNumeric($this->beginTime)) * $totalHeight / $totalTime)+26;
               $height = $totalHeight * $interval / $totalTime; 
               $id = $event->id;
               
               $startTime = TimeToAMPM($event->startTime);
               $endTime = TimeToAMPM($event->endTime);

               $cssClass = "required";
               if($event->required == 0)
               {
                  $cssClass = "tentative";
               }

               if(preg_match("/$i/", $event->days) == 1)
               {	
                  $html = "<div class='$cssClass' align=center style='position:absolute; left:$starts[$i]px; top:${top}px; width:${colwidth}px; height:${height}px; z-index:$z'>\n";
                  $z++;
                  $html .= "<b>$id</b><br>$startTime - $endTime\n";
                  $html .= "</div>\n";
                  echo $html;
               }
            } 
			
			$eventIndex++;
         }
         echo "</div>\n";
      }

   }

   //a single event in a schedule
   //can occur multiple times in a week
   class Event
   {
      //member variables
      var $days; //which days of the week (e.g. "135" for MWF)
      var $startTime; //format "hour:min" in military time
      var $endTime; //format "hour:min"
      var $id;  //name of event
      var $required; //1 for required, 0 otherwise

      function Event( $id, $days, $start, $end, $required=1 )
      {
         $this->days = $days;
         $this->startTime = $start;
         $this->endTime = $end;
         $this->id = $id;
         $this->required = $required;
      }
   }

   //converts time to a real number
   //example: 8:45 will convert to 8.75
   function TimeToNumeric($time)
   {
      list($hour, $minute) = array_pad(explode(':', $time, 2),2,null);
      $new_time = $hour + $minute/60.0;
      return $new_time;
   }

   //converts military time to AM/PM time string
   //example: "17:40" will convert to "5:40 PM"
   function TimeToAMPM($time)
   {
      list($hour, $minute) = array_pad(explode(":", $time, 2),2, null);
      if($hour < 12)
      {
         if($hour == 0)
         {
            $hour = 12;
         }

         $new_time = "$hour:$minute AM";
         return $new_time;
      }
      else
      {
         if($hour != 12)
         {
            $hour -= 12;
         }
         
         $new_time =  "$hour:$minute PM";
         return $new_time;
      }
   } 
?>
