<h3>Halo {{ $comment->user->name }},</h3>
<p>Laporan kerusakan alat <strong>{{ $comment->alat->nama_alat }}</strong> sudah diperbarui oleh Mekanik.</p>
<p><strong>Status Saat Ini:</strong> {{ $maintenance->status_kerja }}</p>
<p><strong>Catatan Mekanik:</strong> {{ $maintenance->content }}</p>
<br>
<p>Terima kasih,<br>Tim Monitoring Alat</p>