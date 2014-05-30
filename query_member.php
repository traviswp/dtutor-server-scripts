    <?php

    //connect to the database  
    $conn = mysql_connect("mysql12.000webhost.com","a6725582_test","testing1");
    mysql_select_db("a6725582_tutors");
       
    //  get the member info to add
    $email = $_GET['email'];
	
	//GET THE MEMBER TYPE
    $result=mysql_query("SELECT member_type FROM members where email='$email'");
    $mTypeQueryArray = array();
    while ($row = mysql_fetch_array($result)) {
       $mTypeQueryArray[] = $row;
    }
    $mType = $mTypeQueryArray[0]['member_type'];
	
	if ($mType == -1)
		$result=mysql_query("SELECT * FROM members where email='$email'");
    else if ($mType == 0) //tutor
		$result=mysql_query("SELECT * FROM members NATURAL JOIN tutor where email='$email'");
	else if ($mType == 1) //tutee
		$result=mysql_query("SELECT * FROM members NATURAL JOIN tutee where email='$email'");
	else if ($mType == 2) //tutor and tutee
		$result=mysql_query("SELECT * FROM members NATURAL JOIN tutor NATURAL JOIN tutee where email='$email'");
	
	$arrayM = array();
    while ($row = mysql_fetch_array($result)) {
       $arrayM[] = $row;
    }
	
    print(json_encode($arrayM));
    mysql_close();  
    ?>  