@extends('layouts.public')

@section('title', 'Pendataan Alumni')

@section('content')
<script>
    window.initialRegisterData = {!! json_encode([
        'nama' => old('nama', ''),
        'nim' => old('nim', ''),
        'nik' => old('nik', ''),
        'tempat_lahir' => old('tempat_lahir', ''),
        'tanggal_lahir' => old('tanggal_lahir', ''),
        'jenis_kelamin' => old('jenis_kelamin', ''),
        'jurusan' => old('jurusan', ''),
        'program_studi' => old('program_studi', ''),
        'tahun_masuk' => old('tahun_masuk', ''),
        'tahun_lulus' => old('tahun_lulus', ''),
        'email' => old('email', ''),
        'no_hp' => old('no_hp', ''),
        'alamat' => old('alamat', '')
    ]) !!};

    window.registerFormData = function() {
        return {
            step: 1,
            isSubmitting: false,
            errorMessage: '',
            draftRestored: false,
            
            // Step 1 Data
            nama: '',
            nim: '',
            nik: '',
            tempat_lahir: '',
            tanggal_lahir: '',
            jenis_kelamin: '',
            program_studi: '',
            jurusan: '',
            tahun_masuk: '',
            tahun_lulus: '',
            email: '',
            no_hp: '',
            alamat: '',
            
            // Step 2 File Names & Preview
            pasFotoName: '',
            pasFotoPreview: '',
            berkasPersyaratanName: '',
            berkasPersyaratanSize: '',

            // Master Data Jurusan & Program Studi Poltekkes Kemenkes Denpasar
            jurusanMap: {
                'Keperawatan': [
                    'D3 Keperawatan',
                    'D4/Str Keperawatan',
                    'Profesi Ners'
                ],
                'Kebidanan': [
                    'D3 Kebidanan',
                    'D4/Str Kebidanan',
                    'Profesi Bidan'
                ],
                'Kesehatan Gigi': [
                    'D3 Kesehatan Gigi'
                ],
                'Gizi': [
                    'D3 Gizi',
                    'Str Gizi',
                    'Dietisien Gizi'
                ],
                'Teknologi Laboratorium Medis': [
                    'D3 TLM',
                    'Str TLM'
                ],
                'Kesehatan Lingkungan': [
                    'D3 Sanitasi',
                    'Str Kesehatan Lingkungan'
                ]
            },

            get availableProdiList() {
                return (this.jurusan && this.jurusanMap[this.jurusan]) ? this.jurusanMap[this.jurusan] : [];
            },

            onJurusanChange() {
                if (!this.availableProdiList.includes(this.program_studi)) {
                    this.program_studi = '';
                }
                this.$nextTick(() => {
                    const select = this.$refs.prodiSelect || document.getElementById('program_studi');
                    if (select && this.jurusan) {
                        select.focus();
                    }
                });
            },

            fpInstance: null,

            initDatePicker() {
                this.$nextTick(() => {
                    const el = this.$refs.tanggalLahirInput || document.getElementById('tanggal_lahir');
                    if (el && typeof flatpickr !== 'undefined') {
                        if (this.fpInstance) {
                            try { this.fpInstance.destroy(); } catch (e) {}
                        }
                        this.fpInstance = flatpickr(el, {
                            locale: 'id',
                            dateFormat: 'Y-m-d',
                            altInput: true,
                            altFormat: 'd/m/Y',
                            altInputClass: 'w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 bg-white cursor-pointer',
                            allowInput: true,
                            defaultDate: this.tanggal_lahir || undefined,
                            onChange: (selectedDates, dateStr) => {
                                this.tanggal_lahir = dateStr;
                            },
                            onValueUpdate: (selectedDates, dateStr) => {
                                this.tanggal_lahir = dateStr;
                            },
                            onClose: (selectedDates, dateStr) => {
                                this.tanggal_lahir = dateStr;
                            }
                        });
                    }
                });
            },

            formatDateDMY(dateStr) {
                if (!dateStr) return '';
                if (/^\d{4}-\d{2}-\d{2}$/.test(dateStr)) {
                    const parts = dateStr.split('-');
                    return `${parts[2]}/${parts[1]}/${parts[0]}`;
                }
                return dateStr;
            },

            // Step 3 Checkbox
            pernyataan: false,

            init() {
                this.loadDraft();
                this.initDatePicker();

                if ({!! json_encode($errors->any()) !!}) {
                    this.step = 1;
                    this.$nextTick(() => {
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    });
                }

                // Watch fields to auto-save draft
                ['nama', 'nim', 'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'program_studi', 'jurusan', 'tahun_masuk', 'tahun_lulus', 'email', 'no_hp', 'alamat', 'step'].forEach(field => {
                    this.$watch(field, () => this.saveDraft());
                });
            },

            loadDraft() {
                try {
                    const raw = localStorage.getItem('sijitu_register_draft');
                    if (!raw) {
                        this.loadOldValues();
                        return;
                    }
                    const parsed = JSON.parse(raw);
                    const now = Date.now();
                    const oneHourInMs = 60 * 60 * 1000;
                    
                    if (now - parsed.timestamp < oneHourInMs) {
                        const d = parsed.data || {};
                        const init = window.initialRegisterData || {};
                        this.nama = d.nama || init.nama || '';
                        this.nim = d.nim || init.nim || '';
                        this.nik = d.nik || init.nik || '';
                        this.tempat_lahir = d.tempat_lahir || init.tempat_lahir || '';
                        this.tanggal_lahir = d.tanggal_lahir || init.tanggal_lahir || '';
                        if (this.fpInstance && this.tanggal_lahir) {
                            this.fpInstance.setDate(this.tanggal_lahir, false);
                        }
                        this.jenis_kelamin = d.jenis_kelamin || init.jenis_kelamin || '';
                        this.jurusan = d.jurusan || init.jurusan || '';
                        const savedProdi = d.program_studi || init.program_studi || '';
                        this.program_studi = savedProdi;
                        this.tahun_masuk = d.tahun_masuk || init.tahun_masuk || '';
                        this.tahun_lulus = d.tahun_lulus || init.tahun_lulus || '';
                        this.email = d.email || init.email || '';
                        this.no_hp = d.no_hp || init.no_hp || '';
                        this.alamat = d.alamat || init.alamat || '';
                        if (d.step && d.step >= 1 && d.step <= 3) this.step = d.step;
                        this.draftRestored = true;
                    } else {
                        localStorage.removeItem('sijitu_register_draft');
                        this.loadOldValues();
                    }
                } catch (e) {
                    this.loadOldValues();
                }
            },

            loadOldValues() {
                const init = window.initialRegisterData || {};
                this.nama = init.nama || '';
                this.nim = init.nim || '';
                this.nik = init.nik || '';
                this.tempat_lahir = init.tempat_lahir || '';
                this.tanggal_lahir = init.tanggal_lahir || '';
                if (this.fpInstance && this.tanggal_lahir) {
                    this.fpInstance.setDate(this.tanggal_lahir, false);
                }
                this.jenis_kelamin = init.jenis_kelamin || '';
                this.jurusan = init.jurusan || '';
                this.program_studi = init.program_studi || '';
                this.tahun_masuk = init.tahun_masuk || '';
                this.tahun_lulus = init.tahun_lulus || '';
                this.email = init.email || '';
                this.no_hp = init.no_hp || '';
                this.alamat = init.alamat || '';
            },

            saveDraft() {
                try {
                    const draft = {
                        timestamp: Date.now(),
                        data: {
                            nama: this.nama,
                            nim: this.nim,
                            nik: this.nik,
                            tempat_lahir: this.tempat_lahir,
                            tanggal_lahir: this.tanggal_lahir,
                            jenis_kelamin: this.jenis_kelamin,
                            program_studi: this.program_studi,
                            jurusan: this.jurusan,
                            tahun_masuk: this.tahun_masuk,
                            tahun_lulus: this.tahun_lulus,
                            email: this.email,
                            no_hp: this.no_hp,
                            alamat: this.alamat,
                            step: this.step
                        }
                    };
                    localStorage.setItem('sijitu_register_draft', JSON.stringify(draft));
                } catch (e) {}
            },

            clearDraft() {
                localStorage.removeItem('sijitu_register_draft');
                this.nama = '';
                this.nim = '';
                this.nik = '';
                this.tempat_lahir = '';
                this.tanggal_lahir = '';
                if (this.fpInstance) {
                    try { this.fpInstance.clear(); } catch (e) {}
                }
                this.jenis_kelamin = '';
                this.program_studi = '';
                this.jurusan = '';
                this.tahun_masuk = '';
                this.tahun_lulus = '';
                this.email = '';
                this.no_hp = '';
                this.alamat = '';
                this.step = 1;
                this.draftRestored = false;
            },
            
            validateStep1() {
                this.errorMessage = '';
                const n = (this.nama || '').trim();
                const ni = (this.nim || '').trim();
                const nk = (this.nik || '').trim();
                const tl = (this.tempat_lahir || '').trim();
                const tgl = (this.tanggal_lahir || '').trim();
                const jk = (this.jenis_kelamin || '').trim();
                const ps = (this.program_studi || '').trim();
                const jr = (this.jurusan || '').trim();
                const tm = String(this.tahun_masuk || '').trim();
                const tlulus = String(this.tahun_lulus || '').trim();
                const em = (this.email || '').trim();
                const hp = (this.no_hp || '').trim();
                const al = (this.alamat || '').trim();

                if (!n || !ni || !nk || !tl || !tgl || !jk || !ps || !jr || !tm || !tlulus || !em || !hp || !al) {
                    this.errorMessage = 'Mohon lengkapi seluruh kolom biodata pada Langkah 1 terlebih dahulu.';
                    return false;
                }
                if (nk.length !== 16) {
                    this.errorMessage = 'NIK KTP harus tepat berjumlah 16 digit angka.';
                    return false;
                }
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailRegex.test(em)) {
                    this.errorMessage = 'Format email tidak valid. Email harus menggunakan "@" dan nama domain yang benar (contoh: alumni@email.com).';
                    return false;
                }
                const tMasuk = parseInt(tm);
                const tLulus = parseInt(tlulus);
                if (isNaN(tMasuk)) {
                    this.errorMessage = 'Tahun masuk harus diisi dengan angka tahun yang valid.';
                    return false;
                }
                if (isNaN(tLulus)) {
                    this.errorMessage = 'Tahun lulus harus diisi dengan angka tahun yang valid.';
                    return false;
                }
                return true;
            },

            validateStep2() {
                this.errorMessage = '';
                if (!this.pasFotoName) {
                    this.errorMessage = 'Mohon unggah Pas Foto resmi (Maksimal 1 MB, JPG/PNG).';
                    return false;
                }
                if (!this.berkasPersyaratanName) {
                    this.errorMessage = 'Mohon unggah 1 file PDF gabungan 5 Berkas Persyaratan (Maksimal 1 MB).';
                    return false;
                }
                return true;
            },

            nextStep() {
                this.errorMessage = '';
                if (this.step === 1) {
                    if (this.validateStep1()) {
                        this.step = 2;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                } else if (this.step === 2) {
                    if (this.validateStep2()) {
                        this.step = 3;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }
            },

            prevStep() {
                this.errorMessage = '';
                if (this.step > 1) {
                    this.step--;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            },

            goToStep(target) {
                this.errorMessage = '';
                if (target === 1) {
                    this.step = 1;
                } else if (target === 2) {
                    if (this.validateStep1()) this.step = 2;
                }
            },

            handleFormSubmit(e) {
                this.errorMessage = '';
                if (!this.validateStep1()) {
                    this.step = 1;
                    return;
                }
                if (!this.validateStep2()) {
                    this.step = 2;
                    return;
                }
                if (!this.pernyataan) {
                    this.errorMessage = 'Anda harus mengcentang pernyataan konfirmasi sebelum mengirim data.';
                    return;
                }
                this.isSubmitting = true;
                localStorage.removeItem('sijitu_register_draft');
                if (e && e.target) {
                    HTMLFormElement.prototype.submit.call(e.target);
                }
            }
        };
    };
</script>

<div class="max-w-4xl mx-auto px-4 py-12 sm:px-6 lg:px-8">
    <!-- Main Card -->
    <div 
        x-data="registerFormData()"
        class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden"
    >
        <!-- Header & Step Indicator -->
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/40">
            <h2 class="text-xl font-extrabold text-slate-800 tracking-tight">Formulir Pendataan Alumni - SIJITU</h2>
            <p class="text-xs text-slate-500 mt-1">Lengkapi data diri Anda secara berkala melalui langkah-langkah di bawah ini.</p>
            
            <!-- Progress Bar Indicator Symmetrical Flex Layout -->
            <div class="mt-8 max-w-2xl mx-auto px-4 sm:px-8">
                <div class="flex items-center justify-between">
                    <!-- Step 1 Circle -->
                    <button type="button" @click="goToStep(1)" class="flex flex-col items-center group focus:outline-none">
                        <div 
                            class="w-9 h-9 rounded-full flex items-center justify-center font-extrabold text-xs border-4 transition-all duration-300"
                            :class="step >= 1 ? 'bg-primary text-white border-primary/20 shadow-md shadow-primary/20' : 'bg-slate-100 text-slate-400 border-slate-200'"
                        >
                            1
                        </div>
                        <span class="text-xs font-bold mt-2 transition-colors duration-300" :class="step >= 1 ? 'text-primary' : 'text-slate-400'">Biodata</span>
                    </button>

                    <!-- Connecting Line 1 -> 2 -->
                    <div class="flex-1 mx-4 h-1 rounded-full transition-colors duration-300 overflow-hidden bg-slate-200">
                        <div 
                            class="h-full bg-primary transition-all duration-500"
                            :style="step >= 2 ? 'width: 100%' : 'width: 0%'"
                        ></div>
                    </div>

                    <!-- Step 2 Circle -->
                    <button type="button" @click="goToStep(2)" class="flex flex-col items-center group focus:outline-none">
                        <div 
                            class="w-9 h-9 rounded-full flex items-center justify-center font-extrabold text-xs border-4 transition-all duration-300"
                            :class="step >= 2 ? 'bg-primary text-white border-primary/20 shadow-md shadow-primary/20' : 'bg-slate-100 text-slate-400 border-slate-200'"
                        >
                            2
                        </div>
                        <span class="text-xs font-bold mt-2 transition-colors duration-300" :class="step >= 2 ? 'text-primary' : 'text-slate-400'">Upload Berkas</span>
                    </button>

                    <!-- Connecting Line 2 -> 3 -->
                    <div class="flex-1 mx-4 h-1 rounded-full transition-colors duration-300 overflow-hidden bg-slate-200">
                        <div 
                            class="h-full bg-primary transition-all duration-500"
                            :style="step >= 3 ? 'width: 100%' : 'width: 0%'"
                        ></div>
                    </div>

                    <!-- Step 3 Circle -->
                    <div class="flex flex-col items-center">
                        <div 
                            class="w-9 h-9 rounded-full flex items-center justify-center font-extrabold text-xs border-4 transition-all duration-300"
                            :class="step === 3 ? 'bg-primary text-white border-primary/20 shadow-md shadow-primary/20' : 'bg-slate-100 text-slate-400 border-slate-200'"
                        >
                            3
                        </div>
                        <span class="text-xs font-bold mt-2 transition-colors duration-300" :class="step === 3 ? 'text-primary' : 'text-slate-400'">Konfirmasi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Banner Warning / Flash Error -->
        <div class="p-8 pb-0 space-y-4">
            <!-- Draft restored alert -->
            <div x-show="draftRestored" x-transition class="p-4 bg-blue-50 border border-blue-200 rounded-2xl flex items-center justify-between text-xs text-blue-800">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Draft isian Anda sebelumnya dipulihkan secara otomatis (tersimpan selama 1 jam).</span>
                </div>
                <button type="button" @click="clearDraft()" class="text-xs font-bold text-rose-600 hover:underline">Reset Isian</button>
            </div>

            <template x-if="errorMessage">
                <div class="p-4 bg-rose-50 border border-rose-200 rounded-2xl flex items-start gap-3 text-rose-800 text-xs shadow-sm">
                    <svg class="w-5 h-5 text-rose-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="flex-grow font-semibold" x-text="errorMessage"></div>
                </div>
            </template>

            @if ($errors->any())
                <x-alert type="danger">
                    <div class="font-bold text-xs mb-1">Terjadi kesalahan input data:</div>
                    <ul class="list-disc pl-4 text-xs space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif
        </div>

        <form action="{{ route('public.register.store') }}" method="POST" enctype="multipart/form-data" @submit.prevent="handleFormSubmit($event)" class="p-8 pt-4">
            @csrf

            <!-- ================= STEP 1: BIODATA ================= -->
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div>
                        <label for="nama" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-600">*</span></label>
                        <input type="text" name="nama" id="nama" x-model="nama" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" placeholder="Masukkan nama lengkap">
                    </div>

                    <!-- NIM -->
                    <div>
                        <label for="nim" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">NIM (Nomor Induk Mahasiswa) <span class="text-rose-600">*</span></label>
                        <input type="text" name="nim" id="nim" x-model="nim" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" placeholder="Masukkan NIM">
                    </div>

                    <!-- NIK -->
                    <div>
                        <label for="nik" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">NIK (KTP 16 Digit) <span class="text-rose-600">*</span></label>
                        <input type="text" name="nik" id="nik" x-model="nik" maxlength="16" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" placeholder="Masukkan 16 digit NIK">
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tempat Lahir <span class="text-rose-600">*</span></label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" x-model="tempat_lahir" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" placeholder="Kota/Kabupaten Lahir">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tanggal Lahir (DD/MM/YYYY) <span class="text-rose-600">*</span></label>
                        <div class="relative">
                            <input 
                                type="text" 
                                name="tanggal_lahir" 
                                id="tanggal_lahir" 
                                x-ref="tanggalLahirInput"
                                x-model="tanggal_lahir" 
                                placeholder="dd/mm/yyyy (Contoh: 17/08/2001)"
                                class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 bg-white"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>
                        <span class="text-[10px] text-slate-400 mt-1 block">Format: Hari / Bulan / Tahun (dd/mm/yyyy).</span>
                    </div>
                    
                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Jenis Kelamin <span class="text-rose-600">*</span></label>
                        <select name="jenis_kelamin" id="jenis_kelamin" x-model="jenis_kelamin" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 bg-white">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <!-- Jurusan (Dipilih Terlebih Dahulu) -->
                    <div>
                        <label for="jurusan" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Jurusan <span class="text-rose-600">*</span>
                        </label>
                        <select 
                            name="jurusan" 
                            id="jurusan" 
                            x-model="jurusan" 
                            @change="onJurusanChange()"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 bg-white"
                        >
                            <option value="">-- Pilih Jurusan Terlebih Dahulu --</option>
                            <option value="Keperawatan">A. Keperawatan</option>
                            <option value="Kebidanan">B. Kebidanan</option>
                            <option value="Kesehatan Gigi">C. Kesehatan Gigi</option>
                            <option value="Gizi">D. Gizi</option>
                            <option value="Teknologi Laboratorium Medis">E. Teknologi Laboratorium Medis</option>
                            <option value="Kesehatan Lingkungan">F. Kesehatan Lingkungan</option>
                        </select>
                        <span class="text-[10px] text-slate-400 mt-1 block">Pilih jurusan terlebih dahulu untuk membuka pilihan program studi.</span>
                    </div>

                    <!-- Program Studi (Otomatis Filter Sesuai Jurusan) -->
                    <div>
                        <label for="program_studi" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Program Studi <span class="text-rose-600">*</span>
                        </label>
                        <select 
                            name="program_studi" 
                            id="program_studi" 
                            x-ref="prodiSelect"
                            x-model="program_studi" 
                            :disabled="!jurusan"
                            class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 disabled:bg-slate-100 disabled:text-slate-400 disabled:cursor-not-allowed transition-all bg-white"
                        >
                            <option value="" x-text="jurusan ? '-- Pilih Program Studi --' : '-- Pilih Jurusan Terlebih Dahulu --'"></option>
                            <template x-for="prodi in availableProdiList" :key="prodi">
                                <option :value="prodi" x-text="prodi" :selected="prodi === program_studi"></option>
                            </template>
                        </select>
                        <span class="text-[10px] text-slate-400 mt-1 block" x-show="jurusan">
                            Pilihan program studi khusus untuk Jurusan <strong class="text-primary" x-text="jurusan"></strong>.
                        </span>
                    </div>

                    <!-- Tahun Masuk -->
                    <div>
                        <label for="tahun_masuk" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tahun Masuk <span class="text-rose-600">*</span></label>
                        <input type="number" name="tahun_masuk" id="tahun_masuk" x-model="tahun_masuk" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" placeholder="Contoh: 2022">
                    </div>

                    <!-- Tahun Lulus -->
                    <div>
                        <label for="tahun_lulus" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Tahun Lulus <span class="text-rose-600">*</span></label>
                        <input type="number" name="tahun_lulus" id="tahun_lulus" x-model="tahun_lulus" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" placeholder="Contoh: 2025">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Email <span class="text-rose-600">*</span></label>
                        <input type="email" name="email" id="email" x-model="email" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" placeholder="Contoh: alumni@email.com">
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label for="no_hp" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Nomor HP / WhatsApp <span class="text-rose-600">*</span></label>
                        <input type="text" name="no_hp" id="no_hp" x-model="no_hp" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" placeholder="Contoh: 08123456789">
                    </div>
                </div>

                <!-- Alamat Lengkap -->
                <div>
                    <label for="alamat" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Alamat Lengkap <span class="text-rose-600">*</span></label>
                    <textarea name="alamat" id="alamat" x-model="alamat" rows="4" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-xs focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" placeholder="Masukkan alamat lengkap tinggal saat ini"></textarea>
                </div>
            </div>

            <!-- ================= STEP 2: UPLOAD DOKUMEN ================= -->
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-6" style="display: none;">
                <!-- Pas Foto -->
                <div class="border border-dashed border-slate-200 rounded-2xl p-6 bg-slate-50/20">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">
                        Upload Pas Foto Resmi Alumni <span class="text-rose-600">*</span> (Maks. 1 MB, JPG/PNG)
                    </label>
                    
                    <div class="flex items-center gap-6">
                        <!-- Image Preview Box -->
                        <div class="relative w-24 h-32 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                            <template x-if="pasFotoPreview">
                                <img :src="pasFotoPreview" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!pasFotoPreview">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </template>
                        </div>
                        
                        <div class="flex-grow">
                            <label class="inline-flex items-center justify-center px-4 py-2.5 border border-primary/20 text-xs font-bold text-primary bg-primary/5 rounded-xl hover:bg-primary hover:text-white cursor-pointer transition-colors duration-200">
                                Pilih Pas Foto
                                <input type="file" name="pas_foto" class="hidden" accept="image/jpeg,image/png,image/jpg"
                                       @change="
                                            const file = $event.target.files[0];
                                            if (file) {
                                                if (file.size > 1048576) {
                                                    errorMessage = 'Ukuran Pas Foto melebihi 1 MB (' + (file.size/1024/1024).toFixed(2) + ' MB). Maksimal ukuran pas foto adalah 1 MB (1024 KB)!';
                                                    $event.target.value = '';
                                                    pasFotoName = '';
                                                    pasFotoPreview = null;
                                                    return;
                                                }
                                                errorMessage = '';
                                                pasFotoName = file.name;
                                                pasFotoPreview = URL.createObjectURL(file);
                                            }
                                       ">
                            </label>
                            <p class="text-[10px] text-slate-400 mt-2" x-text="pasFotoName ? 'File terpilih: ' + pasFotoName : 'Format file .png, .jpg, .jpeg (Maksimal 1 MB, rasio 3x4 disarankan)'"></p>
                        </div>
                    </div>
                </div>

                <!-- Card Instruksi 5 Berkas Persyaratan (1 File PDF) -->
                <div class="border border-rose-200 rounded-2xl p-6 bg-gradient-to-br from-rose-50/40 via-red-50/20 to-amber-50/30 space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center flex-shrink-0 font-bold shadow-md shadow-rose-600/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-800">Petunjuk Upload 5 Berkas Persyaratan (1 File PDF Gabungan)</h3>
                            <p class="text-xs text-slate-600 mt-1 leading-relaxed">
                                Mahasiswa diwajibkan untuk menggabungkan <strong>5 Berkas Persyaratan</strong> menjadi <strong>1 file format PDF</strong> dengan ukuran <strong>maksimal 1 MB</strong> sebelum diunggah ke sistem.
                            </p>
                        </div>
                    </div>

                    <!-- List 5 File yang Harus Digabung ke dalam 1 File PDF -->
                    <div class="bg-white/90 backdrop-blur-xs rounded-xl p-4 border border-rose-100 space-y-2">
                        <span class="text-[11px] font-bold text-rose-900 uppercase tracking-wider block mb-1">5 Berkas yang Wajib Disatukan ke dalam 1 File PDF:</span>
                        <ul class="text-xs text-slate-700 space-y-1.5 pl-2">
                            <li class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-700 font-extrabold text-[10px] flex items-center justify-center">1</span>
                                <span><strong>Screenshot Tracer Study</strong> (Bukti pengisian Tracer Study Kemenkes/Poltekkes)</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-700 font-extrabold text-[10px] flex items-center justify-center">2</span>
                                <span><strong>Surat Bebas Pustaka</strong> (Surat Keterangan Bebas Pinjam Perpustakaan)</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-700 font-extrabold text-[10px] flex items-center justify-center">3</span>
                                <span><strong>Surat Pernyataan Keabsahan Data Ijazah &amp; PDDIKTI</strong> (Surat bermaterai)</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-700 font-extrabold text-[10px] flex items-center justify-center">4</span>
                                <span><strong>Bukti Pengembalian Toga Bersama Petugas</strong> (Tanda terima / Berita acara toga)</span>
                            </li>
                            <li class="flex items-center gap-2">
                                <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-700 font-extrabold text-[10px] flex items-center justify-center">5</span>
                                <span><strong>Bukti Screenshot Pengisian Bank Ijazah</strong> (Tangkapan layar pengisian data)</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Info Ketentuan Ukuran Max 1 MB & Tips Kompresi -->
                    <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-200 text-amber-900 text-xs flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <strong class="font-extrabold block text-amber-950">⚠️ KETENTUAN UKURAN FILE MAKSIMAL 1 MB</strong>
                            <p class="mt-0.5 leading-relaxed text-[11px]">
                                Pastikan file PDF hasil gabungan tidak melebihi <strong>1 MB (1024 KB)</strong>. Jika ukuran file Anda terlalu besar, Anda dapat mengompresnya secara online (misal: <em>ilovepdf.com/compress_pdf</em>) sebelum diunggah.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Input Box File PDF 5 Berkas Persyaratan -->
                <div class="border border-slate-200 rounded-2xl p-6 bg-white shadow-xs">
                    <label for="berkas_persyaratan_input" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                        File PDF Gabungan 5 Berkas Persyaratan <span class="text-rose-600">*</span>
                    </label>

                    <div class="relative">
                        <input 
                            type="file" 
                            name="berkas_persyaratan" 
                            id="berkas_persyaratan_input" 
                            accept=".pdf,application/pdf" 
                            class="hidden"
                            @change="
                                const file = $event.target.files[0];
                                if (file) {
                                    if (!file.name.toLowerCase().endsWith('.pdf') && file.type !== 'application/pdf') {
                                        errorMessage = 'Format file berkas persyaratan tidak valid! Harus berformat .pdf.';
                                        $event.target.value = '';
                                        berkasPersyaratanName = '';
                                        berkasPersyaratanSize = '';
                                        return;
                                    }
                                    if (file.size > 1048576) {
                                        errorMessage = 'Ukuran file PDF melebihi 1 MB (' + (file.size/1024/1024).toFixed(2) + ' MB). Maksimal ukuran file adalah 1 MB (1024 KB)! Silakan kompres file PDF terlebih dahulu.';
                                        $event.target.value = '';
                                        berkasPersyaratanName = '';
                                        berkasPersyaratanSize = '';
                                        return;
                                    }
                                    errorMessage = '';
                                    berkasPersyaratanName = file.name;
                                    berkasPersyaratanSize = (file.size / 1024).toFixed(1) + ' KB';
                                }
                            "
                        />

                        <!-- State: No File Selected -->
                        <div x-show="!berkasPersyaratanName">
                            <label 
                                for="berkas_persyaratan_input" 
                                class="flex flex-col items-center justify-center p-8 border-2 border-dashed border-slate-300 hover:border-rose-400 rounded-2xl bg-slate-50/50 hover:bg-rose-50/30 transition-all cursor-pointer group text-center"
                            >
                                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform shadow-xs">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-700 group-hover:text-rose-600 transition-colors">
                                    Klik di sini untuk memilih file PDF gabungan 5 berkas
                                </span>
                                <span class="text-[11px] text-slate-400 mt-1">
                                    Format: <strong>.PDF</strong> &bull; Ukuran Maksimal: <strong>1 MB (1024 KB)</strong>
                                </span>
                            </label>
                        </div>

                        <!-- State: File Selected -->
                        <div x-show="berkasPersyaratanName" class="p-4 rounded-2xl border border-emerald-200 bg-emerald-50/40 flex items-center justify-between gap-4" style="display: none;">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-rose-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-sm">
                                    PDF
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-slate-800 truncate block" x-text="berkasPersyaratanName"></span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 flex-shrink-0" x-text="berkasPersyaratanSize"></span>
                                    </div>
                                    <span class="text-[11px] text-emerald-700 flex items-center gap-1 font-medium mt-0.5">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        File PDF siap dikirim (Sesuai batas &le; 1 MB)
                                    </span>
                                </div>
                            </div>

                            <label for="berkas_persyaratan_input" class="flex-shrink-0 px-3 py-1.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold rounded-xl transition-all shadow-xs cursor-pointer">
                                Ganti File
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ================= STEP 3: KONFIRMASI ================= -->
            <div x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-x-4" x-transition:enter-end="opacity-100 transform translate-x-0" class="space-y-6" style="display: none;">
                <x-alert type="warning">
                    <p class="font-bold text-xs mb-0.5">Pernyataan Konfirmasi</p>
                    Harap meneliti kembali seluruh data yang Anda masukkan sebelum menekan tombol Kirim Data. Setelah dikirim, data akan masuk proses verifikasi admin.
                </x-alert>

                <!-- Data Summary List -->
                <div class="bg-slate-50 rounded-2xl border border-slate-100 p-6 space-y-4 text-xs">
                    <h3 class="font-bold text-slate-800 text-sm border-b border-slate-200/60 pb-2">Ringkasan Biodata</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><span class="text-slate-400 block">Nama Lengkap:</span> <strong x-text="nama || '-'"></strong></div>
                        <div><span class="text-slate-400 block">NIM:</span> <strong x-text="nim || '-'"></strong></div>
                        <div><span class="text-slate-400 block">NIK:</span> <strong x-text="nik || '-'"></strong></div>
                        <div><span class="text-slate-400 block">Tempat &amp; Tgl Lahir:</span> <strong x-text="(tempat_lahir || '') + (tanggal_lahir ? ', ' + formatDateDMY(tanggal_lahir) : '') || '-'"></strong></div>
                        <div><span class="text-slate-400 block">Jenis Kelamin:</span> <strong x-text="jenis_kelamin || '-'"></strong></div>
                        <div><span class="text-slate-400 block">Jurusan:</span> <strong x-text="jurusan || '-'"></strong></div>
                        <div><span class="text-slate-400 block">Program Studi:</span> <strong x-text="program_studi || '-'"></strong></div>
                        <div><span class="text-slate-400 block">Tahun Masuk / Lulus:</span> <strong x-text="(tahun_masuk || '') + ' / ' + (tahun_lulus || '') || '-'"></strong></div>
                        <div><span class="text-slate-400 block">Email:</span> <strong x-text="email || '-'"></strong></div>
                        <div><span class="text-slate-400 block">Nomor HP:</span> <strong x-text="no_hp || '-'"></strong></div>
                        <div class="col-span-1 md:col-span-2"><span class="text-slate-400 block">Alamat Lengkap:</span> <strong x-text="alamat || '-'"></strong></div>
                    </div>

                    <h3 class="font-bold text-slate-800 text-sm border-b border-slate-200/60 pb-2 pt-4">Ringkasan Berkas Persyaratan</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-slate-400 block">Pas Foto Resmi:</span> 
                            <strong x-text="pasFotoName || '-'"></strong>
                        </div>
                        <div>
                            <span class="text-slate-400 block">5 Berkas Persyaratan (1 File PDF):</span> 
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded text-[10px] font-extrabold">PDF</span>
                                <strong x-text="berkasPersyaratanName || '-'"></strong>
                                <span class="text-slate-400 text-[11px]" x-show="berkasPersyaratanSize" x-text="'(' + berkasPersyaratanSize + ')'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms & Submit Checkbox -->
                <div class="flex items-start gap-3 p-4 bg-slate-50/20 border border-slate-100 rounded-2xl">
                    <input type="checkbox" id="pernyataan" x-model="pernyataan" class="h-5 w-5 text-primary border-slate-300 rounded focus:ring-primary/20 mt-0.5 cursor-pointer">
                    <label for="pernyataan" class="text-xs text-slate-600 font-medium leading-relaxed cursor-pointer select-none">
                        Saya menyatakan seluruh data yang saya kirimkan adalah benar dan dapat dipertanggungjawabkan keabsahannya.
                    </label>
                </div>
            </div>

            <!-- Footer Buttons -->
            <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                <!-- Back Button -->
                <button 
                    type="button" 
                    x-show="step > 1" 
                    @click="prevStep()" 
                    class="inline-flex items-center justify-center px-5 py-3 border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 rounded-xl transition-all duration-200"
                    style="display: none;"
                >
                    Sebelumnya
                </button>
                <div x-show="step === 1"></div> <!-- Spacer -->

                <!-- Next / Submit Button -->
                <!-- Step 1 & 2: Next Button -->
                <button 
                    type="button" 
                    x-show="step < 3" 
                    @click="nextStep()" 
                    class="inline-flex items-center justify-center px-5 py-3 text-xs font-bold text-white bg-primary hover:bg-primary/95 rounded-xl transition-all duration-200"
                >
                    Selanjutnya
                </button>
                
                <!-- Step 3: Submit Button -->
                <button 
                    type="submit" 
                    x-show="step === 3" 
                    :disabled="isSubmitting"
                    class="inline-flex items-center justify-center px-6 py-3.5 text-xs font-bold text-white bg-success hover:bg-success/95 disabled:bg-slate-400 disabled:cursor-not-allowed rounded-xl shadow-lg shadow-success/20 hover:shadow-xl transition-all duration-200 cursor-pointer"
                    style="display: none;"
                >
                    <template x-if="!isSubmitting">
                        <span class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9-2-9-18-9 18 9-2zm0 0v-8" />
                            </svg>
                            Kirim Data
                        </span>
                    </template>
                    <template x-if="isSubmitting">
                        <span class="flex items-center gap-2">
                            <svg class="animate-spin w-4 h-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mengirim Data...
                        </span>
                    </template>
                </button>
            </div>
        </form>

        <!-- Dynamic Loading Overlay Modal -->
        <div 
            x-show="isSubmitting" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4"
            style="display: none;"
        >
            <div class="bg-white rounded-3xl p-8 max-w-sm w-full text-center shadow-2xl border border-slate-100 transform transition-all">
                <div class="relative w-20 h-20 mx-auto mb-6 flex items-center justify-center">
                    <div class="absolute inset-0 rounded-full border-4 border-primary/20 animate-ping"></div>
                    <div class="w-16 h-16 rounded-full border-4 border-primary border-t-transparent animate-spin"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-7 h-7 text-primary animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                    </div>
                </div>

                <h3 class="text-base font-bold text-slate-800 tracking-tight">Sedang Memproses Data...</h3>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Mohon tunggu sejenak. Berkas dokumen dan biodata Anda sedang diunggah dan dikirimkan ke pihak Admin.
                </p>

                <div class="mt-6 pt-4 border-t border-slate-100 text-[11px] text-slate-400 font-medium space-y-2 text-left">
                    <div class="flex items-center gap-2.5 text-primary font-semibold">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>Mengunggah Berkas PDF & Pas Foto</span>
                    </div>
                    <div class="flex items-center gap-2.5 text-slate-400">
                        <div class="w-4 h-4 rounded-full border border-slate-300 flex items-center justify-center text-[9px] font-bold">✓</div>
                        <span>Menyimpan ke Database Alumni</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
