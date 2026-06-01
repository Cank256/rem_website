#!/bin/bash

# Fix Storage Permissions Script
# Run this via SSH: bash fix-permissions.sh

echo "🔧 Fixing storage permissions..."
echo ""

# Get the script directory
SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Set paths
STORAGE_PATH="$SCRIPT_DIR/storage/app/public"
GALLERY_PATH="$STORAGE_PATH/gallery-images"

echo "📁 Paths:"
echo "Storage: $STORAGE_PATH"
echo "Gallery: $GALLERY_PATH"
echo ""

# Check if directories exist
if [ ! -d "$STORAGE_PATH" ]; then
    echo "❌ Storage directory not found: $STORAGE_PATH"
    exit 1
fi

if [ ! -d "$GALLERY_PATH" ]; then
    echo "❌ Gallery directory not found: $GALLERY_PATH"
    exit 1
fi

echo "✅ Directories found"
echo ""

# Fix directory permissions
echo "📁 Setting directory permissions to 755..."
chmod 755 "$STORAGE_PATH"
chmod 755 "$GALLERY_PATH"
echo "✅ Directory permissions set"
echo ""

# Fix file permissions
echo "🖼️ Setting file permissions to 644..."
find "$GALLERY_PATH" -type f -exec chmod 644 {} \;
echo "✅ File permissions set"
echo ""

# Count files
FILE_COUNT=$(find "$GALLERY_PATH" -type f | wc -l)
echo "📊 Fixed permissions for $FILE_COUNT files"
echo ""

# Check for blocking .htaccess files
echo "🔍 Checking for blocking .htaccess files..."
if [ -f "$STORAGE_PATH/.htaccess" ]; then
    echo "⚠️  Found .htaccess in storage/app/public"
    echo "   Location: $STORAGE_PATH/.htaccess"
    echo "   Consider removing it if it blocks access"
fi

if [ -f "$GALLERY_PATH/.htaccess" ]; then
    echo "⚠️  Found .htaccess in gallery-images"
    echo "   Location: $GALLERY_PATH/.htaccess"
    echo "   Consider removing it if it blocks access"
fi

echo ""
echo "✅ Done! Try accessing your images now."
echo ""
echo "If images still show 403, run:"
echo "  bash fix-permissions.sh --create-htaccess"
echo ""

# Create .htaccess if requested
if [ "$1" == "--create-htaccess" ]; then
    echo "📝 Creating .htaccess in gallery-images..."
    cat > "$GALLERY_PATH/.htaccess" << 'EOF'
Options +FollowSymLinks
Require all granted
EOF
    chmod 644 "$GALLERY_PATH/.htaccess"
    echo "✅ Created .htaccess to allow access"
fi
