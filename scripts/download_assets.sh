#!/bin/bash

# Create directories
mkdir -p public/assets/fonts
mkdir -p public/assets/css

# Download Font Awesome CSS
curl -o public/assets/css/fontawesome.min.css \
     https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css

# Download Font Awesome fonts
for font in fa-brands-400.woff2 fa-regular-400.woff2 fa-solid-900.woff2; do
    curl -o "public/assets/fonts/$font" \
         "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/webfonts/$font"
done

# Download Roboto font
curl -o public/assets/fonts/roboto-regular.woff2 \
     https://fonts.gstatic.com/s/roboto/v30/KFOmCnqEu92Fr1Mu4mxK.woff2

echo "Assets downloaded successfully!"