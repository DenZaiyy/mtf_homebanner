/**
 * 2023-2024 MTFibertech
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 */

document.addEventListener("DOMContentLoaded", function () {
    // Initialize slider if it exists
    if ($(".mtf-homebanner-slider").length) {
        $(".mtf-homebanner-slider")
            .on("init", function () {
                // Force equal heights after initialization
                var maxHeight = 0;
                $(".mtf-slider-item").each(function () {
                    if ($(this).height() > maxHeight) {
                        maxHeight = $(this).height();
                    }
                });
                $(".mtf-slider-item").height(maxHeight);
            })
            .slick({
                dots: true,
                infinite: true,
                speed: 500,
                slidesToShow: parseInt(
                    $(".mtf-homebanner-slider").data("columns") || 3
                ),
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 5000,
                adaptiveHeight: false, // Important! Don't allow adaptive height
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: Math.min(
                                parseInt(
                                    $(".mtf-homebanner-slider").data(
                                        "columns"
                                    ) || 3
                                ),
                                3
                            ),
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: Math.min(
                                parseInt(
                                    $(".mtf-homebanner-slider").data(
                                        "columns"
                                    ) || 3
                                ),
                                2
                            ),
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                        },
                    },
                ],
            });

        // Re-calculate heights on window resize
        $(window).resize(function () {
            $(".mtf-homebanner-slider").slick("resize");
        });
    }
    // Add hover effects for banners
    const bannerButtons = document.querySelectorAll(".mtf-banner-button");

    bannerButtons.forEach((button) => {
        button.addEventListener("mouseenter", function () {
            const icon = this.querySelector(".mtf-banner-button-icon");
            if (icon) {
                icon.style.transform = "translateX(3px)";
                icon.style.transition = "transform 0.3s ease";
            }
        });

        button.addEventListener("mouseleave", function () {
            const icon = this.querySelector(".mtf-banner-button-icon");
            if (icon) {
                icon.style.transform = "translateX(0)";
            }
        });
    });

    // Adjust layout for fewer banners in multi-column layouts
    const grid = document.querySelector(".mtf-homebanner-grid");
    if (grid) {
        const bannerCount = grid.querySelectorAll(".mtf-banner-item").length;
        const columns = parseInt(grid.className.match(/cols-(\d+)/)[1]);

        if (bannerCount < columns) {
            if (bannerCount === 1) {
                grid.style.gridTemplateColumns = "1fr";
            } else if (bannerCount === 2 && columns > 2) {
                grid.style.gridTemplateColumns = "repeat(2, 1fr)";
            }
        }
    }
});
