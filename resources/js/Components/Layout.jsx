import { Link } from '@inertiajs/react';
import { useState } from 'react';

export default function Layout({ children }) {
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Navbar - Transparent, Sticky, and Overlaying */}
            <nav className="fixed top-0 left-0 right-0 z-50 bg-white/60 backdrop-blur-md shadow-lg transition-all">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-16 lg:h-20">
                        {/* Logo */}
                        <div className="flex-shrink-0">
                            <Link href="/" className="flex items-center gap-3">
                                <img 
                                    src="/images/logo.png" 
                                    alt="Rural Evangelical Ministries" 
                                    className="h-12 lg:h-16 w-auto"
                                />
                                <div className="hidden md:block">
                                    <h1 className="text-lg lg:text-xl font-bold text-gray-900 leading-tight">
                                        Rural Evangelical<br />Ministries
                                    </h1>
                                </div>
                            </Link>
                        </div>

                        {/* Centered Desktop Menu */}
                        <div className="hidden lg:flex lg:items-center lg:space-x-8">
                            <Link
                                href="/"
                                className="border-transparent text-gray-700 hover:border-indigo-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-base font-medium transition-colors"
                            >
                                Home
                            </Link>
                            <Link
                                href="/about"
                                className="border-transparent text-gray-700 hover:border-indigo-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-base font-medium transition-colors"
                            >
                                About REM
                            </Link>
                            <Link
                                href="/ministries"
                                className="border-transparent text-gray-700 hover:border-indigo-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-base font-medium transition-colors"
                            >
                                Ministries
                            </Link>
                            <Link
                                href="/sermons"
                                className="border-transparent text-gray-700 hover:border-indigo-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-base font-medium transition-colors"
                            >
                                Sermons
                            </Link>
                            <Link
                                href="/events"
                                className="border-transparent text-gray-700 hover:border-indigo-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-base font-medium transition-colors"
                            >
                                Events
                            </Link>
                            <Link
                                href="/gallery"
                                className="border-transparent text-gray-700 hover:border-indigo-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-base font-medium transition-colors"
                            >
                                Gallery
                            </Link>
                            <Link
                                href="/contact"
                                className="border-transparent text-gray-700 hover:border-indigo-500 hover:text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-base font-medium transition-colors"
                            >
                                Contact Us
                            </Link>
                            <Link
                                href="/live"
                                className="border-transparent text-red-600 hover:border-red-500 hover:text-red-700 inline-flex items-center px-1 pt-1 border-b-2 text-base font-bold transition-colors"
                            >
                                Live
                            </Link>
                        </div>

                        {/* Give Button */}
                        <div className="hidden lg:flex lg:items-center">
                            <Link
                                href="/give"
                                className="inline-flex items-center px-5 py-2.5 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-colors"
                            >
                                Give
                            </Link>
                        </div>

                        {/* Mobile menu button */}
                        <div className="flex items-center lg:hidden">
                            <button
                                onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                                className="inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-gray-900 hover:bg-gray-100"
                            >
                                <span className="sr-only">Open main menu</span>
                                {!mobileMenuOpen ? (
                                    <svg className="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                ) : (
                                    <svg className="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                )}
                            </button>
                        </div>
                    </div>
                </div>

                {/* Mobile menu */}
                {mobileMenuOpen && (
                    <div className="lg:hidden bg-white/95 backdrop-blur-md">
                        <div className="pt-2 pb-3 space-y-1">
                            <Link
                                href="/"
                                className="border-transparent text-gray-700 hover:bg-gray-50 hover:border-indigo-500 hover:text-gray-900 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                            >
                                Home
                            </Link>
                            <Link
                                href="/about"
                                className="border-transparent text-gray-700 hover:bg-gray-50 hover:border-indigo-500 hover:text-gray-900 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                            >
                                About REM
                            </Link>
                            <Link
                                href="/ministries"
                                className="border-transparent text-gray-700 hover:bg-gray-50 hover:border-indigo-500 hover:text-gray-900 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                            >
                                Ministries
                            </Link>
                            <Link
                                href="/sermons"
                                className="border-transparent text-gray-700 hover:bg-gray-50 hover:border-indigo-500 hover:text-gray-900 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                            >
                                Sermons
                            </Link>
                            <Link
                                href="/events"
                                className="border-transparent text-gray-700 hover:bg-gray-50 hover:border-indigo-500 hover:text-gray-900 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                            >
                                Events
                            </Link>
                            <Link
                                href="/gallery"
                                className="border-transparent text-gray-700 hover:bg-gray-50 hover:border-indigo-500 hover:text-gray-900 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                            >
                                Gallery
                            </Link>
                            <Link
                                href="/contact"
                                className="border-transparent text-gray-700 hover:bg-gray-50 hover:border-indigo-500 hover:text-gray-900 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                            >
                                Contact Us
                            </Link>
                            <Link
                                href="/live"
                                className="border-transparent text-red-600 hover:bg-gray-50 hover:border-red-500 hover:text-red-700 block pl-3 pr-4 py-2 border-l-4 text-base font-bold"
                            >
                                Live
                            </Link>
                            <Link
                                href="/give"
                                className="border-transparent text-indigo-600 hover:bg-gray-50 hover:border-indigo-500 hover:text-indigo-700 block pl-3 pr-4 py-2 border-l-4 text-base font-medium"
                            >
                                Give
                            </Link>
                        </div>
                    </div>
                )}
            </nav>

            {/* Main Content - No spacer needed as navbar overlays */}
            <main>{children}</main>

            {/* Footer */}
            <footer className="bg-gray-800 text-white mt-12">
                <div className="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div>
                            <img 
                                src="/images/logo.png" 
                                alt="Rural Evangelical Ministries" 
                                className="h-16 w-auto mb-4"
                            />
                            <h3 className="text-lg font-semibold mb-4">Rural Evangelical Ministries</h3>
                            <p className="text-gray-300">
                                Spreading the Gospel and serving rural communities with love through biblical teaching, compassionate outreach, and faithful ministry.
                            </p>
                        </div>
                        <div>
                            <h3 className="text-lg font-semibold mb-4">Quick Links</h3>
                            <ul className="space-y-2">
                                <li>
                                    <Link href="/about" className="text-gray-300 hover:text-white">
                                        About REM
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/ministries" className="text-gray-300 hover:text-white">
                                        Ministries
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/sermons" className="text-gray-300 hover:text-white">
                                        Sermons
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/events" className="text-gray-300 hover:text-white">
                                        Events
                                    </Link>
                                </li>
                                <li>
                                    <Link href="/give" className="text-gray-300 hover:text-white">
                                        Give
                                    </Link>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 className="text-lg font-semibold mb-4">Contact Info</h3>
                            <ul className="space-y-2 text-gray-300">
                                <li>Rural Evangelical Ministries</li>
                                <li>Bukoto Evangelical Church</li>
                                <li>Moyo Close, Mulimira Zone, Bukoto</li>
                                <li>P.O Box 8926, Kampala, Uganda</li>
                                <li>Email: info@ruralevangelicalministries.org</li>
                                <li>Phone: +256 755 532028</li>
                            </ul>
                        </div>
                    </div>
                    <div className="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                        <p>&copy; {new Date().getFullYear()} Rural Evangelical Ministries. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
