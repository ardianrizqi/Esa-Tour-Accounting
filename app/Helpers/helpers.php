<?php
use App\Models\Bank;

function calculate_income($data, $nominal, $is_deduction = false)
{
    // dd('masok');
    if ($is_deduction) {
        // dd('masok');
        $data->update([
            'income'    => $data->income - $nominal,
            'balance'   => $data->balance - $nominal
        ]);
    }else{
        $data->update([
            'income'    => $data->income + $nominal,
            'balance'   => $data->balance + $nominal
        ]);
    }

    // dd('maosk');
    return $data;
}

function calculate_receivables($data, $nominal, $is_increase = false)
{
    if ($is_increase) {
        $data->update([
            'receivables' => $data->receivables + $nominal,
        ]);

    }else{
        $data->update([
            'receivables' => $data->receivables - $nominal,
        ]);
    }

    return $data;
}