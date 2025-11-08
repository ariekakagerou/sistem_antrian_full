# Test cepat koneksi API
$startTime = Get-Date

try {
    $response = Invoke-RestMethod -Uri "http://localhost:8002/api/loket" -Method GET -TimeoutSec 5
    $endTime = Get-Date
    $duration = ($endTime - $startTime).TotalMilliseconds
    
    Write-Host "✅ API OK - Response time: $duration ms" -ForegroundColor Green
    Write-Host "Jumlah loket: $($response.data.Count)"
} catch {
    Write-Host "❌ API Error: $($_.Exception.Message)" -ForegroundColor Red
}
