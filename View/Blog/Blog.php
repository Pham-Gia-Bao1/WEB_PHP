<?php
include("../View/LayOut/Header/Header.php");
include("../root/CSS/Blog.css.php");
?>
<title>Blog</title>
<link rel="stylesheet" href="http://localhost/WEB_PHP/root/CSS/Blog.css">
<!-- phần 1 -->
<div class="container-fluid" id="main">
  <h1 class="main_title">
    Bạn muốn xem gì hôm nay ?
  </h1>
  <div class="box_search">
    <!-- Phần tìm kiếm -->
    <form class="w-100 p-0 form_search" method="GET" action="http://localhost/WEB_PHP/Blog">
      <button class="box_search_icon" type="submit">
        <i class="fa fa-search"></i>
      </button>
      <input type="text" name="search" class="input_search" placeholder="Tìm kiếm...">
    </form>
    <ul class="suggestion_box"></ul>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <?php
  include("../root/JS/Blog.js.php");
  ?>
  <div class="box_choice d-flex">
  <form method="get" action="http://localhost/WEB_PHP/Blog">
  <button type="submit" class="btn btn-primary" name="opption" value="TAT_CA">
    TẤT CẢ
  </button>
  <button type="submit" class="btn btn-light box_choice_btn" name="opption" value="DONG_LUC">
    ĐỘNG LỰC
  </button>
  <button type="submit" class="btn btn-light box_choice_btn" name="opption" value="GIA_DINH">
    GIA ĐÌNH
  </button>
  <button type="submit" class="btn btn-light box_choice_btn" name="opption" value="TINH_YEU">
    TÌNH YÊU
  </button>
  <button type="submit" class="btn btn-light box_choice_btn" name="opption" value="THIEN_NHIEN">
    THIÊN NHIÊN
  </button>
</form>
  </div>
</div>
<!-- <div class="block_img"> here </div> -->
<div class="content" id="content">
  <h2 class="text-primary sub_title">Dành cho bạn</h2>
  <div class="container-fluid container1 d-flex flex-wrap gap-4" id="container1">
    <?php
    foreach ($data['products'] as $product) { ?>
      <div class="card content_video" id="content_video">
        <div class="video">
          <iframe src="<?= $product['youtube_link'] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
        </div>
        <div class="item_style d-lex flex-wrap">
          <h6 class="w-100"><?= $product['title'] ?></h6>
          <h6>5 min read</h6>
        </div>
        <h3 class="card-title item_name"></h3>
        <p class="card-text item_sub_title">
          <?= $product['description'] ?>
        </p>
        <p class="text-primary">
          <a href="<?= $product['youtube_link'] ?>" asp-controller="Blog" asp-action="DetailVideo" asp-route-id="@video.Id">
            Xem video
          </a>
          <i class="fa-solid fa-chevron-right"></i>
        </p>

      </div>
    <?php }
    if ($data['products'] == "" || $data['products'] == null) {
      echo "<h1" . '"class="text-primary"' . ">Không tìm thấy video</h1><br>";
      echo '<img src="https://st.chungta.vn/v303/chungta/images/graphics/404.svg" alt="">';
    } ?>
  </div>
</div>
<div class="content2">
  <div class="podcast">
    <p class="p111">Giới Thiệu Cho Bạn</p>
    <div class="content" id="content">
      <h6 style="text-align: center; padding-bottom: 10px;font-size: 30px;"><b>Một Vài Podcast Tâm Hồn</b></h6>
      <div class="container-fluid container1 d-flex flex-wrap gap-4" id="container1">
        <?php foreach ($data['podcasts'] as $product) { ?>
          <div class="card content_video" id="content_video">
            <div class="video">
              <iframe src="<?= $product['youtube_link'] ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
            <div class="item_style">
              <h6><?= $product['title'] ?></h6>
              <h6>5 min read</h6>
            </div>
            <h3 class="card-title item_name"></h3>
            <p class="card-text item_sub_title">
              <?= $product['description'] ?>
            </p>
            <p class="text-primary">
              <a href="<?= $product['youtube_link'] ?>" asp-controller="Blog" asp-action="DetailVideo" asp-route-id="@video.Id">
                Xem thêm
              </a>
              <i class="fa-solid fa-chevron-right"></i>
            </p>
          </div>
        <?php }  ?>
      </div>
    </div>
  </div>
</div>
<?php
include("../View/LayOut/Footer/Footer.php");
?>