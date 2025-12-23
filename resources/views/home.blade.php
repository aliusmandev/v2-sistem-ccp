@extends('layouts.app')

@section('content')
    <div class="welcome d-lg-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center welcome-text">
            <h3 class="d-flex align-items-center">
                <img src="assets/img/icons/hi.svg" alt="img">&nbsp;
                Hai, {{ Auth::user()->name ?? 'User' }}!
            </h3>, &nbsp;
            <h6 id="random-quote" class="mt-1"></h6>


        </div>
        <div class="d-flex align-items-center">
            <h5 class="mb-0" id="tanggal-jam"></h5>
        </div>
    </div>
    <div class="row sales-cards">
        <div class="col-xl-6 col-sm-12 col-12">
            <div class="card d-flex align-items-center justify-content-between default-cover mb-4">
                <div>
                    <h6>Jumlah Permintaan Pembelian</h6>
                    <h3><span class="counters" data-count="{{ $TotalPermintaan }}">{{ $TotalPermintaan }} </span>
                        Permintaan
                    </h3>
                    <p class="sales-range"><span class="text-info"><i data-feather="info" class="feather-16"></i></span>
                        Keterangan: Jumlah total permintaan pembelian yang tercatat pada sistem hingga hari ini.</p>
                </div>
                <img src="{{ asset('assets/img/ccp/icon/monitor.png') }}" alt="img" style="width:90px; height:90px;">
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card color-info bg-primary mb-4 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <h3 class="counters" data-count="{{ $TotalSelesai }}">{{ $TotalSelesai }}</h3>
                    <p>Jumlah Permintaan Selesai</p>
                </div>
                <img src="{{ asset('assets/img/ccp/icon/done.png') }}" alt="img" style="width:90px; height:90px;">
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 col-12">
            <div class="card color-info bg-secondary mb-4 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <h3 class="counters" data-count="{{ $TotalPermintaan - $TotalSelesai }}">
                        {{ $TotalPermintaan - $TotalSelesai }}</h3>
                    <p>Dalam Proses Pengajuan / Review</p>
                </div>
                <div>
                    <img src="{{ asset('assets/img/ccp/icon/reload.png') }}" alt="img"
                        style="width:90px; height:90px;">
                    <i data-feather="rotate-ccw" class="feather-16 ms-2" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Refresh"></i>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        // Array kata-kata random
        const quotes = [
            "Yuk, produktif hari ini! 💪",
            "Santai, tapi tetap berkarya ya 😎☕",
            "Ingat: Rejeki ngga ke mana, tapi deadline ke mana-mana 😜",
            "Gas pol! Jangan kasih kendor 🔥",
            "Kesalahan itu biasa, semangatnya yang luar biasa!",
            "Ngopi dulu biar otaknya encer ☕💡",
            "Tetap on, walau kadang pingin skip hari ini 🤣",
            "Work smart, bukan work overthinking ✨",
            "Healing boleh, produktif tetap on track 👌",
            "Kerja bagus, self-reward jangan lupa 🍦",
            "Semangat, sekecil apapun progresmu hari ini! 🙌",
            "Jangan lupa bahagia, biar kerja lancar ya 😁🎉",
            "Setiap hari adalah peluang baru untuk belajar 📚✨",
            "Pekerjaan berat terasa ringan kalau dikerjakan bareng 🤝",
            "Target hari ini = selesai satu tugas penting dulu!",
            "Break sebentar, lanjut produktif lagi yuk 🚀",
            "Jangan terburu-buru, hasil bagus datang dari proses 🍀",
            "Senyum dulu, biar urusan kerjaan ikut menyenangkan 😊",
            "Waktunya upgrade skill, take action sekarang! 🏆",
            "Ingat: Lebih baik selesai daripada sempurna tapi tertunda!"
        ];
        document.addEventListener('DOMContentLoaded', function() {
            const randomText = quotes[Math.floor(Math.random() * quotes.length)];
            document.getElementById('random-quote').innerText = randomText;
        });
    </script>
    <script>
        function updateDateTime() {
            const bulan = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];
            const hari = [
                'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
            ];
            const now = new Date();
            const namaHari = hari[now.getDay()];
            const tanggal = now.getDate();
            const namaBulan = bulan[now.getMonth()];
            const tahun = now.getFullYear();

            let jam = now.getHours();
            let menit = now.getMinutes();
            let detik = now.getSeconds();
            jam = jam < 10 ? '0' + jam : jam;
            menit = menit < 10 ? '0' + menit : menit;
            detik = detik < 10 ? '0' + detik : detik;

            const str = `${namaHari}, ${tanggal} ${namaBulan} ${tahun} - ${jam}:${menit}:${detik}`;
            document.getElementById('tanggal-jam').innerHTML = str;
        }

        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>
@endpush
