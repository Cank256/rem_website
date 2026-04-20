import Layout from '@/Components/Layout';
import { Head, Link } from '@inertiajs/react';

export default function EventDetail({ event }) {
    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
    };

    const formatTime = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit' 
        });
    };

    const isOngoing = () => {
        const now = new Date();
        return new Date(event.start_datetime) <= now && new Date(event.end_datetime) >= now;
    };

    const isPast = () => {
        return new Date(event.end_datetime) < new Date();
    };

    return (
        <Layout>
            <Head title={event.title} />

            {/* Hero Section with Image */}
            {event.image_url ? (
                <div className="relative h-96 bg-gray-900">
                    <img 
                        src={event.image_url} 
                        alt={event.title}
                        className="w-full h-full object-cover opacity-70"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div className="absolute bottom-0 left-0 right-0 p-8">
                        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                            {isOngoing() && (
                                <span className="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-500 text-white mb-4">
                                    <span className="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                                    Happening Now
                                </span>
                            )}
                            <h1 className="text-4xl md:text-5xl font-bold text-white mb-2">{event.title}</h1>
                            <p className="text-xl text-gray-200">{formatDate(event.start_datetime)}</p>
                        </div>
                    </div>
                </div>
            ) : (
                <div className="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {isOngoing() && (
                            <span className="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-500 text-white mb-4">
                                <span className="w-2 h-2 bg-white rounded-full mr-2 animate-pulse"></span>
                                Happening Now
                            </span>
                        )}
                        <h1 className="text-4xl md:text-5xl font-bold mb-4">{event.title}</h1>
                        <p className="text-xl text-indigo-100">{formatDate(event.start_datetime)}</p>
                    </div>
                </div>
            )}

            {/* Event Details */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {/* Main Content */}
                    <div className="lg:col-span-2">
                        <div className="bg-white rounded-lg shadow-md p-8">
                            <h2 className="text-2xl font-bold text-gray-900 mb-6">About This Event</h2>
                            <div className="prose max-w-none text-gray-700 whitespace-pre-line">
                                {event.description}
                            </div>
                        </div>
                    </div>

                    {/* Sidebar */}
                    <div className="lg:col-span-1">
                        <div className="bg-white rounded-lg shadow-md p-6 sticky top-4">
                            <h3 className="text-xl font-bold text-gray-900 mb-4">Event Details</h3>
                            
                            <div className="space-y-4">
                                {/* Date */}
                                <div className="flex items-start">
                                    <div className="flex-shrink-0">
                                        <svg className="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div className="ml-3">
                                        <p className="text-sm font-medium text-gray-900">Date</p>
                                        <p className="text-sm text-gray-600">{formatDate(event.start_datetime)}</p>
                                    </div>
                                </div>

                                {/* Time */}
                                <div className="flex items-start">
                                    <div className="flex-shrink-0">
                                        <svg className="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div className="ml-3">
                                        <p className="text-sm font-medium text-gray-900">Time</p>
                                        <p className="text-sm text-gray-600">
                                            {formatTime(event.start_datetime)} - {formatTime(event.end_datetime)}
                                        </p>
                                    </div>
                                </div>

                                {/* Location */}
                                {event.location && (
                                    <div className="flex items-start">
                                        <div className="flex-shrink-0">
                                            <svg className="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </div>
                                        <div className="ml-3">
                                            <p className="text-sm font-medium text-gray-900">Location</p>
                                            <p className="text-sm text-gray-600">{event.location}</p>
                                        </div>
                                    </div>
                                )}
                            </div>

                            {/* Action Buttons */}
                            <div className="mt-6 space-y-3">
                                {!isPast() && (
                                    <>
                                        <button className="w-full bg-indigo-600 text-white px-4 py-3 rounded-md hover:bg-indigo-700 transition-colors font-medium">
                                            Register for Event
                                        </button>
                                        <button className="w-full border border-gray-300 text-gray-700 px-4 py-3 rounded-md hover:bg-gray-50 transition-colors font-medium flex items-center justify-center">
                                            <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Add to Calendar
                                        </button>
                                    </>
                                )}
                                <button className="w-full border border-gray-300 text-gray-700 px-4 py-3 rounded-md hover:bg-gray-50 transition-colors font-medium flex items-center justify-center">
                                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                    Share Event
                                </button>
                            </div>
                        </div>

                        {/* Back to Events */}
                        <div className="mt-6">
                            <Link
                                href={route('events')}
                                className="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium"
                            >
                                <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to All Events
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            {/* Related Events */}
            <div className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl font-bold text-gray-900 mb-8">More Upcoming Events</h2>
                    <div className="text-center py-8">
                        <Link
                            href={route('events')}
                            className="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                        >
                            View All Events
                        </Link>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
