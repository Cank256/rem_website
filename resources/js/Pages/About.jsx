import Layout from '@/Components/Layout';
import { Head } from '@inertiajs/react';

export default function About() {
    return (
        <Layout>
            <Head title="About REM" />

            {/* Hero Section */}
            <div className="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">About Rural Evangelical Ministries</h1>
                    <p className="text-xl text-indigo-100">Spreading the Gospel to rural communities since our founding</p>
                </div>
            </div>

            {/* Mission Section */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 className="text-3xl font-bold text-gray-900 mb-6">Our Mission</h2>
                        <p className="text-lg text-gray-600 mb-4">
                            Rural Evangelical Ministries exists to proclaim the Gospel of Jesus Christ, make disciples, 
                            and serve rural communities through biblical teaching, compassionate outreach, and faithful ministry.
                        </p>
                        <p className="text-lg text-gray-600 mb-4">
                            We believe that every person, regardless of their location, deserves to hear the good news 
                            of Jesus Christ and experience the love of a caring church family.
                        </p>
                        <p className="text-lg text-gray-600">
                            Our ministry is committed to building strong, Christ-centered communities where believers 
                            can grow in their faith and reach out to their neighbors with the transforming love of Christ.
                        </p>
                    </div>
                    <div className="bg-gray-100 rounded-lg p-8">
                        <h3 className="text-2xl font-bold text-gray-900 mb-6">Our Core Values</h3>
                        <ul className="space-y-4">
                            <li className="flex items-start">
                                <svg className="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                </svg>
                                <div>
                                    <h4 className="font-semibold text-gray-900">Biblical Authority</h4>
                                    <p className="text-gray-600">The Bible is our final authority for faith and practice</p>
                                </div>
                            </li>
                            <li className="flex items-start">
                                <svg className="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                </svg>
                                <div>
                                    <h4 className="font-semibold text-gray-900">Gospel-Centered</h4>
                                    <p className="text-gray-600">Everything we do flows from the good news of Jesus</p>
                                </div>
                            </li>
                            <li className="flex items-start">
                                <svg className="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                </svg>
                                <div>
                                    <h4 className="font-semibold text-gray-900">Community Focus</h4>
                                    <p className="text-gray-600">Committed to serving and reaching rural communities</p>
                                </div>
                            </li>
                            <li className="flex items-start">
                                <svg className="w-6 h-6 text-indigo-600 mr-3 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                </svg>
                                <div>
                                    <h4 className="font-semibold text-gray-900">Authentic Relationships</h4>
                                    <p className="text-gray-600">Building genuine community through love and fellowship</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {/* What We Believe Section */}
            <div className="bg-gray-50 py-16">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 className="text-3xl font-bold text-gray-900 mb-12 text-center">What We Believe</h2>
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">The Bible</h3>
                            <p className="text-gray-600">
                                We believe the Bible is the inspired, inerrant Word of God and our final authority 
                                for all matters of faith and practice.
                            </p>
                        </div>
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">The Gospel</h3>
                            <p className="text-gray-600">
                                Salvation is by grace alone, through faith alone, in Christ alone. Jesus died for 
                                our sins and rose again, offering eternal life to all who believe.
                            </p>
                        </div>
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">The Church</h3>
                            <p className="text-gray-600">
                                The church is the body of Christ, called to worship God, build up believers, and 
                                reach the lost with the Gospel message.
                            </p>
                        </div>
                        <div className="bg-white rounded-lg shadow-md p-6">
                            <h3 className="text-xl font-semibold text-gray-900 mb-3">The Great Commission</h3>
                            <p className="text-gray-600">
                                We are called to make disciples of all nations, baptizing them and teaching them 
                                to observe all that Christ commanded.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Leadership Section */}
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <h2 className="text-3xl font-bold text-gray-900 mb-12 text-center">Our Leadership</h2>
                <div className="max-w-md mx-auto">
                    <div className="text-center">
                        <div className="w-48 h-48 bg-gray-300 rounded-full mx-auto mb-6"></div>
                        <h3 className="text-2xl font-semibold text-gray-900 mb-2">Bp. Dr. John Mark Nuwagaba</h3>
                        <p className="text-indigo-600 font-medium mb-4">Overseer</p>
                        <p className="text-gray-600">
                            Leading Rural Evangelical Ministries with a passion for spreading the Gospel 
                            and serving rural communities with biblical teaching and compassionate care.
                        </p>
                    </div>
                </div>
            </div>
        </Layout>
    );
}
