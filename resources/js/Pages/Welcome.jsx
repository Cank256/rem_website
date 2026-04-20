import Layout from '@/Components/Layout';
import SermonCard from '@/Components/SermonCard';
import EventCard from '@/Components/EventCard';
import HeroSlider from '@/Components/HeroSlider';
import { Head, Link } from '@inertiajs/react';

export default function Welcome({ recentSermons, upcomingEvents }) {
    return (
        <Layout>
            <Head title="Welcome" />

            {/* Hero Slider Section - Full Viewport */}
            <HeroSlider />

            {/* Mission & Values Section */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="text-center mb-12">
                    <h2 className="text-3xl font-bold text-gray-900 mb-4">Our Mission</h2>
                    <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                        To proclaim the Gospel of Jesus Christ, make disciples, and serve rural communities 
                        through biblical teaching, compassionate outreach, and faithful ministry.
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                    <div className="text-center p-6 bg-white rounded-lg shadow-md">
                        <div className="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Biblical Teaching</h3>
                        <p className="text-gray-600">
                            Grounded in Scripture, we teach the Word of God with clarity and conviction, 
                            equipping believers to grow in faith and knowledge.
                        </p>
                    </div>

                    <div className="text-center p-6 bg-white rounded-lg shadow-md">
                        <div className="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Community Outreach</h3>
                        <p className="text-gray-600">
                            Serving rural communities with practical help, compassionate care, and the hope 
                            of the Gospel through various ministry programs.
                        </p>
                    </div>

                    <div className="text-center p-6 bg-white rounded-lg shadow-md">
                        <div className="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Faithful Worship</h3>
                        <p className="text-gray-600">
                            Gathering together to worship God in spirit and truth, celebrating His goodness 
                            and growing in fellowship with one another.
                        </p>
                    </div>
                </div>
            </div>

            {/* Recent Sermons Section */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="flex justify-between items-center mb-8">
                    <div>
                        <h2 className="text-3xl font-bold text-gray-900">Recent Sermons</h2>
                        <p className="text-gray-600 mt-2">Watch or listen to our latest messages</p>
                    </div>
                    <Link
                        href="/sermons"
                        className="text-indigo-600 hover:text-indigo-800 font-medium"
                    >
                        View All →
                    </Link>
                </div>

                {recentSermons && recentSermons.length > 0 ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        {recentSermons.map((sermon) => (
                            <SermonCard key={sermon.id} sermon={sermon} />
                        ))}
                    </div>
                ) : (
                    <div className="text-center py-12 bg-gray-50 rounded-lg">
                        <p className="text-gray-500">No sermons available yet.</p>
                    </div>
                )}
            </div>

            {/* Upcoming Events Section */}
            <div className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center mb-8">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900">Upcoming Events</h2>
                            <p className="text-gray-600 mt-2">Join us for these upcoming gatherings</p>
                        </div>
                        <Link
                            href="/events"
                            className="text-indigo-600 hover:text-indigo-800 font-medium"
                        >
                            View All →
                        </Link>
                    </div>

                    {upcomingEvents && upcomingEvents.length > 0 ? (
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {upcomingEvents.map((event) => (
                                <EventCard key={event.id} event={event} />
                            ))}
                        </div>
                    ) : (
                        <div className="text-center py-12 bg-white rounded-lg">
                            <p className="text-gray-500">No upcoming events scheduled.</p>
                        </div>
                    )}
                </div>
            </div>

            {/* About Section */}
            <div className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="lg:grid lg:grid-cols-2 lg:gap-12 items-center">
                        <div>
                            <h2 className="text-3xl font-bold text-gray-900 mb-6">
                                About Rural Evangelical Ministries
                            </h2>
                            <div className="prose prose-lg text-gray-600 space-y-4">
                                <p>
                                    Rural Evangelical Ministries is dedicated to bringing the transforming power 
                                    of the Gospel to rural communities. We believe that every person, regardless 
                                    of location, deserves to hear the good news of Jesus Christ and experience 
                                    the love of a caring church family.
                                </p>
                                <p>
                                    Our ministry focuses on biblical preaching, discipleship, and practical service. 
                                    We are committed to building strong, Christ-centered communities where believers 
                                    can grow in their faith and reach out to their neighbors with the love of Christ.
                                </p>
                                <p>
                                    Whether you're a long-time believer or just beginning to explore faith, 
                                    we welcome you to join us as we worship God, study His Word, and serve together.
                                </p>
                            </div>
                        </div>
                        <div className="mt-10 lg:mt-0">
                            <div className="bg-white rounded-lg shadow-lg p-8">
                                <h3 className="text-2xl font-bold text-gray-900 mb-6">What We Believe</h3>
                                <ul className="space-y-4">
                                    <li className="flex items-start">
                                        <svg className="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                        </svg>
                                        <span className="text-gray-700">The Bible is the inspired, inerrant Word of God</span>
                                    </li>
                                    <li className="flex items-start">
                                        <svg className="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                        </svg>
                                        <span className="text-gray-700">Salvation through faith in Jesus Christ alone</span>
                                    </li>
                                    <li className="flex items-start">
                                        <svg className="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                        </svg>
                                        <span className="text-gray-700">The Great Commission to make disciples of all nations</span>
                                    </li>
                                    <li className="flex items-start">
                                        <svg className="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                        </svg>
                                        <span className="text-gray-700">The importance of the local church in God's plan</span>
                                    </li>
                                    <li className="flex items-start">
                                        <svg className="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                        </svg>
                                        <span className="text-gray-700">Serving others as an expression of Christ's love</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Call to Action Section */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="bg-indigo-600 rounded-2xl shadow-xl overflow-hidden">
                    <div className="px-6 py-12 sm:px-12 sm:py-16 lg:flex lg:items-center lg:justify-between">
                        <div>
                            <h2 className="text-3xl font-bold text-white">
                                New to Rural Evangelical Ministries?
                            </h2>
                            <p className="mt-3 text-lg text-indigo-100">
                                We'd love to connect with you and help you get started on your spiritual journey. 
                                Come as you are and experience the warmth of our church family.
                            </p>
                        </div>
                        <div className="mt-8 lg:mt-0 lg:flex-shrink-0">
                            <Link
                                href="/contact"
                                className="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50"
                            >
                                Get in Touch
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
