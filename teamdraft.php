<?php
session_start();
	include_once 'i/dbaccess.php'; 
	
	$_SESSION['leaderboard20']=-10;
	
	$eachteamdraftid = mysqli_real_escape_string($conn, $_GET['t']);
	
	if(!isset($_GET['t'])){
		header("Location: index.php?noteamdrafts");
		exit();
	}
	
	if(!preg_match("/^[0-9]*$/", $eachteamdraftid)){		
		header("Location: index.php?noteamdrafts");
		exit();
		}
	
	date_default_timezone_set("America/Los_Angeles");

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

<!--Add to other pages of website for ServiceWorkers-->

  <title>PoGoPoints-Team:Draft</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

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

<link rel="manifest" href="manifest1.0.3.json">
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
	body {
      font: 400 16px Lato, sans-serif;
      line-height: 1.8;
      color: black;
	  
  /*Default background color if image is slow to load?(move image to end of html?)*/
  background-color: black;
  
	  
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
  .panel-danger > .panel-heading {
  color: white;
  background-color: rgba(153, 0, 0, 0.80);

}

.extrasmall{
	font-size: 9px;
}
.groupchatscroll{
	height: 300px;
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
#titleedit{
	font-size: 15px;
}
.g-recaptcha {
	display: inline-block;
}
.panel-warning > .panel-heading {
color: white;
  background-color: rgba(89, 89, 89, 0.80);
}
.navbar {
	background-color: rgba(64, 64, 64, .9);
	transition: top 0.3s;
}
.panel{
	color: white;
	background-color: rgba(51, 51, 51,0.95);
}
.well{
	color: white;
	background-color: rgba(51, 51, 51,0.95);
}
.teamdraftstuff{
	background-color: rgba(255, 255, 255, 0.92);
}

  </style>
</head>

<body id="myPage">

<nav class="navbar navbar-inverse" id="hidenavbar">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand pogopointslogo confirmation" href="index.php" target="_self"><span class="glyphicon glyphicon-arrow-left"></span> Login to PoGoPoints</a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
<li><a class="text-center visible-xs" data-toggle="collapse" data-target="#myNavbar"><span class="glyphicon glyphicon-chevron-up"></span></a></li>
      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid">
<div class="row">
<div class="col-sm-1"></div>
	<div class="col-sm-10">
	<br>
			
<?php
		$sqlauniqueteamdrafts = "SELECT * FROM teamdrafts WHERE id='$eachteamdraftid' LIMIT 1;";
		$auniqueteamdraftresult = mysqli_query($conn, $sqlauniqueteamdrafts);
		$teamdraftarray = array();
		if (mysqli_num_rows($auniqueteamdraftresult) > 0) {
			while($row = mysqli_fetch_assoc($auniqueteamdraftresult)){
				$teamdraftarray[] = $row;
			}
		
		//results from godraft leader input
		$teamdraftid = $teamdraftarray[0]['id'];
		$teamdraftmaxcp = $teamdraftarray[0]['maxcp'];
		$teamdraftconcluded = $teamdraftarray[0]['concluded'];
		$teamdraftleader = $teamdraftarray[0]['leader'];
		$teamdrafttimestamp = $teamdraftarray[0]['timestamp'];
		$teamdraftpartysize = $teamdraftarray[0]['partysize'];
		$teamdraftpartyduplicates = $teamdraftarray[0]['partyduplicates'];
		$teamdraftmessage = htmlspecialchars(str_replace('\\', '', $teamdraftarray[0]['message']));
		$teamdrafttitle = htmlspecialchars(str_replace('\\', '', $teamdraftarray[0]['title']));
		$teamdraftinitiated = $teamdraftarray[0]['initiated'];
		$teamdraftteam1name = htmlspecialchars(str_replace('\\', '', $teamdraftarray[0]['team1name']));
		$teamdraftteam2name = htmlspecialchars(str_replace('\\', '', $teamdraftarray[0]['team2name']));
		
		//leaderinfo
		$sqlleaderinfo = "SELECT * FROM users WHERE userid='$teamdraftleader' LIMIT 1;";
		$leaderinforesults = mysqli_query($conn, $sqlleaderinfo);
		$leaderarray = array();
		if (mysqli_num_rows($leaderinforesults) > 0) {
			while($row = mysqli_fetch_assoc($leaderinforesults)){
				$leaderarray[] = $row;
			}
		}
		
		echo '
	<div class="container-fluid">
		<div class="row">
			<div class="text-center" style="background-color:rgba(255, 102, 102, .70);line-height:13px;">
				<br>
				<h2><img src="images/teamdraft.png" alt="Draft-Emblem" width="80" height="80"> <strong>'.$teamdrafttitle.'</strong> <small>(#'.$teamdraftid.')</small></h2>
				<small>
				<strong>Host:</strong> '.$leaderarray[0]['pogoname'].'<br>
				<strong>Max CP:</strong> '.$teamdraftmaxcp.'<br>
				<strong>Started:</strong> '.date("m-d-Y",$teamdrafttimestamp).'
				</small>
				<br>
				<br>
			</div>
		</div>
		<div class="row">
		<div class="well">
		<div class="row">
			<div class="col-sm-2 text-center"></div>
			<div class="col-sm-8 text-center">';
			
			//display battle party size and if duplicates are allowed
					echo '<div class="text-center" style="line-height: 13px;">
					<small><strong>Each Battle Party Size:</strong> '.$teamdraftpartysize.'<br>';
					if($teamdraftpartyduplicates=='0'){
						echo 'No Duplicates.';
					}else{
						echo 'Duplicates Allowed.';
					}
					echo '</small><br><br></div>';
			
			echo'
				<u>Message from Host:</u><br>
				'.$teamdraftmessage;
				
				if($teamdraftinitiated==0){
					echo '<hr><h3>Draft has NOT started!</h3>';
				}
				
				if($teamdraftconcluded=='1'){
					echo '<hr><h3>Draft Concluded!</h3>';
				}
				
		echo '		
			</div>
			<div class="col-sm-2 text-center"></div>
		</div>
		
		<hr>';
		
				$team1='1';
		$sqlteam1 = "SELECT teamdrafttrainers.id, teamdrafttrainers.teamdraftid, teamdrafttrainers.teamdrafttrainerid, teamdrafttrainers.whichteam, teamdrafttrainers.party, teamdrafttrainers.teamrating, users.userid, users.pogoname, users.points, users.usericon FROM users INNER JOIN teamdrafttrainers ON teamdrafttrainers.teamdrafttrainerid=users.userid WHERE teamdraftid='$teamdraftid' AND whichteam='1' ORDER BY id ASC";
		$team1result = mysqli_query($conn, $sqlteam1);
		$team1array = array();
		if (mysqli_num_rows($team1result) > 0) {
			while($row = mysqli_fetch_assoc($team1result)){
				$team1array[] = $row;
			}
		}else{
			$team1='0';
		}
		
		$team2='1';
		$sqlteam2 = "SELECT teamdrafttrainers.id, teamdrafttrainers.teamdraftid, teamdrafttrainers.teamdrafttrainerid, teamdrafttrainers.whichteam, teamdrafttrainers.party, teamdrafttrainers.teamrating, users.userid, users.pogoname, users.points, users.usericon FROM users INNER JOIN teamdrafttrainers ON teamdrafttrainers.teamdrafttrainerid=users.userid WHERE teamdraftid='$teamdraftid' AND whichteam='2' ORDER BY id ASC";
		$team2result = mysqli_query($conn, $sqlteam2);
		$team2array = array();
		if (mysqli_num_rows($team2result) > 0) {
			while($row = mysqli_fetch_assoc($team2result)){
				$team2array[] = $row;
			}
		}else{
			$team2='0';
		}
		
//Start of Team:Draft Battles return

$team1array;
$team2array;

$team1wins='0';
$team1losses='0';
$team2wins='0';
$team2losses='0';
$totalnumberteambattles='0';

//ALL of Team:draft battles
$teambattlelist = "Select * FROM battlerecords WHERE teamdraftid='$teamdraftid' ORDER BY id DESC;";
$teambattlelistresults = mysqli_query($conn, $teambattlelist);
$teambattlelistresultsarray = array();
if (mysqli_num_rows($teambattlelistresults) > 0) {
	while($row = mysqli_fetch_assoc($teambattlelistresults)){
		$teambattlelistresultsarray[] = $row;
	}
	$totalnumberteambattles=mysqli_num_rows($teambattlelistresults);
	
	//calculate team 1 wins/losses
	foreach($team1array as $teammate){
		$teammateid=$teammate['userid'];
		
		//WINs list out the past battles in order of complete
		$teamdraftbattlerecords = "SELECT * FROM battlerecords WHERE teamdraftid=$teamdraftid AND ((receiverid=$teammateid AND receiverwin='1') OR (senderid=$teammateid AND senderwin='1')) ORDER BY id DESC;";
		$teamdraftbattlerecordsresult = mysqli_query($conn, $teamdraftbattlerecords);
		if (mysqli_num_rows($teamdraftbattlerecordsresult) > 0) {
			$teammatewins=mysqli_num_rows($teamdraftbattlerecordsresult);
			$team1wins=$team1wins+$teammatewins;
		}//end of if

		//LOSSES list out the past battles in order of complete
		$teamdraftbattlerecords = "SELECT * FROM battlerecords WHERE teamdraftid=$teamdraftid AND ((receiverid=$teammateid AND senderwin='1') OR (senderid=$teammateid AND receiverwin='1')) ORDER BY id DESC;";
		$teamdraftbattlerecordsresult = mysqli_query($conn, $teamdraftbattlerecords);
		if (mysqli_num_rows($teamdraftbattlerecordsresult) > 0) {
			$teammatelosses=mysqli_num_rows($teamdraftbattlerecordsresult);
			$team1losses=$team1losses+$teammatelosses;
		}//end of if
	}//end of foreach
	
	//calculate team 1 wins/losses
	foreach($team2array as $teammate){
		$teammateid=$teammate['userid'];
		
		//WINs list out the past battles in order of complete
		$teamdraftbattlerecords = "SELECT * FROM battlerecords WHERE teamdraftid=$teamdraftid AND ((receiverid=$teammateid AND receiverwin='1') OR (senderid=$teammateid AND senderwin='1')) ORDER BY id DESC;";
		$teamdraftbattlerecordsresult = mysqli_query($conn, $teamdraftbattlerecords);
		if (mysqli_num_rows($teamdraftbattlerecordsresult) > 0) {
			$teammatewins=mysqli_num_rows($teamdraftbattlerecordsresult);
			$team2wins=$team2wins+$teammatewins;
		}//end of if

		//LOSSES list out the past battles in order of complete
		$teamdraftbattlerecords = "SELECT * FROM battlerecords WHERE teamdraftid=$teamdraftid AND ((receiverid=$teammateid AND senderwin='1') OR (senderid=$teammateid AND receiverwin='1')) ORDER BY id DESC;";
		$teamdraftbattlerecordsresult = mysqli_query($conn, $teamdraftbattlerecords);
		if (mysqli_num_rows($teamdraftbattlerecordsresult) > 0) {
			$teammatelosses=mysqli_num_rows($teamdraftbattlerecordsresult);
			$team2losses=$team2losses+$teammatelosses;
		}//end of if
	}//end of foreach

}//end of if


echo '
<div class="container-fluid">
		<div class="row">
			<div class="col-xs-6 text-center">
				<strong>Team '.$teamdraftteam1name.'</strong>
				<br>
				Win/Loss: '.$team1wins.'/'.$team1losses.'
			</div>
			<div class="col-xs-6 text-center">
				<strong>Team '.$teamdraftteam2name.'</strong>
				<br>
				Win/Loss: '.$team2wins.'/'.$team2losses.'
			</div>
	';

//show past battles
			echo '<br><br><br>
			<button type="button" class="btn btn-block btn-basic btn-shadow" style="background-color:#1a1a1a;" data-toggle="collapse" data-target="#teamdraftbattles'.$teamdraftid.'"><strong><span class="glyphicon glyphicon-certificate"> </span>Past Team:Draft Battles</strong></button>
				
				<div id="teamdraftbattles'.$teamdraftid.'" class="collapse">
					<div class="container-fluid"> <br> ';
					
					//
					//
					
					echo '
	<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>';
	  //all drafting users
	  $alldraftersarray=array_merge($team1array,$team2array);
	  foreach($alldraftersarray as $eachteamdrafter){
				$whichteam = $eachteamdrafter['whichteam'];
				echo '<th ';
				//add color to name headers on table for which team
				if($whichteam=='1'){
					echo 'style="background-color:rgba(204, 0, 0, .75);"';
				}else{
					echo 'style="background-color:rgba(179, 179, 179, .75);"';
				}
				echo '><strong>&nbsp;'.$eachteamdrafter['pogoname'].'</strong>';
				echo '</strong>';
				echo '</th>';
			}
	  
	  echo '
      </tr>
    </thead>
    <tbody>
      <tr>';
	  //user's battle history
	  foreach($alldraftersarray as $eachteamdrafter){
		 $eachteamdraftmemberid= $eachteamdrafter['userid'];
		 
		echo '<td style="line-height:16px;">';
		
			//list out the past battles in order of complete
					$teamdraftbattlerecords = "SELECT * FROM battlerecords WHERE teamdraftid=$teamdraftid AND (receiverid=$eachteamdraftmemberid OR senderid=$eachteamdraftmemberid) ORDER BY id DESC;";
					$teamdraftbattlerecordsresult = mysqli_query($conn, $teamdraftbattlerecords);
					$teamdraftbattlerecordsarray = array();
					if (mysqli_num_rows($teamdraftbattlerecordsresult) > 0) {
						while($row = mysqli_fetch_assoc($teamdraftbattlerecordsresult)){
							$teamdraftbattlerecordsarray[] = $row;
						}
						foreach($teamdraftbattlerecordsarray as $eachteamdraftbattle){
							$battlerecordid=$eachteamdraftbattle['id'];
							$battlerecordyear=$eachteamdraftbattle['year'];
							$battlerecordmonth=$eachteamdraftbattle['month'];
							$battlerecordday=$eachteamdraftbattle['day'];
							
							$senderid=$eachteamdraftbattle['senderid'];
							$senderwin=$eachteamdraftbattle['senderwin'];
							
							$receiverid=$eachteamdraftbattle['receiverid'];
							$receiverwin=$eachteamdraftbattle['receiverwin'];
							
							//sender info
							$senderinfo = "SELECT * FROM users WHERE userid=$senderid LIMIT 1;";
							$senderresults = mysqli_query($conn, $senderinfo);
							$senderarray = array();
							if (mysqli_num_rows($senderresults) > 0) {
								while($row = mysqli_fetch_assoc($senderresults)){
									$senderarray[] = $row;
								}
							}
							//receiver info
							$receiverinfo = "SELECT * FROM users WHERE userid=$receiverid LIMIT 1;";
							$receiverresults = mysqli_query($conn, $receiverinfo);
							$receiverarray = array();
							if (mysqli_num_rows($receiverresults) > 0) {
								while($row = mysqli_fetch_assoc($receiverresults)){
									$receiverarray[] = $row;
								}
							}
							
							//display battle records
							if($senderid==$eachteamdraftmemberid AND $senderwin=='1'){
								
								echo '<strong><span style="color:green;">'.$receiverarray[0]['pogoname'].' </span></strong><br><small><i>Battle ID:'.$battlerecordid.'</i></small><br>';
								
							}elseif($senderid==$eachteamdraftmemberid AND $receiverwin=='1'){
								
								echo '<strong><span style="color:red;">'.$receiverarray[0]['pogoname'].' </span></strong><br><small><i>Battle ID:'.$battlerecordid.'</i></small><br>';
								
							}elseif($receiverid==$eachteamdraftmemberid AND $receiverwin=='1'){
								
								echo '<strong><span style="color:green;">'.$senderarray[0]['pogoname'].' </span></strong><br><small><i>Battle ID:'.$battlerecordid.'</i></small><br>';
								
							}elseif($receiverid==$eachteamdraftmemberid AND $senderwin=='1'){
								
								echo '<strong><span style="color:red;">'.$senderarray[0]['pogoname'].' </span></strong><br><small><i>Battle ID:'.$battlerecordid.'</i></small><br>';
								
							}else{
								echo '[Error]';
							}
							
						}//end of FOREACH for GO:DRAFT batle records
	  }else{
		  echo '<i>None</i>';
	  }
	echo '</td>';
	  }
	  
	  echo '
      </tr>
    </tbody>
  </table>
  </div>
  <small><strong>*<span style="color:green;">Win</span>/<span style="color:red;">Loss</span></strong></small>';
echo'
  </div>
 </div>
 </div>';//end of row well for battle display area/results	
					//
					//
echo'</div>';
//END OF TEAM:DRAFT Battles
		
		echo '
		</div>
		</div>
		
		</div>';

		
//DISPLAYING TEAMS
echo '
<div class="container-fluid" style="line-height: 17px;">
	<div class="row">
		<div class="col-xs-6 text-center">
			<div class="row">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<strong>Team<br> '.$teamdraftteam1name.':</strong>
					</div>
					<div class="panel-body">';
					$team1avgrating=0;
					if($team1!='0'){
					foreach($team1array as $teammember){
						echo '<strong>'.$teammember['pogoname'].'</strong><br>';
						$team1avgrating=$team1avgrating+$teammember['teamrating'];
					}
						$team1avgrating=round(($team1avgrating/(count($team1array))),0);
						//echo '<br><button class="btn btn-link btn-sm" data-toggle="collapse" data-target="#team1ratinginfo" style="color:white;"><span class="glyphicon glyphicon-info-sign"></span> <u>Team Rating</u>:<br>+'.$team1avgrating.'</button>
						//<div id="team1ratinginfo" class="collapse"><small>Each user has a Team:Draft Rating that is calculated by taking the win rate of the user for the past 20 Team:Draft battles and multiplying that by 500 and taking their past win rate for their past 5 completed Team:Drafts and multiplying that by 500. These 2 values are added together to give a user rating. The ratings from each member are averaged to give a total team rating to show relative skill levels between teams in a Team:Draft. These are calculated when a user joins the Team:Draft. (Default is +500)</small></div>';
					
				}else{
					echo 'No players.';
				}
				echo '
					</div>
				</div>
			</div>
		</div>
			
		<div class="col-xs-6 text-center">
			<div class="row">
				<div class="panel panel-warning">
					<div class="panel-heading"><strong>Team<br> '.$teamdraftteam2name.':</strong></div>
					<div class="panel-body">';
					$team2avgrating=0;
						if($team2!='0'){
							foreach($team2array as $teammember){
							echo '<strong>'.$teammember['pogoname'].'</strong><br>';
						$team2avgrating=$team2avgrating+$teammember['teamrating'];
					}
						$team2avgrating=round(($team2avgrating/(count($team2array))),0);
						//echo '<br><button class="btn btn-link btn-sm" data-toggle="collapse" data-target="#team2ratinginfo" style="color:white;"><span class="glyphicon glyphicon-info-sign"></span> <u>Team Rating</u>:<br>+'.$team2avgrating.'</button>
						//<div id="team2ratinginfo" class="collapse"><small>Each user has a Team:Draft Rating that is calculated by taking the win rate of the user for the past 20 Team:Draft battles and multiplying that by 500 and taking their past win rate for their past 5 completed Team:Drafts and multiplying that by 500. These 2 values are added together to give a user rating. The ratings from each member are averaged to give a total team rating to show relative skill levels between teams in a Team:Draft. These are calculated when a user joins the Team:Draft. (Default is +500)</small></div>';
						}else{
							echo 'No players.';
						}
	echo '			</div>
				</div>
			</div>
		</div>
	</div>
</div>
';
		$teamdraftpickcount = $teamdraftarray[0]['pickcount'];
		$teamdraftbancount = $teamdraftarray[0]['bancount'];
		$teamdraftnumberofteam1drafters = $teamdraftarray[0]['numberofteam1drafters'];
		$teamdraftnumberofteam2drafters = $teamdraftarray[0]['numberofteam2drafters'];
		$teamdraftnumberofpickseachteam = $teamdraftarray[0]['numberofpickseachteam'];
		$teamdraftnumberofbanseachteam = $teamdraftarray[0]['numberofbanseachteam'];

//DISPLAYING BANS		
echo '
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-6 text-center">
			<div class="row">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<strong>Bans ('.$teamdraftnumberofbanseachteam.'):</strong>
					</div>
					<div class="panel-body">';
					if($teamdraftnumberofbanseachteam>0){
						
						//Team 1 Bans
						$sqlteam1bans = "SELECT * FROM teambans WHERE teamdraftid='$eachteamdraftid' AND whichteam='1' ORDER BY id ASC;";
						$team1bansresult = mysqli_query($conn, $sqlteam1bans);
						$teamd1bansarray = array();
						if (mysqli_num_rows($team1bansresult) > 0) {
							while($row = mysqli_fetch_assoc($team1bansresult)){
								$teamd1bansarray[] = $row;
							}
							
							foreach($teamd1bansarray as $team1ban){
								if($team1ban['banmon']!='0'){
									$monid=$team1ban['banmon'];
									//look up the pokemon name to display instead of their number
									$getpokemonname = "Select * FROM monlist WHERE monnumber='$monid';";
									$monnamelistresults = mysqli_query($conn, $getpokemonname);
									$monname = array();
									if (mysqli_num_rows($monnamelistresults) > 0) {
										while($row = mysqli_fetch_assoc($monnamelistresults)){
											$monname[] = $row;
										}
										echo '
									<div class="text-left" style="float:left;line-height:8px;">
									<img src="pokemonicons/pokemon_icon_'.$monname[0]['monnumber'].'_00.png" alt="Icon" width="43px" height="43px"><br>
									<div style="width:43px;height:25px;overflow:hidden;font-size:9px;"><small>#'.$team1ban['bannumber'].'</small><br>'.$monname[0]['monname'].'</div>
									</div>';
									}
							
								}else{
									echo '
									<div style="float:left;">
									<img src="pokemonicons/banegg.png" alt="Icon" width="43px" height="43px"><br>
									<div style="width:43px;height:25px;overflow:hidden;font-size:9px;">Ban #'.$team1ban['bannumber'].'</div>
									</div>';
								}
							}
						}
					}else{
						echo '<strong>No bans.</strong>';
					}
				echo '
					</div>
				</div>
			</div>
		</div>
			
		<div class="col-xs-6 text-center">
			<div class="row">
				<div class="panel panel-warning">
					<div class="panel-heading"><strong>Bans ('.$teamdraftnumberofbanseachteam.'):</strong></div>
					<div class="panel-body">';
						if($teamdraftnumberofbanseachteam>0){
						//Team 2 Bans
						$sqlteam2bans = "SELECT * FROM teambans WHERE teamdraftid='$eachteamdraftid' AND whichteam='2' ORDER BY id ASC;";
						$team2bansresult = mysqli_query($conn, $sqlteam2bans);
						$teamd2bansarray = array();
						if (mysqli_num_rows($team2bansresult) > 0) {
							while($row = mysqli_fetch_assoc($team2bansresult)){
								$teamd2bansarray[] = $row;
							}
							
							foreach($teamd2bansarray as $team2ban){
								if($team2ban['banmon']!='0'){
									$monid=$team2ban['banmon'];
									//look up the pokemon name to display instead of their number
									$getpokemonname = "Select * FROM monlist WHERE monnumber='$monid';";
									$monnamelistresults = mysqli_query($conn, $getpokemonname);
									$monname = array();
									if (mysqli_num_rows($monnamelistresults) > 0) {
										while($row = mysqli_fetch_assoc($monnamelistresults)){
											$monname[] = $row;
										}
										echo '
									<div class="text-left" style="float:left;line-height:8px;">
									<img src="pokemonicons/pokemon_icon_'.$monname[0]['monnumber'].'_00.png" alt="Icon" width="43px" height="43px"><br>
									<div style="width:43px;height:25px;overflow:hidden;font-size:9px;"><small>#'.$team2ban['bannumber'].'</small><br>'.$monname[0]['monname'].'</div>
									</div>';
									}
							
								}else{
									echo '
									<div style="float:left;">
									<img src="pokemonicons/banegg.png" alt="Icon" width="43px" height="43px"><br>
									<div style="width:43px;height:25px;overflow:hidden;font-size:9px;">Ban #'.$team2ban['bannumber'].'</div>
									</div>';
								}
							}
						}
					}else{
						echo '<strong>No bans.</strong>';
					}
	echo '			</div>
				</div>
			</div>
		</div>
	</div>
</div>
';

//DISPLAYING PICKS		
echo '
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-6 text-center">
			<div class="row">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<strong>Picks ('.$teamdraftnumberofpickseachteam.'):</strong>
					</div>
					<div class="panel-body">';
					if($teamdraftnumberofpickseachteam>0){
						//Team 1 picks
						$sqlteam1picks = "SELECT * FROM teampicks WHERE teamdraftid='$eachteamdraftid' AND whichteam='1' ORDER BY id ASC;";
						$team1picksresult = mysqli_query($conn, $sqlteam1picks);
						$teamd1picksarray = array();
						if (mysqli_num_rows($team1picksresult) > 0) {
							while($row = mysqli_fetch_assoc($team1picksresult)){
								$teamd1picksarray[] = $row;
							}
							
							foreach($teamd1picksarray as $team1pick){
								//$team1count=1;
								if($team1pick['pickmon']!='0'){
									$monid=$team1pick['pickmon'];
									//look up the pokemon name to display instead of their number
									$getpokemonname = "Select * FROM monlist WHERE monnumber='$monid';";
									$monnamelistresults = mysqli_query($conn, $getpokemonname);
									$monname = array();
									if (mysqli_num_rows($monnamelistresults) > 0) {
										while($row = mysqli_fetch_assoc($monnamelistresults)){
											$monname[] = $row;
										}
										echo '
									<div class="text-left" style="float:left;line-height:8px;">
									<img src="pokemonicons/pokemon_icon_'.$monname[0]['monnumber'].'_00.png" alt="Icon" width="43px" height="43px"><br>
									<div style="width:43px;height:25px;overflow:hidden;font-size:9px;"><small>#'.$team1pick['picknumber'].'</small><br>'.$monname[0]['monname'].'</div>
									</div>';
									}
							
								}else{
									echo '
									<div style="float:left;">
									<img src="pokemonicons/pokemon_icon_0_00.png" alt="Icon" width="43px" height="43px"><br>
									<div style="width:43px;height:25px;overflow:hidden;font-size:9px;">Pick #'.$team1pick['picknumber'].'</div>
									</div>';
								}
								
							}
						}
					}else{
						echo 'No picks.';
					}
				echo '
					</div>
				</div>
			</div>
		</div>
			
		<div class="col-xs-6 text-center">
			<div class="row">
				<div class="panel panel-warning">
					<div class="panel-heading"><strong>Picks ('.$teamdraftnumberofpickseachteam.'):</strong></div>
					<div class="panel-body">';
						if($teamdraftnumberofpickseachteam>0){
						
						//Team 2 picks
						$sqlteam2picks = "SELECT * FROM teampicks WHERE teamdraftid='$eachteamdraftid' AND whichteam='2' ORDER BY id ASC;";
						$team2picksresult = mysqli_query($conn, $sqlteam2picks);
						$teamd2picksarray = array();
						if (mysqli_num_rows($team2picksresult) > 0) {
							while($row = mysqli_fetch_assoc($team2picksresult)){
								$teamd2picksarray[] = $row;
							}
							
							foreach($teamd2picksarray as $team2pick){
								
								if($team2pick['pickmon']!='0'){
									$monid=$team2pick['pickmon'];
									//look up the pokemon name to display instead of their number
									$getpokemonname = "Select * FROM monlist WHERE monnumber='$monid';";
									$monnamelistresults = mysqli_query($conn, $getpokemonname);
									$monname = array();
									if (mysqli_num_rows($monnamelistresults) > 0) {
										while($row = mysqli_fetch_assoc($monnamelistresults)){
											$monname[] = $row;
										}
										echo '
									<div class="text-left" style="float:left;line-height:8px;">
									<img src="pokemonicons/pokemon_icon_'.$monname[0]['monnumber'].'_00.png" alt="Icon" width="43px" height="43px"><br>
									<div style="width:43px;height:25px;overflow:hidden;font-size:9px;"><small>#'.$team2pick['picknumber'].'</small><br>'.$monname[0]['monname'].'</div>
									</div>';
									}
							
								}else{
									echo '
									<div style="float:left;">
									<img src="pokemonicons/pokemon_icon_0_00.png" alt="Icon" width="43px" height="43px"><br>
									<div style="width:43px;height:25px;overflow:hidden;font-size:9px;">Pick #'.$team2pick['picknumber'].'</div>
									</div>';
								}
								
							}
						}
						
						
					}else{
						echo 'No picks.';
					}
	echo '			</div>
				</div>
			</div>
		</div>';
		
//DISPLAYING Parties
		$teamdraftdisplayparty = $teamdraftarray[0]['displayparty'];

echo '
<div class="container-fluid">
	<div class="row">
		<div class="col-xs-6 text-left">
			<div class="row">
				<div class="panel panel-danger">
					<div class="panel-heading text-center">
						<strong>Battle Parties:</strong>
					</div>
					<div class="panel-body">';
					if($teamdraftdisplayparty=='1'){

						foreach($team1array as $eachteammember){
							echo '<div class="container-fluid" style="padding:0px 0px 0px 0px;"><strong><u>'.$eachteammember['pogoname'].'</u></strong><br>';
	//show option to update battle parties for other team members.
		if($eachteammember['party']=='0'){
			echo '<i>None</i>';
		}else{
			//separates pick list by hyphen
			$picklistarray1 = explode('-', $eachteammember['party']);
			$picklistnumber1=1;
			foreach($picklistarray1 as $eachpick1){
				//look up the pokemon name to display instead of their number
					$getpokemonname = "Select * FROM monlist WHERE monnumber='$eachpick1';";
					$mon1listresults = mysqli_query($conn, $getpokemonname);
					$monlist1 = array();
					if (mysqli_num_rows($mon1listresults) > 0) {
						while($row = mysqli_fetch_assoc($mon1listresults)){
							$monlist1[] = $row;
						}						
						echo '
						<div>
							<div class="text-left" style="line-height:8px;float:left;">
							<img src="pokemonicons/pokemon_icon_'.$monlist1[0]['monnumber'].'_00.png" alt="Icon" width="43px" height="43px"><br>
							<div style="width:43px;height:25px;overflow:hidden;font-size:9px;">'.$monlist1[0]['monname'].'</div>
							</div>
						</div>';
					}
			}
		}
						echo '</div>';
						}
						
					}else{
							echo '<div class="text-center">Hidden.</div>';
					}
				echo '
					</div>
				</div>
			</div>
		</div>
			
		<div class="col-xs-6 text-left">
			<div class="row">
				<div class="panel panel-warning">
					<div class="panel-heading text-center"><strong>Battle Parties:</strong></div>
					<div class="panel-body">';
						if($teamdraftdisplayparty=='1'){
							
						foreach($team2array as $eachteammember){
							echo '<div class="container-fluid" style="padding:0px 0px 0px 0px;"><strong><u>'.$eachteammember['pogoname'].'</u></strong><br>';
	//show option to update battle parties for other team members.
		if($eachteammember['party']=='0'){
			echo '<i>None</i>';
		}else{
			//separates pick list by hyphen
			$picklistarray1 = explode('-', $eachteammember['party']);
			$picklistnumber1=1;
			foreach($picklistarray1 as $eachpick1){
				//look up the pokemon name to display instead of their number
					$getpokemonname = "Select * FROM monlist WHERE monnumber='$eachpick1';";
					$mon1listresults = mysqli_query($conn, $getpokemonname);
					$monlist1 = array();
					if (mysqli_num_rows($mon1listresults) > 0) {
						while($row = mysqli_fetch_assoc($mon1listresults)){
							$monlist1[] = $row;
						}						
						echo '
						<div>
							<div class="text-left" style="line-height:8px;float:left;">
							<img src="pokemonicons/pokemon_icon_'.$monlist1[0]['monnumber'].'_00.png" alt="Icon" width="43px" height="43px"><br>
							<div style="width:43px;height:25px;overflow:hidden;font-size:9px;">'.$monlist1[0]['monname'].'</div>
							</div>
						</div>';
					}
			}
		}
						echo '</div>';
						}
						}else{
							echo '<div class="text-center">Hidden.</div>';
						}
	echo '			</div>
				</div>
			</div>
		</div>
	</div>
</div>
';
		
		
echo '
	</div>
</div>

';
		}else{
			echo '<div class="well text-center"><h4><br><br><br>No results found.<br><br><br><br></h4></div>';
		}
 ?>
 <!--End of php -->

			<br>
	</div>
<div class="col-sm-1"></div>
</div>
</div>
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
          <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>;</button>
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
          <button type="button" class="close" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>;</button>
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
	  <small>PoGoPoints is not affiliated with Niantic Inc., The Pokemon Company, or Nintendo.
	  Pokémon And All Respective Names are Trademark & © of Nintendo 1996-2019
		Pokémon GO is Trademark & © of Niantic, Inc.
	  </small>
	  </small>
	  <br>

  </div>
</footer>
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