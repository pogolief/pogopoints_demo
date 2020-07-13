
---------------------REMOVED FROM INDEX----------------------------------
<!--Add to other pages of website for ServiceWorkers-->

<script>
	//this should work on chrome, samsung, and safari,,, but we will check to see availibility
	if('serviceWorker' in navigator){
	navigator.serviceWorker.register('/serviceworker.js')
	.then(function(){
		console.log('SW registered');
	});
	}
</script>

<!--combo league explanation-->
						<button data-toggle="collapse" data-target="#comboleague" class="btn btn-lg blacktext btn-link">
						<span class="glyphicon glyphicon-chevron-right"></span>
						<strong>Combo League</strong></button>
						<br>
						<div class="collapse" id="comboleague">
						<small>
						This league adds variety to PvP with players needing to pick from a certain set of types (dual types are acceptable), such as choosing Pokemon with at least one type, such as Rock, Ground, Fighting, or Steel for the "Rock-Ground-Fighting-Steel" Combo League, and players must NOT have any repeat Pokemon in their battle party for Combo League Matches. 
						</small>
						</div>

<!-- Gym Leader Modal below -->
<script>
//use this link to display download app link in the future
//https://developers.google.com/web/fundamentals/app-install-banners/#trigger-m68

			function findgymleaders() {
						var xhttp = new XMLHttpRequest();
						xhttp.onreadystatechange = function() {
							if (this.readyState == 4 && this.status == 200) {
								document.getElementById("findgymleadersarea").innerHTML = this.responseText;
							}
						};
						xhttp.open("POST", "i/gymleaders.php", true);
						xhttp.send();
					};
					
						//					var xhttp = new XMLHttpRequest();
	//					xhttp.onreadystatechange = function() {
	//						if (this.readyState == 4 && this.status == 200) {
	//							document.getElementById("numberofusersonline").innerHTML = this.responseText;
	//						}
	//					};
	//					xhttp.open("POST", "i/numberofusersonline.php", true);
	//					xhttp.send();
	
					<?php
						if(isset($_SESSION['userid'])){
							//timer to keep group chat updated
							//echo 'updategroupchat=setTimeout(displaygroupchat, 2000);';
						}
						?>
				
		</script>
  <div class="modal fade" id="gymleadermodal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h3 class="modal-title text-center"><strong>Find Gym Leaders</strong></h3>
        </div>
        <div class="modal-body">
   
<div class="container-fluid" id="findgymleadersarea"></div>
<hr/>
       </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal" onclick="onlineoffline();">Close</button>
        </div>
      </div>
    </div>
  </div>
<!--end of Gym Leader modal-->

-----------------------REMOVED FROM FRIENDSLIST.PHP------------------------------

<?PHP
						//friendshipsort sort friends here
						$eachfriendsort=$acceptedfriends['sort'];
						
						echo '
	<div class="text-center">
	<form class="form-inline" action="i/friendsortcode.php" method="POST">
	<div class="form-group input-group-sm">
		<label for="friendsortupdate'.$addedfriendarray[0]['userid'].'">Sort Friend:</label>
		<select class="form-control" id="friendsortupdate'.$addedfriendarray[0]['userid'].'" name="friendsortupdate" required>';
		echo '<option value="'.$eachfriendsort.'">';
			if($eachfriendsort!=Null){
				echo $eachfriendsort;
			}else{
				echo '-';
			}
		echo '</option>';
		$number=1;
		while($number<=200){
			echo '<option value="'.$number.'">'.$number.'</option>';
			$number=($number+1);
		}
		$number=1;
echo '
		</select>
	</div>
	<input type="text" name="friendsortchange" maxlength="30" value="'.$addedfriendarray[0]['userid'].'" class="hidden"/>
	
	 	<button onclick="onlineoffline();" class="btn btn-success" type="submit" name="submit" onclick="fadein();">Update</button>
 </form>
	</div><br>
';

echo '
						<!--Display update Friendship here-->
					<button data-toggle="collapse" data-target="#friendshiplevel'.$addedfriendarray[0]['userid'].'" class="btn btn-default btn-block"><span class="glyphicon glyphicon-chevron-right"></span>';
						if($currentfriendshiplevel=='Enter'){
							echo 'Friendship Level';
						}elseif($currentfriendshiplevel=='Good'){
							echo '<span class="bluehx">'.$currentfriendshiplevel.' Friends</span>';
							
						}elseif($currentfriendshiplevel=='Great'){
							echo '<span class="greenhx">'.$currentfriendshiplevel.' Friends</span>';
							
						}elseif($currentfriendshiplevel=='Ultra'){
							echo '<span class="orangehx">'.$currentfriendshiplevel.' Friends</span>';
							
						}elseif($currentfriendshiplevel=='Best'){
							echo '<span class="redhx">'.$currentfriendshiplevel.' Friends</span>';
							
						}else{
							echo $currentfriendshiplevel.' Friends';
						}
					
					echo  '</button>';
					
					
					
					echo '<div id="friendshiplevel'.$addedfriendarray[0]['userid'].'" class="collapse">
						<form action="i/friendshiplevelupdate.php" method="POST">
							<div class="form-group">
								<div class="col-xs-12">
									<select class="form-control" name="friendshiplevelupdate" required>
										<option value="Good">Good</option>
										<option value="Great">Great</option>
										<option value="Ultra">Ultra</option>
										<option value="Best">Best</option>
									</select>
								</div>
						</div>
						<input type="text" name="friendlevelchange" maxlength="30" value="'.$addedfriendarray[0]['userid'].'" class="hidden"/><br><br>
					<div class="container-fluid text-center">
						<button class= "btn btn-default" type="submit" name="submit" onclick="fadein();">Update</button>
						</div>
					</form>
					</div>';
?>

---------------------------------------REMOVED FROM BATTLEOPTIONS.PHP-------------------------------

	<optgroup label="&diams;Combo Leagues (No Duplicates)&diams;">
<option value="1000">Rock-Ground-Fighting-Steel Great League</option>
<option value="1001">Dark-Fairy-Poison-Ghost Great League</option>
<option value="1002">Electric-Ice-Flying-Ground Great League</option>
<option value="1003">Fire-Ice-Dragon-Steel Great League</option>

-----------------------------------REMOVED FROM FRIENDSLIST----------------------------
<?php

			$sqlacceptedfriends = "Select * FROM friendslist WHERE userid=$loggedinuser AND friendaccepted=1 ORDER BY id DESC";
				$accpetedfriendsresult = mysqli_query($conn, $sqlacceptedfriends);
				$acceptedfriendsarray = array();
				if (mysqli_num_rows($accpetedfriendsresult) > 0) {
					while($row = mysqli_fetch_assoc($accpetedfriendsresult)){
						$acceptedfriendsarray[] = $row;
					}
				}else{
					echo 'Start adding friends using the search box above!';
				}
?>

----------------------------BATTLEDISPLAYLAST.PHP---------------------------------------
<?php

						echo '<form name="form1" action="i/battleinitiate.php" method="POST">
							
							<div class="row">
							<div class="col-xs-6">
									<select class="form-control" name="selectedbattleleague" required>';
									include 'battleoptions.php';
						echo '
									</select>
							</div>
							
							<div class="col-xs-6">
							<input type="text" name="eachfriendid" maxlength="30" value="'.$senderid.'" class="hidden"/>
							<button class="btn btn-success btn-block" name="submit" type="submit" onclick="fadeinout();">Rematch!</button>
							</div>
							<small><small><input type="checkbox" value="1" name="practicebattle" checked><strong>Unranked</strong> &plusmn0 points</small></small>
							</div>
						</form>';


?>