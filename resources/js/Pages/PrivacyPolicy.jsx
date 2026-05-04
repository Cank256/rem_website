import Layout from '@/Components/Layout';
import { Head } from '@inertiajs/react';

export default function PrivacyPolicy() {
    return (
        <Layout>
            <Head title="Privacy Policy" />

            {/* Hero Section */}
            <div className="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">Privacy Policy</h1>
                    <p className="text-xl text-indigo-100">Last Updated: May 4, 2026</p>
                </div>
            </div>

            {/* Content Section */}
            <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="prose prose-lg max-w-none">
                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Introduction</h2>
                        <p className="text-gray-600 mb-4">
                            Rural Evangelical Ministries ("we," "our," or "us") is committed to protecting your privacy. 
                            This Privacy Policy explains how we collect, use, disclose, and safeguard your information 
                            when you visit our website.
                        </p>
                        <p className="text-gray-600">
                            Please read this privacy policy carefully. If you do not agree with the terms of this privacy 
                            policy, please do not access the site.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Information We Collect</h2>
                        
                        <h3 className="text-2xl font-semibold text-gray-900 mb-3 mt-6">Automatically Collected Information</h3>
                        <p className="text-gray-600 mb-4">
                            When you visit our website, we automatically collect certain information about your device and 
                            your interaction with our site, including:
                        </p>
                        <ul className="list-disc pl-6 text-gray-600 mb-4 space-y-2">
                            <li>Browser type and version</li>
                            <li>Operating system</li>
                            <li>Device type (mobile, tablet, desktop)</li>
                            <li>IP address</li>
                            <li>Pages visited and time spent on each page</li>
                            <li>Referring website addresses</li>
                            <li>Date and time of your visit</li>
                        </ul>

                        <h3 className="text-2xl font-semibold text-gray-900 mb-3 mt-6">Information You Provide</h3>
                        <p className="text-gray-600 mb-4">
                            We may collect information that you voluntarily provide to us when you:
                        </p>
                        <ul className="list-disc pl-6 text-gray-600 mb-4 space-y-2">
                            <li>Register for an account</li>
                            <li>Subscribe to our newsletter</li>
                            <li>Contact us through forms</li>
                            <li>Participate in surveys or events</li>
                            <li>Make donations or purchases</li>
                        </ul>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">How We Use Your Information</h2>
                        <p className="text-gray-600 mb-4">
                            We use the information we collect to:
                        </p>
                        <ul className="list-disc pl-6 text-gray-600 mb-4 space-y-2">
                            <li>Provide, operate, and maintain our website</li>
                            <li>Improve, personalize, and expand our website</li>
                            <li>Understand and analyze how you use our website</li>
                            <li>Develop new products, services, features, and functionality</li>
                            <li>Communicate with you for customer service, updates, and promotional purposes</li>
                            <li>Send you newsletters and ministry updates (with your consent)</li>
                            <li>Process your donations and transactions</li>
                            <li>Prevent fraudulent transactions and monitor against theft</li>
                            <li>Comply with legal obligations</li>
                        </ul>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Cookies and Tracking Technologies</h2>
                        <p className="text-gray-600 mb-4">
                            We use cookies and similar tracking technologies to track activity on our website and store 
                            certain information. Cookies are files with a small amount of data that are sent to your 
                            browser from a website and stored on your device.
                        </p>
                        
                        <h3 className="text-2xl font-semibold text-gray-900 mb-3 mt-6">Types of Cookies We Use</h3>
                        <ul className="list-disc pl-6 text-gray-600 mb-4 space-y-2">
                            <li><strong>Essential Cookies:</strong> Required for the website to function properly</li>
                            <li><strong>Analytics Cookies:</strong> Help us understand how visitors interact with our website</li>
                            <li><strong>Preference Cookies:</strong> Remember your preferences and settings</li>
                            <li><strong>Session Cookies:</strong> Maintain your session while browsing</li>
                        </ul>
                        
                        <p className="text-gray-600 mb-4">
                            You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. 
                            However, if you do not accept cookies, you may not be able to use some portions of our website.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Analytics and Website Statistics</h2>
                        <p className="text-gray-600 mb-4">
                            We collect analytics data to understand how our website is being used and to improve our services. 
                            This includes:
                        </p>
                        <ul className="list-disc pl-6 text-gray-600 mb-4 space-y-2">
                            <li>Page view statistics</li>
                            <li>Visitor session information</li>
                            <li>Device and browser information</li>
                            <li>Geographic location (country/city level)</li>
                            <li>User engagement metrics</li>
                        </ul>
                        <p className="text-gray-600">
                            This data is collected anonymously and is used solely for improving our website experience 
                            and understanding our audience better.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Data Sharing and Disclosure</h2>
                        <p className="text-gray-600 mb-4">
                            We do not sell, trade, or rent your personal information to third parties. We may share your 
                            information in the following circumstances:
                        </p>
                        <ul className="list-disc pl-6 text-gray-600 mb-4 space-y-2">
                            <li><strong>Service Providers:</strong> With trusted third-party service providers who assist us in operating our website</li>
                            <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
                            <li><strong>Business Transfers:</strong> In connection with any merger, sale, or transfer of our organization</li>
                            <li><strong>With Your Consent:</strong> When you have given us explicit permission to share your information</li>
                        </ul>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Data Security</h2>
                        <p className="text-gray-600 mb-4">
                            We implement appropriate technical and organizational security measures to protect your personal 
                            information against unauthorized access, alteration, disclosure, or destruction. However, no method 
                            of transmission over the Internet or electronic storage is 100% secure, and we cannot guarantee 
                            absolute security.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Your Privacy Rights</h2>
                        <p className="text-gray-600 mb-4">
                            Depending on your location, you may have the following rights regarding your personal information:
                        </p>
                        <ul className="list-disc pl-6 text-gray-600 mb-4 space-y-2">
                            <li>The right to access your personal information</li>
                            <li>The right to correct inaccurate information</li>
                            <li>The right to request deletion of your information</li>
                            <li>The right to object to or restrict processing</li>
                            <li>The right to data portability</li>
                            <li>The right to withdraw consent</li>
                        </ul>
                        <p className="text-gray-600">
                            To exercise these rights, please contact us using the information provided below.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Children's Privacy</h2>
                        <p className="text-gray-600 mb-4">
                            Our website is not intended for children under the age of 13. We do not knowingly collect 
                            personal information from children under 13. If you are a parent or guardian and believe 
                            your child has provided us with personal information, please contact us.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Changes to This Privacy Policy</h2>
                        <p className="text-gray-600 mb-4">
                            We may update our Privacy Policy from time to time. We will notify you of any changes by 
                            posting the new Privacy Policy on this page and updating the "Last Updated" date at the top 
                            of this policy.
                        </p>
                        <p className="text-gray-600">
                            You are advised to review this Privacy Policy periodically for any changes. Changes to this 
                            Privacy Policy are effective when they are posted on this page.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Contact Us</h2>
                        <p className="text-gray-600 mb-4">
                            If you have any questions about this Privacy Policy, please contact us:
                        </p>
                        <div className="bg-gray-50 rounded-lg p-6">
                            <p className="text-gray-700 mb-2"><strong>Rural Evangelical Ministries</strong></p>
                            <p className="text-gray-600 mb-2">Email: info@ruralministry.org</p>
                            <p className="text-gray-600 mb-2">Phone: [Your Phone Number]</p>
                            <p className="text-gray-600">Address: [Your Physical Address]</p>
                        </div>
                    </section>
                </div>
            </div>
        </Layout>
    );
}
