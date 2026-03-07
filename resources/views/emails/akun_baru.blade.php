<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px;">
        <h2 style="color: #2563eb;">Halo, {{ $user->name }}!</h2>
        <p>Kami informasikan bahwa akun Anda telah berhasil didaftarkan di <strong>Monitoring Alat PAO</strong> oleh Administrator.</p>
        
        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <p style="margin: 0;"><strong>Identitas Akun:</strong></p>
            <p style="margin: 5px 0 0;"><strong>Role:</strong> {{ strtoupper($user->role) }}</p>
            <p style="margin: 5px 0 0;"><strong>Email:</strong> {{ $user->email }}</p>
        </div>

        <p>Silakan gunakan email di atas untuk masuk ke aplikasi Monitoring Alat PAO.</p>
        <p>Password Anda telah ditentukan secara internal. Jika Anda belum mengetahuinya, silakan hubungi Admin langsung.</p>
        
        <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
        <p style="font-size: 11px; color: #94a3b8;">Email ini dikirim otomatis oleh sistem PT. Pilar Artha Oetama.</p>
    </div>
</body>
</html>