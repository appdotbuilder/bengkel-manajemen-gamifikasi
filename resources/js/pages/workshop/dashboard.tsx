import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { AppShell } from '@/components/app-shell';

interface WorkOrder {
    id: number;
    wo_number: string;
    status: string;
    customer: {
        name: string;
    };
    vehicle: {
        brand: string;
        model: string;
        license_plate: string;
    };
    assigned_mechanic?: {
        name: string;
    };
    created_at: string;
}

interface Mechanic {
    id: number;
    name: string;
    monthly_points: number;
}

interface Stats {
    totalWorkOrders: number;
    activeWorkOrders: number;
    completedWorkOrders: number;
    totalMechanics: number;
}

interface Props {
    stats: Stats;
    recentWorkOrders: WorkOrder[];
    topMechanics: Mechanic[];
    [key: string]: unknown;
}

export default function Dashboard({ stats, recentWorkOrders, topMechanics }: Props) {
    const getStatusColor = (status: string) => {
        switch (status) {
            case 'menunggu':
                return 'bg-yellow-100 text-yellow-800 border-yellow-200';
            case 'dikerjakan':
                return 'bg-blue-100 text-blue-800 border-blue-200';
            case 'pengecekan':
                return 'bg-orange-100 text-orange-800 border-orange-200';
            case 'selesai':
                return 'bg-green-100 text-green-800 border-green-200';
            default:
                return 'bg-gray-100 text-gray-800 border-gray-200';
        }
    };

    const getStatusText = (status: string) => {
        switch (status) {
            case 'menunggu':
                return 'Menunggu';
            case 'dikerjakan':
                return 'Dikerjakan';
            case 'pengecekan':
                return 'Pengecekan';
            case 'selesai':
                return 'Selesai';
            default:
                return status;
        }
    };

    return (
        <AppShell>
            <Head title="Dashboard Bengkel" />
            
            <div className="p-6">
                {/* Header */}
                <div className="mb-8">
                    <h1 className="text-3xl font-bold text-gray-900 mb-2">🔧 Dashboard Bengkel</h1>
                    <p className="text-gray-600">Selamat datang kembali! Berikut ringkasan aktivitas bengkel Anda.</p>
                </div>

                {/* Stats Cards */}
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm text-gray-600 mb-1">Total Work Order</p>
                                <p className="text-2xl font-bold text-gray-900">{stats.totalWorkOrders}</p>
                            </div>
                            <div className="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span className="text-2xl">📋</span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm text-gray-600 mb-1">Sedang Aktif</p>
                                <p className="text-2xl font-bold text-orange-600">{stats.activeWorkOrders}</p>
                            </div>
                            <div className="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <span className="text-2xl">⚡</span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm text-gray-600 mb-1">Selesai</p>
                                <p className="text-2xl font-bold text-green-600">{stats.completedWorkOrders}</p>
                            </div>
                            <div className="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <span className="text-2xl">✅</span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-sm text-gray-600 mb-1">Mekanik Aktif</p>
                                <p className="text-2xl font-bold text-purple-600">{stats.totalMechanics}</p>
                            </div>
                            <div className="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <span className="text-2xl">👨‍🔧</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="grid lg:grid-cols-2 gap-6">
                    {/* Recent Work Orders */}
                    <div className="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div className="p-6 border-b border-gray-200">
                            <div className="flex items-center justify-between">
                                <h2 className="text-xl font-semibold text-gray-900">📋 Work Order Terbaru</h2>
                                <Link 
                                    href={route('work-orders.index')} 
                                    className="text-blue-600 hover:text-blue-700 text-sm font-medium"
                                >
                                    Lihat Semua →
                                </Link>
                            </div>
                        </div>
                        <div className="p-6">
                            {recentWorkOrders.length > 0 ? (
                                <div className="space-y-4">
                                    {recentWorkOrders.map((workOrder) => (
                                        <div key={workOrder.id} className="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                            <div>
                                                <div className="flex items-center gap-3 mb-2">
                                                    <span className="font-medium text-gray-900">#{workOrder.wo_number}</span>
                                                    <span className={`px-2 py-1 text-xs font-medium rounded-full border ${getStatusColor(workOrder.status)}`}>
                                                        {getStatusText(workOrder.status)}
                                                    </span>
                                                </div>
                                                <p className="text-sm text-gray-600">{workOrder.customer.name}</p>
                                                <p className="text-sm text-gray-500">{workOrder.vehicle.brand} {workOrder.vehicle.model} - {workOrder.vehicle.license_plate}</p>
                                                {workOrder.assigned_mechanic && (
                                                    <p className="text-xs text-blue-600 mt-1">👨‍🔧 {workOrder.assigned_mechanic.name}</p>
                                                )}
                                            </div>
                                            <div className="text-right">
                                                <p className="text-xs text-gray-500">
                                                    {new Date(workOrder.created_at).toLocaleDateString('id-ID')}
                                                </p>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="text-center py-8 text-gray-500">
                                    <span className="text-4xl block mb-2">📝</span>
                                    <p>Belum ada work order</p>
                                </div>
                            )}
                        </div>
                    </div>

                    {/* Top Mechanics */}
                    <div className="bg-white rounded-xl shadow-sm border border-gray-200">
                        <div className="p-6 border-b border-gray-200">
                            <div className="flex items-center justify-between">
                                <h2 className="text-xl font-semibold text-gray-900">🏆 Top Mekanik Bulan Ini</h2>
                                <Link 
                                    href={route('leaderboard')} 
                                    className="text-blue-600 hover:text-blue-700 text-sm font-medium"
                                >
                                    Lihat Leaderboard →
                                </Link>
                            </div>
                        </div>
                        <div className="p-6">
                            {topMechanics.length > 0 ? (
                                <div className="space-y-4">
                                    {topMechanics.map((mechanic, index) => (
                                        <div key={mechanic.id} className="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                            <div className="flex items-center gap-3">
                                                <div className="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold text-sm">
                                                    {index + 1}
                                                </div>
                                                <div>
                                                    <p className="font-medium text-gray-900">{mechanic.name}</p>
                                                    <p className="text-sm text-gray-600">{mechanic.monthly_points || 0} poin</p>
                                                </div>
                                            </div>
                                            <div className="flex items-center">
                                                {index === 0 && <span className="text-2xl">🥇</span>}
                                                {index === 1 && <span className="text-2xl">🥈</span>}
                                                {index === 2 && <span className="text-2xl">🥉</span>}
                                                {index > 2 && <span className="text-lg">🏅</span>}
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            ) : (
                                <div className="text-center py-8 text-gray-500">
                                    <span className="text-4xl block mb-2">👨‍🔧</span>
                                    <p>Belum ada data mekanik</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Quick Actions */}
                <div className="mt-8 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl p-6 text-white">
                    <h2 className="text-xl font-semibold mb-4">🚀 Aksi Cepat</h2>
                    <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <Link 
                            href={route('work-orders.create')} 
                            className="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-opacity-30 transition-all"
                        >
                            <span className="text-2xl block mb-2">📝</span>
                            <span className="text-sm font-medium">Buat WO Baru</span>
                        </Link>
                        <Link 
                            href={route('customers.create')} 
                            className="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-opacity-30 transition-all"
                        >
                            <span className="text-2xl block mb-2">👥</span>
                            <span className="text-sm font-medium">Tambah Pelanggan</span>
                        </Link>
                        <Link 
                            href={route('work-orders.index')} 
                            className="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-opacity-30 transition-all"
                        >
                            <span className="text-2xl block mb-2">📋</span>
                            <span className="text-sm font-medium">Kelola WO</span>
                        </Link>
                        <Link 
                            href={route('leaderboard')} 
                            className="bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-4 text-center hover:bg-opacity-30 transition-all"
                        >
                            <span className="text-2xl block mb-2">🏆</span>
                            <span className="text-sm font-medium">Leaderboard</span>
                        </Link>
                    </div>
                </div>
            </div>
        </AppShell>
    );
}