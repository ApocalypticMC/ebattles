<?php
/**
 * matchdelete.php
 *
 * This page is for users to edit their account information
 * such as their password, email address, etc. Their
 * usernames can not be edited. When changing their
 * password, they must first confirm their current password.
 *
 */
include("include/main.php");

?>
<div id="main">
<div class="news">

<?php
   $event_id = $_GET['eventid'];
        
   if (!isset($_POST['deletematch']))
   {
        echo "<br />You are not authorized to delete this match.<br />";
        echo "<br />Back to [<a href=\"eventinfo.php?eventid=$event_id\">Event</a>]<br />";
   }
   else
   {
        $match_id = $_POST['matchid'];
       	// Update Players with scores
        $q = "SELECT ".TBL_MATCHS.".*, "
                     .TBL_SCORES.".*, "
                       .TBL_PLAYERS.".*, "
                       .TBL_USERS.".*"
              ." FROM ".TBL_MATCHS.", "
                       .TBL_SCORES.", "
                       .TBL_PLAYERS.", "
                       .TBL_USERS
              ." WHERE (".TBL_MATCHS.".MatchID = '$match_id')"
                ." AND (".TBL_SCORES.".MatchID = ".TBL_MATCHS.".MatchID)"
                ." AND (".TBL_PLAYERS.".PlayerID = ".TBL_SCORES.".Player)"
                ." AND (".TBL_USERS.".username = ".TBL_PLAYERS.".Name)";
        $result = $sql->db_Query($q);
        $num_rows = mysql_numrows($result);

        $max_score = 0;
        for($i=0;$i<$num_rows;$i++)
        {
            $pscore = mysql_result($result,$i, TBL_SCORES.".Player_Score");
            if ($pscore>$max_score)
               $max_score = $pscore;
        }

        for($i=0;$i<$num_rows;$i++)
        {
            $pdeltaELO = mysql_result($result,$i, TBL_SCORES.".Player_deltaELO");
            $pscore = mysql_result($result,$i, TBL_SCORES.".Player_Score");
            $pID= mysql_result($result,$i, TBL_PLAYERS.".PlayerID");
            $pName= mysql_result($result,$i, TBL_USERS.".username");
            $pELO= mysql_result($result,$i, TBL_PLAYERS.".ELORanking");
            $pGamesPlayed= mysql_result($result,$i, TBL_PLAYERS.".GamesPlayed");
            $pWins= mysql_result($result,$i, TBL_PLAYERS.".Win");
            $pLosses= mysql_result($result,$i, TBL_PLAYERS.".Loss");
            $scoreid = mysql_result($result,$i, TBL_SCORES.".ScoreID");
            
            $pELO -= $pdeltaELO;
            $pLosses = $pLosses - $max_score + $pscore;
            $pWins = $pWins - $pscore;
            $pGamesPlayed -= 1;
            
            echo "Player $pName, new ELO:$pELO<br />"; 

            $q = "UPDATE ".TBL_PLAYERS." SET ELORanking = $pELO WHERE (Name = '$pName') AND (Event = '$event_id')";
            $result2 = $sql->db_Query($q);
            $q = "UPDATE ".TBL_PLAYERS." SET GamesPlayed = $pGamesPlayed WHERE (Name = '$pName') AND (Event = '$event_id')";
            $result2 = $sql->db_Query($q);
            $q = "UPDATE ".TBL_PLAYERS." SET Loss = $pLosses WHERE (Name = '$pName') AND (Event = '$event_id')";
            $result2 = $sql->db_Query($q);
            $q = "UPDATE ".TBL_PLAYERS." SET Win = $pWins WHERE (Name = '$pName') AND (Event = '$event_id')";
            $result2 = $sql->db_Query($q);
            
            // fmarc- Can not change "streak" information here :(
            
            // Delete Score
            $q = "DELETE FROM ".TBL_SCORES." WHERE (ScoreID = '$scoreid')";
            $result2 = $sql->db_Query($q);
            
        } 

        $q = "UPDATE ".TBL_EVENTS." SET IsChanged = 1 WHERE (EventID = '$event_id')";
        $result = $sql->db_Query($q);
        
        echo "<br />Match deleted<br />";
        echo "<br />Back to [<a href=\"eventinfo.php?eventid=$event_id\">Event</a>]<br />";
   }

?>
</div>
</div>
<?php
include("include/footer.php");
?>