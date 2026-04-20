import Layout from '@/Components/Layout';
import { Head, Link } from '@inertiajs/react';

export default function Ministries() {
    const ministries = [
        {
            title: 'Children\'s Ministry',
            description: 'Nurturing young hearts with biblical truth through engaging lessons, activities, and loving care.',
            icon: (
                <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            )
        },
        {
            title: 'Youth Ministry',
            description: 'Empowering teenagers to grow in faith, build godly friendships, and discover their purpose in Christ.',
            icon: (
                <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            )
        },
        {
            title: 'Women\'s Ministry',
            description: 'Creating a supportive community where women can grow spiritually, build relationships, and serve together.',
            icon: (
                <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            )
        },
        {
            title: 'Men\'s Ministry',
            description: 'Equipping men to be godly leaders in their homes, churches, and communities through fellowship and discipleship.',
            icon: (
                <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            )
        },
        {
            title: 'Worship Ministry',
            description: 'Leading our congregation in heartfelt worship through music, prayer, and creative expression.',
            icon: (
                <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" />
                </svg>
            )
        },
        {
            title: 'Community Outreach',
            description: 'Serving our rural communities with practical help, compassion, and the hope of the Gospel.',
            icon: (
                <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            )
        },
        {
            title: 'Prayer Ministry',
            description: 'Interceding for our church, community, and world through dedicated prayer and spiritual warfare.',
            icon: (
                <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            )
        },
        {
            title: 'Missions',
            description: 'Supporting local and global missions to spread the Gospel and serve those in need around the world.',
            icon: (
                <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            )
        },
        {
            title: 'Small Groups',
            description: 'Building deeper relationships and spiritual growth through Bible study and fellowship in small group settings.',
            icon: (
                <svg className="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            )
        }
    ];

    return (
        <Layout>
            <Head title="Ministries" />

            {/* Hero Section */}
            <div className="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">Our Ministries</h1>
                    <p className="text-xl text-indigo-100">Serving God and our community through diverse ministry opportunities</p>
                </div>
            </div>

            {/* Ministries Grid */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="text-center mb-12">
                    <h2 className="text-3xl font-bold text-gray-900 mb-4">Get Involved</h2>
                    <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                        Whether you're looking to serve, grow, or connect, we have a ministry for you. 
                        Join us in making a difference in our rural communities.
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    {ministries.map((ministry, index) => (
                        <div key={index} className="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition-shadow">
                            <div className="text-indigo-600 mb-4">
                                {ministry.icon}
                            </div>
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">{ministry.title}</h3>
                            <p className="text-gray-600 mb-4">{ministry.description}</p>
                            <Link
                                href="/contact"
                                className="text-indigo-600 hover:text-indigo-800 font-medium inline-flex items-center"
                            >
                                Learn More
                                <svg className="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                </svg>
                            </Link>
                        </div>
                    ))}
                </div>
            </div>

            {/* Rural Churches Impact Section */}
            <div className="bg-white py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="text-center mb-12">
                        <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                            Reaching Rural Communities
                        </h2>
                        <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                            Through God's grace and faithful partnership, we have established over 250 rural churches 
                            across Uganda, bringing the Gospel to communities that need it most.
                        </p>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                        {/* Stat 1 */}
                        <div className="text-center">
                            <div className="inline-flex items-center justify-center w-16 h-16 bg-indigo-100 rounded-full mb-4">
                                <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div className="text-4xl font-bold text-indigo-600 mb-2">250+</div>
                            <div className="text-gray-600 font-medium">Rural Churches Established</div>
                        </div>

                        {/* Stat 2 */}
                        <div className="text-center">
                            <div className="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                                <svg className="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div className="text-4xl font-bold text-green-600 mb-2">50,000+</div>
                            <div className="text-gray-600 font-medium">Lives Transformed</div>
                        </div>

                        {/* Stat 3 */}
                        <div className="text-center">
                            <div className="inline-flex items-center justify-center w-16 h-16 bg-purple-100 rounded-full mb-4">
                                <svg className="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div className="text-4xl font-bold text-purple-600 mb-2">100+</div>
                            <div className="text-gray-600 font-medium">Rural Districts Reached</div>
                        </div>
                    </div>

                    <div className="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-2xl p-8 md:p-12">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                            <div>
                                <h3 className="text-2xl font-bold text-gray-900 mb-4">Our Church Planting Mission</h3>
                                <p className="text-gray-700 mb-4">
                                    Since our founding, Rural Evangelical Ministries has been committed to establishing 
                                    vibrant, self-sustaining churches in rural communities across Uganda. Each church 
                                    serves as a beacon of hope, providing spiritual guidance, community support, and 
                                    practical assistance to those in need.
                                </p>
                                <p className="text-gray-700 mb-6">
                                    Our church planting strategy focuses on training local leaders, empowering communities, 
                                    and ensuring long-term sustainability. We don't just build churches; we build movements 
                                    that transform entire regions with the Gospel.
                                </p>
                                <Link
                                    href="/contact"
                                    className="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-semibold"
                                >
                                    Partner With Us
                                    <svg className="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                                    </svg>
                                </Link>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="bg-white rounded-lg p-6 shadow-md">
                                    <div className="text-3xl font-bold text-indigo-600 mb-2">15+</div>
                                    <div className="text-sm text-gray-600">Years of Ministry</div>
                                </div>
                                <div className="bg-white rounded-lg p-6 shadow-md">
                                    <div className="text-3xl font-bold text-green-600 mb-2">300+</div>
                                    <div className="text-sm text-gray-600">Trained Pastors</div>
                                </div>
                                <div className="bg-white rounded-lg p-6 shadow-md">
                                    <div className="text-3xl font-bold text-purple-600 mb-2">25+</div>
                                    <div className="text-sm text-gray-600">Districts Covered</div>
                                </div>
                                <div className="bg-white rounded-lg p-6 shadow-md">
                                    <div className="text-3xl font-bold text-orange-600 mb-2">1000+</div>
                                    <div className="text-sm text-gray-600">Weekly Services</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Call to Action */}
            <div className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h2 className="text-3xl font-bold text-gray-900 mb-4">Ready to Get Involved?</h2>
                    <p className="text-xl text-gray-600 mb-8 max-w-2xl mx-auto">
                        We'd love to help you find the perfect ministry to serve in. Contact us today to learn more 
                        about how you can make a difference.
                    </p>
                    <Link
                        href="/contact"
                        className="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
                    >
                        Contact Us
                    </Link>
                </div>
            </div>
        </Layout>
    );
}
