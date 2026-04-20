import { Link } from '@inertiajs/react';

export default function EventCard({ event }) {
    const formatDateTime = (datetime) => {
        return new Date(datetime).toLocaleDateString('en-US', {
            weekday: 'short',
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    return (
        <div className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
            <div className="p-6">
                <div className="flex items-start justify-between mb-4">
                    <div className="flex-1">
                        <h3 className="text-xl font-bold text-gray-900 mb-2 hover:text-indigo-600">
                            <Link href={`/events/${event.slug}`}>
                                {event.title}
                            </Link>
                        </h3>
                        
                        <div className="space-y-2 text-sm text-gray-600">
                            <div className="flex items-center">
                                <svg className="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{formatDateTime(event.start_datetime)}</span>
                            </div>
                            
                            {event.location && (
                                <div className="flex items-center">
                                    <svg className="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span>{event.location}</span>
                                </div>
                            )}
                        </div>
                    </div>
                    
                    <div className="ml-4 flex-shrink-0">
                        <div className="bg-indigo-100 rounded-lg p-3 text-center">
                            <div className="text-2xl font-bold text-indigo-600">
                                {new Date(event.start_datetime).getDate()}
                            </div>
                            <div className="text-xs text-indigo-600 uppercase">
                                {new Date(event.start_datetime).toLocaleDateString('en-US', { month: 'short' })}
                            </div>
                        </div>
                    </div>
                </div>

                <p className="text-gray-600 mb-4 line-clamp-2">
                    {event.description}
                </p>

                <Link
                    href={`/events/${event.slug}`}
                    className="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium text-sm"
                >
                    View Details →
                </Link>
            </div>
        </div>
    );
}
