
<?php
$mode = setMode();
$works = new works();

switch ($mode):
  case 'works':

  ?>
  <article class="col-lg-8 col-xs-12 col-sm-12">
    <div class="row">
      <div class="col-lg-12 col-xs-12 col-sm-12">
    <h2>Works</h2>
  </div>
  <?php
  if (isset($_GET["iCategoryID"]) > 0){
  $Selectedworks = $works->getCategory($_GET["iCategoryID"]);
  foreach ($Selectedworks as $key => $row): ?>
  <div class="col-lg-4 col-xs-12 col-sm-6">

    <a href="?mode=details&iWorkID=<?php echo  $row['iWorkID'] ?>">
  <div class="img" style="background-image: url('<?php echo $row["vcImage"] ?>');"></div>
  <h3><?php echo $row["vcWorkTitel"]; ?></h3>
  <h5><?php echo date("d-m-y", $row["daCreated"]); ?></h4>
  </a>
  </div>

  <?php
endforeach;
  }
  ?>
        </div>
  </article>
  <?php
    break;


case 'details':
$iWorkID = filter_input(INPUT_GET, "iWorkID", FILTER_SANITIZE_NUMBER_INT);

$Selectedworks = $works->getItem($iWorkID);

?>
<article class="col-lg-8 col-xs-12">
  <div class="row">
    <div class="col-lg-12 col-xs-12">
  <h3><?php echo $Selectedworks[0]["vcWorkTitel"]; ?> - <?php echo date("d/m/y", $Selectedworks[0]["daCreated"]); ?></h2>
</div>

<div class="col-lg-12 col-xs-12">
    <img class="hero-image" src="<?php echo $Selectedworks[0]["vcImage"] ?>">
<p><?php echo $Selectedworks[0]["txWorkDescprition"]; ?></p>

</div>
</div>
</article>
<?php
break;
endswitch;
?>
