(function() {
    'use strict';
    
    const config = {
        endpoint: '/api/analytics/track'
    };
    
    function trackPageView() {
        const data = {
            page_url: window.location.pathname,
            page_title: document.title,
            referrer: document.referrer,
            timestamp: new Date().toISOString(),
            screen_width: window.screen.width,
            screen_height: window.screen.height
        };
        
        // Usa sendBeacon per affidabilità
        if (navigator.sendBeacon) {
            const blob = new Blob([JSON.stringify(data)], { type: 'application/json' });
            navigator.sendBeacon(config.endpoint, blob);
        } else {
            // Fallback per browser vecchi
            fetch(config.endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data),
                keepalive: true
            }).catch(err => console.error('Analytics tracking error:', err));
        }
    }
    
    // Traccia quando la pagina è completamente caricata
    if (document.readyState === 'complete') {
        trackPageView();
    } else {
        window.addEventListener('load', trackPageView);
    }
})();
