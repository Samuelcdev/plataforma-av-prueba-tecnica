import React from 'react';

export function AuthTemplate({ 
    children, 
    title, 
    subtitle, 
    logoSrc = '/logo.png',
    footerText = 'Plataforma 2026 ©'
}) {
    return (
        <div className="min-h-screen bg-[#FAFAF8] flex flex-col justify-center items-center relative overflow-hidden font-sans selection:bg-[#E5A500] selection:text-white">
            <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[1200px] h-[800px] bg-[radial-gradient(ellipse_at_center,rgba(240,230,210,0.6)_0%,rgba(250,250,248,0)_70%)] pointer-events-none"></div>

            <div className="relative z-10 w-full max-w-[1100px] flex flex-col items-center px-4 py-8">
                
                <div className="flex flex-col items-center mb-10">
                    {logoSrc && <img src={logoSrc} alt="Logo" className="w-20 h-20 object-contain" />}
                    <h1 className="text-[32px] md:text-[40px] font-bold text-[#1A1A1A] tracking-tight leading-tight">
                        {title}
                    </h1>
                    <p className="text-gray-500 mt-1 font-medium text-[15px]">{subtitle}</p>
                </div>

                <div className="flex justify-center items-stretch gap-10 lg:gap-14 w-full">
                    <div className="bg-white rounded-[24px] p-8 md:p-12 w-full max-w-[460px] shadow-[0_30px_60px_-15px_rgba(0,0,0,0.05)] border border-gray-100 flex-shrink-0 z-20">
                        {children}
                    </div>
                </div>

                <div className="mt-16 sm:mt-20 flex justify-center w-full">
                    <div className="flex flex-col items-center">
                        <p className="text-[10px] sm:text-[11px] font-bold text-gray-400 uppercase tracking-[0.25em]">
                            {footerText}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    );
}