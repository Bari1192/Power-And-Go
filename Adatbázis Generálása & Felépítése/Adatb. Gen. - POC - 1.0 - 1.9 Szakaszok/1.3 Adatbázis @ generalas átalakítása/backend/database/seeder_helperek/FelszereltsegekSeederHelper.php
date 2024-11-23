<?php

namespace Database\SeederHelperek;
use Illuminate\Support\Facades\DB;
class FelszereltsegekSeederHelper
{
    public function generateFelszerelesek(int $katBesorolas): array
    {
        $felszerelesek = [
            'tolatokamera' => 0,
            'savtarto' => 0,
            'tempomat' => 0,
            'tolatoradar' => 0,
            'multif_kormany' => 0,
        ];

        switch ($katBesorolas) {
            case 1:
                if (random_int(1, 100) <= 50) {
                    $felszerelesek = array_map(fn() => 1, $felszerelesek);
                } else {
                    $felszerelesek['tolatoradar'] = 1;
                    $felszerelesek['tempomat'] = 1;
                }
                break;

            case 2:
                if (random_int(1, 100) <= 70) {
                    $felszerelesek['tolatoradar'] = 1;
                    $felszerelesek['tempomat'] = 1;
                    $felszerelesek['multif_kormany'] = 1;
                }
                break;

            case 3:
                if (random_int(1, 100) <= 60) {
                    $felszerelesek['savtarto'] = 1;
                }
                break;

            case 4:
                $felszerelesek['tolatokamera'] = 1;
                $felszerelesek['tempomat'] = 1;
                break;

            case 5:
                $felszerelesek = array_map(fn() => 1, $felszerelesek);
                break;
        }

        return $felszerelesek;
    }
}
