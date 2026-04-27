import { useState, useEffect } from 'react';
import { Link } from '@inertiajs/react';

export default function HeroSlider() {
    const [currentSlide, setCurrentSlide] = useState(0);

    const slides = [
        {
            image: '/images/hero/slide1.jpg',
            title: 'Rural Evangelical Ministries',
            subtitle: 'Spreading the Gospel and serving rural communities with love',
            cta: { text: 'Join Us', href: '/events' },
            cta2: { text: 'Learn More', href: '/about' }
        },
        {
            image: '/images/hero/slide2.jpg',
            title: 'Growing in Faith Together',
            subtitle: 'Building strong, Christ-centered communities through biblical teaching',
            cta: { text: 'View Sermons', href: '/sermons' },
            cta2: { text: 'Our Mission', href: '/about' }
        },
        {
            image: '/images/hero/slide3.jpg',
            title: 'Serving Rural Communities',
            subtitle: 'Bringing hope and practical help to those who need it most',
            cta: { text: 'Get Involved', href: '/contact' },
            cta2: { text: 'Upcoming Events', href: '/events' }
        }
    ];

    useEffect(() => {
        const timer = setInterval(() => {
            setCurrentSlide((prev) => (prev + 1) % slides.length);
        }, 5000); // Change slide every 5 seconds

        return () => clearInterval(timer);
    }, [slides.length]);

    const goToSlide = (index) => {
        setCurrentSlide(index);
    };

    const nextSlide = () => {
        setCurrentSlide((prev) => (prev + 1) % slides.length);
    };

    const prevSlide = () => {
        setCurrentSlide((prev) => (prev - 1 + slides.length) % slides.length);
    };

    return (
        <div className="relative h-screen w-full overflow-hidden">
            {/* Slides */}
            {slides.map((slide, index) => (
                <div
                    key={index}
                    className={`absolute inset-0 transition-opacity duration-1000 ${
                        index === currentSlide ? 'opacity-100' : 'opacity-0'
                    }`}
                >
                    {/* Background Image with Overlay */}
                    <div className="absolute inset-0">
                        <img
                            src={slide.image}
                            alt={slide.title}
                            className="w-full h-full object-cover"
                            onError={(e) => {
                                // Fallback to gradient if image doesn't exist
                                e.target.style.display = 'none';
                                e.target.parentElement.style.background = 
                                    'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                            }}
                        />
                        <div className="absolute inset-0 bg-black bg-opacity-50"></div>
                    </div>

                    {/* Content */}
                    <div className="relative h-full flex items-center justify-center">
                        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                            <h1 className="text-4xl md:text-6xl lg:text-7xl font-bold text-white mb-6 animate-fade-in">
                                {slide.title}
                            </h1>
                            <p className="text-xl md:text-2xl lg:text-3xl text-gray-100 mb-8 max-w-3xl mx-auto animate-fade-in-delay">
                                {slide.subtitle}
                            </p>
                            <div className="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-delay-2">
                                <Link
                                    href={slide.cta.href}
                                    className="inline-flex items-center justify-center px-8 py-4 border border-transparent text-lg font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50 transition-all transform hover:scale-105"
                                >
                                    {slide.cta.text}
                                </Link>
                                <Link
                                    href={slide.cta2.href}
                                    className="inline-flex items-center justify-center px-8 py-4 border-2 border-white text-lg font-medium rounded-md text-white hover:bg-white hover:text-indigo-600 transition-all transform hover:scale-105"
                                >
                                    {slide.cta2.text}
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            ))}

            {/* Navigation Arrows */}
            <button
                onClick={prevSlide}
                className="absolute left-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-50 text-white p-3 rounded-full transition-all backdrop-blur-sm z-10"
                aria-label="Previous slide"
            >
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                onClick={nextSlide}
                className="absolute right-4 top-1/2 -translate-y-1/2 bg-white bg-opacity-30 hover:bg-opacity-50 text-white p-3 rounded-full transition-all backdrop-blur-sm z-10"
                aria-label="Next slide"
            >
                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                </svg>
            </button>

            {/* Dots Navigation */}
            <div className="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3 z-10">
                {slides.map((_, index) => (
                    <button
                        key={index}
                        onClick={() => goToSlide(index)}
                        className={`w-3 h-3 rounded-full transition-all ${
                            index === currentSlide
                                ? 'bg-white w-8'
                                : 'bg-white bg-opacity-50 hover:bg-opacity-75'
                        }`}
                        aria-label={`Go to slide ${index + 1}`}
                    />
                ))}
            </div>

            {/* Scroll Down Indicator */}
            <div className="absolute bottom-20 left-1/2 -translate-x-1/2 animate-bounce">
                <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
            </div>
        </div>
    );
}
