    <?php

    //connect to the database  
    mysql_connect("mysql12.000webhost.com","a6725582_test","testing1");
    mysql_select_db("a6725582_tutors");
       
    //get the course name
    $course = $_GET['course'];
    $mType= $_GET['mType']; //get the member type (role of person launching the query): 0=tutor; 1=tutee (if the tutor launches a query, we need to return tutee records)
	
    //get the course dept and number
    $coursePieces = explode(" ", $course); //coursePieces[0] is the dept; coursePieces[1] is the course number

    if (sizeof($coursePieces)==1) { // only the department or only the course was specified
       if ($mType==0) { //Tutor issued query -->he is searching for tutees
  	      if(is_numeric($course)) // course number was specified
             $q=mysql_query("SELECT * FROM tutee NATURAL JOIN members where tutee_courses LIKE '% $course%'") or die(mysql_error());  
          else					  // dept was specified
             $q=mysql_query("SELECT * FROM tutee NATURAL JOIN members where tutee_courses LIKE '%$course %'") or die(mysql_error());  
       }
       elseif ($mType==1) { //Tutee issued query -->he is searching for tutors
		   if(is_numeric($course)) // course number was specified
		      $q=mysql_query("SELECT * FROM tutor NATURAL JOIN members where tutor_courses LIKE '% $course%'") or die(mysql_error());  
		   else					  // dept was specified
              $q=mysql_query("SELECT * FROM tutor NATURAL JOIN members where tutor_courses LIKE '%$course %'") or die(mysql_error());  
       }
    }
    elseif (sizeof($coursePieces)==2) { // department and course number are specified
       if ($mType==0) //Tutor issued query -->he is searching for tutees
          $q=mysql_query("SELECT * FROM tutee NATURAL JOIN members where FIND_IN_SET('$course',tutee_courses) OR FIND_IN_SET(' $course',tutee_courses)") or die(mysql_error()); // course list may have spaces after the commas
       elseif ($mType==1) //Tutee issued query -->he is searching for tutors
          $q=mysql_query("SELECT * FROM tutor NATURAL JOIN members where FIND_IN_SET('$course',tutor_courses) OR FIND_IN_SET(' $course',tutor_courses)") or die(mysql_error()); // course list may have spaces after the commas
    }
    while($e=mysql_fetch_assoc($q))  
            $output[]=$e;  
       
    //$size = sizeof($coursePieces);
    //$test = is_numeric(" 50");
    //echo "Hello $test!";

    print(json_encode($output));  
       
    mysql_close();  
    ?>  