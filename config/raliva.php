<?php

return [

    'commission' => [
        // Default komisi platform Raliva (persen).
        // Nilai ini di-snapshot ke commissions.persentase saat order dibuat,
        // sehingga perubahan nilai tidak memengaruhi histori transaksi.
        'default_percent' => 5.0,
    ],

];
