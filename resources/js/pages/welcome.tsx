import { type SharedData } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';

interface WorkshopStats {
    totalWorkOrders: number;
    activeWorkOrders: number;
    completedWorkOrders: number;
    totalMechanics: number;
}

interface Props {
    stats?: WorkshopStats;
    [key: string]: unknown;
}

export default function Welcome({ stats }: Props) {
    const { auth } = usePage<SharedData>().props;

    return (
        <>
            <Head title="Sistem Manajemen Bengkel">
                <link rel="preconnect" href="https://fonts.bunny.net" />
                <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
            </Head>
            <div className="flex min-h-screen flex-col items-center bg-gradient-to-br from-blue-50 to-indigo-100 p-6 text-gray-900 lg:justify-center lg:p-8">
                <header className="mb-8 w-full max-w-6xl">
                    <nav className="flex items-center justify-end gap-4">
                        {auth.user ? (
                            <Link
                                href={route('dashboard')}
                                className="inline-flex items-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white shadow-lg hover:bg-blue-700 transition-colors"
                            >
                                🏠 Dashboard
                            </Link>
                        ) : (
                            <>
                                <Link
                                    href={route('login')}
                                    className="inline-flex items-center rounded-lg border border-blue-300 px-6 py-2.5 text-sm font-medium text-blue-700 hover:bg-blue-50 transition-colors"
                                >
                                    Masuk
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="inline-flex items-center rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white shadow-lg hover:bg-blue-700 transition-colors"
                                >
                                    Daftar
                                </Link>
                            </>
                        )}
                    </nav>
                </header>

                <div className="w-full max-w-6xl">
                    <main className="text-center mb-12">
                        {/* Hero Section */}
                        <div className="mb-16">
                            <div className="inline-flex items-center justify-center w-20 h-20 bg-blue-600 rounded-full mb-6">
                                <span className="text-3xl">🔧</span>
                            </div>
                            <h1 className="text-5xl font-bold mb-4 bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent">
                                Sistem Manajemen Bengkel
                            </h1>
                            <p className="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                                Platform komprehensif untuk mengelola operasional bengkel dengan sistem gamifikasi untuk mekanik. 
                                Tingkatkan efisiensi dan motivasi tim Anda! 🚀
                            </p>
                            
                            {!auth.user && (
                                <div className="flex justify-center gap-4">
                                    <Link
                                        href={route('login')}
                                        className="inline-flex items-center rounded-lg bg-blue-600 px-8 py-3 text-lg font-semibold text-white shadow-lg hover:bg-blue-700 transition-all hover:scale-105"
                                    >
                                        🚀 Mulai Sekarang
                                    </Link>
                                    <Link
                                        href={route('register')}
                                        className="inline-flex items-center rounded-lg border-2 border-blue-600 px-8 py-3 text-lg font-semibold text-blue-600 hover:bg-blue-600 hover:text-white transition-all"
                                    >
                                        📝 Daftar Gratis
                                    </Link>
                                </div>
                            )}
                        </div>

                        {/* Stats Section */}
                        {stats && (
                            <div className="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
                                <div className="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                                    <div className="text-3xl mb-2">📋</div>
                                    <div className="text-2xl font-bold text-blue-600">{stats.totalWorkOrders}</div>
                                    <div className="text-sm text-gray-600">Total Work Order</div>
                                </div>
                                <div className="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                                    <div className="text-3xl mb-2">⚡</div>
                                    <div className="text-2xl font-bold text-orange-600">{stats.activeWorkOrders}</div>
                                    <div className="text-sm text-gray-600">Sedang Dikerjakan</div>
                                </div>
                                <div className="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                                    <div className="text-3xl mb-2">✅</div>
                                    <div className="text-2xl font-bold text-green-600">{stats.completedWorkOrders}</div>
                                    <div className="text-sm text-gray-600">Selesai</div>
                                </div>
                                <div className="bg-white rounded-xl p-6 shadow-lg border border-gray-100">
                                    <div className="text-3xl mb-2">👨‍🔧</div>
                                    <div className="text-2xl font-bold text-purple-600">{stats.totalMechanics}</div>
                                    <div className="text-sm text-gray-600">Mekanik Aktif</div>
                                </div>
                            </div>
                        )}

                        {/* Features Section */}
                        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                            <div className="bg-white rounded-xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all">
                                <div className="text-4xl mb-4">📝</div>
                                <h3 className="text-xl font-semibold mb-3">Manajemen Work Order</h3>
                                <p className="text-gray-600">
                                    Kelola semua pekerjaan servis dari penerimaan hingga selesai dengan tracking status real-time
                                </p>
                            </div>

                            <div className="bg-white rounded-xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all">
                                <div className="text-4xl mb-4">👥</div>
                                <h3 className="text-xl font-semibold mb-3">Manajemen Pelanggan</h3>
                                <p className="text-gray-600">
                                    Database pelanggan dan kendaraan lengkap dengan riwayat servis untuk pelayanan terbaik
                                </p>
                            </div>

                            <div className="bg-white rounded-xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all">
                                <div className="text-4xl mb-4">🏆</div>
                                <h3 className="text-xl font-semibold mb-3">Sistem Gamifikasi</h3>
                                <p className="text-gray-600">
                                    Poin dan lencana untuk mekanik berdasarkan kinerja, kualitas kerja, dan kepuasan pelanggan
                                </p>
                            </div>

                            <div className="bg-white rounded-xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all">
                                <div className="text-4xl mb-4">📊</div>
                                <h3 className="text-xl font-semibold mb-3">Leaderboard Mekanik</h3>
                                <p className="text-gray-600">
                                    Papan peringkat harian, mingguan, dan bulanan untuk memotivasi kompetisi sehat
                                </p>
                            </div>

                            <div className="bg-white rounded-xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all">
                                <div className="text-4xl mb-4">⭐</div>
                                <h3 className="text-xl font-semibold mb-3">Rating Pelanggan</h3>
                                <p className="text-gray-600">
                                    Sistem rating dan feedback pelanggan untuk meningkatkan kualitas layanan bengkel
                                </p>
                            </div>

                            <div className="bg-white rounded-xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all">
                                <div className="text-4xl mb-4">🎯</div>
                                <h3 className="text-xl font-semibold mb-3">Lencana Spesialis</h3>
                                <p className="text-gray-600">
                                    Gelar spesialis otomatis untuk mekanik berdasarkan keahlian jenis motor tertentu
                                </p>
                            </div>
                        </div>

                        {/* Point System Section */}
                        <div className="bg-white rounded-2xl p-8 shadow-lg border border-gray-100 mb-16">
                            <h2 className="text-3xl font-bold mb-6 text-gray-800">🎮 Sistem Poin Mekanik</h2>
                            <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-4 text-left">
                                <div className="bg-blue-50 p-4 rounded-lg">
                                    <div className="font-semibold text-blue-800">Servis Ringan</div>
                                    <div className="text-blue-600">10 poin</div>
                                    <div className="text-sm text-gray-600">Ganti oli, tune up</div>
                                </div>
                                <div className="bg-green-50 p-4 rounded-lg">
                                    <div className="font-semibold text-green-800">Servis Berat</div>
                                    <div className="text-green-600">50 poin</div>
                                    <div className="text-sm text-gray-600">Turun mesin, overhaul</div>
                                </div>
                                <div className="bg-purple-50 p-4 rounded-lg">
                                    <div className="font-semibold text-purple-800">Kerja Rapi</div>
                                    <div className="text-purple-600">15 poin</div>
                                    <div className="text-sm text-gray-600">Tanpa revisi</div>
                                </div>
                                <div className="bg-orange-50 p-4 rounded-lg">
                                    <div className="font-semibold text-orange-800">Rating Tinggi</div>
                                    <div className="text-orange-600">25 poin</div>
                                    <div className="text-sm text-gray-600">4-5 bintang</div>
                                </div>
                            </div>
                        </div>

                        {/* CTA Section */}
                        {!auth.user && (
                            <div className="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-8 text-white">
                                <h2 className="text-3xl font-bold mb-4">Siap Mengoptimalkan Bengkel Anda? 🚀</h2>
                                <p className="text-xl mb-6 opacity-90">
                                    Bergabunglah dengan bengkel modern yang menggunakan teknologi terdepan
                                </p>
                                <div className="flex justify-center gap-4">
                                    <Link
                                        href={route('register')}
                                        className="inline-flex items-center rounded-lg bg-white px-8 py-3 text-lg font-semibold text-blue-600 shadow-lg hover:bg-gray-50 transition-all hover:scale-105"
                                    >
                                        ✨ Mulai Gratis Sekarang
                                    </Link>
                                </div>
                            </div>
                        )}
                    </main>

                    <footer className="text-center text-gray-600 text-sm">
                        <p>© 2024 Sistem Manajemen Bengkel - Dibuat dengan ❤️ untuk bengkel Indonesia</p>
                    </footer>
                </div>
            </div>
        </>
    );
}