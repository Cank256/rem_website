# Hero Slider Images

This folder contains the hero slider images for the Rural Evangelical Ministries website.

## Current Images

Currently, placeholder SVG images are being used. To use your own images:

1. **Replace the placeholder images** with your own photos:
   - `slide1.jpg` - Main church/ministry image
   - `slide2.jpg` - Community/worship image
   - `slide3.jpg` - Service/outreach image

## Image Specifications

- **Recommended Size**: 1920x1080 pixels (Full HD) or larger
- **Aspect Ratio**: 16:9 (widescreen)
- **Format**: JPG or PNG
- **File Size**: Optimize images to be under 500KB each for faster loading
- **Content**: Ensure images are bright enough to support white text overlay

## Image Optimization Tips

1. Use tools like TinyPNG or ImageOptim to compress images
2. Ensure images are high quality but web-optimized
3. Consider the text overlay when choosing images (darker or blurred backgrounds work best)
4. Test on mobile devices to ensure images look good at all screen sizes

## Customizing Slider Content

To customize the slider text and buttons, edit:
`resources/js/Components/HeroSlider.jsx`

Look for the `slides` array and modify:
- `title` - Main heading text
- `subtitle` - Subheading text
- `cta` - Primary button text and link
- `cta2` - Secondary button text and link

## Adding More Slides

To add more slides:

1. Add your image to this folder (e.g., `slide4.jpg`)
2. Edit `resources/js/Components/HeroSlider.jsx`
3. Add a new slide object to the `slides` array
4. Run `npm run build` to rebuild the assets
