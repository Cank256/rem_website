import { useState, useEffect } from 'react';
import { Link } from '@inertiajs/react';

export default function CookieConsent() {
    const [showBanner, setShowBanner] = useState(false);

    useEffect(() => {
        // Check if user has already accepted cookies
        const consent = localStorage.getItem('cookie_consent');
        if (!consent) {
            setShowBanner(true);
        }
    }, []);

    const acceptCookies = () => {
        localStorage.setItem('cookie_consent', 'accepted');
        localStorage.setItem('cookie_consent_date', new Date().toISOString());
        setShowBanner(false);
    };

    const declineCookies = () => {
        localStorage.setItem('cookie_consent', 'declined');
        localStorage.setItem('cookie_consent_date', new Date().toISOString());
        setShowBanner(false);
    };

    if (!showBanner) {
        return null;
    }

    return (
        <div className="fixed bottom-0 left-0 right-0 z-50 bg-gray-900 text-white shadow-lg">
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div className="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div className="flex-1">
                        <p className="text-sm sm:text-base">
                            We use cookies and similar technologies to enhance your browsing experience, 
                            analyze site traffic, and understand where our visitors are coming from. 
                            By clicking "Accept", you consent to our use of cookies.
                            {' '}
                            <Link 
                                href="/privacy-policy" 
                                className="underline hover:text-indigo-300 transition-colors"
                            >
                                Learn more
                            </Link>
                        </p>
                    </div>
                    <div className="flex gap-3 flex-shrink-0">
                        <button
                            onClick={declineCookies}
                            className="px-4 py-2 text-sm font-medium text-gray-300 hover:text-white border border-gray-600 rounded-lg hover:bg-gray-800 transition-colors"
                        >
                            Decline
                        </button>
                        <button
                            onClick={acceptCookies}
                            className="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-colors"
                        >
                            Accept
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
}
