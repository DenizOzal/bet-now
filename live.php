<?php 
include('config.php');
session_start();
    $username = $_SESSION['login_user'];

    if( isset($_SESSION['bets'])){
        $bets_query = $_SESSION['bets'];
        $match_ids = [];
        foreach( $bets_query as $bet){
            $match_ids[] = $bet['match_id'];
        }
        $match_ids = implode("','", $match_ids);

        $leagues_sql = "SELECT DISTINCT league from matchs WHERE match_id IN ('".$match_ids."')";
        $leagues = mysqli_query($db, $leagues_sql);

        $sports_sql = "SELECT DISTINCT match_category from matchs WHERE match_id IN ('".$match_ids."')";
        $sports = mysqli_query($db, $sports_sql);

        $match_sql = "SELECT DISTINCT match_id, match_date, match_time, league from matchs WHERE match_id IN ('".$match_ids."')";
        $matchs = mysqli_query($db,$match_sql);

        $contains_sql = "SELECT DISTINCT team_id, match_id from contains WHERE match_id IN ('".$match_ids."')";
        $contains = mysqli_query($db,$contains_sql);
        $contains_arr = [];
        foreach( $contains as $contain){
            $contains_arr[] = $contain['team_id'];
        }
        $contains_arr = implode("','", $contains_arr);
        $team_sql = "SELECT match_id, team_name from team NATURAL JOIN contains WHERE contains.team_id = team.team_id";
        $teams_query = mysqli_query($db,$team_sql);
        $bets = $bets_query;
        $teams = [];
        while($row = mysqli_fetch_assoc($teams_query)) {
            $teams[] = $row;
        }
    }
    else{

        $sql = "SELECT bet_id, match_id, mbn, bet_date, bet_time, category, odd_type, odd_value from bet";
        $match_sql = "SELECT match_id, match_date, match_time, match_category, league from matchs";
        $contains_sql = "SELECT team_id, match_id from contains";
        $team_sql = "SELECT match_id, team_name from team NATURAL JOIN contains WHERE contains.team_id = team.team_id";
        $leagues_sql = "SELECT DISTINCT league from matchs";
        $leagues = mysqli_query($db, $leagues_sql);
        $sports_sql = "SELECT DISTINCT match_category from matchs";
        $sports = mysqli_query($db, $sports_sql);
        $matchs = mysqli_query($db,$match_sql);
        $bets_query = mysqli_query($db,$sql);
        $contains = mysqli_query($db,$contains_sql);
        $teams_query = mysqli_query($db,$team_sql);

        $bets = [];
        while($row = mysqli_fetch_assoc($bets_query)) {
            $bets[] = $row;
        }

        $teams = [];
        while($row = mysqli_fetch_assoc($teams_query)) {
            $teams[] = $row;
        }
    }

    $filter_mbn = [];
    $mbns_sql = "SELECT mbn FROM bet";
    $mbns = mysqli_query($db, $mbns_sql);
    foreach( $mbns as $mbn){
        $filter_mbn[] = $mbn['mbn'];
    }

    $filter_leagues_sql = "SELECT DISTINCT league from matchs";
    $filter_leagues = mysqli_query($db, $filter_leagues_sql);
    $filter_sports_sql = "SELECT DISTINCT match_category from matchs";
    $filter_sports = mysqli_query($db, $filter_sports_sql);
    $betslip = [];
    $odd_arr = [];
    $totalOdd = 1;
?>
<!doctype html>
<html lang="en">
  <head>
 
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
    <title>Login
    </title>
  </head>
  <body>
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>

    <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-warning">
            <a class="navbar-brand" href="live.php">
            <img src="assets/b-n.jpeg" width="30" class="d-inline-block align-top" alt="logo" class="img-fluid">
            <span style="font-family:Times New Roman; color:ForestGreen;">
            Bet-Now
            </span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="live.php" style="font-family:Times New Roman;">LIVE <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="football.php">Football</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="volleyball.php">Volleyball</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="lottery.php">Lottery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="horce-race.php">Horse Race</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="editors.php">Editors</a>
                    </li>
                </ul>

                <form class="form-inline my-2 my-lg-0" action="login.php" method="post">
                    <?php if ($_SESSION['login_flag']) { 
                        include("my-account.php");    
                    ?>
                    <?php } else { ?>
                        <input type="text" placeholder="Username" name="username" style="margin-right:2px;">
                        <input type="password" placeholder="Password" name="password" style="margin-right:2px;">
                        <button class="btn btn-primary btn-sm" type="submit" style="margin-right:2px;">Login</button >
                        <button class="btn btn-primary btn-sm" onclick="location.href='signup.php'" type="button">Signup</button>
                    <?php } ?>
                </form>
            </div>
        </nav>

      <!-- Filter -->
        <div class="container-fluid" style="width:100%">
            <div class="row">
                <div class="col-2" style="border-style:solid; border-color:aqua; margin:0; padding:0;">
                    <h5 style="text-align:center; color:white;">Filter</h5>
                    <form action="filter_bets.php" method='post'>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" style="width:100%;" id="dropdown-league-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        League
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdown-league-button">
                            <?php foreach($filter_leagues as $league){?>
                            <a class="dropdown-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="default-league-<?php echo $league['league']?>" name="default-league-<?php echo $league['league']?>">
                                    <label class="form-check-label" for="default-check-turkey">
                                        <?php echo $league['league'] ?>
                                    </label>
                                </div>
                            </a>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="dropdown" style="margin-top:2px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" style="width:100%;" id="dropdown-sports-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Sports
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdown-sports-button">
                        <?php foreach($filter_sports as $sport){?>
                            <a class="dropdown-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""id="default-sports-<?php echo $sport['match_category']?>" name="default-sports-<?php echo $sport['match_category']?>">
                                    <label class="form-check-label" for="default-check-football">
                                    <?php echo $sport['match_category'] ?>
                                    </label>
                                </div>
                            </a>
                            <?php } ?>

                        </div>
                    </div>

                    <div class="dropdown" style="margin-top:2px;">
                        <button class="btn btn-primary dropdown-toggle" type="button" style="width:100%;" id="dropdown-mbn-button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        MBN
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdown-mbn-button">
                            <?php  $filter_mbns = array_unique($filter_mbn)?>
                        <?php for($i = 0; $i < count($filter_mbns); $i++){?>
                            <a class="dropdown-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="default-mbn-<?php echo $filter_mbns[$i]?>"name="default-mbn-<?php echo $filter_mbns[$i]?>">
                                    <label class="form-check-label" for="default-check-mbn1">
                                    <?php echo $filter_mbns[$i] ?>

                                    </label>
                                </div>
                            </a>
                            <?php } ?>
                        </div>
                        <div class="form-outline datepicker w-100" style="margin-top:2px;">
                                <input
                                style="font-size:16px; text-align:center;"
                                type="date"
                                class="form-control form-control-lg"
                                id="date-filter" name="date-filter"
                                />
                        </div>
                        <input type="submit"style="width:100%;" class="btn btn-warning btn-lg active" value='Apply'> 
                    </div>
                    </form>

                </div>
                  <!-- Match Table -->
                <div class="col-8" style="border-style:solid;">
                    <table class="table-responsive table-bordered">
                        Today's Matches
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">MBN</th>
                            <th scope="col">Date</th>
                            <th scope="col">Time</th>
                            <th scope="col">League</th>
                            <th scope="col">Match</th>
                            <th scope="col">MR1</th>
                            <th scope="col">MRX</th>
                            <th scope="col">MR2</th>
                            <th scope="col">2.5 U</th>
                            <th scope="col">2.5 O</th>
                            <th scope="col">HR1</th>
                            <th scope="col">HRX</th>
                            <th scope="col">HR2</th>
                            <th scope="col">MG+</th>
                            <th scope="col">MG-</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach($matchs as $match){?>
                            <tr id="bet-table">
                                <th scope="row"><?php echo $match['match_id'] ?></th>
                                <td><?php echo $filter_mbn[0] ?></td>
                                <td><?php echo $match['match_date'] ?></td>
                                <td><?php echo $match['match_time'] ?></td>
                                <td><?php echo $match['league'] ?></td>
                                <td>
                                <?php foreach($teams as $team){?>
                                <?php if( $team['match_id'] == $match['match_id']){?>
                                    <?php echo $team['team_name'] ?> <br>
                                    <?php } ?>
                                <?php } ?>
                                </td>
                                <?php for( $i = ($match['match_id'] - 1) * 10; $i < 10 * $match['match_id'] ; $i++){?>
                                <td><button type="button" class="btn btn-secondary btn-sm" id="bet-button-<?php echo $bets[$i]['bet_id']?>"><?php echo $bets[$i]['odd_value'] ?></button></td>
                                <?php }?>
                            </tr>
                            <?php }  ?>
                        </tbody>
                    </table>
                </div>
            <!-- Betslip -->
                <div class="col-2" style="border-style:solid; border-color:aqua;">
                    <div class="row d-flex justify-content-center"  style="width:100%;">
                        <label for="my-coupon" style="position:relative; font-weigth:bold; color:white; font-family: Arial, Helvetica, sans-serif;">
                            My Coupon
                        </label>
                        </div>
                        <hr style="margin:0">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-center">
                            <button type="button" class="btn btn-danger" id='delete-all-button'>
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"  fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                </svg>
                                Delete 
                            </button>
                            </div>
                            <div class="col-6 d-flex justify-content-center" >
                            <button type="button" class="btn btn-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                            <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1H2z"/>
                            </svg>
                                Save 
                            </button>
                            </div>
                        </div>

                        <div class="row" id="row-betslip">
                            
                        </div>
                        <div class="row">
                            <div class="card" style="width:100%; background-color:yellow; margin:3px;" id='make-bet'>
                                    <div class="section">
                                        <p style="text-align:left;">
                                        <span style="float:right" id="odd-value"> 
                                        <?php echo $totalOdd ?>     
                                        </span>
                                        Total Odd: 
                                        </p>

                                        <hr style="margin:0">

                                        <div class="form-group row">
                                        <label for="deposit-amount-label" class="col-6 col-form-label">Deposit:</label>
                                        <div class="col-6">
                                            <input type="text" class="form-control" id="id-deposit-amount" placeholder="TL">
                                        </div>
                                        </div>

                                        <hr style="margin:0">

                                        <p style="text-align:left;">
                                        <span style="float:right"> 
                                        150 TL
                                        </span>
                                        Total Income: 
                                        </p>
                                    </div>
                                </div>
                                <a href="#" class="btn btn-success btn-lg active" role="button" aria-pressed="true" style="width:100%;  margin:3px;">Make Bet</a>
                            </div>
                        </div>
                </div>
        </div>
    </html>
    <?php 
            
    ?> 
    <style>
        .table-responsive{
            text-align:center;
        }
        body{
            background-color:#8fbc8f;
        }
        .col{
            overflow:auto;
        }
        #bet-table th,td { text-align: center; }
        .selected { background-color:green }
    </style>
   
   <?php 
        $card_betslip_id = 0;
        $button_delete_betslip_id = 0;
   ?>
   
<?php for($i = 0; $i < count($bets); $i++){?>
    <script>

        $(document).ready(function() {
                $('#bet-button-<?php echo $bets[$i]['bet_id'] ?>').click(function(e){
                e.preventDefault();
                <?php for($j = floor($i / 10) * 10; $j < 10 * (floor($i / 10) + 1); $j++){?>
                    $('#bet-button-<?php echo $bets[$j]['bet_id'] ?>').removeClass("selected");
                    $("#card-betslip-<?php echo $bets[$j]['bet_id'] ?>").remove();
                    <?php if($key = array_search( $j + 1, $betslip)){?>
                        <?php unset($betslip[$key])?>
                    <?php }?>
                <?php }?>

                <?php $team_compete = [] ?>
                <?php foreach($teams as $team){?>
                     <?php if( $team['match_id'] == floor($i / 10) + 1){?>
                            <?php $team_compete[] = $team['team_name'] ?>
                        <?php } ?>
                <?php } ?>
                $(this).addClass("selected");
                $("#row-betslip").append(`<div class="card" id="card-betslip-<?php echo $bets[$i]['bet_id'] ?>" style="width:100%; background-color:aqua; margin:3px;">
                                <div class="section" style="border-style:solid;">
                                    <p style="text-align:left; padding-bottom:7px; margin:0;">
                                    <span style="float:right"> 
                                                <button type="button" id="button-delete-betslip-<?php echo $bets[$i]['bet_id'] ?>" class="btn btn-warning btn-sm" >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"  fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                                                </svg>
                                                </button>
                                    </span>
                                        <?php echo $bets[$i]['bet_date']?>
                                    </p>
                                    <hr style="margin:0">
                                    <p style="text-align:left;">
                                    <span style="float:right">1</span>
                                        <?php echo $team_compete[0] ?>
                                    </p>
                                    <p style="text-align:left;">
                                    <span style="float:right">2</span>
                                    <?php echo $team_compete[1] ?>
                                    </p>
                                    <hr style="margin:0">
                                    <p style="text-align:left; margin:0;">
                                    <span style="float:right"> 
                                    <?php echo $bets[$i]['odd_value']?>

                                    </span>
                                    MBN: <?php  echo $bets[$i]['mbn']?>
                                    </p>
                                </div>
                            </div>`);
                $(function() {
                    $("#button-delete-betslip-<?php echo $bets[$i]['bet_id'] ?>").on("click",function(e){
                        $("#card-betslip-<?php echo $bets[$i]['bet_id'] ?>").remove();
                        $('#bet-button-<?php echo $bets[$i]['bet_id'] ?>').removeClass("selected");
                    });
                 
                });

                <?php $betslip[] = ($bets[$i]['bet_id'])   ?>

            });

            
        });

    </script>

<?php }?>

<script>
    $(document).ready( function(){
        $('#delete-all-button').click( function(e){
            e.preventDefault();
            $('#row-betslip').html('');
            <?php for($j = 0; $j < count($bets); $j++){?>
                    $('#bet-button-<?php echo $bets[$j]['bet_id'] ?>').removeClass("selected");
                    $("#card-betslip-<?php echo $bets[$j]['bet_id'] ?>").remove();
            <?php }?>
        });
    });

    
</script>

