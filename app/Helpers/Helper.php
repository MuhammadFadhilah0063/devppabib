<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

function formatDate($date)
{
  if ($date != null) {
    Date::setLocale('id');
    $carbonDate = Carbon::createFromFormat('Y-m-d', $date);
    return $carbonDate->translatedFormat('d F Y');
  } else {
    return "-";
  }
}

function selisihHari($tglExp)
{
  if ($tglExp != null) {
    $tanggalExp = new DateTime($tglExp);
    $tanggalSekarang = new DateTime();
    $selisih = $tanggalSekarang->diff($tanggalExp);

    if ($tanggalExp->format('Y-m-d') == $tanggalSekarang->format('Y-m-d')) {
      return "Expired Hari Ini";
    }

    if ($tanggalExp > $tanggalSekarang) {
      // Hitung selisih hanya jika tanggal kedaluwarsa belum lewat tanggal sekarang
      $selisih = $tanggalSekarang->diff($tanggalExp);

      return $selisih->days + 1 . " Hari";
    } else {
      // Jika tanggal kedaluwarsa sudah lewat tanggal sekarang, kembalikan 0
      return "Expired";
    }
  } else {
    return "-";
  }
}

function selisihHariBerlakuKeExp($tglBerlaku, $tglExp)
{
  if ($tglBerlaku != null && $tglExp != null) {
    $tanggalExp = new DateTime($tglExp);
    $tanggalBerlaku = new DateTime($tglBerlaku);

    if ($tanggalExp > $tanggalBerlaku) {
      $selisih = $tanggalBerlaku->diff($tanggalExp);
      return $selisih->days;
    } else {
      return 0;
    }
  } else {
    return null;
  }
}
