$ErrorActionPreference = 'Stop'
Set-Location (Join-Path $PSScriptRoot '..')
if (!(Get-Command composer -ErrorAction SilentlyContinue)) { throw 'Composer 2 is required' }
if (!(Get-Command php -ErrorAction SilentlyContinue)) { throw 'PHP 8.3+ is required' }
if (Test-Path artisan) { composer install; exit $LASTEXITCODE }
$temp = Join-Path ([System.IO.Path]::GetTempPath()) ('izodekor-erp-' + [guid]::NewGuid())
try {
  composer create-project laravel/laravel $temp '^13.0' --no-interaction
  if ($LASTEXITCODE -ne 0) { throw 'Composer create-project failed' }
  $targetRoot = (Get-Location).Path
  Get-ChildItem -Force -Recurse $temp | ForEach-Object {
    $relativePath = $_.FullName.Substring($temp.Length + 1)
    $destination = Join-Path $targetRoot $relativePath
    if ($_.PSIsContainer) {
      if (!(Test-Path $destination)) { New-Item -ItemType Directory -Path $destination -Force | Out-Null }
    } elseif (!(Test-Path $destination)) {
      $parent = Split-Path $destination -Parent
      if (!(Test-Path $parent)) { New-Item -ItemType Directory -Path $parent -Force | Out-Null }
      Copy-Item $_.FullName $destination
    }
  }
  composer install
  if ($LASTEXITCODE -ne 0) { throw 'Composer install failed' }
  php artisan about
} finally { Remove-Item $temp -Recurse -Force -ErrorAction SilentlyContinue }
