@extends('Layout.app')
@section('title', 'Dashboard Admin')
@section('content')
    <!-- ==================== Admin Dashboard ==================== -->
    <section data-admin-page="a-dashboard" class="apage">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
            <div>
                <h1 class="text-xl lg:text-2xl font-extrabold text-slate-900 tracking-tight">Dashboard</h1>
                <p class="text-sm text-slate-500 mt-0.5">Ringkasan performa toko hari ini, 29 September 2026</p>
            </div>
            <button onclick="simulateLoading()"
                class="flex items-center gap-2 bg-white border border-slate-200 hover:border-emerald-300 text-slate-600 font-semibold text-sm px-4 py-2.5 rounded-xl transition"><i
                    data-lucide="refresh-cw" class="w-4 h-4"></i> Muat Ulang Data</button>
        </div>

        <!-- Skeleton (loading state) -->
        <div id="dash-skeleton" class="hidden">
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-5 animate-pulse">
                    <div class="h-4 w-24 bg-slate-100 rounded-lg"></div>
                    <div class="h-7 w-32 bg-slate-100 rounded-lg mt-3"></div>
                    <div class="h-3 w-20 bg-slate-100 rounded-lg mt-3"></div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-5 animate-pulse">
                    <div class="h-4 w-24 bg-slate-100 rounded-lg"></div>
                    <div class="h-7 w-32 bg-slate-100 rounded-lg mt-3"></div>
                    <div class="h-3 w-20 bg-slate-100 rounded-lg mt-3"></div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-5 animate-pulse">
                    <div class="h-4 w-24 bg-slate-100 rounded-lg"></div>
                    <div class="h-7 w-32 bg-slate-100 rounded-lg mt-3"></div>
                    <div class="h-3 w-20 bg-slate-100 rounded-lg mt-3"></div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-5 animate-pulse">
                    <div class="h-4 w-24 bg-slate-100 rounded-lg"></div>
                    <div class="h-7 w-32 bg-slate-100 rounded-lg mt-3"></div>
                    <div class="h-3 w-20 bg-slate-100 rounded-lg mt-3"></div>
                </div>
            </div>
            <div class="grid xl:grid-cols-3 gap-4 mt-4">
                <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl p-5 animate-pulse h-80">
                    <div class="h-4 w-40 bg-slate-100 rounded-lg"></div>
                    <div class="h-56 bg-slate-50 rounded-xl mt-4"></div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-5 animate-pulse h-80">
                    <div class="h-4 w-40 bg-slate-100 rounded-lg"></div>
                    <div class="h-56 bg-slate-50 rounded-xl mt-4"></div>
                </div>
            </div>
        </div>

        <div id="dash-content">
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="flex items-center justify-between"><span
                            class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center"><i
                                data-lucide="wallet" class="w-5 h-5 text-emerald-700"></i></span><span
                            class="text-xs font-bold text-emerald-600 flex items-center gap-0.5"><i
                                data-lucide="trending-up" class="w-3.5 h-3.5"></i>+12,4%</span></div>
                    <p class="text-xl lg:text-2xl font-extrabold text-slate-900 mt-3">Rp48,25 jt</p>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Total pendapatan bulan ini</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="flex items-center justify-between"><span
                            class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center"><i
                                data-lucide="shopping-bag" class="w-5 h-5 text-sky-600"></i></span><span
                            class="text-xs font-bold text-emerald-600 flex items-center gap-0.5"><i
                                data-lucide="trending-up" class="w-3.5 h-3.5"></i>+8,1%</span></div>
                    <p class="text-xl lg:text-2xl font-extrabold text-slate-900 mt-3">342</p>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Pesanan bulan ini</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="flex items-center justify-between"><span
                            class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center"><i
                                data-lucide="package" class="w-5 h-5 text-orange-600"></i></span><span
                            class="text-xs font-bold text-red-500 flex items-center gap-0.5"><i data-lucide="trending-down"
                                class="w-3.5 h-3.5"></i>-2,3%</span></div>
                    <p class="text-xl lg:text-2xl font-extrabold text-slate-900 mt-3">1.284</p>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Total produk aktif</p>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="flex items-center justify-between"><span
                            class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center"><i
                                data-lucide="users" class="w-5 h-5 text-emerald-700"></i></span><span
                            class="text-xs font-bold text-emerald-600 flex items-center gap-0.5"><i
                                data-lucide="trending-up" class="w-3.5 h-3.5"></i>+15,7%</span></div>
                    <p class="text-xl lg:text-2xl font-extrabold text-slate-900 mt-3">2.914</p>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Pelanggan terdaftar</p>
                </div>
            </div>

            <div class="grid xl:grid-cols-3 gap-4 mt-4">
                <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl p-5">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="font-bold text-slate-900">Grafik Penjualan</h2>
                            <p class="text-xs text-slate-400 mt-0.5">7 hari terakhir</p>
                        </div>
                        <select onchange="toast('Rentang grafik diubah','success')"
                            class="bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            <option>7 hari</option>
                            <option>30 hari</option>
                            <option>12 bulan</option>
                        </select>
                    </div>
                    <div class="h-64"><canvas id="salesChart"></canvas></div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-5">
                    <h2 class="font-bold text-slate-900">Kategori Terlaris</h2>
                    <p class="text-xs text-slate-400 mt-0.5 mb-4">Berdasarkan pendapatan bulan ini</p>
                    <div class="h-48"><canvas id="categoryChart"></canvas></div>
                    <div class="space-y-2 mt-4 text-xs font-semibold text-slate-600">
                        <p class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-700"></span>
                            Kopi & Minuman - 34%</p>
                        <p class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                            Camilan - 26%</p>
                        <p class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-orange-400"></span>
                            Fashion - 22%</p>
                        <p class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                            Lainnya - 18%</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl mt-4 overflow-hidden">
                <div class="flex items-center justify-between p-5 pb-4">
                    <div>
                        <h2 class="font-bold text-slate-900">Pesanan Terbaru</h2>
                        <p class="text-xs text-slate-400 mt-0.5">5 pesanan terakhir yang masuk</p>
                    </div>
                    <button onclick="goAdmin('a-orders')"
                        class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">Lihat semua</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm min-w-[640px]">
                        <thead>
                            <tr class="bg-slate-50 text-left text-xs text-slate-500 font-semibold">
                                <th class="px-5 py-3">Invoice</th>
                                <th class="px-5 py-3">Pelanggan</th>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Total</th>
                                <th class="px-5 py-3">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="goAdmin('a-order-detail')">
                                <td class="px-5 py-3.5 font-bold text-slate-800">#INV-2843</td>
                                <td class="px-5 py-3.5 text-slate-600">Bayu Nugroho</td>
                                <td class="px-5 py-3.5 text-slate-500">29 Sep 2026</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800">Rp412.000</td>
                                <td class="px-5 py-3.5"><span
                                        class="text-xs font-bold px-2.5 py-1 rounded-full bg-amber-50 text-amber-700">Menunggu</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="goAdmin('a-order-detail')">
                                <td class="px-5 py-3.5 font-bold text-slate-800">#INV-2842</td>
                                <td class="px-5 py-3.5 text-slate-600">Siti Rahmawati</td>
                                <td class="px-5 py-3.5 text-slate-500">29 Sep 2026</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800">Rp186.500</td>
                                <td class="px-5 py-3.5"><span
                                        class="text-xs font-bold px-2.5 py-1 rounded-full bg-blue-50 text-blue-700">Dibayar</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="goAdmin('a-order-detail')">
                                <td class="px-5 py-3.5 font-bold text-slate-800">#INV-2841</td>
                                <td class="px-5 py-3.5 text-slate-600">Rina Sulastri</td>
                                <td class="px-5 py-3.5 text-slate-500">29 Sep 2026</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800">Rp298.431</td>
                                <td class="px-5 py-3.5"><span
                                        class="text-xs font-bold px-2.5 py-1 rounded-full bg-sky-50 text-sky-700">Dikirim</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="goAdmin('a-order-detail')">
                                <td class="px-5 py-3.5 font-bold text-slate-800">#INV-2840</td>
                                <td class="px-5 py-3.5 text-slate-600">Ahmad Fauzi</td>
                                <td class="px-5 py-3.5 text-slate-500">28 Sep 2026</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800">Rp95.000</td>
                                <td class="px-5 py-3.5"><span
                                        class="text-xs font-bold px-2.5 py-1 rounded-full bg-orange-50 text-orange-700">Diproses</span>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50 transition cursor-pointer" onclick="goAdmin('a-order-detail')">
                                <td class="px-5 py-3.5 font-bold text-slate-800">#INV-2839</td>
                                <td class="px-5 py-3.5 text-slate-600">Dewi Anggraini</td>
                                <td class="px-5 py-3.5 text-slate-500">28 Sep 2026</td>
                                <td class="px-5 py-3.5 font-semibold text-slate-800">Rp523.000</td>
                                <td class="px-5 py-3.5"><span
                                        class="text-xs font-bold px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700">Selesai</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection
