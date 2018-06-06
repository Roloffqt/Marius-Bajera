
<?php

    $mode = setMode();

    switch ($mode):

case "index":
$news = new news();
$mainnews = $news->GetSelect(1);

 ?>
<article class="col-lg-8 col-xs-12 col-sm-12 col-md-12">
  <?php foreach ($mainnews as $key => $value): ?>

  <div  class="col-lg-12 col-xs-12 col-sm-12 menu-space">
    <img class="hero-image" src="<?php echo $value['vcImage'] ?>">

<div class="overlay">
  <a href="?mode=news&iNewsID=<?php echo $value["iNewsID"] ?>">
    <h3 class="underline"><?php echo $value["vcNewsTitel"] ?></h3>
    <p><?php echo $value["txNewsDescription"] ?></p>
    </a>
</div>
  </div>

<?php endforeach; ?>
</article>

<?php
break;

case "news":
$news = new news();
$iNewsID = filter_input(INPUT_GET, "iNewsID", FILTER_SANITIZE_NUMBER_INT);
$mainnews = $news->GetItem($iNewsID);
if($mainnews != NULL){

?>
<article class="col-lg-8 col-xs-12 col-sm-12">

<div  class="inline-block col-lg-12 col-xs-12">
    <h2><?php echo $mainnews[0]["vcNewsTitel"] ?> - <?php echo date("d/m/y", $mainnews[0]["daCreated"]); ?></h3>


      <img style="max-width:100%; max-height: 50vh;" class="hero-image" src="<?php echo $mainnews[0]['vcImage'] ?>">
    <p><?php echo $mainnews[0]["txNewsDescription"] ?></p>
      <p><?php echo $mainnews[0]["txNews"] ?></p>
</div>



</article>
<?php
}else{
  echo "News article not found";
}
break;
endswitch; ?>
