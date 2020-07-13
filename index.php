<?php
session_start();
	include_once 'i/dbaccess.php';
	$_SESSION['leaderboard20']=20;
	date_default_timezone_set("America/Los_Angeles");
	
	//!!!!!!!!!!!!!!!!!!!!!!!!!!
			//ccc Check to see if there is a cookie present on the device to allow for the user to automatically log in ccc
		if(isset($_COOKIE['logincookie'])){
			
		 $logincookie = $_COOKIE['logincookie'];
		
		 $cookieparts = explode('___', $logincookie);
		 $cookieloginkey = $cookieparts[0];
		 $cookielogintoken = $cookieparts[1];
		 
		if(!empty($cookieloginkey) || !empty($cookielogintoken) || preg_match("/^[a-zA-Z0-9_-]*$/", $cookieloginkey)){
		 
		$sql = "SELECT * FROM users WHERE loginkey='$cookieloginkey' LIMIT 1;";
		$result = mysqli_query($conn, $sql);
		$customerinfo = array();
		if (mysqli_num_rows($result) > 0) {
			while($row = mysqli_fetch_assoc($result)){
				$customerinfo[] = $row;
			}
			$logintokenhashed = $customerinfo[0]['logintokenhashed'];
			
			$hashedCookieCheck = password_verify($cookielogintoken, $logintokenhashed);
				if($hashedCookieCheck == false){
					
					//ccc SET LOGIN COOKIE ccc
					$logincookiename = "clogincookie";
					//ccc remove the set cookie for auto login ccc
					setcookie($logincookiename, "", (time()-(86400)), "/", null, true, true);
					//ccc SET THE LOGIN KEY AND TOKEN TO NULL SO NO ONE CAN HACK WITH A COOKIE ccc
					$res=mysqli_query($conn,"UPDATE users SET loginkey=NULL, logintokenhashed=NULL WHERE loginkey='$cookieloginkey';");
					
				}else if($hashedCookieCheck == true){
					//ccc create the session and login ccc
					
					//log in the user here
					$_SESSION['userid']=$customerinfo[0]['userid'];
					$_SESSION['email']=$customerinfo[0]['useremail'];
					$_SESSION['trainername']=$customerinfo[0]['pogoname'];
					$_SESSION['friendcode']=$customerinfo[0]['pogocode'];
					$_SESSION['level']=$customerinfo[0]['level'];
					$_SESSION['team']=$customerinfo[0]['team'];
					$_SESSION['points']=$customerinfo[0]['points'];
					
					$userid = $_SESSION['userid'];
	//Set the timezone 
	date_default_timezone_set("America/Los_Angeles");
	$unixtime = time();
	
	//set session checking cookie
		$cookiename = "sessioncheck";
		$cookievalue = '1';
		setcookie($cookiename, $cookievalue, (time()+(86400*30*12)), "/");
					
				}
		}
		}
	}//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	
	//see if there is the session cookie present to cause buttons to refresh the page if the session expired but the user was logged in
	if (isset($_COOKIE['sessioncheck'])){
		if($_COOKIE['sessioncheck']=='1' AND !isset($_SESSION['email'])){
			//set session checking cookie to zero for logged out
		$cookiename = "sessioncheck";
		$cookievalue = '0';
		setcookie($cookiename, $cookievalue, (time()+(86400*30*12)), "/");
			echo '<style onload="location.reload();"></style>';
		}
	}
	
	if (isset($_SESSION['email'])){
		$useremail=$_SESSION['email'];
		$sql = "Select * FROM users WHERE useremail = '$useremail'";
				$aresult = mysqli_query($conn, $sql);
				$userinfo = array();
				if (mysqli_num_rows($aresult) > 0) {
					while($row = mysqli_fetch_assoc($aresult)){
						$userinfo[] = $row;
					}
				};
				$_SESSION['totalwins'] = $userinfo[0]['totalwins'];
				$_SESSION['totallosses'] = $userinfo[0]['totallosses'];
				$_SESSION['displaycode'] = $userinfo[0]['displaycode'];
				$_SESSION['usericon'] = $userinfo[0]['usericon'];
				$_SESSION['emailbattles'] = $userinfo[0]['emailbattles'];
				$_SESSION['emailmessages'] = $userinfo[0]['emailmessages'];
				$_SESSION['godraftmessages'] = $userinfo[0]['godraftmessages'];
				$_SESSION['confirmemail'] = $userinfo[0]['confirmemail'];
				$_SESSION['userid'] = $userinfo[0]['userid'];
				$_SESSION['email'] = $userinfo[0]['useremail'];
				$_SESSION['trainername'] = $userinfo[0]['pogoname'];
				$_SESSION['friendcode'] = $userinfo[0]['pogocode'];
				$_SESSION['level'] = $userinfo[0]['level'];
				$_SESSION['team'] = $userinfo[0]['team'];
				$_SESSION['points'] = $userinfo[0]['points'];
				$_SESSION['battled'] = $userinfo[0]['battled'];
				//declar session variable for group chat;
				$_SESSION['lastgroupchatid']=0;
				$_SESSION['entertime']=$userinfo[0]['entertime'];
				$_SESSION['battlereadyfriends']='0';
			
				
				$userid = $_SESSION['userid'];
	//Set the timezone 
	date_default_timezone_set("America/Los_Angeles");
	$unixtime = time();
	
	//update the users last active time
	$sql = "UPDATE users 
	SET activitytime=?
	WHERE userid='$userid';";

	$stmt = mysqli_stmt_init($conn);
	if(!mysqli_stmt_prepare($stmt, $sql)){
		echo "SQL error";
		exit;
	}else{
		mysqli_stmt_bind_param($stmt, "s", $unixtime);
		mysqli_stmt_execute($stmt);
	}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-133679119-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-133679119-1');
</script>


  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PoGoPoints-Pokemon Go PVP and Drafting</title>
  <meta name="description" content="Join the online Pokemon Go PVP community to compete with one another. Use tools on this site such as the GO:Drafts to find new ways to battle and challenge one another.">
<meta name="keywords" content="Pokemon GO, Drafting, Draft, GO:Draft, PVP, battlers">
<!--
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 -->
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
 
  <link href="https://fonts.googleapis.com/css?family=Fredoka+One" rel="stylesheet">
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  
  <!-- ICONS AND FAVICONS-->
  <link rel="apple-touch-icon" sizes="57x57" href="icons/new-apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="icons/new-apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="icons/icons/new-apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="icons/new-apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="icons/new-apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="icons/new-apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="icons/new-apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="icons/new-apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="icons/new-apple-icon-180x180.png">
<link rel="apple-touch-icon" sizes="512x512" href="icons/new-apple-icon-512x512.png">
<link rel="apple-touch-icon" sizes="1024x1024" href="icons/new-apple-icon-1024x1024.png">
<link rel="icon" type="image/png" sizes="192x192"  href="icons/new-android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="512x512"  href="icons/new-android-icon-512x512.png">
<link rel="icon" type="image/png" sizes="1024x1024"  href="icons/new-android-icon-1024x1024.png">
<link rel="icon" type="image/png" sizes="32x32" href="icons/new-favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="icons/new-favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="icons/new-favicon-16x16.png">

<link rel="manifest" href="/manifest1.0.3.json">
<meta name="msapplication-TileColor" content="#000000">
<meta name="msapplication-TileImage" content="icons/new-ms-icon-144x144.png">
<meta name="theme-color" content="#000000">


  <style>
    /* Set black background color, white text and some padding */
    footer {
      background-color: rgba(64, 64, 64, .93);
      color: white;
      padding: 10px;
    }
	
	/*Default background color if image is slow to load?(move image to end of html?)*/
	body{
		background-color: #333333;
	}

  .blacktext{
	  color: black;
  }
	 .pogopointslogo {
	 font-family: Fredoka One;
 } 
	.footerfont{
		color: white;
	}
	.leaderboard{
		font-size: 20px;
		color: white;
		background-color: black;
	}
	.panel:hover {
      box-shadow: 0px 0px 15px 1px rgba(0,0,0, .2);
  }
  .greenhx{
	  color: green;
  }
  .redhx{
	  color: red;
  }
  .chatboxes{
	  color: white;
	  background: #404040;
	  border-radius: 10px;
	  overflow-x: hidden;
	  line-height: 1.2;
  }
  .chatboxes2{
	    border-radius: 10px;
		border: 2px solid #404040;
		overflow-x: hidden;
		line-height: 1.2;
  }
  .bluehx{
	  color: blue;
  }
  .orangehx{
	  color: orange;
  }
	.blacktext{
		color: black;
	}
	.socialicon{
		color: red;
		font-size: 45px;
	}
    #loader {
  position: fixed;
  border: 10px solid #f2f2f2;
  border-radius: 100%;
  border-top: 10px solid #cc0000;
  border-right: 10px solid #cc0000;
  width: 24px;
  height: 24px;
  -webkit-animation: spin 0.7s linear infinite; /* Safari */
  animation: spin 0.7s linear infinite;
  z-index: 9999;
  left: 48%;
  top: 90%;
}/* loader */

/* Safari */
@-webkit-keyframes spin {
  0% { -webkit-transform: rotate(0deg); }
  100% { -webkit-transform: rotate(360deg); }
}/* loader */

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}/* loader */

  #nojs{
	  font-weight: 600;
	  font-size: 18px;
	  background-color: white;
	  color: red;
  }
.panel-heading{
	color:white;
	background-color: #990000;
}
  .panel-danger > .panel-heading {
  color: white;
  background-color: #990000;
}

.extrasmall{
	font-size: 9px;
}
.smallauto{
	font-size: 10px;
}
.groupchatscroll{
	height: 250px;
	overflow: auto;
}
.personalmessagescroll{
	height: 250px;
	overflow: auto;
}
.resizepanel{
	resize: vertical;
	overflow: auto;
}
#beta{
	font-size: 9px;
}
.g-recaptcha {
	display: inline-block;
}
.panel-warning > .panel-heading {
color: white;
  background-color: rgba(89, 89, 89, 0.80);
}
.hostteamdraft > .panel-heading {
color: black;
  background-color: #ffb3b3;
}
.panel{
	background-color: rgba(255, 255, 255, 0.85);
}

.navbar {
	background-color: rgba(64, 64, 64, .9);
}
.btn-shadow{
	box-shadow: 3px 3px 5px grey;
}

  </style>
</head>

<?php
	if (!isset($_SESSION['email'])){
		echo '<script src="https://www.google.com/recaptcha/api.js"></script>';
	}
?>

<!--Loader------------------------>
	  <span id="loader"></span>
<!--This script should not run until the document has loaded, so the animation is there until it is fully loaded and then it fades out-->
<script>
$(document).ready(
	function fadeout() {
		$('#loader').fadeOut(1000);
	}
);
function fadein() {
    $('#loader').fadeIn(1000);
}
function fadeinout() {
    $('#loader').fadeIn(300);
	$('#loader').fadeOut(1000);
}

</script>  
<!--Loader------------------------>

<body id="myPage">

<div class="bg-image"></div>
<nav class="navbar navbar-inverse navbar-fixed-top" id="hidenavbar">
  <div class="container-fluid">
    <div class="navbar-header">
	<a data-toggle="modal" data-target="#personalmessages" onclick="displaypersonalmessages();onlineoffline();"><span class="glyphicon glyphicon-comment" style="position:relative; top: 14px; font-size: 22px; color: #bfbfbf;"></span>
		<?php
		//display a notification symbol if there are pending messages to be reviewed
			if(isset($_SESSION['userid'])){
			$userid = $_SESSION['userid'];
			$sqlpendingmessage = "Select * FROM messages WHERE receiverid='$userid' AND reviewed=0";
			$pendingmessageresult = mysqli_query($conn, $sqlpendingmessage);
			$pendingfriendresultarray = array();
			if (mysqli_num_rows($pendingmessageresult) > 0) {
				echo '<span id="messageexclamation"><span class="glyphicon glyphicon-exclamation-sign redhx" style="position:relative; top: 8px; right: 21px; margin-right: -20px;"></span></span>';
			}else{
				echo '<span id="messageexclamation"></span>';
			}
			}
		?></a>
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand pogopointslogo confirmation" href="index.php" target="_self">PoGoPoints</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="#" data-toggle="modal" data-target="#recordsmodal" onclick="onlineoffline();displayrecords();" style="margin-left: -6px;">Records</a></li>
		
        <li><a href="#" data-toggle="modal" data-target="#faqmodal" style="margin-left: -6px;">FAQ</a></li>
  
		<li><a  href="#" data-toggle="modal" data-target="#updatesmodal" onclick="onlineoffline();" style="margin-left: -6px;">Updates</a></li>
		
		<li><a href="#" data-toggle="modal" data-target="#typingchart" onclick="onlineoffline();" style="margin-left: -6px;">Typing Chart</a></li>
		
		<li><a href="#" data-toggle="modal" data-target="#leagueinfo" onclick="onlineoffline();" style="margin-left: -6px;">League Info</a></li>
		
		<li><a href="#" data-toggle="modal" data-target="#leaderboardsmodal" onclick="onlineoffline();" style="margin-left: -6px;">Leaderboard</a></li>
      </ul>
  
      <ul class="nav navbar-nav navbar-right">
		<?php
	  if (isset($_SESSION['email'])){
		  echo '<li><a href="#" data-toggle="modal" data-target="#account" onclick="onlineoffline();" style="margin-left: -9px;"><span class="glyphicon glyphicon-user"></span> Account</a></li>';
			}
			?>
<li><a class="text-center visible-xs" data-toggle="collapse" data-target="#myNavbar"><span class="glyphicon glyphicon-chevron-up"></span></a></li>
      </ul>
	  
    </div>
	
  </div>
  
</nav>

<!--end top navbar-->



<!-- Records Modal below -->
  <div class="modal fade" id="recordsmodal" role="dialog"></div>
<!--end of Records modal-->
  
<script>
			function displayrecords() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("recordsmodal").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/displayrecords.php", true);
						xhttp.send();
					};
</script>

<!-- FAQ Modal below -->
  <div class="modal fade" id="faqmodal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title text-center"><strong>FAQ</strong></h3>
        </div>
        <div class="modal-body">
		
<small>
<div>
	<h3><strong><u>How to Join/Host</u>:</strong></h3> 
PoGoPoints allows trainers to host a variety of Tournament types that include <strong>GO:Drafts</strong>, <strong>Team:Drafts</strong>, and <strong>Ladders</strong>. Create an account by using the Sign Up button and Login to host or join a Tournament. Each Tournament type can be accessed by using the "gray" buttons for GO:Drafts, Team:Drafts, or Ladders. 
<br><br>
You can <strong>join</strong> a Tournament by using a code provided to you by the Host or found within the display link's "<strong>Message from Host:</strong>" section for one of the Tournaments. Simply click on the appropriate "gray" Tournament button, either for a GO:Draft, Team:Draft, or Ladder, and enter the <strong>code</strong> on the bottom of the pop-up in the "Enter Code" area and click "Enter". 
<br><br>
Once you enter a Tournament you will be able to navigate to that Tournament by using the appropriate "gray" button for the Tournament type and then clicking on the "Current" button to access the individual Tournaments you are in. 
<br><br>
Each Tournament has different rules to follow and they can be found under the "Rules" button for each individual Tournament. Also, more details can be found in the "<strong>Message from Host:</strong>" section where the Host can explain any custom rules for the Tournament.

<hr>
The following sections help to explain how each Tournament type functions on the website:
<br>

<h3><strong><u>GO:Drafts</u>:</strong></h3>

You can click on the “gray” GO:Drafts button to see the pop-up for accessing hosted, current, concluded and community GO:Drafts. You can Host a new GO:Draft and enter in a Title, Max CP, Create an Entry Code, and enter in a Host message. Provide the GO:Draft Entry code to your group members so that they can enter in the Tournament. The Host can remove players from the GO:Draft before it starts if someone is unable to play. Also, the Host can click the “Randomize Order on Initiation” to have the group members’ positions randomized at the start of the draft. 
<br><br>
Once the Host Initiates, the GO:Draft participants can start drafting. You cannot select duplicates in the drafting phase, UNLESS the Host changes the number of species allowed for the draft. To help speed the draft along, users can create “Auto Draft” lists for each pick that they want each round. The Auto Draft will pick for a user if they create the list and that pick is available when it is their turn. 
<br><br>
After the Draft is complete, users can utilize the “GO:Draft Battle Form” and submit battle results with other users in the GO:Draft. When you submit a battle form your opponent will need to accept the results under the “PVP Battles” section on the home page. When all users are done battling you can see the number of wins and losses under the “GO:Draft Battles” section for the GO:Draft. The Host can then conclude the Tournament and those who participated in the GO:Draft can see the GO:Draft link under the “Concluded GO:Drafts” button. 

<br>
<h3><strong><u>Team:Drafts</u>:</strong></h3>
You can click on the “gray” Team:Drafts button to see the pop-up for accessing hosted, current, concluded and community Team:Drafts. You can Host a new Team:Draft and enter in a Title, Max CP, Create an Entry Code, Battle Party Size, Number of Bans/Picks, Team Names, and enter in a Host message. Provide the Team:Draft Entry code to your group members so that they can enter in the Tournament. The Host can remove players from the Team:Draft before it starts if someone is unable to play.
<br><br>
Once the Host Initiates, the Team:Draft participants can start drafting. Team 1 will be the “red” team and bans first, while Team 2 will be the “gray” team and bans second and the teams go back and forth until all bans are made. Then, a snake draft starts between the 2 teams with Team 1 getting first pick and Team 2 getting the Final pick.  You cannot select duplicates in the drafting phase. You make your picks/bans by going to “Current Team:Drafts”, clicking on the correct Team:Draft and scrolling down to find the “Send Team:Draft Ban/Pick” button when it is your team’s turn to pick. Anyone on the team can enter in a pick/ban for their respective team.
<br><br>
After the Draft is complete, the Teams will build Battle Parties with what they drafted. Once they are done creating their parties the Host can activate “Battle Party Display” so that users can see the other team’s battle parties. Users can then utilize the “Team:Draft Battle Form” and submit battle results with other users in the Team:Draft. When you submit a battle form your opponent will need to accept the results under the “PVP Battles” section on the home page. When all users are done battling you can see the number of wins and losses under the “Team:Draft Battles” section for the Team:Draft. The Host can then conclude the Tournament and those who participated in the Team:Draft can see the Team:Draft link under the “Concluded Team:Drafts” button. 

<br>
<h3><strong><u>Ladders</u>:</strong></h3>
You can click on the “gray” Ladders button to see the pop-up for accessing hosted, current, concluded and community Ladders. You can Host a new Ladder and enter in a Title, League, Create an Entry Code, Conclusion Date, and enter in a Host message. Provide the Ladder Entry code to your group members so that they can enter in the Tournament. The Host can remove players from the Ladder. 
<br><br>
Once started users can start sending “Challenges” to other users in the Ladder. If the Host leaves in and Entry Code then users can join the Ladder anytime after it starts, but cannot join once it concludes. You can send a Ladder challenge by clicking the “Current Ladders” button and the opening the Ladder that you are currently in. Scroll down to the Challenge button and find a person that you are on the same stage with in the Ladder. Send them a Challenge and complete the battle under the “PVP Battles” section on the home page. 
<br><br>
The Champion of the Ladder is determined by whoever gets to the highest Stage on the Ladder with the Fewest Losses, tiebreakers go to those who joined the Ladder First, by the end of the month. The max number of stages for a Ladder is the total number of participants minus one. 
<br><br>
Ladders challenges can be set up to be fought with the “Duo Draft” battling style unique to PoGoPoints. Duo Draft Battles can be played in the Great, Ultra, or Master Leagues versus one of your friends. Once sent both players select one Pokemon to ban (reveal bans at the same time), then both choose their 1st round pick (reveal 1st picks at the same time), then both choose their 2nd round pick (reveal 2nd picks at the same time), and finally both choose their 3rd round pick (reveal 3rd picks at the same time). Then both players battle each other using only the 3 Pokemon that they drafted in any order they want! The Duo Draft League prevents users from repeated picks with each round and the banned Pokemon from the Ban round cannot be used, however if users choose the same Pokemon at the same time then they will both be able to use that Pokemon on their team! 


<br>
<h3><strong><u>Friends</u>:</strong></h3>
If you do not have a Tournament to challenge others in, then you can use the button for Friends and add people who have made an account on the site and send random battle types to your friends. These battles can be ranked or unranked matches and can be fought at anytime with your friends. There is also a Battle Form that you can send to your friends to record multiple matches at once instead of having to send each match individually on the website. 
<br><br>
Test your skills by trying out different leagues that include Duo Draft League, Mini Draft League, One Type League, and the Gym Leader League. Find out more information about these battle types by clicking on the “League Info” link on the navigation bar at the top of the screen!


<br>
</div>
		
<hr/>
   
<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="stadtwashlogo">
      <h4><strong>How do I Battle?</strong></h4>
    </div>
    <div>
	1) Send/Accept a battle request on this website and wait for the other player to confirm the battle in the “PVP Battles” section. The battles can be sent as "Challenges" when you join a GO:Draft, Team:Draft, or Ladder; otherwise, battles are sent as a normal battle to one friend and each battle is individually recorded.
	<br>
	2) Once the battle is confirmed, whoever sent the battle request should also be the one to send the battle request in the game. If the battle was a "Duo Draft" complete the drafting phase first before sending the battle in the game.
	<br>
	3) Complete the battle and then enter the results in the “PVP Battles” section and the system will exchange points accordingly. If the battle was send as a "Challenge" under the GO:Drafts, Team:Drafts, or Ladders then you will see your progress recorded under those tournaments.

    </div>
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>
<hr/>


<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="stadtwashlogo">
      <h4><strong>Who can I battle?</strong></h4>
    </div>
    <div>
     You can battle anyone you like. This site is tailored toward 1v1 matchups. Start off with adding your Ultra and Best friends to get battling right away. Connect with other trainers to increase your friendship level to be able to compete with one another online. You can still battle other trainers in person and enter the results online as long as both players have an account set up for PoGoPoints.
	 <br>
	 Also, players can choose to join in on one of the different Tournament structures, which are GO:Drafts, Team:Drafts, or Ladders. Send "Challenges" from these Tournaments to have you results tracked and ranked against others in the Tournament. Anyone can start a Tournament and create Entry Codes to give to others so that they can join their private or public tournaments. 
    </div>
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>
<hr/>

<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="stadtwashlogo">
      <h4><strong>What if I don't agree with who lost/won the battle?</strong></h4>
    </div>
    <div>
		Any discrepancies between players submitted battle results will result in no point exchange between players. Users can cancel battles if the opponent is attempting to cheat; however, users caught cancelling battles just because they lost can be kicked from the site. 
    </div>
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>
<hr/>

<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="stadtwashlogo">
      <h4><strong>Do I use my PC or Phone?</strong></h4>
    </div>
    <div>
	Adding the website to your phone's home screen will make the site run more like a native app by taking away the browser searchbar and allowing faster load times. (Works with most updated browsers). This can be found by visiting pogopoints.site on your smartphone's web browser (Safaria, Samsung Internet, or Google Chrome) and clicking "Add to Home Screen" or "Save Webpage". The PVP app icon should then appear on your phone's homescreen. 
    </div>
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>
<hr/>

<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="stadtwashlogo">
      <h4><strong>How do I contact other trainers?</strong></h4>
    </div>
    <div>
	For now, join the Discord group "PoGoPoints" at <a href="https://discord.gg/7usyKPA" target="_blank">https://discord.gg/7usyKPA</a> to be able to connect with other PVP trainers, ask questions, or provide feedback about the website. Also there is a Leaderboard under the navigation bar where users can display their trainer codes. Just click on a user's name on the leaderboad to see if they are displaying their trainer code. 
    </div>
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>
<hr/>

<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="stadtwashlogo">
      <h4><strong>What is the battle etiquette?</strong></h4>
    </div>
    <div>
      For this site, it is recommended that both players agree to a match and accept it online before heading to the game to complete the battle. If there is a <i>tie</i> or <i>network error</i> that does NOT result in a Win or Lose for a player then the battle should be rematched, preferrably with the same teams each player chose at the start. If however one of the players has a network glitch that prevents them from attacking, for instance, and the game tells them they LOST the match then etiquette dictates that they must accept the loss from their opponent <i>unless the opponent agrees to a fair rematch</i>. No arguments should take place on this website about who won or lost. 
    </div>
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>

</small>
			
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div><!--end of FAQ modal-->
  

<!-- Updates Modal below -->
  <div class="modal fade" id="updatesmodal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title text-center"><strong>Updates</strong></h3>
        </div>
        <div class="modal-body">

<small>

<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="stadtwashlogo">
      <h4><strong>December 19, 2019</strong></h4>
    </div>
	<ul>
  <li>Team:Draft updates.</li>
  <li>Team:Drafting with battling is now available. Users can join up in teams to ban, draft and battle Pokemon against one another. Create battle parties for each team member at the end of the drafting phase to display to the opposing team. Users can submit Team:Draft Battle Forms to keep track of the number of wins and losses for each team, and users can view the Past Team:Draft Battles to see how well each individual drafter is battling. </li>
</ul>
    <div>
    </div>
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>
<hr/>

<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="stadtwashlogo">
      <h4><strong>December 13, 2019</strong></h4>
    </div>
	<ul>
  <li>GO:Draft updates.</li>
  <li>"Waiver Wires" have been added to the GO:Draft features. Once a GO:Draft has been completed by a group the Host has the option for players to participate in Waiver Wires where the drafters have the option of exchanging some of their picked Pokemon for available Pokemon, whether they went undrafted or the GO:Draft has an increased number of species allowed. Users can create a list of which Pokemon they would like to exchange and when the Host activates the waiver wire the users have the opportunity for modifying and improving their teams before or after battling.</li>
</ul>
    <div>
    </div>
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>
<hr/>

<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="stadtwashlogo">
      <h4><strong>December 12, 2019</strong></h4>
    </div>
	<ul>
  <li>GO:Draft updates.</li>
  <li>Users can now submit GO:Draft Battles to complete Round Robins (for instance) to determine who is the best drafter! Past battles and the GO:Draft Battle form can be found under the Current GO:Drafts button and also be viewed on the Shareable GO:Draft Links!</li>
</ul>
    <div>
    </div>
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>
<hr/>

<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="stadtwashlogo">
      <h4><strong>September 25, 2019</strong></h4>
    </div>
	<ul>
  <li>GO:Draft updates.</li>
  <li>Auto Draft is now available. Users can select future picks to be auto drafted for them so that they do not have to log back in if it is their turn to pick and they have an available pick on their auto draft lists!</li>
  <li>Hosts can now manually put in duplicate picks for drafts.</li>
</ul>
    <div>
    </div>
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>
<hr/>

<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="stadtwashlogo">
      <h4><strong>September 17, 2019</strong></h4>
    </div>
	<ul>
  <li>Soft launch for GO:Draft application.</li>
  <li>Users can start using the GO:Draft to host "Snake" Drafts with their groups of friends.</li>
  <li>These drafts can be linked to Ladder Tourneys once you finish the draft with your friends.</li>
</ul>
    <div>
    </div>
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>
<hr/>

<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
    <div class="stadtwashlogo">
      <h4><strong>June 12, 2019</strong></h4>
    </div>
	<ul>
  <li>Ladder Tourneys added to the website.</li>
  <li>Host your own Ladder tourney and invite your friends. Users can choose from a variety of battle styles, unranked or ranked Ladder Tourneys and the tourneys can go as long as the Host desires.</li>
  <li>Join as many Ladder Tourneys as you like.</li>
</ul>
    <div>
    </div>
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>
<hr/>

		
</small>		
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div><!--end of Updates modal-->


<!-- Typing Chart Modal below -->
  <div class="modal fade" id="typingchart" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title text-center"><strong>Typing Chart</strong></h3>
        </div>
        <div class="modal-body">
  <div class="row">
	<div class="table-responsive">
  <table class="table table-bordered table-striped table-hover">
    <thead>
      <tr>
        <th>Type</th>
		<th>Increased Damage Against</th>
		<th>Decreased Damage Against</th>
		<th>Increased Damage From</th>
		<th>Decreased Damage From</th>
		<th class="visible-sm visible-xs">Type</th>
      </tr>
    </thead>
    <tbody class="text-center">
      <tr style="background-color:#b3ffb3;">
        <td>Bug<br><img src="images/5.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Dark, Grass, Psychic</td>
		<td>Fighting, Flying, Poison, Ghost, Steel, Fire, Fairy</td>
		<td>Flying, Rock, Fire</td>
		<td>Fighting, Ground, Grass</td>
		<td class="visible-sm visible-xs">Bug<br><img src="images/5.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
      <tr style="background-color:#999999;">
        <td>Dark<br><img src="images/6.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Ghost, Psychic</td><td>Fighting, Dark, Fairy</td><td>Fighting, Bug, Fairy</td><td>Ghost, Psychic, Dark</td>
		<td class="visible-sm visible-xs">Dark<br><img src="images/6.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
      <tr style="background-color:#8cb3d9;">
        <td>Dragon<br><img src="images/7.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Dragon</td><td>Steel, Fairy</td><td>Ice, Dragon, Fairy</td><td>Fire, Water, Grass, Electric</td>
		<td class="visible-sm visible-xs">Dragon<br><img src="images/7.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr style="background-color:#ffffb3;">
        <td>Electric<br><img src="images/8.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Flying, Water</td><td>Ground, Grass, Electric, Dragon</td><td>Ground</td><td>Flying, Steel, Electric</td>
		<td class="visible-sm visible-xs">Electric<br><img src="images/8.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>	  
	  <tr style="background-color:#ffcce6;">
        <td>Fairy<br><img src="images/9.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Fighing, Dragon, Dark</td><td>Poison, Steel, Fire</td><td>Poison, Steel</td><td>Fighting, Bug, Dragon, Dark</td>
		<td class="visible-sm visible-xs">Fairy<br><img src="images/9.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr style="background-color:#d9b38c;">
        <td>Fighting<br><img src="images/10.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Normal, Rock, Steel, Ice, Dark</td><td>Flying, Poison, Psychic, Bug, Ghost, Fairy</td><td>Flying, Psychic, Fairy</td><td>Rock, Bug, Dark</td>
		<td class="visible-sm visible-xs">Fighting<br><img src="images/10.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr style="background-color:#ff6666;">
        <td>Fire<br><img src="images/11.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Bug, Steel, Grass, Ice</td><td>Rock, Fire, Water, Dragon</td><td>Ground, Rock, Water</td><td>Bug, Steel, Fire, Grass, Ice</td>
		<td class="visible-sm visible-xs">Fire<br><img src="images/11.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr style="background-color:#ccccff;">
        <td>Flying<br><img src="images/102.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Fighting, Bug, Grass</td><td>Rock, Steel, Electric</td><td>Rock, Electric, Ice</td><td>Fighting, Ground, Bug, Grass</td>
		<td class="visible-sm visible-xs">Flying<br><img src="images/12.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr style="background-color: #ff99ff;">
        <td>Ghost<br><img src="images/13.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Ghost, Psychic</td><td>Normal, Dark</td><td>Ghost, Dark</td><td>Normal, Fighting, Poison, Bug</td>
		<td class="visible-sm visible-xs">Ghost<br><img src="images/13.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr>
        <td><strong>Type</strong></td>
        <td><strong>Increased Damage Against</strong></td>
        <td><strong>Decreased Damage Against</strong></td>
        <td><strong>Increased Damage From</strong></td>
        <td><strong>Decreased Damage From</strong></td>
		<td class="visible-sm visible-xs"><strong>Type</strong></td>
      </tr>
	  <tr style="background-color:#9fdf9f;">
        <td>Grass<br><img src="images/14.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Gound, Rock, Water</td><td>Flying, Poison, Bug, Steel, Fire, Grass, Dragon</td><td>Flying, Poison, Bug, Fire, Ice</td><td>Ground, Water, Grass, Electric</td>
		<td class="visible-sm visible-xs">Grass<br><img src="images/14.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr style="background-color:#dfbe9f;">
        <td>Ground<br><img src="images/15.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Poison, Rock, Steel, Fire, Electric</td><td>Flying, Bug, Grass</td><td>Water, Grass, Ice</td><td>Poison, Rock, Electric</td>
		<td class="visible-sm visible-xs">Ground<br><img src="images/15.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr style="background-color:#99ffff;">
        <td>Ice<br><img src="images/16.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Flying, Ground, Grass, Dragon</td><td>Steel, Fire, Water, Ice</td><td>Fighting, Rock, Steel, Fire</td><td>Ice</td>
		<td class="visible-sm visible-xs">Ice<br><img src="images/16.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr>
        <td>Normal<br><img src="images/17.png" alt="League-Emblem" width="24" height="25"></td>
		<td>NONE</td><td>Rock, Ghost, Steel</td><td>Fighting</td><td>Ghost</td>
		<td class="visible-sm visible-xs">Normal<br><img src="images/17.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr style="background-color:#ff80ff;">
        <td>Poison<br><img src="images/18.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Grass, Fairy</td><td>Poison, Ground, Rock, Ghost, Steel</td><td>Ground, Psychic</td><td>Fighting, Poison, Grass, Fairy</td>
		<td class="visible-sm visible-xs">Poison<br><img src="images/18.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr style="background-color:#ff6699;">
        <td>Psychic<br><img src="images/19.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Fighting, Poison</td><td>Steel, Psychic, Dark</td><td>Bug, Ghost, Dark</td><td>Fighting, Psychic</td>
		<td class="visible-sm visible-xs">Psychic<br><img src="images/19.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr style="background-color:#ecd9c6;">
        <td>Rock<br><img src="images/20.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Flying, Bug, Fire, Ice</td><td>Fighting, Ground, Steel</td><td>Fighting, Ground, Steel, Water, Grass</td><td>Normal, Flying, Poison, Fire</td>
		<td class="visible-sm visible-xs">Rock<br><img src="images/20.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr style="background-color:#cccccc;">
        <td>Steel<br><img src="images/21.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Rock, Ice, Fairy</td><td>Steel, Fire, Water, Electric</td><td>Fighting, Ground, Fire</td><td>Normal, Flying, Poison, Rock, Bug, Steel, Grass, Psychic, Ice, Dragon, Fairy</td>
		<td class="visible-sm visible-xs">Steel<br><img src="images/21.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr style="background-color:#b3c6ff;">
        <td>Water<br><img src="images/22.png" alt="League-Emblem" width="24" height="25"></td>
		<td>Ground, Rock, Fire</td><td>Water, Grass, Dragon</td><td>Grass, Electric</td><td>Steel, Fire, Water, Ice</td>
		<td class="visible-sm visible-xs">Water<br><img src="images/22.png" alt="League-Emblem" width="24" height="25"></td>
      </tr>
	  <tr>
        <td><strong>Type</strong></td>
        <td><strong>Increased Damage Against</strong></td>
        <td><strong>Decreased Damage Against</strong></td>
        <td><strong>Increased Damage From</strong></td>
        <td><strong>Decreased Damage From</strong></td>
		<td class="visible-sm visible-xs"><strong>Type</strong></td>
      </tr>
    </tbody>
  </table>
  
  </div>
  </div>
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div><!--end of Typing Chart modal-->
		
		
		<!-- GO:Draft Modal below -->
  <div class="modal fade" id="godraft" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
	  
	  <!--mobile header-->
        <div class="modal-header hidden-lg hidden-md hidden-sm" style="position: -webkit-sticky; position: sticky; top: 0px; z-index:987654321; border-bottom: 0 none; margin-bottom: -28px;">
         <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
        </div>
		
		<!--computer header-->
		<div class="modal-header hidden-xs">
         <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title text-center"><strong>GO:Draft</strong></h3>
        </div>
		
        <div class="modal-body">
  <div class="row">
  <div class="container-fluid" style="padding: 8px 8px 8px 8px;">
  
  <!--mobile header view-->
  <h3 class="text-center hidden-lg hidden-md hidden-sm" style="padding: -20px 0px 0px 0px; margin: -20px 0px 0px 0px;"><strong>GO:Draft</strong></h3>
	<hr class="hidden-lg hidden-md hidden-sm">
	
	<button type="button" class="btn btn-md btn-basic btn-block text-left btn-shadow" onclick="displaygodrafthosting();fadeinout();onlineoffline();" data-toggle="collapse" data-target="#hostinggodraft"><strong><span class="glyphicon glyphicon-tower"></span> Host GO:Drafts</strong></button>
	<div id="hostinggodraft" class="collapse"></div>
<script>
	function displaygodrafthosting() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("hostinggodraft").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/godrafthostingarea.php", true);
		xhttp.send();
	}
	
	function godraftaddmember(godraftid){
		var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/godraftaddmember.php?memberid="+document.getElementById("memberselect"+godraftid).value+"&godraftid="+godraftid, true);
		xhttp.send();
	}	
	
	function godraftremovemember(godraftid){
		var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/godraftremovemember.php?memberid="+document.getElementById("memberremove"+godraftid).value+"&godraftid="+godraftid, true);
		xhttp.send();
	}
	
	function godraftrandomizemembers(godraftid){
		var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/godraftrandomizemembers.php?godraftid="+godraftid, true);
		xhttp.send();
	}

function godraftrandomizemembersoninitiate(godraftid){
		var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/godraftrandomizemembersoninitiate.php?godraftid="+godraftid, true);
		xhttp.send();
	}		
	
	
	function sendgodraftmessage(godraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/godraftmessage.php?godraftmessage="+document.getElementById("godraftmessage"+godraftid).value+"&godraftid="+godraftid, true);
			xhttp.send();
  };	
  function sendgodrafttitle(godraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/godrafttitle.php?godrafttitle="+document.getElementById("godrafttitle"+godraftid).value+"&godraftid="+godraftid, true);
			xhttp.send();
  };   
  function sendgodraftcode(godraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var coderesponse = this.responseText;
				alert(coderesponse);
			}
			};
			xhttp.open("POST","i/godraftcode.php?godraftcode="+document.getElementById("godraftcode"+godraftid).value+"&godraftid="+godraftid, true);
			xhttp.send();
  };  
  function sendnumberofspecies(godraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/godraftnumberofspecies.php?numberofspecies="+document.getElementById("numberofspecies"+godraftid).value+"&godraftid="+godraftid, true);
			xhttp.send();
  };  
</script>
	<hr>
	<button type="button" class="btn btn-md btn-info btn-block btn-shadow" onclick="displaygodraftcurrent();fadeinout();onlineoffline();hideexclamation(2);" data-toggle="collapse" data-target="#currentgodrafts"><strong>
	  <?php
		//display a notification symbol if it is time to GO DRAFT!
			if(isset($_SESSION['userid'])){
				$timetodraft=0;
			$userid = $_SESSION['userid'];
			$activegodrafts = "Select * FROM godrafttrainers WHERE godrafttrainerid='$userid' AND godraftconcluded='0';";
			$activegodraftresult = mysqli_query($conn, $activegodrafts);
			$activegodraftarray = array();
			if (mysqli_num_rows($activegodraftresult) > 0) {
				while($row = mysqli_fetch_assoc($activegodraftresult)){
				$activegodraftarray[] = $row;
			}
			foreach($activegodraftarray as $eachactivegodraft){
				$eachactivegodraftid=$eachactivegodraft['godraftid'];
				$eachactivegodraftcount1=$eachactivegodraft['count1'];
				$eachactivegodraftcount2=$eachactivegodraft['count2'];
				$eachactivegodraftcount3=$eachactivegodraft['count3'];
				$eachactivegodraftcount4=$eachactivegodraft['count4'];
				$eachactivegodraftcount5=$eachactivegodraft['count5'];
				$eachactivegodraftcount6=$eachactivegodraft['count6'];
				
				$trainersonsamestage = "Select * FROM godrafts WHERE id='$eachactivegodraftid' AND concluded='0' AND initiated='1' AND (pickcount='$eachactivegodraftcount1' OR pickcount='$eachactivegodraftcount2' OR pickcount='$eachactivegodraftcount3' OR pickcount='$eachactivegodraftcount4' OR pickcount='$eachactivegodraftcount5' OR pickcount='$eachactivegodraftcount6');";
				$trainersonsamestageresult = mysqli_query($conn, $trainersonsamestage);
				if (mysqli_num_rows($trainersonsamestageresult) > 0) {
					$timetodraft=1;
				}
			}
			
				if($timetodraft==1){
					echo '<span id="exclamation2" class="glyphicon glyphicon-exclamation-sign redhx"></span>';
				}
			}else{
				echo '<span id="exclamation2"></span>';
			}
			}else{
				echo '<span id="exclamation2"></span>';
			}
?>
	
	<span class="glyphicon glyphicon-refresh"></span> Current GO:Drafts</strong></button>
		<div id="currentgodrafts" class="collapse"></div>
		<script>
	function displaygodraftcurrent() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("currentgodrafts").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/godraftcurrentarea2.php", true);
		xhttp.send();
	};
	
	function displaygodraftcurrenteachdraft(godraftid) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("eachgodraft"+godraftid).innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/godraftcurrentareaeachgodraft.php?eachgodraft="+godraftid, true);
		xhttp.send();
	};
	function displaygodraftcurrentautodraft(godraftid) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("autodraftwaiverwire"+godraftid).innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/godraftautodraftdisplay.php?eachgodraft="+godraftid, true);
		xhttp.send();
	};
	
function displaygodraftcurrentwaiverwire(godraftid) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("autodraftwaiverwire"+godraftid).innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/godraftwaiverwiredisplay.php?eachgodraft="+godraftid, true);
		xhttp.send();
	};
	
	//prevent mini icons to be called in great succesion with on change event!
	var getminiicontimergodraft;
	var getminiicontimerauto;
	var getminiicontimerwaiver;
	var getminiicontimer;
	function getminiicongodraft(godraftid) {
						clearTimeout(getminiicontimergodraft);
						getminiicontimergodraft = setTimeout(fetchminiicongodraft, 400);
						function fetchminiicongodraft(){	
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("displayminiicongodraft"+godraftid).innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/getgodraftminiicon.php?godraftpick="+document.getElementById("monselectgodraft"+godraftid).value+"&godraftid="+godraftid, true);
						xhttp.send();
						}
					};
	function getversususer(godraftid) {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("godraftversusteam"+godraftid).innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/godraftversususer.php?versususer="+document.getElementById("godraftversususer"+godraftid).value+"&godraftid="+godraftid, true);
						xhttp.send();
					};
	function getminiicongodraftauto(godraftid) {
						clearTimeout(getminiicontimerauto);
						getminiicontimerauto = setTimeout(fetchminiiconauto, 400);
					function fetchminiiconauto(){	
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("displayminiicongodraftauto"+godraftid).innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/getgodraftminiicon.php?godraftpick="+document.getElementById("monselectgodraftauto"+godraftid).value+"&godraftid="+godraftid, true);
						xhttp.send();
					}
				};
	function getminiicongodraftwaiver(godraftid) {
						clearTimeout(getminiicontimerwaiver);
						getminiicontimerwaiver = setTimeout(fetchminiiconwaiver, 400);
					function fetchminiiconwaiver(){	
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("displayminiicongodraftwaiver"+godraftid).innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/getgodraftminiicon.php?godraftpick="+document.getElementById("monselectgodraftwaiver"+godraftid).value+"&godraftid="+godraftid, true);
						xhttp.send();
					}
				};
					
	function sendgodraftpick(godraftid){
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("displaygodraftresponse"+godraftid).innerHTML = this.responseText;
							}
						};
						xhttp.open("POST","i/godraftpickupdate.php?godraftpick="+document.getElementById("monselectgodraft"+godraftid).value+"&godraftid="+godraftid, true);
						xhttp.send();
					};
	function sendgodraftpickauto(godraftid){
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								//refresh auto draft area once the auto picks lists have been updated
								displaygodraftcurrentautodraft(godraftid);
							}
						};
						xhttp.open("POST","i/godraftpickupdateauto.php?godraftpick="+document.getElementById("monselectgodraftauto"+godraftid).value+"&godraftid="+godraftid+"&picklist="+document.getElementById("picklist"+godraftid).value, true);
						xhttp.send();
					};
	function sendgodraftpickwaiver(godraftid){
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								//refresh auto draft area once the auto picks lists have been updated
								displaygodraftcurrentwaiverwire(godraftid);
							}
						};
						xhttp.open("POST","i/godraftpickupdatewaiver.php?godraftpick="+document.getElementById("monselectgodraftwaiver"+godraftid).value+"&godraftid="+godraftid+"&picklist="+document.getElementById("picklist"+godraftid).value, true);
						xhttp.send();
					};
	function godraftremoveautopick(eachpick,godraftid,listnumber){
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								//refresh auto draft area once the auto picks lists have been updated
								displaygodraftcurrentautodraft(godraftid);
							}
						};
						xhttp.open("POST","i/godraftpicklistremove.php?godraftpick="+eachpick+"&godraftid="+godraftid+"&picklist="+listnumber, true);
						xhttp.send();
					};
	function godraftremovewaiverpick(eachpick,godraftid,listnumber){
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								//refresh auto draft area once the auto picks lists have been updated
								displaygodraftcurrentwaiverwire(godraftid);
							}
						};
						xhttp.open("POST","i/godraftwaiverwireremove.php?godraftpick="+eachpick+"&godraftid="+godraftid+"&picklist="+listnumber, true);
						xhttp.send();
					};
</script>
	<hr>
	<button type="button" class="btn btn-md btn-primary btn-block btn-shadow" onclick="displaygodraftconcluded();fadeinout();onlineoffline();" data-toggle="collapse" data-target="#concludedgodrafts"><strong><span class="glyphicon glyphicon-ok"></span> Concluded GO:Drafts</strong></button>
		<div id="concludedgodrafts" class="collapse"></div>
<script>
	function displaygodraftconcluded() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("concludedgodrafts").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/godraftconcludedarea2.php", true);
		xhttp.send();
	}

	function displaygodraftconcludedeachdraft(godraftid) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("concludedgodrafts"+godraftid).innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/godraftconcludedareaeachgodraft.php?eachgodraft="+godraftid, true);
		xhttp.send();
	};
</script>
	<hr>

	  <div class="panel panel-default">
  <div class="panel-heading">
   <strong>Enter GO:Draft:</strong> <input class="form-control" type="text" id="godraftentrycodetrainer" name="godraftentrycodetrainer" minlength="1" maxlength="255" autocomplete="off" placeholder="TYPE GO:DRAFT CODE HERE" required>
	<div class="text-right">	
	<button id="sentgodraftentrytrainercodebtn" onclick="sendgodraftentrycodetrainer();onlineoffline();fadeinout();" class="btn btn-sm btn-default">Enter <span class="glyphicon glyphicon-send"></span></button>
	</div>
	<div id="godraftentrycoderesponsetrainer"></div>
	</div>
	</div>
<script>
	function sendgodraftentrycodetrainer(){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
				document.getElementById("godraftentrycoderesponsetrainer").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST","i/godraftenterdraft.php?entrycode="+document.getElementById("godraftentrycodetrainer").value, true);
		xhttp.send();	
	};
</script>
	<hr>
	<button type="button" class="btn btn-md btn-block btn-shadow" style="background-color:#4d4d4d;color:white;" onclick="displaygodraftcommunity();fadeinout();" data-toggle="collapse" data-target="#communitygodrafts"><strong><span class="glyphicon glyphicon-eye-open"></span> Community Links</strong></button>
		<div id="communitygodrafts" class="collapse"></div>
<script>
	function displaygodraftcommunity() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("communitygodrafts").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/godraftcommunity.php", true);
		xhttp.send();
	}
</script>
	
	</div>


  </div>
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div><!--end of GO:Draft modal-->
  
  
		<!-- team:draft Modal below -->
  <div class="modal fade" id="teamdrafts" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
	  
        <!--mobile header-->
        <div class="modal-header hidden-lg hidden-md hidden-sm" style="position: -webkit-sticky; position: sticky; top: 0px; z-index:987654321; border-bottom: 0 none; margin-bottom: -28px;">
         <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
        </div>
		
		<!--computer header-->
		<div class="modal-header hidden-xs">
         <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title text-center"><strong>Team:Draft</strong></h3>
        </div>
		
        <div class="modal-body">
  <div class="row">
  <div class="container-fluid" style="padding: 8px 8px 8px 8px;">
  
  <!--mobile header view-->
  <h3 class="text-center hidden-lg hidden-md hidden-sm" style="padding: -20px 0px 0px 0px; margin: -20px 0px 0px 0px;"><strong>Team:Draft</strong></h3>
	<hr class="hidden-lg hidden-md hidden-sm">
  
	<button type="button" class="btn btn-md btn-basic btn-block text-left btn-shadow" onclick="displayteamdrafthosting();fadeinout();onlineoffline();" data-toggle="collapse" data-target="#hostingteamdraft"><strong><span class="glyphicon glyphicon-tower"></span> Host Team:Drafts</strong></button>
	<div id="hostingteamdraft" class="collapse"></div>
<script>
	function displayteamdrafthosting() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("hostingteamdraft").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/teamdrafthostingarea.php", true);
		xhttp.send();
	}
	
	function teamdraftaddmember(teamdraftid){
		var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/teamdraftaddmember.php?memberid="+document.getElementById("teammemberselect"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
		xhttp.send();
	}	
	
	function teamdraftremovemember(teamdraftid){
		var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/teamdraftremovemember.php?memberid="+document.getElementById("teammemberremove"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
		xhttp.send();
	}
	
	function teamdraftrandomizemembers(teamdraftid){
		var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/teamdraftrandomizemembers.php?teamdraftid="+teamdraftid, true);
		xhttp.send();
	}

function teamdraftrandomizemembersoninitiate(teamdraftid){
		var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/teamdraftrandomizemembersoninitiate.php?teamdraftid="+teamdraftid, true);
		xhttp.send();
	}		
	
	
	function sendteamdraftmessage(teamdraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/teamdraftmessage.php?teamdraftmessage="+document.getElementById("teamdraftmessage"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
			xhttp.send();
  };	
  function sendteamdrafttitle(teamdraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/teamdrafttitle.php?teamdrafttitle="+document.getElementById("teamdrafttitle"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
			xhttp.send();
  };  
  function sendteam1name(teamdraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/team1name.php?team1name="+document.getElementById("team1name"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
			xhttp.send();
  };
    function sendteam2name(teamdraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/team2name.php?team2name="+document.getElementById("team2name"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
			xhttp.send();
  }; 
  function sendteam1code(teamdraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var coderesponse = this.responseText;
				alert(coderesponse);
			}
			};
			xhttp.open("POST","i/team1code.php?team1code="+document.getElementById("team1code"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
			xhttp.send();
  };
  function sendteam2code(teamdraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var coderesponse = this.responseText;
				alert(coderesponse);
			}
			};
			xhttp.open("POST","i/team2code.php?team2code="+document.getElementById("team2code"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
			xhttp.send();
  };   
  function sendnumberofpickseachteam(teamdraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/teamdraftnumberofpickseachteam.php?numberofpickseachteam="+document.getElementById("numberofpickseachteam"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
			xhttp.send();
  };
  function sendnumberofbanseachteam(teamdraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/teamdraftnumberofbanseachteam.php?numberofbanseachteam="+document.getElementById("numberofbanseachteam"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
			xhttp.send();
  }; 
  function sendteamdisplayparty(teamdraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/teamdraftdisplayparty.php?teamdisplayparty="+document.getElementById("teamdisplayparty"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
			xhttp.send();
  };   
function sendteampartysize(teamdraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/teamdraftpartysize.php?teampartysize="+document.getElementById("teampartysize"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
			xhttp.send();
  }; 
function sendteampartyduplicates(teamdraftid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/teamdraftpartyduplicates.php?teampartyduplicates="+document.getElementById("teampartyduplicates"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
			xhttp.send();
  }; 
</script>
	<hr>
	<button type="button" style="background-color:#ff4d4d;" class="btn btn-md btn-block btn-shadow" onclick="displayteamdraftcurrent();fadeinout();onlineoffline();" data-toggle="collapse" data-target="#currentteamdrafts"><strong>
	
	<span class="glyphicon glyphicon-refresh"></span> Current Team:Drafts</strong></button>
		<div id="currentteamdrafts" class="collapse"></div>
		<script>
	function displayteamdraftcurrent() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("currentteamdrafts").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/teamdraftcurrentarea2.php", true);
		xhttp.send();
	};
	
	function displayteamdraftcurrenteachdraft(teamdraftid) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("eachteamdraft"+teamdraftid).innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/teamdraftcurrentareaeachteamdraft.php?eachteamdraft="+teamdraftid, true);
		xhttp.send();
	};
	function displayteamdraftcurrentautodraft(teamdraftid) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("eachteamdraft"+teamdraftid).innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/teamdraftcurrentareaeachteamdraft.php?eachteamdraft="+teamdraftid, true);
		xhttp.send();
	};
	
	//prevent mini icons to be called in great succesion with on change event!
	var getminiicontimerteamdraft;
	var teamgetminiicontimerauto;
	var teamgetminiicontimer;
	function getminiiconteamdraft(teamdraftid) {
						clearTimeout(getminiicontimerteamdraft);
						getminiicontimerteamdraft = setTimeout(fetchminiiconteamdraft, 400);
						function fetchminiiconteamdraft(){	
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("displayminiiconteamdraft"+teamdraftid).innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/getteamdraftminiicon.php?teamdraftpick="+document.getElementById("monselectteamdraft"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
						xhttp.send();
						}
					};
	function getminiiconteamdraftauto(teamdraftid) {
						clearTimeout(getminiicontimerauto);
						teamgetminiicontimerauto = setTimeout(teamfetchminiiconauto, 400);
					function teamfetchminiiconauto(){	
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("displayminiiconteamdraftauto"+teamdraftid).innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/getteamdraftminiicon.php?teamdraftpick="+document.getElementById("monselectteamdraftauto"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
						xhttp.send();
					}
					};
					
	function sendteamdraftpick(teamdraftid){
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("displayteamdraftresponse"+teamdraftid).innerHTML = this.responseText;
							}
						};
						xhttp.open("POST","i/teamdraftpickupdate.php?teamdraftpick="+document.getElementById("monselectteamdraft"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
						xhttp.send();
					};
	function sendteamdraftban(teamdraftid){
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("displayteamdraftresponse"+teamdraftid).innerHTML = this.responseText;
							}
						};
						xhttp.open("POST","i/teamdraftbanupdate.php?teamdraftpick="+document.getElementById("monselectteamdraft"+teamdraftid).value+"&teamdraftid="+teamdraftid, true);
						xhttp.send();
					};
					
	function sendteamdraftpickparty(teamdraftid){
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								//refresh auto draft area once the auto picks lists have been updated
								displayteamdraftcurrentautodraft(teamdraftid);
							}
						};
						xhttp.open("POST","i/teamdraftpickupdateparty.php?teamdraftpick="+document.getElementById("monselectteamdraftparty"+teamdraftid).value+"&teamdraftid="+teamdraftid+"&teammate="+document.getElementById("teammate"+teamdraftid).value, true);
						xhttp.send();
					};
					
	function teamdraftremovepick(eachpick,tablerowid,teamdraftid){
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								//refresh auto draft area once the auto picks lists have been updated
								displayteamdraftcurrentautodraft(teamdraftid);
							}
						};
						xhttp.open("POST","i/teamdraftpickremove.php?teamdraftpick="+eachpick+"&tablerowid="+tablerowid+"&teamdraftid="+teamdraftid, true);
						xhttp.send();
					};
</script>
	<hr>
	<button type="button" style="background-color:#e60000;" class="btn btn-md btn-block btn-shadow" onclick="displayteamdraftconcluded();fadeinout();onlineoffline();" data-toggle="collapse" data-target="#concludedteamdrafts"><strong><span class="glyphicon glyphicon-ok"></span> Concluded Team:Drafts</strong></button>
		<div id="concludedteamdrafts" class="collapse"></div>
<script>
	function displayteamdraftconcluded() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("concludedteamdrafts").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/teamdraftconcludedarea2.php", true);
		xhttp.send();
	}

	function displayteamdraftconcludedeachdraft(teamdraftid) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("concludedteamdrafts"+godraftid).innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/teamdraftconcludedareaeachteamdraft.php?eachteamdraft="+teamdraftid, true);
		xhttp.send();
	};
</script>

<!--
<hr>
	<button type="button" style="background-color:gray;color:white;" class="btn btn-md btn-block btn-shadow" onclick="displayteamdraftuserratints();fadeinout();onlineoffline();" data-toggle="collapse" data-target="#userratingsteamdrafts"><strong><span class="glyphicon glyphicon-equalizer"></span> Your Ratings (Team:Drafts)</strong></button>
		<div id="userratingsteamdrafts" class="text-left collapse"></div>
-->
<script>
	function displayteamdraftuserratints() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("userratingsteamdrafts").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/teamdraftuserratings.php", true);
		xhttp.send();
	}
</script>

	<hr>

	  <div class="panel panel-default">
  <div class="panel-heading">
   <strong>Enter Team:Draft:</strong> <input class="form-control" type="text" id="teamdraftentrycodetrainer" name="teamdraftentrycodetrainer" minlength="1" maxlength="255" autocomplete="off" placeholder="TYPE TEAM:DRAFT CODE HERE" required>
	<div class="text-right">	
	<button id="sentteamdraftentrytrainercodebtn" onclick="sendteamdraftentrycodetrainer();fadeinout();" class="btn btn-sm btn-default">Enter <span class="glyphicon glyphicon-send"></span></button>
	</div>
	<div id="teamdraftentrycoderesponsetrainer"></div>
	</div>
	</div>
<script>
	function sendteamdraftentrycodetrainer(){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
				document.getElementById("teamdraftentrycoderesponsetrainer").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST","i/teamdraftenterdraft.php?teamentrycode="+document.getElementById("teamdraftentrycodetrainer").value, true);
		xhttp.send();	
	};
</script>

	<hr>
	<button type="button" class="btn btn-md btn-block btn-shadow" style="background-color:#4d4d4d;color:white;" onclick="displayteamdraftcommunity();fadeinout();" data-toggle="collapse" data-target="#communityteamdrafts"><strong><span class="glyphicon glyphicon-eye-open"></span> Community Links</strong></button>
		<div id="communityteamdrafts" class="collapse"></div>
<script>
	function displayteamdraftcommunity() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("communityteamdrafts").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/teamdraftcommunity.php", true);
		xhttp.send();
	}
</script>

	</div>
  </div>
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div><!--end of TEAM:Draft modal-->

<!-- Account Modal below -->
  <div class="modal fade" id="account" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title text-center"><strong>My Account</strong></h3>
        </div>
        <div class="modal-body">
		
		<!--Start of Update User Icons-->
		<script>
	$(document).ready(function(){
		$(".trainerusericonupdate").click(function(){
			$("#toggleusericondisplay").toggle(700);
		});
	});
	function usericondisplay(){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
				document.getElementById("toggleusericondisplay").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST","i/usericondisplay.php", true);
		xhttp.send();	
	};
</script>
		<button id="trainerusericonupdate" type="button" class="btn btn-default trainerusericonupdate" onclick="usericondisplay();">
			<span class="glyphicon glyphicon-chevron-right"></span>
			<strong><img class="img-thumbnail" src="usericons/<?php if (isset($_SESSION['email'])){echo $_SESSION['usericon'];}?>.png" alt="League-Emblem" width="40" height="40"> User Icon:</strong> 
			
		</button> 
	
	<div id="toggleusericondisplay" hidden>
	</div>
		<hr>
		<!--End of update user icons-->
		
<!--Updating Trainer/Pogoname-->
<script>
	$(document).ready(function(){
		$("#trainernameupdate").click(function(){
			$("#toggletrainername").toggle(700);
		});
	});
</script>
	<button id="trainernameupdate" type="button" class="btn btn-default">
	  <span class="glyphicon glyphicon-chevron-right"></span>
	  <strong> Trainer Name:</strong> 
	  <?php if (isset($_SESSION['email'])){echo $_SESSION['trainername'];}?>
	</button> 
	
	<div id="toggletrainername" hidden>
		<br/>
      <form class="form-horizontal" action="i/newtrainername.php" method="POST">
		<div class="form-group">
			<label class="control-label col-sm-2">Name: </label>
			<div class="col-sm-10">
			<input class="form-control" type="text" name="newtrainername" id="newtrainername" placeholder="New Name" required  maxlength="30" autocomplete="off" autocapitalize="words">
			</div><br>
		</div>
		<div class="container-fluid text-center">
			<button class= "btn btn-default" style="background-color:#ff9999" type="submit" name="submit" onclick="fadeinout();">Update</button>
		</div>
	  </form>
	  </div>
<hr><!--end update-->

<!--Updating Friend Code-->
<script>
	$(document).ready(function(){
		$("#friendcodeupdate").click(function(){
			$("#togglefriendcode").toggle(700);
		});
	});
</script>
	<button id="friendcodeupdate" type="button" class="btn btn-default">
	  <span class="glyphicon glyphicon-chevron-right"></span>
	  <strong> Friend Code:</strong> 
	  <?php if (isset($_SESSION['email'])){echo $_SESSION['friendcode'];}?>
	</button> 
	
	<div id="togglefriendcode" hidden>
		<br/>
      <form class="form-horizontal" action="i/newfriendcode.php" method="POST">
		<div class="form-group">
			<label class="control-label col-sm-2">Code: </label>
			<div class="col-sm-10">
			<input class="form-control" type="text" name="newfriendcode" id="newfriendcode" placeholder="Friend Code" required maxlength="14" minlength="12" autocomplete="off">
			</div><br>
		</div>
		<div class="container-fluid text-center">
			<button class= "btn btn-defualt" style="background-color:#ff9999" type="submit" name="submit" onclick="fadeinout();">Update</button>
		</div>
	  </form>
	  </div>
<hr><!--end update-->

<!--Updating level-->
<script>
	$(document).ready(function(){
		$("#trainerlevelupdate").click(function(){
			$("#toggletrainerlevel").toggle(700);
		});
	});
</script>
	<button id="trainerlevelupdate" type="button" class="btn btn-default">
	  <span class="glyphicon glyphicon-chevron-right"></span>
	  <strong> Level:</strong> 
	  <?php if (isset($_SESSION['email'])){echo $_SESSION['level'];}?>
	</button> 
	
	<div id="toggletrainerlevel" hidden>
		<br/>
      <form class="form-horizontal" action="i/newtrainerlevel.php" method="POST">
		<div class="form-group">
			<label class="control-label col-sm-2">Level: </label>
			<div class="col-sm-10">
			<select class="form-control" name="newtrainerlevel" id="newtrainerlevel" required>
				<option value="">Select</option>
				<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option>
			</select>
			</div><br>
		</div>
		<div class="container-fluid text-center">
			<button class= "btn btn-defualt" style="background-color:#ff9999" type="submit" name="submit" onclick="fadeinout();">Update</button>
		</div>
	  </form>
	  </div>
<hr><!--end update-->

<!--Updating Team-->
<script>
	$(document).ready(function(){
		$("#trainerteamupdate").click(function(){
			$("#toggletrainerteam").toggle(700);
		});
	});
</script>
	<button id="trainerteamupdate" type="button" class="btn btn-default">
	  <span class="glyphicon glyphicon-chevron-right"></span>
	  <strong> Team:</strong> 
	  <?php if (isset($_SESSION['email'])){echo $_SESSION['team'];}?>
	</button> 
	
	<div id="toggletrainerteam" hidden>
		<br/>
      <form class="form-horizontal" action="i/newtrainerteam.php" method="POST">
		<div class="form-group">
			<label class="control-label col-sm-2">Team: </label>
			<div class="col-sm-10">
			<select class="form-control" name="newtrainerteam" id="newtrainerteam" required>
				<option value="">Select</option>
				<option value="Valor">Valor</option>
				<option value="Mystic">Mystic</option>
				<option value="Instinct">Instinct</option>
			</select>
			</div><br>
		</div>
		<div class="container-fluid text-center">
			<button class= "btn btn-defualt" style="background-color:#ff9999" type="submit" name="submit" onclick="fadeinout();">Update</button>
		</div>
	  </form>
	  </div>
	  <hr>
<!--end Team update-->


<!--Updating Password-->
<script>
	$(document).ready(function(){
		$("#trainerpasswordupdate").click(function(){
			$("#togglepasswordupdate").toggle(700);
		});
	});
</script>
	<button id="trainerpasswordupdate" type="button" class="btn btn-default">
	  <span class="glyphicon glyphicon-chevron-right"></span>
	  <strong> Password: ********</strong> 

	</button> 
	
	<div id="togglepasswordupdate" hidden>
		<br/>
      <form class="form-horizontal" action="i/newpassword.php" method="POST">
	  
		<div class="form-group">
			<label class="control-label col-sm-2">Current:</label>
			<div class="col-sm-10">
			<input class="form-control" type="password" name="ccurrentpassword" placeholder="********" required maxlength="45" minlength="8">
			</div>
			<br>
		</div>
		
		<div class="form-group">
			<label class="control-label col-sm-2">New:</label>
			<div class="col-sm-10">
			<input class="form-control" type="password" id="cnewpassword" name="cnewpassword" placeholder="********" required maxlength="45" minlength="8">
			</div><br>
		</div>		
		
		<div class="form-group">
			<label class="control-label col-sm-2">Repeat New:</label>
			<div class="col-sm-10">
			<input class="form-control" type="password" id="crepeatnewpassword" placeholder="********" name="crepeatnewpassword" required maxlength="45" minlength="8">
			</div>
			
			<br class="hidden-xs"/>
		</div>
		
		<div class="container-fluid text-center">
			<button class= "btn btn-default" style="background-color:#ff9999" type="submit" name="submit" onclick="fadeinout();">Update Password</button>
		</div>	  </form>
	  </div>
	  <hr>
	  <!--end password update-->
	  <!--EMAIL confirmation area-->
	  
	  <?php
	  //email confirmation area
	  if (isset($_SESSION['email'])){
		  if($_SESSION['confirmemail']!='1'){
			  echo '	  <script>
			function sendconfirmationemail() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("sendemailcodearea").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/emailsendcode.php", true);
						xhttp.send();
						
					};
		</script>';
			 
		echo 'Please confirm your email: <strong>'.$_SESSION['email'].'</strong>
			 <button class="btn btn-small" onclick="sendconfirmationemail();fadeinout();">Click here to send a new code!</button></small><br><br>
			 <div id="sendemailcodearea" class="redhx">';
		if($_SESSION['confirmemail']!='0'){
			echo 'Code Sent! Please check for a confirmation code in your inbox. <br><small>Code may be in spam folder</small>.';
		}
		echo '
			 </div>
		';
		
		echo '
			<form class="form-horizontal" action="i/emailconfirmemail.php" method="POST">
		<div class="form-group">
			<label class="control-label col-sm-2">Code: </label>
			<div class="col-sm-10">
			<input class="form-control" type="text" name="confirmemail" id="confirmemail" placeholder="Confirmation Code" required  maxlength="6" autocomplete="off">
			</div>
		</div>
		<div class="container-fluid text-center">
			<button class= "btn btn-success" type="submit" name="submit">Confirm</button>
		</div>
	  </form>
	 
		';
		  }else{
			  //MULTIPLE OPT IN/ OPT OUT OPTIONS BELOW. 
			  echo 'Verified email: <strong>'.$_SESSION['email'].'</strong>';
			  
			   //section to display turning off and on emailing battle alerts!
			  if($_SESSION['displaycode']!='1'){
			  echo '
			<form class="form-horizontal" action="i/displaycodeoptin.php" method="POST">
		<input type="text" name="optinout" maxlength="1" value="1" class="hidden"/>
		<div class="container-fluid text-left">
			<button class= "btn btn-success" type="submit" name="submit">Share Code on Leaderboard </button>
		</div>
	  </form>';
			  }else{
			echo '<form class="form-horizontal" action="i/displaycodeoptin.php" method="POST">
		<input type="text" name="optinout" maxlength="1" value="0" class="hidden"/>
		<div class="container-fluid text-left">
			<button class= "btn btn-danger" type="submit" name="submit">Unshare Leaderboard Code</button>
		</div>
	  </form>';
			  }
			  
			  //section to display turning off and on emailing battle alerts!
			  if($_SESSION['emailbattles']!='1'){
			  echo '
			<form class="form-horizontal" action="i/emailbattleoptin.php" method="POST">
		<input type="text" name="optinout" maxlength="1" value="1" class="hidden"/>
		<div class="container-fluid text-left">
			<button class= "btn btn-success" type="submit" name="submit">Activate Battle Request Emails</button>
		</div>
	  </form>';
			  }else{
			echo '<form class="form-horizontal" action="i/emailbattleoptin.php" method="POST">
		<input type="text" name="optinout" maxlength="1" value="0" class="hidden"/>
		<div class="container-fluid text-left">
			<button class= "btn btn-danger" type="submit" name="submit">Deactivate Battle Request Emails</button>
		</div>
	  </form>';
			  }
			
			//section to display turning off and on DRIECT MESSAGES ALERTS
			  if($_SESSION['emailmessages']!='1'){
			  echo '
			<form class="form-horizontal" action="i/emailmessagesoptin.php" method="POST">
		<input type="text" name="optinout" maxlength="1" value="1" class="hidden"/>
		<div class="container-fluid text-left">
			<button class= "btn btn-success" type="submit" name="submit">Activate New Message Emails</button>
		</div>
	  </form>';
			  }else{
			echo '<form class="form-horizontal" action="i/emailmessagesoptin.php" method="POST">
		<input type="text" name="optinout" maxlength="1" value="0" class="hidden"/>
		<div class="container-fluid text-left">
			<button class= "btn btn-danger" type="submit" name="submit">Deactivate New Message Emails</button>
		</div>
	  </form>';
			  }  
			  			
			//section to display turning off and on GO:DRAFT ALERTS
			  if($_SESSION['godraftmessages']!='1'){
			  echo '
			<form class="form-horizontal" action="i/godraftmessagesoptin.php" method="POST">
		<input type="text" name="optinout" maxlength="1" value="1" class="hidden"/>
		<div class="container-fluid text-left">
			<button class= "btn btn-success" type="submit" name="submit">Activate GO:Draft Pick Emails</button>
		</div>
	  </form>';
			  }else{
			echo '<form class="form-horizontal" action="i/godraftmessagesoptin.php" method="POST">
		<input type="text" name="optinout" maxlength="1" value="0" class="hidden"/>
		<div class="container-fluid text-left">
			<button class= "btn btn-danger" type="submit" name="submit">Deactivate GO:Draft Emails</button>
		</div>
	  </form>';
			  } 
		  }
	  }

		if (isset($_SESSION['email'])){
		echo '<hr>
				<form action="i/logoutcode.php" method="POST">
					<button class="btn btn-small btn-danger" type="submit" name="submit">Logout</button>
				</form>';
		}
		?>
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
<br><br>
<div class="container text-center">
<?php
	  if (isset($_SESSION['email'])){
		  if($_SESSION['confirmemail']=='0'){
		  echo '
			<div class="text-center alert alert-success">
				<small>Please verify your email under <strong><a href="#" data-toggle="modal" data-target="#account"> Account Settings.</a></strong></small>
			</div>';
			}
	  }
?>
<noscript><div id="nojs"><span>Please enable javascript to access all services on our site. The site will NOT function properly otherwise.</span></div><br></noscript>

<!--Sound audio element to play noise on clicks and refresh battles and groupchat-->
<audio id="droplet">
  <!--<source src="horse.ogg" type="audio/ogg">-->
  <source src="droplet.mp3" type="audio/mpeg">
  Your browser does not support the audio element.
</audio>
<audio id="threedroplets">
  <!--<source src="horse.ogg" type="audio/ogg">-->
  <source src="threedroplets.mp3" type="audio/mpeg">
</audio>

<script>
var drop = document.getElementById("droplet"); 
function playdroplet() {
    drop.play(); 
} 

var tds = document.getElementById("threedroplets"); 
function playdroplets() { 
    tds.play(); 
}
</script>
		
  <div class="row container"><!--Row for whole website...-->
	  <br>
  <div class="col-md-4">
    <div class="row"><!--Row container-fluid for user info and welcome...-->
	
    <div class="panel">
		<div class="panel-heading leaderboard"><!--Return HERE-->
			<?php
			if (isset($_SESSION['trainername'])){
				echo '<strong>Welcome <a href="#" data-toggle="modal" data-target="#account" class="trainerusericonupdate" onclick="setTimeout(usericondisplay, 300);"><img style="float:right;" class="img-thumbnail" src="usericons/'.$_SESSION['usericon'].'.png" alt="User-Emblem" width="35" height="35"></a></strong>
					<strong>'.$_SESSION['trainername'].'!</strong>';
			}else{
				echo '<strong>Hey Trainer!</strong>';
			}
			?>
		</div>
		<div>
      <div class="panel-body">
	   <div class="text-center"> 
		<a href="index.php?<?php $randomvalue = mt_rand(1000,100000000); echo $randomvalue; ?>" target="_self">
		
		<?php
		if (isset($_SESSION['team'])){
				echo '<img src="icons/new-android-icon-512x512.png" style="float:left;" alt="PoGoPoints Logo" width="70" height="70">';
		}else{
			echo '<img src="icons/new-android-icon-512x512.png" alt="PoGoPoints Logo" width="100" height="100">';
		}
		?>
		</a>
	   </div>
	  <?php
	  if (isset($_SESSION['email'])){
		  //calculation to display the users Current Rank
		  $currentpoints=$_SESSION['points'];
		  $currentrank=1;
		  $sqlrank = "Select userid FROM users WHERE battled=1 AND points>$currentpoints";
			$alluserswithmorepoints = mysqli_query($conn, $sqlrank);
			$alluserswithmorepointsarray = array();
			if (mysqli_num_rows($alluserswithmorepoints) > 0) {
				$totaluserswithmorepoints=mysqli_num_rows($alluserswithmorepoints);
				$currentrank=$currentrank+$totaluserswithmorepoints;
			}
		  
		  if($_SESSION['battled']!='1'){
			  echo '<strong>Complete a Battle!</strong><br>';
		  }else{
			echo '<strong>Current Rank: '.$currentrank.'</strong> <br>';
		  }
				echo '<small>Points: '.$_SESSION['points'].'</small>';
					
			//calculate each player's total rating by $currentuser=$_SESSION['userid'
			//default total rating = 0 to make it show that a person has not been rated yet (no friends)
			$currentuser=$_SESSION['userid'];
			$totalratinguser=0;
			$sql = "Select rating FROM friendslist WHERE friendid=$currentuser AND friendaccepted=1";
			$ratingfind = mysqli_query($conn, $sql);
			$allacceptedratings = array();
			if (mysqli_num_rows($ratingfind) > 0) {
				while($row = mysqli_fetch_assoc($ratingfind)){
					$allacceptedratings[] = $row;
				}
				$totalnumberofratings = mysqli_num_rows($ratingfind);
				$totalratingsum=0;
				
				foreach ($allacceptedratings as $eachrating){
					$totalratingsum=$totalratingsum+$eachrating['rating'];
				}
				$totalratinguser=round(($totalratingsum/$totalnumberofratings),2);
			}else{
				$totalratinguser="None";
				$totalnumberofratings="0";
			}
			echo '<br><small><small>Rating: '.$totalratinguser.' <small>('.$totalnumberofratings.')</small></small></small>';
			
			
			}else{
				echo '       
				<br>
				<div class="container-fluid">
				<form class="form-horizontal" action="i/logincode.php" method="POST" id="loginform">
		<div class="form-group">
			
			<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span> 
			<input class="form-control" type="email" id="email" name="email" placeholder="example@email.com" autocomplete="off"';
			if(!isset($_COOKIE['email'])){
				echo "";
			}else{
				echo ' value="'.$_COOKIE['email'].'"';
			}
			echo '
			 required maxlength="50">
			
			</div>
		</div>
		
		<div class="form-group">
			<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span> 
			<input class="form-control" type="password" id="password" name="password" placeholder="Password" required maxlength="45">
			</div>
		</div>
		
		<div class="container-fluid text-center">
		  <button class="btn btn-block btn-md btn-shadow" style="background-color:black; color:white;" type="submit" name="submit"><strong>Login</strong></button>
		</div>
		<a href="#" type="button" data-toggle="modal" data-target="#signup" class="btn btn-link btn-sm"><u>Sign Up</u></a> <a href="#" type="button" data-toggle="modal" data-target="#forgotpassword" class="btn btn-link btn-sm"><u>Forgot Password?</u></a>
	</form>
	</div>

	';
}
?>
</div>
  			<!-- Show password script-->
	<script>
		function showPass(){
		var x = document.getElementById("myPass");
		if (x.type === "password") {
			x.type = "text";
		}else{
			x.type = "password";
			}
		};
	</script>
	  
	    <!-- Sign Up Modal below -->
  <div class="modal fade" id="signup" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title">Sign Up</h3>
        </div>
        <div class="modal-body">
		
		
		<form class="form-horizontal text-left" action="i/signupcode.php" method="POST">
	
		<div class="form-group">
			<label class="control-label col-sm-2">Name:</label>
			<div class="col-sm-10">
			<input class="form-control" type="text" name="trainername" placeholder="Trainer Name" required maxlength="30" autocomplete="off" autocapitalize="words">
			</div><br>
		</div>
		
		<div class="form-group">
			<label class="control-label col-sm-2">Team:</label>
			<div class="col-sm-10">
			<select id="enterteam" class="form-control" name="team" id="team" required>
				<option value="">Select</option>
				<option value="Valor">Valor</option>
				<option value="Mystic">Mystic</option>
				<option value="Instinct">Instinct</option>
			</select>
			</div>
		</div>		
		
		<div class="form-group">
			<label class="control-label col-sm-2">Level:</label>
			<div class="col-sm-10">
			<select id="enterlevel" class="form-control" name="level" id="level" required>
				<option value="">Select</option>
				<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option>
			</select>
			</div>
		</div>
		
		<div class="form-group hidden">
			<label class="control-label col-sm-2">Friend&#160;Code:</label>
			<div class="col-sm-10">
			<input class="form-control" type="text" name="friendcode" placeholder="123412341234" id="friendcode" required maxlength="14" minlength="12" autocomplete="off" value="000000000000">
			</div><br>
		</div>
		
		<div class="form-group">
			<label class="control-label col-sm-2">Email:</label>
			<div class="col-sm-10">
			<input class="form-control" type="email" id="signupemail" name="email" placeholder="example@email.com" autocomplete="off" required maxlength="50">
			<small><small>*Other users will NOT be able to see your email.</small></small>
			</div>
			<br>
		</div>
		
		<div class="form-group">
			<label class="control-label col-sm-2">Password:</label>
			<div class="col-sm-10">
			<input class="form-control" type="password" name="password" placeholder="Password" required maxlength="45" minlength="8" id="myPass"><div> <input type="checkbox" onclick="showPass()">Show Password</div>
			</div>
			<br class="hidden-xs"/>
			<br class="hidden-xs"/>
		</div>
			<div class="container-fluid text-center">
				<input type="checkbox" required>Accept 
				<a href="#" data-toggle="modal" data-target="#termsmodal" onclick="displayterms();fadeinout();">Terms and Conditions</a>, and 
				<a href="#" data-toggle="modal" data-target="#privacymodal" onclick="displayprivacy();fadeinout();">Privacy Policy</a>
			</div>
			<!--Google Recaptcha-->
		<div class="container-fluid text-center">
			<div class="g-recaptcha" data-sitekey="6Le5TZIUAAAAAIXTE6JuJRei3vMUmd-iy4cr5wxm"></div>
		</div>
		<div class="container-fluid text-center">
			<button class="btn btn-lg" style="background-color:black;color:white;" type="submit" name="submit">Sign Up</button>
		</div>
	</form>
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  	<script>
	//this script helps safari browsers display a message for the required attribute on forms since it does not work naturally. 
		$("form").submit(function(e) {
    var ref = $(this).find("[required]");

    $(ref).each(function(){
        if ( $(this).val() == '' )
        {
            alert("Please fill in all fields.");
            $(this).focus();
            e.preventDefault();
            return false;
        }
    });  return true;
});
</script>


	    <!-- Forgot Password Modal below -->
  <div class="modal fade" id="forgotpassword" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title">Forgot Password?</h3>
        </div>
        <div class="modal-body">
		<div class="container-fluid">
	<div class="row">
	<div class="col-xs-1"></div>
	
	<div class="col-xs-10">
	<h4 class="text-center"><strong>Enter your email address to send a reset code to your email.</strong></h4>
	<form id="sendpasswordlink" class="form-horizontal" action="i/sendpasswordlink.php" method="POST">
	
		<div class="form-group">
			<label class="control-label col-sm-2">Email:</label>
			<div class="col-sm-10">
			<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span> 
			<input class="form-control" type="email" name="cemail" placeholder="example@email.com" required maxlength="50">
			</div>
			</div><br>
		</div>

		<div class="container-fluid text-center">
			<button class="btn btn" style="background-color:#ff9999;color:black;" type="submit" name="submit">Send</button>
		</div>
	</form>
	</div>
	
	<div class="col-xs-1"></div>
	</div><!--rowdiv-->
	</div>
	<hr>
	<h4 class="text-center"><strong>Fill out this form to reset password.</strong></h4>

		<form class="form-horizontal" action="i/resetpasswordcode.php" method="POST">
		
		<div class="form-group">
			<label class="control-label col-sm-2">Reset Code:</label>
			<div class="col-sm-10">
			<input class="form-control" type="text" name="cpasswordreset" placeholder="*****" id="cpasswordreset" required maxlength="50" minlength="1" autocomplete="off">
			</div><br>
		</div>
		
		<div class="form-group">
			<label class="control-label col-sm-2">Email:</label>
			<div class="col-sm-10">
			<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span> 
			<input class="form-control" type="email" name="cemail" placeholder="example@email.com" required maxlength="50">
			</div>
			</div><br>
		</div>
		
		<div class="form-group">
			<label class="control-label col-sm-2">New:</label>
			<div class="col-sm-10">
			<input class="form-control" type="password" id="ccnewpassword" name="ccnewpassword" placeholder="New Password"  required maxlength="45" minlength="8">
			</div><br>
		</div>		
		
		<div class="form-group">
			<label class="control-label col-sm-2">Repeat New:</label>
			<div class="col-sm-10">
			<input class="form-control" type="password" id="ccrepeatnewpassword" name="ccrepeatnewpassword" placeholder="Repeat New Password"  required maxlength="45" minlength="8">
			</div>
			
			<br class="hidden-xs"/>
		</div>
		<div class="text-center">
		  <span id="message"></span>
		  <br/>
		</div>
		<script>
	 	$('#ccnewpassword, #ccrepeatnewpassword').on('keyup', function () {
          if ($('#ccnewpassword').val() == $('#ccrepeatnewpassword').val()) {
            $('#message').html('Passwords match.').css('color', 'green');
			$("#resetbtn").show();
          } else{
            $('#message').html('Passwords do not match!').css('color', 'red');
			$("#resetbtn").hide();
			}
          });
        </script>
		
		<div class="container-fluid text-center">
			<button id="resetbtn" class= "btn" style="background-color:black;color:white;" type="submit" name="submit">Reset Password</button>
		</div>
	  </form>

       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <script>
  function hideexclamation(x){
	  document.getElementById("exclamation"+x).style.display = "none";
  };
  </script>
  <div class="container-fluid">
  <button class="btn btn-default btn-block btn-shadow" style="background-color:#595959;color:white; text-align:left !important;" href="#" data-toggle="modal" data-target="#godraft" onclick="onlineoffline();hideexclamation(1);"><strong>
 <?php
		//display a notification symbol if it is time to GO DRAFT!
			if(isset($_SESSION['userid'])){
				if($timetodraft==1){
					echo '<span id="exclamation1" class="glyphicon glyphicon-exclamation-sign redhx"></span>';
				}
			}else{
				echo '<span id="exclamation1"></span>';
			}
?>
  <span class="glyphicon glyphicon-chevron-right"></span>GO:Drafts</strong></button>
  </div>
  <br>
  
     <!-- Trigger the modal with a button -->
   <div class="container-fluid">
<button type="button" style="background-color:#595959;color:white; text-align:left !important;" class="btn btn-default btn-block btn-shadow" data-toggle="modal" data-target="#teamdrafts"><strong><span class="glyphicon glyphicon-chevron-right"></span>Team:Drafts</strong></button>
</div>
<br>
  
<div class="container-fluid">
  <button class="btn btn-default btn-block btn-shadow" style="background-color:#595959;color:white; text-align:left !important;" href="#" data-toggle="modal" data-target="#laddertourneymodal" onclick="onlineoffline();"><strong>
  <?php
		//display a notification symbol if there are pending messages to be reviewed
			if(isset($_SESSION['userid'])){
				$challengeabletrainer=0;
			$userid = $_SESSION['userid'];
			$activetourneys = "Select * FROM tourneytrainers WHERE tourneytrainerid='$userid' AND tourneyconcluded='0';";
			$activetourneyresult = mysqli_query($conn, $activetourneys);
			$activetourneyarray = array();
			if (mysqli_num_rows($activetourneyresult) > 0) {
				while($row = mysqli_fetch_assoc($activetourneyresult)){
				$activetourneyarray[] = $row;
			}
			foreach($activetourneyarray as $eachactivetourney){
				$eachactivetourneyid=$eachactivetourney['tourneyid'];
				$eachactivetourneyposition=$eachactivetourney['tourneyposition'];
				
				$trainersonsamestage = "Select * FROM tourneytrainers WHERE tourneyid='$eachactivetourneyid' AND tourneyposition='$eachactivetourneyposition' AND tourneyconcluded='0' AND NOT tourneytrainerid='$userid';";
				$trainersonsamestageresult = mysqli_query($conn, $trainersonsamestage);
				if (mysqli_num_rows($trainersonsamestageresult) > 0) {
					$challengeabletrainer=1;
				}
			}
				if($challengeabletrainer==1){
					echo '<span class="glyphicon glyphicon-exclamation-sign redhx"></span>';
				}
			}
			}
		?>
  <span class="glyphicon glyphicon-chevron-right"></span>Ladders</strong></button>
</div>
  <br>
   <!--Community Public Monthly Ladders-->
   

  
  	    <!--START OF Ladder Modal below -->
  <div class="modal fade" id="laddertourneymodal" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <!--mobile header-->
        <div class="modal-header hidden-lg hidden-md hidden-sm" style="position: -webkit-sticky; position: sticky; top: 0px; z-index:987654321; border-bottom: 0 none; margin-bottom: -28px;">
         <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
        </div>
		
		<!--computer header-->
		<div class="modal-header hidden-xs">
         <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title text-center"><strong>Ladders</strong></h3>
        </div>
		
        <div class="modal-body">
  <div class="row">
  <div class="container-fluid" style="padding: 8px 8px 8px 8px;">
  
  <!--mobile header view-->
  <h3 class="text-center hidden-lg hidden-md hidden-sm" style="padding: -20px 0px 0px 0px; margin: -20px 0px 0px 0px;"><strong>Ladders</strong></h3>
	<hr class="hidden-lg hidden-md hidden-sm">
  
	<button type="button" class="btn btn-md btn-default btn-block text-left  btn-shadow" onclick="displaytourneyhosting();fadeinout();onlineoffline();" data-toggle="collapse" data-target="#hostingtourney"><strong><span class="glyphicon glyphicon-tower"></span> Host Ladders</strong></button>
	<div id="hostingtourney" class="collapse"></div>
<script>
	function displaytourneyhosting() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("hostingtourney").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/tourneyhostingarea.php", true);
		xhttp.send();
	}
	
	function tourneyaddmember(tourneyid){
		var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/tourneyaddmember.php?memberid="+document.getElementById("memberselect"+tourneyid).value+"&tourneyid="+tourneyid, true);
		xhttp.send();
	}	
	
	function tourneyremovemember(tourneyid){
		var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/tourneyremovemember.php?memberid="+document.getElementById("memberremove"+tourneyid).value+"&tourneyid="+tourneyid, true);
		xhttp.send();
	}
	
	function tourneyleave(tourneyid){
		var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/tourneyremovemember.php?memberid="+document.getElementById("memberleave"+tourneyid).value+"&tourneyid="+tourneyid, true);
		xhttp.send();
	}
	
	function sendtourneymessage(tourneyid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/tourneymessage.php?tourneymessage="+document.getElementById("tourneymessage"+tourneyid).value+"&tourneyid="+tourneyid, true);
			xhttp.send();
  };	
  function sendtourneytitle(tourneyid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/tourneytitle.php?tourneytitle="+document.getElementById("tourneytitle"+tourneyid).value+"&tourneyid="+tourneyid, true);
			xhttp.send();
  };   
  function sendtourneyentrycode(tourneyid) {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var coderesponse = this.responseText;
				alert(coderesponse);
			}
			};
			xhttp.open("POST","i/tourneyentrycode.php?tourneyentrycode="+document.getElementById("tourneyentrycode"+tourneyid).value+"&tourneyid="+tourneyid, true);
			xhttp.send();
  };  
  function sendtourneytimer(tourneyid) {
			var xhttp = new XMLHttpRequest();
			xhttp.open("POST","i/tourneytimer.php?tourneytimer="+document.getElementById("tourneytimer"+tourneyid).value+"&tourneyid="+tourneyid, true);
			xhttp.send();
  };	
  
	function tourneypastbattles(tourneyid,user,directory) {
		//toggle modal togglemodal
		$('#tourneypastbattlesmodal').modal('toggle');
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("tourneypastbattles").innerHTML = this.responseText;
			}
		};
			xhttp.open("POST","i/tourneypastbattles.php?tourneyid="+tourneyid+"&userid="+user+"&directory="+directory, true);
			xhttp.send();
  };
  
  	function battleaccept(friendid,battleid){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				var battleresponse = this.responseText;
				alert(battleresponse);
			}
			};
		xhttp.open("POST","i/battleaccept.php?battleaccept="+friendid+"&battleidreceived="+battleid, true);
		xhttp.send();	
	};
</script>

	<hr>
	<button type="button" style="background-color:#ffffcc;" class="btn btn-md btn-default btn-block btn-shadow" onclick="displaytourneycurrent();fadeinout();onlineoffline();" data-toggle="collapse" data-target="#currenttourneys">
	<?php
	if(isset($_SESSION['userid'])){
		if($challengeabletrainer==1){
					echo '<span class="glyphicon glyphicon-exclamation-sign redhx"></span>';
				}
	}
	?>
	<strong><span class="glyphicon glyphicon-refresh"></span> Current Ladders</strong></button>
		<div id="currenttourneys" class="collapse"></div>
		<script>
	function displaytourneycurrent() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("currenttourneys").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/tourneycurrentarea2.php", true);
		xhttp.send();
	};
	function displaytourneycurrenteachtourney(x) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("currenttourneys"+x).innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/tourneycurrentareaeachtourney.php?eachtourneyid="+x, true);
		xhttp.send();
	};
</script>
	<hr>
	<button type="button" style="background-color:#ffff66;" class="btn btn-md btn-default btn-block btn-shadow" onclick="displaytourneyconcluded();fadeinout();onlineoffline();" data-toggle="collapse" data-target="#concludedtourneys"><strong><span class="glyphicon glyphicon-ok"></span> Concluded Ladders</strong></button>
		<div id="concludedtourneys" class="collapse"></div>
<script>
	function displaytourneyconcluded() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("concludedtourneys").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/tourneyconcludedarea2.php", true);
		xhttp.send();
	};
	function displaytourneyconcludedeachtourney(x) {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("concludedtourneys"+x).innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/tourneyconcludedareaeachtourney.php?eachtourneyid="+x, true);
		xhttp.send();
	};
</script>
	<hr>

	  <div class="panel panel-default">
  <div class="panel-heading">
   <strong>Enter Ladder:</strong> <input class="form-control" type="text" id="tourneyentrycodetrainer" name="tourneyentrycodetrainer" minlength="1" maxlength="255" autocomplete="off" placeholder="TYPE ENTRY CODE HERE" required>
	<div class="text-right">
		<button class="btn btn-sm btn-link" data-toggle="modal" data-target="#laddertourneymodal" onclick="onlineoffline();"><small>Use GO:Draft Button for Go:Draft Codes!</small></button>
	<button id="senttourneyentrytrainercodebtn" onclick="sendtourneyentrycodetrainer();onlineoffline();fadeinout();" class="btn btn-sm btn-default">Enter <span class="glyphicon glyphicon-send"></span></button>
	</div>
	<div id="tourneyentrycoderesponsetrainer"></div>
	</div>
	</div>
<script>
	function sendtourneyentrycodetrainer(){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
				document.getElementById("tourneyentrycoderesponsetrainer").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST","i/tourneyenterladder.php?entrycode="+document.getElementById("tourneyentrycodetrainer").value, true);
		xhttp.send();	
	};
	function toggleladdertourneymodal(){
		$('#laddertourneymodal').modal('toggle')
	};
</script>

<hr>
	<button type="button" class="btn btn-md btn-block btn-shadow" style="background-color:#4d4d4d;color:white;" onclick="displayladdercommunity();fadeinout();" data-toggle="collapse" data-target="#communityladders"><strong><span class="glyphicon glyphicon-eye-open"></span> Community Links</strong></button>
		<div id="communityladders" class="collapse"></div>
<script>
	function displayladdercommunity() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("communityladders").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/laddercommunity.php", true);
		xhttp.send();
	}
</script>
	
	
	<!-- Monthly ladders
	
	      <div>
<button class="btn btn-lg btn-block btn-default" data-toggle="collapse" data-target="#demomonthlyladderinfo"><strong><u>Monthly Ladders</u> <small><span class="glyphicon glyphicon-question-sign"></span></small></strong></button>
        <div class="text-left collapse" id="demomonthlyladderinfo">
		<br> There will be <strong>Monthly Themed Ladders</strong> starting for the website and <i>open to all users</i>. Each Ladder will be started soon after the first announcement for the current monthly cup. The Ladder challenges will be fought with the <strong>“Duo Draft”</strong> format and users must Only DRAFT teams that are currently permitted for the current monthly cup. The Champion will be determined by whoever gets to the highest Stage on the Ladder with the Fewest Losses, tiebreakers go to those who joined the Ladder First, by the end of the month.<br><br>
		Ladders are based on a Challenge system and you can face anyone on the same stage as you as long as you Ultra friends to battle remotely, or are able to meet up locally. This means that <strong>anyone can join</strong> in on these Monthly Ladders at anytime before the month ends! There will be a code posted in the Monthly Ladder message so that users can enter in the Ladder and get to battling when they are ready!<br><br>
		View all Monthly Ladders below. 
		<hr>
		</div>
	
		<div class="container-fluid text center">
		<h3><strong>2020</strong></h3>
		<hr>
<a href="http://www.pogopoints.site/i/tourneydisplaylink.php?l=28" target="_blank"><button type="button" style="color: black; border: 3px solid rgb(255, 215, 0); border-radius: 3px; background: rgba(255, 255, 240, 0.8);" class="btn btn-default btn-block btn-shadow"><strong>February's Ladder!</strong></button></a>
  <hr>
 <a href="http://www.pogopoints.site/i/tourneydisplaylink.php?l=26" target="_blank"><button type="button" style="color: black; border: 3px solid rgb(255, 215, 0); border-radius: 3px; background: rgba(255, 255, 240, 0.8);" class="btn btn-default btn-block btn-shadow"><strong>January's Ladder!</strong></button></a>
  <hr>
		<h3><strong>2019</strong></h3>
		<hr>
 <a href="http://www.pogopoints.site/i/tourneydisplaylink.php?l=25" target="_blank"><button type="button" style="color: black; border: 3px solid rgb(255, 215, 0); border-radius: 3px; background: rgba(255, 255, 240, 0.8);" class="btn btn-default btn-block btn-shadow"><strong>December's Ladder!</strong></button></a>
  <hr>
  <a href="http://www.pogopoints.site/i/tourneydisplaylink.php?l=23" target="_blank"><button type="button" style="color: black; border: 3px solid rgb(255, 215, 0); border-radius: 3px; background: rgba(255, 255, 240, 0.8);" class="btn btn-default btn-block btn-shadow"><strong>November's Ladder!</strong></button></a>
  <hr>
  <a href="http://www.pogopoints.site/i/tourneydisplaylink.php?l=22" target="_blank"><button type="button" style="color: black; border: 3px solid rgb(255, 215, 0); border-radius: 3px; background: rgba(255, 255, 240, 0.8);" class="btn btn-default btn-block btn-shadow"><strong>October's Ladder!</strong></button></a>
 
  </div>
		
      </div>
	  -->
	  </div>
  </div>
		 </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <!--END OF Ladder Modal below -->
  
  <!--Start Past Battles Modal -->
<div id="tourneypastbattlesmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
        <h4 class="modal-title"><strong>Past Battles</strong></h4>
      </div>
      <div class="modal-body text-left" id="tourneypastbattles"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="setTimeout(toggleladdertourneymodal, 500);">Close</button>
      </div>
    </div>
  </div>
</div>
<!--END Past Battles Modal -->
	  <!--Friends List Area-->  
	  <div class="container-fluid">
        <button href="#" data-toggle="modal" data-target="#friendslist" class="btn btn-default btn-block btn-shadow" style="text-align:left !important;" onclick="onlineoffline(); displayfriendslist();">
		<?php
		//display a notification symbol if there are pending friend requests
			if(isset($_SESSION['userid'])){
				$userid = $_SESSION['userid'];
			$sqlfl = "Select * FROM friendslist WHERE friendid='$userid' AND friendaccepted=0";
			$pendingfriendresult = mysqli_query($conn, $sqlfl);
			$pendingfriendresultarray = array();
			if (mysqli_num_rows($pendingfriendresult) > 0) {
				echo '<span class="glyphicon glyphicon-exclamation-sign redhx"></span>';
			}
			}
		?>
		<span class="glyphicon glyphicon-chevron-right"></span>
		<strong>Friends</strong></button><br>
	  </div>
	    <!-- Friends List Modal below -->
  <div class="modal fade" id="friendslist" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
       <!--mobile header-->
        <div class="modal-header hidden-lg hidden-md hidden-sm" style="position: -webkit-sticky; position: sticky; top: 0px; z-index:987654321; border-bottom: 0 none; margin-bottom: -28px;">
         <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
        </div>
		
		<!--computer header-->
		<div class="modal-header hidden-xs">
         <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title text-center"><strong>Friends</strong></h3>
        </div>
		
        <div class="modal-body">
  
  <!--mobile header view-->
  <h3 class="text-center hidden-lg hidden-md hidden-sm" style="padding: -20px 0px 0px 0px; margin: -20px 0px 0px 0px;"><strong>Friends</strong></h3>
	<hr class="hidden-lg hidden-md hidden-sm">
		<!--Display current friends here-->
<script>
function displayfriendslist() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("friendslistarea").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/friendslist.php", true);
						xhttp.send();
						//refresh friends list every 2 minutes to display their last activity
						//updatefriendslist=setTimeout(displayfriendslist, 120000);
					};
		var findfriendstimer;
function findfriends(str) {
						clearTimeout(findfriendstimer);
						findfriendstimer = setTimeout(findfiendsnow, 300);
					function findfiendsnow(){
    if (str.length == 0) { 
        document.getElementById("suggestedfriends").innerHTML = "";
        return;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("suggestedfriends").innerHTML = this.responseText;
            }
        };
        xmlhttp.open("GET", "i/findfriends.php?friendname=" + str, true);
        xmlhttp.send();
    }
};
}

	function getsentfriendrequestlist(){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
				document.getElementById("sentlistcollapse").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST","i/sentfriendrequestlist.php", true);
		xhttp.send();	
	};

	function addfriend(friendid){
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
				document.getElementById("suggestedfriends").innerHTML = this.responseText;
				getsentfriendrequestlist();
			}
		};
		xhttp.open("POST","i/addfriend.php?addfriend="+friendid, true);
		xhttp.send();	
	};
		</script>
		<!--search user database to find friends!-->
		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
			<input type="text" class="form-control" onkeyup="findfriends(this.value)" placeholder="Look Up Friend's Name to Add...">
		</div>
<div id="suggestedfriends" class="text-left"></div>
<hr>
			<button data-toggle="collapse" data-target="#friendslistcollapse" class="btn btn-default btn-shadow">
			<span class="glyphicon glyphicon-chevron-right"></span>Current Friends</button>
			<div class="collapse" id="friendslistcollapse">
				<div id="friendslistarea"></div>
			</div>
			<hr>
		<!--Display pending friends here-->
			<button data-toggle="collapse" data-target="#pendinglistcollapse" class="btn btn-default btn-shadow"><span class="glyphicon glyphicon-chevron-right"></span>Pending Requests</button>
			<div class="collapse in" id="pendinglistcollapse"><br>
				<?php
				if(isset($_SESSION['userid'])){
				$loggedinuser = $_SESSION['userid'];
				$sqlpendingfriends = "Select * FROM friendslist WHERE friendid=$loggedinuser AND friendaccepted=0";
				$pendingrequestsresult = mysqli_query($conn, $sqlpendingfriends);
				$pendingrequestarray = array();
				if (mysqli_num_rows($pendingrequestsresult) > 0) {
					while($row = mysqli_fetch_assoc($pendingrequestsresult)){
						$pendingrequestarray[] = $row;
					}
				}else{
					echo 'No Pending Requests.';
				}
				
				foreach ($pendingrequestarray as $pendingusers) {
					//Each sentuser has their own id
					$pendinguserid=$pendingusers['userid'];
					
					$sqlpendingfriend = "Select * FROM users WHERE userid=$pendinguserid";
					$pendingrequestsresults = mysqli_query($conn, $sqlpendingfriend);
					$sentrequestarray1 = array();
					if (mysqli_num_rows($pendingrequestsresults) > 0) {
						while($row = mysqli_fetch_assoc($pendingrequestsresults)){
							$sentrequestarray1[] = $row;
						}
					}
					
					$acceptfriend = $sentrequestarray1[0]['userid'];
					
					echo '
					<form class="form-horizontal" name="form2" action="i/acceptfriendrequest.php" method="POST">
						<input type="text" name="acceptfriend" maxlength="30" value="'.$acceptfriend.'" class="hidden"/><strong>
						'.$sentrequestarray1[0]['pogoname'].':</strong> <button class="btn btn-success" name="submit" type="submit">Add Friend</button> 
					</form>';
				}
				}else{
					echo 'Login to see requests!';
				}
				?>
			</div>
			<hr>
		<!--Display Sent request friends here-->
			<button data-toggle="collapse" data-target="#sentlistcollapse" class="btn btn-default btn-shadow"><span class="glyphicon glyphicon-chevron-right"></span>Sent Requests</button>
			<div id="sentlistcollapse" class="collapse">
				<?php
				if(isset($_SESSION['userid'])){
				$loggedinuser = $_SESSION['userid'];
				$sqlfriends = "Select * FROM friendslist WHERE userid=$loggedinuser AND friendaccepted=0 ORDER BY id DESC";
				$sentrequestsresult = mysqli_query($conn, $sqlfriends);
				$sentrequestarray = array();
				if (mysqli_num_rows($sentrequestsresult) > 0) {
					while($row = mysqli_fetch_assoc($sentrequestsresult)){
						$sentrequestarray[] = $row;
					}
				}else{
					echo 'No Sent Requests.';
				}
				
				foreach ($sentrequestarray as $sentusers) {
					//Each sentuser has their own id
					$sentuserid=$sentusers['friendid'];
					
					$sqlfriend = "Select * FROM users WHERE userid=$sentuserid";
					$sentrequestsresults = mysqli_query($conn, $sqlfriend);
					$sentrequestarray1 = array();
					if (mysqli_num_rows($sentrequestsresults) > 0) {
						while($row = mysqli_fetch_assoc($sentrequestsresults)){
							$sentrequestarray1[] = $row;
						}
					}
					echo '
					<form class="form-horizontal" action="i/friendcancelrequest.php" method="POST">
						<input type="text" name="friendcancelrequest" maxlength="30" value="'.$sentuserid.'" class="hidden"/>
						'.$sentrequestarray1[0]['pogoname'].': <button class="btn btn-sm btn-danger" type="submit" name="submit" onclick="fadein();">Cancel</button>
					</form>
					';
				}	
				}else{
					echo 'Login to make friends!';
				}
?>
			</div>
			<hr>
			<button style="background-color:#ff9999" type="button" class="btn btn-block btn-default btn-shadow" href="#"  data-toggle="collapse" data-target="#submitbattlescollapse"  onclick="onlineoffline();"><strong><span class="glyphicon glyphicon-chevron-right"></span>Battle Form</strong></button><br>
			
			<div id="submitbattlescollapse" class="collapse">
			
			<div class="container-fluid text-left">  
<small>Trainers can submit multiple RANKED battles at once with a friend to record their battle history. Complete a few battles <small>(max 20 wins/losses)</small> with a friend and then enter the results here by selecting who you fought, what league you fought in, and the number of wins and losses you had. Click submit and wait for approval from your friend. Once they accept the submitted battles, the battles will be recorded and <i>points will exchange</i> between players!</small>
</div>
<div class="container-fluid text-center"> 
  <h3><u>Battle Form</u></h3>
</div>
<div class="container-fluid text-left"> 
<form class="form-horizontal" action="i/submitbattlesforreview.php" method="POST">
<div class="form-group">
  <label for="friendid1">Friend:</label>
  <select class="form-control" id="friendid1" name="friendid" required>
    <option value="">Select</option>
<?php				
				if(isset($_SESSION['userid'])){
				$userid = $_SESSION['userid'];
				
				//SELECT Orders.OrderID, Customers.CustomerName, Orders.OrderDate
				//FROM Orders
				//INNER JOIN Customers ON Orders.CustomerID=Customers.CustomerID;
		
				$sortedfriends = "Select friendslist.id, friendslist.userid, friendslist.friendid, friendslist.friendaccepted, users.userid, users.pogoname FROM friendslist INNER JOIN users ON friendslist.friendid=users.userid WHERE friendslist.userid='$userid' AND friendaccepted='1' ORDER BY pogoname ASC;";
				$sortedresults = mysqli_query($conn, $sortedfriends);
				$sortedfriendsarray = array();
				if (mysqli_num_rows($sortedresults) > 0) {
					while($row = mysqli_fetch_assoc($sortedresults)){
						$sortedfriendsarray[] = $row;
					}
					foreach($sortedfriendsarray as $sortedfriend){
						echo '<option value="'.$sortedfriend['friendid'].'">'.$sortedfriend['pogoname'].'</option>';
					}
				}
				}
?>
  </select>
</div>
<div class="form-group">
  <label for="league1">League:</label>
  <select class="form-control" id="league1" name="league" required>
	<?php include 'i/battleoptions.php'; ?>
  </select>
</div>
<div class="form-group">
  <label for="numberwins1">Your Win Count:</label>
  <select class="form-control" id="numberwins1" name="numberwins" required>
    <option value="">Select</option>
    <option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option>
  </select>
</div>
<div class="form-group">
  <label for="numberlosses1">Your Loss Count:</label>
  <select class="form-control" id="numberlosses1" name="numberlosses" required>
    <option value="">Select</option>
    <option value="0">0</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option>
  </select>
</div>
<div class="text-center">
	<small><small>*Only one person needs to send the battle form for each set of battles!</small></small>
</div>
  <div class="form-group"> 
    <div class="text-center">
      <button type="submit" class="btn btn-default" style="background-color:#ff9999"><strong>Submit</strong></button>
    </div>
  </div>
</form>
</div>

			</div>
			
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
	  <!--END of Friend's List Area-->	  
	  
	  <!--Battle History-->
	  <script>
			function displaybattlehx() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("battlehistoryarea").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/battlehistory.php", true);
						xhttp.send();
					};
				
		</script>
	  <div class="container-fluid">
        <button data-toggle="modal" data-target="#battlehistory" class="btn btn-default btn-block btn-shadow" style="text-align:left !important;" onclick="displaybattlehx();onlineoffline();">
		<span class="glyphicon glyphicon-chevron-right"></span><strong>
		Battle History</strong></button>
	  </div>
		<br>
			    <!-- Battle History Modal below -->
  <div class="modal fade" id="battlehistory" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <!--mobile header-->
        <div class="modal-header hidden-lg hidden-md hidden-sm" style="position: -webkit-sticky; position: sticky; top: 0px; z-index:987654321; border-bottom: 0 none; margin-bottom: -28px;">
         <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
        </div>
		
		<!--computer header-->
		<div class="modal-header hidden-xs">
         <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title text-center"><strong>Battle History</strong></h3>
        </div>
		
        <div class="modal-body text-left">
  <!--mobile header view-->
  <h3 class="text-center hidden-lg hidden-md hidden-sm" style="padding: -20px 0px 0px 0px; margin: -20px 0px 0px 0px;"><strong>Battle History</strong></h3>
	<hr class="hidden-lg hidden-md hidden-sm">
<?php
if(isset($_SESSION['userid'])){
	echo ' <div class="modal-body text-center"><small>Lifetime Battles <small>(Includes practice matches):</small><br> <strong><small>Won:</strong> '.$_SESSION['totalwins'].' <strong>Lost:</strong> '.$_SESSION['totallosses'].'</small></small><hr></div>';
}

?>
			<div id="battlehistoryarea"></div>
			<hr>
			<!-- See battle results from certain friends-->
<script>
	function getfriendbattlehistory() {
		var xhttp = new XMLHttpRequest();
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				document.getElementById("friendbattlehistoryarea").innerHTML = this.responseText;
			}
		};
		xhttp.open("POST", "i/getfriendbattlehistoryarea.php?friendidbattlesfrom="+document.getElementById("friendidbattlesfrom").value, true);
		xhttp.send();
	}
</script>
		
			<div class="container-fluid">
			<h4>Individual Friend Battle History</h4>
			<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
			<select class="form-control" id="friendidbattlesfrom" name="friendidbattlesfrom" onchange="getfriendbattlehistory();" required>
				<option value="">Past Battles From:</option>
<?php				
				if(isset($_SESSION['userid'])){
				$userid = $_SESSION['userid'];
				
				//SELECT Orders.OrderID, Customers.CustomerName, Orders.OrderDate
				//FROM Orders
				//INNER JOIN Customers ON Orders.CustomerID=Customers.CustomerID;
		
				$sortedfriends = "Select friendslist.id, friendslist.userid, friendslist.friendid, friendslist.friendaccepted, users.userid, users.pogoname FROM friendslist INNER JOIN users ON friendslist.friendid=users.userid WHERE friendslist.userid='$userid' AND friendaccepted='1' ORDER BY pogoname ASC;";
				$sortedresults = mysqli_query($conn, $sortedfriends);
				$sortedfriendsarray = array();
				if (mysqli_num_rows($sortedresults) > 0) {
					while($row = mysqli_fetch_assoc($sortedresults)){
						$sortedfriendsarray[] = $row;
					}
					foreach($sortedfriendsarray as $sortedfriend){
						echo '<option value="'.$sortedfriend['friendid'].'">'.$sortedfriend['pogoname'].'</option>';
					}
				}
				}
?>
			</select>
			</div>
			</div>
			<div class="container-fluid" id="friendbattlehistoryarea"></div>  
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div><!--end of modal-->

	  <!--END of Battle History Area-->
  
   <!--Messages-->
	  <script>
			function displaypersonalmessages() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("pastmessagesarea").innerHTML = this.responseText;
								document.getElementById("messageexclamation").innerHTML = '';
								document.getElementById("refreshmessages").innerHTML = '';
							}
						};
						xhttp.open("POST", "i/pastmessages.php", true);
						xhttp.send();
					};
			
			//check every 5 minutes for new messages and display an exclamation when someonehas a new message!
					function displayexclamation() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("messageexclamation").innerHTML = this.responseText;
								document.getElementById("refreshmessages").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/displayexclamation.php", true);
						xhttp.send();
					};
		</script>
			   <!-- Personal Messages Modal below -->
  <div class="modal fade" id="personalmessages" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title">Inbox</h3>
        </div>
        <div class="modal-body text-left">
		
			<div id="personalmessagesarea">
			
				<!--Display Past Messages-->
				<div class="personalmessagescroll resizepanel" id="pastmessagesarea"></div>
			<hr>
<script>
function sendpersonalmessage() {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("personalmessageinput").value = "";
					displaypersonalmessages();
				}
			};
			
			xhttp.open("POST","i/personalmessage.php?personalmessage="+document.getElementById("personalmessageinput").value+"&friendid="+document.getElementById("friendid").value, true);
			xhttp.send();
  };
  </script>
			
		<div class="container-fluid text-center">
			<button class= "btn btn-default" onclick="displaypersonalmessages();onlineoffline();fadeinout();"><span id="refreshmessages"></span> Refresh</button>
		</div>
			<!--New Personal Message Form-->
			<div class="container-fluid">
			<select class="form-control" id="friendid" name="friendid" required>
				<option value="">Send To:</option>
<?php				
				if(isset($_SESSION['userid'])){
				$userid = $_SESSION['userid'];
				
				//SELECT Orders.OrderID, Customers.CustomerName, Orders.OrderDate
				//FROM Orders
				//INNER JOIN Customers ON Orders.CustomerID=Customers.CustomerID;
		
				$sortedfriends = "Select friendslist.id, friendslist.userid, friendslist.friendid, friendslist.friendaccepted, users.userid, users.pogoname FROM friendslist INNER JOIN users ON friendslist.friendid=users.userid WHERE friendslist.userid='$userid' AND friendaccepted='1' ORDER BY pogoname ASC;";
				$sortedresults = mysqli_query($conn, $sortedfriends);
				$sortedfriendsarray = array();
				if (mysqli_num_rows($sortedresults) > 0) {
					while($row = mysqli_fetch_assoc($sortedresults)){
						$sortedfriendsarray[] = $row;
					}
					foreach($sortedfriendsarray as $sortedfriend){
						echo '<option value="'.$sortedfriend['friendid'].'">'.$sortedfriend['pogoname'].'</option>';
					}
				}
				}
?>
			</select>
			</div>
		<div class="container-fluid input-group">
				<input class="form-control" type="text" id="personalmessageinput" name="personalmessage" minlength="1" maxlength="255" placeholder="New message here..." autocomplete="off" required>
			<div class="input-group-btn">
				<button class= "btn btn-default" style="background-color:#ff9999" type="button" id="sendpersonalmessagebtn" onclick="sendpersonalmessage();onlineoffline();fadeinout();">Send <span class="glyphicon glyphicon-send"></span></button>
			</div>
		</div>
		<script>
// Get the input field
var input = document.getElementById("personalmessageinput");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Trigger the button element with a click
    document.getElementById("sendpersonalmessagebtn").click();    
	displaypersonalmessages();
	onlineoffline();
  }
});

//SITE POSTS GROUP Chat
	function displaygroupchat() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("groupchatarea").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/groupchat.php", true);
						xhttp.send();
					};
					
	function sendgroupmessage() {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("sentgroupchat").value = "";
				}
			};
			xhttp.open("POST","i/postgroupchat.php?sentgroupchat="+document.getElementById("sentgroupchat").value, true);
			xhttp.send();
  };
  
  //goup chat message chatmessage scroll to the last message sent instead of start at top
		function autoscrollgroupchat(){	
			var objDiv = document.getElementById("chatscroller");
			objDiv.scrollTop = objDiv.scrollHeight;
		};
		
    $(document).ready(function(){
		autoscrollgroupchat();
 });
</script>
			</div>  
       </div>
        <div class="modal-footer">
		<small><span class="btn btn-link"><a href="https://discord.gg/7usyKPA" target="_blank" onclick="fadeinout();">Connect on Discord</a></span></small>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div><!--end of modal-->
	  <!--END of Messages Area-->
	  <!--Start Site Posts Modal 
	  <div class="container-fluid">
	  <button data-toggle="modal" data-target="#sitepostsmodal" class="btn btn-default btn-block btn-shadow" style="background-color:#595959;color:white;" onclick="onlineoffline();setTimeout(autoscrollgroupchat, 800);">
		<span class="glyphicon glyphicon-chevron-right"></span><strong>
		Site Posts</strong></button>
		<br>
		</div>
		-->
		
<div id="sitepostsmodal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
        <h4 class="modal-title"><strong>Site Posts</strong></h4>
      </div>
      <div class="modal-body">
		<div class="text-left groupchatscroll resizepanel" id="chatscroller">
			<div id="groupchatarea">
			<?php 
			if (!isset($_SESSION['confirmemail'])){
			echo '<div class="container-fluid chatboxes2"><br>Posts from site users.</div>
		<div class="container-fluid chatboxes2"><br>3!<br></div>
		<div class="container-fluid chatboxes2"><br>2!<br></div>
		<div class="container-fluid chatboxes2"><br>1!<br></div>
			<div class="container-fluid chatboxes2"><br>GO!<br></div>';
			}else{
				
			$lastmessageID=1;
			$past10message=1;
			//find the last ID for the last message sent
			$sqllastmessage = "Select id FROM groupchat ORDER BY id DESC LIMIT 1;";
			$lastmessageresults = mysqli_query($conn, $sqllastmessage);
			$lastmessagearray = array();
			if (mysqli_num_rows($lastmessageresults) > 0) {
				while($row = mysqli_fetch_assoc($lastmessageresults)){
					$lastmessagearray[] = $row;
				}
				$lastmessageID = $lastmessagearray[0]['id'];
				$pastmessage = $lastmessageID-20;
			}
			
			//limit the messages retrieved to the last 200 sent 
		    $sqlgroupmessages = "Select * FROM groupchat WHERE id>$pastmessage ORDER BY id ASC LIMIT 20;";
			$groupchatresults = mysqli_query($conn, $sqlgroupmessages);
			$groupchatarray = array();
			if (mysqli_num_rows($groupchatresults) > 0) {
				while($row = mysqli_fetch_assoc($groupchatresults)){
					$groupchatarray[] = $row;
				}
				
				foreach($groupchatarray as $eachmessage){
				
				//get the user's name by using the message id by using the message userid
				$useridlookup=$eachmessage['userid'];
				$userinfo = "Select * FROM users WHERE userid=$useridlookup";
				$userinforesults = mysqli_query($conn, $userinfo);
				$userinfoarray = array();
				if (mysqli_num_rows($userinforesults) > 0) {
					while($row = mysqli_fetch_assoc($userinforesults)){
						$userinfoarray[] = $row;
					}
				}
				$pogonames=$userinfoarray[0]['pogoname'];
					if($_SESSION['userid']==$useridlookup){
						echo '<div class="container-fluid chatboxes2">';
					}else{
						echo '<div class="container-fluid chatboxes">';
					}
					
					echo '<div class="container-fluid"><small><strong>'.$pogonames.'</strong> <span class="extrasmall">(';
					
					//Set the timezone 
					date_default_timezone_set("America/Los_Angeles");
					$unixtime = time();
					$lasttime = $eachmessage['unixtime'];
					
					$lastactivitymins = (($unixtime-$lasttime)/60);
					$lastminutes = ceil($lastactivitymins);
					$lasthours=floor($lastminutes/60);
					$lastday=floor(($lastminutes/60)/24);
					
					//display minutes up to 60 minutes
					if($lastminutes==1 || $lastminutes<1){
						echo 'sent <1 min ago';
					}elseif($lastminutes>1 && $lastminutes<61){
						echo 'sent '.$lastminutes.' min ago';
						
					//display hours if between 1-24 hours
					}elseif($lastminutes>=61 && $lastminutes<1440){
						echo 'sent '.$lasthours.' hr ago';
						
					//display 1+ days if more than 24 hours offline
					}elseif($lastminutes>=1440 && $lastminutes<2880){
						echo 'sent '.$lastday.' day ago';
					
					}else{
						echo 'sent '.$lastday.' days ago';
					}
					
					//escape the \ that is used to escape SQL injections
					$messagecleaned = str_replace('\\', '', $eachmessage['groupmessage']);
					
					echo ')</span><br><p>&#160;&#160;&#160;&#160;'.htmlspecialchars($messagecleaned).'</p></small></div>
					
					</div>
					';
				}//foreach message end...
				
				//create a session variable to display the most current chat id
				$sqllastmessages = "Select * FROM groupchat ORDER BY id DESC LIMIT 1";
				$lastchatresults = mysqli_query($conn, $sqllastmessages);
				$lastchatarray = array();
				if (mysqli_num_rows($lastchatresults) > 0) {
					while($row = mysqli_fetch_assoc($lastchatresults)){
						$lastchatarray[] = $row;
				}}
				
				//this will call the autoscrollgroupchat function when there is a new message posted that has a larger id than the previous lastgroupchatid set in The SESSION variable for it
				if($lastchatarray[0]['id']>$_SESSION['lastgroupchatid']){
				
					echo '<style onload="autoscrollgroupchat();playdroplet();"></style>';
				
					$_SESSION['lastgroupchatid'] = $lastchatarray[0]['id'];
				}	
			}
			}
			?>
			</div> 
		  </div>
          <div class="panel-footer">
		  <div class="container-fluid row">
<?php
				//check to see if user has a confirmed email
			if (isset($_SESSION['confirmemail'])){
			//ACTIVATE THIS AREA TO MAKE SURE PEOPLE HAVE THIER EMAILS CONFIRMED IF SPAM STARTS TO SHOW UP
			//if($_SESSION['confirmemail']!='1'){
			//	echo '<small><span class="greenhx">Please verify your email before posting to Group Chat. Go to "My Account" and //send a confirmation code to your email.</span></small>';
			//}else{
				echo '<input class="form-control input-sm" type="text" id="sentgroupchat" name="groupmessage" minlength="1" maxlength="255" autocomplete="off" required>
				<div class="row">
				<div class="col-xs-4 text-left">
					<small><button class="btn btn-small btn-link" onclick="displaygroupchat();fadeinout();">Refresh</button> </small>
				</div>
				 
				 <div class="col-xs-8 text-right">	
					<!--<button id="discordchat" onclick="onlineoffline();fadeinout();" class="btn btn-sm btn-default" data-toggle="modal" data-target="#discordchatmodal"><span class="glyphicon glyphicon-chevron-right"></span> Discord Chat</button>-->
					<button id="sentgroupchatbtn" onclick="sendgroupmessage();onlineoffline();fadeinout();setTimeout(displaygroupchat, 2000);" class="btn btn-sm btn-default">Send <span class="glyphicon glyphicon-send"></span></button>
				</div>
				</div>
				';
			//}
			}else{
				echo '<input class="form-control input-sm" type="text" id="sentgroupchat" name="groupmessage" minlength="1" maxlength="255" autocomplete="off" required>
				<div class="text-right">	
					<!--<button id="discordchat" onclick="onlineoffline();fadeinout();" class="btn btn-sm btn-default" data-toggle="modal" data-target="#discordchatmodal"><span class="glyphicon glyphicon-chevron-right"></span> Discord Chat</button>-->
					<button id="sentgroupchatbtn" onclick="sendgroupmessage();onlineoffline();fadeinout();" class="btn btn-sm btn-default">Send <span class="glyphicon glyphicon-send"></span></button>
				</div>';
			}
?>
	
<script>
// Get the input field
var input = document.getElementById("sentgroupchat");

// Execute a function when the user releases a key on the keyboard
input.addEventListener("keyup", function(event) {
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Trigger the button element with a click
    document.getElementById("sentgroupchatbtn").click();
  }
});
</script>
		  </div>
		  </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
		<!--END Site Posts Modal -->
		
		<!--START Leaderboards Modal -->
<div id="leaderboardsmodal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
        <h4 class="modal-title"><strong>Ranked Leaderboards</strong></h4>
      </div>
      <div class="modal-body">
		<div class="container-fluid">
				<div class="btn-group-justified">
				<div class="btn-group">
					<button onclick="refreshleaderboard();onlineoffline();fadeinout();" class="btn btn-default">
						<strong>Global</strong>
					</button>
				</div>
				<div class="btn-group">
					<button onclick="displayfriendleaderboard();onlineoffline();fadeinout();" class="btn btn-default">
						<strong>Friends Only</strong>
					</button>
				</div>
				</div>
				</div>	
<script>
			function displayleaderboard() {
						
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("leaderboardarea").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/leaderboard.php", true);
						xhttp.send();
			};
			function refreshleaderboard() {
						
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("leaderboardarea").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/refreshleaderboard.php", true);
						xhttp.send();
			};
			function displayfriendleaderboard() {
						
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("leaderboardarea").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/displayfriendleaderboard.php", true);
						xhttp.send();
			};
			</script>
				<div class="row" id="leaderboardarea">
				<div class="text-center"><small><i>Select an option.</i></small></div>
				<hr>
				</div><!--end of row div-->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!--END Leaderboards Modal -->
	  
</div>
  
  
      </div><!--End of welcome panel div-->
	  </div><!--end of row div for col sm 3-->
	  
    </div><!--End of Col-sm-3 div for user info-->

	<!--Code to update user activity to show who is online, detect, update, online, offline-->
<?php
	if (isset($_SESSION['email'])){
		echo '
			<script>
				function onlineoffline() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
							}
						};
						xhttp.open("POST", "i/onlineoffline.php", true);
						xhttp.send();
					};
			</script>
		';
	}else{
		echo '
			<script>
				function onlineoffline() {
						console.log("onlinestatus");
					};
			</script>
		';
	}
?>
	<div class="col-md-1"></div>
	<!--Code to update user activity to show who is online-->
    <div class="col-md-7">
      <div class="row">
		<!--Start of panel for active battles and refresh area-->
          <div class="panel text-left">
		<script>
  function sendentertime() {
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("battlereadytime").innerHTML = this.responseText;
				}
			};
			xhttp.open("POST","i/entertime.php?entertime="+document.getElementById("sel1").value, true);
			xhttp.send();
  };
  
  function displayentertime() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("entertime").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/entertimedisplay.php", true);
						xhttp.send();
					};

	  function displayentertimedisplaycurrent() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("battlereadytime").innerHTML = this.responseText;
								//update the users battle time every 3 minutes (180000 milliseconds)
						updateentertimedisplay=setTimeout(displayentertimedisplaycurrent, 180000);
							}
						};
						xhttp.open("POST", "i/entertimedisplaycurrent.php", true);
						xhttp.send();
						
					};
					
		//function to keep the battle ready friends displayed on refresh
	//	function entertimefriendsdropdown() {
		//				var xhttp = new XMLHttpRequest();
			//			xhttp.open("POST", "i/entertimefriendsdropdown.php", true);
				//		xhttp.send();
					//};
</script>
			<div class="panel-heading text-center pogopointslogo leaderboard">PVP Battles</div>
            <div class="panel-body">
			
			
<!-- Leagues Modal below -->
  <div class="modal fade" id="leagueinfo" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title text-center"><strong>League Info</strong></h3>
        </div>
        <div class="modal-body">
   
<div class="container-fluid">  
  <div class="row">
  <div class="col-sm-1"></div>
  <div class="col-sm-10">
  
						<button data-toggle="collapse" data-target="#duodraftleagueinfo" class="btn btn-lg blacktext btn-link">
						<span class="glyphicon glyphicon-chevron-right"></span>
						<strong>Duo Draft League</strong></button>
						<br>
						<div class="collapse" id="duodraftleagueinfo">
						<small>Duo Draft Battles can be played in the Great, Ultra, or Master Leagues versus one of your friends. Once sent both players select one Pokemon to ban (reveal bans at the same time), then both choose their 1st round pick (reveal 1st picks at the same time), then both choose their 2nd round pick (reveal 2nd picks at the same time), and finaly both choose their 3rd round pick (reveal 3rd picks at the same time). Then both players battle eachother using only the 3 Pokemon that they drafted in any order they want! <strong>The Duo Draft League prevents users from repeated picks with each round and the banned Pokemon from the Ban round cannot be used, however if users choose the <i>same</i> Pokemon at the same time then they will both be able to use that Pokemon on their team!</strong></small><br>
						<img src="images/9000.png" alt="Duo Draft Picture" width="50" height="50">
						<img src="images/9001.png" alt="Duo Draft Picture" width="50" height="50">
						<img src="images/9002.png" alt="Duo Draft Picture" width="50" height="50">
						<hr>
						</div>
						
						<button data-toggle="collapse" data-target="#minidraftleagueinfo" class="btn btn-lg blacktext btn-link">
						<span class="glyphicon glyphicon-chevron-right"></span>
						<strong>Mini Draft League</strong></button>
						<br>
						<div class="collapse" id="minidraftleagueinfo">
						<small>Mini Draft Battles can be played in the Great, Ultra, or Master Leagues versus one of your friends. Once sent the Sender gets 1st pick, Receiver then picks 2nd and 3rd, Sender picks the 4th and 5th, and finally Receiver picks the last 6th spot. Then both players battle eachother using only the 3 Pokemon that they drafted in any order they want. <strong>The draft League prevents duplicates from being selected.</strong></small><br>
						<img src="images/minidraftpics.png" alt="Mini Draft Example" width="280" height="250">
						<hr>
						</div>
						
						<button data-toggle="collapse" data-target="#onetypeleague" class="btn btn-lg blacktext btn-link">
						<span class="glyphicon glyphicon-chevron-right"></span>
						<strong>One Type League</strong></button>
						<br>
						<div class="collapse" id="onetypeleague">
						<small> Both players must choose Pokemon that have at least one of the selected types for the league. For example, the <img src="images/5.png" alt="League-Emblem" width="19" height="20"> <strong>"Bug Only Great League"</strong> will be fought in the <img src="images/1.png" alt="League-Emblem" width="19" height="20"> Great League and the opponents can choose pure Bug type Pokemon <i>or dual types</i> that have at least one type classified as Bug, such as Bug/Steel. Duplicate species are accepted. Send this battle to your friends to test your skills!</small>
						<hr>
						</div>
					
						<button data-toggle="collapse" data-target="#gymleaderleague" class="btn btn-lg blacktext btn-link">
						<span class="glyphicon glyphicon-chevron-right"></span>
						<strong>Gym Leader League</strong></button>
						<br>
						<div class="collapse" id="gymleaderleague">
						<small> The sender (Gym Leader) can select what kind of <strong>Gym Leader</strong> they would like to be and must ONLY use types that have at least one of the selected types (duplicates are fine). For example, a battle sender can select <img src="images/20.png" alt="League-Emblem" width="19" height="20"> <strong>"Rock Gym Leader Great League"</strong> and they must ONLY use types that have at least one typing classified as rock but can also choose dual types such as rock-water. Your opponent will be able to use ANY type that they want to try to defeat you! If you are up for the challenge send this battle request to a friend <i>from the friends list</i> to test your skills as a <strong>Gym Leader</strong>!</small>
						<hr>
						</div>
						
	</div>
  <div class="col-sm-1"></div>
  </div>
</div>
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="onlineoffline();">Close</button>
        </div>
      </div>
    </div>
  </div><!--end of league modal-->
  
    <!--Number of friends battle ready and Trainers Online Modal Modal -->
<div id="numberoffriendsbattleready" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!--Start Battle Ready Friends Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
        <h4 class="modal-title text-center"><strong>Battle Ready Friends</strong></h4>
      </div>
      <div class="modal-body">
	  
 <?php
 $numberofbattleready = 0;
if(isset($_SESSION['userid'])){
		//battle ready drop down display entertimedisplay enter time
	
	//Set the timezone 
			date_default_timezone_set("America/Los_Angeles");
			$unixtime = time();
			//subtract 5 minutes (300 seconds)
			$past5minutes = $unixtime-300;

				if(isset($_SESSION['userid'])){
				$loggedinuser = $_SESSION['userid'];
				$sqlacceptedfriends = "Select * FROM friendslist WHERE userid=$loggedinuser AND friendaccepted=1 ORDER BY id ASC";
				$accpetedfriendsresult = mysqli_query($conn, $sqlacceptedfriends);
				$acceptedfriendsarray = array();
				if (mysqli_num_rows($accpetedfriendsresult) >= 0) {
					
					while($row = mysqli_fetch_assoc($accpetedfriendsresult)){
						$acceptedfriendsarray[] = $row;
					}
					
						echo '<div id="battlereadyfriends">';
					//foreach to disply battle ready friends
					$numberofbattleready = 0;
				foreach ($acceptedfriendsarray as $acceptedfriends) {
					//Each sentuser has their own id
					$eachfriendid=$acceptedfriends['friendid'];
					
					$sqlbattleready = "Select pogoname, activitytime, entertime FROM users WHERE userid=$eachfriendid AND entertime>$unixtime";
					$battlereadyresults = mysqli_query($conn, $sqlbattleready);
					$battlereadyarray = array();
					if (mysqli_num_rows($battlereadyresults) > 0) {
						while($row = mysqli_fetch_assoc($battlereadyresults)){
							$battlereadyarray[] = $row;
						}
  $enteredtime = $battlereadyarray[0]['entertime'];
  $timedifference = (($enteredtime)-($unixtime));
  
						$numberofbattleready = ($numberofbattleready+1);
						
						$_SESSION['battlereadyfriends']='1';
					}
				}
			
				
				echo '</div>';			
	}else{
		echo 'Start adding friends!';
	}
?> 
<div class="container-fluid" id="entertime"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
	
    <!--END OF Battle Read Friends Modal content-->


  </div>
</div>
<!-- End of Number of friends battle ready and Trainers online modal-->
  <small>
  <div id="battlereadytime" class="text-center">
  <?php
  if(isset($_SESSION['userid'])){

     $enteredtime = $_SESSION['entertime'];
  $timedifference = (($enteredtime)-($unixtime));
  
  if($timedifference<=60){
	  echo '<small>Enter battle ready time.</small>';
  }elseif($timedifference>10 && $timedifference<=3599){
	  $timeinminutes = round($timedifference/60);
	  echo '<span class="glyphicon glyphicon-certificate"></span>Battle ready for the next '.$timeinminutes.' minutes.';
  }else{
	  $timeinhours = round(($timedifference/3600),1);
	  echo '<span class="glyphicon glyphicon-certificate"></span>Battle ready for the next '.$timeinhours.' hours';
  }
				
				echo '<button type="button" data-toggle="modal" data-target="#numberoffriendsbattleready" style="background-color:#ff9999" class="btn btn-block blacktext btn-default btn-shadow" onclick="displayentertime();displayentertimedisplaycurrent();clearTimeout(updateentertimedisplay);">
						<strong>Battle Ready<br><span class="glyphicon glyphicon-chevron-right"></span>
						<small>Number of Friends Battle Ready: '.$numberofbattleready.' </small></strong><br>';
						
				echo '<small>Trainers Online: </small>';
				//display number of users online
				
			
//find everyone that was on in the last 5 minutes?
			$sqlnumusers = "Select userid FROM users WHERE activitytime>$past5minutes";
			$onlineresults = mysqli_query($conn, $sqlnumusers);
			if (mysqli_num_rows($onlineresults) > 0) {
				$usersonlinecount = mysqli_num_rows($onlineresults);
				echo $usersonlinecount;
			}else{
				echo '0';
			}
				echo '</button>';
				
				}else{
					echo 'Start adding friends to send battles, OR join a Ladder Tourney below to send a Challenge with the yellow button to someone else in the same Tourney!';
				}
				}
				
				//ADDED FOR MISSING PANELS ON NON LOGIN!!!! GLITCH
  }else{
	  echo '</div></div></div>';
  }
?>
  </div>
    </small>
  
  <!-- Battle Ready Teams Modal -->
<div id="battlereadymodal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header text-center">
        <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
        <h3 class="modal-title"><strong>Battle Ready Teams</strong></h3>
      </div>
      <div class="modal-body">
	  
	  
<div class="container-fluid">
   <div><small><i>*Update only one pick at a time.</i></small></div>
<div class="table-responsive" id="displaybattlereadyteams"></div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
			//prevent network spam
			function stopbattletimeouts() {
				clearTimeout(updatebattledisplay);
			};
			
			function battlereadymodalfunction() {
							$('#battlereadymodal').modal('toggle');
						};
			//displays battle ready teams when pulled up, refresh with delayed update after picks have been selected
				function displaybattlereadyteams() {
					//toggle modal togglemodal
					$('#numberoffriendsbattleready').modal('toggle');
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("displaybattlereadyteams").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/battlereadyteamsdisplay.php", true);
						xhttp.send();
						
						setTimeout(battlereadymodalfunction, 500);
						
					};
					function displaybattlereadyteamsonupdate() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("displaybattlereadyteams").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/battlereadyteamsdisplay.php", true);
						xhttp.send();
					};
					
			//monslot is just the number
			function updatethemedcuppick(monslot){
						var xhttp = new XMLHttpRequest();
						xhttp.open("POST","i/updatethemedcuppick.php?themedcuppick="+document.getElementById("themedcupmon"+monslot).value+"&monslot=mon"+monslot, true);
						xhttp.send();
					};
					
			//monslot is just the number
			function updatecustomsix(monslot){
						var xhttp = new XMLHttpRequest();
						xhttp.open("POST","i/updatecustomsixpick.php?customsixpick="+document.getElementById("customsix"+monslot).value+"&monslot=mon"+monslot, true);
						xhttp.send();
					};
			
				//displays the last battle, will update every 4 minutes (240000 ms)
				function displaylastbattle() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("battledisplaylast").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/battledisplaylast.php", true);
						xhttp.send();	
					};
					
					//displays active, pending, and sent battle requests will update every 4 seconds
					function minidraftclearupdate() {
							clearTimeout(updatebattledisplay);
						};
					//stop updating battle display after a user has been inactive for 20 minutes (1200000 ms)
					function stoprefreshingdisplaybattles() {
						clearTimeout(updatebattledisplay);
						clearTimeout(updatebattledisplay);
						document.getElementById("battledisplay").innerHTML = "<div class='text-center'><i>Idling</i></div>";
						document.getElementById("battleidle").innerHTML = "<br><button class='btn btn-lg btn-warning btn-block' onclick='this.disabled=true;displaybattles();setTimeout(stoprefreshingdisplaybattles, 1200000)'>Sync battles.<br>Click to see active battles.<br></button><br>";
						playdroplets();
					};
					
					function displaybattles() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("battledisplay").innerHTML = this.responseText;
								document.getElementById("battleidle").innerHTML = "";
								//
						<?php
						if(isset($_SESSION['userid'])){
							echo 'updatebattledisplay=setTimeout(displaybattles, 5000);';
						}
						?>
							}
						};
						xhttp.open("POST", "i/battledisplay.php", true);
						xhttp.send();
					};
					
					function getminiicon(battleid) {
						clearTimeout(getminiicontimer)
						getminiicontimer = setTimeout(fetchminiicon, 400);
						function fetchminiicon(){	
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("displayminiicon"+battleid).innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/getminiicon.php?minipick="+document.getElementById("monselect"+battleid).value, true);
						xhttp.send();
						}
					};
					
					function sendminidraftpick(battleid){
						var xhttp = new XMLHttpRequest();
						xhttp.open("POST","i/updateminidraftpick.php?minipick="+document.getElementById("monselect"+battleid).value+"&battleid="+battleid, true);
						xhttp.send();
					};
				
				//duo draft pick send
				function sendduodraftpick(battleid){
						var xhttp = new XMLHttpRequest();
						xhttp.open("POST","i/updateduodraftpick.php?duopick="+document.getElementById("monselect"+battleid).value+"&battleid="+battleid, true);
						xhttp.send();
					};
 </script>
 
<?php  if (isset($_SESSION['userid'])){
	echo '<hr>';
}
?>
			  <div id="battleidle"></div>
			  
<div id="battledisplay">
<?php
			  if (isset($_SESSION['userid'])){
				echo '<button class="btn btn-lg btn-block btn-shadow" style="background-color:rgba(0, 0, 0, .75);color:white;" onclick="this.disabled=true;displaybattles();setTimeout(stoprefreshingdisplaybattles, 1200000)">Sync battles.<br>Click to see active battles.<br></button><br>';
			  }else{
				  
	echo '<div style="font-size: 18px; line-height:20px;">
	<h3><strong><u>How to Join/Host</u>:</strong></h3> 
Welcome! PoGoPoints allows trainers to host a variety of Tournament types that include <strong>GO:Drafts</strong>, <strong>Team:Drafts</strong>, and <strong>Ladders</strong>. Create an account by using the <a href="#" data-toggle="modal" data-target="#signup"><u>Sign Up</u></a> button and Login to host or join a Tournament. Each Tournament type can be accessed by using the "gray" buttons for GO:Drafts, Team:Drafts, or Ladders. 
<br><br>
You can <strong>join</strong> a Tournament by using a code provided to you by the Host or found within the display link\'s "<strong>Message from Host:</strong>" section for one of the Tournaments. Simply click on the appropriate "gray" Tournament button, either for a GO:Draft, Team:Draft, or Ladder, and enter the <strong>code</strong> on the bottom of the pop-up in the "Enter Code" area and click "Enter". 
<br><br>
Once you enter a Tournament you will be able to navigate to that Tournament by using the appropriate "gray" button for the Tournament type and then clicking on the "Current" button to access the individual Tournaments you are in. 
<br><br>
Each Tournament has different rules to follow and they can be found under the "Rules" button for each individual Tournament. Also, more details can be found in the "<strong>Message from Host:</strong>" section where the Host can explain any custom rules for the Tournament.

<hr>

The following sections help to explain how each Tournament type functions on the website:


<br>

<h3><strong><u>GO:Drafts</u>:</strong></h3>

You can click on the “gray” GO:Drafts button to see the pop-up for accessing hosted, current, concluded and community GO:Drafts. You can Host a new GO:Draft and enter in a Title, Max CP, Create an Entry Code, and enter in a Host message. Provide the GO:Draft Entry code to your group members so that they can enter in the Tournament. The Host can remove players from the GO:Draft before it starts if someone is unable to play. Also, the Host can click the “Randomize Order on Initiation” to have the group members’ positions randomized at the start of the draft. 
<br><br>
Once the Host Initiates, the GO:Draft participants can start drafting. You cannot select duplicates in the drafting phase, UNLESS the Host changes the number of species allowed for the draft. To help speed the draft along, users can create “Auto Draft” lists for each pick that they want each round. The Auto Draft will pick for a user if they create the list and that pick is available when it is their turn. 
<br><br>
After the Draft is complete, users can utilize the “GO:Draft Battle Form” and submit battle results with other users in the GO:Draft. When you submit a battle form your opponent will need to accept the results under the “PVP Battles” section on the home page. When all users are done battling you can see the number of wins and losses under the “GO:Draft Battles” section for the GO:Draft. The Host can then conclude the Tournament and those who participated in the GO:Draft can see the GO:Draft link under the “Concluded GO:Drafts” button. 

<br>
<h3><strong><u>Team:Drafts</u>:</strong></h3>
You can click on the “gray” Team:Drafts button to see the pop-up for accessing hosted, current, concluded and community Team:Drafts. You can Host a new Team:Draft and enter in a Title, Max CP, Create an Entry Code, Battle Party Size, Number of Bans/Picks, Team Names, and enter in a Host message. Provide the Team:Draft Entry code to your group members so that they can enter in the Tournament. The Host can remove players from the Team:Draft before it starts if someone is unable to play.
<br><br>
Once the Host Initiates, the Team:Draft participants can start drafting. Team 1 will be the “red” team and bans first, while Team 2 will be the “gray” team and bans second and the teams go back and forth until all bans are made. Then, a snake draft starts between the 2 teams with Team 1 getting first pick and Team 2 getting the Final pick.  You cannot select duplicates in the drafting phase. You make your picks/bans by going to “Current Team:Drafts”, clicking on the correct Team:Draft and scrolling down to find the “Send Team:Draft Ban/Pick” button when it is your team’s turn to pick. Anyone on the team can enter in a pick/ban for their respective team.
<br><br>
After the Draft is complete, the Teams will build Battle Parties with what they drafted. Once they are done creating their parties the Host can activate “Battle Party Display” so that users can see the other team’s battle parties. Users can then utilize the “Team:Draft Battle Form” and submit battle results with other users in the Team:Draft. When you submit a battle form your opponent will need to accept the results under the “PVP Battles” section on the home page. When all users are done battling you can see the number of wins and losses under the “Team:Draft Battles” section for the Team:Draft. The Host can then conclude the Tournament and those who participated in the Team:Draft can see the Team:Draft link under the “Concluded Team:Drafts” button. 

<br>
<h3><strong><u>Ladders</u>:</strong></h3>
You can click on the “gray” Ladders button to see the pop-up for accessing hosted, current, concluded and community Ladders. You can Host a new Ladder and enter in a Title, League, Create an Entry Code, Conclusion Date, and enter in a Host message. Provide the Ladder Entry code to your group members so that they can enter in the Tournament. The Host can remove players from the Ladder. 
<br><br>
Once started users can start sending “Challenges” to other users in the Ladder. If the Host leaves in and Entry Code then users can join the Ladder anytime after it starts, but cannot join once it concludes. You can send a Ladder challenge by clicking the “Current Ladders” button and the opening the Ladder that you are currently in. Scroll down to the Challenge button and find a person that you are on the same stage with in the Ladder. Send them a Challenge and complete the battle under the “PVP Battles” section on the home page. 
<br><br>
The Champion of the Ladder is determined by whoever gets to the highest Stage on the Ladder with the Fewest Losses, tiebreakers go to those who joined the Ladder First, by the end of the month. The max number of stages for a Ladder is the total number of participants minus one. 
<br><br>
Ladders challenges can be set up to be fought with the “Duo Draft” battling style unique to PoGoPoints. Duo Draft Battles can be played in the Great, Ultra, or Master Leagues versus one of your friends. Once sent both players select one Pokemon to ban (reveal bans at the same time), then both choose their 1st round pick (reveal 1st picks at the same time), then both choose their 2nd round pick (reveal 2nd picks at the same time), and finally both choose their 3rd round pick (reveal 3rd picks at the same time). Then both players battle each other using only the 3 Pokemon that they drafted in any order they want! The Duo Draft League prevents users from repeated picks with each round and the banned Pokemon from the Ban round cannot be used, however if users choose the same Pokemon at the same time then they will both be able to use that Pokemon on their team! 


<br>
<h3><strong><u>Friends</u>:</strong></h3>
If you do not have a Tournament to challenge others in, then you can use the button for Friends and add people who have made an account on the site and send random battle types to your friends. These battles can be ranked or unranked matches and can be fought at anytime with your friends. There is also a Battle Form that you can send to your friends to record multiple matches at once instead of having to send each match individually on the website. 
<br><br>
Test your skills by trying out different leagues that include Duo Draft League, Mini Draft League, One Type League, and the Gym Leader League. Find out more information about these battle types by clicking on the “League Info” link on the navigation bar at the top of the screen!


<br>
</div>
';
}
?>
 <?php
 if (!isset($_SESSION['userid'])){
  echo '
	<div class="text-center">
	<hr>
	
	<strong>How to Do a Mini-Draft Battle 1v1</strong>
	<iframe width="100%" height="315" src="https://www.youtube.com/embed/4BuaK5yGKCQ?controls=1"></iframe>
	<small><small>Visit <a href="https://www.youtube.com/channel/UCUeKzp_kKs1hPovnIp97J3w/featured" target="_blank">Jake Does Hurdles</a> on YouTube for more PVP content or <br><a href="https://discord.gg/7usyKPA" target="_blank" onclick="fadeinout();">Connect on Discord</a>!</small></small>
	</div>
	';
 }
 ?>
</div>
<?php
//functions to call when logged in
			  
			  if (isset($_SESSION['userid'])){
				echo '<script>
				$(document).ready(function(){
					updateentertimedisplay=setTimeout(displayentertimedisplaycurrent, 180000);
					autoscrollgroupchat();
				}
				);
			  </script>';
			  }
			  ?>
            </div>
			<div class="container-fluid">
			<div class="well" id="battledisplaylast">
<?php
if(isset($_SESSION['userid'])){
	//continue
	$userid = $_SESSION['userid'];
		
	//Display Last Battle so users can quickly click rematch
		$sqlcheck = "Select * FROM battlerecords WHERE (senderid='$userid' OR receiverid='$userid') AND accepted=1 AND cancelled=0 AND NOT senderwin=receiverwin AND sendercompletedbattle=1 AND receivercompletedbattle=1 AND pointsawarded=1 ORDER BY timestamp DESC LIMIT 1;";
		$lastbattleresult = mysqli_query($conn, $sqlcheck);
		$lastbattlesarray = array();
		if (mysqli_num_rows($lastbattleresult) > 0) {
			while($row = mysqli_fetch_assoc($lastbattleresult)){
				$lastbattlesarray[] = $row;
			}
			
				if(isset($_SESSION['userid'])){
	echo '
			<a class="btn btn-link btn-xs blacktext inline" onclick="displaylastbattle();onlineoffline();fadeinout();">
				<span class="glyphicon glyphicon-refresh"></span>
			</a>';
	}
			echo '<strong>Last Battle: </strong><br>';
			
			$senderid=$lastbattlesarray[0]['senderid'];
			$receiverid=$lastbattlesarray[0]['receiverid'];
			// check to see if the user was the sender in this case the battled user will be the dreceiverid
			if($senderid==$userid){
	//	
				$sqlrecievedfriend = "Select * FROM users WHERE userid=$receiverid";
				$receiveridresults = mysqli_query($conn, $sqlrecievedfriend);
				$receiveridarray = array();
				if (mysqli_num_rows($receiveridresults) > 0) {
					while($row = mysqli_fetch_assoc($receiveridresults)){
						$receiveridarray[] = $row;
					}
				}
				$friendname=$receiveridarray[0]['pogoname'];
				echo '<div class="container-fluid" style="line-height: 20px;">
					<div class="row text-center">';
					
					//show what league and who the user battled
					echo '<div class="col-sm-6">';
						echo '<small><img src="images/'.$lastbattlesarray[0]['battletype'].'.png" alt="League-Emblem" width="28" height="30"> '.$lastbattlesarray[0]['battleleague'].' League </small><br class="hidden-xs"> <i>vs</i> <strong>'.$friendname.'</strong>';
				
					echo '</div>';
					
					//display how many points were exchanged
					echo '<div class="col-sm-6">';
						if($lastbattlesarray[0]['senderwin']==1){
							echo '<span class="greenhx">You won: +'.$lastbattlesarray[0]['pointexchange'].' points </span>';
						}else{
							echo '<span class="redhx">You lost: -'.$lastbattlesarray[0]['pointexchange'].' points </span>';
						}
		
					echo '</div>';
					
				echo '</div></div>';
				
			}else{
	//
				$sqlrecievedfriend = "Select * FROM users WHERE userid=$senderid";
				$receiveridresults = mysqli_query($conn, $sqlrecievedfriend);
				$receiveridarray = array();
				if (mysqli_num_rows($receiveridresults) > 0) {
					while($row = mysqli_fetch_assoc($receiveridresults)){
						$receiveridarray[] = $row;
					}
				}
				$friendname=$receiveridarray[0]['pogoname'];
				echo '<div class="container-fluid" style="line-height: 20px;">
					<div class="row text-center">';
					
					//show what league and who the user battled
					echo '<div class="col-sm-6"><img src="images/'.$lastbattlesarray[0]['battletype'].'.png" alt="League-Emblem" width="28" height="30"> ';
						echo $lastbattlesarray[0]['battleleague'].' League <br class="hidden-xs"> <i>vs</i> <strong>'.$friendname.'</strong>';
				
					echo '</div>';
					
					//display how many points were exchanged
					echo '<div class="col-sm-6">';
						if($lastbattlesarray[0]['receiverwin']==1){
							echo '<span class="greenhx">You won: +'.$lastbattlesarray[0]['pointexchange'].' points </span>';
						}else{
							echo '<span class="redhx">You lost: -'.$lastbattlesarray[0]['pointexchange'].' points </span>';
						}
					
					echo '</div>';
					
				echo '</div></div>';
	//

			}
		}else{
			echo '<div><small><strong>Steps for using this website: </strong><br>
			
			(1) Send or accept a battle request on this site with a friend by using the friends list for a <u>random battle</u> (ranked or unranked) OR by joining a <u>Ladder Tourney</u> and using the Current Tourney area and sending a <u>Challenge</u> with the yellow button to a friend!<br>
			(2) The person that sent the site request should also be the one to send the battle request in the game. <br>
			(3) Complete <i>one</i> battle (NOT a best of three). <br>
			(4) Return to the website and click "I Won" or "I lost". <br>
			(5) Repeat and have fun!
			</small></div>
			<hr>';
		}
echo '<strong>Next Battle:</strong><form name="form1" action="i/battleinitiate.php" method="POST">
							
							<div class="row text-center">
							<div class="col-xs-6">
							<select class="form-control" id="friendidbattledisplay" name="eachfriendid" required>
							<option value="">Friend:</option>
							';
							
							$sortedfriends = "Select friendslist.id, friendslist.userid, friendslist.friendid, friendslist.friendaccepted, users.userid, users.pogoname FROM friendslist INNER JOIN users ON friendslist.friendid=users.userid WHERE friendslist.userid='$userid' AND friendaccepted='1' ORDER BY pogoname ASC;";
				$sortedresults = mysqli_query($conn, $sortedfriends);
				$sortedfriendsarray = array();
				if (mysqli_num_rows($sortedresults) > 0) {
					while($row = mysqli_fetch_assoc($sortedresults)){
						$sortedfriendsarray[] = $row;
					}
					foreach($sortedfriendsarray as $sortedfriend){
						echo '<option value="'.$sortedfriend['friendid'].'">'.$sortedfriend['pogoname'].'</option>';
					}
				}
				echo '</select>
									<select class="form-control" name="selectedbattleleague" required>';
							include 'i/battleoptions.php';
						echo '
									</select>
							</div>
							
							<div class="col-xs-6">
														
							<button class="btn btn-block btn-shadow" style="background-color:black;color:white;" name="submit" type="submit" onclick="fadeinout();">Send Battle</button>
							<small><small><input type="checkbox" value="1" name="practicebattle" checked><strong>Unranked</strong> &plusmn0 points</small></small>
							</div>
							</div>
						</form>';
}else{
	echo '
	<div class="text-center"><strong><small>Active PvP battles will be displayed here!</small></strong></div>
	';
}
?></div><!-- DIV well Last Battle-->
</div><!--Div container fluid-->
		
          </div>  
      </div><!--row div for pvp battles-->

	 </div><!--End of col-md-7 div for pvp battles/leaderboard-->
  </div><!--End of ROW div for entire body area... -->
</div><!--end container for 3 panels for entire body area...-->

<footer class="container-fluid text-center">
  <div id="myfooter" class="container-fluid">
	<a href="#myPage" title="To Top" class="footerfont">
		<span class="glyphicon glyphicon-chevron-up"></span>
		<p>Return to top</p>
	</a>
	<hr>
	<!--<a href="https://www.facebook.com/pogopoints" target="_blank" class="socialicon fa fa-facebook" aria-label="PoGoPoints Facebook" rel="noopener"></a>-->
	<a href="https://www.patreon.com/bePatron?u=25912104" data-patreon-widget-type="become-patron-button">Become a Patron!</a><script async src="https://c6.patreon.com/becomePatronButton.bundle.js"></script><br>
  <a href="https://www.reddit.com/r/PokemonGoAppPVP/" target="_blank" class="socialicon fa fa-reddit" aria-label="PoGoPVP Reddit" rel="noopener"></a>
  <a href="https://twitter.com/pogopoints" target="_blank" class="socialicon fa fa-twitter" aria-label="PoGoPoints Twitter" rel="noopener"></a>
   <a href="https://www.instagram.com/pogopoints/" target="_blank" class="socialicon fa fa-instagram" aria-label="PoGoPoints Instagram" rel="noopener"></a>
  <br>	  
	  <span class="pogopointslogo"><i>&#169;</i> 2019 PoGoPoints. All Rights Reserved.</span>
	  <br>
	  <script>
			function displayterms() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("termsandconditionsarea").innerHTML = this.responseText;
							}
						};
						xhttp.open("GET", "termsandconditions.php", true);
						xhttp.send();
					}
				
		</script>
      <a href="#" data-toggle="modal" data-target="#termsmodal" class="footerfont" onclick="displayterms()">&#8226;Terms & Conditions </a>

			    <!-- Terms Modal below -->
  <div class="modal fade blacktext text-left" id="termsmodal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title">Terms and Conditions</h3>
        </div>
        <div class="modal-body">
			<div id="termsandconditionsarea" class="container-fluid"></div>
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div><!--end of Terms modal-->
  
	  <br>
	  <script>
			function displayprivacy() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("privacyarea").innerHTML = this.responseText;
							}
						};
						xhttp.open("GET", "privacypolicy.php", true);
						xhttp.send();
					}
				
		</script>
	  <a href="#" data-toggle="modal" data-target="#privacymodal" onclick="displayprivacy()" class="footerfont">&#8226;Privacy </a>
	  
	   <!-- Privacy Modal below -->
  <div class="modal fade blacktext text-left" id="privacymodal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span></button>
          <h3 class="modal-title">Privacy Policy</h3>
        </div>
        <div class="modal-body">
			<div id="privacyarea" class="container-fluid"></div>
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div><!--end of Privacy modal-->
  
  <!--<br><img src="icons/android-icon-192x192.png" alt="PoGoPoints Logo" width="40" height="40">-->
	  
	  <br><small>
	  <small>PoGoPoints is not affiliated with Niantic Inc., The Pokemon Company, or Nintendo. Pokémon And All Respective Names are Trademark & © of Nintendo 1996-2019. Pokémon GO is Trademark & © of Niantic, Inc.
	  </small>
	  </small>
	  <br>
  </div>
</footer>
<script>
$(document).ready(function(){
  // Add smooth scrolling to all links in navbar + footer link
  $(".navbar a, footer a[href='#myPage']").on('click', function(event) {
    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault();
      // Store hash
      var hash = this.hash;
      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (900) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 900, function(){
   
        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
  $(window).scroll(function() {
    $(".slideanim").each(function(){
      var pos = $(this).offset().top;

      var winTop = $(window).scrollTop();
        if (pos < winTop + 600) {
          $(this).addClass("slide");
        }
    });
  });
 });
 //confirm pop up for I won and I lost buttons
function confirmation() {
  confirm("Submit Results?");
};
<?php
	//call display battles if there are active battles... and call the 20 minute timeout to stop displaying after 20mins
		$sqlcheck1 = "Select * FROM battlerecords WHERE (senderid='$userid' OR receiverid='$userid') AND pointsawarded=0 AND cancelled=0;";
		$reslutstocalldisplaybattles = mysqli_query($conn, $sqlcheck1);
		if (mysqli_num_rows($reslutstocalldisplaybattles) > 0) {
			echo 'displaybattles();setTimeout(stoprefreshingdisplaybattles, 1200000);';
		}else{
			$sqlcheck1 = "Select * FROM submitbattles WHERE (userid='$userid' OR friendid='$userid') AND dealtwith=0;";
		$reslutstocalldisplaybattles = mysqli_query($conn, $sqlcheck1);
		if (mysqli_num_rows($reslutstocalldisplaybattles) > 0) {
			echo 'displaybattles();setTimeout(stoprefreshingdisplaybattles, 1200000);';
		}
		}
?>
function monlistfilter(x) {
    var input, filter, select, option, a, i, txtValue;
    input = document.getElementById("mymonsearch"+x);
    filter = input.value.toUpperCase();
    select = document.getElementById("monselect"+x);
    option = select.getElementsByTagName("option");
    for (i = 0; i < option.length; i++) {
        a = option[i];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
			//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "";
			//"option[i].value" with give you the last #number of the pokemon on the new select list
			// input.value gives you what was typed in the search box
			//"txtValue.toUpperCase();" whole name of last pokemon in the list
			txtValueName = txtValue.toUpperCase();
				//document.getElementById("monselect"+x).value = filter;
				var str = txtValueName;
				if(str.startsWith(filter)){
					document.getElementById("monselect"+x).value = option[i].value;
				}
			
        } else {
		//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "none";
        }
    };
	
	if(filter == ""){
		document.getElementById("monselect"+x).selectedIndex = "0";
	}
};

function monlistfilterteamdraft(x) {
    var input, filter, select, option, a, i, txtValue;
    input = document.getElementById("mymonsearchteamdraft"+x);
    filter = input.value.toUpperCase();
    select = document.getElementById("monselectteamdraft"+x);
    option = select.getElementsByTagName("option");
    for (i = 0; i < option.length; i++) {
        a = option[i];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
			//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "";
			//"option[i].value" with give you the last #number of the pokemon on the new select list
			// input.value gives you what was typed in the search box
			//"txtValue.toUpperCase();" whole name of last pokemon in the list
			txtValueName = txtValue.toUpperCase();
				//document.getElementById("monselect"+x).value = filter;
				var str = txtValueName;
				if(str.startsWith(filter)){
					document.getElementById("monselectteamdraft"+x).value = option[i].value;
				}
			
        } else {
		//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "none";
        }
    };
	
	if(filter == ""){
		document.getElementById("monselectteamdraft"+x).selectedIndex = "0";
	}
};


function monlistfiltergodraft(x) {
    var input, filter, select, option, a, i, txtValue;
    input = document.getElementById("mymonsearchgodraft"+x);
    filter = input.value.toUpperCase();
    select = document.getElementById("monselectgodraft"+x);
    option = select.getElementsByTagName("option");
    for (i = 0; i < option.length; i++) {
        a = option[i];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
			//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "";
			//"option[i].value" with give you the last #number of the pokemon on the new select list
			// input.value gives you what was typed in the search box
			//"txtValue.toUpperCase();" whole name of last pokemon in the list
			txtValueName = txtValue.toUpperCase();
				//document.getElementById("monselect"+x).value = filter;
				var str = txtValueName;
				if(str.startsWith(filter)){
					document.getElementById("monselectgodraft"+x).value = option[i].value;
				}
			
        } else {
		//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "none";
        }
    };
	
	if(filter == ""){
		document.getElementById("monselectgodraft"+x).selectedIndex = "0";
	}
};

function monlistfiltergodrafthost(x) {
    var input, filter, select, option, a, i, txtValue;
    input = document.getElementById("mymonsearchgodrafthost"+x);
    filter = input.value.toUpperCase();
    select = document.getElementById("monselectgodrafthost"+x);
    option = select.getElementsByTagName("option");
    for (i = 0; i < option.length; i++) {
        a = option[i];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
			//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "";
			//"option[i].value" with give you the last #number of the pokemon on the new select list
			// input.value gives you what was typed in the search box
			//"txtValue.toUpperCase();" whole name of last pokemon in the list
			txtValueName = txtValue.toUpperCase();
				//document.getElementById("monselect"+x).value = filter;
				var str = txtValueName;
				if(str.startsWith(filter)){
					document.getElementById("monselectgodrafthost"+x).value = option[i].value;
				}
			
        } else {
		//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "none";
        }
    };
	
	if(filter == ""){
		document.getElementById("monselectgodrafthost"+x).selectedIndex = "0";
	}
};

function monlistfiltergodrafthostchange(x) {
    var input, filter, select, option, a, i, txtValue;
    input = document.getElementById("mymonsearchgodrafthostchange"+x);
    filter = input.value.toUpperCase();
    select = document.getElementById("monselectgodrafthostchange"+x);
    option = select.getElementsByTagName("option");
    for (i = 0; i < option.length; i++) {
        a = option[i];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
			//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "";
			//"option[i].value" with give you the last #number of the pokemon on the new select list
			// input.value gives you what was typed in the search box
			//"txtValue.toUpperCase();" whole name of last pokemon in the list
			txtValueName = txtValue.toUpperCase();
				//document.getElementById("monselect"+x).value = filter;
				var str = txtValueName;
				if(str.startsWith(filter)){
					document.getElementById("monselectgodrafthostchange"+x).value = option[i].value;
				}
			
        } else {
		//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "none";
        }
    };
	
	if(filter == ""){
		document.getElementById("monselectgodrafthostchange"+x).selectedIndex = "0";
	}
};


function monlistfilterteamdrafthostchange(x) {
    var input, filter, select, option, a, i, txtValue;
    input = document.getElementById("mymonsearchteamdrafthostchange"+x);
    filter = input.value.toUpperCase();
    select = document.getElementById("monselectteamdrafthostchange"+x);
    option = select.getElementsByTagName("option");
    for (i = 0; i < option.length; i++) {
        a = option[i];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
			//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "";
			//"option[i].value" with give you the last #number of the pokemon on the new select list
			// input.value gives you what was typed in the search box
			//"txtValue.toUpperCase();" whole name of last pokemon in the list
			txtValueName = txtValue.toUpperCase();
				//document.getElementById("monselect"+x).value = filter;
				var str = txtValueName;
				if(str.startsWith(filter)){
					document.getElementById("monselectteamdrafthostchange"+x).value = option[i].value;
				}
			
        } else {
		//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "none";
        }
    };
	
	if(filter == ""){
		document.getElementById("monselectteamdrafthostchange"+x).selectedIndex = "0";
	}
};

function monlistfiltergodraftauto(x) {
    var input, filter, select, option, a, i, txtValue;
    input = document.getElementById("mymonsearchgodraftauto"+x);
    filter = input.value.toUpperCase();
    select = document.getElementById("monselectgodraftauto"+x);
    option = select.getElementsByTagName("option");
    for (i = 0; i < option.length; i++) {
        a = option[i];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
			//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "";
			//"option[i].value" with give you the last #number of the pokemon on the new select list
			// input.value gives you what was typed in the search box
			//"txtValue.toUpperCase();" whole name of last pokemon in the list
			txtValueName = txtValue.toUpperCase();
				//document.getElementById("monselect"+x).value = filter;
				var str = txtValueName;
				if(str.startsWith(filter)){
					document.getElementById("monselectgodraftauto"+x).value = option[i].value;
				}
			
        } else {
		//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "none";
        }
    };
	
	if(filter == ""){
		document.getElementById("monselectgodraftauto"+x).selectedIndex = "0";
	}
};

function monlistfilterteamdraftparty(x) {
    var input, filter, select, option, a, i, txtValue;
    input = document.getElementById("mymonsearchteamdraftparty"+x);
    filter = input.value.toUpperCase();
    select = document.getElementById("monselectteamdraftparty"+x);
    option = select.getElementsByTagName("option");
    for (i = 0; i < option.length; i++) {
        a = option[i];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
			//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "";
			//"option[i].value" with give you the last #number of the pokemon on the new select list
			// input.value gives you what was typed in the search box
			//"txtValue.toUpperCase();" whole name of last pokemon in the list
			txtValueName = txtValue.toUpperCase();
				//document.getElementById("monselect"+x).value = filter;
				var str = txtValueName;
				if(str.startsWith(filter)){
					document.getElementById("monselectteamdraftparty"+x).value = option[i].value;
				}
			
        } else {
		//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "none";
        }
    };
	
	if(filter == ""){
		document.getElementById("monselectteamdraftparty"+x).selectedIndex = "0";
	}
};

function monlistfiltergodraftwaiver(x) {
    var input, filter, select, option, a, i, txtValue;
    input = document.getElementById("mymonsearchgodraftwaiver"+x);
    filter = input.value.toUpperCase();
    select = document.getElementById("monselectgodraftwaiver"+x);
    option = select.getElementsByTagName("option");
    for (i = 0; i < option.length; i++) {
        a = option[i];
        txtValue = a.textContent || a.innerText;
        if (txtValue.toUpperCase().indexOf(filter) > -1) {
			//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "";
			//"option[i].value" with give you the last #number of the pokemon on the new select list
			// input.value gives you what was typed in the search box
			//"txtValue.toUpperCase();" whole name of last pokemon in the list
			txtValueName = txtValue.toUpperCase();
				//document.getElementById("monselect"+x).value = filter;
				var str = txtValueName;
				if(str.startsWith(filter)){
					document.getElementById("monselectgodraftwaiver"+x).value = option[i].value;
				}
			
        } else {
		//either shows or doesn't show one of the 509 indexed pokemon
            option[i].style.display = "none";
        }
    };
	
	if(filter == ""){
		document.getElementById("monselectgodraftwaiver"+x).selectedIndex = "0";
	}
};
</script>
</body>
<style>
body {
  height: 100%;
  margin: 0;

  
  /* Set a specified height, or the minimum height for the background image */
  min-height: 800px;
  
		/* The image used */
  background-image: url("mountains.jpg");

  
  /* Center and scale the image nicely */
  background-position: center;
  /*background-repeat: no-repeat;*/
  background-size: cover;
  background-attachment: fixed;
}
</style>
</html>