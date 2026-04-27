import ReactPlayer from 'react-player';
import { Link } from '@inertiajs/react';

export default function SermonCard({ sermon }) {
    const formatDate = (date) => {
        return new Date(date).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    return (
        <div className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
            {/* Media Player */}
            {sermon.youtube_url && (
                <div className="relative pt-[56.25%]">
                    <div className="absolute top-0 left-0 w-full h-full">
                        <ReactPlayer
                            url={sermon.youtube_url}
                            width="100%"
                            height="100%"
                            controls
                            light
                        />
                    </div>
                </div>
            )}

            {/* Audio Player (if no YouTube video) */}
            {!sermon.youtube_url && sermon.audio_url && (
                <div className="p-4 bg-gray-100">
                    <ReactPlayer
                        url={sermon.audio_url}
                        width="100%"
                        height="50px"
                        controls
                        config={{
                            file: {
                                attributes: {
                                    controlsList: 'nodownload'
                                }
                            }
                        }}
                    />
                </div>
            )}

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

                <h3 className="text-xl font-bold text-gray-900 mb-3 hover:text-indigo-600">
                    <Link href={`/sermons/${sermon.slug}`}>
                        {sermon.title}
                    </Link>
                </h3>

                {sermon.description && (
                    <p className="text-gray-600 mb-4 line-clamp-3">
                        {sermon.description}
                    </p>
                )}

                <div className="flex items-center justify-between">
                    <Link
                        href={`/sermons/${sermon.slug}`}
                        className="text-indigo-600 hover:text-indigo-800 font-medium text-sm"
                    >
                        Read More →
                    </Link>

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
        </div>
    );
}
