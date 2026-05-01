import { Link } from '@inertiajs/react';

export default function SermonCard({ sermon }) {
    const formatDate = (date) => {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    const getYouTubeThumbnail = (url) => {
        if (!url) return null;
        const videoId = url.match(/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/);
        return videoId ? `https://img.youtube.com/vi/${videoId[1]}/maxresdefault.jpg` : null;
    };

    const thumbnailUrl = getYouTubeThumbnail(sermon.youtube_url);

    return (
        <Link 
            href={`/sermons/${sermon.slug}`}
            className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300 block group"
        >
            {/* Thumbnail */}
            <div className="relative pt-[56.25%] bg-gradient-to-br from-indigo-500 to-purple-600 overflow-hidden">
                {thumbnailUrl ? (
                    <>
                        <img 
                            src={thumbnailUrl}
                            alt={sermon.title}
                            className="absolute top-0 left-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            onError={(e) => {
                                e.target.style.display = 'none';
                                e.target.nextElementSibling.style.display = 'flex';
                            }}
                        />
                        <div className="absolute top-0 left-0 w-full h-full bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center" style={{display: 'none'}}>
                            <svg className="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
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
                    <div className="absolute top-0 left-0 w-full h-full flex items-center justify-center">
                        <svg className="w-20 h-20 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                )}
            </div>

            {/* Content */}
            <div className="p-6">
                <div className="flex items-center justify-between mb-2">
                    <span className="text-sm text-indigo-600 font-semibold">
                        {sermon.speaker_name}
                    </span>
                    <span className="text-sm text-gray-500">
                        {formatDate(sermon.date_preached)}
                    </span>
                </div>

                <h3 className="text-xl font-bold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors">
                    {sermon.title}
                </h3>

                {sermon.description && (
                    <p className="text-gray-600 mb-4 line-clamp-3">
                        {sermon.description}
                    </p>
                )}

                <div className="flex items-center justify-between">
                    <span className="text-indigo-600 group-hover:text-indigo-800 font-medium text-sm">
                        Watch Now →
                    </span>

                    <div className="flex space-x-2">
                        {sermon.youtube_url && (
                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Video
                            </span>
                        )}
                        {sermon.audio_url && (
                            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Audio
                            </span>
                        )}
                    </div>
                </div>
            </div>
        </Link>
    );
}
