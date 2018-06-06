
<?php
    $about = new about();
    $aboutcontent = $about->getabout(1);

 ?>

 <article class="col-lg-8 col-xs-12 col-sm-12">
   <div class="row">
     <div class="col-lg-8 col-xs-12 col-sm-12">
      <h3 class="page-title"><?php echo $aboutcontent[0]["vcAboutTitel"] ?></h3>
      <p class="about-text"><?php echo $aboutcontent[0]["txAboutDescprition"] ?></p>
          </div>
          <div class="col-lg-4 col-xs-12">
          <img class="profile-image" src="<?php echo $aboutcontent[0]["vcImage"] ?>">
          </div>
 <div class="col-lg-12 col-xs-12 col-sm-12">
    <ul class="Artist-info">

<li>Information<ul>
  <li><?php echo $aboutcontent[0]["vcName"] ?></li>
  <li><?php echo date('d ,m ,y ', $aboutcontent[0]["iDateofbirth"]); ?></li>
</ul></li>
<li>Exhibtions
  <ul><li>Yata</li></ul></li>
  <li>Education<ul>
    <li><?php echo $aboutcontent[0]["vcEducation"] ?></li>
  </ul></li>
</ul>
</div>

  </section>
</article>
