import Layout from '@/Components/Layout';
import { Head, router } from '@inertiajs/react';
import { useState } from 'react';

export default function Sermons({ sermons }) {
    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
    };

    const loadMore = () => {
        router.get(route('sermons'), 
            { per_page: sermons.per_page + 5 }, 
            { 
                preserveState: true,
                preserveScroll: true 
            }
        );
    };

    const hasMore = sermons.current_page < sermons.last_page;

    return (
        <Layout>
            <Head title="Sermons" />

            {/* Hero Section */}
            <div className="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">Sermons</h1>
                    <p className="text-xl text-indigo-100">Listen to biblical teaching and be encouraged in your faith</p>
                </div>
            </div>

            {/* Sermons List */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="mb-8">
                    <h2 className="text-3xl font-bold text-gray-900 mb-4">Recent Messages</h2>
                    <p className="text-gray-600">
                        Browse our collection of sermons and be encouraged by God's Word. 
                        Each message is designed to help you grow in your faith and walk with Christ.
                    </p>
                </div>

                {sermons.data && sermons.data.length > 0 ? (
                    <>
                        <div className="space-y-6">
                            {sermons.data.map((sermon) => {
                                const getYouTubeThumbnail = (url) => {
                                    if (!url) return null;
                                    const videoId = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
                                    return videoId ? `https://img.youtube.com/vi/${videoId[1]}/mqdefault.jpg` : null;
                                };

                                const thumbnailUrl = getYouTubeThumbnail(sermon.youtube_url);

                                return (
                            <div key={sermon.id} className="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow overflow-hidden group">
                                <div className="md:flex">
                                    {/* Sermon Thumbnail */}
                                    <a 
                                        href={`/sermons/${sermon.slug}`}
                                        className="md:w-80 block relative overflow-hidden"
                                    >
                                        <div className="relative pt-[56.25%] md:pt-0 md:h-full bg-gradient-to-br from-indigo-500 to-purple-600">
                                            {thumbnailUrl ? (
                                                <>
                                                    <img 
                                                        src={thumbnailUrl}
                                                        alt={sermon.title}
                                                        className="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                        onError={(e) => {
                                                            e.target.style.display = 'none';
                                                        }}
                                                    />
                                                    {/* Play button overlay */}
                                                    <div className="absolute inset-0 flex items-center justify-center bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300">
                                                        <div className="w-16 h-16 bg-white bg-opacity-90 rounded-full flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                                                            <svg className="w-8 h-8 text-indigo-600 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z" />
                                                            </svg>
                                                        </div>
                                                    </div>
                                                </>
                                            ) : (
                                                <div className="absolute inset-0 flex items-center justify-center">
                                                    <svg className="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            )}
                                        </div>
                                    </a>

                                    {/* Sermon Details */}
                                    <div className="p-6 flex-1">
                                        <div className="flex flex-wrap items-center gap-2 mb-3">
                                            <span className="text-sm text-gray-500">{formatDate(sermon.date_preached)}</span>
                                        </div>

                                        <a href={`/sermons/${sermon.slug}`}>
                                            <h3 className="text-2xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">
                                                {sermon.title}
                                            </h3>
                                        </a>
                                        
                                        <div className="flex items-center text-gray-600 mb-3">
                                            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            <span>{sermon.speaker_name}</span>
                                        </div>

                                        {sermon.description && (
                                            <p className="text-gray-600 mb-4 line-clamp-2">{sermon.description}</p>
                                        )}

                                        <div className="flex flex-wrap gap-3">
                                            <a 
                                                href={`/sermons/${sermon.slug}`}
                                                className="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors"
                                            >
                                                <svg className="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clipRule="evenodd" />
                                                </svg>
                                                Watch Now
                                            </a>
                                            {sermon.youtube_url && (
                                                <a 
                                                    href={sermon.youtube_url}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors"
                                                >
                                                    <svg className="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                    </svg>
                                                    YouTube
                                                </a>
                                            )}
                                            {sermon.audio_url && (
                                                <a 
                                                    href={sermon.audio_url}
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    className="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors"
                                                >
                                                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                                    </svg>
                                                    Audio
                                                </a>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            )})}
                        </div>

                        {/* Load More Button */}
                        {hasMore && (
                            <div className="text-center mt-12">
                                <button
                                    onClick={loadMore}
                                    className="inline-flex items-center px-8 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors font-medium"
                                >
                                    Load More Sermons
                                    <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <p className="text-gray-500 mt-3">
                                    Showing {sermons.data.length} of {sermons.total} sermons
                                </p>
                            </div>
                        )}
                    </>
                ) : (
                    <div className="text-center py-12 bg-white rounded-lg shadow-md">
                        <svg className="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <p className="text-gray-500 text-lg">No sermons available yet.</p>
                        <p className="text-gray-400 mt-2">Check back soon for new messages!</p>
                    </div>
                )}
            </div>

            {/* Sermon Series */}
            <div className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl font-bold text-gray-900 mb-8 text-center">Current Sermon Series</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div className="bg-white rounded-lg shadow-md overflow-hidden">
                            <div className="h-48 bg-gradient-to-br from-indigo-500 to-purple-600"></div>
                            <div className="p-6">
                                <h3 className="text-xl font-bold text-gray-900 mb-2">Faith Series</h3>
                                <p className="text-gray-600 mb-4">
                                    A deep dive into what it means to live by faith in every area of life.
                                </p>
                                <button className="text-indigo-600 hover:text-indigo-800 font-medium">
                                    View Series →
                                </button>
                            </div>
                        </div>

                        <div className="bg-white rounded-lg shadow-md overflow-hidden">
                            <div className="h-48 bg-gradient-to-br from-blue-500 to-cyan-600"></div>
                            <div className="p-6">
                                <h3 className="text-xl font-bold text-gray-900 mb-2">Prayer Life</h3>
                                <p className="text-gray-600 mb-4">
                                    Learning to develop a powerful and consistent prayer life.
                                </p>
                                <button className="text-indigo-600 hover:text-indigo-800 font-medium">
                                    View Series →
                                </button>
                            </div>
                        </div>

                        <div className="bg-white rounded-lg shadow-md overflow-hidden">
                            <div className="h-48 bg-gradient-to-br from-pink-500 to-orange-600"></div>
                            <div className="p-6">
                                <h3 className="text-xl font-bold text-gray-900 mb-2">Living the Gospel</h3>
                                <p className="text-gray-600 mb-4">
                                    Practical ways to live out the Gospel in our daily lives.
                                </p>
                                <button className="text-indigo-600 hover:text-indigo-800 font-medium">
                                    View Series →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Subscribe Section */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="bg-indigo-600 rounded-2xl shadow-xl overflow-hidden">
                    <div className="px-6 py-12 sm:px-12 sm:py-16 text-center">
                        <h2 className="text-3xl font-bold text-white mb-4">
                            Never Miss a Sermon
                        </h2>
                        <p className="text-lg text-indigo-100 mb-8 max-w-2xl mx-auto">
                            Subscribe to our podcast or YouTube channel to get notified when new sermons are available.
                        </p>
                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <button className="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50">
                                Subscribe on YouTube
                            </button>
                            <button className="inline-flex items-center justify-center px-8 py-3 border-2 border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-indigo-600 transition-all">
                                Listen on Podcast
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
