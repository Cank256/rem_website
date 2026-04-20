import Layout from '@/Components/Layout';
import { Head } from '@inertiajs/react';

export default function Give() {
    return (
        <Layout>
            <Head title="Give" />

            {/* Hero Section */}
            <div className="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">Give</h1>
                    <p className="text-xl text-indigo-100">Supporting God's work in rural communities</p>
                </div>
            </div>

            {/* Why Give Section */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="text-center mb-12">
                    <h2 className="text-3xl font-bold text-gray-900 mb-4">Why Give?</h2>
                    <p className="text-xl text-gray-600 max-w-3xl mx-auto">
                        Your generous giving helps us spread the Gospel, serve our community, and support 
                        ministries that are making a real difference in rural areas.
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <div className="bg-white rounded-lg shadow-md p-6 text-center">
                        <div className="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Ministry Support</h3>
                        <p className="text-gray-600">
                            Fund biblical teaching, worship services, and discipleship programs
                        </p>
                    </div>

                    <div className="bg-white rounded-lg shadow-md p-6 text-center">
                        <div className="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Community Outreach</h3>
                        <p className="text-gray-600">
                            Support programs that serve and care for those in need
                        </p>
                    </div>

                    <div className="bg-white rounded-lg shadow-md p-6 text-center">
                        <div className="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg className="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 className="text-xl font-semibold text-gray-900 mb-2">Missions</h3>
                        <p className="text-gray-600">
                            Extend the Gospel reach to rural communities near and far
                        </p>
                    </div>
                </div>
            </div>

            {/* Ways to Give */}
            <div className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl font-bold text-gray-900 mb-12 text-center">Ways to Give</h2>
                    
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        {/* Online Giving */}
                        <div className="bg-white rounded-lg shadow-lg p-8">
                            <div className="flex items-center mb-6">
                                <div className="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                                    <svg className="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 className="text-2xl font-bold text-gray-900">Online Giving</h3>
                            </div>
                            <p className="text-gray-600 mb-6">
                                Give securely online using your credit card, debit card, or bank account. 
                                Set up one-time or recurring donations.
                            </p>
                            <button className="w-full bg-indigo-600 text-white px-6 py-3 rounded-md font-medium hover:bg-indigo-700 transition-colors">
                                Give Online Now
                            </button>
                        </div>

                        {/* Mobile Money */}
                        <div className="bg-white rounded-lg shadow-lg p-8">
                            <div className="flex items-center mb-6">
                                <div className="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                                    <svg className="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 className="text-2xl font-bold text-gray-900">Mobile Money</h3>
                            </div>
                            <p className="text-gray-600 mb-6">
                                Give conveniently using MTN Mobile Money or Airtel Money. 
                                Send your donation directly from your mobile phone.
                            </p>
                            
                            {/* MTN Mobile Money */}
                            <div className="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-4 mb-4">
                                <div className="flex items-center mb-3">
                                    <div className="w-10 h-10 bg-yellow-400 rounded-full flex items-center justify-center mr-3">
                                        <span className="text-white font-bold text-sm">MTN</span>
                                    </div>
                                    <h4 className="font-bold text-gray-900">MTN Mobile Money</h4>
                                </div>
                                <div className="space-y-2">
                                    <p className="text-sm text-gray-700">
                                        <span className="font-semibold">Number:</span> 0772 XXX XXX
                                    </p>
                                    <p className="text-sm text-gray-700">
                                        <span className="font-semibold">Name:</span> Rural Evangelical Ministries
                                    </p>
                                </div>
                            </div>

                            {/* Airtel Money */}
                            <div className="bg-red-50 border-2 border-red-400 rounded-lg p-4">
                                <div className="flex items-center mb-3">
                                    <div className="w-10 h-10 bg-red-500 rounded-full flex items-center justify-center mr-3">
                                        <span className="text-white font-bold text-xs">Airtel</span>
                                    </div>
                                    <h4 className="font-bold text-gray-900">Airtel Money</h4>
                                </div>
                                <div className="space-y-2">
                                    <p className="text-sm text-gray-700">
                                        <span className="font-semibold">Number:</span> 0752 XXX XXX
                                    </p>
                                    <p className="text-sm text-gray-700">
                                        <span className="font-semibold">Name:</span> Rural Evangelical Ministries
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Mail */}
                        <div className="bg-white rounded-lg shadow-lg p-8">
                            <div className="flex items-center mb-6">
                                <div className="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                                    <svg className="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 className="text-2xl font-bold text-gray-900">Mail a Check</h3>
                            </div>
                            <p className="text-gray-600 mb-4">
                                Mail your check or money order to:
                            </p>
                            <div className="bg-gray-100 rounded-md p-4">
                                <p className="font-semibold text-gray-900">Rural Evangelical Ministries</p>
                                <p className="text-gray-600">Attn: Giving</p>
                                <p className="text-gray-600">Contact us for mailing address</p>
                            </div>
                        </div>

                        {/* In Person */}
                        <div className="bg-white rounded-lg shadow-lg p-8">
                            <div className="flex items-center mb-6">
                                <div className="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                                    <svg className="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                    </svg>
                                </div>
                                <h3 className="text-2xl font-bold text-gray-900">Give In Person</h3>
                            </div>
                            <p className="text-gray-600 mb-4">
                                Place your offering in the collection during any of our services, or drop it off 
                                at the church office during office hours.
                            </p>
                            <p className="text-sm text-gray-500">
                                Office Hours: Monday - Friday, 9:00 AM - 5:00 PM
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Biblical Giving */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="bg-indigo-50 rounded-lg p-8 md:p-12">
                    <h2 className="text-3xl font-bold text-gray-900 mb-6 text-center">What the Bible Says About Giving</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div className="bg-white rounded-lg p-6">
                            <p className="text-gray-700 italic mb-3">
                                "Each of you should give what you have decided in your heart to give, not reluctantly 
                                or under compulsion, for God loves a cheerful giver."
                            </p>
                            <p className="text-indigo-600 font-semibold">2 Corinthians 9:7</p>
                        </div>
                        <div className="bg-white rounded-lg p-6">
                            <p className="text-gray-700 italic mb-3">
                                "Honor the LORD with your wealth, with the firstfruits of all your crops."
                            </p>
                            <p className="text-indigo-600 font-semibold">Proverbs 3:9</p>
                        </div>
                    </div>
                </div>
            </div>

            {/* FAQ */}
            <div className="bg-gray-50 py-16">
                <div className="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl font-bold text-gray-900 mb-8 text-center">Frequently Asked Questions</h2>
                    <div className="space-y-6">
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">Is my donation tax-deductible?</h3>
                            <p className="text-gray-600">
                                Yes! Rural Evangelical Ministries is a registered 501(c)(3) organization. 
                                You will receive a tax receipt for your donation.
                            </p>
                        </div>
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">Is online giving secure?</h3>
                            <p className="text-gray-600">
                                Absolutely. We use industry-standard encryption and security measures to protect 
                                your personal and financial information.
                            </p>
                        </div>
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <h3 className="text-lg font-semibold text-gray-900 mb-2">Can I designate my gift?</h3>
                            <p className="text-gray-600">
                                Yes, you can designate your gift to specific ministries or projects. 
                                Please indicate your preference when giving.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
