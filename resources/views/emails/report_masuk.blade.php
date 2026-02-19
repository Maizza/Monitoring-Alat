<h2>Halo Mekanik!</h2>
<p>Ada laporan masuk untuk alat: <strong>{{ $comment->alat->nama_alat ?? 'Alat Tidak Diketahui' }}</strong></p>
<p>Keluhan: {{ $comment->content ?? 'Tidak ada deskripsi' }}</p>
<p>Kode Laporan: {{ $comment->unique_code }}</p>