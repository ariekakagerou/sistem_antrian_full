$body = @{
    loket_id = 1
    nama_pasien = "Test Patient"
    nik = "1234567890123456"
    jenis_kelamin = "Laki-laki"
    tanggal_lahir = "1990-01-01"
    nomor_hp = "08123456789"
    alamat = "Test Address"
    keluhan = "Test Complaint"
    poli_tujuan = "Poli Umum"
} | ConvertTo-Json

try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000/api/antrian" -Method POST -Body $body -ContentType "application/json"
    Write-Host "Response Status:" $response.StatusCode
    Write-Host "Response Content:" $response.Content
} catch {
    Write-Host "Error:" $_.Exception.Message
    Write-Host "Response:" $_.Exception.Response.GetResponseStream()
}
