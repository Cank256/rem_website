import Layout from '@/Components/Layout';
import { Head, Link, router } from '@inertiajs/react';

export default function Events({ events }) {
    const formatDate = (dateString) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
    };

    const formatTime = (startDate, endDate) => {
        const start = new Date(startDate);
        const end = new Date(endDate);
        return `${start.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit' 
        })} - ${end.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit' 
        })}`;
    };

    const loadMore = () => {
        router.get(route('events'), 
            { per_page: events.per_page + 5 }, 
            { 
                preserveState: true,
                preserveScroll: true 
            }
        );
    };

    const hasMore = events.current_page < events.last_page;

    const isOngoing = (startDate, endDate) => {
        const now = new Date();
        return new Date(startDate) <= now && new Date(endDate) >= now;
    };

    return (
        <Layout>
            <Head title="Events" />

            {/* Hero Section */}
            <div className="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">Events</h1>
                    <p className="text-xl text-indigo-100">Join us for worship, fellowship, and community events</p>
                </div>
            </div>

            {/* Events List */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="mb-8">
                    <h2 className="text-3xl font-bold text-gray-900 mb-4">Upcoming Events</h2>
                    <p className="text-gray-600">
                        Stay connected with what's happening at Rural Evangelical Ministries. 
                        We have events for all ages and interests!
                    </p>
                </div>

                {events.data && events.data.length > 0 ? (
                    <>
                        <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            {events.data.map((event) => (
                                <div key={event.id} className="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow overflow-hidden">
                                    {event.image_url && (
                                        <div className="h-48 overflow-hidden">
                                            <img 
                                                src={event.image_url} 
                                                alt={event.title}
                                                className="w-full h-full object-cover"
                                            />
                                        </div>
                                    )}
                                    <div className="p-6">
                                        <div className="flex items-center gap-2 mb-3">
                                            {isOngoing(event.start_datetime, event.end_datetime) && (
                                                <span className="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <span className="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                                                    Happening Now
                                                </span>
                                            )}
                                        </div>
                                        <h3 className="text-2xl font-bold text-gray-900 mb-4">{event.title}</h3>

                                        <div className="space-y-2 mb-4">
                                            <div className="flex items-center text-gray-600">
                                                <svg className="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <span>{formatDate(event.start_datetime)}</span>
                                            </div>
                                            <div className="flex items-center text-gray-600">
                                                <svg className="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span>{formatTime(event.start_datetime, event.end_datetime)}</span>
                                            </div>
                                            {event.location && (
                                                <div className="flex items-center text-gray-600">
                                                    <svg className="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    <span>{event.location}</span>
                                                </div>
                                            )}
                                        </div>

                                        {event.description && (
                                            <p className="text-gray-600 mb-4 line-clamp-3">{event.description}</p>
                                        )}

                                        <div className="flex gap-3">
                                            <Link
                                                href={route('events.show', event.slug)}
                                                className="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors font-medium text-center"
                                            >
                                                Learn More
                                            </Link>
                                            <button className="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition-colors">
                                                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>

                        {/* Load More Button */}
                        {hasMore && (
                            <div className="text-center mt-12">
                                <button
                                    onClick={loadMore}
                                    className="inline-flex items-center px-8 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors font-medium"
                                >
                                    Load More Events
                                    <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <p className="text-gray-500 mt-3">
                                    Showing {events.data.length} of {events.total} events
                                </p>
                            </div>
                        )}
                    </>
                ) : (
                    <div className="text-center py-12 bg-white rounded-lg shadow-md">
                        <svg className="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p className="text-gray-500 text-lg">No upcoming events at the moment.</p>
                        <p className="text-gray-400 mt-2">Check back soon for new events!</p>
                    </div>
                )}
            </div>

            {/* Calendar Section */}
            <div className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Event Calendar</h2>
                        <p className="text-gray-600">
                            View our full calendar to see all upcoming events and activities
                        </p>
                    </div>

                    <div className="bg-white rounded-lg shadow-lg p-8 text-center">
                        <svg className="w-24 h-24 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Full Calendar Coming Soon</h3>
                        <p className="text-gray-600 mb-6">
                            We're working on an interactive calendar to help you stay up to date with all our events.
                        </p>
                        <button className="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Subscribe to Calendar
                        </button>
                    </div>
                </div>
            </div>

            {/* CTA Section */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="bg-indigo-600 rounded-2xl shadow-xl overflow-hidden">
                    <div className="px-6 py-12 sm:px-12 sm:py-16 lg:flex lg:items-center lg:justify-between">
                        <div>
                            <h2 className="text-3xl font-bold text-white">
                                Want to Host an Event?
                            </h2>
                            <p className="mt-3 text-lg text-indigo-100">
                                Have an idea for a ministry event or community gathering? We'd love to hear from you!
                            </p>
                        </div>
                        <div className="mt-8 lg:mt-0 lg:flex-shrink-0">
                            <a
                                href="/contact"
                                className="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50"
                            >
                                Contact Us
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
