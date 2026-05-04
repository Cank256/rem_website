import Layout from '@/Components/Layout';
import { Head } from '@inertiajs/react';

export default function TermsOfUse() {
    return (
        <Layout>
            <Head title="Terms of Use" />

            {/* Hero Section */}
            <div className="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-20">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 className="text-4xl md:text-5xl font-bold mb-4">Terms of Use</h1>
                    <p className="text-xl text-indigo-100">Last Updated: May 4, 2026</p>
                </div>
            </div>

            {/* Content Section */}
            <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div className="prose prose-lg max-w-none">
                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Agreement to Terms</h2>
                        <p className="text-gray-600 mb-4">
                            These Terms of Use constitute a legally binding agreement made between you, whether personally 
                            or on behalf of an entity ("you") and Rural Evangelical Ministries ("we," "us," or "our"), 
                            concerning your access to and use of our website.
                        </p>
                        <p className="text-gray-600">
                            By accessing or using the website, you agree that you have read, understood, and agree to be 
                            bound by all of these Terms of Use. If you do not agree with all of these terms, then you are 
                            expressly prohibited from using the site and you must discontinue use immediately.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Intellectual Property Rights</h2>
                        <p className="text-gray-600 mb-4">
                            Unless otherwise indicated, the website is our proprietary property and all source code, databases, 
                            functionality, software, website designs, audio, video, text, photographs, and graphics on the 
                            website (collectively, the "Content") and the trademarks, service marks, and logos contained 
                            therein (the "Marks") are owned or controlled by us or licensed to us.
                        </p>
                        <p className="text-gray-600 mb-4">
                            The Content and the Marks are provided on the website "AS IS" for your information and personal 
                            use only. Except as expressly provided in these Terms of Use, no part of the website and no 
                            Content or Marks may be copied, reproduced, aggregated, republished, uploaded, posted, publicly 
                            displayed, encoded, translated, transmitted, distributed, sold, licensed, or otherwise exploited 
                            for any commercial purpose whatsoever, without our express prior written permission.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">User Representations</h2>
                        <p className="text-gray-600 mb-4">
                            By using the website, you represent and warrant that:
                        </p>
                        <ul className="list-disc pl-6 text-gray-600 mb-4 space-y-2">
                            <li>All registration information you submit will be true, accurate, current, and complete</li>
                            <li>You will maintain the accuracy of such information and promptly update such registration information as necessary</li>
                            <li>You have the legal capacity and you agree to comply with these Terms of Use</li>
                            <li>You are not a minor in the jurisdiction in which you reside</li>
                            <li>You will not access the website through automated or non-human means, whether through a bot, script, or otherwise</li>
                            <li>You will not use the website for any illegal or unauthorized purpose</li>
                            <li>Your use of the website will not violate any applicable law or regulation</li>
                        </ul>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Prohibited Activities</h2>
                        <p className="text-gray-600 mb-4">
                            You may not access or use the website for any purpose other than that for which we make the 
                            website available. The website may not be used in connection with any commercial endeavors 
                            except those that are specifically endorsed or approved by us.
                        </p>
                        <p className="text-gray-600 mb-4">
                            As a user of the website, you agree not to:
                        </p>
                        <ul className="list-disc pl-6 text-gray-600 mb-4 space-y-2">
                            <li>Systematically retrieve data or other content from the website to create or compile a collection, database, or directory</li>
                            <li>Make any unauthorized use of the website, including collecting usernames and/or email addresses</li>
                            <li>Use the website to advertise or offer to sell goods and services</li>
                            <li>Circumvent, disable, or otherwise interfere with security-related features of the website</li>
                            <li>Engage in unauthorized framing of or linking to the website</li>
                            <li>Trick, defraud, or mislead us and other users</li>
                            <li>Make improper use of our support services or submit false reports of abuse or misconduct</li>
                            <li>Engage in any automated use of the system</li>
                            <li>Interfere with, disrupt, or create an undue burden on the website or the networks or services connected to the website</li>
                            <li>Attempt to impersonate another user or person</li>
                            <li>Use any information obtained from the website in order to harass, abuse, or harm another person</li>
                            <li>Use the website as part of any effort to compete with us</li>
                            <li>Decipher, decompile, disassemble, or reverse engineer any of the software comprising the website</li>
                            <li>Harass, annoy, intimidate, or threaten any of our employees or agents</li>
                            <li>Delete the copyright or other proprietary rights notice from any Content</li>
                            <li>Upload or transmit viruses, Trojan horses, or other material that interferes with any party's use of the website</li>
                        </ul>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">User Generated Contributions</h2>
                        <p className="text-gray-600 mb-4">
                            The website may invite you to chat, contribute to, or participate in blogs, message boards, 
                            online forums, and other functionality, and may provide you with the opportunity to create, 
                            submit, post, display, transmit, perform, publish, distribute, or broadcast content and materials 
                            to us or on the website.
                        </p>
                        <p className="text-gray-600 mb-4">
                            By posting your contributions to any part of the website, you automatically grant us an unrestricted, 
                            unlimited, irrevocable, perpetual, non-exclusive, transferable, royalty-free, fully-paid, worldwide 
                            right to use, copy, reproduce, distribute, sell, resell, publish, broadcast, retitle, archive, 
                            store, cache, publicly perform, publicly display, reformat, translate, transmit, excerpt, and 
                            distribute such contributions for any purpose.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Donations and Contributions</h2>
                        <p className="text-gray-600 mb-4">
                            All donations made through our website are voluntary and non-refundable unless required by law. 
                            We reserve the right to refuse or return any donation at our discretion.
                        </p>
                        <p className="text-gray-600 mb-4">
                            Donations are used to support the ministry activities of Rural Evangelical Ministries. We are 
                            committed to using donations responsibly and in accordance with our mission and values.
                        </p>
                        <p className="text-gray-600">
                            For tax purposes, you will receive a receipt for your donation. Please consult with your tax 
                            advisor regarding the deductibility of your donation.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Website Management</h2>
                        <p className="text-gray-600 mb-4">
                            We reserve the right, but not the obligation, to:
                        </p>
                        <ul className="list-disc pl-6 text-gray-600 mb-4 space-y-2">
                            <li>Monitor the website for violations of these Terms of Use</li>
                            <li>Take appropriate legal action against anyone who violates these Terms of Use</li>
                            <li>Refuse, restrict access to, limit the availability of, or disable any of your contributions</li>
                            <li>Remove from the website or otherwise disable all files and content that are excessive in size or burdensome to our systems</li>
                            <li>Otherwise manage the website in a manner designed to protect our rights and property</li>
                        </ul>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Privacy Policy</h2>
                        <p className="text-gray-600 mb-4">
                            We care about data privacy and security. Please review our Privacy Policy. By using the website, 
                            you agree to be bound by our Privacy Policy, which is incorporated into these Terms of Use.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Term and Termination</h2>
                        <p className="text-gray-600 mb-4">
                            These Terms of Use shall remain in full force and effect while you use the website. Without 
                            limiting any other provision of these Terms of Use, we reserve the right to, in our sole 
                            discretion and without notice or liability, deny access to and use of the website to any person 
                            for any reason or for no reason.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Modifications and Interruptions</h2>
                        <p className="text-gray-600 mb-4">
                            We reserve the right to change, modify, or remove the contents of the website at any time or 
                            for any reason at our sole discretion without notice. However, we have no obligation to update 
                            any information on our website.
                        </p>
                        <p className="text-gray-600">
                            We cannot guarantee the website will be available at all times. We may experience hardware, 
                            software, or other problems or need to perform maintenance related to the website, resulting 
                            in interruptions, delays, or errors.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Disclaimer</h2>
                        <p className="text-gray-600 mb-4">
                            THE WEBSITE IS PROVIDED ON AN AS-IS AND AS-AVAILABLE BASIS. YOU AGREE THAT YOUR USE OF THE 
                            WEBSITE AND OUR SERVICES WILL BE AT YOUR SOLE RISK. TO THE FULLEST EXTENT PERMITTED BY LAW, 
                            WE DISCLAIM ALL WARRANTIES, EXPRESS OR IMPLIED, IN CONNECTION WITH THE WEBSITE AND YOUR USE 
                            THEREOF.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Limitations of Liability</h2>
                        <p className="text-gray-600 mb-4">
                            IN NO EVENT WILL WE OR OUR DIRECTORS, EMPLOYEES, OR AGENTS BE LIABLE TO YOU OR ANY THIRD PARTY 
                            FOR ANY DIRECT, INDIRECT, CONSEQUENTIAL, EXEMPLARY, INCIDENTAL, SPECIAL, OR PUNITIVE DAMAGES, 
                            INCLUDING LOST PROFIT, LOST REVENUE, LOSS OF DATA, OR OTHER DAMAGES ARISING FROM YOUR USE OF 
                            THE WEBSITE.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Governing Law</h2>
                        <p className="text-gray-600 mb-4">
                            These Terms of Use and your use of the website are governed by and construed in accordance with 
                            the laws applicable to our organization, without regard to its conflict of law principles.
                        </p>
                    </section>

                    <section className="mb-12">
                        <h2 className="text-3xl font-bold text-gray-900 mb-4">Contact Us</h2>
                        <p className="text-gray-600 mb-4">
                            If you have any questions or concerns about these Terms of Use, please contact us:
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
