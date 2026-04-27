import Layout from '@/Components/Layout';
import { Head } from '@inertiajs/react';

export default function Live({ liveStream }) {
    const isLive = liveStream?.is_live;

    return (
        <Layout>
            <Head title="Live Stream" />

            {/* Hero Section */}
            <div className="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">Watch Live</h1>
                    <p className="text-xl text-indigo-100">Join us for live worship services and special events</p>
                </div>
            </div>

            {/* Live Stream Section */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="bg-gray-900 rounded-lg overflow-hidden shadow-2xl mb-12">
                    {isLive && liveStream?.embed_url ? (
                        // Live Stream Active
                        <div className="relative">
                            <div className="aspect-video">
                                <iframe
                                    src={liveStream.embed_url}
                                    title={liveStream.title || 'Live Stream'}
                                    className="w-full h-full"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowFullScreen
                                ></iframe>
                            </div>
                            <div className="bg-gray-800 p-6">
                                <div className="flex items-center justify-between">
                                    <div>
                                        <h2 className="text-2xl font-bold text-white mb-2">
                                            {liveStream.title || 'Live Stream'}
                                        </h2>
                                        {liveStream.description && (
                                            <p className="text-gray-300">{liveStream.description}</p>
                                        )}
                                        {liveStream.stream_started_at && (
                                            <p className="text-gray-400 text-sm mt-2">
                                                Started at {liveStream.stream_started_at}
                                            </p>
                                        )}
                                    </div>
                                    <div className="inline-flex items-center px-4 py-2 bg-red-600 rounded-full">
                                        <span className="w-3 h-3 bg-white rounded-full mr-2 animate-pulse"></span>
                                        <span className="text-sm font-medium text-white">LIVE</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ) : (
                        // Stream Offline
                        <div className="aspect-video bg-gray-800 flex items-center justify-center">
                            <div className="text-center text-white p-8">
                                <svg className="w-24 h-24 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                                <h2 className="text-2xl font-bold mb-2">Stream Currently Offline</h2>
                                <p className="text-gray-400 mb-6">
                                    We're not currently live. Check back during our service times or watch previous sermons.
                                </p>
                                <div className="inline-flex items-center px-4 py-2 bg-gray-700 rounded-full">
                                    <span className="w-3 h-3 bg-gray-500 rounded-full mr-2"></span>
                                    <span className="text-sm font-medium">Offline</span>
                                </div>
                            </div>
                        </div>
                    )}
                </div>

                {/* Service Times */}
                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <div className="bg-white rounded-lg shadow-md p-8">
                        <h3 className="text-2xl font-bold text-gray-900 mb-4">Service Times</h3>
                        <div className="space-y-4">
                            <div>
                                <h4 className="font-semibold text-gray-900 text-lg mb-3">Sunday Services</h4>
                                <div className="space-y-2 ml-4">
                                    <div className="flex items-start">
                                        <svg className="w-5 h-5 text-indigo-600 mr-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p className="font-medium text-gray-900">First Service</p>
                                            <p className="text-gray-600">7:30 AM - 9:30 AM</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start">
                                        <svg className="w-5 h-5 text-indigo-600 mr-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p className="font-medium text-gray-900">Second Service</p>
                                            <p className="text-gray-600">9:30 AM - 11:30 AM</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start">
                                        <svg className="w-5 h-5 text-indigo-600 mr-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p className="font-medium text-gray-900">Third Service</p>
                                            <p className="text-gray-600">11:30 AM - 2:00 PM</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start">
                                        <svg className="w-5 h-5 text-indigo-600 mr-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p className="font-medium text-gray-900">Fourth Service</p>
                                            <p className="text-gray-600">2:00 PM - 5:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div className="pt-4 border-t border-gray-200">
                                <div className="flex items-start">
                                    <svg className="w-5 h-5 text-indigo-600 mr-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <h4 className="font-semibold text-gray-900">Weekly Evening Services</h4>
                                        <p className="text-gray-600">Monday - Friday: 5:30 PM - 8:00 PM</p>
                                    </div>
                                </div>
                            </div>

                            <div className="pt-4 border-t border-gray-200">
                                <div className="flex items-start">
                                    <svg className="w-5 h-5 text-indigo-600 mr-2 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <h4 className="font-semibold text-gray-900">General Overnight Prayers</h4>
                                        <p className="text-gray-600">5:30 PM till late</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-lg shadow-md p-8">
                        <h3 className="text-2xl font-bold text-gray-900 mb-4">How to Watch</h3>
                        <div className="space-y-4">
                            <div className="flex items-start">
                                <svg className="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                </svg>
                                <div>
                                    <h4 className="font-semibold text-gray-900">Visit This Page</h4>
                                    <p className="text-gray-600">Come back during service times to watch live</p>
                                </div>
                            </div>
                            <div className="flex items-start">
                                <svg className="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                </svg>
                                <div>
                                    <h4 className="font-semibold text-gray-900">YouTube Channel</h4>
                                    <p className="text-gray-600">Subscribe to get notifications when we go live</p>
                                </div>
                            </div>
                            <div className="flex items-start">
                                <svg className="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                </svg>
                                <div>
                                    <h4 className="font-semibold text-gray-900">Facebook Live</h4>
                                    <p className="text-gray-600">Follow us on Facebook for live streams</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Features */}
                <div className="bg-gray-50 rounded-lg p-8">
                    <h3 className="text-2xl font-bold text-gray-900 mb-6 text-center">Live Stream Features</h3>
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div className="text-center">
                            <div className="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <h4 className="font-semibold text-gray-900 mb-2">HD Quality</h4>
                            <p className="text-gray-600">Crystal clear video and audio streaming</p>
                        </div>
                        <div className="text-center">
                            <div className="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h4 className="font-semibold text-gray-900 mb-2">Live Chat</h4>
                            <p className="text-gray-600">Interact with other viewers during the service</p>
                        </div>
                        <div className="text-center">
                            <div className="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h4 className="font-semibold text-gray-900 mb-2">On-Demand</h4>
                            <p className="text-gray-600">Watch recordings anytime after the service</p>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
