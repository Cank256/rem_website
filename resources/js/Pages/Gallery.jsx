import Layout from '@/Components/Layout';
import { Head } from '@inertiajs/react';
import { useState, useEffect } from 'react';

export default function Gallery({ galleries = [] }) {
    const [selectedGallery, setSelectedGallery] = useState('all');
    const [lightboxIndex, setLightboxIndex] = useState(null);

    // Get all images from all galleries
    const allImages = galleries.flatMap(gallery => 
        gallery.images.map(image => ({
            ...image,
            gallery_name: gallery.name
        }))
    );

    // Filter images based on selected gallery
    const filteredImages = selectedGallery === 'all' 
        ? allImages 
        : galleries.find(g => g.id === selectedGallery)?.images || [];

    // Get current lightbox image
    const lightboxImage = lightboxIndex !== null ? filteredImages[lightboxIndex] : null;

    // Navigation functions
    const goToPrevious = () => {
        if (lightboxIndex > 0) {
            setLightboxIndex(lightboxIndex - 1);
        }
    };

    const goToNext = () => {
        if (lightboxIndex < filteredImages.length - 1) {
            setLightboxIndex(lightboxIndex + 1);
        }
    };

    const closeLightbox = () => {
        setLightboxIndex(null);
    };

    // Keyboard navigation
    useEffect(() => {
        const handleKeyDown = (e) => {
            if (lightboxIndex === null) return;

            switch (e.key) {
                case 'Escape':
                    closeLightbox();
                    break;
                case 'ArrowLeft':
                    goToPrevious();
                    break;
                case 'ArrowRight':
                    goToNext();
                    break;
                default:
                    break;
            }
        };

        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [lightboxIndex, filteredImages.length]);

    // Prevent body scroll when lightbox is open
    useEffect(() => {
        if (lightboxIndex !== null) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = 'unset';
        }
        return () => {
            document.body.style.overflow = 'unset';
        };
    }, [lightboxIndex]);

    return (
        <Layout>
            <Head title="Gallery" />

            {/* Hero Section */}
            <div className="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">Photo Gallery</h1>
                    <p className="text-xl text-indigo-100">Capturing moments of faith, fellowship, and service</p>
                </div>
            </div>

            {/* Gallery Section */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                {/* Gallery Filter */}
                <div className="flex flex-wrap justify-center gap-4 mb-12">
                    <button
                        onClick={() => setSelectedGallery('all')}
                        className={`px-6 py-2 rounded-full font-medium transition-colors ${
                            selectedGallery === 'all'
                                ? 'bg-indigo-600 text-white'
                                : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                        }`}
                    >
                        All Photos
                    </button>
                    {galleries.map((gallery) => (
                        <button
                            key={gallery.id}
                            onClick={() => setSelectedGallery(gallery.id)}
                            className={`px-6 py-2 rounded-full font-medium transition-colors ${
                                selectedGallery === gallery.id
                                    ? 'bg-indigo-600 text-white'
                                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
                            }`}
                        >
                            {gallery.name}
                        </button>
                    ))}
                </div>

                {/* Gallery Grid */}
                {filteredImages.length > 0 ? (
                    <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        {filteredImages.map((image, index) => (
                            <div
                                key={image.id}
                                className="group relative bg-gray-200 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow cursor-pointer aspect-square"
                                onClick={() => setLightboxIndex(index)}
                            >
                                {/* Image */}
                                <img 
                                    src={image.image_url || '/images/placeholder.jpg'} 
                                    alt={image.title}
                                    className="w-full h-full object-cover"
                                />
                                
                                {/* Overlay */}
                                <div className="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-60 transition-all flex items-center justify-center">
                                    <div className="text-white text-center opacity-0 group-hover:opacity-100 transition-opacity p-4">
                                        <h3 className="text-xl font-semibold mb-2">{image.title}</h3>
                                        {image.description && (
                                            <p className="text-sm">{image.description}</p>
                                        )}
                                        {selectedGallery === 'all' && (
                                            <p className="text-xs mt-2 text-indigo-300">{image.gallery_name}</p>
                                        )}
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                ) : (
                    <div className="text-center py-12">
                        <svg className="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p className="text-gray-500 text-lg">
                            {galleries.length === 0 
                                ? 'No galleries available yet. Check back soon!' 
                                : 'No photos in this gallery yet.'}
                        </p>
                    </div>
                )}

                {/* Enhanced Lightbox with Navigation */}
                {lightboxImage && (
                    <div 
                        className="fixed inset-0 bg-black bg-opacity-95 z-50 flex items-center justify-center"
                        onClick={closeLightbox}
                    >
                        {/* Close Button */}
                        <button 
                            className="absolute top-4 right-4 text-white text-4xl hover:text-gray-300 transition-colors z-10 w-12 h-12 flex items-center justify-center"
                            onClick={closeLightbox}
                            aria-label="Close"
                        >
                            <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>

                        {/* Previous Button */}
                        {lightboxIndex > 0 && (
                            <button
                                className="absolute left-4 top-1/2 -translate-y-1/2 text-white bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-3 transition-all z-10"
                                onClick={(e) => {
                                    e.stopPropagation();
                                    goToPrevious();
                                }}
                                aria-label="Previous image"
                            >
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                        )}

                        {/* Next Button */}
                        {lightboxIndex < filteredImages.length - 1 && (
                            <button
                                className="absolute right-4 top-1/2 -translate-y-1/2 text-white bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-3 transition-all z-10"
                                onClick={(e) => {
                                    e.stopPropagation();
                                    goToNext();
                                }}
                                aria-label="Next image"
                            >
                                <svg className="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        )}

                        {/* Image Container */}
                        <div 
                            className="max-w-7xl w-full h-full flex flex-col items-center justify-center p-4 md:p-8"
                            onClick={(e) => e.stopPropagation()}
                        >
                            {/* Image */}
                            <div className="relative max-h-[70vh] w-full flex items-center justify-center mb-4">
                                <img 
                                    src={lightboxImage.image_url || '/images/placeholder.jpg'} 
                                    alt={lightboxImage.title}
                                    className="max-w-full max-h-[70vh] w-auto h-auto object-contain rounded-lg shadow-2xl"
                                />
                            </div>

                            {/* Image Info */}
                            <div className="text-white text-center max-w-2xl">
                                {/* Counter */}
                                <p className="text-sm text-gray-400 mb-2">
                                    {lightboxIndex + 1} / {filteredImages.length}
                                </p>

                                {/* Title */}
                                {lightboxImage.title && (
                                    <h3 className="text-2xl font-semibold mb-2">{lightboxImage.title}</h3>
                                )}

                                {/* Description */}
                                {lightboxImage.description && (
                                    <p className="text-gray-300 mb-2">{lightboxImage.description}</p>
                                )}

                                {/* Gallery Name (when viewing all) */}
                                {selectedGallery === 'all' && lightboxImage.gallery_name && (
                                    <p className="text-sm text-indigo-400">
                                        <svg className="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        {lightboxImage.gallery_name}
                                    </p>
                                )}

                                {/* Keyboard Hints */}
                                <div className="mt-4 text-xs text-gray-500 flex items-center justify-center gap-4">
                                    <span className="flex items-center gap-1">
                                        <kbd className="px-2 py-1 bg-gray-800 rounded">←</kbd>
                                        <kbd className="px-2 py-1 bg-gray-800 rounded">→</kbd>
                                        Navigate
                                    </span>
                                    <span className="flex items-center gap-1">
                                        <kbd className="px-2 py-1 bg-gray-800 rounded">Esc</kbd>
                                        Close
                                    </span>
                                </div>
                            </div>
                        </div>

                        {/* Thumbnail Strip (Optional - for desktop) */}
                        <div className="hidden md:block absolute bottom-0 left-0 right-0 bg-black bg-opacity-75 p-4">
                            <div className="max-w-7xl mx-auto overflow-x-auto">
                                <div className="flex gap-2 justify-center">
                                    {filteredImages.slice(
                                        Math.max(0, lightboxIndex - 5),
                                        Math.min(filteredImages.length, lightboxIndex + 6)
                                    ).map((img, idx) => {
                                        const actualIndex = Math.max(0, lightboxIndex - 5) + idx;
                                        return (
                                            <button
                                                key={img.id}
                                                onClick={(e) => {
                                                    e.stopPropagation();
                                                    setLightboxIndex(actualIndex);
                                                }}
                                                className={`flex-shrink-0 w-16 h-16 rounded overflow-hidden transition-all ${
                                                    actualIndex === lightboxIndex 
                                                        ? 'ring-2 ring-indigo-500 opacity-100' 
                                                        : 'opacity-50 hover:opacity-75'
                                                }`}
                                            >
                                                <img
                                                    src={img.image_url || '/images/placeholder.jpg'}
                                                    alt={img.title}
                                                    className="w-full h-full object-cover"
                                                />
                                            </button>
                                        );
                                    })}
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </div>

            {/* Upload Info */}
            <div className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-gray-900 mb-4">Share Your Photos</h2>
                    <p className="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                        Have photos from our events or services? We'd love to see them! 
                        Contact us to share your memories with our church family.
                    </p>
                    <a
                        href="/contact"
                        className="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                    >
                        Contact Us
                    </a>
                </div>
            </div>
        </Layout>
    );
}
