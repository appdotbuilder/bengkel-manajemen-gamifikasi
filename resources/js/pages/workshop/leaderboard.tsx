import React from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface Badge {
    id: number;
    badge_type: string;
    title: string;
    icon: string;
    earned_at: string;
}

interface Mechanic {
    id: number;
    name: string;
    period_points: number;
    rank: number;
    badges: Badge[];
}

interface Props {
    mechanics: Mechanic[];
    period: string;
    [key: string]: unknown;
}

export default function Leaderboard({ mechanics, period }: Props) {
    const handlePeriodChange = (newPeriod: string) => {
        router.get(route('leaderboard'), { period: newPeriod });
    };

    const getPeriodTitle = () => {
        switch (period) {
            case 'daily':
                return 'Hari Ini';
            case 'weekly':
                return 'Minggu Ini';
            case 'monthly':
                return 'Bulan Ini';
            default:
                return 'Bulan Ini';
        }
    };

    const getRankIcon = (rank: number) => {
        switch (rank) {
            case 1:
                return '🥇';
            case 2:
                return '🥈';
            case 3:
                return '🥉';
            default:
                return '🏅';
        }
    };

    const getRankColor = (rank: number) => {
        switch (rank) {
            case 1:
                return 'from-yellow-400 to-yellow-600';
            case 2:
                return 'from-gray-300 to-gray-500';
            case 3:
                return 'from-orange-400 to-orange-600';
            default:
                return 'from-blue-400 to-blue-600';
        }
    };

    return (
        <AppShell>
            <Head title="Leaderboard Mekanik" />
            
            <div className="p-6">
                {/* Header */}
                <div className="mb-8">
                    <h1 className="text-3xl font-bold text-gray-900 mb-2">🏆 Leaderboard Mekanik</h1>
                    <p className="text-gray-600">Peringkat mekanik berdasarkan poin yang dikumpulkan</p>
                </div>

                {/* Period Selector */}
                <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
                    <h2 className="text-xl font-semibold text-gray-900 mb-4">📅 Periode: {getPeriodTitle()}</h2>
                    <div className="flex gap-4">
                        <button
                            onClick={() => handlePeriodChange('daily')}
                            className={`px-4 py-2 rounded-lg font-medium transition-all ${
                                period === 'daily' 
                                    ? 'bg-blue-600 text-white' 
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                            }`}
                        >
                            Hari Ini
                        </button>
                        <button
                            onClick={() => handlePeriodChange('weekly')}
                            className={`px-4 py-2 rounded-lg font-medium transition-all ${
                                period === 'weekly' 
                                    ? 'bg-blue-600 text-white' 
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                            }`}
                        >
                            Minggu Ini
                        </button>
                        <button
                            onClick={() => handlePeriodChange('monthly')}
                            className={`px-4 py-2 rounded-lg font-medium transition-all ${
                                period === 'monthly' 
                                    ? 'bg-blue-600 text-white' 
                                    : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                            }`}
                        >
                            Bulan Ini
                        </button>
                    </div>
                </div>

                {/* Leaderboard */}
                <div className="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div className="p-6 border-b border-gray-200">
                        <h2 className="text-xl font-semibold text-gray-900">🏆 Peringkat Mekanik</h2>
                    </div>
                    <div className="p-6">
                        {mechanics.length > 0 ? (
                            <div className="space-y-4">
                                {mechanics.map((mechanic) => (
                                    <div 
                                        key={mechanic.id} 
                                        className={`relative overflow-hidden rounded-xl p-6 ${
                                            mechanic.rank <= 3 
                                                ? `bg-gradient-to-r ${getRankColor(mechanic.rank)} text-white` 
                                                : 'bg-gray-50'
                                        }`}
                                    >
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center gap-4">
                                                <div className={`w-16 h-16 rounded-full flex items-center justify-center text-2xl font-bold ${
                                                    mechanic.rank <= 3 ? 'bg-white bg-opacity-20' : 'bg-white'
                                                }`}>
                                                    <span className={mechanic.rank <= 3 ? 'text-white' : 'text-gray-700'}>
                                                        #{mechanic.rank}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div className="flex items-center gap-2 mb-1">
                                                        <h3 className={`text-xl font-bold ${
                                                            mechanic.rank <= 3 ? 'text-white' : 'text-gray-900'
                                                        }`}>
                                                            {mechanic.name}
                                                        </h3>
                                                        <span className="text-2xl">{getRankIcon(mechanic.rank)}</span>
                                                    </div>
                                                    <p className={`text-lg font-medium ${
                                                        mechanic.rank <= 3 ? 'text-white opacity-90' : 'text-gray-600'
                                                    }`}>
                                                        {mechanic.period_points || 0} poin
                                                    </p>
                                                    {mechanic.badges.length > 0 && (
                                                        <div className="flex gap-1 mt-2">
                                                            {mechanic.badges.slice(0, 3).map((badge) => (
                                                                <span 
                                                                    key={badge.id}
                                                                    className="text-lg"
                                                                    title={badge.title}
                                                                >
                                                                    {badge.icon}
                                                                </span>
                                                            ))}
                                                            {mechanic.badges.length > 3 && (
                                                                <span className={`text-sm ${
                                                                    mechanic.rank <= 3 ? 'text-white opacity-75' : 'text-gray-500'
                                                                }`}>
                                                                    +{mechanic.badges.length - 3}
                                                                </span>
                                                            )}
                                                        </div>
                                                    )}
                                                </div>
                                            </div>
                                            <div className="text-right">
                                                <Link
                                                    href={route('mechanics.profile', mechanic.id)}
                                                    className={`inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium transition-all ${
                                                        mechanic.rank <= 3 
                                                            ? 'bg-white bg-opacity-20 text-white hover:bg-opacity-30' 
                                                            : 'bg-blue-600 text-white hover:bg-blue-700'
                                                    }`}
                                                >
                                                    👤 Lihat Profil
                                                </Link>
                                            </div>
                                        </div>
                                        
                                        {/* Decorative elements for top 3 */}
                                        {mechanic.rank <= 3 && (
                                            <div className="absolute top-0 right-0 w-20 h-20 opacity-10">
                                                <div className="text-6xl transform rotate-12 translate-x-4 -translate-y-2">
                                                    {getRankIcon(mechanic.rank)}
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="text-center py-12 text-gray-500">
                                <span className="text-6xl block mb-4">🏆</span>
                                <h3 className="text-xl font-semibold mb-2">Belum Ada Data</h3>
                                <p>Belum ada mekanik dengan poin untuk periode ini</p>
                            </div>
                        )}
                    </div>
                </div>

                {/* Info Section */}
                <div className="mt-8 bg-blue-50 rounded-xl p-6">
                    <h3 className="text-lg font-semibold text-blue-900 mb-3">📋 Cara Mendapatkan Poin</h3>
                    <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div className="bg-white p-4 rounded-lg">
                            <div className="text-2xl mb-2">🔧</div>
                            <div className="font-medium text-gray-900">Servis Ringan</div>
                            <div className="text-blue-600">10 poin</div>
                        </div>
                        <div className="bg-white p-4 rounded-lg">
                            <div className="text-2xl mb-2">⚙️</div>
                            <div className="font-medium text-gray-900">Servis Berat</div>
                            <div className="text-blue-600">50 poin</div>
                        </div>
                        <div className="bg-white p-4 rounded-lg">
                            <div className="text-2xl mb-2">✨</div>
                            <div className="font-medium text-gray-900">Kerja Rapi</div>
                            <div className="text-blue-600">15 poin</div>
                        </div>
                        <div className="bg-white p-4 rounded-lg">
                            <div className="text-2xl mb-2">⭐</div>
                            <div className="font-medium text-gray-900">Rating Tinggi</div>
                            <div className="text-blue-600">25 poin</div>
                        </div>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}