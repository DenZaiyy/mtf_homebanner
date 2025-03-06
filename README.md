# MTF HomeBanner Module for PrestaShop 8.2

A custom module for PrestaShop 8.2 that allows you to display up to 4 banners on your homepage. Perfect for showcasing electrical products with a sleek and modern design.

## Features

- Display up to 4 custom banners on your homepage
- Choose between grid or slider layout
- Adjust the number of columns (1-4)
- Each banner supports:
  - Custom image
  - Title
  - Caption (with HTML support)
  - Link URL
  - Enable/disable individual banners
- Modern styling with rounded corners and hover effects
- Yellow call-to-action buttons with hand icons
- Automatic fallback to placeholder image when no image is uploaded
- Responsive design for all devices
- Admin interface with image preview and delete option
- Special styling optimized for electrical products

## Installation

1. Download the module files
2. Create a folder named `mtf_homebanner` in your PrestaShop `modules` directory
3. Upload all files to this folder, maintaining the directory structure
4. Go to your PrestaShop admin panel > Modules > Module Manager
5. Find "MTF - HomeBanner" and click Install

## Directory Structure

```
modules/
└── mtf_homebanner/
    ├── mtf_homebanner.php              # Main module file
    ├── controllers/
    │   └── admin/
    │       └── AdminMtfHomeBannerController.php  # Admin controller
    ├── assets/
    │   ├── css/
    │   │   ├── mtf_homebanner.css      # Main CSS styles
    │   │   └── slick.css               # Slider styles (for slider layout)
    │   └── js/
    │       ├── mtf_homebanner.js       # Main JS functionality
    │       └── slick.min.js            # Slider library (for slider layout)
    ├── views/
    │   ├── img/
    │   │   └── default-placeholder.jpg # Default placeholder image
    │   └── templates/
    │       └── hook/
    │           └── mtf_homebanner.tpl  # Front-end template
    └── index.php
```

## Configuration

1. After installation, go to the module configuration page in the admin menu
2. Configure your banners:
   - Set display layout (grid or slider)
   - Set number of columns
   - Upload banner images (recommended size: 800x400px)
   - Add title, caption, and links for each banner
   - Enable/disable individual banners
3. Save your settings

## Admin Features

- **Image Management**: Upload, preview and delete images for each banner
- **Placeholder Image**: Default placeholder shown when a banner is enabled but has no image
- **Rich Text Editor**: HTML support for banner captions
- **Separate Admin Controller**: Clean separation of admin logic from core module functionality

## Front-end Features

- **Grid Layout**: Display banners in a responsive grid
- **Slider Layout**: Display banners in an interactive slider (requires slick.js)
- **Responsive Design**: Adapts to all screen sizes
- **Modern Styling**: Rounded corners, shadows, and hover effects
- **Call-to-action Buttons**: Yellow buttons with hand pointer icons

## Electrical Products Styling

This module has been specially designed with electrical products in mind:
- Blue color scheme that matches electrical industry standards
- Clean, modern design that helps showcase technical products
- Hover effects that draw attention to important products or categories
- Gradient overlays for improved text readability

## Technical Notes

- Uses modern PrestaShop 8.2 architecture
- Follows best practices with separate controller for admin interface
- Asset files located in the `/assets/` directory (recommended for PrestaShop 8.2)
- Image uploads stored in `/views/img/` directory