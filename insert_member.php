    <?php

    //connect to the database  
    $conn = mysql_connect("mysql12.000webhost.com","a6725582_test","testing1");
    mysql_select_db("a6725582_tutors");
       
    //  get the member info to add
    $email = $_GET['email'];
    $name = $_GET['name'];
    $member_type = $_GET['mType']; //get the member type : 0=tutor; 1=tutee; 2=both
    $courses = $_GET['courses']; 
    $about = $_GET['about'];

//	echo "'$courses' <BR>";
	
     // check if the user already exists
    $result=mysql_query("SELECT member_type FROM members where email='$email'") or die(mysql_error());

    $arrayM = array();
    while ($row = mysql_fetch_array($result)) {
       $arrayM[] = $row;
    }
	$mmm = $arrayM[0]['member_type'];
	
     // Determine the new membership type for the members table
    $new_mType = $member_type; // new_mType is the membership type that will be added to the members table
    if(isset($arrayM[0]['member_type']) & $arrayM[0]['member_type'] != -1)  {// the user already exists and has a role specified already
       if ($member_type != $arrayM['member_type']) { // user is signing up for a 'second' role --> his member type should now be set to 2 in the member table
           $new_mType = 2;
       }
    }

    //  add to members table
    $query="INSERT INTO members (email, name, member_type) VALUES ('$email', '$name', '$new_mType') ON DUPLICATE key update name='$name', member_type='$new_mType'";

    $q = mysql_query($query, $conn);
    $flag['code']=1;   //succes code when adding new record to database    
    if(!$q ) {
       $flag['code']=0;
	   die('Could not connect: ' . mysql_error());
	}

    // add to tutor or tutee table if info has been provided

    if($member_type == 0) { //if adding tutor entry, add values to the tutor table:
      $query = "INSERT INTO tutor (email, tutor_courses, tutor_about) VALUES ('$email', '$courses', '$about') on duplicate key update tutor_courses='$courses', tutor_about='$about'";
      $q = mysql_query($query, $conn);
      if(!$q ) {
         $flag['code']=0;
		 die('Could not connect tutor table: ' . mysql_error());
	  }
		 
    }
    elseif($member_type == 1) { //if tutee; add values to the tutee table:
      $query = "INSERT INTO tutee (email, tutee_courses, tutee_about) VALUES ('$email', '$courses', '$about') on duplicate key update tutee_courses='$courses', tutee_about='$about'";
      $q = mysql_query($query, $conn);

      if(! $q ) {
         $flag['code']=0;
		 die('Could not connect to tutee table: ' . mysql_error());
	  }
    }
    
    print(json_encode($flag));
    //echo "The user has been added";
        
    mysql_close();  
    ?>   