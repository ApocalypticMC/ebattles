<?php
/**
* LadderInfo_process.php
*
*/
require_once(e_PLUGIN.'ebattles/include/ladder.php');

$ladder_id = $_GET['LadderID'];
$ladder = new Ladder($ladder_id);

if(isset($_POST['quitladder'])){
    $pid = $_POST['player'];

    // Player can quit an ladder if he has not played yet
    $q = "SELECT ".TBL_PLAYERS.".*"
    ." FROM ".TBL_PLAYERS.", "
    .TBL_SCORES
    ." WHERE (".TBL_PLAYERS.".PlayerID = '$pid')"
    ." AND (".TBL_SCORES.".Player = ".TBL_PLAYERS.".PlayerID)";
    $result = $sql->db_Query($q);
    $nbrscores = mysql_numrows($result);
    if ($nbrscores == 0)
    {
        deletePlayer($pid);
        $q = "UPDATE ".TBL_LADDERS." SET IsChanged = 1 WHERE (LadderID = '$ladder_id')";
        $result = $sql->db_Query($q);
    }
    header("Location: ladderinfo.php?LadderID=$ladder_id");
}
if(isset($_POST['joinladder'])){
    if ($_POST['joinLadderPassword'] == $ladder->getField('Password'))
    {
        $ladder->ladderAddPlayer(USERID, 0, FALSE);
    }
    header("Location: ladderinfo.php?LadderID=$ladder_id");
}
if(isset($_POST['teamjoinladder'])){
    if ($_POST['joinLadderPassword'] == $ladder->getField('Password'))
    {
        $div_id = $_POST['division'];
        $ladder->ladderAddDivision($div_id, FALSE);
    }
    header("Location: ladderinfo.php?LadderID=$ladder_id");
}
if(isset($_POST['jointeamladder'])){
    $team_id = $_POST['team'];
    $ladder->ladderAddPlayer (USERID, $team_id, FALSE);
    header("Location: ladderinfo.php?LadderID=$ladder_id");
}

?>