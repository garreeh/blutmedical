(function ($) {
  "use strict";

  // Tracks whether the user manually closed the sidebar
  let sidebarClosedByUser = false;

  function keepSidebarOpen() {
    // Only force open on mobile if the user hasn't manually closed it
    if ($(window).width() < 768 && !sidebarClosedByUser) {
      $("body").removeClass("sidebar-toggled");
      $(".sidebar").removeClass("toggled");

      // Keep all submenus closed
      $(".sidebar .collapse")
        .removeClass("show")
        .attr("aria-expanded", "false");

      $(".sidebar .nav-link[data-toggle='collapse']")
        .addClass("collapsed")
        .attr("aria-expanded", "false");
    }
  }

  // Initial load
  keepSidebarOpen();

  // Toggle sidebar
  $("#sidebarToggle, #sidebarToggleTop").on("click", function () {

    $("body").toggleClass("sidebar-toggled");
    $(".sidebar").toggleClass("toggled");

    if ($(".sidebar").hasClass("toggled")) {
      // User closed sidebar
      sidebarClosedByUser = true;
      $(".sidebar .collapse").collapse("hide");
    } else {
      // User opened sidebar
      sidebarClosedByUser = false;
    }
  });

  // Only check on resize
  $(window).on("resize", function () {
    keepSidebarOpen();
  });

  // Prevent the content wrapper from scrolling when the fixed side navigation is hovered
  $("body.fixed-nav .sidebar").on("mousewheel DOMMouseScroll wheel", function (e) {
    if ($(window).width() > 768) {
      var e0 = e.originalEvent,
        delta = e0.wheelDelta || -e0.detail;

      this.scrollTop += (delta < 0 ? 1 : -1) * 30;
      e.preventDefault();
    }
  });

  // Scroll to top button
  $(document).on("scroll", function () {
    var scrollDistance = $(this).scrollTop();

    if (scrollDistance > 100) {
      $(".scroll-to-top").fadeIn();
    } else {
      $(".scroll-to-top").fadeOut();
    }

    // If sidebar is open, keep it open.
    // If sidebar was manually closed, don't reopen it.
    keepSidebarOpen();
  });

  // Smooth scrolling
  $(document).on("click", "a.scroll-to-top", function (e) {
    var $anchor = $(this);

    $("html, body").stop().animate(
      {
        scrollTop: $($anchor.attr("href")).offset().top
      },
      1000,
      "easeInOutExpo"
    );

    e.preventDefault();
  });

})(jQuery);