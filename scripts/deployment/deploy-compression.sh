#!/bin/bash

# Deployment script for image compression feature
# Run this on your production server after pulling the code

echo "🚀 Deploying Image Compression Feature"
echo "========================================"
echo ""

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "❌ Error: artisan file not found. Are you in the project root?"
    exit 1
fi

echo "📦 Step 1: Installing Composer Dependencies"
echo "--------------------------------------------"
composer install --no-dev --optimize-autoloader
if [ $? -ne 0 ]; then
    echo "❌ Composer install failed!"
    exit 1
fi
echo "✅ Dependencies installed"
echo ""

echo "🧹 Step 2: Clearing Caches"
echo "--------------------------------------------"
php artisan config:clear
php artisan cache:clear
php artisan view:clear
echo "✅ Caches cleared"
echo ""

echo "🔍 Step 3: Checking GD Extension"
echo "--------------------------------------------"
if php -m | grep -i gd > /dev/null; then
    echo "✅ GD extension is installed"
else
    echo "⚠️  WARNING: GD extension not found!"
    echo "   Image compression will be skipped until GD is installed."
    echo "   Contact your hosting provider to install php-gd"
fi
echo ""

echo "📁 Step 4: Checking Directories"
echo "--------------------------------------------"
if [ -d "public/gallery-images" ]; then
    echo "✅ public/gallery-images exists"
    chmod 755 public/gallery-images
    echo "✅ Permissions set to 755"
else
    echo "📁 Creating public/gallery-images"
    mkdir -p public/gallery-images
    chmod 755 public/gallery-images
    echo "✅ Directory created"
fi
echo ""

echo "🧪 Step 5: Testing Image Compression"
echo "--------------------------------------------"
php -r "
require 'vendor/autoload.php';
if (class_exists('Intervention\Image\Laravel\Facades\Image')) {
    echo '✅ Intervention Image is available' . PHP_EOL;
} else {
    echo '❌ Intervention Image not found' . PHP_EOL;
    echo 'Run: composer require intervention/image' . PHP_EOL;
}
"
echo ""

echo "✨ Deployment Complete!"
echo "========================================"
echo ""
echo "Next steps:"
echo "1. Test uploading an image through Filament"
echo "2. Check that it compresses automatically"
echo "3. Verify images display correctly on the website"
echo ""
echo "If compression doesn't work:"
echo "- Check storage/logs/laravel.log for errors"
echo "- Ensure GD extension is installed"
echo "- Verify composer dependencies are installed"
echo ""
