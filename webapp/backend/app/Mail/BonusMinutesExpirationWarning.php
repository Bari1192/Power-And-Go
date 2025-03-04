<?php

namespace App\Mail;

use App\Models\Person;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BonusMinutesExpirationWarning extends Mailable
{
    use Queueable, SerializesModels;

    public $personName;
    public $bonusMinutes;
    public $expirationDate;

    public function __construct(string $personName, float $bonusMinutes, string $expirationDate)
    {
        $this->personName = $personName;
        $this->bonusMinutes = $bonusMinutes;
        $this->expirationDate = $expirationDate;
    }

    public function build()
    {
        $formattedDate = Carbon::parse($this->expirationDate)->format('Y-m-d');

        return $this->subject('Bónusz perceid veszélyben vannak!')
            ->view('emails.bonus-minutes-expiration')
            ->with([
                'name' => $this->personName,
                'bonusMinutes' => $this->bonusMinutes,
                'expirationDate' => $formattedDate
            ]);
    }
}
