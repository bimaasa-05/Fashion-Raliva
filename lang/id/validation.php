<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Baris Bahasa Validasi
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut berisi pesan error bawaan yang digunakan oleh
    | kelas validator. Beberapa aturan memiliki beberapa versi seperti
    | aturan ukuran. Silakan sesuaikan pesan-pesan ini di sini.
    |
    */

    'accepted' => 'Kolom :attribute harus diterima.',
    'accepted_if' => 'Kolom :attribute harus diterima ketika :other bernilai :value.',
    'active_url' => 'Kolom :attribute harus berupa URL yang valid.',
    'after' => 'Kolom :attribute harus berupa tanggal setelah :date.',
    'after_or_equal' => 'Kolom :attribute harus berupa tanggal setelah atau sama dengan :date.',
    'alpha' => 'Kolom :attribute hanya boleh berisi huruf.',
    'alpha_dash' => 'Kolom :attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
    'alpha_num' => 'Kolom :attribute hanya boleh berisi huruf dan angka.',
    'any_of' => 'Kolom :attribute tidak valid.',
    'array' => 'Kolom :attribute harus berupa array.',
    'ascii' => 'Kolom :attribute hanya boleh berisi karakter alfanumerik dan simbol satu byte.',
    'before' => 'Kolom :attribute harus berupa tanggal sebelum :date.',
    'before_or_equal' => 'Kolom :attribute harus berupa tanggal sebelum atau sama dengan :date.',
    'between' => [
        'array' => 'Kolom :attribute harus memiliki antara :min dan :max item.',
        'file' => 'Kolom :attribute harus berukuran antara :min dan :max kilobyte.',
        'numeric' => 'Kolom :attribute harus antara :min dan :max.',
        'string' => 'Kolom :attribute harus antara :min dan :max karakter.',
    ],
    'boolean' => 'Kolom :attribute harus bernilai true atau false.',
    'can' => 'Kolom :attribute berisi nilai yang tidak diizinkan.',
    'confirmed' => 'Konfirmasi :attribute tidak cocok.',
    'contains' => 'Kolom :attribute tidak berisi nilai yang wajib ada.',
    'current_password' => 'Kata sandi salah.',
    'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
    'date_equals' => 'Kolom :attribute harus berupa tanggal yang sama dengan :date.',
    'date_format' => 'Kolom :attribute harus cocok dengan format :format.',
    'decimal' => 'Kolom :attribute harus memiliki :decimal tempat desimal.',
    'declined' => 'Kolom :attribute harus ditolak.',
    'declined_if' => 'Kolom :attribute harus ditolak ketika :other bernilai :value.',
    'different' => 'Kolom :attribute dan :other harus berbeda.',
    'digits' => 'Kolom :attribute harus :digits digit.',
    'digits_between' => 'Kolom :attribute harus antara :min dan :max digit.',
    'dimensions' => 'Kolom :attribute memiliki dimensi gambar yang tidak valid.',
    'distinct' => 'Kolom :attribute memiliki nilai ganda.',
    'doesnt_contain' => 'Kolom :attribute tidak boleh berisi salah satu dari berikut: :values.',
    'doesnt_end_with' => 'Kolom :attribute tidak boleh diakhiri dengan salah satu dari berikut: :values.',
    'doesnt_start_with' => 'Kolom :attribute tidak boleh diawali dengan salah satu dari berikut: :values.',
    'email' => 'Kolom :attribute harus berupa alamat email yang valid.',
    'encoding' => 'Kolom :attribute harus dikodekan dalam :encoding.',
    'ends_with' => 'Kolom :attribute harus diakhiri dengan salah satu dari berikut: :values.',
    'enum' => 'Nilai :attribute yang dipilih tidak valid.',
    'exists' => 'Nilai :attribute yang dipilih tidak valid.',
    'extensions' => 'Kolom :attribute harus memiliki salah satu ekstensi berikut: :values.',
    'file' => 'Kolom :attribute harus berupa berkas.',
    'filled' => 'Kolom :attribute harus memiliki nilai.',
    'gt' => [
        'array' => 'Kolom :attribute harus memiliki lebih dari :value item.',
        'file' => 'Kolom :attribute harus lebih besar dari :value kilobyte.',
        'numeric' => 'Kolom :attribute harus lebih besar dari :value.',
        'string' => 'Kolom :attribute harus lebih panjang dari :value karakter.',
    ],
    'gte' => [
        'array' => 'Kolom :attribute harus memiliki :value item atau lebih.',
        'file' => 'Kolom :attribute harus lebih besar atau sama dengan :value kilobyte.',
        'numeric' => 'Kolom :attribute harus lebih besar atau sama dengan :value.',
        'string' => 'Kolom :attribute harus lebih panjang atau sama dengan :value karakter.',
    ],
    'hex_color' => 'Kolom :attribute harus berupa warna heksadesimal yang valid.',
    'image' => 'Kolom :attribute harus berupa gambar.',
    'in' => 'Nilai :attribute yang dipilih tidak valid.',
    'in_array' => 'Kolom :attribute harus ada di :other.',
    'in_array_keys' => 'Kolom :attribute harus berisi setidaknya salah satu kunci berikut: :values.',
    'integer' => 'Kolom :attribute harus berupa bilangan bulat.',
    'ip' => 'Kolom :attribute harus berupa alamat IP yang valid.',
    'ipv4' => 'Kolom :attribute harus berupa alamat IPv4 yang valid.',
    'ipv6' => 'Kolom :attribute harus berupa alamat IPv6 yang valid.',
    'json' => 'Kolom :attribute harus berupa string JSON yang valid.',
    'list' => 'Kolom :attribute harus berupa daftar.',
    'lowercase' => 'Kolom :attribute harus berupa huruf kecil.',
    'lt' => [
        'array' => 'Kolom :attribute harus memiliki kurang dari :value item.',
        'file' => 'Kolom :attribute harus lebih kecil dari :value kilobyte.',
        'numeric' => 'Kolom :attribute harus lebih kecil dari :value.',
        'string' => 'Kolom :attribute harus lebih pendek dari :value karakter.',
    ],
    'lte' => [
        'array' => 'Kolom :attribute tidak boleh memiliki lebih dari :value item.',
        'file' => 'Kolom :attribute harus lebih kecil atau sama dengan :value kilobyte.',
        'numeric' => 'Kolom :attribute harus lebih kecil atau sama dengan :value.',
        'string' => 'Kolom :attribute harus lebih pendek atau sama dengan :value karakter.',
    ],
    'mac_address' => 'Kolom :attribute harus berupa alamat MAC yang valid.',
    'max' => [
        'array' => 'Kolom :attribute tidak boleh memiliki lebih dari :max item.',
        'file' => 'Kolom :attribute tidak boleh lebih besar dari :max kilobyte.',
        'numeric' => 'Kolom :attribute tidak boleh lebih besar dari :max.',
        'string' => 'Kolom :attribute tidak boleh lebih panjang dari :max karakter.',
    ],
    'max_digits' => 'Kolom :attribute tidak boleh memiliki lebih dari :max digit.',
    'mimes' => 'Kolom :attribute harus berupa berkas bertipe: :values.',
    'mimetypes' => 'Kolom :attribute harus berupa berkas bertipe: :values.',
    'min' => [
        'array' => 'Kolom :attribute harus memiliki setidaknya :min item.',
        'file' => 'Kolom :attribute harus berukuran setidaknya :min kilobyte.',
        'numeric' => 'Kolom :attribute harus setidaknya :min.',
        'string' => 'Kolom :attribute harus setidaknya :min karakter.',
    ],
    'min_digits' => 'Kolom :attribute harus memiliki setidaknya :min digit.',
    'missing' => 'Kolom :attribute tidak boleh ada.',
    'missing_if' => 'Kolom :attribute tidak boleh ada ketika :other bernilai :value.',
    'missing_unless' => 'Kolom :attribute tidak boleh ada kecuali :other bernilai :value.',
    'missing_with' => 'Kolom :attribute tidak boleh ada ketika :values tersedia.',
    'missing_with_all' => 'Kolom :attribute tidak boleh ada ketika :values tersedia.',
    'multiple_of' => 'Kolom :attribute harus merupakan kelipatan dari :value.',
    'not_in' => 'Nilai :attribute yang dipilih tidak valid.',
    'not_regex' => 'Format kolom :attribute tidak valid.',
    'numeric' => 'Kolom :attribute harus berupa angka.',
    'password' => [
        'letters' => 'Kolom :attribute harus berisi setidaknya satu huruf.',
        'mixed' => 'Kolom :attribute harus berisi setidaknya satu huruf besar dan satu huruf kecil.',
        'numbers' => 'Kolom :attribute harus berisi setidaknya satu angka.',
        'symbols' => 'Kolom :attribute harus berisi setidaknya satu simbol.',
        'uncompromised' => ':attribute yang diberikan muncul dalam kebocoran data. Silakan pilih :attribute yang berbeda.',
    ],
    'present' => 'Kolom :attribute harus ada.',
    'present_if' => 'Kolom :attribute harus ada ketika :other bernilai :value.',
    'present_unless' => 'Kolom :attribute harus ada kecuali :other bernilai :value.',
    'present_with' => 'Kolom :attribute harus ada ketika :values tersedia.',
    'present_with_all' => 'Kolom :attribute harus ada ketika :values tersedia.',
    'prohibited' => 'Kolom :attribute dilarang diisi.',
    'prohibited_if' => 'Kolom :attribute dilarang diisi ketika :other bernilai :value.',
    'prohibited_if_accepted' => 'Kolom :attribute dilarang diisi ketika :other diterima.',
    'prohibited_if_declined' => 'Kolom :attribute dilarang diisi ketika :other ditolak.',
    'prohibited_unless' => 'Kolom :attribute dilarang diisi kecuali :other bernilai salah satu dari :values.',
    'prohibits' => 'Kolom :attribute melarang :other untuk diisi.',
    'regex' => 'Format kolom :attribute tidak valid.',
    'required' => 'Kolom :attribute wajib diisi.',
    'required_array_keys' => 'Kolom :attribute harus berisi entri untuk: :values.',
    'required_if' => 'Kolom :attribute wajib diisi ketika :other bernilai :value.',
    'required_if_accepted' => 'Kolom :attribute wajib diisi ketika :other diterima.',
    'required_if_declined' => 'Kolom :attribute wajib diisi ketika :other ditolak.',
    'required_unless' => 'Kolom :attribute wajib diisi kecuali :other bernilai salah satu dari :values.',
    'required_with' => 'Kolom :attribute wajib diisi ketika :values tersedia.',
    'required_with_all' => 'Kolom :attribute wajib diisi ketika :values tersedia.',
    'required_without' => 'Kolom :attribute wajib diisi ketika :values tidak tersedia.',
    'required_without_all' => 'Kolom :attribute wajib diisi ketika tidak ada satupun dari :values yang tersedia.',
    'same' => 'Kolom :attribute harus sama dengan :other.',
    'size' => [
        'array' => 'Kolom :attribute harus berisi :size item.',
        'file' => 'Kolom :attribute harus berukuran :size kilobyte.',
        'numeric' => 'Kolom :attribute harus :size.',
        'string' => 'Kolom :attribute harus :size karakter.',
    ],
    'starts_with' => 'Kolom :attribute harus diawali dengan salah satu dari berikut: :values.',
    'string' => 'Kolom :attribute harus berupa teks.',
    'timezone' => 'Kolom :attribute harus berupa zona waktu yang valid.',
    'unique' => ':attribute sudah digunakan.',
    'uploaded' => ':attribute gagal diunggah.',
    'uppercase' => 'Kolom :attribute harus berupa huruf besar.',
    'url' => 'Kolom :attribute harus berupa URL yang valid.',
    'ulid' => 'Kolom :attribute harus berupa ULID yang valid.',
    'uuid' => 'Kolom :attribute harus berupa UUID yang valid.',

    /*
    |--------------------------------------------------------------------------
    | Pesan Validasi Kustom
    |--------------------------------------------------------------------------
    |
    | Di sini Anda dapat menentukan pesan validasi kustom untuk atribut
    | menggunakan konvensi "attribute.rule" untuk menamai baris. Ini
    | memudahkan penentuan baris bahasa kustom untuk aturan atribut
    | tertentu.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'pesan-kustom',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atribut Validasi Kustom
    |--------------------------------------------------------------------------
    |
    | Baris bahasa berikut digunakan untuk mengganti placeholder atribut
    | dengan sesuatu yang lebih mudah dibaca seperti "Alamat Email"
    | alih-alih "email". Ini membantu membuat pesan lebih ekspresif.
    |
    */

    'attributes' => [],

];
