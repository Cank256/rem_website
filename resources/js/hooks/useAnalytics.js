import { useEffect } from 'react';
import axios from 'axios';

export function useAnalytics() {
    /**
     * Track a custom event
     */
    const trackEvent = async (eventName, eventCategory = null, eventData = null) => {
        try {
            // Check if user has accepted cookies
            const consent = localStorage.getItem('cookie_consent');
            if (consent !== 'accepted') {
                return;
            }

            await axios.post('/api/analytics/track-event', {
                event_name: eventName,
                event_category: eventCategory,
                event_data: eventData,
            });
        } catch (error) {
            console.error('Error tracking event:', error);
        }
    };

    return { trackEvent };
}

/**
 * Hook to track page duration
 */
export function usePageDuration(pageViewId) {
    useEffect(() => {
        // Check if user has accepted cookies
        const consent = localStorage.getItem('cookie_consent');
        if (consent !== 'accepted' || !pageViewId) {
            return;
        }

        const startTime = Date.now();

        const updateDuration = async () => {
            const duration = Math.floor((Date.now() - startTime) / 1000);
            try {
                await axios.post('/api/analytics/update-duration', {
                    page_view_id: pageViewId,
                    duration: duration,
                });
            } catch (error) {
                console.error('Error updating page duration:', error);
            }
        };

        // Update duration when user leaves the page
        const handleBeforeUnload = () => {
            updateDuration();
        };

        window.addEventListener('beforeunload', handleBeforeUnload);

        // Also update every 30 seconds
        const interval = setInterval(updateDuration, 30000);

        return () => {
            window.removeEventListener('beforeunload', handleBeforeUnload);
            clearInterval(interval);
            updateDuration();
        };
    }, [pageViewId]);
}
