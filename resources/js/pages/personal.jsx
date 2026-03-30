import React from 'react';
import DashboardTemplate from '../components/templates/DashboardTemplate';
import { 
  CalendarDays, 
  MapPin, 
  Clock, 
  CheckCircle2, 
  MinusCircle, 
  Lock, 
  User, 
  MessageSquare
} from 'lucide-react';

export default function PersonalPage() {
  
  const staffMembers = [
    { 
      id: 1, 
      name: "Carlos Vega", 
      role: "Técnico de\nIluminación", 
      status: "Disponible", 
      tags: ["GrandMA3", "LED Walls"], 
      locked: false, 
      imgColor: "bg-gray-700" 
    },
    { 
      id: 2, 
      name: "Elena Torres", 
      role: "Fotógrafa\nSenior", 
      status: "Disponible", 
      tags: ["Editing", "Live Stream"], 
      locked: false, 
      imgColor: "bg-gray-800" 
    },
    { 
      id: 3, 
      name: "Luis Ferra", 
      role: "Ingeniero de\nSonido", 
      status: "Ocupado: Gala Artística", 
      tags: [], 
      locked: true, 
      imgColor: "bg-[#EBE9E2]" 
    },
    { 
      id: 4, 
      name: "Mario Ruiz", 
      role: "Broadcast\nDirector", 
      status: "Disponible", 
      tags: ["vMix", "NDI"], 
      locked: false, 
      imgColor: "bg-gray-600" 
    },
    { 
      id: 5, 
      name: "Paula Santos", 
      role: "Stage\nManager", 
      status: "Disponible", 
      tags: ["Logistics", "VIP"], 
      locked: false, 
      imgColor: "bg-gray-700" 
    },
    { 
      id: 6, 
      name: "Andrés Gil", 
      role: "Especialista\nLED", 
      status: "Disponible", 
      tags: ["Novastar", "Resolume"], 
      locked: false, 
      imgColor: "bg-gray-900" 
    },
  ];

  return (
    <DashboardTemplate 
      activePath="/personal"
      headerActions={
        <button className="bg-[#FFB800] hover:bg-[#F2AE00] text-[#1A1A1A] font-bold text-[15px] px-8 py-3.5 rounded-full flex items-center gap-2.5 transition-transform active:scale-95 shadow-[0_4px_12px_rgba(255,184,0,0.3)]">
          <CheckCircle2 size={20} strokeWidth={2.5}/>
          Confirmar Equipo
        </button>
      }
    >
      {/* PAGE HEADER SLOT */}
      <div className="mb-2">
        <div className="flex items-center gap-2 text-[13px] font-bold text-gray-400 mb-3 tracking-wide">
          <span className="cursor-pointer hover:text-gray-600 transition-colors">Eventos</span>
          <span className="text-gray-300">›</span>
          <span className="cursor-pointer hover:text-gray-600 transition-colors">Tech Summit 2024</span>
          <span className="text-gray-300">›</span>
          <span className="text-[#8C610F] cursor-pointer">Asignación de Personal</span>
        </div>
        <h1 className="text-[34px] font-extrabold text-[#1A1A1A] tracking-tight">
          Asignación Operativa
        </h1>
      </div>

      <div className="grid grid-cols-1 xl:grid-cols-[400px_1fr] gap-x-10 gap-y-8 mt-10">
        
        {/* LEFT COLUMN: Event Overview & Assigned Staff */}
        <div className="space-y-8">
          
          {/* Event Details Card */}
          <div className="bg-white rounded-[32px] p-7 shadow-[0_2px_15px_-4px_rgba(0,0,0,0.05)] border border-white">
            <div className="w-full h-[180px] bg-gradient-to-br from-[#003B73] to-[#00172D] rounded-2xl relative overflow-hidden mb-6 shadow-md flex items-center justify-center">
              {/* Event Image Placeholder / Tech Elements */}
              <div className="absolute inset-0 opacity-20 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNmZmZmZmYiLz48L3N2Zz4=')]"></div>
              
              <div className="absolute top-4 left-4 bg-[#E03A16] text-white text-[10px] font-extrabold tracking-wider px-3 py-1.5 rounded shadow-md z-10 flex items-center gap-1.5">
                <div className="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></div>
                EN PREPARACIÓN
              </div>
            </div>

            <h2 className="text-[24px] font-extrabold text-[#1A1A1A] mb-3 tracking-tight">
              Tech Summit 2024
            </h2>
            <p className="text-[14.5px] text-gray-500 mb-8 leading-relaxed font-medium">
              Conferencia anual de innovación tecnológica con despliegue de audio multizona y transmisión 4K en vivo.
            </p>

            <div className="space-y-5">
              <div className="flex items-center gap-4">
                <div className="w-[38px] h-[38px] rounded-full bg-[#EBE9E2] flex items-center justify-center text-[#8C610F] shadow-sm">
                  <CalendarDays size={18} strokeWidth={2.5} />
                </div>
                <span className="font-bold text-[#1A1A1A] text-[14px]">15 de Octubre, 2024</span>
              </div>
              <div className="flex items-center gap-4">
                <div className="w-[38px] h-[38px] rounded-full bg-[#EBE9E2] flex items-center justify-center text-[#8C610F] shadow-sm">
                  <MapPin size={18} strokeWidth={2.5} />
                </div>
                <span className="font-bold text-[#1A1A1A] text-[14px]">Centro de Convenciones<br/>Metropolitan</span>
              </div>
              <div className="flex items-center gap-4">
                <div className="w-[38px] h-[38px] rounded-full bg-[#EBE9E2] flex items-center justify-center text-[#8C610F] shadow-sm">
                  <Clock size={18} strokeWidth={2.5} />
                </div>
                <span className="font-bold text-[#1A1A1A] text-[14px]">08:00 AM - 06:00 PM</span>
              </div>
            </div>
          </div>

          {/* Assigned Staff Block */}
          <div className="bg-[#F6F5F2] rounded-[32px] p-7 shadow-inner">
            <h3 className="text-[13px] font-extrabold text-gray-500 tracking-[0.15em] uppercase mb-6 ml-2">
              PERSONAL ASIGNADO (4/8)
            </h3>
            <div className="space-y-3.5">
              
              {/* Assigned Staff 1 */}
              <div className="bg-white rounded-2xl p-4 px-5 flex items-center justify-between shadow-[0_2px_8px_-4px_rgba(0,0,0,0.05)] border border-gray-100/50 hover:shadow-md transition-shadow">
                <div className="flex items-center gap-4">
                  <div className="w-11 h-11 rounded-full bg-[#FCEFCE] text-[#AE802D] font-bold flex items-center justify-center text-[13px] shadow-sm">
                    RM
                  </div>
                  <div>
                    <h4 className="text-[14.5px] font-bold text-[#1A1A1A]">Ricardo Méndez</h4>
                    <p className="text-[12px] text-gray-500 font-medium mt-0.5">Coordinador AV</p>
                  </div>
                </div>
                <button className="text-[#D9534F] hover:text-red-700 transition-colors opacity-80 hover:opacity-100">
                  <MinusCircle size={22} className="fill-red-50" strokeWidth={2}/>
                </button>
              </div>

              {/* Assigned Staff 2 */}
              <div className="bg-white rounded-2xl p-4 px-5 flex items-center justify-between shadow-[0_2px_8px_-4px_rgba(0,0,0,0.05)] border border-gray-100/50 hover:shadow-md transition-shadow">
                <div className="flex items-center gap-4">
                  <div className="w-11 h-11 rounded-full bg-[#DCEBFF] text-[#4A87D4] font-bold flex items-center justify-center text-[13px] shadow-sm">
                    SA
                  </div>
                  <div>
                    <h4 className="text-[14.5px] font-bold text-[#1A1A1A]">Sofía Alarcón</h4>
                    <p className="text-[12px] text-gray-500 font-medium mt-0.5">Técnico de Sonido</p>
                  </div>
                </div>
                <button className="text-[#D9534F] hover:text-red-700 transition-colors opacity-80 hover:opacity-100">
                  <MinusCircle size={22} className="fill-red-50" strokeWidth={2}/>
                </button>
              </div>

            </div>
          </div>

        </div>

        {/* RIGHT COLUMN: Directory & Selection */}
        <div className="flex flex-col">
          
          {/* Filters Area */}
          <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8 sticky top-0 bg-[#FDFBF7]/90 py-2 backdrop-blur-md z-10 -mx-4 px-4 sm:mx-0 sm:px-0">
            <div className="flex flex-wrap items-center gap-2">
              <span className="bg-[#FFB800] text-black font-bold text-[13px] px-5 py-2.5 rounded-full cursor-pointer shadow-sm">Todos</span>
              <span className="bg-[#EBE9E2] text-gray-600 font-bold text-[13px] px-5 py-2.5 rounded-full cursor-pointer hover:bg-gray-200 transition-colors">Técnicos</span>
              <span className="bg-[#EBE9E2] text-gray-600 font-bold text-[13px] px-5 py-2.5 rounded-full cursor-pointer hover:bg-gray-200 transition-colors">Coordinadores</span>
              <span className="bg-[#EBE9E2] text-gray-600 font-bold text-[13px] px-5 py-2.5 rounded-full cursor-pointer hover:bg-gray-200 transition-colors">Multimedia</span>
            </div>
            <span className="text-[13px] font-bold text-gray-500 shrink-0">
              Mostrando 24 colaboradores
            </span>
          </div>

          {/* Grid of Available Staff */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {staffMembers.map((staff) => (
              <div 
                key={staff.id} 
                className={`rounded-[28px] p-6 relative border transition-shadow ${
                  staff.locked 
                    ? 'bg-[#F6F5F2] border-transparent shadow-none' 
                    : 'bg-white border-white shadow-[0_4px_15px_-5px_rgba(0,0,0,0.05)] hover:shadow-[0_8px_25px_-5px_rgba(0,0,0,0.08)] cursor-pointer'
                }`}
              >
                {/* Action Icon Top Right */}
                <div className={`absolute top-5 right-5 ${staff.locked ? 'text-gray-400' : 'text-gray-300'}`}>
                  {staff.locked ? (
                    <Lock size={18} strokeWidth={2.5}/>
                  ) : (
                    <div className="w-[22px] h-[22px] border-2 border-gray-200 rounded-[6px] transition-colors hover:border-[#FFB800]"></div>
                  )}
                </div>

                {/* Profile Header */}
                <div className="flex items-center gap-4 mb-6">
                  {staff.locked ? (
                    <div className="w-[52px] h-[52px] rounded-full bg-[#EBE9E2] flex items-center justify-center text-gray-400 shrink-0 shadow-inner">
                      <User size={24} strokeWidth={2} />
                    </div>
                  ) : (
                    <div className={`w-[52px] h-[52px] rounded-full shrink-0 shadow-sm flex items-center justify-center text-white/50 text-[10px] overflow-hidden ${staff.imgColor}`}>
                      <div className="w-full h-full bg-gradient-to-tr from-black/40 to-transparent"></div>
                    </div>
                  )}
                  
                  <div>
                    <h4 className={`text-[16px] font-extrabold leading-tight mb-1 tracking-tight ${staff.locked ? 'text-gray-500' : 'text-[#1A1A1A]'}`}>
                      {staff.name}
                    </h4>
                    <p className={`text-[10px] font-bold uppercase tracking-[0.1em] leading-snug whitespace-pre-wrap ${staff.locked ? 'text-gray-400' : 'text-[#A67822]'}`}>
                      {staff.role}
                    </p>
                  </div>
                </div>

                {/* Status & Capacity */}
                <div className="flex flex-col gap-4">
                  <div className="flex items-center gap-2">
                    <div className={`w-2.5 h-2.5 rounded-full ${staff.locked ? 'bg-[#E03A16]' : 'bg-[#22C55E]'}`}></div>
                    <span className={`text-[13px] font-bold ${staff.locked ? 'text-[#E03A16]' : 'text-[#22C55E]'}`}>
                      {staff.status}
                    </span>
                  </div>

                  {staff.tags.length > 0 && (
                    <div className="flex flex-wrap gap-2">
                      {staff.tags.map((tag, idx) => (
                        <span key={idx} className="bg-[#F6F5F2] text-gray-500 text-[11px] font-bold px-3 py-1.5 rounded-md tracking-wide">
                          {tag}
                        </span>
                      ))}
                    </div>
                  )}
                </div>
              </div>
            ))}
          </div>

          {/* Load More Button */}
          <div className="flex justify-center mt-12 pb-10">
            <button className="bg-transparent border-2 border-gray-200 text-gray-500 font-bold text-[14px] px-8 py-3.5 rounded-full hover:bg-white hover:text-gray-800 hover:border-gray-300 transition-all shadow-sm">
              Cargar más personal
            </button>
          </div>

        </div>
      </div>

      {/* Floating Action/Support Button */}
      <div className="fixed bottom-10 right-10 z-50">
        <button className="bg-[#7A540D] hover:bg-[#5E410A] text-white w-14 h-14 rounded-full flex items-center justify-center shadow-[0_8px_20px_rgba(122,84,13,0.4)] transition-transform hover:scale-105 active:scale-95">
          <MessageSquare size={24} fill="currentColor" className="text-[#EBE9E2] stroke-[#7A540D]"/>
        </button>
      </div>

    </DashboardTemplate>
  );
}
