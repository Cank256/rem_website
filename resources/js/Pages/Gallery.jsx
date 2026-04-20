import Layout from '@/Components/Layout';
import { Head } from '@inertiajs/react';
import { useState } from 'react';

export default function Gallery({ galleries = [] }) {
    const [selectedGallery, setSelectedGallery] = useState('all');
    const [lightboxImage, setLightboxImage] = useState(null);

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
                        {filteredImages.map((image) => (
                            <div
                                key={image.id}
                                className="group relative bg-gray-200 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow cursor-pointer aspect-square"
                                onClick={() => setLightboxImage(image)}
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

                {/* Lightbox */}
                {lightboxImage && (
                    <div 
                        className="fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4"
                        onClick={() => setLightboxImage(null)}
                    >
                        <div className="max-w-4xl w-full">
                            <img 
                                src={lightboxImage.image_url || '/images/placeholder.jpg'} 
                                alt={lightboxImage.title}
                                className="w-full h-auto rounded-lg"
                            />
                            <div className="text-white text-center mt-4">
                                <h3 className="text-2xl font-semibold mb-2">{lightboxImage.title}</h3>
                                {lightboxImage.description && (
                                    <p className="text-gray-300">{lightboxImage.description}</p>
                                )}
                            </div>
                        </div>
                        <button 
                            className="absolute top-4 right-4 text-white text-4xl hover:text-gray-300"
                            onClick={() => setLightboxImage(null)}
                        >
                            &times;
                        </button>
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
