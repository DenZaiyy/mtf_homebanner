/**
 * 2023-2024 MTFibertech
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 */

document.addEventListener("DOMContentLoaded", function () {
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
