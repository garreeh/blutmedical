<!-- /*
* Bootstrap 5
* Template Name: Furni
* Template Author: Untree.co
* Template URI: https://untree.co/
* License: https://creativecommons.org/licenses/by/3.0/
*/ -->
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="author" content="Untree.co">
  <link rel="shortcut icon" href="favicon.png">

  <meta name="description" content="" />
  <meta name="keywords" content="bootstrap, bootstrap4" />

  <!-- Bootstrap CSS -->
  <?php include 'assets.php'; ?>
  <title>Blut Medical</title>
</head>


<body>

  <?php
  include './includes/navigation.php';
  include './connections/connections.php';

  function fixUploadPath($path)
  {
    return './uploads/' . basename($path);
  }

  $sql_carousel = "SELECT * FROM carousel ORDER BY carousel_id ASC LIMIT 3";
  $result_carousel = mysqli_query($conn, $sql_carousel);

  $category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0;

  /* =========================
     GET CATEGORY NAME (FIXED)
  ========================= */
  $category_name = "All Categories";

  if ($category_id > 0) {
    $nameQuery = "SELECT category_name FROM category WHERE category_id = $category_id LIMIT 1";
    $nameResult = mysqli_query($conn, $nameQuery);

    if ($nameResult && mysqli_num_rows($nameResult) > 0) {
      $nameRow = mysqli_fetch_assoc($nameResult);
      $category_name = $nameRow['category_name'];
    }
  }

  if ($category_id > 0) {
    $sql = "SELECT * FROM category WHERE category_id = $category_id";
  } else {
    $sql = "SELECT * FROM category";
  }

  $result = $conn->query($sql);
  ?>

  <?php if (mysqli_num_rows($result_carousel) > 0): ?>

    <!-- HERO BACKGROUND CAROUSEL -->
    <div id="heroCarousel" class="carousel slide hero-bg-carousel" data-bs-ride="carousel">

      <!-- SLIDES -->
      <div class="carousel-inner">

        <?php
        $i = 0;
        while ($row_carousel = mysqli_fetch_assoc($result_carousel)) {

          $active = ($i == 0) ? 'active' : '';
          $file = fixUploadPath($row_carousel['scene']);
          $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
          ?>

          <div class="carousel-item <?php echo $active; ?> hero-slide position-relative">

            <?php if ($ext == 'mp4') { ?>

              <video class="w-100 h-100 hero-video" style="object-fit:cover;" autoplay loop playsinline>
                <source src="<?php echo $file; ?>" type="video/mp4">
              </video>

            <?php } else { ?>

              <img src="<?php echo $file; ?>" class="d-block w-100 h-100" style="object-fit:cover;">

            <?php } ?>

            <!-- DARK OVERLAY -->
            <!-- <div class="position-absolute top-0 start-0 w-100 h-100" style="background:rgba(0,0,0,0.4); z-index:1;"></div> -->

            <!-- TEXT CONTENT (CENTER BUT LOWER) -->
            <div class="position-absolute start-50 translate-middle-x text-center w-100" style="top:85%; z-index:2;">

              <a href="products.php" class="btn btn-secondary">
                Shop Now
              </a>

            </div>

          </div>

          <?php $i++;
        } ?>

      </div>

      <!-- CONTROLS -->
      <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>

      <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>

      <div class="position-absolute bottom-0 end-0 p-3" style="z-index:999;">
        <button id="muteBtn" class="btn btn-dark btn-sm">🔈 Unmute</button>
        <button id="volDownBtn" class="btn btn-dark btn-sm">➖</button>
        <button id="volUpBtn" class="btn btn-dark btn-sm">➕</button>
      </div>

    </div>

  <?php else: ?>

    <!-- START HERO SECTION (FALLBACK) -->
    <div class="hero">
      <div class="container">
        <div class="row justify-content-between">
          <div class="col-lg-7">
            <div class="intro-excerpt">
              <h1 style="color:black !important; opacity: 100%;">
                Welcome to BLüT Medical
              </h1>

              <p class="mb-4" style="color:black !important; opacity: 100%;">
                We are a provider of innovative premium quality products that will elevate any medical practice be it for
                veterinarians or human doctors.
              </p>

              <p>
                <a href="products.php" class="btn btn-secondary me-2">Shop Now</a>
              </p>
            </div>
          </div>

          <div class="col-lg-5 d-none d-md-block">
            <div class="hero-img-wrap">
              <img src="assets/logo/blutfront.png" class="img-fluid" style="max-width: 75%;">
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- END HERO SECTION -->

  <?php endif; ?>

  <br>


  <!-- Start Product Section -->
  <div class="product-section">
    <div class="container">

      <nav class="d-flex justify-content-between align-items-center" aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
          <li class="breadcrumb-item"><a href="index.php">Home</a></li>
          <li class="breadcrumb-item active" aria-current="page">
            <?php echo htmlspecialchars($category_name); ?>
          </li>
        </ol>
      </nav>

      <br>

      <!-- Product List -->
      <div class="row" id="productList">

        <?php if ($result && $result->num_rows > 0): ?>

          <?php while ($row = $result->fetch_assoc()): ?>

            <?php
            $cat_id = $row['category_id'];
            $cat_name = htmlspecialchars($row['category_name']);
            $cat_image = !empty($row['category_image'])
              ? 'uploads/category/' . htmlspecialchars($row['category_image'])
              : null;
            ?>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-5">

              <a href="category_based.php?category_id=<?php echo $cat_id; ?>"
                style="text-decoration: none; color: inherit;">

                <div class="product-item" style="text-align: center; padding: 20px; border: 1px solid #ddd; border-radius: 15px;
                          box-shadow: 0px 4px 10px rgba(0,0,0,0.1);
                          transition: all 0.3s ease-in-out; background: #fff;">

                  <?php if ($cat_image && file_exists($cat_image)) { ?>
                    <div style="width: 100%; height: 180px; overflow: hidden; border-radius: 10px; margin-bottom: 15px;">
                      <img src="<?php echo $cat_image; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                  <?php } else { ?>
                    <div style="width: 100%; height: 180px; background: linear-gradient(45deg, rgb(86, 13, 210), #feb47b);
                              display: flex; align-items: center; justify-content: center;
                              border-radius: 10px; margin-bottom: 15px;">
                      <i class="fas fa-tags" style="font-size: 4rem; color: #fff;"></i>
                    </div>
                  <?php } ?>

                  <p style="font-size: 1.2rem; font-weight: bold; color: #333;">
                    <?php echo $cat_name; ?>
                  </p>

                </div>

              </a>
            </div>

          <?php endwhile; ?>

        <?php else: ?>

          <div class="col-12 text-center mt-5">
            <p style="font-size: 1.5rem; font-weight: bold; color: #555;">
              There’s no category here.
            </p>
          </div>

        <?php endif; ?>

      </div>
    </div>
  </div>

  <?php include './includes/footer.php'; ?>

</body>

</html>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
  // AJAX for Search Bar using jQuery
  $('#searchInput').on('input', function () {
    const query = $(this).val().trim();
    if (query.length > 0) {
      $.ajax({
        type: 'POST',
        url: '/blutmedical/controllers/users/search_products.php',
        data: {
          query: query
        },
        success: function (response) {
          $('#searchResults').html(response).addClass('show');
        }
      });
    } else {
      $('#searchResults').removeClass('show');
    }
  });

  // Hide search results when clicking outside
  $(document).on('click', function (e) {
    if (!$(e.target).closest('#searchInput, #searchResults').length) {
      $('#searchResults').removeClass('show');
    }
  });

  $(document).ready(function () {
    // Populate categories in the dropdown
    $.ajax({
      type: 'GET',
      url: '/blutmedical/controllers/users/fetch_categories.php',
      success: function (response) {
        $('#categoryDropdown').append(response);
      },
      error: function () {
        $('#categoryDropdown').append('<option disabled>Error loading categories</option>');
      }
    });

    // Handle category change
    $('#categoryDropdown').on('change', function () {
      const categoryId = $(this).val();
      const urlParams = new URLSearchParams(window.location.search);
      const subcategoryId = urlParams.get('category_id') || 0;

      // Fetch products by selected category
      $.ajax({
        type: 'GET',
        url: '/blutmedical/controllers/users/fetch_products_by_category_subs.php',
        data: {
          category_id: categoryId,
          category_id: subcategoryId
        },
        success: function (response) {
          $('#productList').html(response);
        },
        error: function () {
          $('#productList').html('<p>Error loading products. Please try again.</p>');
        }
      });
    });
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {

    const carousel = document.getElementById('heroCarousel');

    if (!carousel) return;

    const videos = document.querySelectorAll('.hero-video');

    // Default settings
    videos.forEach(video => {
      video.volume = 0.3; // 30%
      video.muted = true; // browser allows autoplay
    });

    function pauseAllVideos() {
      videos.forEach(video => {
        video.pause();
      });
    }

    function playActiveVideo() {

      const activeVideo =
        document.querySelector('.carousel-item.active .hero-video');

      if (!activeVideo) return;

      activeVideo.play().catch(() => {
        console.log('Autoplay blocked until user interacts.');
      });
    }

    pauseAllVideos();
    playActiveVideo();

    carousel.addEventListener('slide.bs.carousel', function () {
      pauseAllVideos();
    });

    carousel.addEventListener('slid.bs.carousel', function () {
      playActiveVideo();
    });

    // Enable sound after first user interaction
    let soundEnabled = false;

    document.addEventListener('click', function enableSound() {

      if (soundEnabled) return;

      soundEnabled = true;

      videos.forEach(video => {
        video.muted = false;
      });

      const muteBtn = document.getElementById('muteBtn');

      if (muteBtn) {
        muteBtn.innerHTML = '🔇 Mute';
      }

    }, { once: true });

    // MUTE BUTTON
    const muteBtn = document.getElementById('muteBtn');

    if (muteBtn) {

      muteBtn.addEventListener('click', function () {

        const activeVideo =
          document.querySelector('.carousel-item.active .hero-video');

        if (!activeVideo) return;

        activeVideo.muted = !activeVideo.muted;

        if (activeVideo.muted) {
          this.innerHTML = '🔈 Unmute';
        } else {
          this.innerHTML = '🔇 Mute';
        }

      });

    }

    // VOLUME UP
    const volUpBtn = document.getElementById('volUpBtn');

    if (volUpBtn) {

      volUpBtn.addEventListener('click', function () {

        const activeVideo =
          document.querySelector('.carousel-item.active .hero-video');

        if (!activeVideo) return;

        activeVideo.muted = false;

        activeVideo.volume = Math.min(
          activeVideo.volume + 0.1,
          1
        );

        console.log('Volume:', activeVideo.volume);

      });

    }

    // VOLUME DOWN
    const volDownBtn = document.getElementById('volDownBtn');

    if (volDownBtn) {

      volDownBtn.addEventListener('click', function () {

        const activeVideo =
          document.querySelector('.carousel-item.active .hero-video');

        if (!activeVideo) return;

        activeVideo.volume = Math.max(
          activeVideo.volume - 0.1,
          0
        );

        if (activeVideo.volume === 0) {
          activeVideo.muted = true;
        }

        console.log('Volume:', activeVideo.volume);

      });

    }

  });
</script>


<style>
  .hero-bg-carousel,
  .hero-bg-carousel .carousel-inner,
  .hero-bg-carousel .carousel-item {
    height: 80vh;
    min-height: 500px;
  }

  .hero-slide {
    position: relative;
  }

  .hero-slide img,
  .hero-slide video {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  /* DARK OVERLAY */
  .hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.45);
  }

  /* TEXT ON TOP */
  .hero-content {
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    transform: translateY(-50%);
    color: white;
    text-align: left;
  }

  .hero-content h1 {
    color: white !important;
    font-size: 48px;
  }

  .hero-content p {
    font-size: 18px;
    max-width: 600px;
  }
</style>