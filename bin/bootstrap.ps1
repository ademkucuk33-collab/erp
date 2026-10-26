$ErrorActionPreference = 'Stop'
Set-Location (Resolve-Path (Join-Path $PSScriptRoot '..'))
php -r 'if (PHP_VERSION_ID < 80300) { fwrite(STDERR, "PHP 8.3+ required"); exit(1); }'
if ($LASTEXITCODE -ne 0) { throw 'PHP 8.3+ required' }
if (Test-Path artisan) { composer install; exit $LASTEXITCODE }
$temp = Join-Path ([IO.Path]::GetTempPath()) ('izodekor-' + [guid]::NewGuid())
New-Item -ItemType Directory -Force -Path $temp | Out-Null
try {
  composer create-project laravel/laravel (Join-Path $temp 'base') '^13.0' --no-interaction
  if ($LASTEXITCODE -ne 0) { throw 'Composer create-project failed' }
  Get-ChildItem -Force (Join-Path $temp 'base') | ForEach-Object {
    $target = Join-Path (Get-Location) $_.Name
    if (-not (Test-Path $target)) { Copy-Item $_.FullName -Destination $target -Recurse -Force }
  }
  composer install
  php artisan about
} finally { Remove-Item -Recurse -Force $temp -ErrorAction SilentlyContinue }
