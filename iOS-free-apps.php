<?php
include "index-ios.php";
?>
<div class="col-md-5">
    <div class="free">
        <div class="title">
            برنامه‌های <span>رایگان شده</span> آیفون
        </div>
        <?php foreach ($myApps as $apps) {?>
            <div class="post">
            <a href="<?php echo $apps["url"]; ?>" target="_blank">
            <div class="img white">
                <img src="<?php echo $apps["img_url"]; ?>" alt="free App mac">
            </div>
            </a>
            <div class="title">
                <h2><a href="<?php echo $apps["url"]; ?>" target="_blank"><?php echo $apps["title"]; ?></a>
                </h2>
            </div>
            <div class="price"><?php echo $apps["price"]; ?> دلار</div>
            <div class="free green">رایگان</div>
        </div>
        <?php }?>
    </div>
</div>